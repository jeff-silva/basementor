<?php

/* Download helpers */
add_action('admin_menu', function() {
	add_submenu_page('tools.php', 'WP Helpers', 'WP Helpers', 'manage_options', 'wp-helpers', function() {
		$data = new stdClass;
		$data->repo = ['loading' => false, 'files' => []];
		$data->files = array_map(function($file) {
			return pathinfo($file, PATHINFO_BASENAME);
		}, glob(__DIR__ .'/*'));

		?>
		<div id="wp-helpers">
			<table>
				<colgroup><col width="*"><col width="50px"></colgroup>
				<thead><tr><th style="text-align:left;">File</th><th>Actions</th></tr></thead>
				<tbody>
					<tr v-for="f in repo.files" :key="f.name" :style="{opacity:(f.loading? .5: 1)}">
						<td>{{ f.name }}</td>
						<td style="text-align:right;">
							<a href="javascript:;" class="fa fa-fw fa-remove" title="Deletar" @click="repoRemove(f);" v-if="f.downloaded"></a>
							<a href="javascript:;" class="fa fa-fw fa-download" title="Instalar" @click="repoDownload(f);"></a>
						</td>
					</tr>
					<tr v-if="repo.loading">
						<td style="text-align:center;" colspan="2">Carregando...</td>
					</tr>
				</tbody>
			</table>
		</div>

		<style>
		#wp-helpers table {width:100%;}
		#wp-helpers table td, #wp-helpers table th {padding:5px;}
		#wp-helpers table tr:hover > * {background:#ffd; cursor:pointer;}
		#wp-helpers table, #wp-helpers a, #wp-helpers a:active, #wp-helpers a:link {color:#444;}
		</style>

		<script>
		var $=jQuery;

		new Vue({
			el: "#wp-helpers",
			data: <?php echo json_encode($data); ?>,

			methods: {
				repoSearch() {
					this.repo.loading = true;
					$.get('<?php echo Hp_Helpers_Api::url('repo_search'); ?>', (resp) => {
						this.repo.loading = false;
						this.repo.files = resp;
					}, 'json');
				},

				repoDownload(f) {
					f.loading = true;
					$.get('<?php echo Hp_Helpers_Api::url('repo_download'); ?>', {name:f.name}, (resp) => {
						this.repoSearch();
					}, 'json');
				},

				repoRemove(f) {
					if (!confirm('Deseja deletar?')) return;
					f.loading = true;
					$.get('<?php echo Hp_Helpers_Api::url('repo_delete'); ?>', {name:f.name}, (resp) => {
						this.repoSearch();
					}, 'json');
				},
			},

			mounted() {
				this.repoSearch();
			},
		});
		</script>
		<?php
	});
});

if (! class_exists('Hp_Helpers_Api')) {
	class Hp_Helpers_Api
	{
		static function url($method) {
			return site_url("/wp-json/wp-helpers/v1/{$method}");
		}
	
		static function repo_search() {
			$input = (object) $_GET;
	
			$c = curl_init();
			curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($c, CURLOPT_HTTPHEADER, ['Accept: application/json', 'Content-Type: application/json', 'User-Agent: Awesome-Octocat-App']);
			curl_setopt($c, CURLOPT_URL, 'https://api.github.com/repos/jeff-silva/wp-helpers/contents');
			$data = json_decode(curl_exec($c));
			curl_close($c);
	
			$return = [];
			if (is_array($data)) {
				foreach($data as $item) {
					if (in_array($item->name, ['wp-helpers.php', 'README.md'])) continue;
					$item->downloaded = file_exists(__DIR__ . "/{$item->name}");
					$return[] = $item;
				}
			}
	
			return $return;
		}
	
		static function repo_download() {
			$input = (object) $_GET;
			$content = file_get_contents("https://raw.githubusercontent.com/jeff-silva/wp-helpers/master/{$input->name}");
			return file_put_contents(__DIR__ ."/{$input->name}", $content);
		}
	
		static function repo_delete() {
			$input = (object) $_GET;
			return unlink(__DIR__ ."/{$input->name}");
		}
	}
}


add_action('rest_api_init', function() {
	foreach(get_class_methods('Hp_Helpers_Api') as $method) {
		register_rest_route('wp-helpers/v1', $method, [
			'methods' => 'GET',
			'callback' => function() use($method) {
				try { return call_user_func(['Hp_Helpers_Api', $method]); }
				catch(\Exception $e) { return ['error' => $e->getMessage()]; }
				return ['error'=>'Undefined error'];
			},
		]);
	}
});

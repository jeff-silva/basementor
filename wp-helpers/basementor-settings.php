<?php

add_action('admin_menu', function() {
	add_menu_page('Theme Settings', 'Theme Settings', 'manage_options', 'basementor-settings-default', function() { ?>
	<br><div>Configurações do tema</div>
	<?php }, 'dashicons-admin-generic', 10);



	add_submenu_page('basementor-settings-default', 'Ajuda', 'Ajuda', 'manage_options', 'basementor-settings-help', function() {

		$_data_item = function($title, $content) {
			global $basementor_settings_default_items_index;
			$basementor_settings_default_items_index++;

			ob_start();
			call_user_func($content);
			$content = ob_get_clean();

			return [
				'id' => sanitize_title($title)."-{$basementor_settings_default_items_index}",
				'title' => $title,
				'content' => $content,
			];
		};

		$data = new \stdClass;
		$data->categories = [];
		$data->items = [];

		$data->items[] = $_data_item('Como encontar faixa de CEP?', function() { ?>
		Acesse a <a href="http://www.buscacep.correios.com.br/sistemas/buscacep/buscaFaixaCep.cfm" target="_blank">busca de faixa de CEP dos correios</a>
		<?php });

		$data->items[] = $_data_item('Mensagem "Não existem métodos de entrega disponíveis"', function() { ?>
		Talvez os correios tenham mudado NOVAMENTE seus códigos de entrega. <br>Mas isso pode ser configurado acessando as
		<a href="<?php echo admin_url('/admin.php?page=wc-settings&tab=shipping'); ?>">configurações de entrega</a> e alterando seus respectivos códigos.
		<br><a href="https://wordpress.org/support/topic/solucao-nao-existe-nenhum-metodo-de-entrega-disponivel/" target="_blank">Fonte</a>
		<?php });

		$data->item = $data->items[0];
		?><br>

		<div id="basementor-settings">
			<div class="accordion" id="accordionExample">
				<div class="card" v-for="i in items">
					<div class="card-header">
						<h2 class="mb-0">
							<button class="btn btn-link" type="button" @click="item=i;">
								{{ i.title }}
							</button>
						</h2>
					</div>

					<div class="collapse" :class="{'show':i.id==item.id}">
						<div class="card-body" v-html="i.content"></div>
					</div>
				</div>
			</div>
		</div>

		<script>new Vue({
			el: "#basementor-settings",
			data: <?php echo json_encode($data); ?>,
		});</script>
		<?php
	});



	add_submenu_page('basementor-settings-default', 'Update', 'Update', 'manage_options', 'basementor-settings-update', function() {
		echo '<br><a href="?page=basementor&basementor-update" class="btn btn-success">Update</a>';
	});
});


if (isset($_GET['basementor-update'])) {
	add_action('init', function() {
		$data = new stdClass;
		$data->themes_dir = realpath(__DIR__ . '/../../');
		$data->zip_filename = "{$data->themes_dir}/basementor-master.zip";
		$data->theme_delete = realpath(__DIR__ . '/../');
		
		if (file_exists($data->zip_filename)) { unlink($data->zip_filename); }
		$contents = file_get_contents('https://github.com/jeff-silva/basementor/archive/master.zip');
		file_put_contents($data->zip_filename, $contents);
		$data->delete = \Basementor\Basementor::file_delete($data->theme_delete);

		$zip = new ZipArchive;
		if ($zip->open($data->zip_filename) === TRUE) {
			$zip->extractTo($data->themes_dir);
			$zip->close();
		}

		wp_redirect($_SERVER['HTTP_REFERER']);
	});
}

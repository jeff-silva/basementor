<?php

\Basementor\Basementor::action('basementor-settings-woocommerce-template-save', function($post) {
	if ($post->delete) {
		unlink($post->file);
		wp_redirect($_SERVER['HTTP_REFERER']);
		return;
	}

	$info = pathinfo($post->file);

	if (! file_exists($info['dirname'])) {
		mkdir($info['dirname'], 0755, true);
	}

	file_put_contents($post->file, stripslashes($post->content));
	wp_redirect($_SERVER['HTTP_REFERER']);
});



add_action('admin_bar_menu', function($admin_bar) {
	$menu_id = 'basementor';
	$admin_bar->add_menu([
		'id'    => 'basementor',
		'title' => 'Tema',
		'href'  => 'javascript:;',
	]);

	$admin_bar->add_menu([
		'parent' => $menu_id,
		'id'    => 'basementor-settings',
		'title' => 'Configurações',
		'href'  => admin_url('admin.php?page=basementor-settings'),
	]);

	$admin_bar->add_menu([
		'parent' => $menu_id,
		'id'    => 'basementor-settings-bootstrap',
		'title' => 'Bootstrap',
		'href'  => admin_url('admin.php?page=basementor-settings&tab=bootstrap'),
	]);

	if (BASEMENTOR_WOOCOMMERCE) {
		$admin_bar->add_menu([
			'parent' => $menu_id,
			'id'    => 'basementor-settings-wc-templates',
			'title' => 'Woocommerce Templates',
			'href'  => admin_url('admin.php?page=basementor-settings&tab=wc-templates'),
		]);
	}

	$admin_bar->add_menu([
		'parent' => $menu_id,
		'id'    => 'basementor-settings-help',
		'title' => 'Help',
		'href'  => admin_url('admin.php?page=basementor-settings&tab=help'),
	]);

	$admin_bar->add_menu([
		'parent' => $menu_id,
		'id'    => 'basementor-settings-update',
		'title' => 'Update',
		'href'  => admin_url('admin.php?page=basementor-settings&tab=update'),
	]);

	$admin_bar->add_menu([
		'parent' => $menu_id,
		'id'    => 'basementor-autoload',
		'title' => 'Refresh autoload',
		'href'  => '?basementor-autoload',
	]);

	if (BASEMENTOR_PARENT == BASEMENTOR_CHILD) {
		$admin_bar->add_menu([
			'parent' => $menu_id,
			'id'    => 'basementor-theme-child',
			'title' => 'Criar tema filho',
			'href'  => '?basementor-theme-child',
		]);
	}
}, 100);



add_action('admin_menu', function() {
	add_submenu_page('_doesnt_exist', 'Configurações', '', 'manage_options', 'basementor-settings', function() {
		$data = new \stdClass;
		$data->id = uniqid('basementor-settings-');
		$data->tab = isset($_GET['tab'])? $_GET['tab']: null;
		$data->settings = \Basementor\Basementor::settings();

		echo '<br>';

		/* Bootstrap */
		if ($data->tab=='bootstrap') {

			$data->prefix = 'primary';
			$data->prefixes = [
				'primary' => 'Primary',
				'secondary' => 'Secondary',
				'success' => 'Success',
				'danger' => 'Danger',
				'warning' => 'Warning',
				'info' => 'Info',
				'facebook' => 'Facebook',
				'twitter' => 'Twitter',
				'linkedin' => 'Linkedin',
				'skype' => 'Skype',
				'dropbox' => 'Dropbox',
				'wordpress' => 'Wordpress',
				'vimeo' => 'Vimeo',
				'vk' => 'Vk',
				'tumblr' => 'Tumblr',
				'yahoo' => 'Yahoo',
				'pinterest' => 'Pinterest',
				'youtube' => 'Youtube',
				'reddit' => 'Reddit',
				'quora' => 'Quora',
				'soundcloud' => 'Soundcloud',
				'whatsapp' => 'Whatsapp',
				'instagram' => 'Instagram',
			];

			?>
			<div id="<?php echo $data->id; ?>">
				<select class="form-control" v-model="prefix">
					<option :value="pref" v-for="(prefName, pref) in prefixes">{{ prefName }}</option>
				</select><br>

				<nav class="navbar navbar-expand-lg navbar-dark mb-2" :class="`bg-${prefix}`"><a href="#" class="navbar-brand">{{ prefix }}</a> <button type="button" data-toggle="collapse" data-target="#navbar-color-primary" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler"><span class="navbar-toggler-icon"></span></button> <div id="navbar-color-primary" class="collapse navbar-collapse"><ul class="navbar-nav mr-auto"><li class="nav-item active"><a href="#" class="nav-link">Home <span class="sr-only">(current)</span></a></li> <li class="nav-item"><a href="#" class="nav-link">Features</a></li> <li class="nav-item"><a href="#" class="nav-link">Pricing</a></li> <li class="nav-item"><a href="#" class="nav-link">About</a></li></ul> 
					<form class="input-group form-control border border-secondary" style="max-width:300px;"><input type="text" placeholder="Search" class="form-control mr-sm-2"><div class="input-group-btn"><button type="button" class="btn btn-secondary"><i class="fa fa-fw fa-search"></i></button></div></form>
				</div></nav>

				<div class="alert alert-dismissible mb-2" :class="`alert-${prefix}`"><button type="button" data-dismiss="alert" class="close">×</button> <h4 class="alert-heading">Warning!</h4> <p class="mb-0">Lorem ipsum dolor sit amet, odit magni cum qui doloribus  <a href="#" class="alert-link">vel scelerisque nisl consectetur et</a>.</p></div>

				<div class="mp-2 p-3">
					<span class="badge" :class="`badge-${prefix}`">{{ prefix }}</span>
					<span class="badge badge-pill" :class="`badge-${prefix}`">{{ prefix }}</span>
				</div>

				<div class="row mb-2">
					<div class="col"><button type="button" class="btn btn-block" :class="`btn-${prefix}`">Test</button></div>
					<div class="col"><button type="button" class="btn btn-block" :class="`btn-outline-${prefix}`">Test</button></div>
				</div>

				<div class="row mb-2">
					<div class="col">
						<div class="progress"><div role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" class="progress-bar" :class="`bg-${prefix}`" style="width: 25%;"></div></div>
					</div>
					<div class="col">
						<div class="progress"><div role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-striped" :class="`bg-${prefix}`" style="width: 10%;"></div></div>
					</div>
					<div class="col">
						<div class="progress"><div role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-striped progress-bar-animated" :class="`bg-${prefix}`" style="width: 75%;"></div></div>
					</div>
				</div>

				<div class="row mb-2">
					<div class="col">
						<div class="card text-white" :class="`bg-${prefix}`"><div class="card-header">Header</div> <div class="card-body"><h4 class="card-title">Primary card title</h4> <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p></div></div>
					</div>
					<div class="col">
						<div class="card" :class="`border-${prefix}`"><div class="card-header">Header</div> <div class="card-body"><h4 class="card-title">Primary card title</h4> <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p></div></div>
					</div>
				</div>

				<div class="d-flex">
					<div class="mx-auto">
						<nav aria-label="Page navigation example">
							<ul class="pagination">
								<li class="page-item"><a class="page-link " href="https://zpet.com.br/loja/page/1/">1</a></li>
								<li class="page-item"><a class="page-link bg-primary text-light" href="https://zpet.com.br/loja/page/2/">2</a></li>
								<li class="page-item"><a class="page-link " href="https://zpet.com.br/loja/page/3/">3</a></li>
								<li class="page-item"><a class="page-link " href="https://zpet.com.br/loja/page/4/">4</a></li>
								<li class="page-item"><a class="page-link " href="https://zpet.com.br/loja/page/5/">5</a></li>
							</ul>
						</nav>
					</div>
				</div>
			</div>

			<script>new Vue({
				el: "#<?php echo $data->id; ?>",
				data: <?php echo json_encode($data); ?>,
			});</script>
			<?php
		}

		
		/* Woocommerce Templates */
		else if ($data->tab=='wc-templates') {
			if (! function_exists('basementor_settings_woocommerce_files')) {
				function basementor_settings_woocommerce_files($dir=null, $files=[], $level=0) {
					if ($dir===null) {
						$dir = get_template_directory() .'/woocommerce';
					}

					foreach(glob($dir .'/*') as $file) {
						if (is_dir($file)) {
							$files = basementor_settings_woocommerce_files($file, $files, $level+1);
							continue;
						}
						$info = pathinfo($file);
						$info['woo_file'] = str_replace(realpath(__DIR__ .'/..').'/', '', $file);
						$info['id'] = md5($info['woo_file']);
						$info['parent_file'] = $file;
						$info['theme_file'] = get_stylesheet_directory() ."/{$info['woo_file']}";
						$info['theme_file_exists'] = file_exists($info['theme_file']);
						$info['edit'] = '?'. http_build_query(array_merge($_GET, ['file'=>$info['id']]));
						$info['content'] = '';
						$info['content_edit'] = false;

						if (isset($_GET['file']) AND $_GET['file']==$info['id']) {
							$info['content_edit'] = true;
							if ($info['theme_file_exists']) {
								$info['content'] = file_get_contents($info['theme_file']);
							}
							else {
								$info['content'] = file_get_contents($info['parent_file']);
							}
						}

						$files[ $info['id'] ] = $info;
					}

					return $files;
				};
			}

			$data->files = basementor_settings_woocommerce_files();
			$data->vueCodemirrorOptions = [
				'tabSize' => 4,
				'mode' => 'application/x-httpd-php',
				'theme' => 'ambiance',
				'lineNumbers' => true,
				'line' => 'true',
				'indentUnit' => 4,
				'indentWithTabs' => true,
			];

			?><br>
			<div id="basementor-woocommerce-templates">
				<div class="row">
					<div class="col-4">
						<div class="list-group">
							<a :href="f.edit" class="list-group-item" v-for="f in files" :key="f.id">
								{{ f.woo_file }}
							</a>
						</div>
						<!-- <pre>$data: {{ $data }}</pre> -->
					</div>
					<div class="col-8">
						<div v-for="f in files" :key="f.id" v-if="f.content_edit">
							<form action="<?php echo \Basementor\Basementor::action('basementor-settings-woocommerce-template-save'); ?>" method="post">
								<div class="card m-0">
									<div class="card-header">
										<div class="text-uppercase font-weight-bold">{{ f.basename }}</div>
										<div class="text-muted">{{ f.woo_file }}</div>
									</div>
									<div class="card-body p-0">
										<codemirror :options="vueCodemirrorOptions" v-model="f.content" name="content"></codemirror>
									</div>
									<div class="card-footer text-right p-2">
										<input type="hidden" name="file" v-model="f.theme_file">

										<button type="submit" class="btn btn-danger pull-left" name="delete" value="1">
											<i class="fa fa-fw fa-remove"></i> Resetar
										</button>

										<button type="submit" class="btn btn-primary">
											<i class="fa fa-fw fa-save"></i> Salvar
										</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>

			<style>.CodeMirror {min-height:500px;}</style>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.50.2/codemirror.min.js"></script>
			<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.50.2/codemirror.min.css">
			<script src="https://cdn.jsdelivr.net/npm/vue-codemirror@4.0.6/dist/vue-codemirror.js"></script>
			<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.50.2/theme/<?php echo $data->vueCodemirrorOptions['theme']; ?>.min.css">
			<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.50.2/mode/htmlmixed/htmlmixed.js"></script>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.50.2/mode/xml/xml.js"></script>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.50.2/mode/javascript/javascript.js"></script>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.50.2/mode/css/css.js"></script>
			<script>
			Vue.use(window.VueCodemirror, <?php echo json_encode($data->vueCodemirrorOptions); ?>);
			new Vue({
				el: "#basementor-woocommerce-templates",
				data: <?php echo json_encode($data); ?>,
			});</script>
			<?php
		}


		/* Help */
		else if ($data->tab=='help') {
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
		}


		/* Basementor update */
		else if ($data->tab=='update') {
			?><a href="?page=basementor&basementor-update" class="btn btn-success">Update</a><?php
		}


		/* Main */
		else {
			?>Main<?php
		}
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

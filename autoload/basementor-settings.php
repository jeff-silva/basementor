<?php

\Basementor\Basementor::action('basementor-settings-save', function($post) {
	$post->settings = stripslashes($post->settings);
	update_option('basementor-settings', $post->settings);
	wp_redirect($_SERVER['HTTP_REFERER']);
	return $post;
});



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
	else {
		$admin_bar->add_menu([
			'parent' => $menu_id,
			'id'    => 'basementor-theme-child',
			'title' => 'Download child theme',
			'href'  => '?basementor-theme-child-download',
		]);
	}
}, 100);



if (isset($_GET['basementor-theme-child-download'])) {
	add_action('init', function() {
		if ($pcl = realpath(ABSPATH . 'wp-admin/includes/class-pclzip.php')) {
			include $pcl;
			$pcl = new PclZip;
			dd($pcl);
		}

		die;
	});
}



add_action('admin_menu', function() {
	add_submenu_page('_doesnt_exist', 'Configurações', '', 'manage_options', 'basementor-settings', function() {
		$data = new \stdClass;
		$data->id = uniqid('basementor-settings-');
		$data->tab = isset($_GET['tab'])? $_GET['tab']: null;
		$data->settingsDefault = \Basementor\Basementor::settingsDefault();
		$data->settings = \Basementor\Basementor::settings();

		echo '<br>';

		/* Bootstrap */
		if ($data->tab=='bootstrap') {

			$data->prefix = 'primary';
			$data->prefixes = [
				['id'=>'primary', 'label'=>'Primary'],
				['id'=>'secondary', 'label'=>'Secondary'],
				['id'=>'success', 'label'=>'Success'],
				['id'=>'danger', 'label'=>'Danger'],
				['id'=>'warning', 'label'=>'Warning'],
				['id'=>'info', 'label'=>'Info'],
				['id'=>'facebook', 'label'=>'Facebook'],
				['id'=>'twitter', 'label'=>'Twitter'],
				['id'=>'linkedin', 'label'=>'Linkedin'],
				['id'=>'skype', 'label'=>'Skype'],
				['id'=>'dropbox', 'label'=>'Dropbox'],
				['id'=>'wordpress', 'label'=>'Wordpress'],
				['id'=>'vimeo', 'label'=>'Vimeo'],
				['id'=>'vk', 'label'=>'Vk'],
				['id'=>'tumblr', 'label'=>'Tumblr'],
				['id'=>'yahoo', 'label'=>'Yahoo'],
				['id'=>'pinterest', 'label'=>'Pinterest'],
				['id'=>'youtube', 'label'=>'Youtube'],
				['id'=>'reddit', 'label'=>'Reddit'],
				['id'=>'quora', 'label'=>'Quora'],
				['id'=>'soundcloud', 'label'=>'Soundcloud'],
				['id'=>'whatsapp', 'label'=>'Whatsapp'],
				['id'=>'instagram', 'label'=>'Instagram'],
			];

			$data->bootswatch = [
				['id'=>'', 'label'=>'Sem estilo'],
				['id'=>'cerulean', 'label'=>'Cerulean'],
				['id'=>'cosmo', 'label'=>'Cosmo'],
				['id'=>'cyborg', 'label'=>'Cyborg'],
				['id'=>'darkly', 'label'=>'Darkly'],
				['id'=>'flatly', 'label'=>'Flatly'],
				['id'=>'journal', 'label'=>'Journal'],
				['id'=>'litera', 'label'=>'Litera'],
				['id'=>'lumen', 'label'=>'Lumen'],
				['id'=>'lux', 'label'=>'Lux'],
				['id'=>'materia', 'label'=>'Materia'],
				['id'=>'minty', 'label'=>'Minty'],
				['id'=>'pulse', 'label'=>'Pulse'],
				['id'=>'sandstone', 'label'=>'Sandstone'],
				['id'=>'simplex', 'label'=>'Simplex'],
				['id'=>'sketchy', 'label'=>'Sketchy'],
				['id'=>'slate', 'label'=>'Slate'],
				['id'=>'solar', 'label'=>'Solar'],
				['id'=>'spacelab', 'label'=>'Spacelab'],
				['id'=>'superhero', 'label'=>'Superhero'],
				['id'=>'united', 'label'=>'United'],
				['id'=>'yeti', 'label'=>'Yeti'],
			];

			$data->loader_icon = [
				['id'=>'', 'label'=>'Desativado'],
				['id'=>'lds-default', 'label'=>'Default'],
				['id'=>'lds-circle', 'label'=>'Circle'],
				['id'=>'lds-dual-ring', 'label'=>'Dual Ring'],
				['id'=>'lds-facebook', 'label'=>'Facebook'],
				['id'=>'lds-heart', 'label'=>'Heart'],
				['id'=>'lds-ring', 'label'=>'Ring'],
				['id'=>'lds-roller', 'label'=>'Roller'],
				['id'=>'lds-ellipsis', 'label'=>'Ellipsis'],
				['id'=>'lds-grid', 'label'=>'Grid'],
				['id'=>'lds-hourglass', 'label'=>'Hourglass'],
				['id'=>'lds-ripple', 'label'=>'Ripple'],
				['id'=>'lds-spinner', 'label'=>'Spinner'],
			];

			?>
			<div id="<?php echo $data->id; ?>">
				<form action="<?php echo \Basementor\Basementor::action('basementor-settings-save'); ?>" method="post">
					<div class="text-right mt-3">
						<textarea name="settings" style="display:none;">{{ settings }}</textarea>
						<button type="submit" class="btn btn-primary">
							<i class="fa fa-fw fa-save"></i> Salvar
						</button>
					</div><br>

					<div class="row">
						<div class="col-4 form-group">
							<label>Estilo Bootswatch</label>
							<input-select v-model="settings.basementor_bootstrap_bootswatch"
								:options="bootswatch"
								option-id="id"
								option-label="label"
								placeholder="Estilo Bootswatch"
							>
								<template #option="{option, value}">{{ option.label }}</template>
							</input-select>
						</div>

						<div class="col-12"><br></div>

						<div class="col-4 form-group">
							<label>Prefix</label>
							<input-select v-model="prefix"
								:options="prefixes"
								option-id="id"
								option-label="label"
								placeholder="Prefixo"
							>
								<template #option="{option, value}">{{ option.label }}</template>
							</input-select>
						</div>

						<?php foreach($data->prefixes as $pref): ?>
						<template v-if="prefix=='<?php echo $pref['id']; ?>'">
							<div class="col-2 form-group">
								<label><?php echo $pref['label']; ?> bg</label>
								<input-color v-model="settings.basementor_bootstrap_<?php echo $pref['id']; ?>_bg">
									<template #append><div class="input-group-append">
										<div class="input-group-btn">
											<button type="button" class="btn border-0 bg-transparent" @click="basementorSettingsDefault('basementor_bootstrap_<?php echo $pref['id']; ?>_bg');">
												<i class="fa fa-fw fa-refresh"></i>
											</button>
										</div>
									</div></template>
								</input-color>
							</div>

							<div class="col-2 form-group">
								<label><?php echo $pref['label']; ?> text</label>
								<input-color v-model="settings.basementor_bootstrap_<?php echo $pref['id']; ?>_text">
									<template #append><div class="input-group-append">
										<div class="input-group-btn">
											<button type="button" class="btn border-0 bg-transparent" @click="basementorSettingsDefault('basementor_bootstrap_<?php echo $pref['id']; ?>_text');">
												<i class="fa fa-fw fa-refresh"></i>
											</button>
										</div>
									</div></template>
								</input-color>
							</div>
						</template>
						<?php endforeach; ?>

						<div class="col-2 form-group">
							<label>Dark percent</label>
							<div class="input-group form-control" style="padding:0px !important;">
								<input type="number" class="form-control border-0 bg-transparent" v-model="settings.basementor_bootstrap_dark_percent">
								<div class="input-group-append"><div class="input-group-btn">
									<button type="button" class="btn" @click="basementorSettingsDefault('basementor_bootstrap_dark_percent');">
										<i class="fa fa-fw fa-refresh"></i>
									</button>
								</div></div>
							</div>
						</div>

						<div class="col-2 form-group">
							<label>Light percent</label>
							<div class="input-group form-control" style="padding:0px !important;">
								<input type="number" class="form-control border-0 bg-transparent" v-model="settings.basementor_bootstrap_light_percent">
								<div class="input-group-append"><div class="input-group-btn">
									<button type="button" class="btn" @click="basementorSettingsDefault('basementor_bootstrap_light_percent');">
										<i class="fa fa-fw fa-refresh"></i>
									</button>
								</div></div>
							</div>
						</div>
					</div><br>

					<div :class="`border border-${prefix} mr-2`">
						<nav class="navbar navbar-expand-lg navbar-dark mb-2" :class="`bg-${prefix}`">
							<a href="#" class="navbar-brand">
								<i :class="`fa fa-fw fa-${prefix}`"></i>
							</a>
							<button type="button" data-toggle="collapse" data-target="#navbar-color-primary" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler"><span class="navbar-toggler-icon"></span></button>
							<div id="navbar-color-primary" class="collapse navbar-collapse"><ul class="navbar-nav mr-auto"><li class="nav-item active"><a href="#" class="nav-link">Home <span class="sr-only">(current)</span></a></li> <li class="nav-item"><a href="#" class="nav-link">Features</a></li> <li class="nav-item"><a href="#" class="nav-link">Pricing</a></li> <li class="nav-item"><a href="#" class="nav-link">About</a></li></ul> 
								<div class="input-group form-control" style="max-width:300px; padding:0px!important; border:none!important;">
									<input type="text" placeholder="Search" class="form-control border-0 mr-sm-2">
									<div class="input-group-btn" style="padding:0px!important; border:none!important;">
										<button type="button" :class="`btn btn-${prefix}-light`" style="border:none!important;">
											<i class="fa fa-fw fa-search"></i>
										</button>
									</div>
								</div>
							</div>
						</nav>

						<div class="p-2">
							<div class="alert alert-dismissible mb-2" :class="`alert-${prefix}`"><button type="button" data-dismiss="alert" class="close">×</button> <h4 class="alert-heading">Warning!</h4> <p class="mb-0">Lorem ipsum dolor sit amet, odit magni cum qui doloribus  <a href="#" class="alert-link">vel scelerisque nisl consectetur et</a>.</p></div>

							<div class="row">
								<div class="col-12 col-md-6">
									<h2>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</h2>
									<p>Excepturi doloribus unde molestias laborum delectus adipisci, eos repellat in debitis cum impedit numquam, architecto, facilis. Blanditiis cum quas nam ut, eum?</p>
									<p>Rem accusamus, ipsa asperiores earum natus nesciunt vitae maxime eum, fugit labore odio, animi sequi at excepturi itaque rerum sit eius. Minus?</p>

									<div class="row mb-6">
										<div class="col-6 form-group">
											<input type="text" class="form-control" placeholder="Placeholder">
										</div>

										<div class="col-6 form-group">
											<div class="input-group">
												<input type="text" class="form-control" placeholder="Placeholder">
												<div class="input-group-append"><div class="input-group-text">
													Append
												</div></div>
											</div>
										</div>

										<div class="col-6 form-group">
											<select class="form-control">
												<option value="">Select</option>
												<option value="">Option A</option>
												<option value="">Option B</option>
												<option value="">Option C</option>
											</select>
										</div>

										<div class="col-6 form-group">
											<div class="input-group form-control" style="padding:0px!important;">
												<input type="text" class="form-control border-0 bg-transparent" placeholder="Placeholder">
												<div class="input-group-append"><div class="input-group-text border-0 bg-transparent">
													Append
												</div></div>
											</div>
										</div>
									</div>

									<div class="row mb-2">
										<div class="col"><button type="button" class="btn btn-block" :class="`btn-outline-${prefix}`">Cancel</button></div>
										<div class="col"><button type="button" class="btn btn-block" :class="`btn-${prefix}`">Save</button></div>
									</div>
								</div>

								<div class="col-12 col-md-6">
									<div class="list-inline text-right mb-2">
										<div class="list-inline-item">
											<a href="javascript:;" :class="`btn btn-${prefix}`">
												<i :class="`fa fa-fw fa-plus text-white`"></i> Add
											</a>
										</div>

										<div class="list-inline-item">
											<a href="javascript:;" :class="`btn btn-outline-${prefix} text-${prefix}`">
												<i :class="`fa fa-fw fa-plus text-${prefix}`"></i> Add
											</a>
										</div>
									</div>
									
									<table class="table table-bordered table-striped">
										<colgroup>
											<col width="50px">
											<col width="*">
											<col width="*">
										</colgroup>

										<thead>
											<tr>
												<th>ID</th><th>Bbb</th><th>Ccc</th>
											</tr>
										</thead>

										<tbody>
											<?php for($x=1; $x<=5; $x++): ?>
											<tr :class="`table-${prefix}`">
												<td><?php echo $x; ?></td>
												<td>
													<?php if ($x%2==0): ?>
													<span class="badge" :class="`badge-${prefix}`">{{ prefix }} <?php echo $x; ?></span>
													<?php endif; ?>

													<?php if ($x%1==0): ?>
													<span class="badge badge-pill" :class="`badge-${prefix}`">{{ prefix }} <?php echo $x; ?></span>
													<?php endif; ?>
												</td>
												<td>Ccc</td>
											</tr>
											<?php endfor; ?>
										</tbody>
									</table>

									<div class="progress mb-3"><div role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-striped progress-bar-animated" :class="`bg-${prefix}`" style="width: 75%;"></div></div>

									<div class="d-flex">
										<div class="mx-auto">
											<nav aria-label="Page navigation example">
												<ul class="pagination">
													<li class="page-item"><a href="javascript:;" class="page-link">1</a></li>
													<li class="page-item"><a href="javascript:;" class="page-link bg-primary text-light">2</a></li>
													<li class="page-item"><a href="javascript:;" class="page-link">3</a></li>
													<li class="page-item"><a href="javascript:;" class="page-link">4</a></li>
													<li class="page-item"><a href="javascript:;" class="page-link">5</a></li>
												</ul>
											</nav>
										</div>
									</div>
								</div>
							</div>

							<div class="row mb-4">
								<div class="col">
									<div class="card text-white" :class="`bg-${prefix}`"><div class="card-header">Header</div> <div class="card-body"><h4 class="card-title">Primary card title</h4> <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p></div></div>
								</div>
								<div class="col">
									<div class="card" :class="`border-${prefix}`"><div class="card-header">Header</div> <div class="card-body"><h4 class="card-title">Primary card title</h4> <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p></div></div>
								</div>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label>CSS customizado</label>
						<input-code v-model="settings.basementor_css" @change="updateCss($event);"></input-code>
					</div>

					<div class="row mt-4">
						<div class="col-4 form-group">
							<label>Loader</label>
							<input-select v-model="settings.basementor_loader_icon"
								:options="loader_icon"
								option-id="id"
								option-label="label"
								placeholder="Ícone do loader"
							>
								<template #option="{option, value}">{{ option.label }}</template>
							</input-select>
						</div>

						<div class="col-4 form-group">
							<label>Cor do ícone</label>
							<input-color v-model="settings.basementor_loader_icon_color">
								<template #append><div class="input-group-append">
									<div class="input-group-btn">
										<button type="button" class="btn border-0 bg-transparent" @click="basementorSettingsDefault('basementor_bootstrap_<?php echo $pref['id']; ?>_text');">
											<i class="fa fa-fw fa-refresh"></i>
										</button>
									</div>
								</div></template>
							</input-color>
						</div>

						<div class="col-4 form-group">
							<label>Cor de fundo</label>
							<input-color v-model="settings.basementor_loader_bg_color">
								<template #append><div class="input-group-append">
									<div class="input-group-btn">
										<button type="button" class="btn border-0 bg-transparent" @click="basementorSettingsDefault('basementor_bootstrap_<?php echo $pref['id']; ?>_text');">
											<i class="fa fa-fw fa-refresh"></i>
										</button>
									</div>
								</div></template>
							</input-color>
						</div>
					</div>

					<div class="text-right mt-3">
						<textarea name="settings" style="display:none;">{{ settings }}</textarea>
						<button type="submit" class="btn btn-primary">
							<i class="fa fa-fw fa-save"></i> Salvar
						</button>
					</div>
				</form>
			</div>

			<?php do_action('vue'); ?>
			<script>new Vue({
				el: "#<?php echo $data->id; ?>",
				data: <?php echo json_encode($data); ?>,
				methods: {
					basementorSettingsDefault(kname) {
						this.$set(this.settings, kname, this.settingsDefault[kname]||false);
						this.$forceUpdate();
					},

					updateCss(css) {
						if (this.updateCssTimeout) clearTimeout(this.updateCssTimeout);
						this.updateCssTimeout = setTimeout(() => {
							document.getElementById('basementor_css').innerHTML = css;
						}, 1000);
					},
				},
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
			$data->id = uniqid('basementor-settings-');
			$data->vueCodemirrorOptions = [
				'tabSize' => 4,
				'mode' => 'application/x-httpd-php',
				'theme' => 'ambiance',
				'lineNumbers' => true,
				'line' => 'true',
				'indentUnit' => 4,
				'indentWithTabs' => true,
			];
			
			?><div id="<?php echo $data->id; ?>">
				<form action="<?php echo \Basementor\Basementor::action('basementor-settings-save'); ?>" method="post">
					<div class="form-group">
						<label>Conteúdo head</label>
						<codemirror :options="vueCodemirrorOptions" v-model="settings.basementor_head"></codemirror>
					</div>

					<div class="form-group">
						<label>Conteúdo body</label>
						<codemirror :options="vueCodemirrorOptions" v-model="settings.basementor_body"></codemirror>
					</div>

					<div class="text-right">
						<textarea name="settings" style="display:none;">{{ settings }}</textarea>
						<button type="submit" class="btn btn-primary">Salvar</button>
					</div>
				</form>
			</div>

			<style>.CodeMirror {min-height:200px;}</style>
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
				el: "#<?php echo $data->id; ?>",
				data: <?php echo json_encode($data); ?>,
			});</script><?php
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

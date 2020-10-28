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
	global $post;
	$items = [];

	$items['basementor'] = [
		'title' => 'Tema',
		'href'  => 'javascript:;',
		'children' => [
			[
				'id'    => 'basementor-settings-update',
				'title' => 'Update',
				'href'  => admin_url('admin.php?page=basementor-settings&tab=update'),
			],
		],
	];


	$items['basementor-elementor'] = [
		'title' => 'Elementor',
		'href' => 'javascript:;',
		'children' => [],
	];

	if ($post AND $post->ID) {
		$items['basementor-elementor']['children'][] = [
			'id'    => 'basementor-elementor-page',
			'title' => 'Editar página atual',
			'href'  => admin_url("/post.php?post={$post->ID}&action=elementor"),
		];
	}

	$postid = get_option('theme-elementor-header');
	$items['basementor-elementor']['children'][] = [
		'id'    => 'basementor-elementor-header',
		'title' => 'Editar página atual',
		'href'  => admin_url("/post.php?post={$postid}&action=elementor"),
	];
	
	$postid = get_option('theme-elementor-footer');
	$items['basementor-elementor']['children'][] = [
		'id'    => 'basementor-elementor-footer',
		'title' => 'Editar página atual',
		'href'  => admin_url("/post.php?post={$postid}&action=elementor"),
	];

	$items = apply_filters('basementor-settings-menu', $items);

	foreach($items as $id=>$item) {
		$item['id'] = $id;
		$admin_bar->add_menu($item);

		if (isset($item['children']) AND is_array($item['children'])) {
			foreach($item['children'] as $iitem) {
				$iitem['parent'] = $item['id'];
				$admin_bar->add_menu($iitem);
			}
		}
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
		do_action("basementor-settings-{$data->tab}", $data);

		if ($data->tab=='help') {
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

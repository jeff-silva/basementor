<?php

include __DIR__ . '/wp-helpers/wp-helpers.php';

add_action('after_setup_theme', function() {
	add_theme_support('post-thumbnails');
	add_theme_support('woocommerce');

	register_nav_menus([
		'primary' => 'Principal',
		'socials' => 'Redes Sociais',
	]);
});


/*
	theme-elementor-header
	theme-elementor-footer
	theme-elementor-404
*/



foreach(['wp_enqueue_scripts', 'admin_enqueue_scripts'] as $action) {
	add_action($action, function() {
		wp_enqueue_script('vue', '//cdnjs.cloudflare.com/ajax/libs/vue/2.6.10/vue.min.js');
		wp_enqueue_script('popper', '//cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js');
		wp_enqueue_script('bootstrap', '//stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js');
		wp_enqueue_style('bootstrap', '//stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css');
		wp_enqueue_style('font-awesome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');
		wp_enqueue_style('animate-css', '//cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css');
		wp_enqueue_script('slick', '//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js');
		wp_enqueue_style('slick', '//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css');
	});
}


class Wpt
{
	static function elementor($slug) {
		$key = "theme-elementor-{$slug}";
		$id = get_option($key);

		if (! $id) {
			$postarr = [
				'post_type' => 'elementor_library',
				'post_title' => ucfirst($slug),
				'post_status' => 'publish',
				'meta_input' => [
					'_elementor_edit_mode' => 'builder',
					'_elementor_template_type' => 'page',
				],
			];

			if ($slug=='header') {
				$postarr['post_title'] = 'Cabeçalho';
				$postarr['meta_input']['_elementor_template_type'] = 'section';
				$postarr['meta_input']['_elementor_data'] = '[{"id":"b7a4845","elType":"section","settings":{"structure":"20"},"elements":[{"id":"2b0f9e6","elType":"column","settings":{"_column_size":50,"_inline_size":15.525999999999999801048033987171947956085205078125},"elements":[{"id":"619cb2c","elType":"widget","settings":{"title":"Site Name"},"elements":[],"widgetType":"heading"}],"isInner":false},{"id":"aa19766","elType":"column","settings":{"_column_size":50,"_inline_size":84.4740000000000037516656448133289813995361328125},"elements":[{"id":"bb601c1","elType":"widget","settings":{"editor":"Menu<\/p>"},"elements":[],"widgetType":"text-editor"}],"isInner":false}],"isInner":false}]';
				$postarr['meta_input']['_elementor_controls_usage'] = 'a:4:{s:7:"heading";a:3:{s:5:"count";i:1;s:15:"control_percent";i:0;s:8:"controls";a:1:{s:7:"content";a:1:{s:13:"section_title";a:1:{s:5:"title";i:1;}}}}s:6:"column";a:3:{s:5:"count";i:2;s:15:"control_percent";i:0;s:8:"controls";a:1:{s:6:"layout";a:1:{s:6:"layout";a:1:{s:12:"_inline_size";i:2;}}}}s:11:"text-editor";a:3:{s:5:"count";i:1;s:15:"control_percent";i:0;s:8:"controls";a:1:{s:7:"content";a:1:{s:14:"section_editor";a:1:{s:6:"editor";i:1;}}}}s:7:"section";a:3:{s:5:"count";i:1;s:15:"control_percent";i:0;s:8:"controls";a:1:{s:6:"layout";a:1:{s:17:"section_structure";a:1:{s:9:"structure";i:1;}}}}}';
			}
			else if ($slug=='footer') {
				$postarr['post_title'] = 'Rodapé';
				$postarr['meta_input']['_elementor_template_type'] = 'section';
				$postarr['meta_input']['_elementor_data'] = '[{"id":"b7a4845","elType":"section","settings":{"structure":"20"},"elements":[{"id":"2b0f9e6","elType":"column","settings":{"_column_size":50,"_inline_size":15.525999999999999801048033987171947956085205078125},"elements":[{"id":"619cb2c","elType":"widget","settings":{"title":"Site Name"},"elements":[],"widgetType":"heading"}],"isInner":false},{"id":"aa19766","elType":"column","settings":{"_column_size":50,"_inline_size":84.4740000000000037516656448133289813995361328125},"elements":[{"id":"bb601c1","elType":"widget","settings":{"editor":"Menu<\/p>"},"elements":[],"widgetType":"text-editor"}],"isInner":false}],"isInner":false}]';
				$postarr['meta_input']['_elementor_controls_usage'] = 'a:4:{s:7:"heading";a:3:{s:5:"count";i:1;s:15:"control_percent";i:0;s:8:"controls";a:1:{s:7:"content";a:1:{s:13:"section_title";a:1:{s:5:"title";i:1;}}}}s:6:"column";a:3:{s:5:"count";i:2;s:15:"control_percent";i:0;s:8:"controls";a:1:{s:6:"layout";a:1:{s:6:"layout";a:1:{s:12:"_inline_size";i:2;}}}}s:11:"text-editor";a:3:{s:5:"count";i:1;s:15:"control_percent";i:0;s:8:"controls";a:1:{s:7:"content";a:1:{s:14:"section_editor";a:1:{s:6:"editor";i:1;}}}}s:7:"section";a:3:{s:5:"count";i:1;s:15:"control_percent";i:0;s:8:"controls";a:1:{s:6:"layout";a:1:{s:17:"section_structure";a:1:{s:9:"structure";i:1;}}}}}';
			}
			else if ($slug=='404') {
				$postarr['post_title'] = 'Erro 404';
				$postarr['meta_input']['_elementor_template_type'] = 'page';
				$postarr['meta_input']['_elementor_data'] = '[{"id":"6b2e708","elType":"section","settings":[],"elements":[{"id":"8578271","elType":"column","settings":{"_column_size":100,"_inline_size":null},"elements":[{"id":"5004317","elType":"widget","settings":{"editor":"<h1>Erro 404<\/h1><p>A p\u00e1gina que voc\u00ea procura n\u00e3o existe ou mudou de endere\u00e7o.<\/p><p>Voltar para a <a href=\"\/\">p\u00e1gina inicial<\/a>.<\/p>"},"elements":[],"widgetType":"text-editor"}],"isInner":false}],"isInner":false}]';
				$postarr['meta_input']['_elementor_controls_usage'] = 'a:3:{s:11:"text-editor";a:3:{s:5:"count";i:1;s:15:"control_percent";i:0;s:8:"controls";a:1:{s:7:"content";a:1:{s:14:"section_editor";a:1:{s:6:"editor";i:1;}}}}s:6:"column";a:3:{s:5:"count";i:1;s:15:"control_percent";i:0;s:8:"controls";a:1:{s:6:"layout";a:1:{s:6:"layout";a:1:{s:12:"_inline_size";i:1;}}}}s:7:"section";a:3:{s:5:"count";i:1;s:15:"control_percent";i:0;s:8:"controls";a:0:{}}}';
			}

			$id = wp_insert_post($postarr);
			update_option($key, $id);

		}

		echo \Elementor\Plugin::$instance->frontend->get_builder_content($id, true);
	}


	static function checkbox($attrs=null, $label=null) {
		echo "<label class='wpt-check'><input type='checkbox' {$attrs} /><div></div> {$label}</label>";
	}

	static function radio($attrs=null, $label=null) {
		echo "<label class='wpt-check'><input type='radio' {$attrs} /><div></div> {$label}</label>";
	}
}


class Basementor
{
	static function fileDelete($file, $files=[], $level=0) {
		if (is_dir($file)) {
			foreach(glob($file .'/*') as $file_each) {
				if (is_dir($file_each)) {
					$files = self::fileDelete($file_each, $files, $level+1);
					continue;
				}
				$files[] = $file_each;
			}
		}
		$files[] = $file;

		if ($level==0) {
			foreach($files as $file) {
				if (is_dir($file)) { rmdir($file); }
				else { unlink($file); }
			}
		}

		return $files;
	}
}


add_action('wp_head', function() { ?>
<style>
/* Bugfixes */
.elementor-column-gap-default>.elementor-row>.elementor-column>.elementor-element-populated {padding:0px !important; margin:0px !important;}

.input-group.border {border-radius:4px; overflow:hidden;}
.input-group.border .form-control {border:none !important; background:none !important; border-radius:0 !important; outline:0!important; box-shadow:none !important;}
.input-group.border .btn {border:none !important; border-radius:0 !important;}

/* Customizations */
.elementor-heading-title {color:#666 !important;}

/* Classes */
.wpt-cover {background:url() #eee center center no-repeat; background-size:cover;}
.wpt-footer {}
.wpt-footer .elementor-heading-title {color:#666 !important; text-transform:uppercase;}
.wpt-footer .list-group {}
.wpt-footer .list-group-item {border:none; background:none; padding:2px 0px; color:#888;}
.wpt-footer .list-group-item > i {width:20px;}

.wpt-products {}
.wpt-products-each {position:relative; margin:0px 0px 25px 0px;}
.wpt-products-each a {color:#363f4d; text-decoration:none !important;}
.wpt-products-each .woocommerce-loop-product__title {font-size:14px; padding:0px; text-transform:uppercase; white-space:nowrap;}
.wpt-products-each .added_to_cart {display:none !important;}

.wpt-products-each-sale {position:absolute; top:0px; right:0px; background:red; color:#fff; font-size:12px; padding:0px 10px; border-radius:3px;}

.wpt-products-each .price {display:block; clear:both; text-align:center; padding:3px 0px;}
.wpt-products-each .price ins,
.wpt-products-each .price .woocommerce-Price-amount {color:#e73d3d; font-weight:bold;}
.wpt-products-each .price del {color:#999 !important; font-size:12px;}

.wpt-products-each .star-rating {display:block; float:none; margin:0 auto; padding:0px 0px;}
.wpt-products-each .star-rating:before,
.wpt-products-each .star-rating span:before {color:#f9ba48; letter-spacing: 1px;  font-weight:100;}

.wpt-products-each .btn {margin:5px 0px 0px 0px !important;}
<?php

$colors = [
	'primary' => ['r'=>242, 'g'=>148, 'b'=>34],
	'facebook' => ['r'=>59, 'g'=>89, 'b'=>153],
	'twitter' => ['r'=>85, 'g'=>172, 'b'=>238],
	'linkedin' => ['r'=>0, 'g'=>119, 'b'=>181],
	'vimeo' => ['r'=>26, 'g'=>183, 'b'=>234],
	'tumblr' => ['r'=>52, 'g'=>70, 'b'=>93],
	'pinterest' => ['r'=>189, 'g'=>8, 'b'=>28],
	'youtube' => ['r'=>205, 'g'=>32, 'b'=>31],
	'reddit' => ['r'=>255, 'g'=>87, 'b'=>0],
	'quora' => ['r'=>185, 'g'=>43, 'b'=>39],
	'soundcloud' => ['r'=>255, 'g'=>51, 'b'=>0],
	'whatsapp' => ['r'=>37, 'g'=>211, 'b'=>102],
	'instagram' => ['r'=>228, 'g'=>64, 'b'=>95],
];

$__add = function($color, $add=0) {
	$color['r'] += $add;
	$color['g'] += $add;
	$color['b'] += $add;
	return 'rgb('. implode(',', $color) .')';
};

$style = [];
foreach($colors as $prefix=>$c) {
	$default = $__add($c, 0);
	$light = $__add($c, 10);
	$dark = $__add($c, -10);

	$style[] = ".text-{$prefix} {color:{$default};}";
	$style[] = ".bg-{$prefix}-light {background-color:{$light} !important;}";
	$style[] = ".bg-{$prefix}-dark {background-color:{$dark} !important;}";
	$style[] = ".bg-{$prefix} {background-color:{$default} !important;}";
	$style[] = ".btn-{$prefix} {background-color:{$default} !important; border-color:{$default};}";
	$style[] = ".btn-{$prefix}:hover, .btn-{$prefix}:active {background-color:{$dark} !important; border-color:{$dark};}";
	$style[] = ".border-{$prefix} {border-color:{$default} !important;}";
}

echo implode('', $style); ?></style> 
<?php });


add_action('woocommerce_product_query', function($query) {
	$meta_query = $query->get('meta_query');
	$tax_query = $query->get('tax_query');

	// if (isset($_GET['product_cat'])) {
	// 	$value = array_values(array_filter(explode(',', $_GET['product_cat']), 'strlen'));
	// 	$meta_query[] = ['key'=>'product_cat', 'compare'=>'IN', 'value'=>$value];
	// }

	// foreach(wc_get_attribute_taxonomies() as $tax) {
	// 	$key = "pa_{$tax->attribute_name}";

	// 	if (isset($_GET[$key])) {
	// 		$value = array_values(array_filter(explode(',', $_GET[$key]), 'strlen'));
	// 		$tax_query[] = [
	// 			'taxonomy' => $key,
	// 			'field' => 'slug',
	// 			'terms' => $value,
	// 			'operator' => 'IN',
	// 		];
	// 	}
	// }

	$query->set('meta_query', $meta_query);
	$query->set('tax_query', $tax_query);
	// echo '<!-- $tax_query: ', print_r($tax_query, true), '-->';
});


if (isset($_GET['basementor-update'])) {
	add_action('init', function() {
		$data = new stdClass;
		$data->themes_dir = realpath(__DIR__ . '/../');
		$data->zip_filename = "{$data->themes_dir}/basementor-master.zip";
		
		if (file_exists($data->zip_filename)) { unlink($data->zip_filename); }
		$contents = file_get_contents('https://github.com/jeff-silva/basementor/archive/master.zip');
		file_put_contents($data->zip_filename, $contents);
		$data->delete = Basementor::fileDelete(__DIR__);

		$zip = new ZipArchive;
		if ($zip->open($data->zip_filename) === TRUE) {
			$zip->extractTo($data->themes_dir);
			$zip->close();
		}

		wp_redirect($_SERVER['HTTP_REFERER']);
	});
}


add_action('admin_menu', function() {
	add_submenu_page('tools.php', 'Basementor', 'Basementor', 'manage_options', 'basementor', function() {
		echo '<br><a href="?page=basementor&basementor-update" class="btn btn-success">Update</a>';
	});
});

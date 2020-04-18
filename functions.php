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


add_action('wp_head', function() { ?>
<style>
/* Bugfixes */
.elementor-column-gap-default>.elementor-row>.elementor-column>.elementor-element-populated {padding:0px !important;}
.input-group .form-control {outline:0!important; box-shadow:none !important;}

/* Customizations */
.elementor-heading-title {color:#666 !important;}

/* Classes */
.wpt-cover {background:url() #eee center center no-repeat; background-size:cover;}
.wpt-footer {}
.wpt-footer .elementor-heading-title {color:#666 !important; text-transform:uppercase;}
.wpt-footer .list-group {}
.wpt-footer .list-group-item {border:none; background:none; padding:2px 0px; color:#888;}
.wpt-footer .list-group-item > i {width:20px;}
<?php

$colors = [
	'primary' => (object) ['default'=>'#F29422', 'dark'=>'#D07200', 'light'=>'#F4B644'],
	'secondary' => (object) ['default'=>'#ff0000', 'dark'=>'#ff0000', 'light'=>'#ff0000'],
	'success' => (object) ['default'=>'#00ff00', 'dark'=>'#00ff00', 'light'=>'#00ff00'],
	'danger' => (object) ['default'=>'#ff0000', 'dark'=>'#ff0000', 'light'=>'#ff0000'],
	'warning' => (object) ['default'=>'#ffff00', 'dark'=>'#ffff00', 'light'=>'#ffff00'],
	'info' => (object) ['default'=>'#0000ff', 'dark'=>'#0000ff', 'light'=>'#0000ff'],
];

foreach($colors as $prefix=>$c): echo <<<EOF
\n\n/* {$prefix}: default:{$c->default}, dark:{$c->dark}, light:{$c->light} */
.text-{$prefix} {color:{$c->default};}
.bg-{$prefix} {background-color:{$c->default} !important;}
.btn-{$prefix} {background-color:{$c->default} !important; border-color:{$c->default};}
.btn-{$prefix}:hover, .btn-{$prefix}:active {background-color:{$c->dark} !important; border-color:{$c->dark};}
.border-{$prefix} {border-color:{$c->default} !important;}
EOF;
endforeach;
?></style> 
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
	echo '<!-- $tax_query: ', print_r($tax_query, true), '-->';
});
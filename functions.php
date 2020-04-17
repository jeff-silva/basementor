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
		wp_enqueue_script('vue', 'https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.10/vue.min.js');
		wp_enqueue_script('popper', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js');
		wp_enqueue_script('bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js');
		wp_enqueue_style('bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css');
		wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');
		wp_enqueue_style('animate-css', 'https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css');
	});
}


class Theme
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
}



// add_filter('woocommerce_checkout_fields', function($fields) {
// 	foreach($fields as $field_type=>$field_fields) {
// 		foreach($field_fields as $name=>$field) {
// 			foreach($field['class'] as $class_index=>$class) {
// 				if ($class=='form-row-first' OR $class=='form-row-last') {
// 					unset($field['class'][$class_index]);
// 					$field['class'][] = 'col-6';
// 				}
// 				else if ($class=='form-row-wide') {
// 					unset($field['class'][$class_index]);
// 					$field['class'][] = 'col-12';
// 				}
// 			}
// 			$field['input_class'][] = 'form-control';
// 			$field_fields[$name] = $field;
// 		}
// 		$fields[$field_type] = $field_fields;
// 	}
	
// 	return $fields;
// });


add_action('wp_head', function() { ?>
<style>
.elementor-column-gap-default>.elementor-row>.elementor-column>.elementor-element-populated {padding:0px !important;}
</style>
<?php });
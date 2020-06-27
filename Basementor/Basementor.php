<?php

namespace Basementor;

class Basementor
{
	
	static function action($action, $callback=null) {
		if ($callback===null) {
			return site_url("?basementor-action={$action}");
		}

		if (isset($_GET['basementor-action']) AND $_GET['basementor-action']==$action) {
			add_action('init', function() use($callback) {
				$resp = new \stdClass;
				$post = array_map('stripslashes', $_POST);
				try { $resp = call_user_func($callback, (object) $post); }
				catch(\Exception $e) { $resp->error = $e->getMessage(); }
				echo json_encode($resp); die;
			});
		}
	}


	static function file_delete($file, $files=[], $level=0) {
		if (is_dir($file)) {
			foreach(glob($file .'/*') as $file_each) {
				if (is_dir($file_each)) {
					$files = self::file_delete($file_each, $files, $level+1);
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


	static function colors() {
		$colors = self::option('colors');

		$__add = function($color, $add=0) {
			$color['r'] += $add;
			$color['r'] = min($color['r'], 255);
			$color['r'] = max($color['r'], 0);

			$color['g'] += $add;
			$color['g'] = min($color['g'], 255);
			$color['g'] = max($color['g'], 0);

			$color['b'] += $add;
			$color['b'] = min($color['b'], 255);
			$color['b'] = max($color['b'], 0);

			return 'rgb('. implode(',', $color) .')';
		};

		$colors = array_map(function($color) use($__add) {
			return [
				'default' => $__add($color),
				'light' => $__add($color, 10),
				'dark' => $__add($color, -10),
			];
		}, $colors);

		return $colors;
	}


	static function checkbox($attrs=null, $label=null) {
		echo "<label class='wpt-check'><input type='checkbox' {$attrs} /><div></div> {$label}</label>";
	}

	static function radio($attrs=null, $label=null) {
		echo "<label class='wpt-check'><input type='radio' {$attrs} /><div></div> {$label}</label>";
	}

	static function settingsDefault() {
		return [
			'basementor_bootstrap_bootswatch' => '',
			'basementor_bootstrap_dark_percent' => '-30',
			'basementor_bootstrap_light_percent' => '30',
			'basementor_bootstrap_link_color' => '#007bff',
			'basementor_bootstrap_primary_bg' => '#007bff',
			'basementor_bootstrap_primary_text' => '#ffffff',
			'basementor_bootstrap_secondary_bg' => '#6c757d',
			'basementor_bootstrap_secondary_text' => '#ffffff',
			'basementor_bootstrap_success_bg' => '#28a745',
			'basementor_bootstrap_success_text' => '#ffffff',
			'basementor_bootstrap_danger_bg' => '#dc3545',
			'basementor_bootstrap_danger_text' => '#ffffff',
			'basementor_bootstrap_warning_bg' => '#ffc107',
			'basementor_bootstrap_warning_text' => '#ffffff',
			'basementor_bootstrap_info_bg' => '#17a2b8',
			'basementor_bootstrap_info_text' => '#ffffff',
			'basementor_bootstrap_white_bg' => '#ffffff',
			'basementor_bootstrap_white_text' => '#ffffff',
			'basementor_bootstrap_light_bg' => '#f8f9fa',
			'basementor_bootstrap_light_text' => '#ffffff',
			'basementor_bootstrap_dark_bg' => '#343a40',
			'basementor_bootstrap_dark_text' => '#ffffff',
			'basementor_bootstrap_transparent_bg' => '#343a40',
			'basementor_bootstrap_transparent_text' => '#ffffff',
			'basementor_bootstrap_facebook_bg' => '#3b5999',
			'basementor_bootstrap_facebook_text' => '#ffffff',
			'basementor_bootstrap_twitter_bg' => '#55acee',
			'basementor_bootstrap_twitter_text' => '#ffffff',
			'basementor_bootstrap_linkedin_bg' => '#0077b5',
			'basementor_bootstrap_linkedin_text' => '#ffffff',
			'basementor_bootstrap_skype_bg' => '#00aff0',
			'basementor_bootstrap_skype_text' => '#ffffff',
			'basementor_bootstrap_dropbox_bg' => '#007ee5',
			'basementor_bootstrap_dropbox_text' => '#ffffff',
			'basementor_bootstrap_wordpress_bg' => '#21759b',
			'basementor_bootstrap_wordpress_text' => '#ffffff',
			'basementor_bootstrap_vimeo_bg' => '#1ab7ea',
			'basementor_bootstrap_vimeo_text' => '#ffffff',
			'basementor_bootstrap_vk_bg' => '#4c75a3',
			'basementor_bootstrap_vk_text' => '#ffffff',
			'basementor_bootstrap_tumblr_bg' => '#34465d',
			'basementor_bootstrap_tumblr_text' => '#ffffff',
			'basementor_bootstrap_yahoo_bg' => '#410093',
			'basementor_bootstrap_yahoo_text' => '#ffffff',
			'basementor_bootstrap_pinterest_bg' => '#bd081c',
			'basementor_bootstrap_pinterest_text' => '#ffffff',
			'basementor_bootstrap_youtube_bg' => '#cd201f',
			'basementor_bootstrap_youtube_text' => '#ffffff',
			'basementor_bootstrap_reddit_bg' => '#ff5700',
			'basementor_bootstrap_reddit_text' => '#ffffff',
			'basementor_bootstrap_quora_bg' => '#b92b27',
			'basementor_bootstrap_quora_text' => '#ffffff',
			'basementor_bootstrap_soundcloud_bg' => '#ff3300',
			'basementor_bootstrap_soundcloud_text' => '#ffffff',
			'basementor_bootstrap_whatsapp_bg' => '#25d366',
			'basementor_bootstrap_whatsapp_text' => '#ffffff',
			'basementor_bootstrap_instagram_bg' => '#e4405f',
			'basementor_bootstrap_instagram_text' => '#ffffff',
			'basementor_css' => '* {transition: all 300ms ease;}',
		];
	}

	static function settings($key=null) {
		$defaults = self::settingsDefault();

		$settings = get_option('basementor-settings', '{}');
		$settings = @json_decode($settings, true);
		$settings = is_array($settings)? $settings: [];

		$settings = array_merge($defaults, $settings);

		if ($key !== null) {
			return isset($settings[ $key ])? $settings[ $key ]: false;
		}

		return $settings;
	}


	static function settings_save($data) {
		update_option('basementor-settings', json_encode($data));
	}

	static function style() {
		$settings = \Basementor\Basementor::settings();

		$prefixes = [
			'primary', 'secondary', 'success', 'danger', 'warning',
			'info', 'facebook', 'twitter', 'linkedin', 'skype',
			'dropbox', 'wordpress', 'vimeo', 'vk', 'tumblr',
			'yahoo', 'pinterest', 'youtube', 'reddit', 'quora',
			'soundcloud', 'whatsapp', 'instagram',
		];

		$_color = function($hex, $add=null) {
			if ($add!==null) {
				$hex = preg_replace('/[^0-9a-zA-Z]/', '', $hex);
				$hex = str_split($hex, 2);
				$hex = array_map(function($val) use($add) {
					$val = hexdec($val);
					$val += intval($add);
					$val = min(max($val, 0), 255);
					return str_pad(dechex($val), 2, '0', STR_PAD_LEFT);
				}, $hex);
				$hex = '#'. implode('', $hex);
			}
			return $hex;
		};

		$lines = [];

		if ($settings['basementor_bootstrap_bootswatch']) {
			$lines[] = "@import url('https://cdnjs.cloudflare.com/ajax/libs/bootswatch/4.5.0/{$settings['basementor_bootstrap_bootswatch']}/bootstrap.min.css');";
		}

		$lines[] = $settings['basementor_css'];
		$lines[] = ".input-group.form-control {padding:0px;}";
		$lines[] = ".input-group.form-control .btn {border:transparent!important; border-radius:0px; display:inline-block!important;}";
		$lines[] = ".input-group.form-control .form-control, .input-group.form-control .input-group-text {border:transparent!important; background:none; border-radius:0px;}";
		$lines[] = ".basementor-woocommerce-price del {}";
		$lines[] = ".basementor-woocommerce-price ins {text-decoration: none;}";

		foreach($prefixes as $prefix) {
			$color = isset($settings["basementor_bootstrap_{$prefix}_bg"])? $settings["basementor_bootstrap_{$prefix}_bg"]: '#ffffff';
			$text = isset($settings["basementor_bootstrap_{$prefix}_text"])? $settings["basementor_bootstrap_{$prefix}_text"]: '#ffffff';
			$dark = $_color($color, intval($settings['basementor_bootstrap_dark_percent']));
			$light = $_color($color, intval($settings['basementor_bootstrap_light_percent']));

			$lines[] = ".text-{$prefix}, .text-{$prefix}:hover {color:{$color} !important;}";
			$lines[] = ".bg-{$prefix} {background-color:{$color} !important; color:{$text};}";
			$lines[] = ".bg-{$prefix}-light {background-color:{$light} !important;}";
			$lines[] = ".bg-{$prefix}-dark {background-color:{$dark} !important;}";
			$lines[] = ".btn-{$prefix} {background-color:{$color} !important; border-color:{$color}; color:{$text} !important;}";
			$lines[] = ".btn-{$prefix}-light {background-color:{$light} !important; border-color:{$light}; color:{$text};}";
			$lines[] = ".btn-{$prefix}-dark {background-color:{$dark} !important; border-color:{$dark}; color:{$text};}";
			$lines[] = ".btn-{$prefix}:hover, .btn-{$prefix}:active {background-color:{$dark} !important; border-color:{$dark}; color:{$text};}";
			$lines[] = ".btn-outline-{$prefix} {border-color:{$color} !important; color:{$color};}";
			$lines[] = ".btn-outline-{$prefix}:hover {background-color:{$color} !important; color:{$text};}";
			$lines[] = ".border-{$prefix} {border-color:{$color} !important;}";
			$lines[] = ".alert-{$prefix} {background-color:{$light}; color:{$text};}";
			$lines[] = ".badge-{$prefix} {background-color:{$color}; color:{$text};}";
		}

		return implode('', $lines);
	}
}
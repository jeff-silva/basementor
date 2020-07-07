<?php

namespace Basementor;

class Basementor
{
	static function action($action, $callback=null) {
		if ($callback===null) {
			$nonce = wp_create_nonce('wp_rest');
			return site_url("/wp-json/basementor/v1/{$action}?_wpnonce={$nonce}");
		}

		add_action('rest_api_init', function() use($action, $callback) {
			register_rest_route('basementor/v1', $action, [
				'methods' => 'POST',
				'callback' => function() use($callback) {
					$resp = new \stdClass;
					try {
						$post = json_decode(json_encode($_POST));
						$resp = call_user_func($callback, $post);
					}
					catch(\Exception $e) { $resp->error = nl2br($e->getMessage()); }

					if (isset($_POST['_redirect'])) {
						$url = ('back'==$_POST['_redirect'])? $_SERVER['HTTP_REFERER']: $_POST['_redirect'];
						wp_redirect($url);
					}

					return $resp;
				},
				'permission_callback' => function() { return true; },
			]);
		});
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
			'basementor_css' => '',
			'basementor_loader_icon' => '',
			'basementor_loader_icon_color' => '#444444',
			'basementor_loader_bg_color' => '#ffffff',
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

	static function styles() {
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

		$lines[] = '.basementor-checkbox {display:inline-block; cursor:pointer; user-select:none; -moz-user-select:none; -khtml-user-select:none; -webkit-user-select:none; -o-user-select:none;}';
		$lines[] = '.basementor-checkbox input {display:none;}';
		$lines[] = '.basementor-checkbox > * {display:inline-block;}';
		$lines[] = '.basementor-checkbox > *:before {content:"\f096"; font-family:FontAwesome; display:inline-block; width:20px; font-size:12pt;}';
		$lines[] = '.basementor-checkbox input:checked ~ *:before {content:"\f046";}';
		
		// $lines[] = ".input-group.form-control {padding:0px !important;}";
		// $lines[] = ".input-group.form-control .btn {border:transparent!important; border-radius:0px; display:inline-block!important;}";
		// $lines[] = ".input-group.form-control .form-control, .input-group.form-control .input-group-text {border:transparent!important; background:none; border-radius:0px;}";
		// $lines[] = ".basementor-woocommerce-price del {}";
		// $lines[] = ".basementor-woocommerce-price ins {text-decoration: none;}";

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

		return '<style>'. implode('', $lines) .'</style><style id="basementor_css">'. $settings['basementor_css'] .'</style>';
	}


	static function loader() {
		$sets = self::settings();

		$set = new \stdClass;
		$set->id = uniqid('basementor-loader-');
		$set->loader_type = $sets['basementor_loader_icon'];
		$set->loader_color1 = $sets['basementor_loader_icon_color'];
		$set->loader_bg = $sets['basementor_loader_bg_color'];
		if (! $set->loader_type) return false;
		
		ob_start(); ?>

		<div id="<?php echo $set->id; ?>" style="position:fixed; top:0px; left:0px; width:100%; height:100%; background:<?php echo $set->loader_bg; ?>; z-index:999!important; display: flex; align-items: center; justify-content: center;">
			<?php if ($set->loader_type=='lds-default'): ?>
			<style>.lds-default{display:inline-block;position:relative;width:80px;height:80px}.lds-default div{position:absolute;width:6px;height:6px;background:<?php echo $set->loader_color1; ?>;border-radius:50%;animation:lds-default 1.2s linear infinite}.lds-default div:nth-child(1){animation-delay:0s;top:37px;left:66px}.lds-default div:nth-child(2){animation-delay:-.1s;top:22px;left:62px}.lds-default div:nth-child(3){animation-delay:-.2s;top:11px;left:52px}.lds-default div:nth-child(4){animation-delay:-.3s;top:7px;left:37px}.lds-default div:nth-child(5){animation-delay:-.4s;top:11px;left:22px}.lds-default div:nth-child(6){animation-delay:-.5s;top:22px;left:11px}.lds-default div:nth-child(7){animation-delay:-.6s;top:37px;left:7px}.lds-default div:nth-child(8){animation-delay:-.7s;top:52px;left:11px}.lds-default div:nth-child(9){animation-delay:-.8s;top:62px;left:22px}.lds-default div:nth-child(10){animation-delay:-.9s;top:66px;left:37px}.lds-default div:nth-child(11){animation-delay:-1s;top:62px;left:52px}.lds-default div:nth-child(12){animation-delay:-1.1s;top:52px;left:62px}@keyframes lds-default{0%,100%,20%,80%{transform:scale(1)}50%{transform:scale(1.5)}}</style>
			<div class="lds-default"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>

			<?php elseif ($set->loader_type=='lds-circle'): ?>
			<style>.lds-circle{display:inline-block;transform:translateZ(1px)}.lds-circle>div{display:inline-block;width:64px;height:64px;margin:8px;border-radius:50%;background:<?php echo $set->loader_color1; ?>;animation:lds-circle 2.4s cubic-bezier(0,.2,.8,1) infinite}@keyframes lds-circle{0%,100%{animation-timing-function:cubic-bezier(.5,0,1,.5)}0%{transform:rotateY(0)}50%{transform:rotateY(1800deg);animation-timing-function:cubic-bezier(0,.5,.5,1)}100%{transform:rotateY(3600deg)}}</style>
			<div class="lds-circle"><div></div></div>

			<?php elseif ($set->loader_type=='lds-dual-ring'): ?>
			<style>.lds-dual-ring{display:inline-block;width:80px;height:80px}.lds-dual-ring:after{content:" ";display:block;width:64px;height:64px;margin:8px;border-radius:50%;border:6px solid <?php echo $set->loader_color1; ?>;border-color:<?php echo $set->loader_color1; ?> transparent <?php echo $set->loader_color1; ?> transparent;animation:lds-dual-ring 1.2s linear infinite}@keyframes lds-dual-ring{0%{transform:rotate(0)}100%{transform:rotate(360deg)}}</style>
			<div class="lds-dual-ring"></div>

			<?php elseif ($set->loader_type=='lds-facebook'): ?>
			<style>.lds-facebook{display:inline-block;position:relative;width:80px;height:80px}.lds-facebook div{display:inline-block;position:absolute;left:8px;width:16px;background:<?php echo $set->loader_color1; ?>;animation:lds-facebook 1.2s cubic-bezier(0,.5,.5,1) infinite}.lds-facebook div:nth-child(1){left:8px;animation-delay:-.24s}.lds-facebook div:nth-child(2){left:32px;animation-delay:-.12s}.lds-facebook div:nth-child(3){left:56px;animation-delay:0}@keyframes lds-facebook{0%{top:8px;height:64px}100%,50%{top:24px;height:32px}}</style>
			<div class="lds-facebook"><div></div><div></div><div></div></div>

			<?php elseif ($set->loader_type=='lds-heart'): ?>
			<style>.lds-heart{display:inline-block;position:relative;width:80px;height:80px;transform:rotate(45deg);transform-origin:40px 40px}.lds-heart div{top:32px;left:32px;position:absolute;width:32px;height:32px;background:<?php echo $set->loader_color1; ?>;animation:lds-heart 1.2s infinite cubic-bezier(.215,.61,.355,1)}.lds-heart div:after,.lds-heart div:before{content:" ";position:absolute;display:block;width:32px;height:32px;background:<?php echo $set->loader_color1; ?>}.lds-heart div:before{left:-24px;border-radius:50% 0 0 50%}.lds-heart div:after{top:-24px;border-radius:50% 50% 0 0}@keyframes lds-heart{0%{transform:scale(.95)}5%{transform:scale(1.1)}39%{transform:scale(.85)}45%{transform:scale(1)}60%{transform:scale(.95)}100%{transform:scale(.9)}}</style>
			<div class="lds-heart"><div></div></div>

			<?php elseif ($set->loader_type=='lds-ring'): ?>
			<style>.lds-ring{display:inline-block;position:relative;width:80px;height:80px}.lds-ring div{box-sizing:border-box;display:block;position:absolute;width:64px;height:64px;margin:8px;border:8px solid <?php echo $set->loader_color1; ?>;border-radius:50%;animation:lds-ring 1.2s cubic-bezier(.5,0,.5,1) infinite;border-color:<?php echo $set->loader_color1; ?> transparent transparent transparent}.lds-ring div:nth-child(1){animation-delay:-.45s}.lds-ring div:nth-child(2){animation-delay:-.3s}.lds-ring div:nth-child(3){animation-delay:-.15s}@keyframes lds-ring{0%{transform:rotate(0)}100%{transform:rotate(360deg)}}</style>
			<div class="lds-ring"><div></div><div></div><div></div><div></div></div>

			<?php elseif ($set->loader_type=='lds-roller'): ?>
			<style>.lds-roller{display:inline-block;position:relative;width:80px;height:80px}.lds-roller div{animation:lds-roller 1.2s cubic-bezier(.5,0,.5,1) infinite;transform-origin:40px 40px}.lds-roller div:after{content:" ";display:block;position:absolute;width:7px;height:7px;border-radius:50%;background:<?php echo $set->loader_color1; ?>;margin:-4px 0 0 -4px}.lds-roller div:nth-child(1){animation-delay:-36ms}.lds-roller div:nth-child(1):after{top:63px;left:63px}.lds-roller div:nth-child(2){animation-delay:-72ms}.lds-roller div:nth-child(2):after{top:68px;left:56px}.lds-roller div:nth-child(3){animation-delay:-108ms}.lds-roller div:nth-child(3):after{top:71px;left:48px}.lds-roller div:nth-child(4){animation-delay:-144ms}.lds-roller div:nth-child(4):after{top:72px;left:40px}.lds-roller div:nth-child(5){animation-delay:-.18s}.lds-roller div:nth-child(5):after{top:71px;left:32px}.lds-roller div:nth-child(6){animation-delay:-216ms}.lds-roller div:nth-child(6):after{top:68px;left:24px}.lds-roller div:nth-child(7){animation-delay:-252ms}.lds-roller div:nth-child(7):after{top:63px;left:17px}.lds-roller div:nth-child(8){animation-delay:-288ms}.lds-roller div:nth-child(8):after{top:56px;left:12px}@keyframes lds-roller{0%{transform:rotate(0)}100%{transform:rotate(360deg)}}</style>
			<div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>

			<?php elseif ($set->loader_type=='lds-ellipsis'): ?>
			<style>.lds-default{display:inline-block;position:relative;width:80px;height:80px}.lds-default div{position:absolute;width:6px;height:6px;background:<?php echo $set->loader_color1; ?>;border-radius:50%;animation:lds-default 1.2s linear infinite}.lds-default div:nth-child(1){animation-delay:0s;top:37px;left:66px}.lds-default div:nth-child(2){animation-delay:-.1s;top:22px;left:62px}.lds-default div:nth-child(3){animation-delay:-.2s;top:11px;left:52px}.lds-default div:nth-child(4){animation-delay:-.3s;top:7px;left:37px}.lds-default div:nth-child(5){animation-delay:-.4s;top:11px;left:22px}.lds-default div:nth-child(6){animation-delay:-.5s;top:22px;left:11px}.lds-default div:nth-child(7){animation-delay:-.6s;top:37px;left:7px}.lds-default div:nth-child(8){animation-delay:-.7s;top:52px;left:11px}.lds-default div:nth-child(9){animation-delay:-.8s;top:62px;left:22px}.lds-default div:nth-child(10){animation-delay:-.9s;top:66px;left:37px}.lds-default div:nth-child(11){animation-delay:-1s;top:62px;left:52px}.lds-default div:nth-child(12){animation-delay:-1.1s;top:52px;left:62px}@keyframes lds-default{0%,100%,20%,80%{transform:scale(1)}50%{transform:scale(1.5)}}</style>
			<div class="lds-default"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>

			<?php elseif ($set->loader_type=='lds-grid'): ?>
			<style>.lds-grid{display:inline-block;position:relative;width:80px;height:80px}.lds-grid div{position:absolute;width:16px;height:16px;border-radius:50%;background:<?php echo $set->loader_color1; ?>;animation:lds-grid 1.2s linear infinite}.lds-grid div:nth-child(1){top:8px;left:8px;animation-delay:0s}.lds-grid div:nth-child(2){top:8px;left:32px;animation-delay:-.4s}.lds-grid div:nth-child(3){top:8px;left:56px;animation-delay:-.8s}.lds-grid div:nth-child(4){top:32px;left:8px;animation-delay:-.4s}.lds-grid div:nth-child(5){top:32px;left:32px;animation-delay:-.8s}.lds-grid div:nth-child(6){top:32px;left:56px;animation-delay:-1.2s}.lds-grid div:nth-child(7){top:56px;left:8px;animation-delay:-.8s}.lds-grid div:nth-child(8){top:56px;left:32px;animation-delay:-1.2s}.lds-grid div:nth-child(9){top:56px;left:56px;animation-delay:-1.6s}@keyframes lds-grid{0%,100%{opacity:1}50%{opacity:.5}}</style>
			<div class="lds-grid"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>

			<?php elseif ($set->loader_type=='lds-hourglass'): ?>
			<style>.lds-hourglass{display:inline-block;position:relative;width:80px;height:80px}.lds-hourglass:after{content:" ";display:block;border-radius:50%;width:0;height:0;margin:8px;box-sizing:border-box;border:32px solid <?php echo $set->loader_color1; ?>;border-color:<?php echo $set->loader_color1; ?> transparent <?php echo $set->loader_color1; ?> transparent;animation:lds-hourglass 1.2s infinite}@keyframes lds-hourglass{0%{transform:rotate(0);animation-timing-function:cubic-bezier(.55,.055,.675,.19)}50%{transform:rotate(900deg);animation-timing-function:cubic-bezier(.215,.61,.355,1)}100%{transform:rotate(1800deg)}}</style>
			<div class="lds-hourglass"></div>

			<?php elseif ($set->loader_type=='lds-ripple'): ?>
			<style>.lds-ripple{display:inline-block;position:relative;width:80px;height:80px}.lds-ripple div{position:absolute;border:4px solid <?php echo $set->loader_color1; ?>;opacity:1;border-radius:50%;animation:lds-ripple 1s cubic-bezier(0,.2,.8,1) infinite}.lds-ripple div:nth-child(2){animation-delay:-.5s}@keyframes lds-ripple{0%{top:36px;left:36px;width:0;height:0;opacity:1}100%{top:0;left:0;width:72px;height:72px;opacity:0}}</style>
			<div class="lds-ripple"><div></div><div></div></div>

			<?php elseif ($set->loader_type=='lds-spinner'): ?>
			<style>.lds-spinner{color:official;display:inline-block;position:relative;width:80px;height:80px}.lds-spinner div{transform-origin:40px 40px;animation:lds-spinner 1.2s linear infinite}.lds-spinner div:after{content:" ";display:block;position:absolute;top:3px;left:37px;width:6px;height:18px;border-radius:20%;background:<?php echo $set->loader_color1; ?>}.lds-spinner div:nth-child(1){transform:rotate(0);animation-delay:-1.1s}.lds-spinner div:nth-child(2){transform:rotate(30deg);animation-delay:-1s}.lds-spinner div:nth-child(3){transform:rotate(60deg);animation-delay:-.9s}.lds-spinner div:nth-child(4){transform:rotate(90deg);animation-delay:-.8s}.lds-spinner div:nth-child(5){transform:rotate(120deg);animation-delay:-.7s}.lds-spinner div:nth-child(6){transform:rotate(150deg);animation-delay:-.6s}.lds-spinner div:nth-child(7){transform:rotate(180deg);animation-delay:-.5s}.lds-spinner div:nth-child(8){transform:rotate(210deg);animation-delay:-.4s}.lds-spinner div:nth-child(9){transform:rotate(240deg);animation-delay:-.3s}.lds-spinner div:nth-child(10){transform:rotate(270deg);animation-delay:-.2s}.lds-spinner div:nth-child(11){transform:rotate(300deg);animation-delay:-.1s}.lds-spinner div:nth-child(12){transform:rotate(330deg);animation-delay:0s}@keyframes lds-spinner{0%{opacity:1}100%{opacity:0}}</style>
			<div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
			<?php endif; ?>
		</div>

		<script>jQuery(document).ready(function($) {
			$("#<?php echo $set->id; ?>").fadeOut(500);
			$(window).on('beforeunload', function() {
				$("#<?php echo $set->id; ?>").fadeIn(500);
			});
		});</script>
		<?php return ob_get_clean();
	}
}
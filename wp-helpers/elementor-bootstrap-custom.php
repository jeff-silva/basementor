<?php

if (! BASEMENTOR_ELEMENTOR) { return; }

add_action('elementor/widgets/widgets_registered', function($manager) {
	class Elementor_Bootstrap_Custom extends \Elementor\Widget_Base {

		public function get_name() {
			return __CLASS__;
		}

		public function get_title() {
			return preg_replace('/[^a-zA-Z0-9]/', ' ', __CLASS__);
		}

		// https://ecomfe.github.io/eicons/demo/demo.html
		public function get_icon() {
			return 'eicon-editor-code';
		}

		public function get_categories() {
			return [ 'general' ];
		}

		public function get_script_depends() {
			return [];
		}

		public function get_style_depends() {
			return [];
		}

		protected function _register_controls() {
			$this->start_controls_section('elementor_bootstrap_custom_css', [
				'label' => 'CSS',
			]);

			$this->add_control('border_radius', [
				'label' => 'Border Radius',
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 0,
			]);

			$this->add_control('css', [
				'label' => 'CSS',
				'type' => \Elementor\Controls_Manager::CODE,
				'default' => '',
			]);

			$this->add_control('bootswatch', [
				'label' => 'Bootswatch',
				'type' => \Elementor\Controls_Manager::SELECT2,
				'label_block' => true,
				'default' => '',
				'options' => [
					'cerulean' => 'Cerulean',
					'cosmo' => 'Cosmo',
					'cyborg' => 'Cyborg',
					'darkly' => 'Darkly',
					'flatly' => 'Flatly',
					'journal' => 'Journal',
					'litera' => 'Litera',
					'lumen' => 'Lumen',
					'lux' => 'Lux',
					'materia' => 'Materia',
					'minty' => 'Minty',
					'pulse' => 'Pulse',
					'sandstone' => 'Sandstone',
					'simplex' => 'Simplex',
					'sketchy' => 'Sketchy',
					'slate' => 'Slate',
					'solar' => 'Solar',
					'spacelab' => 'Spacelab',
					'superhero' => 'Superhero',
					'united' => 'United',
					'yeti' => 'Yeti',
				],
			]);

			$this->add_control('bootswatch_preview', [
				'label' => 'Bootswatch preview elements',
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => '',
			]);

			$repeater = new \Elementor\Repeater();

			$repeater->add_control('font', [
				'label' => 'Fonte',
				'type' => \Elementor\Controls_Manager::FONT,
				'default' => '',
				'label_block' => true,
			]);

			$repeater->add_control('selector', [
				'label' => 'Seletor',
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'label_block' => true,
			]);

			$repeater->add_control('important', [
				'label' => 'Importante',
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => '',
				'label_block' => true,
			]);

			$repeater->add_control('style', [
				'label' => 'Style',
				'type' => \Elementor\Controls_Manager::CODE,
				'default' => '',
				'label_block' => true,
			]);

			$this->add_control('fonts', [
				'label' => 'Fontes',
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [],
				'title_field' => '{{{ selector }}}',
			]);

			$this->end_controls_section();






			$this->start_controls_section('elementor_bootstrap_custom_loader', [
				'label' => 'Loader',
			]);

			$this->add_control('loader_active', [
				'label' => 'Usar Loader',
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => '',
			]);

			$this->add_control('loader_type', [
				'label' => 'Tipo de loader',
				'type' => \Elementor\Controls_Manager::SELECT2,
				'default' => 'lds-default',
				'options' => [
					'lds-default' => 'Default',
					'lds-circle' => 'Circle',
					'lds-dual-ring' => 'Dual Ring',
					'lds-facebook' => 'Facebook',
					'lds-heart' => 'Heart',
					'lds-ring' => 'Ring',
					'lds-roller' => 'Roller',
					'lds-ellipsis' => 'Ellipsis',
					'lds-grid' => 'Grid',
					'lds-hourglass' => 'Hourglass',
					'lds-ripple' => 'Ripple',
					'lds-spinner' => 'Spinner',
				],
			]);

			$this->add_control('loader_bg', [
				'label' => 'Cor',
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#FFFFFF',
			]);

			$this->add_control('loader_color1', [
				'label' => 'Cor',
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#666666',
			]);

			$this->add_control('loader_content', [
				'label' => 'Visualizar loader',
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'default' => '',
			]);

			$this->add_control('loader_test', [
				'label' => 'Visualizar loader',
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => '',
			]);

			$this->end_controls_section();






			$this->start_controls_section('elementor_bootstrap_custom_colors', [
				'label' => 'Colors',
			]);

			$this->add_control('color_dark', [
				'label' => 'Qtde. escurecimento cor',
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => '-50',
			]);

			$this->add_control('color_light', [
				'label' => 'Qtde. clareamento cor',
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => '50',
			]);

			$repeater = new \Elementor\Repeater();

			$repeater->add_control('prefix', [
				'label' => 'Prefixo',
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'label_block' => true,
			]);

			$repeater->add_control('color', [
				'label' => 'Cor',
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'label_block' => true,
			]);

			$repeater->add_control('color_inverse', [
				'label' => 'Cor inversa',
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'label_block' => true,
			]);

			$this->add_control('prefixes', [
				'label' => 'Prefixos & Cores',
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					['color'=>'#007bff', 'color_inverse'=>'#ffffff', 'prefix'=>'primary'],
					['color'=>'#6c757d', 'color_inverse'=>'#666666', 'prefix'=>'secondary'],
					['color'=>'#28a745', 'color_inverse'=>'#ffffff', 'prefix'=>'success'],
					['color'=>'#dc3545', 'color_inverse'=>'#ffffff', 'prefix'=>'danger'],
					['color'=>'#ffc107', 'color_inverse'=>'#ffffff', 'prefix'=>'warning'],
					['color'=>'#17a2b8', 'color_inverse'=>'#ffffff', 'prefix'=>'info'],
					['color'=>'#3b5999', 'color_inverse'=>'#ffffff', 'prefix'=>'facebook'],
					['color'=>'#55acee', 'color_inverse'=>'#ffffff', 'prefix'=>'twitter'],
					['color'=>'#0077b5', 'color_inverse'=>'#ffffff', 'prefix'=>'linkedin'],
					['color'=>'#00aff0', 'color_inverse'=>'#ffffff', 'prefix'=>'skype'],
					['color'=>'#007ee5', 'color_inverse'=>'#ffffff', 'prefix'=>'dropbox'],
					['color'=>'#21759b', 'color_inverse'=>'#ffffff', 'prefix'=>'wordpress'],
					['color'=>'#1ab7ea', 'color_inverse'=>'#ffffff', 'prefix'=>'vimeo'],
					['color'=>'#4c75a3', 'color_inverse'=>'#ffffff', 'prefix'=>'vk'],
					['color'=>'#34465d', 'color_inverse'=>'#ffffff', 'prefix'=>'tumblr'],
					['color'=>'#410093', 'color_inverse'=>'#ffffff', 'prefix'=>'yahoo'],
					['color'=>'#bd081c', 'color_inverse'=>'#ffffff', 'prefix'=>'pinterest'],
					['color'=>'#cd201f', 'color_inverse'=>'#ffffff', 'prefix'=>'youtube'],
					['color'=>'#ff5700', 'color_inverse'=>'#ffffff', 'prefix'=>'reddit'],
					['color'=>'#b92b27', 'color_inverse'=>'#ffffff', 'prefix'=>'quora'],
					['color'=>'#ff3300', 'color_inverse'=>'#ffffff', 'prefix'=>'soundcloud'],
					['color'=>'#25d366', 'color_inverse'=>'#ffffff', 'prefix'=>'whatsapp'],
					['color'=>'#e4405f', 'color_inverse'=>'#ffffff', 'prefix'=>'instagram'],
				],
				'title_field' => '{{{ prefix }}}',
			]);

			$this->end_controls_section();
		}

		protected function render() {
			$set = json_decode(json_encode($this->get_settings()));
			$set->id = uniqid('elementor-bootstrap-custom-');
			$set->edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
			?><style><?php

			$lines = [];

			if ($set->bootswatch) {
				$lines[] = "@import url('https://cdnjs.cloudflare.com/ajax/libs/bootswatch/4.4.1/{$set->bootswatch}/bootstrap.min.css');\n";
			}

			foreach($set->fonts as $font) {
				$lines[] = "@import url('https://fonts.googleapis.com/css?family={$font->font}&display=swap');\n";
			}

			foreach($set->fonts as $font) {
				if (! $font->selector) continue;
				$important = $font->important? '!important': null;
				$lines[] = "{$font->selector} {font-family:'{$font->font}'{$important}; {$font->style}}";
			}

			foreach($set->prefixes as $p) {
				$prefix = $p->prefix;
				$color = $p->color;
				$inverse = $p->color_inverse;
				$dark = $this->color($p->color, $set->color_dark);
				$light = $this->color($p->color, $set->color_light);
				$lines[] = ".text-{$prefix}, .text-{$prefix}:hover {color:{$color} !important;}";
				$lines[] = ".bg-{$prefix}-light {background-color:{$light} !important;}";
				$lines[] = ".bg-{$prefix}-dark {background-color:{$dark} !important;}";
				$lines[] = ".bg-{$prefix} {background-color:{$color} !important; color:{$inverse};}";
				$lines[] = ".btn-{$prefix} {background-color:{$color} !important; border-color:{$color};}";
				$lines[] = ".btn-{$prefix}-light {background-color:{$light} !important; border-color:{$light};}";
				$lines[] = ".btn-{$prefix}-dark {background-color:{$dark} !important; border-color:{$dark};}";
				$lines[] = ".btn-{$prefix}:hover, .btn-{$prefix}:active {background-color:{$dark} !important; border-color:{$dark};}";
				$lines[] = ".border-{$prefix} {border-color:{$color} !important;}";
				$lines[] = ".alert-{$prefix} {background-color:{$light};}";
			}

			echo "\n". implode('', $lines) . $set->css; ?></style>

			<?php if ($set->edit_mode && $set->bootswatch_preview): ?>
			<br>
			<div class="row">
				<div class="col-3">
					<input type="text" class="form-control">
				</div>

				<div class="col-3">
					<div class="input-group">
						<input type="text" class="form-control">
						<div class="input-group-btn">
							<button type="button" class="btn btn-primary">
								<i class="fa fa-fw fa-search"></i>
							</button>
						</div>
					</div>
				</div>

				<div class="col-12 py-2"><hr></div>

				<?php foreach($set->prefixes as $p): ?>
				<div class="col-3">
					<div class="card border-<?php echo $p->prefix; ?>">
						<div class="card-header bg-<?php echo $p->prefix; ?>"><?php echo $p->prefix; ?></div>
						<div class="card-body">
							<a href="javascript:;" class="btn btn-<?php echo $p->prefix; ?> btn-block">btn btn-<?php echo $p->prefix; ?></a>
							<br>
							<div class="border border-<?php echo $p->prefix; ?> p-1">
								<div class="bg-<?php echo $p->prefix; ?> p-2">
									text
								</div>
							</div>
							<br>
							<div class="alert alert-<?php echo $p->prefix; ?>">alert alert-<?php echo $p->prefix; ?></div>
						</div>
					</div><br>
				</div>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>

			<div id="<?php echo $set->id; ?>" style="position:fixed; top:0px; left:0px; width:100%; height:100%; background:<?php echo $set->loader_bg; ?>; z-index:99999!important; display:flex; align-items:center; justify-content:center;">
				<div>
					<?php echo $set->loader_content; ?>
					
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
			</div>

			<script>jQuery(document).ready(function($) {
				<?php if ($set->edit_mode AND $set->loader_test): ?>
				// 

				<?php else: ?>
				$("#<?php echo $set->id; ?>").fadeOut(500);
				$(window).on('beforeunload', function() {
					$("#<?php echo $set->id; ?>").fadeIn(500);
				});
				<?php endif; ?>
			});</script>
			<?php
		}

		protected function content_template() {}


		public function color($hex, $add=null) {
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
		}
	}

	$manager->register_widget_type(new \Elementor_Bootstrap_Custom());
});


/*

Fonts
Colors
Loader

*/
<?php

if (! BASEMENTOR_ELEMENTOR) { return; }

add_action('elementor/widgets/widgets_registered', function($manager) {
	if (class_exists('Elementor_Bootstrap_Custom')) return;
	
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
				'type' => \Elementor\Controls_Manager::SELECT,
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

			$repeater->add_control('color_text', [
				'label' => 'Texto',
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'label_block' => true,
			]);

			$this->add_control('prefixes', [
				'label' => 'Prefixos & Cores',
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					['color'=>'#007bff', 'color_text'=>'#ffffff', 'prefix'=>'primary'],
					['color'=>'#6c757d', 'color_text'=>'#666666', 'prefix'=>'secondary'],
					['color'=>'#28a745', 'color_text'=>'#ffffff', 'prefix'=>'success'],
					['color'=>'#dc3545', 'color_text'=>'#ffffff', 'prefix'=>'danger'],
					['color'=>'#ffc107', 'color_text'=>'#ffffff', 'prefix'=>'warning'],
					['color'=>'#17a2b8', 'color_text'=>'#ffffff', 'prefix'=>'info'],
					['color'=>'#3b5999', 'color_text'=>'#ffffff', 'prefix'=>'facebook'],
					['color'=>'#55acee', 'color_text'=>'#ffffff', 'prefix'=>'twitter'],
					['color'=>'#0077b5', 'color_text'=>'#ffffff', 'prefix'=>'linkedin'],
					['color'=>'#00aff0', 'color_text'=>'#ffffff', 'prefix'=>'skype'],
					['color'=>'#007ee5', 'color_text'=>'#ffffff', 'prefix'=>'dropbox'],
					['color'=>'#21759b', 'color_text'=>'#ffffff', 'prefix'=>'wordpress'],
					['color'=>'#1ab7ea', 'color_text'=>'#ffffff', 'prefix'=>'vimeo'],
					['color'=>'#4c75a3', 'color_text'=>'#ffffff', 'prefix'=>'vk'],
					['color'=>'#34465d', 'color_text'=>'#ffffff', 'prefix'=>'tumblr'],
					['color'=>'#410093', 'color_text'=>'#ffffff', 'prefix'=>'yahoo'],
					['color'=>'#bd081c', 'color_text'=>'#ffffff', 'prefix'=>'pinterest'],
					['color'=>'#cd201f', 'color_text'=>'#ffffff', 'prefix'=>'youtube'],
					['color'=>'#ff5700', 'color_text'=>'#ffffff', 'prefix'=>'reddit'],
					['color'=>'#b92b27', 'color_text'=>'#ffffff', 'prefix'=>'quora'],
					['color'=>'#ff3300', 'color_text'=>'#ffffff', 'prefix'=>'soundcloud'],
					['color'=>'#25d366', 'color_text'=>'#ffffff', 'prefix'=>'whatsapp'],
					['color'=>'#e4405f', 'color_text'=>'#ffffff', 'prefix'=>'instagram'],
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
			
			$lines[] = ".input-group.form-control {padding:0px;}";
			$lines[] = ".input-group.form-control .btn {border:transparent!important; border-radius:0px; display:inline-block!important;}";
			$lines[] = ".input-group.form-control .form-control, .input-group.form-control .input-group-text {border:transparent!important; background:none; border-radius:0px;}";
			$lines[] = ".basementor-woocommerce-price del {}";
			$lines[] = ".basementor-woocommerce-price ins {text-decoration: none;}";

			foreach($set->prefixes as $p) {
				$prefix = $p->prefix;
				$color = $p->color;
				$text = $p->color_text;
				$dark = $this->color($p->color, $set->color_dark);
				$light = $this->color($p->color, $set->color_light);
				$lines[] = ".text-{$prefix}, .text-{$prefix}:hover {color:{$color} !important;}";
				$lines[] = ".bg-{$prefix}-light {background-color:{$light} !important;}";
				$lines[] = ".bg-{$prefix}-dark {background-color:{$dark} !important;}";
				$lines[] = ".bg-{$prefix} {background-color:{$color} !important; color:{$text};}";
				$lines[] = ".btn-{$prefix} {background-color:{$color} !important; border-color:{$color}; color:{$text};}";
				$lines[] = ".btn-{$prefix}-light {background-color:{$light} !important; border-color:{$light}; color:{$text};}";
				$lines[] = ".btn-{$prefix}-dark {background-color:{$dark} !important; border-color:{$dark}; color:{$text};}";
				$lines[] = ".btn-{$prefix}:hover, .btn-{$prefix}:active {background-color:{$dark} !important; border-color:{$dark}; color:{$text};}";
				$lines[] = ".btn-outline-{$prefix} {border-color:{$color} !important; color:{$color};}";
				$lines[] = ".btn-outline-{$prefix}:hover {background-color:{$color} !important; color:{$text};}";
				$lines[] = ".border-{$prefix} {border-color:{$color} !important;}";
				$lines[] = ".alert-{$prefix} {background-color:{$light}; color:{$text};}";
				$lines[] = ".badge-{$prefix} {background-color:{$color}; color:{$text};}";
			}

			echo "\n". implode('', $lines) . $set->css; ?></style>

			<?php if ($set->edit_mode && $set->bootswatch_preview):

			$test = (object) ['prefix'=>'', 'prefixes'=>[]];
			foreach($set->prefixes as $prefix) {
				if (! $test->prefix) { $test->prefix = $prefix->prefix; }
				$test->prefixes[ $prefix->prefix ] = ucwords($prefix->prefix);
			}

			?>
			<br><br><br>
			<div id="bootswatch_preview" class="container">
				<select v-model="prefix" class="form-control mp-2" style="max-width:300px;">
					<option :value="prefix" v-for="(name, prefix) in prefixes">{{ name }}</option>
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
			</div>

			<script>new Vue({
				el: "#bootswatch_preview",
				data: <?php echo json_encode($test); ?>,
			});</script>
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


add_filter('woocommerce_get_price_html', function($price, $product) {
	return '<div class="basementor-woocommerce-price">'. $price .'</div>';
}, 100, 2);
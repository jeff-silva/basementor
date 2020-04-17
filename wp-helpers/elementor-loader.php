<?php

add_action('elementor/widgets/widgets_registered', function($manager) {
	class Elementor_Loader extends \Elementor\Widget_Base {

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
			$this->start_controls_section('section_heading', [
				'label' => 'Configurações',
			]);

			$this->add_control('type', [
				'label' => 'Tipo',
				'type' => \Elementor\Controls_Manager::SELECT2,
				'default' => 'lds-default',
				'options' => [
					'lds-default' => 'lds-default',
					'lds-circle' => 'lds-circle',
					'lds-dual-ring' => 'dlds-ual-ring',
					'lds-facebook' => 'lds-facebook',
					'lds-heart' => 'lds-heart',
					'lds-ring' => 'lds-ring',
					'lds-roller' => 'lds-roller',
					'lds-ellipsis' => 'lds-ellipsis',
					'lds-grid' => 'lds-grid',
					'lds-hourglass' => 'lds-hourglass',
					'lds-ripple' => 'lds-ripple',
					'lds-spinner' => 'lds-spinner',
				],
			]);

			$this->add_control('bg', [
				'label' => 'Background color',
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#ffffff',
			]);

			$this->add_control('color1', [
				'label' => 'Color',
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#ff0000',
			]);

			$this->add_control('test', [
				'label' => 'Enable test',
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => '',
			]);

			$this->end_controls_section();
		}

		protected function render() {
			$set = json_decode(json_encode($this->get_settings()));
			$set->id = uniqid('elementor-loader-');

			?>

			<?php if (\Elementor\Plugin::$instance->editor->is_edit_mode()): ?>
			<div class="text-center">Loader area</div>
			<?php endif; ?>

			<div id="<?php echo $set->id; ?>" style="position:fixed; top:0px; left:0px; width:100%; height:100%; background:<?php echo $set->bg; ?>; z-index:999; display:flex; align-items:center; justify-content:center;">
				<?php if ($set->type=='lds-default'): ?>
				<style>.lds-default{display:inline-block;position:relative;width:80px;height:80px}.lds-default div{position:absolute;width:6px;height:6px;background:<?php echo $set->color1; ?>;border-radius:50%;animation:lds-default 1.2s linear infinite}.lds-default div:nth-child(1){animation-delay:0s;top:37px;left:66px}.lds-default div:nth-child(2){animation-delay:-.1s;top:22px;left:62px}.lds-default div:nth-child(3){animation-delay:-.2s;top:11px;left:52px}.lds-default div:nth-child(4){animation-delay:-.3s;top:7px;left:37px}.lds-default div:nth-child(5){animation-delay:-.4s;top:11px;left:22px}.lds-default div:nth-child(6){animation-delay:-.5s;top:22px;left:11px}.lds-default div:nth-child(7){animation-delay:-.6s;top:37px;left:7px}.lds-default div:nth-child(8){animation-delay:-.7s;top:52px;left:11px}.lds-default div:nth-child(9){animation-delay:-.8s;top:62px;left:22px}.lds-default div:nth-child(10){animation-delay:-.9s;top:66px;left:37px}.lds-default div:nth-child(11){animation-delay:-1s;top:62px;left:52px}.lds-default div:nth-child(12){animation-delay:-1.1s;top:52px;left:62px}@keyframes lds-default{0%,100%,20%,80%{transform:scale(1)}50%{transform:scale(1.5)}}</style>
				<div class="lds-default"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
				
				<?php elseif ($set->type=='lds-circle'): ?>
				<style>.lds-circle{display:inline-block;transform:translateZ(1px)}.lds-circle>div{display:inline-block;width:64px;height:64px;margin:8px;border-radius:50%;background:<?php echo $set->color1; ?>;animation:lds-circle 2.4s cubic-bezier(0,.2,.8,1) infinite}@keyframes lds-circle{0%,100%{animation-timing-function:cubic-bezier(.5,0,1,.5)}0%{transform:rotateY(0)}50%{transform:rotateY(1800deg);animation-timing-function:cubic-bezier(0,.5,.5,1)}100%{transform:rotateY(3600deg)}}</style>
				<div class="lds-circle"><div></div></div>
				
				<?php elseif ($set->type=='lds-dual-ring'): ?>
				<style>.lds-dual-ring{display:inline-block;width:80px;height:80px}.lds-dual-ring:after{content:" ";display:block;width:64px;height:64px;margin:8px;border-radius:50%;border:6px solid <?php echo $set->color1; ?>;border-color:<?php echo $set->color1; ?> transparent <?php echo $set->color1; ?> transparent;animation:lds-dual-ring 1.2s linear infinite}@keyframes lds-dual-ring{0%{transform:rotate(0)}100%{transform:rotate(360deg)}}</style>
				<div class="lds-dual-ring"></div>
				
				<?php elseif ($set->type=='lds-facebook'): ?>
				<style>.lds-facebook{display:inline-block;position:relative;width:80px;height:80px}.lds-facebook div{display:inline-block;position:absolute;left:8px;width:16px;background:<?php echo $set->color1; ?>;animation:lds-facebook 1.2s cubic-bezier(0,.5,.5,1) infinite}.lds-facebook div:nth-child(1){left:8px;animation-delay:-.24s}.lds-facebook div:nth-child(2){left:32px;animation-delay:-.12s}.lds-facebook div:nth-child(3){left:56px;animation-delay:0}@keyframes lds-facebook{0%{top:8px;height:64px}100%,50%{top:24px;height:32px}}</style>
				<div class="lds-facebook"><div></div><div></div><div></div></div>
				
				<?php elseif ($set->type=='lds-heart'): ?>
				<style>.lds-heart{display:inline-block;position:relative;width:80px;height:80px;transform:rotate(45deg);transform-origin:40px 40px}.lds-heart div{top:32px;left:32px;position:absolute;width:32px;height:32px;background:<?php echo $set->color1; ?>;animation:lds-heart 1.2s infinite cubic-bezier(.215,.61,.355,1)}.lds-heart div:after,.lds-heart div:before{content:" ";position:absolute;display:block;width:32px;height:32px;background:<?php echo $set->color1; ?>}.lds-heart div:before{left:-24px;border-radius:50% 0 0 50%}.lds-heart div:after{top:-24px;border-radius:50% 50% 0 0}@keyframes lds-heart{0%{transform:scale(.95)}5%{transform:scale(1.1)}39%{transform:scale(.85)}45%{transform:scale(1)}60%{transform:scale(.95)}100%{transform:scale(.9)}}</style>
				<div class="lds-heart"><div></div></div>
				
				<?php elseif ($set->type=='lds-ring'): ?>
				<style>.lds-ring{display:inline-block;position:relative;width:80px;height:80px}.lds-ring div{box-sizing:border-box;display:block;position:absolute;width:64px;height:64px;margin:8px;border:8px solid <?php echo $set->color1; ?>;border-radius:50%;animation:lds-ring 1.2s cubic-bezier(.5,0,.5,1) infinite;border-color:<?php echo $set->color1; ?> transparent transparent transparent}.lds-ring div:nth-child(1){animation-delay:-.45s}.lds-ring div:nth-child(2){animation-delay:-.3s}.lds-ring div:nth-child(3){animation-delay:-.15s}@keyframes lds-ring{0%{transform:rotate(0)}100%{transform:rotate(360deg)}}</style>
				<div class="lds-ring"><div></div><div></div><div></div><div></div></div>
				
				<?php elseif ($set->type=='lds-roller'): ?>
				<style>.lds-roller{display:inline-block;position:relative;width:80px;height:80px}.lds-roller div{animation:lds-roller 1.2s cubic-bezier(.5,0,.5,1) infinite;transform-origin:40px 40px}.lds-roller div:after{content:" ";display:block;position:absolute;width:7px;height:7px;border-radius:50%;background:<?php echo $set->color1; ?>;margin:-4px 0 0 -4px}.lds-roller div:nth-child(1){animation-delay:-36ms}.lds-roller div:nth-child(1):after{top:63px;left:63px}.lds-roller div:nth-child(2){animation-delay:-72ms}.lds-roller div:nth-child(2):after{top:68px;left:56px}.lds-roller div:nth-child(3){animation-delay:-108ms}.lds-roller div:nth-child(3):after{top:71px;left:48px}.lds-roller div:nth-child(4){animation-delay:-144ms}.lds-roller div:nth-child(4):after{top:72px;left:40px}.lds-roller div:nth-child(5){animation-delay:-.18s}.lds-roller div:nth-child(5):after{top:71px;left:32px}.lds-roller div:nth-child(6){animation-delay:-216ms}.lds-roller div:nth-child(6):after{top:68px;left:24px}.lds-roller div:nth-child(7){animation-delay:-252ms}.lds-roller div:nth-child(7):after{top:63px;left:17px}.lds-roller div:nth-child(8){animation-delay:-288ms}.lds-roller div:nth-child(8):after{top:56px;left:12px}@keyframes lds-roller{0%{transform:rotate(0)}100%{transform:rotate(360deg)}}</style>
				<div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
				
				<?php elseif ($set->type=='lds-ellipsis'): ?>
				<style>.lds-default{display:inline-block;position:relative;width:80px;height:80px}.lds-default div{position:absolute;width:6px;height:6px;background:<?php echo $set->color1; ?>;border-radius:50%;animation:lds-default 1.2s linear infinite}.lds-default div:nth-child(1){animation-delay:0s;top:37px;left:66px}.lds-default div:nth-child(2){animation-delay:-.1s;top:22px;left:62px}.lds-default div:nth-child(3){animation-delay:-.2s;top:11px;left:52px}.lds-default div:nth-child(4){animation-delay:-.3s;top:7px;left:37px}.lds-default div:nth-child(5){animation-delay:-.4s;top:11px;left:22px}.lds-default div:nth-child(6){animation-delay:-.5s;top:22px;left:11px}.lds-default div:nth-child(7){animation-delay:-.6s;top:37px;left:7px}.lds-default div:nth-child(8){animation-delay:-.7s;top:52px;left:11px}.lds-default div:nth-child(9){animation-delay:-.8s;top:62px;left:22px}.lds-default div:nth-child(10){animation-delay:-.9s;top:66px;left:37px}.lds-default div:nth-child(11){animation-delay:-1s;top:62px;left:52px}.lds-default div:nth-child(12){animation-delay:-1.1s;top:52px;left:62px}@keyframes lds-default{0%,100%,20%,80%{transform:scale(1)}50%{transform:scale(1.5)}}</style>
				<div class="lds-default"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
				
				<?php elseif ($set->type=='lds-grid'): ?>
				<style>.lds-grid{display:inline-block;position:relative;width:80px;height:80px}.lds-grid div{position:absolute;width:16px;height:16px;border-radius:50%;background:<?php echo $set->color1; ?>;animation:lds-grid 1.2s linear infinite}.lds-grid div:nth-child(1){top:8px;left:8px;animation-delay:0s}.lds-grid div:nth-child(2){top:8px;left:32px;animation-delay:-.4s}.lds-grid div:nth-child(3){top:8px;left:56px;animation-delay:-.8s}.lds-grid div:nth-child(4){top:32px;left:8px;animation-delay:-.4s}.lds-grid div:nth-child(5){top:32px;left:32px;animation-delay:-.8s}.lds-grid div:nth-child(6){top:32px;left:56px;animation-delay:-1.2s}.lds-grid div:nth-child(7){top:56px;left:8px;animation-delay:-.8s}.lds-grid div:nth-child(8){top:56px;left:32px;animation-delay:-1.2s}.lds-grid div:nth-child(9){top:56px;left:56px;animation-delay:-1.6s}@keyframes lds-grid{0%,100%{opacity:1}50%{opacity:.5}}</style>
				<div class="lds-grid"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
				
				<?php elseif ($set->type=='lds-hourglass'): ?>
				<style>.lds-hourglass{display:inline-block;position:relative;width:80px;height:80px}.lds-hourglass:after{content:" ";display:block;border-radius:50%;width:0;height:0;margin:8px;box-sizing:border-box;border:32px solid <?php echo $set->color1; ?>;border-color:<?php echo $set->color1; ?> transparent <?php echo $set->color1; ?> transparent;animation:lds-hourglass 1.2s infinite}@keyframes lds-hourglass{0%{transform:rotate(0);animation-timing-function:cubic-bezier(.55,.055,.675,.19)}50%{transform:rotate(900deg);animation-timing-function:cubic-bezier(.215,.61,.355,1)}100%{transform:rotate(1800deg)}}</style>
				<div class="lds-hourglass"></div>
				
				<?php elseif ($set->type=='lds-ripple'): ?>
				<style>.lds-ripple{display:inline-block;position:relative;width:80px;height:80px}.lds-ripple div{position:absolute;border:4px solid <?php echo $set->color1; ?>;opacity:1;border-radius:50%;animation:lds-ripple 1s cubic-bezier(0,.2,.8,1) infinite}.lds-ripple div:nth-child(2){animation-delay:-.5s}@keyframes lds-ripple{0%{top:36px;left:36px;width:0;height:0;opacity:1}100%{top:0;left:0;width:72px;height:72px;opacity:0}}</style>
				<div class="lds-ripple"><div></div><div></div></div>
				
				<?php elseif ($set->type=='lds-spinner'): ?>
				<style>.lds-spinner{color:official;display:inline-block;position:relative;width:80px;height:80px}.lds-spinner div{transform-origin:40px 40px;animation:lds-spinner 1.2s linear infinite}.lds-spinner div:after{content:" ";display:block;position:absolute;top:3px;left:37px;width:6px;height:18px;border-radius:20%;background:<?php echo $set->color1; ?>}.lds-spinner div:nth-child(1){transform:rotate(0);animation-delay:-1.1s}.lds-spinner div:nth-child(2){transform:rotate(30deg);animation-delay:-1s}.lds-spinner div:nth-child(3){transform:rotate(60deg);animation-delay:-.9s}.lds-spinner div:nth-child(4){transform:rotate(90deg);animation-delay:-.8s}.lds-spinner div:nth-child(5){transform:rotate(120deg);animation-delay:-.7s}.lds-spinner div:nth-child(6){transform:rotate(150deg);animation-delay:-.6s}.lds-spinner div:nth-child(7){transform:rotate(180deg);animation-delay:-.5s}.lds-spinner div:nth-child(8){transform:rotate(210deg);animation-delay:-.4s}.lds-spinner div:nth-child(9){transform:rotate(240deg);animation-delay:-.3s}.lds-spinner div:nth-child(10){transform:rotate(270deg);animation-delay:-.2s}.lds-spinner div:nth-child(11){transform:rotate(300deg);animation-delay:-.1s}.lds-spinner div:nth-child(12){transform:rotate(330deg);animation-delay:0s}@keyframes lds-spinner{0%{opacity:1}100%{opacity:0}}</style>
				<div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
				<?php endif; ?>
			</div>

			<script>jQuery(document).ready(function($) {
				<?php if (\Elementor\Plugin::$instance->editor->is_edit_mode() AND $set->test): ?>
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
	}

	$manager->register_widget_type(new \Elementor_Loader());
});

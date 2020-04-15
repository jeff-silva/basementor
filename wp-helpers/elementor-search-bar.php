<?php

add_action('elementor/widgets/widgets_registered', function($manager) {
	class Elementor_Search_Bar extends \Elementor\Widget_Base {

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

			$this->add_control('placeholder', [
				'label' => 'Placeholder',
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 'Pesquisar',
			]);

			$this->add_control('css', [
				'label' => 'CSS',
				'type' => \Elementor\Controls_Manager::CODE,
				'default' => '$root {}',
			]);

			$this->end_controls_section();
		}

		protected function render() {
			$set = json_decode(json_encode($this->get_settings()));
			$set->id = uniqid('elementor-search-bar-');

			$input = (object) array_merge([
				's' => '',
			], $_GET);

			?>
			<style><?php echo str_replace('$root', ".{$set->id}", $set->css); ?></style>
			<div class="<?php echo $set->id; ?>">
				<form action="" class="input-group">
					<input type="text" name="s" value="<?php echo $input->s; ?>" class="form-control" placeholder="<?php echo $set->placeholder; ?>" >
					<div class="input-group-btn">
						<button type="submit" class="btn btn-primary">
							<i class="fa fa-fw fa-search"></i>
						</button>
					</div>
				</form>
			</div>
			<?php
		}

		protected function content_template() {}
	}

	$manager->register_widget_type(new \Elementor_Search_Bar());
});

<?php

if (! BASEMENTOR_ELEMENTOR) { return; }

add_action('elementor/widgets/widgets_registered', function($manager) {
	class Elementor_List_Group extends \Elementor\Widget_Base {

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

			$repeater = new \Elementor\Repeater();

			$repeater->add_control('title', [
				'label' => 'Título',
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'label_block' => true,
			]);

			$repeater->add_control('icon', [
				'label' => 'Ícone',
				'type' => \Elementor\Controls_Manager::ICON,
				'default' => '',
				'label_block' => true,
			]);

			$repeater->add_control('link', [
				'label' => 'Ícone',
				'type' => \Elementor\Controls_Manager::URL,
				'default' => ['url'],
				'label_block' => true,
			]);

			$this->add_control('options', [
				'label' => 'Opções',
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [],
				'title_field' => '{{{ title }}}',
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
			$set->id = uniqid('elementor-list-group-');
			?>
			<div class="list-group <?php echo $set->id; ?>">
				<?php foreach($set->options as $opt): ?>
				<a href="<?php echo $opt->link->url; ?>" class="list-group-item" <?php echo $opt->link->is_external? 'target="_blank"': null; ?>>
					<?php if ($opt->icon): ?>
					<i class="<?php echo $opt->icon; ?>"></i>
					<?php endif; ?>
					<span><?php echo $opt->title; ?></span>
				</a>
				<?php endforeach; ?>
			</div>
			<style><?php echo str_replace('$root', ".{$set->id}", $set->css); ?></style>
			<?php
		}

		protected function content_template() {}
	}

	$manager->register_widget_type(new \Elementor_List_Group());
});

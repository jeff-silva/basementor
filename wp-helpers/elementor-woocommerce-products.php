<?php

add_action('elementor/widgets/widgets_registered', function($manager) {
	class Elementor_Woocommerce_Products extends \Elementor\Widget_Base {

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

			$this->add_control('query', [
				'label' => 'Query',
				'type' => \Elementor\Controls_Manager::CODE,
				'default' => '{"posts_per_page":12}',
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
			$set->id = uniqid('elementor-woocommerce-products-');

			$data = new stdClass;
			$data->query = json_decode($set->query, true);
			$data->query['post_type'] = 'product';

			$loop = new WP_Query($data->query);
			
			wc_get_template_part('loop/loop-start');
			if ($loop->have_posts()) {
				while ($loop->have_posts()) : $loop->the_post();
					wc_get_template_part('content-product');
				endwhile;
			}
			else {
				wc_get_template_part('loop/no-products-found');
			}
			wp_reset_postdata();
			wc_get_template_part('loop/loop-end');

			?>
			<style><?php echo str_replace('$root', ".{$set->id}", $set->css); ?></style>
			<?php
		}

		protected function content_template() {}
	}

	$manager->register_widget_type(new \Elementor_Woocommerce_Products());
});

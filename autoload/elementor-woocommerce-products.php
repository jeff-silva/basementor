<?php

if (! BASEMENTOR_ELEMENTOR) { return; }
if (! BASEMENTOR_WOOCOMMERCE) { return; }

add_action('elementor/widgets/widgets_registered', function($manager) {
	if (class_exists('Elementor_Woocommerce_Products')) return;
	
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

			$this->add_control('featured', [
				'label' => 'Em destaque',
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => '',
			]);

			$this->add_control('sale', [
				'label' => 'Em promoção',
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => '',
			]);

			$this->add_control('query', [
				'label' => 'Query customizada',
				'type' => \Elementor\Controls_Manager::CODE,
				'default' => '{"posts_per_page":12}',
			]);

			$this->add_control('slider', [
				'label' => 'Ativar/Desativar slider',
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => '',
			]);

			$this->add_control('slider_settings', [
				'label' => 'Configurações de slider <br><a href="https://kenwheeler.github.io/slick/" target="_blank">https://kenwheeler.github.io/slick/</a>',
				'type' => \Elementor\Controls_Manager::CODE,
				'default' => '{infinite: true, slidesToShow: 4, slidesToScroll: 4}',
			]);

			$this->add_control('slider_settings_prev', [
				'label' => 'Ícone esquerda',
				'type' => \Elementor\Controls_Manager::ICON,
				'default' => 'fa fa-chevron-left',
			]);

			$this->add_control('slider_settings_next', [
				'label' => 'Ícone direita',
				'type' => \Elementor\Controls_Manager::ICON,
				'default' => 'fa fa-chevron-right',
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
			
			$data->query['meta_query'] = isset($data->query['meta_query'])?
				$data->query['meta_query']: ['relation'=>'OR'];

			$data->query['tax_query'] = isset($data->query['tax_query'])?
				$data->query['tax_query']: [];

			if ($set->featured) {
				$data->query['tax_query'][] = [
					'taxonomy'   => 'product_visibility',
					'field' => 'name',
					'terms' => 'featured',
				];
			}

			if ($set->sale) {
				$data->query['meta_query'][] = [
					'key'   => '_sale_price',
					'value' => 0,
					'compare' => '>',
					'type' => 'numeric',
				];
				$data->query['meta_query'][] = [
					'key'   => '_min_variation_sale_price',
					'value' => 0,
					'compare' => '>',
					'type' => 'numeric',
				];
			}

			$loop = new WP_Query($data->query);

			?>
			<div id="<?php echo $set->id; ?>" class="<?php echo $set->id; ?>">
				<?php if ($loop->have_posts()) {
					wc_get_template_part('loop/loop-start');
					while ($loop->have_posts()) : $loop->the_post();
						wc_get_template_part('content-product');
					endwhile;
					wc_get_template_part('loop/loop-end');
				}
				else {
					wc_get_template_part('loop/no-products-found');
				}
				wp_reset_postdata(); ?>
			</div>

			<style>
			.wpt-products.slick-slider {position:relative;}
			.wpt-products.slick-slider .slick-arrow {position:absolute; top:0px; width:60px; height:100%; border:none; background:none; z-index:9; cursor:pointer; font-size:30px; outline:none !important;}
			.wpt-products.slick-slider .slick-prev {left: 0px;}
			.wpt-products.slick-slider .slick-next {right: 0px;}

			<?php echo str_replace('$root', ".{$set->id}", $set->css); ?>
			</style>

			<?php if ($set->slider): ?>
			<script>jQuery(document).ready(function($) {
				var classes = ['col'];
				['', '-sm', '-md', '-lg'].forEach(function(s) {
					['-1', '-2', '-3', '-4', '-5', '-6', '-7', '-8', '-9', '-10', '-11', '-12'].forEach(function(n) {
						classes.push(`col${s}${n}`);
					});
				});
				classes = classes.join(' ');

				$("#<?php echo $set->id; ?> .wpt-products").each(function() {
					$(this).removeClass('row').find('>*').removeClass(classes).css({padding:10});
					var settings = <?php echo $set->slider_settings; ?>;
					settings.prevArrow = `<button type='button' class='slick-arrow slick-prev'><i class='<?php echo $set->slider_settings_prev; ?>'></i></button>`;
					settings.nextArrow = `<button type='button' class='slick-arrow slick-next'><i class='<?php echo $set->slider_settings_next; ?>'></i></button>`;
					$(this).slick(settings);
				});
			});</script>
			<?php endif; ?>
			<?php
		}

		protected function content_template() {}
	}

	$manager->register_widget_type(new \Elementor_Woocommerce_Products());
});

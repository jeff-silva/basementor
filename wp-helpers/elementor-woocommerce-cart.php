<?php

if (! BASEMENTOR_ELEMENTOR) { return; }
if (! BASEMENTOR_WOOCOMMERCE) { return; }

add_action('elementor/widgets/widgets_registered', function($manager) {
	class Elementor_Woocommerce_Cart extends \Elementor\Widget_Base {

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

			$this->add_control('template_btn', [
				'label' => 'Template botão',
				'type' => \Elementor\Controls_Manager::CODE,
				'default' => '<i class="fa fa-fw fa-shopping-cart"></i> - {{ cart.items_total }} item(ns) - {{ cart.price_total_format }}',
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
			$set->id = uniqid('elementor-woocommerce-cart-');
			$cart = \WC()->cart;

			$data = (object) ['loading'=>false];
			$data->cart = elementor_woocommerce_cart_data();

			?>
			<style><?php echo str_replace('$root', ".{$set->id}", $set->css); ?></style>
			<div id="<?php echo $set->id; ?>">
				<div style="cursor:pointer;" @click="cartOpen();">
					<?php echo $set->template_btn; ?>
				</div>
			</div>
			<script>new Vue({
				el: "#<?php echo $set->id; ?>",
				data: <?php echo json_encode($data); ?>,
				methods: {
					cartOpen() {
						window.elementorWoocommerceCartToggle();
					},
				},
			});</script>
			<?php

			add_action('wp_footer', function() use($data) {
				global $elementor_woocommerce_cart_footer;
				if ($elementor_woocommerce_cart_footer) return;
				$elementor_woocommerce_cart_footer = true;
				?>
				<style>
				.elementor-woocommerce-cart, .elementor-woocommerce-cart * {transition: all 500ms ease;}
				.elementor-woocommerce-cart {visibility:hidden; opacity:0; position:fixed; top:0px; left:0px; width:100%; height:100%; background:#00000088; z-index:99;}
				.elementor-woocommerce-cart-show {visibility:visible; opacity:1;}
				.elementor-woocommerce-cart-content {position:absolute; top:0px; right:-100%; width:100%; height:100%; max-width:500px; background:#fff; padding:15px;}
				.elementor-woocommerce-cart-show .elementor-woocommerce-cart-content {right:0px;}
				.elementor-woocommerce-cart-content iframe {position:absolute; top:0px; left:0px; width:100%; height:100%; border:none;}
				</style>
				<div class="elementor-woocommerce-cart" onclick="if (event.target==this) window.elementorWoocommerceCartToggle();">
					<div class="elementor-woocommerce-cart-content">
						<?php // woocommerce_mini_cart(); ?>
						<?php wc_get_template_part('cart/mini-cart'); ?>
					</div>
				</div>

				<script>
				window.elementorWoocommerceCartToggle = function(method) {
					var $=jQuery;
					method = method||"toggleClass";
					$(".elementor-woocommerce-cart")[method]("elementor-woocommerce-cart-show");
				};

				jQuery(document).ready(function($) {
					$(document.body).on('added_to_cart', function(ev, fragments) {
						window.elementorWoocommerceCartToggle('addClass');
						$(".elementor-woocommerce-cart-content").html(fragments['div.widget_shopping_cart_content']);
					});
				});</script>
				<?php
			});
		}

		protected function content_template() {}
	}

	$manager->register_widget_type(new \Elementor_Woocommerce_Cart());
});


if (! function_exists('elementor_woocommerce_cart_data')) {
	function elementor_woocommerce_cart_data() {
		$cart = \WC()->cart;
			
		$data = (object) [
			'show_items' => false,
			'items_total' => 0,
			'price_total' => 0,
			'price_total_format' => '',
			'items' => [],
		];

		if ($cart) {
			foreach($cart->get_cart() as $key=>$values) {
				$prod =  wc_get_product($values['product_id']);
				
				$item = new stdClass;
				$item->key = $key;
				$item->product_id = $values['product_id'];
				$item->title = $prod->get_title();
				$item->thumbnail = wp_get_attachment_url($prod->get_image_id());
				$item->quantity = $values['quantity'];

				$item->price = get_post_meta($values['product_id'] , '_price', true);
				$item->price_format = 'R$ '. number_format($item->price, 2, ',', '.');

				$item->regular_price = get_post_meta($values['product_id'] , '_regular_price', true);
				$item->regular_price_format = 'R$ '. number_format($item->regular_price, 2, ',', '.');

				$item->sale_price = get_post_meta($values['product_id'] , '_sale_price', true);
				$item->sale_price_format = 'R$ '. number_format($item->sale_price, 2, ',', '.');

				$data->items[] = $item;
				$data->items_total += $item->quantity;
				$data->price_total += ($item->price * $item->quantity);
			}
		}

		$data->price_total_format = 'R$ '. number_format($data->price_total, 2, ',', '.');
		return $data;
	}
}


if (isset($_GET['elementor-woocommerce-cart-items'])) {
	add_action('init', function() {
		$data = elementor_woocommerce_cart_data();
		echo json_encode($data); die;
	});
}

if (isset($_GET['elementor-woocommerce-cart-item-update'])) {
	add_action('init', function() {
		$item = (object) $_POST;
		if ($cart = \WC()->instance()->cart) {
			foreach($cart->get_cart() as $key=>$values) {
				if ($key==$item->key) {
					$cart->set_quantity($item->key, intval($item->quantity));
					$data = elementor_woocommerce_cart_data();
					$data->item = $item;
					echo json_encode($data); die;
				}
			}
		}
		echo json_encode([]); die;
	});
}

<?php

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

			$this->add_control('template_content', [
				'label' => 'Template carrinho',
				'type' => \Elementor\Controls_Manager::CODE,
				'default' => '<div class="row align-items-center" v-for="i in cart.items">
    <div class="col-4">
        <img :src="i.thumbnail" style="width:100%;" />
    </div>
    
    <div class="col">
        <div><strong>{{ i.title }}</strong></div>
        <div>{{ i.price_format }} &times; {{ i.quantity }}</div>
    </div>
</div>',
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
			$cart = WC()->cart;

			$data = new stdClass;
			
			$data->cart = (object) [
				'show_items' => false,
				'items_total' => 0,
				'price_total' => 0,
				'price_total_format' => '',
				'items' => [],
			];

			if ($cart) {
				foreach($cart->get_cart() as $item=>$values) {
					$prod =  wc_get_product($values['product_id']);
					
					$item = new stdClass;
					$item->title = $prod->get_title();
					$item->thumbnail = wp_get_attachment_url($prod->get_image_id());
					$item->quantity = $values['quantity'];

					$item->price = get_post_meta($values['product_id'] , '_price', true);
					$item->price_format = 'R$ '. number_format($item->price, 2, ',', '.');

					$item->regular_price = get_post_meta($values['product_id'] , '_regular_price', true);
					$item->regular_price_format = 'R$ '. number_format($item->regular_price, 2, ',', '.');

					$item->sale_price = get_post_meta($values['product_id'] , '_sale_price', true);
					$item->sale_price_format = 'R$ '. number_format($item->sale_price, 2, ',', '.');

					$data->cart->items[] = $item;
					$data->cart->items_total += $item->quantity;
					$data->cart->price_total += $item->price;
				}
			}

			$data->cart->price_total_format = 'R$ '. number_format($data->cart->price_total, 2, ',', '.');

			?>
			<style><?php echo str_replace('$root', ".{$set->id}", $set->css); ?></style>
			<div id="<?php echo $set->id; ?>">
				<div style="cursor:pointer;" @click="cart.show_items=!cart.show_items;">
					<?php echo $set->template_btn; ?>
				</div>
				<transition enter-active-class="animated fadeIn" leave-active-class="animated fadeOut">
					<div v-if="cart.show_items" style="position:fixed; top:0px; right:0px; width:100%; height:100%; background:#00000066; z-index:99; animation-duration:200ms;" @click.self="cart.show_items=false;">
						<div style="position:absolute; top:0px; right:0px; height:100%; max-width:300px; background:#fff;">
							<div class="bg-primary p-3 text-light">
								<a href="javascript:;" class="pull-right text-light" @click="cart.show_items=false;">&times;</a>
								Cart
							</div>
							<br>
							<?php echo $set->template_content; ?>
							<br>
							<div class="row no-gutters">
								<div class="col p-2">
									<a href="<?php echo wc_get_checkout_url(); ?>" class="btn btn-primary btn-block btn-sm">Finalizar compra</a>
								</div>
							</div>
					    </div>
					</div>
				</transition>
			</div>
			<script>new Vue({
				el: "#<?php echo $set->id; ?>",
				data: <?php echo json_encode($data); ?>,
				mounted() {
					window.addEventListener('keyup', (ev) => {
						if (ev.key=='Escape') {
							this.cart.show_items = false;
						}
					});
				},
			});</script>
			<!-- <?php dd($data); ?> -->
			<?php
		}

		protected function content_template() {}
	}

	$manager->register_widget_type(new \Elementor_Woocommerce_Cart());
});

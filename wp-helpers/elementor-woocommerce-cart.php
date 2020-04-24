<?php

if (! BASEMENTOR_ELEMENTOR) return;
if (! BASEMENTOR_WOOCOMMERCE) return;

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
			$cart = WC()->cart;

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
						window.elementorWoocommerceCart.cart.show_items = true;
					},
				},
			});</script>
			<!-- <?php dd($data); ?> -->
			<?php

			add_action('wp_footer', function() use($data) {
				global $elementor_woocommerce_cart_footer;
				if ($elementor_woocommerce_cart_footer) return;
				$elementor_woocommerce_cart_footer = true;
				?>
				<div id="elementor-woocommerce-cart-footer" style="display:none;">
					<transition name="custom-transition-01" enter-active-class="animated fadeIn" leave-active-class="animated fadeOut">
						<div v-if="cart.show_items" class="shadow-sm" style="position:fixed; top:0px; right:0px; width:100%; height:100%; background:#00000066; z-index:9999!important; animation-duration:500ms;" @click.self="cart.show_items=false;">
							<div style="position:absolute; top:0px; right:0px; height:100%; width:400px; max-width:100%; background:#fff; overflow:auto; animation-duration:2000ms;">
								<div class="bg-primary row no-gutters align-items-center">
									<div class="col p-2">
										<i class="fa fa-fw fa-spin fa-spinner text-light" v-if="loading"></i>
										<span class="text-uppercase font-weight-bold text-light">Carrinho</span>
									</div>
									<div class="col p-2 text-right">
										<a href="javascript:;" class="btn btn-light btn-sm text-primary" @click="cart.show_items=false;"><i class="fa fa-fw fa-remove"></i></a>
									</div>
								</div>
								<br>
								<div class="text-center text-muted" v-if="(cart.items||[]).length==0">
									Nenhum item no carrinho
								</div>

								<div class="row no-gutters align-items-center" v-for="i in cart.items">
								    <div class="col-3">
								        <img :src="i.thumbnail" style="width:100%;" />
								    </div>
								    
								    <div class="col">
								        <div><strong>{{ i.title }}</strong></div>
								        <div><input type="number" class="form-control form-control-sm" style="display:inline-block; width:70px;" v-model="i.quantity" @keyup="itemUpdate(i);"> &times; {{ i.price_format }}</div>
								    </div>
								    
								    <div clas="col-3">
								        <a href="javascript:;" class="btn btn-danger btn-sm" @click="itemRemove(i);">
								        	<i class="fa fa-fw fa-remove"></i>
								        </a>
								    </div>
								</div>
								<br>
								<div class="row no-gutters">
									<div class="col-12 p-2">
										<div class="p-2 text-right"><strong>Total: {{ cart.price_total_format }}</strong></div>
										<a href="<?php echo wc_get_checkout_url(); ?>" class="btn btn-primary btn-block btn-lg">Finalizar compra</a>
									</div>
								</div>
							</div>
						</div>
					</transition>
				</div>

				<script>window.elementorWoocommerceCart = new Vue({
					el: "#elementor-woocommerce-cart-footer",
					data: <?php echo json_encode($data); ?>,
					methods: {
						itemRemove(item) {
							var $=jQuery, post=Object.assign({}, item);
							post.quantity = 0;
							this.itemUpdate(post);
						},

						itemUpdate(item) {
							var $=jQuery, post=Object.assign({}, item);

							if (window.elementorWoocommerceCartItemUpdateTimeout) {
								clearTimeout(window.elementorWoocommerceCartItemUpdateTimeout);
							}

							window.elementorWoocommerceCartItemUpdateTimeout = setTimeout(() => {
								this.loading = true;
								$.post('?elementor-woocommerce-cart-item-update', post, (resp) => {
									this.cart = resp;
									this.cart.show_items = true;
									this.loading = false;
								}, "json");
							}, 1000);
						},

						cartRefresh() {
							var $=jQuery;

							this.loading = true;
							$.get('?elementor-woocommerce-cart-items', (resp) => {
								this.cart = resp;
								this.cart.show_items = true;
								this.loading = false;
							}, "json");
						},
					},
					mounted() {
						window.addEventListener('keyup', (ev) => {
							if (ev.key=='Escape') {
								this.cart.show_items = false;
							}
						});

						jQuery(document).ready(($) => {
							$(document.body).on('added_to_cart', (ev) => {
								this.cartRefresh();
							});
						});

						jQuery(this.$el).css({display:"block"});
					},
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
		$cart = WC()->cart;
			
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
		if ($cart = WC()->instance()->cart) {
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
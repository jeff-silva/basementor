<?php

if (isset($_GET['test'])) {
	add_action('init', function() {
		$postcode_start = intval(get_option('woocommerce_store_postcode'));
		$values = [];

		add_filter('woocommerce_correios_shipping_args', function($args) {
			$args['nVlPeso'] = 1;
			$args['nVlDiametro'] = 2;
			return $args;
		});

		$package = [
			'destination' => ['postcode'=>$postcode_final, 'country'=>'BR'],
			'contents' => [],
		];

		if ($zone = WC_Shipping_Zones::get_zone_matching_package($package)) {
			foreach($zone->get_shipping_methods() as $method) {
				$method->calculate_shipping($package);
				foreach($method->rates as $rate) {
					$values[] = [
						'title' => $rate->get_label(),
						'value' => $rate->get_cost(),
					];
				}
			}
		}

		dd($values);
		die;
	});
}


\Basementor\Basementor::action('woocommerce-shipping-calculator', function($post) {
	$post->postcode_start = get_option('woocommerce_store_postcode');
	$post->values = [];

	add_filter('woocommerce_correios_shipping_args', function($args) {
		$args['nVlPeso'] = 1;
		$args['nVlDiametro'] = 2;
		return $args;
	});

	$package = [
		'destination' => [
			'country' => 'BR',
			'postcode' => $post->postcode,
		],
		'contents' => [],
	];

	if ($zone = WC_Shipping_Zones::get_zone_matching_package($package)) {
		foreach($zone->get_shipping_methods() as $method) {
			$method->calculate_shipping($package);
			foreach($method->rates as $rate) {
				$post->values[] = [
					'title' => $rate->get_label(),
					'value' => $rate->get_cost(),
				];
			}
		}
	}

	return $post;
});


add_shortcode('woocommerce-shipping-calculator', function($atts=[], $content=null) {
	$data = (object) shortcode_atts([], $atts, 'bartag');
	$data->id = uniqid('woocommerce-shipping-calculator-');
	$data->loading = false;
	$data->post = ['postcode'=>''];
	$data->resp = false;

	?>
	<div id="<?php echo $data->id; ?>">
		<form action="" @submit.prevent="calculate();">
			<div class="input-group border border-primary" style="max-width:300px;">
				<input type="text" class="form-control" placeholder="Calcular frete" v-model="post.postcode">
				<div class="input-group-btn">
					<button type="submit" class="btn btn-primary">
						<i class="fa fa-fw fa-spin fa-spinner" v-if="loading"></i>
						<i class="fa fa-fw fa-search" v-else></i>
					</button>
				</div>
			</div>
			
			<div class="alert alert-success p-0 mt-2" v-if="resp">
				<div class="row align-items-center p-2" v-for="v in resp.values" :key="v">
					<div class="col">{{ v.title }}</div>
					<div class="col-6">R$ {{ v.value }}</div>
				</div>
			</div>
		</form>
	</div>

	<script>new Vue({
		el: "#<?php echo $data->id; ?>",
		data: <?php echo json_encode($data); ?>,
		methods: {
			calculate() {
				this.loading = true;
				jQuery.post("<?php echo \Basementor\Basementor::action('woocommerce-shipping-calculator'); ?>", this.post, (resp) => {
					this.loading = false;
					this.resp = resp;
				}, "json");
			},
		},
	});</script>
	<?php
});

add_action('woocommerce_after_add_to_cart_form', function() {
	echo do_shortcode('[woocommerce-shipping-calculator]');
});

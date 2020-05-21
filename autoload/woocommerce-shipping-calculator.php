<?php

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
				$days = $rate->get_meta_data();
				$days = isset($days['_delivery_forecast'])? $days['_delivery_forecast']: 0;
				$post->values[] = [
					'title' => $rate->get_label(),
					'value' => $rate->get_cost(),
					'days' => $days,
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
			<div class="input-group form-control border border-primary" style="max-width:300px;">
				<input type="text" class="form-control" placeholder="Calcular frete" v-model="post.postcode" v-mask="'#####-###'">
				<div class="input-group-btn">
					<button type="submit" class="btn btn-primary">
						<i class="fa fa-fw fa-spin fa-spinner" v-if="loading"></i>
						<i class="fa fa-fw fa-search" v-else></i>
					</button>
				</div>
			</div>

			<table class="table table-bordered mt-2">
				<tbody>
					<tr v-for="v in resp.values" :key="v">
						<td>{{ v.title }}</td>
						<td v-if="v.value>0">R$ {{ v.value }}</td>
						<td v-if="v.days>0">{{ v.days }} dias</td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/vue-the-mask@0.11.1"></script>
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

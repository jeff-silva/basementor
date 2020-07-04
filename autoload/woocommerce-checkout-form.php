<?php return;

function woocommerce_checkout_form_fields() {
	$fields = get_option('basementor-form-checkout-fields');
	$fields = json_decode($fields, true);
	$fields = is_array($fields)? $fields: [];

	if (empty($fields)) {
		$country = new \WC_Countries();

		$fields['billing'] = $country->get_address_fields($country->get_base_country(), 'billing_');
		$fields['billing'] = array_map(function($field, $field_name) {
			$field['name'] = $field_name;
			$field['class'] = implode(' ', $field['class']);
			return $field;
		}, $fields['billing'], array_keys($fields['billing']));

		$fields['shipping'] = $country->get_address_fields($country->get_base_country(), 'shipping_');
		$fields['shipping'] = array_map(function($field, $field_name) {
			$field['name'] = $field_name;
			$field['class'] = implode(' ', $field['class']);
			return $field;
		}, $fields['shipping'], array_keys($fields['shipping']));
	}

	return $fields;
}



add_action('admin_menu', function() {
	add_submenu_page('woocommerce', 'Formulário de checkout', 'Formulário de checkout', 'manage_options', __FILE__, function() {

		$data = new \stdClass;
		$data->id = uniqid(pathinfo(__FILE__, PATHINFO_FILENAME) .'-');
		$data->fields = woocommerce_checkout_form_fields();

		$data->tests = [
			['id'=>uniqid(), 'random'=>rand(0, 999), 'children'=>[]],
			['id'=>uniqid(), 'random'=>rand(0, 999), 'children'=>[]],
			['id'=>uniqid(), 'random'=>rand(0, 999), 'children'=>[]],
			['id'=>uniqid(), 'random'=>rand(0, 999), 'children'=>[]],
			['id'=>uniqid(), 'random'=>rand(0, 999), 'children'=>[]],
			['id'=>uniqid(), 'random'=>rand(0, 999), 'children'=>[]],
			['id'=>uniqid(), 'random'=>rand(0, 999), 'children'=>[]],
			['id'=>uniqid(), 'random'=>rand(0, 999), 'children'=>[]],
		];

		?><div id="<?php echo $data->id; ?>">
			<div v-for="(fds, section) in fields">
				<div>{{ section }}</div>

				<vue-draggable v-model="fds" animation="150">
					<div v-for="t in fds" :key="t.id" class="pt-2">
						<pre>{{ t }}</pre>
					</div>
				</vue-draggable>

				<!-- <div v-for="(field, field_name) in fds">
					<pre>{{ field }}</pre>
				</div> -->
			</div>
			<pre>$data: {{ $data }}</pre>
		</div>

		<?php do_action('vue'); ?>
		<script>new Vue({
			el: "#<?php echo $data->id; ?>",
			data: <?php echo json_encode($data); ?>,
		});</script><?php
	});
});

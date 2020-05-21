<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.0
 */

defined('ABSPATH') || exit;

$user = wp_get_current_user();
if ($user->data->ID==0) {
	wc_get_template_part('global/form-login');
	return;
}

?>

<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
	<div class="row">
		<?php if ( $checkout->get_checkout_fields() ) : ?>
		<div class="col-12 col-md-6">
			<?php do_action('woocommerce_checkout_before_customer_details'); ?>
			<?php do_action('woocommerce_checkout_billing'); ?>
		</div>

		<div class="col-12 col-md-6">
			<?php do_action('woocommerce_checkout_shipping'); ?>
			<?php do_action('woocommerce_checkout_after_customer_details'); ?>
		</div>
		<?php endif; ?>

		<div class="col-12">
			<h3><?php esc_html_e( 'Your order', 'woocommerce'); ?></h3>
			<?php do_action('woocommerce_checkout_before_order_review_heading'); ?>
			<?php do_action('woocommerce_checkout_before_order_review'); ?>
			<?php do_action('woocommerce_checkout_order_review'); ?>
			<?php do_action('woocommerce_checkout_after_order_review'); ?>
		</div>
	</div>
</form>

<?php do_action('woocommerce_after_checkout_form', $checkout ); ?>

<script>jQuery(document).ready(function($) {
	["billing_postcode", "shipping_postcode"].forEach(function(input_name) {
		$(`input[name=${input_name}]`).on("keyup blue", function(ev) {
			if (ev.target.value.length < 8) return;
			$.get(`https://viacep.com.br/ws/${ev.target.value.replace(/[^0-9]/g, '')}/json/`, function(resp) {
				if (input_name=="billing_postcode") {
					$("input[name=billing_address_1]").val(resp.logradouro);
					$("input[name=billing_neighborhood]").val(resp.bairro);
					$("input[name=billing_city]").val(resp.localidade);
					$("input[name=billing_state]").val(resp.uf);
				}
				else if (input_name=="shipping_postcode") {
					$("input[name=shipping_address_1]").val(resp.logradouro);
					$("input[name=shipping_neighborhood]").val(resp.bairro);
					$("input[name=shipping_city]").val(resp.localidade);
					$("input[name=shipping_state]").val(resp.uf);
				}
			}, "json");
		});
	});
});</script>

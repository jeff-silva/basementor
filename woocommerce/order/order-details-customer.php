<?php
/**
 * Order Customer Details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details-customer.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.4
 */

defined( 'ABSPATH' ) || exit;

$show_shipping = ! wc_ship_to_billing_address_only() && $order->needs_shipping_address();
$data = json_decode(json_encode($order->get_data()));
?>

<div class="card">
	<div class="card-header text-uppercase font-weight-bold">
		<?php esc_html_e( 'Billing address', 'woocommerce' ); ?>
	</div>
	<div class="card-body">
		<address>
			<div><strong><?php echo $data->billing->first_name .' '. $data->billing->last_name; ?></strong></div>
			<div><?php echo $data->billing->address_1 .' '. $data->billing->address_2; ?></div>
			<div><?php echo $data->billing->city .'/'. $data->billing->state .' - CEP: '. $data->billing->postcode; ?></div>
			<hr>
			<div><?php echo $data->billing->email .' - '. $data->billing->phone; ?></div>
		</address>
	</div>
</div>
<br>

<?php if ($show_shipping): ?>
<div class="card">
	<div class="card-header text-uppercase font-weight-bold">
		<?php esc_html_e( 'Shipping address', 'woocommerce' ); ?>
	</div>
	<div class="card-body">
		<address>
			<div><strong><?php echo $data->shipping->first_name .' '. $data->shipping->last_name; ?></strong></div>
			<div><?php echo $data->shipping->address_1 .' '. $data->shipping->address_2; ?></div>
			<div><?php echo $data->shipping->city .'/'. $data->shipping->state .' - CEP: '. $data->shipping->postcode; ?></div>
		</address>
	</div>
</div>
<br>
<?php endif; ?>

<?php do_action('woocommerce_order_details_after_customer_details', $order); ?>

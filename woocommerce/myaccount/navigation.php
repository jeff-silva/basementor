<?php
/**
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$user = wp_get_current_user();
$user->data->avatar = get_avatar_url($user->data->ID);
do_action( 'woocommerce_before_account_navigation' );
?>

<style>
.woocommerce-MyAccount-navigation .list-group-item {border:none; padding:0px;}
.woocommerce-MyAccount-navigation .list-group-item:hover {background:#eee;}
.woocommerce-MyAccount-navigation .list-group-item a {color:#666 !important; display:block; padding:12px 12px;}
.woocommerce-MyAccount-navigation .list-group-item.is-active {background:#ddd;}
</style>

<nav class="woocommerce-MyAccount-navigation">
	<div class="row no-gutters align-items-center pt-5" style="background:#eee;">
		<div class="col-3 p-3">
			<img src="<?php echo $user->data->avatar; ?>" alt="" style="width:100%; border-radius:50%;">
		</div>
		<div class="col">
			<div class="text-uppercase font-weight-bold"><?php echo $user->data->display_name; ?></div>
			<div><?php echo $user->data->user_email; ?></div>
		</div>
	</div>
	<hr>

	<ul class="list-group">
		<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
			<li class="list-group-item <?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
				<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>"><?php echo esc_html( $label ); ?></a>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>

<?php do_action( 'woocommerce_after_account_navigation' ); ?>

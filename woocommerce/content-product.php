<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
?>
<div <?php wc_product_class('col-12 col-sm-6 col-md-4 col-lg-3 text-center mb-5 basementor-products-each', $product ); ?>>
	<?php
	// do_action('woocommerce_before_shop_loop_item');
	// do_action('woocommerce_before_shop_loop_item_title');
	// do_action('woocommerce_shop_loop_item_title');
	// do_action('woocommerce_after_shop_loop_item_title');
	// do_action('woocommerce_after_shop_loop_item');


	do_action('woocommerce_before_shop_loop_item');
	
	echo '<div style="position:absolute; z-index:1; right:20px;">';
	wc_get_template_part('loop/sale-flash');
	echo '</div>';

	wc_get_template_part('loop/thumbnail');
	wc_get_template_part('loop/title');
	do_action('woocommerce_after_shop_loop_item_title');
	do_action('woocommerce_after_shop_loop_item');
	?>
</div>

<?php
/**
 * Single Product Sale Flash
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/sale-flash.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     1.6.4
 */

defined('ABSPATH') || exit;

global $post, $product;

if ($product->is_on_sale()) {
	// echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . esc_html__( 'Sale!', 'woocommerce' ) . '</span>', $post, $product );
	
	$price = floatval($product->get_regular_price());
	$sale = floatval($product->get_sale_price());
	$percent = round(($price - $sale) / $price * 100 ).'%';
	echo "<div class='badge badge-primary'>Economize {$percent}</div>";
}

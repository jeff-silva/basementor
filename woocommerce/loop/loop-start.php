<?php
/**
 * Product Loop Start
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/loop-start.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<style>
.wpt-products {}
.wpt-products-each {position:relative; margin:0px 0px 25px 0px;}
.wpt-products-each a {color:#363f4d; text-decoration:none !important;}
.wpt-products-each .woocommerce-loop-product__title {font-size:14px; padding:0px; text-transform:uppercase; white-space:nowrap;}

.wpt-products-each-sale {position:absolute; top:0px; right:0px; background:red; color:#fff; font-size:12px; padding:0px 10px; border-radius:3px;}

.wpt-products-each .price {display:block; clear:both; text-align:center; padding:3px 0px;}
.wpt-products-each .price ins,
.wpt-products-each .price .woocommerce-Price-amount {color:#e73d3d; font-weight:bold;}
.wpt-products-each .price del {color:#999 !important; font-size:12px;}

.wpt-products-each .star-rating {display:block; float:none; margin:0 auto; padding:0px 0px;}
.wpt-products-each .star-rating:before,
.wpt-products-each .star-rating span:before {color:#f9ba48; letter-spacing: 1px;  font-weight:100;}

.wpt-products-each .btn {margin:5px 0px 0px 0px !important; background:#eee !important; color:#555 !important;}
</style>

<ul class="row wpt-products" style="list-style-type:none; padding:0px; margin:0px;">

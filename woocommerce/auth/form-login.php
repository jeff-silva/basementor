<?php
/**
 * Auth form login
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/auth/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates/Auth
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

// do_action( 'woocommerce_auth_page_header' );
wc_get_template_part('global/form-login');
// do_action( 'woocommerce_auth_page_footer' ); ?>

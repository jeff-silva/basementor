<?php
/**
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     3.3.0
 */

defined('ABSPATH') || exit;

$args = isset($args)? $args: [];
$args = array_merge([
	'row' => '',
], $args);

?>

<div class="row basementor-products <?php echo $args['row']; ?>" style="list-style-type:none; padding:0px; margin:0px;">

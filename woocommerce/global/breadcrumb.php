<?php
/**
 * Shop breadcrumb
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/breadcrumb.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     2.3.0
 * @see         woocommerce_breadcrumb()
 */

defined('ABSPATH') || exit; ?>

<nav aria-label="breadcrumb">
	<ol class="breadcrumb">
		<?php foreach($breadcrumb as $i => $crumb): ?>
		<li class="breadcrumb-item"><a href="<?php echo $crumb[1]; ?>"><?php echo $crumb[0]; ?></a></li>
		<?php endforeach; ?>
	</ol>
</nav>
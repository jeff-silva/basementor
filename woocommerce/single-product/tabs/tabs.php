<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.8.0
 */

defined('ABSPATH') or exit;

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 *
 * @see woocommerce_default_product_tabs()
 */
$product_tabs = apply_filters( 'woocommerce_product_tabs', array() );

if ( ! empty( $product_tabs ) ) : ?>
	
	<div class="basementor-product-tabs">
		<ul class="nav nav-tabs mb-3">
			<?php foreach ($product_tabs as $key => $product_tab): ?>
			<li class="nav-item">
				<a href="javascript:;" class="nav-link" data-basementor-product-tab="<?php echo $key; ?>">
					<?php echo $product_tab['title']; ?>
				</a>
			</li>
			<?php endforeach; ?>
		</ul>

		<?php foreach($product_tabs as $key => $product_tab): ?>
		<div class="basementor-product-tab-content" data-basementor-product-tab-content="<?php echo $key; ?>">
			<?php if (isset($product_tab['callback'])) {
				call_user_func( $product_tab['callback'], $key, $product_tab );
			} ?>
		</div>
		<?php endforeach; ?>
	</div>
	<script>jQuery(document).ready(function($) {
		$("[data-basementor-product-tab]").on("click", function() {
			$(".basementor-product-tabs .nav-tabs a").removeClass("active");
			$(this).addClass("active");
			$(".basementor-product-tab-content").hide();
			var selector = $(this).attr("data-basementor-product-tab");
			$(`[data-basementor-product-tab-content=${selector}]`).fadeIn(200);
		});

		$(".basementor-product-tabs .nav-tabs li:eq(0) a").trigger("click");
	});</script>

<?php endif; ?>

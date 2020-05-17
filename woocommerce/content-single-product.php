<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
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

global $product, $post;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked wc_print_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}


?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>
	<div class="row no-gutters">
		<div class="col-12 col-md-9">
			<div class="row no-gutters">
				<div class="col-12 col-md-6 pr-md-3">
					<?php wc_get_template_part('single-product/product-image'); ?>
				</div>

				<div class="col-12 col-md-6">
					<?php wc_get_template_part('single-product/title'); ?>
					<?php wc_get_template_part('single-product/rating'); ?>
					<?php wc_get_template_part('single-product/short-description'); ?>
					<?php wc_get_template_part('single-product/stock'); ?>
					<?php wc_get_template_part('single-product/share'); ?>
				</div>

				<div class="col-12 col-md-12">
					<?php wc_get_template_part('single-product/tabs/tabs'); ?>
					<?php wc_get_template_part('single-product/related'); ?>
					<?php wc_get_template_part('single-product/up-sells'); ?>
				</div>
			</div>
		</div>

		<div class="col-12 col-md-3 pl-md-3">
			<?php wc_get_template_part('single-product/sale-flash'); ?>
			<?php wc_get_template_part('single-product/price'); ?>
			<?php wc_get_template_part('cart/shipping-calculator'); ?>
			<?php wc_get_template_part('single-product/add-to-cart/external'); ?>
			<?php wc_get_template_part('single-product/add-to-cart/grouped'); ?>
			<?php wc_get_template_part('single-product/add-to-cart/simple'); ?>
			<?php wc_get_template_part('single-product/add-to-cart/variable'); ?>

			<?php

			$sections = [
				[
					'title' => 'Compre também',
					'query' => [
						'post_type' => 'product',
						'posts_per_page' => -1,
						'post__in' => get_post_meta($post->ID, '_crosssell_ids', true),
					],
				],

				[
					'title' => 'Relacionados',
					'query' => [
						'post_type' => 'product',
						'posts_per_page' => -1,
						'post__in' => get_post_meta($post->ID, '_upsell_ids', true),
					],
				],

				[
					'title' => 'Outros produtos',
					'query' => [
						'post_type' => 'product',
						'posts_per_page' => 5,
						'orderby' => 'RAND',
					],
				],
			];

			foreach($sections as $section):
				if (isset($section['query']['post__in']) AND !$section['query']['post__in']) continue;
				$loop = new WP_Query($section['query']);
				if ($loop->have_posts()): ?>
					<div class="row mb-3">
						<div class="col-12">
							<h4 class="font-weight-bold text-uppercase m-0 p-0"><?php echo $section['title']; ?></h4>		
						</div>
						
						<?php while($loop->have_posts()): $loop->the_post(); ?>
						<div class="col-12 mt-3">
							<a href="<?php echo get_the_permalink(); ?>" class="d-block">
								<?php wc_get_template_part('loop/thumbnail'); ?>
								<?php wc_get_template_part('loop/title'); ?>
								<?php wc_get_template_part('loop/add-to-cart'); ?>
							</a>
						</div>
						<?php endwhile; ?>
					</div>
				<?php endif;; ?>
			<?php endforeach; ?>
		</div>
	</div>
</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>

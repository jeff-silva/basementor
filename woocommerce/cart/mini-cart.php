<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/mini-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;
$cart = \WC()->cart;
if (! $cart) return;

do_action( 'woocommerce_before_mini_cart' ); ?>


<?php if ($cart->is_empty()): ?>
<div class="text-center py-5" style="background:#eee;">
	Carrinho vazio
</div>

<?php else: ?>
<div><br>
	<div class="<?php echo esc_attr( $args['list_class'] ); ?>">
		<?php do_action('woocommerce_before_mini_cart_contents'); ?>
		<?php foreach ($cart->get_cart() as $cart_item_key => $cart_item):
			$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ):
				$product_name      = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
				$thumbnail         = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);
				$product_price     = apply_filters('woocommerce_cart_item_price', $cart->get_product_price( $_product ), $cart_item, $cart_item_key);
				$product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key);
				?>
				<div class="woocommerce-mini-cart-item <?php echo esc_attr(apply_filters('woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key)); ?>">
					<div class="row no-gutters align-items-center">

						<div class="col-2">
							<?php echo $thumbnail; ?>
						</div>

						<div class="col pl-2">
							<div><a href="<?php echo $product_permalink? $product_permalink: 'javascript:;'; ?>">
								<?php echo $product_name; ?>
							</a></div>

							<div class="text-muted">
								<?php echo wc_get_formatted_cart_item_data($cart_item); ?>
								<?php echo apply_filters('woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key); ?>
							</div>
						</div>

						<div class="col-1"><?php echo apply_filters('woocommerce_cart_item_remove_link', sprintf(
							'<a href="%s" class="remove remove_from_cart_button pull-right" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">&times;</a>',
							esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
							esc_attr__( 'Remove this item', 'woocommerce' ),
							esc_attr( $product_id ),
							esc_attr( $cart_item_key ),
							esc_attr( $_product->get_sku() )
						), $cart_item_key); ?></div>
					</div>
				</div>
			<?php endif; ?>
		<?php endforeach; ?>
		<?php do_action('woocommerce_mini_cart_contents'); ?>
	</div>

	<div class="text-right py-3">
		<?php do_action('woocommerce_widget_shopping_cart_total'); ?>
	</div>

	<?php do_action('woocommerce_widget_shopping_cart_before_buttons'); ?>
	<p class="woocommerce-mini-cart__buttons buttons"><?php do_action( 'woocommerce_widget_shopping_cart_buttons'); ?></p>
	<?php do_action('woocommerce_widget_shopping_cart_after_buttons'); ?>
</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_mini_cart' ); ?>

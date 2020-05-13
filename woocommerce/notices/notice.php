<?php

/**
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.9.0
 */

defined( 'ABSPATH' ) || exit;

if (! $notices) return;

?>

<div class="alert alert-info" role="alert">
	<?php foreach ( $notices as $notice ) : ?>
	<div><?php echo wc_kses_notice( $notice['notice'] ); ?></div>
	<?php endforeach; ?>
</div>
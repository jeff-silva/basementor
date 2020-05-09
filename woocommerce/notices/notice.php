<?php

defined( 'ABSPATH' ) or exit;
if (! $notices) return;

?>

<div class="alert alert-info" role="alert">
	<?php foreach ( $notices as $notice ) : ?>
	<div><?php echo wc_kses_notice( $notice['notice'] ); ?></div>
	<?php endforeach; ?>
</div>
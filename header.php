<!doctype html><html <?php language_attributes(); ?>><head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="https://gmpg.org/xfn/11">
<?php wp_head(); ?>
</head><body <?php body_class(); ?>>
<?php echo \Basementor\Basementor::loader(); ?>
<?php \Basementor\Basementor::elementor('header'); ?>
<?php if (function_exists('wc_print_notices') AND wc_notice_count()>0): ?>
<div class="container"><?php wc_print_notices(); ?></div>
<?php endif; ?>
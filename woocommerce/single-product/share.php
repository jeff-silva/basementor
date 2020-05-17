<?php
/**
 * Single Product Share
 *
 * Sharing plugins can hook into here or you can add your own code directly.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/share.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.0
 */

defined('ABSPATH') || exit;

$title = urlencode(get_the_title());
$url = urlencode(get_the_permalink());
$thumb = urlencode(get_the_post_thumbnail_url());
$excerpt = urlencode(get_the_excerpt());

$links = [
	(object) [
		'name' => 'Twitter',
		'icon' => 'fa fa-fw fa-twitter',
		'btn' => 'btn-twitter',
		'url' => "https://twitter.com/home?status={$title}+{$url}",
	],
	(object) [
		'name' => 'Pinterest',
		'icon' => 'fa fa-fw fa-pinterest',
		'btn' => 'btn-pinterest',
		'url' => "https://pinterest.com/pin/create/bookmarklet/?media={$thumb}&url={$url}&is_video=false&description={$title}",
	],
	(object) [
		'name' => 'Dacebook',
		'icon' => 'fa fa-fw fa-facebook',
		'btn' => 'btn-facebook',
		'url' => "https://facebook.com/share.php?u={$url}&title={$title}",
	],
	(object) [
		'name' => 'Reddit',
		'icon' => 'fa fa-fw fa-reddit',
		'btn' => 'btn-reddit',
		'url' => "https://reddit.com/submit?url={$url}&title={$title}",
	],
	(object) [
		'name' => 'Delicious',
		'icon' => 'fa fa-fw fa-delicious',
		'btn' => 'btn-delicious',
		'url' => "https://del.icio.us/post?url={$url}&title={$title}]&notes={$excerpt}",
	],
	(object) [
		'name' => 'Linkedin',
		'icon' => 'fa fa-fw fa-linkedin',
		'btn' => 'btn-linkedin',
		'url' => "https://www.linkedin.com/shareArticle?mini=true&url={$url}&title={$title}&source=[SOURCE/DOMAIN]",
	],
	(object) [
		'name' => 'Tumblr',
		'icon' => 'fa fa-fw fa-tumblr',
		'btn' => 'btn-tumblr',
		'url' => "https://tumblr.com/share?v=3&u={$url}&t={$title}",
	],
];

foreach($links as $link) {
	echo "<a href='{$link->url}' class='btn btn-sm {$link->btn} mr-1' target='_blank'><i class='{$link->icon}'></i></a>";
}



do_action( 'woocommerce_share' ); // Sharing plugins can hook into here.

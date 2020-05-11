<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.1
 */

defined( 'ABSPATH' ) || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
	return;
}

global $post, $product;


$images = [];

if ($url = get_the_post_thumbnail_url($post->ID, 'full')) {
	$images[] = (object) [
		'url' => $url,
		'title' => '',
	];
}

if ($img_ids = get_post_meta($post->ID, '_product_image_gallery', true)) {
	$img_ids = explode(',', $img_ids);
	foreach($img_ids as $img_id) {
		if ($img_id AND $image = wp_get_attachment_image_src($img_id, 'full')) {
			$images[] = (object) [
				'url' => $image[0],
				'title' => '',
			];
		}
	}
}

dd($data);

?>

<style>
.basementor-product-carousel-align {display: flex; align-items: center; justify-content: center;}
.basementor-product-carousel {position:relative;}
.basementor-product-carousel .slick-arrow {position:absolute; top:0px; height:100%; z-index:2; background:none; border:none; font-size:40px; outline:0!important; box-shadow:none !important;}
.basementor-product-carousel .slick-prev {left:0px;}
.basementor-product-carousel .slick-next {right:0px;}
</style>

<div class="basementor-product-carousel">
	<?php foreach($images as $image): ?>
	<div>
		<div class="basementor-product-carousel-align" style="height:300px; padding:0px 50px;">
			<img src="<?php echo $image->url; ?>"
				alt=""
				xoriginal="<?php echo $image->url; ?>"
				xpreview="<?php echo $image->url; ?>"
				style="width:100%; max-width:100%;"
			>
		</div>
	</div>
	<?php endforeach; ?>
</div>

<br>
<div class="basementor-product-carousel-dots text-center">
	<?php foreach($images as $i=>$image): ?>
	<a href="javascript:;" class="basementor-product-carousel-align border" style="display:inline-flex; vertical-align:middle; margin:5px 5px 0px 5px; width:70px; height:70px;" data-basementor-product-carousel-goto="<?php echo $i; ?>">
		<img src="<?php echo $image->url; ?>" alt="" style="width:90%;">
	</a>
	<?php endforeach; ?>
</div>

<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script>jQuery(document).ready(function($) {
	$(".basementor-product-carousel").slick({
		prevArrow: `<button type="button" class="slick-arrow slick-prev"><i class="fa fa-fw fa-chevron-left"></i></button>`,
		nextArrow: `<button type="button" class="slick-arrow slick-next"><i class="fa fa-fw fa-chevron-right"></i></button>`,
	});

	$(".basementor-product-carousel").on("setPosition", function(event, slick, direction) {
		$(".basementor-product-carousel-dots a").removeClass("border-primary");
		$(".basementor-product-carousel-dots a").eq(slick.currentSlide).addClass("border-primary");
	});

	$("[data-basementor-product-carousel-goto]").on("click", function() {
		var index = $(this).attr("data-basementor-product-carousel-goto")||0;
		$(".basementor-product-carousel").slick('slickGoTo', parseInt(index));
	});
});</script>

<br><br>
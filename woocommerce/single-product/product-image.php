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

?>

<style>
/*.basementor-product-carousel-align {display: flex; align-items: center; justify-content: center;}*/
.basementor-product-carousel {position:relative;}
.basementor-product-carousel .slick-arrow {position:absolute; top:0px; width:50px; height:100%; z-index:2; text-align:center; padding:0px; background:none; border:none; text-shadow:0px 0px #fff; font-size:30px; outline:0!important; box-shadow:none !important; cursor:pointer;}
.basementor-product-carousel .slick-prev {left:0px;}
.basementor-product-carousel .slick-next {right:0px;}
.basementor-product-carousel .slick-dots {list-style-type:none; margin:0px; padding:0px; text-align:center;}
.basementor-product-carousel .slick-dots li {display:inline-block;}
.basementor-product-carousel .slick-dots li.slick-active {}
.basementor-product-carousel .slick-dots li button {border:none; background:#eee; color:#eee; border:solid 1px #444; font-size:0px; padding:0px; width:10px; height:10px; margin-right:5px; border-radius:50%;}
.basementor-product-carousel .slick-dots li.slick-active button {background:#444; color:#444;}
</style>

<div class="basementor-product-carousel basementor-product-carousel-big" data-slick-settings='{slidesToShow:1, slidesToScroll:1, fade:true, adaptiveHeight:true, asNavFor:".basementor-product-carousel-small"}'>
	<?php foreach($images as $image): ?>
	<div><img src="<?php echo $image->url; ?>" alt="" style="width:100%;"></div>
	<?php endforeach; ?>
</div>


<?php if (sizeof($images)>1): ?>
<br>
<div class="basementor-product-carousel basementor-product-carousel-small" data-slick-settings='{slidesToShow:3, slidesToScroll:1, arrows:false, asNavFor:".basementor-product-carousel-big", dots:true, centerMode:true, focusOnSelect:true}'>
	<?php foreach($images as $image): ?>
	<div class="px-1"><img src="<?php echo $image->url; ?>" alt="" style="width:100%;"></div>
	<?php endforeach; ?>
</div>
<?php endif; ?>


<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script>jQuery(document).ready(function($) {
	$("[data-slick-settings]").each(function() {
		var sets = $(this).attr("data-slick-settings")||"{}";
		try { eval('sets = '+sets); } catch(e) { sets = {}; }
		sets.prevArrow = sets.prevArrow||`<button type="button" class="slick-arrow slick-prev"><i class="fa fa-fw fa-chevron-left"></i></button>`;
		sets.nextArrow = sets.nextArrow||`<button type="button" class="slick-arrow slick-next"><i class="fa fa-fw fa-chevron-right"></i></button>`;
		$(this).slick(sets);
	});
});</script>

<br><br>
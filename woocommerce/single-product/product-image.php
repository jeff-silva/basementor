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

$data = new \stdClass;
$data->id = uniqid('single-product-slider-');
$data->images = [];

if ($url = get_the_post_thumbnail_url($post->ID, 'full')) {
	$data->images[] = (object) [
		'url' => $url,
		'title' => '',
	];
}

if ($img_ids = get_post_meta($post->ID, '_product_image_gallery', true)) {
	$img_ids = explode(',', $img_ids);
	foreach($img_ids as $img_id) {
		if ($img_id AND $image = wp_get_attachment_image_src($img_id, 'full')) {
			$data->images[] = (object) [
				'url' => $image[0],
				'title' => '',
			];
		}
	}
}

?>

<div id="<?php echo $data->id; ?>">
	<vue-slider ref="slider" :items="images">
		<template #slide="{slide, index}">
			<img :src="slide.url" alt="" style="width:100%;">
		</template>
	</vue-slider>

	<div class="row no-gutters">
		<template v-if="images.length==2">
			<div class="col-6 p-2 text-right">
				<img :src="images[0].url" alt="" style="width:80px; cursor:pointer;" @click="$refs.slider.slickGoTo(0, false);">
			</div>
			<div class="col-6 p-2 text-left">
				<img :src="images[1].url" alt="" style="width:80px; cursor:pointer;" @click="$refs.slider.slickGoTo(1, false);">
			</div>
		</template>

		<template v-else-if="images.length>2">
			<div class="col-3 p-2" v-for="(i, index) in images">
				<img :src="i.url" alt="" style="width:100%; cursor:pointer;" @click="$refs.slider.slickGoTo(index, false);">
			</div>
		</template>
	</div>
</div>

<?php do_action('vue'); ?>
<script>new Vue({
	el: "#<?php echo $data->id; ?>",
	data: <?php echo json_encode($data); ?>,
});</script>
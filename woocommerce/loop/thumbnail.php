<?php

global $product, $post;

$args = isset($args)? $args: [];
$args = (object) array_merge([
	'height' => '350px',
], $args);

/* <div style="width:100%; position:relative; text-align:center; overflow:hidden; box-sizing:border-box; background:#f5f5f5;">
	<div style="height:350px;"></div>
	<?php echo get_the_post_thumbnail($post->ID, 'post-thumbnail', [
		'style' => 'position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); height:100%;',
	]); ?>
</div> */

$thumbnail_url = get_the_post_thumbnail_url($post->ID);

?>
<div style="width:100%; height:<?php echo $args->height; ?>; background:url(<?php echo $thumbnail_url; ?>) center center no-repeat #f5f5f5; background-size:cover;">
</div>
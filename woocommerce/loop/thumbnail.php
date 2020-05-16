<?php

global $product, $post;

?>
<div class="mb-2" style="width:100%; height:200px; position:relative; text-align:center; overflow:hidden; box-sizing:border-box; background:#f5f5f5;">
	<?php echo get_the_post_thumbnail($post->ID, 'post-thumbnail', [
		'style' => 'position:absolute; top:50%; left:50%; transform:translate(-50%, -50%);',
	]); ?>
</div>
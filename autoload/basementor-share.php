<?php

add_shortcode('basementor-share', function() {
	global $post;
	$post->permalink = urlencode(get_the_permalink($post));
	$post->thumbnail = urlencode(get_the_post_thumbnail_url($post));

	$socials = [
		(object) [
			'id' => 'facebook',
			'icon' => 'fa fa-fw fa-facebook',
			'url' => "https://www.facebook.com/sharer/sharer.php?u={$post->permalink}",
		],

		(object) [
			'id' => 'twitter',
			'icon' => 'fa fa-fw fa-twitter',
			'url' => "https://twitter.com/home?status={$post->permalink} {$post->post_title}",
		],

		(object) [
			'id' => 'pinterest',
			'icon' => 'fa fa-fw fa-pinterest',
			'url' => "https://pinterest.com/pin/create/button/?url={$post->permalink}&media={$post->thumbnail}&description={$post->post_title}",
		],

		(object) [
			'id' => 'linkedin',
			'icon' => 'fa fa-fw fa-linkedin',
			'url' => "https://www.linkedin.com/shareArticle?mini=true&url={$post->permalink}&title=&summary={$post->post_title}&source=",
		],
	];

	?><div class="list-inline">
		<?php foreach($socials as $social): ?>
		<div class="list-inline-item">
			<a href="<?php echo $social->url; ?>" class="btn btn-outline-<?php echo $social->id; ?> btn-sm" target="_blank">
				<i class="<?php echo $social->icon; ?>"></i>
			</a>
		</div>
		<?php endforeach; ?>
	</div><?php
});

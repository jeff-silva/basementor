<?php

// add_action('save_post', function() {
// 	if (isset($_POST['_thumbnail_id'])) {
// 		$url = wp_get_attachment_url($_POST['_thumbnail_id']);
// 		update_post_meta($_POST['post_ID'], '_thumbnail', $url);
// 	}

// 	if (isset($_POST['product_image_gallery'])) {
// 		$_gallery = [];
// 		$attach_ids = explode(',', $_POST['product_image_gallery']);
// 		foreach($attach_ids as $attach_id) {
// 			if (! $attach_id) continue;
// 			$_gallery[] = [
// 				'url' => wp_get_attachment_url($attach_id),
// 				'title' => '',
// 			];
// 		}
// 		update_post_meta($_POST['post_ID'], '_gallery', $_gallery);
// 	}
// }, 10, 2);

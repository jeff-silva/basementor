<?php

global $post;

// get_header();
// echo \Elementor\Plugin::$instance->frontend->get_builder_content(1321, true);
// get_footer();
// return;


get_header();

if (have_posts()) {
	while (have_posts()) {
		the_post();
		the_content();
	}
}

get_footer();
<?php 

global $post;

if ($post->post_type!='elementor_library') {
    \Basementor\Basementor::elementor('footer');
}

wp_footer();

echo \Basementor\Basementor::settings('basementor_body');

?></body></html>
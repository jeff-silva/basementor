<?php

define('BASEMENTOR_ELEMENTOR', did_action('elementor/loaded'));
define('BASEMENTOR_WOOCOMMERCE', class_exists('WooCommerce'));

spl_autoload_register(function($class) {
	if ($include = realpath(str_replace('\\', '/', __DIR__ . "/{$class}.php"))) {
		include $include;
	}
});


include __DIR__ . '/wp-helpers/wp-helpers.php';


add_action('after_setup_theme', function() {
	add_theme_support('post-thumbnails');
	add_theme_support('woocommerce');

	register_nav_menus([
		'primary' => 'Principal',
		'socials' => 'Redes Sociais',
	]);
});


foreach(['wp_enqueue_scripts', 'admin_enqueue_scripts'] as $action) {
	add_action($action, function() {
		wp_enqueue_script('vue', '//cdnjs.cloudflare.com/ajax/libs/vue/2.6.10/vue.min.js');
		wp_enqueue_script('popper', '//cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js');
		wp_enqueue_script('bootstrap', '//stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js');
		wp_enqueue_style('bootstrap', '//stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css');
		wp_enqueue_style('font-awesome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');
		wp_enqueue_style('animate-css', '//cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css');
		wp_enqueue_script('slick', '//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js');
		wp_enqueue_style('slick', '//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css');
	});
}

foreach(['wp_head', 'admin_head'] as $action):
add_action($action, function() { ?>
<style>
/* Bugfixes */
.elementor-column-gap-default>.elementor-row>.elementor-column>.elementor-element-populated {padding:0px !important; margin:0px !important;}
.wp-admin .card {padding:0px; max-width:none;}
[v-cloak] {display:none;}
.wp-filter > * {line-height:3px; margin:0px !important;}
.search-plugins {padding:5px 0px 0px 0px;}
.search-plugins > * {vertical-align:top;}
.btn, .btn:active, .btn:focus, .form-control, .form-control:active, .form-control:focus {box-shadow:none !important; outline:none !important;}

.pagination {}
.pagination .page-item {}
.pagination .page-link {width:40px; border:none; color:#888; text-align:center;}

.input-group.border {border-radius:4px; overflow:hidden;}
.input-group.border .form-control {border:none !important; background:none !important; border-radius:0 !important; outline:0!important; box-shadow:none !important;}
.input-group.border .btn {border:none !important; border-radius:0 !important;}
</style> 
<?php });
endforeach;




/*
add_filter('pre_get_posts', function($query) {
	if (isset($query->query['post_type']) AND $query->query['post_type']=='product') {
		$meta_query = $query->get('meta_query');
		$tax_query = $query->get('tax_query');
		$tax_query['relation'] = 'OR';

		if (isset($query->query['product_cat'])) {
			$values = array_values(array_filter(explode(',', $query->query['product_cat']), 'strlen'));
			foreach($values as $value) {
				$tax_query[] = ['taxonomy'=>'product_cat', 'field'=>'slug', 'value'=>$value];
			}
			unset($query->query['product_cat']);
		}

		$query->set('meta_query', $meta_query);
		$query->set('tax_query', $tax_query);
		// dd($tax_query);
	}
	return $query;
});
*/


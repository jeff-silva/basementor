<?php

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
.wp-admin .card {padding:0px;}
[v-cloak] {display:none;}

.input-group.border {border-radius:4px; overflow:hidden;}
.input-group.border .form-control {border:none !important; background:none !important; border-radius:0 !important; outline:0!important; box-shadow:none !important;}
.input-group.border .btn {border:none !important; border-radius:0 !important;}

/* Customizations */
.elementor-heading-title {color:#666 !important;}

/* Classes */
.wpt-cover {background:url() #eee center center no-repeat; background-size:cover;}
.wpt-footer {}
.wpt-footer .elementor-heading-title {color:#666 !important; text-transform:uppercase;}
.wpt-footer .list-group {}
.wpt-footer .list-group-item {border:none; background:none; padding:2px 0px; color:#888;}
.wpt-footer .list-group-item > i {width:20px;}

.wpt-products {}
.wpt-products-each {position:relative; margin:0px 0px 25px 0px;}
.wpt-products-each a {color:#363f4d; text-decoration:none !important;}
.wpt-products-each .woocommerce-loop-product__title {font-size:14px; padding:0px; text-transform:uppercase; white-space:nowrap;}
.wpt-products-each .added_to_cart {display:none !important;}

.wpt-products-each-sale {position:absolute; top:0px; right:0px; background:red; color:#fff; font-size:12px; padding:0px 10px; border-radius:3px;}

.wpt-products-each .price {display:block; clear:both; text-align:center; padding:3px 0px;}
.wpt-products-each .price ins,
.wpt-products-each .price .woocommerce-Price-amount {color:#e73d3d; font-weight:bold;}
.wpt-products-each .price del {color:#999 !important; font-size:12px;}

.wpt-products-each .star-rating {display:block; float:none; margin:0 auto; padding:0px 0px;}
.wpt-products-each .star-rating:before,
.wpt-products-each .star-rating span:before {color:#f9ba48; letter-spacing: 1px;  font-weight:100;}

.wpt-products-each .btn {margin:5px 0px 0px 0px !important;}
<?php

$color_dark = \Basementor\Options::get('color_dark');
$color_light = \Basementor\Options::get('color_light');
$style = [];
foreach(\Basementor\Options::get('colors') as $prefix=>$color) {
	$c = (object) ['default' => $color];
	$c->dark = \Basementor\Options::color($color, $color_dark);
	$c->light = \Basementor\Options::color($color, $color_light);

	$style[] = ".text-{$prefix}, .text-{$prefix}:hover {color:{$c->default} !important;}";
	$style[] = ".bg-{$prefix}-light {background-color:{$c->light} !important;}";
	$style[] = ".bg-{$prefix}-dark {background-color:{$c->dark} !important;}";
	$style[] = ".bg-{$prefix} {background-color:{$c->default} !important;}";
	$style[] = ".btn-{$prefix} {background-color:{$c->default} !important; border-color:{$c->default};}";
	$style[] = ".btn-{$prefix}:hover, .btn-{$prefix}:active {background-color:{$c->dark} !important; border-color:{$c->dark};}";
	$style[] = ".border-{$prefix} {border-color:{$c->default} !important;}";
}

echo implode('', $style); ?></style> 
<?php });
endforeach;



add_action('woocommerce_product_query', function($query) {
	$meta_query = $query->get('meta_query');
	$tax_query = $query->get('tax_query');

	// if (isset($_GET['product_cat'])) {
	// 	$value = array_values(array_filter(explode(',', $_GET['product_cat']), 'strlen'));
	// 	$meta_query[] = ['key'=>'product_cat', 'compare'=>'IN', 'value'=>$value];
	// }

	// foreach(wc_get_attribute_taxonomies() as $tax) {
	// 	$key = "pa_{$tax->attribute_name}";

	// 	if (isset($_GET[$key])) {
	// 		$value = array_values(array_filter(explode(',', $_GET[$key]), 'strlen'));
	// 		$tax_query[] = [
	// 			'taxonomy' => $key,
	// 			'field' => 'slug',
	// 			'terms' => $value,
	// 			'operator' => 'IN',
	// 		];
	// 	}
	// }

	$query->set('meta_query', $meta_query);
	$query->set('tax_query', $tax_query);
	// echo '<!-- $tax_query: ', print_r($tax_query, true), '-->';
});


if (isset($_GET['basementor-update'])) {
	add_action('init', function() {
		$data = new stdClass;
		$data->themes_dir = realpath(__DIR__ . '/../');
		$data->zip_filename = "{$data->themes_dir}/basementor-master.zip";
		
		if (file_exists($data->zip_filename)) { unlink($data->zip_filename); }
		$contents = file_get_contents('https://github.com/jeff-silva/basementor/archive/master.zip');
		file_put_contents($data->zip_filename, $contents);
		$data->delete = \Basementor\Basementor::file_delete(__DIR__);

		$zip = new ZipArchive;
		if ($zip->open($data->zip_filename) === TRUE) {
			$zip->extractTo($data->themes_dir);
			$zip->close();
		}

		wp_redirect($_SERVER['HTTP_REFERER']);
	});
}


add_action('admin_menu', function() {
	add_submenu_page('tools.php', 'Basementor', 'Basementor', 'manage_options', 'basementor', function() {
		echo '<br><a href="?page=basementor&basementor-update" class="btn btn-success">Update</a>';
	});
});

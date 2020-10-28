<?php

/*
[ ] - Make "basementor-vue" action to global register components
	[ ] - Make wp_editor component for vue
[ ] - Create "clone" action in posts actions list
[ ] - Make "Post excel mode" work in all post types
[ ] - Make Woocommerce filters customizable
[ ] - Put title inside thumbnail for loop elements height bugfix
*/

if (defined('BASEMENTOR_PARENT')) return;

define('BASEMENTOR_ELEMENTOR', did_action('elementor/loaded'));
define('BASEMENTOR_WOOCOMMERCE', class_exists('WooCommerce'));
define('BASEMENTOR_PARENT', get_template_directory());
define('BASEMENTOR_CHILD', get_stylesheet_directory());


/* Print data: dd($data1, $data2, $data3); */
if (! function_exists('dd')) { function dd() { foreach(func_get_args() as $data) { echo '<pre>'. print_r($data, true) .'</pre>'; }}}
if (! function_exists('de')) { function de() { foreach(func_get_args() as $data) { echo '<pre>'. var_export($data, true) .'</pre>'; }}}


spl_autoload_register(function($class) {
	if ($include = realpath(str_replace('\\', '/', __DIR__ . "/{$class}.php"))) {
		include $include;
	}
});


foreach([BASEMENTOR_PARENT, BASEMENTOR_CHILD] as $folder) {
    foreach(glob($folder .'/autoload/*.php') as $include) {
        include $include;
    }
}

include __DIR__ . '/woocommerce-checkout-fields/woocommerce-checkout-fields.php';


if (isset($_GET['basementor-theme-child']) AND is_user_logged_in()) {
	add_action('init', function() {
		$folder = wp_get_current_user();
		$folder = $folder->data->user_login;
		$theme_folder = get_theme_root() ."/{$folder}";
		@mkdir($theme_folder, 0755, true);
		@mkdir($theme_folder.'/autoload', 0755, true);
		file_put_contents("{$theme_folder}/style.css", implode("\n", [
			'/*',
			"Theme Name:     {$folder} theme",
			"Description:    {$folder} Theme",
			'Author:         Jeferson Siqueira',
			'Author URI:     https://jsiqueira.com',
			'Template:       basementor-master',
			'Version:        1.0',
			'*/',
		]));

		file_put_contents("{$theme_folder}/functions.php", implode("\n", [
			'<?php',
			'',
			'include __DIR__ .\'/autoload.php\';',
		]));

		wp_redirect($_SERVER['HTTP_REFERER']); die;
	});
}



/* Remove WP Emoji */
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('admin_print_styles', 'print_emoji_styles');


/* Disable Woocommerce Stylesheet */
// add_filter('woocommerce_enqueue_styles', '__return_false' );
// add_filter('woocommerce_enqueue_styles', function( $enqueue_styles ) {
// 	unset( $enqueue_styles['woocommerce-general'] );
// 	unset( $enqueue_styles['woocommerce-layout'] );
// 	unset( $enqueue_styles['woocommerce-smallscreen'] );
// 	return $enqueue_styles;
// });




add_action('after_setup_theme', function() {
	add_theme_support('post-thumbnails');
	add_theme_support('woocommerce');
	add_theme_support('custom-logo', [
		'width'       => 300,
		'height'      => 100,
		'flex-height' => true,
		'flex-width'  => true,
		'header-text' => ['site-title', 'site-description'],
	]);

	register_nav_menus([
		'primary' => 'Principal',
		'socials' => 'Redes Sociais',
	]);
});


/* Enqueue scripts and styles in admin and website */
foreach(['wp_enqueue_scripts', 'admin_enqueue_scripts'] as $action) {
	add_action($action, function() {
		wp_enqueue_script('vue', '//cdn.jsdelivr.net/npm/vue');
		wp_enqueue_script('popper', '//cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js');
		wp_enqueue_script('bootstrap', '//stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js');
		wp_enqueue_style('bootstrap', '//stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css');
		wp_enqueue_style('font-awesome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');
		wp_enqueue_style('animate-css', '//cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css');
		wp_enqueue_script('head-load', '//cdnjs.cloudflare.com/ajax/libs/headjs/1.0.3/head.load.min.js');
	});
}



/* foreach(['wp_head', 'admin_head'] as $action):
add_action($action, function() {
$settings = \Basementor\Basementor::settings();
?><style>
.wp-admin .card {padding:0px; max-width:none;}
[v-cloak] {display:none;}
.wp-filter > * {line-height:3px; margin:0px !important;}
.search-plugins {padding:5px 0px 0px 0px;}
.search-plugins > * {vertical-align:top;}
</style>

<?php echo \Basementor\Basementor::styles(); ?>
<?php });
endforeach; */





// add_action('wp_enqueue_scripts', function() {
// 	wp_enqueue_style('aaa', get_template_directory_uri().'/style.css');
// 	wp_enqueue_style('bbb', get_stylesheet_directory_uri().'/style.css');
// });


/* Redirect if current post edit is Elementor */
add_action('admin_head-post.php', function() {
	global $post;
	if (\Elementor\Plugin::$instance->db->is_built_with_elementor($post->ID)) {
		wp_redirect(admin_url("/post.php?post={$post->ID}&action=elementor"));
	}
});


/* Save any Woocommerce user post field */
add_action('woocommerce_customer_save_address', function($user_id, $load_address) {
    $ignore = ['save_address', 'woocommerce-edit-address-nonce', '_wp_http_referer', 'action'];
    foreach($_POST as $key=>$value) {
        if (in_array($key, $ignore)) continue;
        update_user_meta(get_current_user_id(), $key, $value);
    }
}, 10, 2);





add_action('admin_bar_menu', function($admin_bar) {

	$items = [
		[
			'id' => 'basementor-elementor',
			'title' => 'Elementor',
			'href' => 'javascript:;',
			'children' => [
				[
					'id'    => 'basementor-elementor-page',
					'title' => 'Editar página atual',
					'href'  => admin_url('admin.php?page=basementor-settings&tab=update'),
				],
				[
					'id'    => 'basementor-elementor-header',
					'title' => 'Editar Cabeçalho',
					'href'  => admin_url('admin.php?page=basementor-settings&tab=update'),
				],
				[
					'id'    => 'basementor-elementor-footer',
					'title' => 'Rodapé',
					'href'  => admin_url('admin.php?page=basementor-settings&tab=update'),
				],
			],
		]
	];

	foreach($items as $item) {
		$admin_bar->add_menu($item);

		if (isset($item['children']) AND is_array($item['children'])) {
			foreach($item['children'] as $iitem) {
				$iitem['parent'] = $item['id'];
				$admin_bar->add_menu($iitem);
			}
		}
	}
}, 100);
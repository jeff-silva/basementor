<?php

/*
[ ] - Make "basementor-vue" action to global register components
	[ ] - Make wp_editor component for vue
[ ] - Create "clone" action in posts actions list
[ ] - Make "Post excel mode" work in all post types
[ ] - Make Woocommerce filters customizable
[ ] - Put title inside thumbnail for loop elements height bugfix
*/

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


function basementor_autoload_generate() {
	$date = date('Y-m-d H:i:s');
	$themepaths = [];

	if ($path = get_template_directory()) {
		$themepaths[] = $path;
	}

	if ($path = get_stylesheet_directory()) {
		$themepaths[] = $path;
	}

	foreach($themepaths as $themepath) {
		if ($autoload = realpath("{$themepath}/autoload/")) {
			$file = ['<?php', ''];

			$file[] = '/* ';
			$file[] = " * THIS FILE IS AUTOMATICALLY GENERATED. DO NOT EDIT IT DIRECTLY.";
			$file[] = " * A file called \"autoload.php\" will be created in the parent and child themes folder";
			$file[] = " * containing an include for all files inside the \"/autoload\" folder.";
			$file[] = " * This file was last generated on {$date}";
			$file[] = '*/';
			$file[] = '';
			foreach(glob("{$autoload}/*") as $filename) {
				$filename = str_replace($themepath, '', $filename);
				$file[] = "@ include __DIR__ . '{$filename}';";
			}
			$file[] = '';
			$file = implode("\n", $file);
			
			file_put_contents("{$themepath}/autoload.php", $file);

			if (isset($_GET['autoload'])) {
				$url = ($_SERVER['HTTPS'] || $_SERVER['HTTPS']=='on')? 'https://': 'http://';
				$url .= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
				$url = (object) parse_url($url);
				parse_str($url->query, $url->query);
				if (isset($url->query['autoload'])) {
					unset($url->query['autoload']);
				}
				if (!empty($url->query)) {
					$url->path .= '?'. http_build_query($url->query);
				}
				$url = "{$url->scheme}://{$url->host}{$url->path}";
				header("Location: {$url}"); die;
			}

		}
	}
}



if (isset($_GET['basementor-autoload']) AND is_user_logged_in()) {
	basementor_autoload_generate();
	wp_redirect($_SERVER['HTTP_REFERER']); die;
}

$autoload = realpath(__DIR__ . '/autoload.php');
if (! $autoload) {
	basementor_autoload_generate();
	$autoload = realpath(__DIR__ . '/autoload.php');
}

include $autoload;


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
		wp_enqueue_script('vue', '//cdn.jsdelivr.net/npm/vue/dist/vue.min.js');
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

<?php $prefixes = [
	(object) ['color'=>'#007bff', 'color_text'=>'#ffffff', 'prefix'=>'primary'],
	(object) ['color'=>'#6c757d', 'color_text'=>'#666666', 'prefix'=>'secondary'],
	(object) ['color'=>'#28a745', 'color_text'=>'#ffffff', 'prefix'=>'success'],
	(object) ['color'=>'#dc3545', 'color_text'=>'#ffffff', 'prefix'=>'danger'],
	(object) ['color'=>'#ffc107', 'color_text'=>'#ffffff', 'prefix'=>'warning'],
	(object) ['color'=>'#17a2b8', 'color_text'=>'#ffffff', 'prefix'=>'info'],
	(object) ['color'=>'#3b5999', 'color_text'=>'#ffffff', 'prefix'=>'facebook'],
	(object) ['color'=>'#55acee', 'color_text'=>'#ffffff', 'prefix'=>'twitter'],
	(object) ['color'=>'#0077b5', 'color_text'=>'#ffffff', 'prefix'=>'linkedin'],
	(object) ['color'=>'#00aff0', 'color_text'=>'#ffffff', 'prefix'=>'skype'],
	(object) ['color'=>'#007ee5', 'color_text'=>'#ffffff', 'prefix'=>'dropbox'],
	(object) ['color'=>'#21759b', 'color_text'=>'#ffffff', 'prefix'=>'wordpress'],
	(object) ['color'=>'#1ab7ea', 'color_text'=>'#ffffff', 'prefix'=>'vimeo'],
	(object) ['color'=>'#4c75a3', 'color_text'=>'#ffffff', 'prefix'=>'vk'],
	(object) ['color'=>'#34465d', 'color_text'=>'#ffffff', 'prefix'=>'tumblr'],
	(object) ['color'=>'#410093', 'color_text'=>'#ffffff', 'prefix'=>'yahoo'],
	(object) ['color'=>'#bd081c', 'color_text'=>'#ffffff', 'prefix'=>'pinterest'],
	(object) ['color'=>'#cd201f', 'color_text'=>'#ffffff', 'prefix'=>'youtube'],
	(object) ['color'=>'#ff5700', 'color_text'=>'#ffffff', 'prefix'=>'reddit'],
	(object) ['color'=>'#b92b27', 'color_text'=>'#ffffff', 'prefix'=>'quora'],
	(object) ['color'=>'#ff3300', 'color_text'=>'#ffffff', 'prefix'=>'soundcloud'],
	(object) ['color'=>'#25d366', 'color_text'=>'#ffffff', 'prefix'=>'whatsapp'],
	(object) ['color'=>'#e4405f', 'color_text'=>'#ffffff', 'prefix'=>'instagram'],
];

$_color = function($hex, $add=null) {
	if ($add!==null) {
		$hex = preg_replace('/[^0-9a-zA-Z]/', '', $hex);
		$hex = str_split($hex, 2);
		$hex = array_map(function($val) use($add) {
			$val = hexdec($val);
			$val += intval($add);
			$val = min(max($val, 0), 255);
			return str_pad(dechex($val), 2, '0', STR_PAD_LEFT);
		}, $hex);
		$hex = '#'. implode('', $hex);
	}
	return $hex;
};

$lines = [];
$lines[] = ".input-group.form-control {padding:0px;}";
$lines[] = ".input-group.form-control .btn {border:transparent!important; border-radius:0px; display:inline-block!important;}";
$lines[] = ".input-group.form-control .form-control, .input-group.form-control .input-group-text {border:transparent!important; background:none; border-radius:0px;}";
$lines[] = ".basementor-woocommerce-price del {}";
$lines[] = ".basementor-woocommerce-price ins {text-decoration: none;}";

foreach($prefixes as $p) {
	$prefix = $p->prefix;
	$color = $p->color;
	$text = $p->color_text;
	$dark = $_color($p->color, $set->color_dark);
	$light = $_color($p->color, $set->color_light);
	$lines[] = ".text-{$prefix}, .text-{$prefix}:hover {color:{$color} !important;}";
	$lines[] = ".bg-{$prefix}-light {background-color:{$light} !important;}";
	$lines[] = ".bg-{$prefix}-dark {background-color:{$dark} !important;}";
	$lines[] = ".bg-{$prefix} {background-color:{$color} !important; color:{$text};}";
	$lines[] = ".btn-{$prefix} {background-color:{$color} !important; border-color:{$color}; color:{$text};}";
	$lines[] = ".btn-{$prefix}-light {background-color:{$light} !important; border-color:{$light}; color:{$text};}";
	$lines[] = ".btn-{$prefix}-dark {background-color:{$dark} !important; border-color:{$dark}; color:{$text};}";
	$lines[] = ".btn-{$prefix}:hover, .btn-{$prefix}:active {background-color:{$dark} !important; border-color:{$dark}; color:{$text};}";
	$lines[] = ".btn-outline-{$prefix} {border-color:{$color} !important; color:{$color};}";
	$lines[] = ".btn-outline-{$prefix}:hover {background-color:{$color} !important; color:{$text};}";
	$lines[] = ".border-{$prefix} {border-color:{$color} !important;}";
	$lines[] = ".alert-{$prefix} {background-color:{$light}; color:{$text};}";
	$lines[] = ".badge-{$prefix} {background-color:{$color}; color:{$text};}";
}

echo implode('', $lines); ?>
</style>

<?php /*if (is_user_logged_in()): ?>
<script>jQuery(document).ready(function($) {
	var height = parseFloat( $("#wpadminbar").height() );
	$(".fixed-top").each(function() {
		var top = parseFloat( $(this).css("top") );
		var hh = parseFloat( $(this).height() );
		$(this).css({top: top+height});
		$('body').prepend(`<div style="height:${hh}px;"></div>`);
	});
});</script>
<?php endif;*/ ?>

<?php });
endforeach;




add_action('admin_head', function() { ?><style>
ul.subsubsub {}
ul.subsubsub li {padding:0px 5px;}
ul.subsubsub li a {color:inherit !important;}

.tablenav select, .tablenav input, .tablenav button, ul.subsubsub li {
	background: #eee !important;
	border: solid 1px #ddd !important;
	color: #555 !important;
	height: auto !important;
	border-radius: 3px;
	padding: 0px 8px !important;
	margin: 0px 5px 0px 0px !important;
}

.tablenav select:hover, .tablenav input:hover, .tablenav button:hover, ul.subsubsub li:hover {
	background: #f5f5f5 !important;
	text-decoration: none !important;
}


.wp-list-table {border:none !important;}
.wp-list-table th {border:none !important;}
.wp-list-table td {border:none !important;}
.wp-list-table thead th, .wp-list-table thead td,
.wp-list-table tfoot th, .wp-list-table tfoot td {background:#eee; color:#666 !important;}
.wp-list-table thead a, .wp-list-table tfoot a {color:inherit; font-weight:600;}
</style><?php });



add_action('wp_enqueue_scripts', function() {
	wp_enqueue_style('aaa', get_template_directory_uri().'/style.css');
	wp_enqueue_style('bbb', get_stylesheet_directory_uri().'/style.css');
});


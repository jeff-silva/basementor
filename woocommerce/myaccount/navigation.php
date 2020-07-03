<?php
/**
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 2.6.0
 */

defined('ABSPATH') || exit;

$user = wp_get_current_user();
$user->data->avatar = get_avatar_url($user->data->ID);
do_action( 'woocommerce_before_account_navigation' );
?>

<div style="position:relative;">
	<div class="profile-sidebar shadow" style="position:sticky; position:-webkit-sticky; top:0;">
		<div class="profile-userpic text-center">
			<img src="<?php echo $user->data->avatar; ?>" class="img-responsive" alt="">
		</div>
		<div class="profile-usertitle">
			<div class="profile-usertitle-name font-weight-bold h5">
				<?php echo $user->data->display_name; ?>
			</div>
			<div class="profile-usertitle-job text-muted">
				<?php echo $user->data->user_email; ?>
			</div>
		</div>
		<div class="profile-usermenu">
			<ul class="nav flex-column nav-pills">
				<?php foreach(wc_get_account_menu_items() as $endpoint => $label): ?>
				<li class="nav-item <?php echo is_wc_endpoint_url($endpoint)? 'active': null; ?>">
					<a href="<?php echo esc_url(wc_get_account_endpoint_url($endpoint)); ?>" class="nav-link">
						<?php echo esc_html($label); ?>
					</a>
				</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</div>

<style>
.profile-sidebar {padding: 20px 0 10px 0;}
.profile-userpic img {float: none; margin: 0 auto; width: 50%; height: 50%; -webkit-border-radius: 50% !important; -moz-border-radius: 50% !important; border-radius: 50% !important;}
.profile-usertitle {text-align: center; margin-top: 20px;}
.profile-usertitle-name {margin-bottom: 7px;}
.profile-usertitle-job {text-transform: uppercase; margin-bottom: 15px; font-size:12px;}
.profile-usermenu {margin-top: 30px;}
.profile-usermenu ul li {border-bottom: 1px solid #f0f4f7;}
.profile-usermenu ul li:last-child {border-bottom: none;}
.profile-usermenu ul li a {font-size: 14px; font-weight: 400;}
.profile-usermenu ul li a i {margin-right: 8px; font-size: 14px;}
.profile-usermenu ul li.active {border-bottom: none;}
.profile-usermenu ul li.active a {background-color:#fff; border-left: 2px solid var(--dark); border-radius:0px;}
</style>

<?php do_action( 'woocommerce_after_account_navigation' ); ?>

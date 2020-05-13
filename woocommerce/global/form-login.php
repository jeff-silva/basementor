<?php
/**
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     3.6.0
 */



if ( ! defined( 'ABSPATH' ) ) { exit; }

$input = (object) array_merge([
	'username' => '',
	'email' => '',
], $_POST);

$woocommerce_enable_myaccount_registration = get_option('woocommerce_enable_myaccount_registration');
$wrapper_style = $woocommerce_enable_myaccount_registration=='yes'? '': 'max-width:400px; margin:0 auto;';

do_action( 'woocommerce_before_customer_login_form' ); ?>


<div class="row" style="<?php echo $wrapper_style; ?>">
	<div class="col">
		<div class="card">
			<div class="card-header text-uppercase font-weight-bold">
				<?php esc_html_e( 'Login', 'woocommerce' ); ?>
			</div>
			<div class="card-body">
				<form class="woocommerce-form woocommerce-form-login" method="post">
					<?php do_action( 'woocommerce_login_form_start' ); ?>

					<div class="form-group">
						<label><?php esc_html_e( 'Username or email address', 'woocommerce' ); ?>* </label>
						<input type="text" class="form-control" name="username" id="username" autocomplete="username" value="<?php echo $post->username; ?>" />
					</div>

					<div class="form-group">
						<label><?php esc_html_e( 'Password', 'woocommerce' ); ?> *</label>
						<input class="form-control" type="password" name="password" id="password" autocomplete="current-password" />
					</div>

					<?php do_action('woocommerce_login_form'); ?>
					<?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>

					<div class="row align-items-center">
						<div class="col-6">
							<label><input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <?php esc_html_e( 'Remember me', 'woocommerce' ); ?></label>
							<div><a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'woocommerce' ); ?></a></div>
						</div>

						<div class="col-6">
							<button type="submit" class="btn btn-primary btn-block" name="login" value="<?php esc_attr_e('Log in', 'woocommerce'); ?>">
								<?php esc_html_e('Log in', 'woocommerce'); ?>
							</button>
						</div>
					</div>

					<?php do_action( 'woocommerce_login_form_end' ); ?>
				</form>
			</div>
		</div>
	</div>

	<?php if ('yes' === $woocommerce_enable_myaccount_registration) : ?>
	<div class="col">
		<div class="card">
			<div class="card-header text-uppercase font-weight-bold">
				<?php esc_html_e( 'Register', 'woocommerce' ); ?>
			</div>
			<div class="card-body">
				<form method="post" class="woocommerce-form woocommerce-form-register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >
					<?php do_action( 'woocommerce_register_form_start' ); ?>

					<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>
					<div class="form-group">
						<label><?php esc_html_e( 'Username', 'woocommerce' ); ?> *</label>
						<input type="text" class="form-control" name="username" autocomplete="username" value="<?php echo $input->username; ?>" />
					</div>
					<?php endif; ?>

					<div class="form-group">
						<label><?php esc_html_e( 'Email address', 'woocommerce' ); ?> *</label>
						<input type="email" class="form-control" name="email" autocomplete="email" value="<?php echo $input->email; ?>" />
					</div>

					<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
					<div class="form-group">
						<label><?php esc_html_e( 'Password', 'woocommerce' ); ?> *</label>
						<input type="password" class="form-control" name="password" autocomplete="new-password" />
					</div>
					<?php else : ?>
					
					<p><?php esc_html_e( 'A password will be sent to your email address.', 'woocommerce' ); ?></p>
					<?php endif; ?>

					<?php do_action( 'woocommerce_register_form' ); ?>
					<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>

					<div class="text-right">
						<button type="submit" class="btn btn-primary" name="register" value="<?php esc_attr_e('Register', 'woocommerce'); ?>">
							<?php esc_html_e('Register', 'woocommerce'); ?>
						</button>
					</div>

					<?php do_action( 'woocommerce_register_form_end' ); ?>
				</form>
			</div>
		</div>
	</div>
	<?php endif; ?>
</div>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>

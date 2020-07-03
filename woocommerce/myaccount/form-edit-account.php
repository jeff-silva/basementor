<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_edit_account_form' ); ?>

<form class="woocommerce-EditAccountForm edit-account" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?> >

	<?php do_action( 'woocommerce_edit_account_form_start' ); ?>

	<div class="row">
		<div class="form-group col-12 col-md-6">
			<label><?php esc_html_e( 'First name', 'woocommerce' ); ?></label>
			<input type="text" class="form-control" name="account_first_name" value="<?php echo $user->first_name; ?>">
		</div>

		<div class="form-group col-12 col-md-6">
			<label><?php esc_html_e( 'Last name', 'woocommerce' ); ?></label>
			<input type="text" class="form-control" name="account_last_name" value="<?php echo $user->last_name; ?>">
		</div>

		<div class="form-group col-12">
			<label><?php esc_html_e( 'Display name', 'woocommerce' ); ?></label>
			<input type="text" class="form-control" name="account_email" value="<?php echo $user->user_email; ?>">
		</div>

		<div class="form-group col-12">
			<label>Nome de exibição</label>
			<input type="text" class="form-control" name="account_display_name" value="<?php echo $user->display_name; ?>">
			<div class="text-muted"><?php esc_html_e( 'This will be how your name will be displayed in the account section and in reviews', 'woocommerce' ); ?></div>
		</div>
	</div>


	<div class="card">
		<div class="card-header"><?php esc_html_e( 'Password change', 'woocommerce' ); ?></div>
		<div class="card-body">
			<div style="max-width:600px;">
				<div class="form-group">
					<label><?php esc_html_e( 'Current password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
					<div class="input-group form-control bg-white" style="padding:0px!important;">
						<input type="password" class="form-control" name="password_current" autocomplete="off" style="border:none; background:none;">
						<div class="input-group-btn">
							<button type="button" class="btn btn-default" onclick="this.form.password_current.type = this.form.password_current.type=='password'? 'text': 'password';" style="background:none;">
								<i class="fa fa-fw fa-eye"></i>
							</button>
						</div>
					</div>
				</div>

				<div class="form-group">
					<label><?php esc_html_e( 'New password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
					<div class="input-group form-control bg-white" style="padding:0px!important;">
						<input type="password" class="form-control" name="password_1" autocomplete="off" style="border:none; background:none;">
						<div class="input-group-btn">
							<button type="button" class="btn btn-default" onclick="this.form.password_1.type = this.form.password_1.type=='password'? 'text': 'password';" style="background:none;">
								<i class="fa fa-fw fa-eye"></i>
							</button>
						</div>
					</div>
				</div>

				<div class="form-group">
					<label><?php esc_html_e( 'Confirm new password', 'woocommerce' ); ?></label>
					<div class="input-group form-control bg-white" style="padding:0px!important;">
						<input type="password" class="form-control" name="password_2" autocomplete="off" style="border:none; background:none;">
						<div class="input-group-btn">
							<button type="button" class="btn btn-default" onclick="this.form.password_2.type = this.form.password_2.type=='password'? 'text': 'password';" style="background:none;">
								<i class="fa fa-fw fa-eye"></i>
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php do_action( 'woocommerce_edit_account_form' ); ?>

	<br>
	<div class="text-right">
		<?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
		<button type="submit" class="btn btn-primary" name="save_account_details" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>"><?php esc_html_e( 'Save changes', 'woocommerce' ); ?></button>
		<input type="hidden" name="action" value="save_account_details" />
	</div>

	<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
</form>

<?php do_action( 'woocommerce_after_edit_account_form' ); ?>

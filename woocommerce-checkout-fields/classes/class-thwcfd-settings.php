<?php
/**
 * Woo Checkout Field Editor Settings
 *
 * @author   ThemeHigh
 * @category Admin
 */

defined( 'ABSPATH' ) || exit;

if(!class_exists('THWCFD_Settings')) :

class THWCFD_Settings {
	public function __construct() {
		
	}
	
	public function enqueue_styles_and_scripts($hook) {
		/*if(strpos($hook, 'woocommerce_page_checkout_form_designer') === false) {
			return;
		}*/
		if(strpos($hook, 'page_checkout_form_designer') === false) {
			return;
		}

		$deps = array('jquery', 'jquery-ui-dialog', 'jquery-ui-sortable', 'jquery-tiptip', 'woocommerce_admin', 'select2', 'wp-color-picker');

		wp_enqueue_style('woocommerce_admin_styles');
		wp_enqueue_style('thwcfd-admin-style', THWCFD_ASSETS_URL . 'css/thwcfd-admin.css', THWCFD_VERSION);
		wp_enqueue_script('thwcfd-admin-script', THWCFD_ASSETS_URL . 'js/thwcfd-admin.js', $deps, THWCFD_VERSION, true);
	}

	public function wcfd_capability() {
		$allowed = array('manage_woocommerce', 'manage_options');
		$capability = apply_filters('thwcfd_required_capability', 'manage_woocommerce');

		if(!in_array($capability, $allowed)){
			$capability = 'manage_woocommerce';
		}
		return $capability;
	}
	
	public function admin_menu() {
		$capability = $this->wcfd_capability();
		$this->screen_id = add_submenu_page('woocommerce', 'Customizar checkout', 'Customizar checkout', $capability, 'checkout_form_designer', array($this, 'output_settings'));

		//add_action('admin_print_scripts-'. $this->screen_id, array($this, 'enqueue_admin_scripts'));
	}
	
	public function add_screen_id($ids){
		$ids[] = 'woocommerce_page_checkout_form_designer';
		$ids[] = strtolower(__('WooCommerce', 'woo-checkout-field-editor-pro')) .'_page_checkout_form_designer';

		return $ids;
	}

	public function plugin_action_links($links) {
		$settings_link = '<a href="'.admin_url('admin.php?page=checkout_form_designer').'">'. __('Settings', 'woo-checkout-field-editor-pro') .'</a>';
		array_unshift($links, $settings_link);
		return $links;
	}
	
	/*public function plugin_row_meta( $links, $file ) {
		if(THWCFE_BASE_NAME == $file) {
			$doc_link = esc_url('https://www.themehigh.com/help-guides/woocommerce-checkout-field-editor/');
			$support_link = esc_url('https://www.themehigh.com/help-guides/');
				
			$row_meta = array(
				'docs' => '<a href="'.$doc_link.'" target="_blank" aria-label="'.__('View plugin documentation', 'woo-checkout-field-editor-pro').'">'.__('Docs', 'woo-checkout-field-editor-pro').'</a>',
				'support' => '<a href="'.$support_link.'" target="_blank" aria-label="'. __('Visit premium customer support', 'woo-checkout-field-editor-pro') .'">'. __('Premium support', 'woo-checkout-field-editor-pro') .'</a>',
			);

			return array_merge( $links, $row_meta );
		}
		return (array) $links;
	}*/

	private function output_premium_version_notice(){
		// 
	}

	private function output_review_request_link(){
		// 
	}
	
	public function output_settings(){
		$this->output_premium_version_notice();
		$this->output_review_request_link();

		$tab = $this->get_current_tab();
		if($tab === 'fields'){
			$general_settings = THWCFD_Settings_General::instance();	
			$general_settings->render_page();
		}
	}

	public function get_current_tab(){
		return isset( $_GET['tab'] ) ? esc_attr( $_GET['tab'] ) : 'fields';
	}
}

endif;


<?php
/* WOOF - Products Filter for Woocommerce
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('autoparts_woof_theme_setup9')) {
	add_action( 'after_setup_theme', 'autoparts_woof_theme_setup9', 9 );
	function autoparts_woof_theme_setup9() {
		if (is_admin()) {
			add_filter( 'autoparts_filter_tgmpa_required_plugins',			'autoparts_woof_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'autoparts_woof_tgmpa_required_plugins' ) ) {
	
	function autoparts_woof_tgmpa_required_plugins($list=array()) {
		if (in_array('woocommerce-products-filter', autoparts_storage_get('required_plugins'))) {
			$list[] = array(
				'name' 		=> esc_html__('WOOF - WooCommerce Products Filter', 'autoparts'),
				'slug' 		=> 'woocommerce-products-filter',
				'required' 	=> false
			);
		}
		return $list;
	}
}

// Check if WOOF installed and activated
if ( !function_exists( 'autoparts_exists_woof' ) ) {
	function autoparts_exists_woof() {
		return defined( 'WOOF_VERSION' );
	}
}
?>
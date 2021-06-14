<?php
/* elegro Crypto Payment support functions
------------------------------------------------------------------------------- */
// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'autoparts_elegro_payment_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'autoparts_elegro_payment_theme_setup9', 9 );
	function autoparts_elegro_payment_theme_setup9() {
		if ( autoparts_exists_elegro_payment() ) {
            add_action('wp_enqueue_scripts', 'autoparts_elegro_payment_frontend_scripts', 1100);
			add_filter( 'autoparts_filter_merge_styles', 'autoparts_elegro_payment_merge_styles' );
		}
		if ( is_admin() ) {
			add_filter( 'autoparts_filter_tgmpa_required_plugins', 'autoparts_elegro_payment_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( ! function_exists( 'autoparts_elegro_payment_tgmpa_required_plugins' ) ) {
	function autoparts_elegro_payment_tgmpa_required_plugins( $list = array() ) {
            if (in_array('elegro-payment', autoparts_storage_get('required_plugins'))) {
			$list[] = array(
        'name' 		=> esc_html__('elegro Crypto Payment', 'autoparts'),
				'slug'     => 'elegro-payment',
				'required' => false,
			);
		}
		return $list;
	}
}

// Check if this plugin installed and activated
if ( ! function_exists( 'autoparts_exists_elegro_payment' ) ) {
	function autoparts_exists_elegro_payment() {
		return class_exists( 'WC_Elegro_Payment' );
	}
}

// Merge custom styles
if ( ! function_exists( 'autoparts_elegro_payment_merge_styles' ) ) {
	function autoparts_elegro_payment_merge_styles( $list ) {
		$list[] = 'plugins/elegro-payment/elegro-payment.css';
		return $list;
	}
}
// Enqueue custom styles
if (!function_exists('autoparts_elegro_payment_frontend_scripts')) {
    function autoparts_elegro_payment_frontend_scripts()
    {
        if (autoparts_exists_elegro_payment()) {
            if (autoparts_is_on(autoparts_get_theme_option('debug_mode')) && autoparts_get_file_dir('plugins/elegro-payment/elegro-payment.css') != '')
                wp_enqueue_style('autoparts-elegro-payment', autoparts_get_file_url('plugins/elegro-payment/elegro-payment.css'), array(), null);
        }
    }
}
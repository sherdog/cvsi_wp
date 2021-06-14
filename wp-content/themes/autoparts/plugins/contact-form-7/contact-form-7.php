<?php
/* Contact Form 7 support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('autoparts_cf7_theme_setup9')) {
	add_action( 'after_setup_theme', 'autoparts_cf7_theme_setup9', 9 );
	function autoparts_cf7_theme_setup9() {
		
		if (autoparts_exists_cf7()) {
			add_action( 'wp_enqueue_scripts', 								'autoparts_cf7_frontend_scripts', 1100 );
			add_filter( 'autoparts_filter_merge_styles',						'autoparts_cf7_merge_styles' );
			add_filter( 'autoparts_filter_merge_scripts',						'autoparts_cf7_merge_scripts' );
		}
		if (is_admin()) {
			add_filter( 'autoparts_filter_tgmpa_required_plugins',			'autoparts_cf7_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'autoparts_cf7_tgmpa_required_plugins' ) ) {
	
	function autoparts_cf7_tgmpa_required_plugins($list=array()) {
		if (in_array('contact-form-7', autoparts_storage_get('required_plugins'))) {
			// CF7 plugin
			$list[] = array(
					'name' 		=> esc_html__('Contact Form 7', 'autoparts'),
					'slug' 		=> 'contact-form-7',
					'required' 	=> false
			);
		}
		return $list;
	}
}



// Check if cf7 installed and activated
if ( !function_exists( 'autoparts_exists_cf7' ) ) {
	function autoparts_exists_cf7() {
		return class_exists('WPCF7');
	}
}
	
// Enqueue custom styles
if ( !function_exists( 'autoparts_cf7_frontend_scripts' ) ) {
	
	function autoparts_cf7_frontend_scripts() {
		if (autoparts_is_on(autoparts_get_theme_option('debug_mode')) && autoparts_get_file_dir('plugins/contact-form-7/contact-form-7.css')!='') {
            wp_enqueue_style( 'autoparts-contact-form-7',  autoparts_get_file_url('plugins/contact-form-7/contact-form-7.css'), array(), null );
        }
		if( autoparts_get_file_dir('plugins/contact-form-7/contact-form-7.js')!='' ) {
            $autoparts_url = autoparts_get_file_url( 'plugins/contact-form-7/contact-form-7.js' );
            wp_enqueue_script( 'autoparts-contact-form-7', $autoparts_url, array( 'jquery' ), null, true );
        }
	}
}
// Merge custom scripts
if ( ! function_exists( 'autoparts_cf7_merge_scripts' ) ) {
    //Handler of the add_filter('autoparts_filter_merge_scripts', 'autoparts_cf7_merge_scripts');
    function autoparts_cf7_merge_scripts( $list ) {
        $list[] = 'plugins/contact-form-7/contact-form-7.js';
        return $list;
    }
}
	
// Merge custom styles
if ( !function_exists( 'autoparts_cf7_merge_styles' ) ) {
	
	function autoparts_cf7_merge_styles($list) {
		$list[] = 'plugins/contact-form-7/contact-form-7.css';
		return $list;
	}
}
?>
<?php
/* Mail Chimp support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('autoparts_mailchimp_theme_setup9')) {
	add_action( 'after_setup_theme', 'autoparts_mailchimp_theme_setup9', 9 );
	function autoparts_mailchimp_theme_setup9() {
		if (autoparts_exists_mailchimp()) {
			add_action( 'wp_enqueue_scripts',							'autoparts_mailchimp_frontend_scripts', 1100 );
			add_filter( 'autoparts_filter_merge_styles',					'autoparts_mailchimp_merge_styles');
		}
		if (is_admin()) {
			add_filter( 'autoparts_filter_tgmpa_required_plugins',		'autoparts_mailchimp_tgmpa_required_plugins' );
		}
	}
}

// Check if plugin installed and activated
if ( !function_exists( 'autoparts_exists_mailchimp' ) ) {
	function autoparts_exists_mailchimp() {
		return function_exists('__mc4wp_load_plugin') || defined('MC4WP_VERSION');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'autoparts_mailchimp_tgmpa_required_plugins' ) ) {
	
	function autoparts_mailchimp_tgmpa_required_plugins($list=array()) {
		if (in_array('mailchimp-for-wp', autoparts_storage_get('required_plugins')))
			$list[] = array(
				'name' 		=> esc_html__('MailChimp for WP', 'autoparts'),
				'slug' 		=> 'mailchimp-for-wp',
				'required' 	=> false
			);
		return $list;
	}
}



// Custom styles and scripts
//------------------------------------------------------------------------

// Enqueue custom styles
if ( !function_exists( 'autoparts_mailchimp_frontend_scripts' ) ) {
	
	function autoparts_mailchimp_frontend_scripts() {
		if (autoparts_exists_mailchimp()) {
			if (autoparts_is_on(autoparts_get_theme_option('debug_mode')) && autoparts_get_file_dir('plugins/mailchimp-for-wp/mailchimp-for-wp.css')!='')
				wp_enqueue_style( 'autoparts-mailchimp-for-wp',  autoparts_get_file_url('plugins/mailchimp-for-wp/mailchimp-for-wp.css'), array(), null );
		}
	}
}
	
// Merge custom styles
if ( !function_exists( 'autoparts_mailchimp_merge_styles' ) ) {
	
	function autoparts_mailchimp_merge_styles($list) {
		$list[] = 'plugins/mailchimp-for-wp/mailchimp-for-wp.css';
		return $list;
	}
}


// Add plugin-specific colors and fonts to the custom CSS
if (autoparts_exists_mailchimp()) { require_once AUTOPARTS_THEME_DIR . 'plugins/mailchimp-for-wp/mailchimp-for-wp.styles.php'; }
?>
<?php
/* Booked Appointments support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('autoparts_booked_theme_setup9')) {
	add_action( 'after_setup_theme', 'autoparts_booked_theme_setup9', 9 );
	function autoparts_booked_theme_setup9() {
		if (autoparts_exists_booked()) {
			add_action( 'wp_enqueue_scripts', 							'autoparts_booked_frontend_scripts', 1100 );
			add_filter( 'autoparts_filter_merge_styles',					'autoparts_booked_merge_styles' );
		}
		if (is_admin()) {
			add_filter( 'autoparts_filter_tgmpa_required_plugins',		'autoparts_booked_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'autoparts_booked_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('autoparts_filter_tgmpa_required_plugins',	'autoparts_booked_tgmpa_required_plugins');
	function autoparts_booked_tgmpa_required_plugins($list=array()) {
		if (in_array('booked', autoparts_storage_get('required_plugins'))  ) {
            $path = autoparts_get_file_dir('plugins/booked/booked.zip');
            $list[] = array(
                'name' 		=> esc_html__('Booked', 'autoparts'),
                'slug' 		=> 'booked',
                'version'	=> '2.3',
                'source' 	=> !empty($path) ? $path : 'upload://booked.zip',
                'required' 	=> false
            );
		}
		return $list;
	}
}

// Check if plugin installed and activated
if ( !function_exists( 'autoparts_exists_booked' ) ) {
	function autoparts_exists_booked() {
		return class_exists('booked_plugin');
	}
}
	
// Enqueue plugin's custom styles
if ( !function_exists( 'autoparts_booked_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'autoparts_booked_frontend_scripts', 1100 );
	function autoparts_booked_frontend_scripts() {
		if (autoparts_is_on(autoparts_get_theme_option('debug_mode')) && autoparts_get_file_dir('plugins/booked/booked.css')!='')
			wp_enqueue_style( 'autoparts-booked',  autoparts_get_file_url('plugins/booked/booked.css'), array(), null );
	}
}
	
// Merge custom styles
if ( !function_exists( 'autoparts_booked_merge_styles' ) ) {
	//Handler of the add_filter('autoparts_filter_merge_styles', 'autoparts_booked_merge_styles');
	function autoparts_booked_merge_styles($list) {
		$list[] = 'plugins/booked/booked.css';
		return $list;
	}
}

// Add plugin-specific colors and fonts to the custom CSS
if (autoparts_exists_booked()) { require_once AUTOPARTS_THEME_DIR . 'plugins/booked/booked.styles.php'; }

?>
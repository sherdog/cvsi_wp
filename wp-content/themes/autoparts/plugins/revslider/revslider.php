<?php
/* Revolution Slider support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('autoparts_revslider_theme_setup9')) {
	add_action( 'after_setup_theme', 'autoparts_revslider_theme_setup9', 9 );
	function autoparts_revslider_theme_setup9() {
		if (autoparts_exists_revslider()) {
			add_action( 'wp_enqueue_scripts', 					'autoparts_revslider_frontend_scripts', 1100 );
			add_filter( 'autoparts_filter_merge_styles',			'autoparts_revslider_merge_styles' );
		}
		if (is_admin()) {
			add_filter( 'autoparts_filter_tgmpa_required_plugins','autoparts_revslider_tgmpa_required_plugins' );
		}
	}
}

// Check if RevSlider installed and activated
if ( !function_exists( 'autoparts_exists_revslider' ) ) {
	function autoparts_exists_revslider() {
		return function_exists('rev_slider_shortcode');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'autoparts_revslider_tgmpa_required_plugins' ) ) {
	
	function autoparts_revslider_tgmpa_required_plugins($list=array()) {
		if (in_array('revslider', autoparts_storage_get('required_plugins'))) {
			$path = autoparts_get_file_dir('plugins/revslider/revslider.zip');
			$list[] = array(
					'name' 		=> esc_html__('Revolution Slider', 'autoparts'),
					'slug' 		=> 'revslider',
					'version'	=> '6.3.5',
					'source'	=> !empty($path) ? $path : 'upload://revslider.zip',
					'required' 	=> false
			);
		}
		return $list;
	}
}
	
// Enqueue custom styles
if ( !function_exists( 'autoparts_revslider_frontend_scripts' ) ) {
	
	function autoparts_revslider_frontend_scripts() {
		if (autoparts_is_on(autoparts_get_theme_option('debug_mode')) && autoparts_get_file_dir('plugins/revslider/revslider.css')!='')
			wp_enqueue_style( 'autoparts-revslider',  autoparts_get_file_url('plugins/revslider/revslider.css'), array(), null );
	}
}
	
// Merge custom styles
if ( !function_exists( 'autoparts_revslider_merge_styles' ) ) {
	
	function autoparts_revslider_merge_styles($list) {
		$list[] = 'plugins/revslider/revslider.css';
		return $list;
	}
}
?>
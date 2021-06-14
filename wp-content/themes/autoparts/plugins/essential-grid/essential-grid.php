<?php
/* Essential Grid support functions
------------------------------------------------------------------------------- */


// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('autoparts_essential_grid_theme_setup9')) {
	add_action( 'after_setup_theme', 'autoparts_essential_grid_theme_setup9', 9 );
	function autoparts_essential_grid_theme_setup9() {
        if (autoparts_exists_essential_grid()) {
			add_action( 'wp_enqueue_scripts', 							'autoparts_essential_grid_frontend_scripts', 1100 );
            add_filter( 'autoparts_filter_merge_styles',				'autoparts_essential_grid_merge_styles' );
        }
		if (is_admin()) {
			add_filter( 'autoparts_filter_tgmpa_required_plugins',		'autoparts_essential_grid_tgmpa_required_plugins' );
		}
	}
}

// Check if plugin installed and activated
if ( !function_exists( 'autoparts_exists_essential_grid' ) ) {
	function autoparts_exists_essential_grid() {
		return defined('EG_PLUGIN_PATH');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'autoparts_essential_grid_tgmpa_required_plugins' ) ) {
	
	function autoparts_essential_grid_tgmpa_required_plugins($list=array()) {
		if (in_array('essential-grid', autoparts_storage_get('required_plugins'))){
			$path = autoparts_get_file_dir('plugins/essential-grid/essential-grid.zip');
			$list[] = array(
						'name' 		=> esc_html__('Essential Grid', 'autoparts'),
						'slug' 		=> 'essential-grid',
						'version'	=> '3.0.11',
						'source'	=> !empty($path) ? $path : 'upload://essential-grid.zip',
						'required' 	=> false
			);
		}
		return $list;
	}
}
// Merge custom styles
if ( !function_exists( 'autoparts_essential_grid_merge_styles' ) ) {
    function autoparts_essential_grid_merge_styles($list) {
        $list[] = 'plugins/essential-grid/essential-grid.css';
        return $list;
    }
}
// Enqueue plugin's custom styles
if ( !function_exists( 'autoparts_essential_grid_frontend_scripts' ) ) {
	function autoparts_essential_grid_frontend_scripts() {
		if (autoparts_is_on(autoparts_get_theme_option('debug_mode')) && autoparts_get_file_dir('plugins/essential-grid/essential-grid.css')!='')
			wp_enqueue_style( 'autoparts-essential-grid',  autoparts_get_file_url('plugins/essential-grid/essential-grid.css'), array(), null );
	}
}

?>
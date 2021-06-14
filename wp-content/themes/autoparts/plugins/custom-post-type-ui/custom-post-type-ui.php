<?php
/* Custom Post Type support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('autoparts_custom_post_type_ui_theme_setup9')) {
    add_action( 'after_setup_theme', 'autoparts_custom_post_type_ui_theme_setup9', 9 );
    function autoparts_custom_post_type_ui_theme_setup9() {
        if (is_admin()) {
            add_filter( 'autoparts_filter_tgmpa_required_plugins',		'autoparts_custom_post_type_ui_tgmpa_required_plugins' );
        }
    }
}

// Filter to add in the required plugins list
if ( !function_exists( 'autoparts_custom_post_type_ui_tgmpa_required_plugins' ) ) {
    
    function autoparts_custom_post_type_ui_tgmpa_required_plugins($list=array()) {
		if (in_array('custom-post-type-ui', autoparts_storage_get('required_plugins'))) {
            $list[] = array(
				'name' 		=> esc_html__('Custom Post Type UI', 'autoparts'),
                'slug' 		=> 'custom-post-type-ui',
                'required' 	=> false
            );
        }
        return $list;
    }
}



// Check if plugin installed and activated
if ( !function_exists( 'autoparts_exists_custom_post_type_ui' ) ) {
    function autoparts_exists_custom_post_type_ui() {
        return function_exists('cptui_register_single_post_type');
    }
}


?>
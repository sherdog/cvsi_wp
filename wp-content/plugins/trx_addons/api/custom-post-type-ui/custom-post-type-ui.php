<?php
/**
 * Plugin support: Custom Post Type UI
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.6.38
 */

// Check if plugin installed and activated
if ( !function_exists( 'trx_addons_exists_custom_post_type_ui' ) ) {
    function trx_addons_exists_custom_post_type_ui() {
        return defined( 'CPT_VERSION' );
    }
}


// One-click import support
//------------------------------------------------------------------------

// Check plugin in the required plugins
if ( !function_exists( 'trx_addons_custom_post_type_ui_importer_required_plugins' ) ) {
    if (is_admin()) add_filter( 'trx_addons_filter_importer_required_plugins',	'trx_addons_custom_post_type_ui_importer_required_plugins', 10, 2 );
    function trx_addons_custom_post_type_ui_importer_required_plugins($not_installed='', $list='') {
        if (strpos($list, 'custom-post-type-ui')!==false && !trx_addons_exists_custom_post_type_ui() )
            $not_installed .= '<br>' . esc_html__('Advanced Custom Fields', 'trx_addons');
        return $not_installed;
    }
}

// Set plugin's specific importer options
if ( !function_exists( 'trx_addons_custom_post_type_ui_importer_set_options' ) ) {
    if (is_admin()) add_filter( 'trx_addons_filter_importer_options',	'trx_addons_custom_post_type_ui_importer_set_options' );
    function trx_addons_custom_post_type_ui_importer_set_options($options=array()) {
        if ( trx_addons_exists_custom_post_type_ui() && in_array('custom-post-type-ui', $options['required_plugins']) ) {
            $options['additional_options'][] = 'cptui_%';
        }
        return $options;
    }
}


// Add checkbox to the one-click importer
if ( !function_exists( 'trx_addons_custom_post_type_ui_importer_show_params' ) ) {
    if (is_admin()) add_action( 'trx_addons_action_importer_params',	'trx_addons_custom_post_type_ui_importer_show_params', 10, 1 );
    function trx_addons_custom_post_type_ui_importer_show_params($importer) {
        if ( trx_addons_exists_custom_post_type_ui() && in_array('custom-post-type-ui', $importer->options['required_plugins']) ) {
            $importer->show_importer_params(array(
                'slug' => 'custom-post-type-ui',
                'title' => esc_html__('Import Custom Post Type UI', 'trx_addons'),
                'part' => 1
            ));
        }
    }
}

// Display import progress
if ( !function_exists( 'trx_addons_custom_post_type_ui_importer_import_fields' ) ) {
    if (is_admin()) add_action( 'trx_addons_action_importer_import_fields',	'trx_addons_custom_post_type_ui_importer_import_fields', 10, 1 );
    function trx_addons_custom_post_type_ui_importer_import_fields($importer) {
        if ( trx_addons_exists_custom_post_type_ui() && in_array('custom-post-type-ui', $importer->options['required_plugins']) ) {
            $importer->show_importer_fields(array(
                    'slug'=>'custom-post-type-ui',
                    'title' => esc_html__('Custom Post Type UI meta', 'trx_addons')
                )
            );
        }
    }
}


?>
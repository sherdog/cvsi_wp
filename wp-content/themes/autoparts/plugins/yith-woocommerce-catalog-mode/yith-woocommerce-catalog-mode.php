<?php
/* WOOF - Products Filter for Woocommerce
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('autoparts_yith_wcm_theme_setup9')) {
    add_action( 'after_setup_theme', 'autoparts_yith_wcm_theme_setup9', 9 );
    function autoparts_yith_wcm_theme_setup9() {
        if (is_admin()) {
            add_filter( 'autoparts_filter_tgmpa_required_plugins',          'autoparts_yith_wcm_tgmpa_required_plugins' );
        }
    }
}

// Filter to add in the required plugins list
if ( !function_exists( 'autoparts_yith_wcm_tgmpa_required_plugins' ) ) {
    
    function autoparts_yith_wcm_tgmpa_required_plugins($list=array()) {
        if (in_array('yith-woocommerce-catalog-mode', autoparts_storage_get('required_plugins'))) {
            $list[] = array(
                'name'      => esc_html__('YITH WooCommerce Catalog Mode', 'autoparts'),
                'slug'      => 'yith-woocommerce-catalog-mode',
                'required'  => false
            );
        }
        return $list;
    }
}

// Check if WOOF installed and activated
if ( !function_exists( 'autoparts_exists_yith_wcm' ) ) {
    function autoparts_exists_yith_wcm() {
        return class_exists( 'YITH_WooCommerce_Catalog_Mode' );
    }
}

/* Import Options */
// Set plugin's specific importer options
if ( !function_exists( 'autoparts_woocommerce_yith_wcm' ) ) {
    add_filter( 'trx_addons_filter_importer_options',	'autoparts_woocommerce_yith_wcm' );
    function autoparts_woocommerce_yith_wcm($options=array()) {
        if ( autoparts_exists_yith_wcm() && in_array('yith-woocommerce-catalog-mode', $options['required_plugins']) ) {
            $options['additional_options'][]	= 'ywctm_%';
        }
        return $options;
    }
}

?>
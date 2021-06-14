<?php
/* ThemeREX Updater support functions
------------------------------------------------------------------------------- */


// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'autoparts_trx_updater_theme_setup9' ) ) {
    add_action( 'after_setup_theme', 'autoparts_trx_updater_theme_setup9', 9 );
    function autoparts_trx_updater_theme_setup9() {
        if ( is_admin() ) {
            add_filter( 'autoparts_filter_tgmpa_required_plugins', 'autoparts_trx_updater_tgmpa_required_plugins', 8 );
        }
    }
}


// Filter to add in the required plugins list
if ( ! function_exists( 'autoparts_trx_updater_tgmpa_required_plugins' ) ) {
    function autoparts_trx_updater_tgmpa_required_plugins( $list = array() ) {
        if (in_array('trx_updater', autoparts_storage_get('required_plugins'))) {
            $path = autoparts_get_file_dir( 'plugins/trx_updater/trx_updater.zip' );
            $list[] = array(
                'name' 		=> esc_html__('ThemeREX Updater', 'autoparts'),
                'slug'     => 'trx_updater',
                'version'  => '1.5.3',
                'source'   => ! empty( $path ) ? $path : 'upload://trx_updater.zip',
                'required' => false,
            );

        }
        return $list;
    }
}
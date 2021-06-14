<?php
/**
 * Plugin support: WooCommerce Products Filter
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.29
 */

// Check if plugin installed and activated
// Attention! This function is used in many files and was moved to the api.php

if ( !function_exists( 'trx_addons_exists_woof' ) ) {
	function trx_addons_exists_woof() {
		return defined( 'WOOF_VERSION' );
	}
}


// One-click import support
//------------------------------------------------------------------------

// Check plugin in the required plugins
if ( !function_exists( 'trx_addons_woof_importer_required_plugins' ) ) {
	if (is_admin()) add_filter( 'trx_addons_filter_importer_required_plugins',	'trx_addons_woof_importer_required_plugins', 10, 2 );
	function trx_addons_woof_importer_required_plugins($not_installed='', $list='') {
		if (strpos($list, 'woocommerce-products-filter')!==false && !trx_addons_exists_woof() )
			$not_installed .= '<br>' . esc_html__('Import WooCommerce Products Filter', 'trx_addons');
		return $not_installed;
	}
}


// Set plugin's specific importer options
if ( !function_exists( 'trx_addons_woof_importer_set_options' ) ) {
	if (is_admin()) add_filter( 'trx_addons_filter_importer_options',	'trx_addons_woof_importer_set_options' );
	function trx_addons_woof_importer_set_options($options=array()) {
		if ( trx_addons_exists_woof() && in_array('woocommerce-products-filter', $options['required_plugins']) ) {
			$options['additional_options'][]	= 'woof_%';					// Add slugs to export options for this plugin
		}
		return $options;
	}
}

// Add checkbox to the one-click importer
if ( !function_exists( 'trx_addons_woof_importer_show_params' ) ) {
	if (is_admin()) add_action( 'trx_addons_action_importer_params',	'trx_addons_woof_importer_show_params', 10, 1 );
	function trx_addons_woof_importer_show_params($importer) {
		if ( trx_addons_exists_woof() && in_array('woocommerce-products-filter', $importer->options['required_plugins']) ) {
			$importer->show_importer_params(array(
				'slug' => 'woocommerce-products-filter',
				'title' => esc_html__('Import WooCommerce Products Filter', 'trx_addons'),
				'part' => 1
			));
		}
	}
}

// Display import progress
if ( !function_exists( 'trx_addons_woof_importer_import_fields' ) ) {
	if (is_admin()) add_action( 'trx_addons_action_importer_import_fields',	'trx_addons_woof_importer_import_fields', 10, 1 );
	function trx_addons_woof_importer_import_fields($importer) {
		if ( trx_addons_exists_woof() && in_array('woocommerce-products-filter', $importer->options['required_plugins']) ) {
			$importer->show_importer_fields(array(
				'slug'=>'woocommerce-products-filter',
				'title' => esc_html__('WooCommerce Products Filter meta', 'trx_addons')
				)
			);
		}
	}
}
?>
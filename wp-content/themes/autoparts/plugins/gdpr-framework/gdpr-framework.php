<?php
/* GDPR Framework support functions
------------------------------------------------------------------------------- */



// Filter to add in the required plugins list
if ( !function_exists( 'autoparts_gdpr_tgmpa_required_plugins' ) ) {
	add_filter('autoparts_filter_tgmpa_required_plugins',	'autoparts_gdpr_tgmpa_required_plugins');
	function autoparts_gdpr_tgmpa_required_plugins($list=array()) {
            if (in_array('gdpr-framework', autoparts_storage_get('required_plugins')))
			$list[] = array(
                    'name' 		=> esc_html__('GDRP Framework', 'autoparts'),
					'slug' 		=> 'gdpr-framework',
					'required' 	=> false
			);
		return $list;
	}
}

// Check if cf7 installed and activated
if ( !function_exists( 'autoparts_exists_gdpr' ) ) {
	function autoparts_exists_gdpr() {
		return defined('GDPR_FRAMEWORK_VERSION');
	}
}
?>
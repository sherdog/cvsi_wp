<?php
/**
 * Shortcode: Display any previously created layout (WPBakery support)
 *
 * @package ThemeREX Addons
 * @since v1.6.06
 */

// Don't load directly
if ( ! defined( 'TRX_ADDONS_VERSION' ) ) {
	exit;
}


// Add [trx_sc_layouts] in the VC shortcodes list
if (!function_exists('trx_addons_sc_layouts_add_in_vc')) {
	function trx_addons_sc_layouts_add_in_vc() {

	    if (!trx_addons_exists_vc()) return;

		vc_lean_map( "trx_sc_layouts", 'trx_addons_sc_layouts_add_in_vc_params');
		class WPBakeryShortCode_Trx_Sc_Layouts extends WPBakeryShortCode {}
	}
	add_action('init', 'trx_addons_sc_layouts_add_in_vc', 20);
}


// Return params
if (!function_exists('trx_addons_sc_layouts_add_in_vc_params')) {
	function trx_addons_sc_layouts_add_in_vc_params() {
		// If open params in VC Editor
		list($vc_edit, $vc_params) = trx_addons_get_vc_form_params('trx_sc_layouts');
		$layouts = trx_addons_array_merge(	array(
												0 => __('- Use content -', 'trx_addons')
											),
											trx_addons_get_list_layouts()
										);
		$default = trx_addons_array_get_first($layouts);
		$layout = $vc_edit && !empty($vc_params['layout']) ? $vc_params['layout'] : $default;

		return apply_filters('trx_addons_sc_map', array(
				"base" => "trx_sc_layouts",
				"name" => esc_html__("Layouts", 'trx_addons'),
				"description" => wp_kses_data( __("Display previously created custom layouts", 'trx_addons') ),
				"category" => esc_html__('Layouts', 'trx_addons'),
				"icon" => 'icon_trx_sc_layouts',
				"class" => "trx_sc_layouts",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array_merge(
					array(
						array(
							"param_name" => "type",
							"heading" => esc_html__("Type", 'trx_addons'),
							"description" => wp_kses_data( __("Select shortcodes's type", 'trx_addons') ),
							"admin_label" => true,
							'edit_field_class' => 'vc_col-sm-4',
							"std" => "default",
							"value" => array_flip(apply_filters('trx_addons_sc_type', trx_addons_get_list_sc_layouts_type(), 'trx_sc_layouts' )),
							"type" => "dropdown"
						),
						array(
							"param_name" => "layout",
							"heading" => esc_html__("Layout", 'trx_addons'),
							"description" => wp_kses( __("Select any previously created layout to insert to this page", 'trx_addons')
															. '<br>'
															. sprintf('<a href="%1$s" class="trx_addons_post_editor'.(intval($layout)==0 ? ' trx_addons_hidden' : '').'" target="_blank">%2$s</a>',
																		admin_url( sprintf( "post.php?post=%d&amp;action=edit", $layout ) ),
																		__("Open selected layout in a new tab to edit", 'trx_addons')
																	),
														'trx_addons_kses_content'
														),
							"admin_label" => true,
							'edit_field_class' => 'vc_col-sm-4',
							'save_always' => true,
							"value" => array_flip($layouts),
							"std" => $default,
							"type" => "dropdown"
						),
						array(
							'param_name' => 'content',
							'heading' => esc_html__( 'Content', 'trx_addons' ),
							"description" => wp_kses_data( __("Alternative content to be used instead of the selected layout", 'trx_addons') ),
							'value' => '',
							'holder' => 'div',
							'type' => 'textarea_html',
						)
					),
					trx_addons_vc_add_id_param()
				)
			), 'trx_sc_layouts' );
	}
}

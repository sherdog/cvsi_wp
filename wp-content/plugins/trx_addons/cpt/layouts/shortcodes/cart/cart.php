<?php
/**
 * Shortcode: Display WooCommerce cart with items number and totals
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.6.08
 */

	
// Load required styles and scripts for the frontend
if ( !function_exists( 'trx_addons_sc_layouts_cart_load_scripts_front' ) ) {
	add_action("wp_enqueue_scripts", 'trx_addons_sc_layouts_cart_load_scripts_front');
	function trx_addons_sc_layouts_cart_load_scripts_front() {
		if (trx_addons_is_on(trx_addons_get_option('debug_mode'))) {
			wp_enqueue_style( 'trx_addons-sc_layouts_cart', trx_addons_get_file_url('cpt/layouts/shortcodes/cart/cart.css'), array(), null );
		}
	}
}

	
// Merge shortcode specific styles into single stylesheet
if ( !function_exists( 'trx_addons_sc_layouts_cart_merge_styles' ) ) {
	add_action("trx_addons_filter_merge_styles", 'trx_addons_sc_layouts_cart_merge_styles');
	function trx_addons_sc_layouts_cart_merge_styles($list) {
		$list[] = 'cpt/layouts/shortcodes/cart/cart.css';
		return $list;
	}
}

	
// Merge shortcode's specific scripts into single file
if ( !function_exists( 'trx_addons_sc_layouts_cart_merge_scripts' ) ) {
	add_action("trx_addons_filter_merge_scripts", 'trx_addons_sc_layouts_cart_merge_scripts');
	function trx_addons_sc_layouts_cart_merge_scripts($list) {
		$list[] = 'cpt/layouts/shortcodes/cart/cart.js';
		return $list;
	}
}



// trx_sc_layouts_cart
//-------------------------------------------------------------
/*
[trx_sc_layouts_cart id="unique_id" text="Shopping cart"]
*/
if ( !function_exists( 'trx_addons_sc_layouts_cart' ) ) {
	function trx_addons_sc_layouts_cart($atts, $content=null){	
		$atts = trx_addons_sc_prepare_atts('trx_sc_layouts_cart', $atts, array(
			// Individual params
			"type" => "default",
			"text" => "",
			"hide_on_tablet" => "0",
			"hide_on_mobile" => "0",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
			)
		);

		if (trx_addons_is_on(trx_addons_get_option('debug_mode')))
			wp_enqueue_script( 'trx_addons-sc_layouts_cart', trx_addons_get_file_url('cpt/layouts/shortcodes/cart/cart.js'), array('jquery'), null, true );

		ob_start();
		trx_addons_get_template_part(array(
										'cpt/layouts/shortcodes/cart/tpl.'.trx_addons_esc($atts['type']).'.php',
										'cpt/layouts/shortcodes/cart/tpl.default.php'
										),
										'trx_addons_args_sc_layouts_cart',
										$atts
									);
		$output = ob_get_contents();
		ob_end_clean();

		return apply_filters('trx_addons_sc_output', $output, 'trx_sc_layouts_cart', $atts, $content);
	}
}


// Add [trx_sc_layouts_cart] in the VC shortcodes list
if (!function_exists('trx_addons_sc_layouts_cart_add_in_vc')) {
	function trx_addons_sc_layouts_cart_add_in_vc() {
        if (!trx_addons_cpt_layouts_sc_required()) return;
		if (!trx_addons_exists_visual_composer()) return;

		add_shortcode("trx_sc_layouts_cart", "trx_addons_sc_layouts_cart");
	
		vc_lean_map("trx_sc_layouts_cart", 'trx_addons_sc_layouts_cart_add_in_vc_params');
		class WPBakeryShortCode_Trx_Sc_Layouts_Cart extends WPBakeryShortCode {}

	}
	add_action('init', 'trx_addons_sc_layouts_cart_add_in_vc', 15);
}

// Return params
if (!function_exists('trx_addons_sc_layouts_cart_add_in_vc_params')) {
	function trx_addons_sc_layouts_cart_add_in_vc_params() {
		return apply_filters('trx_addons_sc_map', array(
				"base" => "trx_sc_layouts_cart",
				"name" => esc_html__("Layouts: Cart", 'trx_addons'),
				"description" => wp_kses_data( __("Insert cart with items number and totals to the custom layout", 'trx_addons') ),
				"category" => esc_html__('Layouts', 'trx_addons'),
				"icon" => 'icon_trx_sc_layouts_cart',
				"class" => "trx_sc_layouts_cart",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array_merge(
					array(
						array(
							"param_name" => "type",
							"heading" => esc_html__("Layout", 'trx_addons'),
							"description" => wp_kses_data( __("Select shortcodes's layout", 'trx_addons') ),
							"std" => "default",
							"value" => apply_filters('trx_addons_sc_type', array(
								esc_html__('Default', 'trx_addons') => 'default',
							), 'trx_sc_layouts_cart' ),
							"type" => "dropdown"
						),
						array(
							"param_name" => "text",
							"heading" => esc_html__("Cart text", 'trx_addons'),
							"description" => wp_kses_data( __("Text in the first line.", 'trx_addons') ),
							"admin_label" => true,
							"value" => "",
							"type" => "textfield"
						)
					),
					trx_addons_vc_add_hide_param(),
					trx_addons_vc_add_id_param()
				)
			), 'trx_sc_layouts_cart');
	}
}

// Elementor Widget
//------------------------------------------------------
if (!function_exists('trx_addons_sc_layouts_cart_add_in_elementor')) {
    add_action( 'elementor/widgets/widgets_registered', 'trx_addons_sc_layouts_cart_add_in_elementor' );
    function trx_addons_sc_layouts_cart_add_in_elementor() {
        class TRX_Addons_Elementor_Widget_Layouts_Cart extends TRX_Addons_Elementor_Layouts_Widget {

            /**
             * Retrieve widget name.
             *
             * @since 1.6.41
             * @access public
             *
             * @return string Widget name.
             */
            public function get_name() {
                return 'trx_sc_layouts_cart';
            }

            /**
             * Retrieve widget title.
             *
             * @since 1.6.41
             * @access public
             *
             * @return string Widget title.
             */
            public function get_title() {
                return __( 'Layouts: Cart', 'trx_addons' );
            }

            /**
             * Retrieve widget icon.
             *
             * @since 1.6.41
             * @access public
             *
             * @return string Widget icon.
             */
            public function get_icon() {
                return 'fa fa-shopping-cart';
            }

            /**
             * Retrieve the list of categories the widget belongs to.
             *
             * Used to determine where to display the widget in the editor.
             *
             * @since 1.6.41
             * @access public
             *
             * @return array Widget categories.
             */
            public function get_categories() {
                return ['trx_addons-layouts'];
            }

            /**
             * Register widget controls.
             *
             * Adds different input fields to allow the user to change and customize the widget settings.
             *
             * @since 1.6.41
             * @access protected
             */
            protected function _register_controls() {
                $this->start_controls_section(
                    'section_sc_layouts_cart',
                    [
                        'label' => __( 'Layouts: Cart', 'trx_addons' ),
                    ]
                );

                $this->add_control(
                    'type',
                    [
                        'label' => __( 'Layout', 'trx_addons' ),
                        'label_block' => false,
                        'type' => \Elementor\Controls_Manager::SELECT,
                        'options' => apply_filters('trx_addons_sc_type', array(
                            'default' => esc_html__('Default', 'trx_addons'),
                        ), 'trx_sc_layouts_cart'),
                        'default' => 'default'
                    ]
                );

                $this->add_control(
                    'market',
                    [
                        'label' => __( 'Market', 'trx_addons' ),
                        'label_block' => false,
                        'type' => \Elementor\Controls_Manager::SELECT,
                        'options' => apply_filters('trx_addons_sc_cart_market', array(
                            'woocommerce' => esc_html__('WooCommerce', 'trx_addons'),
                        ), 'trx_sc_layouts_cart'),
                        'default' => 'woocommerce'
                    ]
                );

                $this->add_control(
                    'text',
                    [
                        'label' => __( 'Cart text', 'trx_addons' ),
                        'label_block' => false,
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'default' => ''
                    ]
                );

                $this->end_controls_section();
            }

            /**
             * Render widget's template for the editor.
             *
             * Written as a Backbone JavaScript template and used to generate the live preview.
             *
             * @since 1.6.41
             * @access protected
             */
            protected function _content_template() {
                trx_addons_get_template_part(TRX_ADDONS_PLUGIN_CPT_LAYOUTS_SHORTCODES . "cart/tpe.cart.php",
                    'trx_addons_args_sc_layouts_cart',
                    array('element' => $this)
                );
            }
        }

        // Register widget
        \Elementor\Plugin::$instance->widgets_manager->register_widget_type( new TRX_Addons_Elementor_Widget_Layouts_Cart() );
    }
}


?>
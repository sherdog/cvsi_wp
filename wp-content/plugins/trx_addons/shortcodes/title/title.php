<?php
/**
 * Shortcode: Content container
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.4.3
 */

 
 
	
// Load required styles and scripts for the frontend
if ( !function_exists( 'trx_addons_sc_title_load_scripts_front' ) ) {
	add_action("wp_enqueue_scripts", 'trx_addons_sc_title_load_scripts_front');
	function trx_addons_sc_title_load_scripts_front() {
		if (trx_addons_is_on(trx_addons_get_option('debug_mode'))) {
			wp_enqueue_style( 'trx_addons-sc_title', trx_addons_get_file_url('shortcodes/title/title.css'), array(), null );
		}
	}
}

	
// Merge shortcode's specific styles into single stylesheet
if ( !function_exists( 'trx_addons_sc_title_merge_styles' ) ) {
	add_action("trx_addons_filter_merge_styles", 'trx_addons_sc_title_merge_styles');
	function trx_addons_sc_title_merge_styles($list) {
		$list[] = 'shortcodes/title/title.css';
		return $list;
	}
}


// trx_sc_title
//-------------------------------------------------------------
/*
[trx_sc_title id="unique_id" title="" subtitle="" description=""]
*/
if ( !function_exists( 'trx_addons_sc_title' ) ) {
	function trx_addons_sc_title($atts, $content=null){	
		$atts = trx_addons_sc_prepare_atts('trx_sc_title', $atts, array(
			// Individual params
			'type' => '',
			"title" => "",
			"counter" => "",
			"subtitle" => "",
			"description" => "",
			"link" => '',
			"link_image" => '',
			"link_text" => esc_html__('Learn more', 'trx_addons'),
			"title_align" => "left",
			"title_style" => "default",
			"title_tag" => '',
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
			)
		);
		
		$output = '';

		if (empty($atts['type'])) $atts['type'] = $atts['title_style'];
		else $atts['title_style'] = $atts['type'];
		
		if (!empty($atts['title']) || !empty($atts['subtitle']) || !empty($atts['description'])) {

			ob_start();
			trx_addons_get_template_part(array(
											'shortcodes/title/tpl.'.trx_addons_esc($atts['type']).'.php',
											'shortcodes/title/tpl.default.php'
											),
                                            'trx_addons_args_sc_title',
                                            $atts
                                        );
			$output = ob_get_contents();
			ob_end_clean();

		}
		
		return apply_filters('trx_addons_sc_output', $output, 'trx_sc_title', $atts, $content);
	}
}


// Add [trx_sc_content] in the VC shortcodes list
if (!function_exists('trx_addons_sc_title_add_in_vc')) {
	function trx_addons_sc_title_add_in_vc() {
		
		if (!trx_addons_exists_visual_composer()) return;
		
		add_shortcode("trx_sc_title", "trx_addons_sc_title");
		
		vc_lean_map("trx_sc_title", 'trx_addons_sc_title_add_in_vc_params');
		class WPBakeryShortCode_Trx_Sc_Title extends WPBakeryShortCode {}
	}
	add_action('init', 'trx_addons_sc_title_add_in_vc', 20);
}

// Return params
if (!function_exists('trx_addons_sc_title_add_in_vc_params')) {
	function trx_addons_sc_title_add_in_vc_params() {
		return apply_filters('trx_addons_sc_map', array(
				"base" => "trx_sc_title",
				"name" => esc_html__("Title", 'trx_addons'),
				"description" => wp_kses_data( __("Add title, subtitle and description", 'trx_addons') ),
				"category" => esc_html__('ThemeREX', 'trx_addons'),
				"icon" => 'icon_trx_sc_title',
				"class" => "trx_sc_title",
				'content_element' => true,
				'is_container' => false,
				"show_settings_on_create" => true,
				"params" => array_merge(					
					trx_addons_vc_add_title_param(''),
					array(
						array(
								"param_name" => "counter",
								"heading" => esc_html__("Title counter", 'trx_addons'),
								"description" => wp_kses_data( __("Counter", 'trx_addons') ),
								'edit_field_class' => 'vc_col-sm-6',
								"type" => "textfield"
							)
					),
					trx_addons_vc_add_id_param()					
				)
			), 'trx_sc_title' );
	}
}

// Elementor Widget
//------------------------------------------------------
if (!function_exists('trx_addons_sc_title_add_in_elementor')) {
    add_action( 'elementor/widgets/widgets_registered', 'trx_addons_sc_title_add_in_elementor' );
    function trx_addons_sc_title_add_in_elementor() {
        class TRX_Addons_Elementor_Widget_Title extends TRX_Addons_Elementor_Widget {

            /**
             * Retrieve widget name.
             *
             * @since 1.6.41
             * @access public
             *
             * @return string Widget name.
             */
            public function get_name() {
                return 'trx_sc_title';
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
                return __( 'Title', 'trx_addons' );
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
                return 'eicon-t-letter';
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
                return ['trx_addons-elements'];
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
                    'section_sc_title',
                    [
                        'label' => __( 'Title', 'trx_addons' ),
                    ]
                );

                $this->add_title_param('', !trx_addons_elm_is_preview() ? [] : [
                    'title_align' => ['default' => ''],
                    'title' => ['default' => __('Title of the block', 'trx_addons')],
                    'subtitle' => ['default' => ''],
                    'description' => ['default' => ''],
                ]);

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
                trx_addons_get_template_part(TRX_ADDONS_PLUGIN_DIR . "shortcodes/title/tpe.title.php",
                    'trx_addons_args_sc_title',
                    array('element' => $this)
                );
            }
        }

        // Register widget
        \Elementor\Plugin::$instance->widgets_manager->register_widget_type( new TRX_Addons_Elementor_Widget_Title() );
    }
}

?>
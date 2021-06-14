<?php
/**
 * Shortcode: Blogger
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.2
 */

// Don't load directly
if ( ! defined( 'TRX_ADDONS_VERSION' ) ) {
	die( '-1' );
}


// Load required styles and scripts for the frontend
if ( !function_exists( 'trx_addons_sc_blogger_load_scripts_front' ) ) {
	add_action("wp_enqueue_scripts", 'trx_addons_sc_blogger_load_scripts_front');
	function trx_addons_sc_blogger_load_scripts_front() {
		if (trx_addons_is_on(trx_addons_get_option('debug_mode'))) {
			wp_enqueue_style( 'trx_addons-sc_blogger', trx_addons_get_file_url('shortcodes/blogger/blogger.css'), array(), null );
		}
	}
}

	
// Merge shortcode's specific styles into single stylesheet
if ( !function_exists( 'trx_addons_sc_blogger_merge_styles' ) ) {
	add_action("trx_addons_filter_merge_styles", 'trx_addons_sc_blogger_merge_styles');
	function trx_addons_sc_blogger_merge_styles($list) {
		$list[] = 'shortcodes/blogger/blogger.css';
		return $list;
	}
}



// trx_sc_blogger
//-------------------------------------------------------------
/*
[trx_sc_blogger id="unique_id" type="default" cat="category_slug or id" count="3" columns="3" slider="0|1"]
*/
if ( !function_exists( 'trx_addons_sc_blogger' ) ) {
	function trx_addons_sc_blogger($atts, $content=null) {	
		$atts = trx_addons_sc_prepare_atts('trx_sc_blogger', $atts, array(
			// Individual params
			"type" => 'classic',
			"hide_excerpt" => 0,
			"hide_meta" => 0,
			"columns" => '',
			'post_type' => 'post',
			'taxonomy' => 'category',
			"cat" => '',
			"count" => 3,
			"offset" => 0,
			"orderby" => '',
			"order" => '',
			"ids" => '',
			"slider" => 0,
			"slider_pagination" => "none",
			"slider_controls" => "none",
			"slides_space" => 0,
			"title" => '',
			"subtitle" => '',
			"description" => '',
			"link" => '',
			"link_image" => '',
			"link_text" => esc_html__('Learn more', 'trx_addons'),
			"title_align" => 'left',
			"title_style" => 'default',
			"title_tag" => '',
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
			)
		);
		
		if (!empty($atts['ids'])) {
			$atts['ids'] = str_replace(array(';', ' '), array(',', ''), $atts['ids']);
			$atts['count'] = count(explode(',', $atts['ids']));
		}
		$atts['count'] = max(1, (int) $atts['count']);
		$atts['offset'] = max(0, (int) $atts['offset']);
		if (empty($atts['orderby'])) $atts['orderby'] = 'date';
		if (empty($atts['order'])) $atts['order'] = 'desc';
		$atts['slider'] = max(0, (int) $atts['slider']);
		if ($atts['slider'] > 0 && (int) $atts['slider_pagination'] > 0) $atts['slider_pagination'] = 'bottom';

		ob_start();
		trx_addons_get_template_part(array(
										'shortcodes/blogger/tpl.'.trx_addons_esc($atts['type']).'.php',
										'shortcodes/blogger/tpl.default.php'
										),
                                        'trx_addons_args_sc_blogger', 
                                        $atts
                                    );
		$output = ob_get_contents();
		ob_end_clean();
		
		return apply_filters('trx_addons_sc_output', $output, 'trx_sc_blogger', $atts, $content);
	}
}


// Add [trx_sc_blogger] in the VC shortcodes list
if (!function_exists('trx_addons_sc_blogger_add_in_vc')) {
	function trx_addons_sc_blogger_add_in_vc() {

		if (!trx_addons_exists_visual_composer()) return;
		
		add_shortcode("trx_sc_blogger", "trx_addons_sc_blogger");
		
		vc_lean_map("trx_sc_blogger", 'trx_addons_sc_blogger_add_in_vc_params');
		class WPBakeryShortCode_Trx_Sc_Blogger extends WPBakeryShortCode {}
	}
	add_action('init', 'trx_addons_sc_blogger_add_in_vc', 20);
}


// Return params
if (!function_exists('trx_addons_sc_blogger_add_in_vc_params')) {
	function trx_addons_sc_blogger_add_in_vc_params() {
		// If open params in VC Editor
		$vc_edit = is_admin() && trx_addons_get_value_gp('action')=='vc_edit_form' && trx_addons_get_value_gp('tag') == 'trx_sc_blogger';
		$vc_params = $vc_edit && isset($_POST['params']) ? $_POST['params'] : array();
		// Prepare lists
		$post_type = $vc_edit && !empty($vc_params['post_type']) ? $vc_params['post_type'] : 'post';
		$taxonomy = $vc_edit && !empty($vc_params['taxonomy']) ? $vc_params['taxonomy'] : 'category';
		$taxonomies_objects = get_object_taxonomies($post_type, 'objects');
		$taxonomies = array();
		if (is_array($taxonomies_objects)) {
			foreach ($taxonomies_objects as $slug=>$taxonomy_obj) {
				$taxonomies[$slug] = $taxonomy_obj->label;
			}
		}
		$tax_obj = get_taxonomy($taxonomy);
		return apply_filters('trx_addons_sc_map', array(
				"base" => "trx_sc_blogger",
				"name" => esc_html__("Blogger", 'trx_addons'),
				"description" => wp_kses_data( __("Display posts from specified category in many styles", 'trx_addons') ),
				"category" => esc_html__('ThemeREX', 'trx_addons'),
				"icon" => 'icon_trx_sc_blogger',
				"class" => "trx_sc_blogger",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array_merge(
					array(
/*
						array(
							"param_name" => "type",
							"heading" => esc_html__("Layout", 'trx_addons'),
							"description" => wp_kses_data( __("Select shortcode's layout", 'trx_addons') ),
							"admin_label" => true,
					        'save_always' => true,
							"std" => "default",
							"value" => apply_filters('trx_addons_sc_type', array_flip(trx_addons_components_get_allowed_layouts('sc', 'blogger')), 'trx_sc_blogger' ),
							"type" => "dropdown"
						),
*/
						// Attention! It's our custom param's type and it need values list as normal associative array 'key' => 'value'
						// not in VC-style 'value' => 'key'
						// 'style' => 'icons' | 'images'
						// 'mode' => 'inline' | 'dropdown'
						// 'return' => 'slug' | 'full'
						array(
							"param_name" => "type",
							"heading" => esc_html__("Layout", 'trx_addons'),
							"description" => wp_kses_data( __("Select shortcode's layout", 'trx_addons') ),
							"admin_label" => true,
					        'save_always' => true,
							"std" => "classic",
							"value" => apply_filters('trx_addons_sc_type', trx_addons_components_get_allowed_layouts('sc', 'blogger'), 'trx_sc_blogger' ),
							"mode" => 'inline',
							"return" => 'slug',
							"style" => "images",
							"type" => "icons"
						),
						array(
							"param_name" => "hide_excerpt",
							"heading" => esc_html__("Excerpt", 'trx_addons'),
							"description" => wp_kses_data( __("Check if you want hide excerpt", 'trx_addons') ),
							'dependency' => array(
								'element' => 'type',
								'value' => array('classic')
							),
							"std" => "0",
							"value" => array(esc_html__("Hide excerpt", 'trx_addons') => "1" ),
							"type" => "checkbox"
						),
                        array(
                            "param_name" => "hide_meta",
                            "heading" => esc_html__("Meta", 'trx_addons'),
                            "description" => wp_kses_data( __("Check if you want hide meta", 'trx_addons') ),
                            'dependency' => array(
                                'element' => 'type',
                                'value' => array('classic')
                            ),
                            "std" => "0",
                            "value" => array(esc_html__("Hide meta", 'trx_addons') => "1" ),
                            "type" => "checkbox"
                        ),
						array(
							"param_name" => "post_type",
							"heading" => esc_html__("Post type", 'trx_addons'),
							"description" => wp_kses_data( __("Select post type to show posts", 'trx_addons') ),
							'edit_field_class' => 'vc_col-sm-4',
							"std" => 'post',
							"value" => array_flip(trx_addons_get_list_posts_types()),
							"type" => "dropdown"
						),
						array(
							"param_name" => "taxonomy",
							"heading" => esc_html__("Taxonomy", 'trx_addons'),
							"description" => wp_kses_data( __("Select taxonomy to show posts", 'trx_addons') ),
							'edit_field_class' => 'vc_col-sm-4',
							"std" => 'category',
							"value" => array_flip($taxonomies),
							"type" => "dropdown"
						),
						array(
							"param_name" => "cat",
							"heading" => esc_html__("Category", 'trx_addons'),
							"description" => wp_kses_data( __("Select category to show posts", 'trx_addons') ),
							'edit_field_class' => 'vc_col-sm-4',
							"std" => 0,
							"value" => array_flip(trx_addons_array_merge(array(0=>sprintf(__('- %s -', 'trx_addons'), $tax_obj->label)),
																		 $taxonomy == 'category' 
																			? trx_addons_get_list_categories() 
																			: trx_addons_get_list_terms(false, $taxonomy)
																		)),
							"type" => "dropdown"
						)
					),
					trx_addons_vc_add_query_param(''),
					trx_addons_vc_add_slider_param(),
					trx_addons_vc_add_title_param(),
					trx_addons_vc_add_id_param()
				)
			), 'trx_sc_blogger' );
	}
}

// Elementor Widget
//------------------------------------------------------
if (!function_exists('trx_addons_sc_blogger_add_in_elementor')) {
    add_action( 'elementor/widgets/widgets_registered', 'trx_addons_sc_blogger_add_in_elementor' );
    function trx_addons_sc_blogger_add_in_elementor() {
        class TRX_Addons_Elementor_Widget_Blogger extends TRX_Addons_Elementor_Widget {

            /**
             * Retrieve widget name.
             *
             * @since 1.6.41
             * @access public
             *
             * @return string Widget name.
             */
            public function get_name() {
                return 'trx_sc_blogger';
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
                return __( 'Blogger', 'trx_addons' );
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
                return 'eicon-image-box';
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
                // If open params in Elementor Editor
                $params = $this->get_sc_params();
                // Prepare lists
                $post_type = !empty($params['post_type']) ? $params['post_type'] : 'post';
                $taxonomy = !empty($params['taxonomy']) ? $params['taxonomy'] : 'category';
                $tax_obj = get_taxonomy($taxonomy);

                $this->start_controls_section(
                    'section_sc_blogger',
                    [
                        'label' => __( 'Blogger', 'trx_addons' ),
                    ]
                );

                $this->add_control(
                    'type',
                    [
                        'label' => __( 'Layout', 'trx_addons' ),
                        'label_block' => true,
                        'show_label' => false,
                        'type' => \Elementor\Controls_Manager::SELECT,
                        'options' => array(
                            'default' => esc_html__('Default', 'trx_addons'),
                            'plain' => esc_html__('Plain', 'trx_addons'),
                            'classic' => esc_html__('Classic', 'trx_addons'),
                        ),
                        'default' => 'classic',
                        "mode" => 'inline',
                        "return" => 'slug',
                        "style" => "images",
                    ]
                );

                $this->add_control(
                    'hide_excerpt',
                    [
                        'label' => __( 'Hide excerpt', 'trx_addons' ),
                        'label_block' => false,
                        'type' => \Elementor\Controls_Manager::SWITCHER,
                        'label_off' => __( 'Off', 'trx_addons' ),
                        'label_on' => __( 'On', 'trx_addons' ),
                        'return_value' => '1',
                        'condition' => [
                            'type' => 'classic'
                        ]
                    ]
                );

                $this->add_control(
                    'hide_meta',
                    [
                        'label' => __( 'Hide meta', 'trx_addons' ),
                        'label_block' => false,
                        'type' => \Elementor\Controls_Manager::SWITCHER,
                        'label_off' => __( 'Off', 'trx_addons' ),
                        'label_on' => __( 'On', 'trx_addons' ),
                        'return_value' => '1',
                        'condition' => [
                            'type' => 'classic'
                        ]
                    ]
                );

                $this->add_control(
                    'no_links',
                    [
                        'label' => __( 'Disable links', 'trx_addons' ),
                        'label_block' => false,
                        'type' => \Elementor\Controls_Manager::SWITCHER,
                        'label_off' => __( 'Off', 'trx_addons' ),
                        'label_on' => __( 'On', 'trx_addons' ),
                        'return_value' => '1'
                    ]
                );

                $this->add_control(
                    'more_text',
                    [
                        'label' => __( "'More' text", 'trx_addons' ),
                        'label_block' => false,
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'default' => esc_html__('Read more', 'trx_addons')
                    ]
                );

                $this->add_control(
                    'post_type',
                    [
                        'label' => __( 'Post type', 'trx_addons' ),
                        'label_block' => false,
                        'type' => \Elementor\Controls_Manager::SELECT,
                        'options' => trx_addons_get_list_posts_types(),
                        'default' => 'post'
                    ]
                );

                $this->add_control(
                    'taxonomy',
                    [
                        'label' => __( 'Taxonomy', 'trx_addons' ),
                        'label_block' => false,
                        'type' => \Elementor\Controls_Manager::SELECT,
                        'options' => trx_addons_get_list_taxonomies(false, $post_type),
                        'default' => 'category'
                    ]
                );

                $this->add_control(
                    'cat',
                    [
                        'label' => __( 'Category', 'trx_addons' ),
                        'label_block' => false,
                        'type' => \Elementor\Controls_Manager::SELECT,
                        'options' => trx_addons_array_merge(array(0=>sprintf(__('- %s -', 'trx_addons'), $tax_obj->label)),
                            $taxonomy == 'category'
                                ? trx_addons_get_list_categories()
                                : trx_addons_get_list_terms(false, $taxonomy)
                        ),
                        'default' => '0'
                    ]
                );

                $this->add_query_param('');

                $this->end_controls_section();

                $this->add_slider_param();
                $this->add_title_param();
            }
        }

        // Register widget
        \Elementor\Plugin::$instance->widgets_manager->register_widget_type( new TRX_Addons_Elementor_Widget_Blogger() );
    }
}

?>
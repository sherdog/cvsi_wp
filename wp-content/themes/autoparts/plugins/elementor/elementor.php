<?php
/* Elementor Builder support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('autoparts_elm_theme_setup9')) {
	add_action( 'after_setup_theme', 'autoparts_elm_theme_setup9', 9 );
	function autoparts_elm_theme_setup9() {

        add_filter( 'autoparts_filter_merge_styles',					'autoparts_elm_merge_styles' );
        add_filter( 'autoparts_filter_merge_styles', 		'autoparts_elm_merge_styles_responsive');

		if (autoparts_exists_elementor()) {
			add_action( 'wp_enqueue_scripts', 						'autoparts_elm_frontend_scripts', 1100 );
			add_action( 'init',										'autoparts_elm_init_once', 3 );
            add_action( 'elementor/element/before_section_end', 'autoparts_elm_add_color_scheme_control', 10, 3 );
            add_filter( 'elementor/element/before_section_end', 'autoparts_trx_addons_sc_param_group_params', 10, 3 );
		}
		if (is_admin()) {
			add_filter( 'autoparts_filter_tgmpa_required_plugins',	'autoparts_elm_tgmpa_required_plugins' );
		}
    }
}

// Filter to add in the required plugins list
if ( !function_exists( 'autoparts_elm_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('autoparts_filter_tgmpa_required_plugins',	'autoparts_elm_tgmpa_required_plugins');
	function autoparts_elm_tgmpa_required_plugins($list=array()) {
		if ( in_array('elementor', autoparts_storage_get('required_plugins') ) ) {
			$list[] = array(
                'name' 		=> esc_html__('Elementor', 'autoparts'),
                'slug' 		=> 'elementor',
				'required' 	=> false
			);
		}
		return $list;
	}
}

// Check if Elementor is installed and activated
if ( !function_exists( 'autoparts_exists_elementor' ) ) {
	function autoparts_exists_elementor() {
        return class_exists('Elementor\Plugin');
	}
}

// Merge custom styles
if ( !function_exists( 'autoparts_elm_merge_styles' ) ) {
	function autoparts_elm_merge_styles($list) {
		if (autoparts_exists_elementor()) {
            $list[] = 'plugins/elementor/elementor.css';
		}
		return $list;
	}
}

// Merge responsive styles
if ( !function_exists( 'autoparts_elm_merge_styles_responsive' ) ) {
	function autoparts_elm_merge_styles_responsive($list) {
		if (autoparts_exists_elementor()) {
			$list[] = 'plugins/elementor/elementor-responsive.css';
		}
		return $list;
	}
}

// Enqueue Elementor's support script
if ( !function_exists( 'autoparts_elm_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'autoparts_elm_frontend_scripts', 1100 );
	function autoparts_elm_frontend_scripts() {
        if (autoparts_is_on(autoparts_get_theme_option('debug_mode')) && autoparts_get_file_dir('plugins/elementor/elementor.css')!='') {
            wp_enqueue_style( 'autoparts-elementor',  autoparts_get_file_url('plugins/elementor/elementor.css'), array(), null );
            wp_enqueue_style( 'autoparts-elementor-responsive',  autoparts_get_file_url('plugins/elementor/elementor-responsive.css'), array(), null );
        }
	}
}

// Load required styles and scripts for Elementor Editor mode
if ( !function_exists( 'autoparts_elm_editor_load_scripts' ) ) {
    add_action("elementor/editor/before_enqueue_scripts", 'autoparts_elm_editor_load_scripts');
    function autoparts_elm_editor_load_scripts() {
        // Load font icons
        wp_enqueue_style(  'autoparts-icons', autoparts_get_file_url('css/fontello/css/fontello-embedded.css'), array(), null );
    }
}

// Set Elementor's options at once
if (!function_exists('autoparts_elm_init_once')) {
	//Handler of the add_action( 'init', 'autoparts_elm_init_once', 3 );
	function autoparts_elm_init_once() {
		if (autoparts_exists_elementor() && !get_option('autoparts_setup_elementor_options', false)) {
			// Set theme-specific values to the Elementor's options
			update_option('elementor_disable_color_schemes', 'yes');
			update_option('elementor_disable_typography_schemes', 'yes');
			update_option('elementor_container_width', 1200);
			update_option('elementor_space_between_widgets', 0);
			update_option('elementor_stretched_section_container', '.body_wrap');
			update_option('elementor_page_title_selector', '.elementor-widget-trx_sc_layouts_title,.elementor-widget-trx_sc_layouts_featured');
			// Set flag to prevent change Elementor's options again
			update_option('autoparts_setup_elementor_options', 1);
		}
	}
}


// Add plugin-specific colors and fonts to the custom CSS
if (autoparts_exists_elementor()) { require_once AUTOPARTS_THEME_DIR . 'plugins/elementor/elementor-styles.php'; }


// Add theme-specific controls to sections and columns
if ( ! function_exists( 'autoparts_elm_add_color_scheme_control' ) ) {
    //Handler of the add_action( 'elementor/element/before_section_end', 'autoparts_elm_add_color_scheme_control', 10, 3 );
    function autoparts_elm_add_color_scheme_control( $element, $section_id, $args ) {
        if ( is_object( $element ) ) {
            $el_name = $element->get_name();
            // Add color scheme selector
            if ( apply_filters(
                'autoparts_filter_add_scheme_in_elements',
                ( in_array( $el_name, array( 'section', 'column' ) ) && 'section_advanced' === $section_id )
                || ( 'common' === $el_name && '_section_style' === $section_id ),
                $element, $section_id, $args
            ) ) {
                $element->add_control(
                    'scheme_heading',
                    array(
                        'label' => esc_html__( 'Theme-specific params', 'autoparts' ),
                        'type' => \Elementor\Controls_Manager::HEADING,
                        'separator' => 'before',
                    )
                );
                $element->add_control(
                    'scheme', array(
                        'type'         => \Elementor\Controls_Manager::SELECT,
                        'label'        => esc_html__( 'Color scheme', 'autoparts' ),
                        'label_block'  => false,
                        'options'      => autoparts_array_merge( array( '' => esc_html__( 'Inherit', 'autoparts' ) ), autoparts_get_list_schemes() ),
                        'render_type'  => 'template',	// ( none | ui | template ) - reload template after parameter is changed
                        'default'      => '',
                        'prefix_class' => 'scheme_',
                    )
                );
            }
            // Add 'Color style'
            if ( in_array($el_name, array(
                    'trx_sc_blogger',
                    'trx_sc_icons',
                    'trx_sc_socials',
                    'trx_sc_team',
                    'trx_sc_testimonials',
                    'trx_sc_title',
                    'trx_sc_button'))
                && in_array( $section_id, array( 'section_sc_button', 'section_sc_promo', 'section_sc_title', 'section_title_params' ) )
            ) {
                $element->add_control(
                    'color_style', array(
                        'type'         => \Elementor\Controls_Manager::SELECT,
                        'label'        => esc_html__( 'Color style', 'autoparts' ),
                        'label_block'  => false,
                        'options'      => autoparts_get_list_sc_color_styles(),
                        'default'      => 'default',
                    )
                );
            }
        }
    }
}

// Add param 'color_style' to the shortcode 'Button' in the Elementor
if ( ! function_exists( 'autoparts_trx_addons_sc_param_group_params' ) ) {
    // Handler of add_filter( 'trx_addons_sc_param_group_params', 'autoparts_trx_addons_sc_param_group_params', 10, 2 );
    // Handler of add_filter( 'elementor/element/before_section_end', 'autoparts_trx_addons_sc_param_group_params', 10, 3 );
    function autoparts_trx_addons_sc_param_group_params( $element, $section_id, $args ) {

        if ( ! is_object($element) ) return;
        $el_name = $element->get_name();

        if ( in_array($el_name, array('trx_sc_title')) ) {
            $element->add_control( 'counter', array(
                'label_block'  => false,
                'type' => \Elementor\Controls_Manager::TEXT,
                'label' => esc_html__( 'Show short text as background', 'autoparts' ),
            ) );
        }
    }
}

?>
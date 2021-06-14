<?php
/* ThemeREX Addons support functions
------------------------------------------------------------------------------- */

// Add theme-specific functions
require_once AUTOPARTS_THEME_DIR . 'theme-specific/trx_addons.setup.php';

// Theme init priorities:
// 1 - register filters, that add/remove lists items for the Theme Options
if (!function_exists('autoparts_trx_addons_theme_setup1')) {
	add_action( 'after_setup_theme', 'autoparts_trx_addons_theme_setup1', 1 );
	add_action( 'trx_addons_action_save_options', 'autoparts_trx_addons_theme_setup1', 8 );
	function autoparts_trx_addons_theme_setup1() {
		if (autoparts_exists_trx_addons()) {
			add_filter( 'autoparts_filter_list_posts_types',	'autoparts_trx_addons_list_post_types');
			add_filter( 'autoparts_filter_list_header_styles','autoparts_trx_addons_list_header_styles');
			add_filter( 'autoparts_filter_list_footer_styles','autoparts_trx_addons_list_footer_styles');
			add_filter( 'trx_addons_filter_default_layouts','autoparts_trx_addons_default_layouts');
			add_filter( 'trx_addons_filter_load_options',	'autoparts_trx_addons_default_components');
		}
	}
}

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('autoparts_trx_addons_theme_setup9')) {
	add_action( 'after_setup_theme', 'autoparts_trx_addons_theme_setup9', 9 );
	function autoparts_trx_addons_theme_setup9() {
		if (autoparts_exists_trx_addons()) {
			add_filter( 'trx_addons_filter_featured_image',				'autoparts_trx_addons_featured_image', 10, 2);
			add_filter( 'trx_addons_filter_no_image',					'autoparts_trx_addons_no_image' );
			add_filter( 'trx_addons_filter_get_list_icons',				'autoparts_trx_addons_get_list_icons', 10, 2 );
			add_action( 'wp_enqueue_scripts', 							'autoparts_trx_addons_frontend_scripts', 1100 );
			add_filter( 'autoparts_filter_query_sort_order',	 			'autoparts_trx_addons_query_sort_order', 10, 3);
			add_filter( 'autoparts_filter_merge_scripts',					'autoparts_trx_addons_merge_scripts');
			add_filter( 'autoparts_filter_prepare_css',					'autoparts_trx_addons_prepare_css', 10, 2);
			add_filter( 'autoparts_filter_prepare_js',					'autoparts_trx_addons_prepare_js', 10, 2);
			add_filter( 'autoparts_filter_localize_script',				'autoparts_trx_addons_localize_script');
			add_filter( 'autoparts_filter_get_post_categories',		 	'autoparts_trx_addons_get_post_categories');
			add_filter( 'autoparts_filter_get_post_date',		 			'autoparts_trx_addons_get_post_date');
			add_filter( 'trx_addons_filter_get_post_date',		 		'autoparts_trx_addons_get_post_date_wrap');
			add_filter( 'autoparts_filter_post_type_taxonomy',			'autoparts_trx_addons_post_type_taxonomy', 10, 2 );
			if (is_admin()) {
				add_filter( 'autoparts_filter_allow_override_options', 			'autoparts_trx_addons_allow_override_options', 10, 2);
				add_filter( 'autoparts_filter_allow_theme_icons', 		'autoparts_trx_addons_allow_theme_icons', 10, 2);
			} else {
				add_filter( 'trx_addons_filter_theme_logo',				'autoparts_trx_addons_theme_logo');
				add_filter( 'trx_addons_filter_post_meta',				'autoparts_trx_addons_post_meta', 10, 2);
				add_filter( 'trx_addons_filter_args_related',			'autoparts_trx_addons_args_related');
				add_filter( 'autoparts_filter_get_mobile_menu',			'autoparts_trx_addons_get_mobile_menu');
				add_filter( 'autoparts_filter_detect_blog_mode',			'autoparts_trx_addons_detect_blog_mode' );
				add_filter( 'autoparts_filter_get_blog_title', 			'autoparts_trx_addons_get_blog_title');
				add_action( 'autoparts_action_login',						'autoparts_trx_addons_action_login', 10, 2);
				add_action( 'autoparts_action_search',					'autoparts_trx_addons_action_search', 10, 3);
				add_action( 'autoparts_action_breadcrumbs',				'autoparts_trx_addons_action_breadcrumbs');
				add_action( 'autoparts_action_show_layout',				'autoparts_trx_addons_action_show_layout', 10, 1);
				add_action( 'autoparts_action_user_meta',					'autoparts_trx_addons_action_user_meta');
			}
		}
		
		// Add this filter any time: if plugin exists - load plugin's styles, if not exists - load layouts.css instead plugin's styles
		add_filter( 'autoparts_filter_merge_styles',						'autoparts_trx_addons_merge_styles');
		
		if (is_admin()) {
			add_filter( 'autoparts_filter_tgmpa_required_plugins',		'autoparts_trx_addons_tgmpa_required_plugins' );
			add_action( 'admin_enqueue_scripts', 						'autoparts_trx_addons_editor_load_scripts_admin');
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'autoparts_trx_addons_tgmpa_required_plugins' ) ) {
	
	function autoparts_trx_addons_tgmpa_required_plugins($list=array()) {
		if (in_array('trx_addons', autoparts_storage_get('required_plugins'))) {
			$path = autoparts_get_file_dir('plugins/trx_addons/trx_addons.zip');
			$list[] = array(
					'name' 		=> esc_html__('ThemeREX Addons', 'autoparts'),
					'slug' 		=> 'trx_addons',
					'version'	=> '28.6.29.6',
					'source'	=> !empty($path) ? $path : 'upload://trx_addons.zip',
					'required' 	=> true
				);
		}
		return $list;
	}
}

// Check if theme is activated
if (!function_exists('autoparts_trx_addons_is_theme_activated')) {
	function autoparts_trx_addons_is_theme_activated() {
		return function_exists('trx_addons_is_theme_activated')
			? trx_addons_is_theme_activated()
			: false;
	}
}



/* Add options in the Theme Options Customizer
------------------------------------------------------------------------------- */

// Theme init priorities:
// 3 - add/remove Theme Options elements
if (!function_exists('autoparts_trx_addons_setup3')) {
	add_action( 'after_setup_theme', 'autoparts_trx_addons_setup3', 3 );
	function autoparts_trx_addons_setup3() {
		
		// Section 'Cars' - settings to show 'Cars' blog archive and single posts
		if (autoparts_exists_cars()) {
		
			autoparts_storage_merge_array('options', '', array(
				'cars' => array(
					"title" => esc_html__('Cars', 'autoparts'),
					"desc" => wp_kses_data( __('Select parameters to display the cars pages', 'autoparts') ),
					"type" => "section"
					),
				'expand_content_cars' => array(
					"title" => esc_html__('Expand content', 'autoparts'),
					"desc" => wp_kses_data( __('Expand the content width if the sidebar is hidden', 'autoparts') ),
					"refresh" => false,
					"std" => 1,
					"type" => "checkbox"
					),
				'related_posts_cars' => array(
					"title" => esc_html__('Related cars', 'autoparts'),
					"desc" => wp_kses_data( __('How many related cars should be displayed in the single car page?', 'autoparts') ),
					"std" => 3,
					"options" => autoparts_get_list_range(0,9),
					"type" => "select"
					),
				'related_columns_cars' => array(
					"title" => esc_html__('Related columns', 'autoparts'),
					"desc" => wp_kses_data( __('How many columns should be used to output related cars in the single car page?', 'autoparts') ),
					"std" => 3,
					"options" => autoparts_get_list_range(1,4),
					"type" => "select"
					),
				'header_style_cars' => array(
					"title" => esc_html__('Header style', 'autoparts'),
					"desc" => wp_kses_data( __('Select style to display the site header on the cars pages', 'autoparts') ),
					"std" => 'inherit',
					"options" => array(),
					"type" => "select"
					),
				'header_position_cars' => array(
					"title" => esc_html__('Header position', 'autoparts'),
					"desc" => wp_kses_data( __('Select position to display the site header on the cars pages', 'autoparts') ),
					"std" => 'inherit',
					"options" => array(),
					"type" => "select"
					),
				'header_widgets_cars' => array(
					"title" => esc_html__('Header widgets', 'autoparts'),
					"desc" => wp_kses_data( __('Select set of widgets to show in the header on the cars pages', 'autoparts') ),
					"std" => 'hide',
					"options" => array(),
					"type" => "select"
					),
				'sidebar_widgets_cars' => array(
					"title" => esc_html__('Sidebar widgets', 'autoparts'),
					"desc" => wp_kses_data( __('Select sidebar to show on the cars pages', 'autoparts') ),
					"std" => 'hide',
					"options" => array(),
					"type" => "select"
					),
				'sidebar_position_cars' => array(
					"title" => esc_html__('Sidebar position', 'autoparts'),
					"desc" => wp_kses_data( __('Select position to show sidebar on the cars pages', 'autoparts') ),
					"refresh" => false,
					"std" => 'left',
					"options" => array(),
					"type" => "select"
					),
				'hide_sidebar_on_single_cars' => array(
					"title" => esc_html__('Hide sidebar on the single pages', 'autoparts'),
					"desc" => wp_kses_data( __("Hide sidebar on the single property or agent pages", 'autoparts') ),
					"std" => 0,
					"type" => "checkbox"
					),
				'widgets_above_page_cars' => array(
					"title" => esc_html__('Widgets at the top of the page', 'autoparts'),
					"desc" => wp_kses_data( __('Select widgets to show at the top of the page (above content and sidebar)', 'autoparts') ),
					"std" => 'hide',
					"options" => array(),
					"type" => "select"
					),
				'widgets_above_content_cars' => array(
					"title" => esc_html__('Widgets above the content', 'autoparts'),
					"desc" => wp_kses_data( __('Select widgets to show at the beginning of the content area', 'autoparts') ),
					"std" => 'hide',
					"options" => array(),
					"type" => "select"
					),
				'widgets_below_content_cars' => array(
					"title" => esc_html__('Widgets below the content', 'autoparts'),
					"desc" => wp_kses_data( __('Select widgets to show at the ending of the content area', 'autoparts') ),
					"std" => 'hide',
					"options" => array(),
					"type" => "select"
					),
				'widgets_below_page_cars' => array(
					"title" => esc_html__('Widgets at the bottom of the page', 'autoparts'),
					"desc" => wp_kses_data( __('Select widgets to show at the bottom of the page (below content and sidebar)', 'autoparts') ),
					"std" => 'hide',
					"options" => array(),
					"type" => "select"
					),
				'footer_scheme_cars' => array(
					"title" => esc_html__('Footer Color Scheme', 'autoparts'),
					"desc" => wp_kses_data( __('Select color scheme to decorate footer area', 'autoparts') ),
					"std" => 'dark',
					"options" => array(),
					"type" => "select"
					),
				'footer_widgets_cars' => array(
					"title" => esc_html__('Footer widgets', 'autoparts'),
					"desc" => wp_kses_data( __('Select set of widgets to show in the footer', 'autoparts') ),
					"std" => 'footer_widgets',
					"options" => array(),
					"type" => "select"
					),
				'footer_columns_cars' => array(
					"title" => esc_html__('Footer columns', 'autoparts'),
					"desc" => wp_kses_data( __('Select number columns to show widgets in the footer. If 0 - autodetect by the widgets count', 'autoparts') ),
					"dependency" => array(
						'footer_widgets_cars' => array('^hide')
					),
					"std" => 0,
					"options" => autoparts_get_list_range(0,6),
					"type" => "select"
					),
				'footer_wide_cars' => array(
					"title" => esc_html__('Footer fullwide', 'autoparts'),
					"desc" => wp_kses_data( __('Do you want to stretch the footer to the entire window width?', 'autoparts') ),
					"std" => 0,
					"type" => "checkbox"
					)
				)
			);
		}
		
		// Section 'Courses' - settings to show 'Courses' blog archive and single posts
		if (autoparts_exists_courses()) {
		
			autoparts_storage_merge_array('options', '', array(
				'courses' => array(
					"title" => esc_html__('Courses', 'autoparts'),
					"desc" => wp_kses_data( __('Select parameters to display the courses pages', 'autoparts') ),
					"type" => "section"
					),
				'expand_content_courses' => array(
					"title" => esc_html__('Expand content', 'autoparts'),
					"desc" => wp_kses_data( __('Expand the content width if the sidebar is hidden', 'autoparts') ),
					"refresh" => false,
					"std" => 1,
					"type" => "checkbox"
					),
				'related_posts_courses' => array(
					"title" => esc_html__('Related courses', 'autoparts'),
					"desc" => wp_kses_data( __('How many related courses should be displayed in the single course page?', 'autoparts') ),
					"std" => 3,
					"options" => autoparts_get_list_range(0,9),
					"type" => "select"
					),
				'related_columns_courses' => array(
					"title" => esc_html__('Related columns', 'autoparts'),
					"desc" => wp_kses_data( __('How many columns should be used to output related courses in the single course page?', 'autoparts') ),
					"std" => 3,
					"options" => autoparts_get_list_range(1,4),
					"type" => "select"
					),
				'header_style_courses' => array(
					"title" => esc_html__('Header style', 'autoparts'),
					"desc" => wp_kses_data( __('Select style to display the site header on the courses pages', 'autoparts') ),
					"std" => 'inherit',
					"options" => array(),
					"type" => "select"
					),
				'header_position_courses' => array(
					"title" => esc_html__('Header position', 'autoparts'),
					"desc" => wp_kses_data( __('Select position to display the site header on the courses pages', 'autoparts') ),
					"std" => 'inherit',
					"options" => array(),
					"type" => "select"
					),
				'header_widgets_courses' => array(
					"title" => esc_html__('Header widgets', 'autoparts'),
					"desc" => wp_kses_data( __('Select set of widgets to show in the header on the courses pages', 'autoparts') ),
					"std" => 'hide',
					"options" => array(),
					"type" => "select"
					),
				'sidebar_widgets_courses' => array(
					"title" => esc_html__('Sidebar widgets', 'autoparts'),
					"desc" => wp_kses_data( __('Select sidebar to show on the courses pages', 'autoparts') ),
					"std" => 'hide',
					"options" => array(),
					"type" => "select"
					),
				'sidebar_position_courses' => array(
					"title" => esc_html__('Sidebar position', 'autoparts'),
					"desc" => wp_kses_data( __('Select position to show sidebar on the courses pages', 'autoparts') ),
					"refresh" => false,
					"std" => 'left',
					"options" => array(),
					"type" => "select"
					),
				'hide_sidebar_on_single_courses' => array(
					"title" => esc_html__('Hide sidebar on the single course', 'autoparts'),
					"desc" => wp_kses_data( __("Hide sidebar on the single course's page", 'autoparts') ),
					"std" => 0,
					"type" => "checkbox"
					),
				'widgets_above_page_courses' => array(
					"title" => esc_html__('Widgets above the page', 'autoparts'),
					"desc" => wp_kses_data( __('Select widgets to show above page (content and sidebar)', 'autoparts') ),
					"std" => 'hide',
					"options" => array(),
					"type" => "select"
					),
				'widgets_above_content_courses' => array(
					"title" => esc_html__('Widgets above the content', 'autoparts'),
					"desc" => wp_kses_data( __('Select widgets to show at the beginning of the content area', 'autoparts') ),
					"std" => 'hide',
					"options" => array(),
					"type" => "select"
					),
				'widgets_below_content_courses' => array(
					"title" => esc_html__('Widgets below the content', 'autoparts'),
					"desc" => wp_kses_data( __('Select widgets to show at the ending of the content area', 'autoparts') ),
					"std" => 'hide',
					"options" => array(),
					"type" => "select"
					),
				'widgets_below_page_courses' => array(
					"title" => esc_html__('Widgets below the page', 'autoparts'),
					"desc" => wp_kses_data( __('Select widgets to show below the page (content and sidebar)', 'autoparts') ),
					"std" => 'hide',
					"options" => array(),
					"type" => "select"
					),
				'footer_scheme_courses' => array(
					"title" => esc_html__('Footer Color Scheme', 'autoparts'),
					"desc" => wp_kses_data( __('Select color scheme to decorate footer area', 'autoparts') ),
					"std" => 'dark',
					"options" => array(),
					"type" => "select"
					),
				'footer_widgets_courses' => array(
					"title" => esc_html__('Footer widgets', 'autoparts'),
					"desc" => wp_kses_data( __('Select set of widgets to show in the footer', 'autoparts') ),
					"std" => 'footer_widgets',
					"options" => array(),
					"type" => "select"
					),
				'footer_columns_courses' => array(
					"title" => esc_html__('Footer columns', 'autoparts'),
					"desc" => wp_kses_data( __('Select number columns to show widgets in the footer. If 0 - autodetect by the widgets count', 'autoparts') ),
					"dependency" => array(
						'footer_widgets_courses' => array('^hide')
					),
					"std" => 0,
					"options" => autoparts_get_list_range(0,6),
					"type" => "select"
					),
				'footer_wide_courses' => array(
					"title" => esc_html__('Footer fullwide', 'autoparts'),
					"desc" => wp_kses_data( __('Do you want to stretch the footer to the entire window width?', 'autoparts') ),
					"std" => 0,
					"type" => "checkbox"
					)
				)
			);
		}
		
		// Section 'Dishes' - settings to show 'Dishes' blog archive and single posts
		if (autoparts_exists_dishes()) {
		
			autoparts_storage_merge_array('options', '', array(
				'dishes' => array(
					"title" => esc_html__('Dishes', 'autoparts'),
					"desc" => wp_kses_data( __('Select parameters to display the dishes pages', 'autoparts') ),
					"type" => "section"
					),
				'expand_content_dishes' => array(
					"title" => esc_html__('Expand content', 'autoparts'),
					"desc" => wp_kses_data( __('Expand the content width if the sidebar is hidden', 'autoparts') ),
					"refresh" => false,
					"std" => 1,
					"type" => "checkbox"
					),
				'related_posts_dishes' => array(
					"title" => esc_html__('Related dishes', 'autoparts'),
					"desc" => wp_kses_data( __('How many related dishes should be displayed in the single course page?', 'autoparts') ),
					"std" => 3,
					"options" => autoparts_get_list_range(0,9),
					"type" => "select"
					),
				'related_columns_dishes' => array(
					"title" => esc_html__('Related columns', 'autoparts'),
					"desc" => wp_kses_data( __('How many columns should be used to output related dishes in the single course page?', 'autoparts') ),
					"std" => 3,
					"options" => autoparts_get_list_range(1,4),
					"type" => "select"
					),
				'header_style_dishes' => array(
					"title" => esc_html__('Header style', 'autoparts'),
					"desc" => wp_kses_data( __('Select style to display the site header on the dishes pages', 'autoparts') ),
					"std" => 'inherit',
					"options" => array(),
					"type" => "select"
					),
				'header_position_dishes' => array(
					"title" => esc_html__('Header position', 'autoparts'),
					"desc" => wp_kses_data( __('Select position to display the site header on the dishes pages', 'autoparts') ),
					"std" => 'inherit',
					"options" => array(),
					"type" => "select"
					),
				'header_widgets_dishes' => array(
					"title" => esc_html__('Header widgets', 'autoparts'),
					"desc" => wp_kses_data( __('Select set of widgets to show in the header on the dishes pages', 'autoparts') ),
					"std" => 'hide',
					"options" => array(),
					"type" => "select"
					),
				'sidebar_widgets_dishes' => array(
					"title" => esc_html__('Sidebar widgets', 'autoparts'),
					"desc" => wp_kses_data( __('Select sidebar to show on the dishes pages', 'autoparts') ),
					"std" => 'hide',
					"options" => array(),
					"type" => "select"
					),
				'sidebar_position_dishes' => array(
					"title" => esc_html__('Sidebar position', 'autoparts'),
					"desc" => wp_kses_data( __('Select position to show sidebar on the dishes pages', 'autoparts') ),
					"refresh" => false,
					"std" => 'left',
					"options" => array(),
					"type" => "select"
					),
				'hide_sidebar_on_single_dishes' => array(
					"title" => esc_html__('Hide sidebar on the single dish', 'autoparts'),
					"desc" => wp_kses_data( __("Hide sidebar on the single dish's page", 'autoparts') ),
					"std" => 0,
					"type" => "checkbox"
					),
				'widgets_above_page_dishes' => array(
					"title" => esc_html__('Widgets above the page', 'autoparts'),
					"desc" => wp_kses_data( __('Select widgets to show above page (content and sidebar)', 'autoparts') ),
					"std" => 'hide',
					"options" => array(),
					"type" => "select"
					),
				'widgets_above_content_dishes' => array(
					"title" => esc_html__('Widgets above the content', 'autoparts'),
					"desc" => wp_kses_data( __('Select widgets to show at the beginning of the content area', 'autoparts') ),
					"std" => 'hide',
					"options" => array(),
					"type" => "select"
					),
				'widgets_below_content_dishes' => array(
					"title" => esc_html__('Widgets below the content', 'autoparts'),
					"desc" => wp_kses_data( __('Select widgets to show at the ending of the content area', 'autoparts') ),
					"std" => 'hide',
					"options" => array(),
					"type" => "select"
					),
				'widgets_below_page_dishes' => array(
					"title" => esc_html__('Widgets below the page', 'autoparts'),
					"desc" => wp_kses_data( __('Select widgets to show below the page (content and sidebar)', 'autoparts') ),
					"std" => 'hide',
					"options" => array(),
					"type" => "select"
					),
				'footer_scheme_dishes' => array(
					"title" => esc_html__('Footer Color Scheme', 'autoparts'),
					"desc" => wp_kses_data( __('Select color scheme to decorate footer area', 'autoparts') ),
					"std" => 'dark',
					"options" => array(),
					"type" => "select"
					),
				'footer_widgets_dishes' => array(
					"title" => esc_html__('Footer widgets', 'autoparts'),
					"desc" => wp_kses_data( __('Select set of widgets to show in the footer', 'autoparts') ),
					"std" => 'footer_widgets',
					"options" => array(),
					"type" => "select"
					),
				'footer_columns_dishes' => array(
					"title" => esc_html__('Footer columns', 'autoparts'),
					"desc" => wp_kses_data( __('Select number columns to show widgets in the footer. If 0 - autodetect by the widgets count', 'autoparts') ),
					"dependency" => array(
						'footer_widgets_dishes' => array('^hide')
					),
					"std" => 0,
					"options" => autoparts_get_list_range(0,6),
					"type" => "select"
					),
				'footer_wide_dishes' => array(
					"title" => esc_html__('Footer fullwide', 'autoparts'),
					"desc" => wp_kses_data( __('Do you want to stretch the footer to the entire window width?', 'autoparts') ),
					"std" => 0,
					"type" => "checkbox"
					)
				)
			);
		}
		
		// Section 'Properties' - settings to show 'Properties' blog archive and single posts
		if (autoparts_exists_properties()) {
		
			autoparts_storage_merge_array('options', '', array(
				'properties' => array(
					"title" => esc_html__('Properties', 'autoparts'),
					"desc" => wp_kses_data( __('Select parameters to display the properties pages', 'autoparts') ),
					"type" => "section"
					),
				'expand_content_properties' => array(
					"title" => esc_html__('Expand content', 'autoparts'),
					"desc" => wp_kses_data( __('Expand the content width if the sidebar is hidden', 'autoparts') ),
					"refresh" => false,
					"std" => 1,
					"type" => "checkbox"
					),
				'related_posts_properties' => array(
					"title" => esc_html__('Related properties', 'autoparts'),
					"desc" => wp_kses_data( __('How many related properties should be displayed in the single property page?', 'autoparts') ),
					"std" => 3,
					"options" => autoparts_get_list_range(0,9),
					"type" => "select"
					),
				'related_columns_properties' => array(
					"title" => esc_html__('Related columns', 'autoparts'),
					"desc" => wp_kses_data( __('How many columns should be used to output related properties in the single property page?', 'autoparts') ),
					"std" => 3,
					"options" => autoparts_get_list_range(1,4),
					"type" => "select"
					),
				'header_style_properties' => array(
					"title" => esc_html__('Header style', 'autoparts'),
					"desc" => wp_kses_data( __('Select style to display the site header on the properties pages', 'autoparts') ),
					"std" => 'inherit',
					"options" => array(),
					"type" => "select"
					),
				'header_position_properties' => array(
					"title" => esc_html__('Header position', 'autoparts'),
					"desc" => wp_kses_data( __('Select position to display the site header on the properties pages', 'autoparts') ),
					"std" => 'inherit',
					"options" => array(),
					"type" => "select"
					),
				'header_widgets_properties' => array(
					"title" => esc_html__('Header widgets', 'autoparts'),
					"desc" => wp_kses_data( __('Select set of widgets to show in the header on the properties pages', 'autoparts') ),
					"std" => 'hide',
					"options" => array(),
					"type" => "select"
					),
				'sidebar_widgets_properties' => array(
					"title" => esc_html__('Sidebar widgets', 'autoparts'),
					"desc" => wp_kses_data( __('Select sidebar to show on the properties pages', 'autoparts') ),
					"std" => 'hide',
					"options" => array(),
					"type" => "select"
					),
				'sidebar_position_properties' => array(
					"title" => esc_html__('Sidebar position', 'autoparts'),
					"desc" => wp_kses_data( __('Select position to show sidebar on the properties pages', 'autoparts') ),
					"refresh" => false,
					"std" => 'left',
					"options" => array(),
					"type" => "select"
					),
				'hide_sidebar_on_single_properties' => array(
					"title" => esc_html__('Hide sidebar on the single pages', 'autoparts'),
					"desc" => wp_kses_data( __("Hide sidebar on the single property or agent pages", 'autoparts') ),
					"std" => 0,
					"type" => "checkbox"
					),
				'widgets_above_page_properties' => array(
					"title" => esc_html__('Widgets above the page', 'autoparts'),
					"desc" => wp_kses_data( __('Select widgets to show above page (content and sidebar)', 'autoparts') ),
					"std" => 'hide',
					"options" => array(),
					"type" => "select"
					),
				'widgets_above_content_properties' => array(
					"title" => esc_html__('Widgets above the content', 'autoparts'),
					"desc" => wp_kses_data( __('Select widgets to show at the beginning of the content area', 'autoparts') ),
					"std" => 'hide',
					"options" => array(),
					"type" => "select"
					),
				'widgets_below_content_properties' => array(
					"title" => esc_html__('Widgets below the content', 'autoparts'),
					"desc" => wp_kses_data( __('Select widgets to show at the ending of the content area', 'autoparts') ),
					"std" => 'hide',
					"options" => array(),
					"type" => "select"
					),
				'widgets_below_page_properties' => array(
					"title" => esc_html__('Widgets below the page', 'autoparts'),
					"desc" => wp_kses_data( __('Select widgets to show below the page (content and sidebar)', 'autoparts') ),
					"std" => 'hide',
					"options" => array(),
					"type" => "select"
					),
				'footer_scheme_properties' => array(
					"title" => esc_html__('Footer Color Scheme', 'autoparts'),
					"desc" => wp_kses_data( __('Select color scheme to decorate footer area', 'autoparts') ),
					"std" => 'dark',
					"options" => array(),
					"type" => "select"
					),
				'footer_widgets_properties' => array(
					"title" => esc_html__('Footer widgets', 'autoparts'),
					"desc" => wp_kses_data( __('Select set of widgets to show in the footer', 'autoparts') ),
					"std" => 'footer_widgets',
					"options" => array(),
					"type" => "select"
					),
				'footer_columns_properties' => array(
					"title" => esc_html__('Footer columns', 'autoparts'),
					"desc" => wp_kses_data( __('Select number columns to show widgets in the footer. If 0 - autodetect by the widgets count', 'autoparts') ),
					"dependency" => array(
						'footer_widgets_properties' => array('^hide')
					),
					"std" => 0,
					"options" => autoparts_get_list_range(0,6),
					"type" => "select"
					),
				'footer_wide_properties' => array(
					"title" => esc_html__('Footer fullwide', 'autoparts'),
					"desc" => wp_kses_data( __('Do you want to stretch the footer to the entire window width?', 'autoparts') ),
					"std" => 0,
					"type" => "checkbox"
					)
				)
			);
		}
		
		// Section 'Sport' - settings to show 'Sport' blog archive and single posts
		if (autoparts_exists_sport()) {
			autoparts_storage_merge_array('options', '', array(
				'sport' => array(
					"title" => esc_html__('Sport', 'autoparts'),
					"desc" => wp_kses_data( __('Select parameters to display the sport pages', 'autoparts') ),
					"type" => "section"
					),
				'expand_content_sport' => array(
					"title" => esc_html__('Expand content', 'autoparts'),
					"desc" => wp_kses_data( __('Expand the content width if the sidebar is hidden', 'autoparts') ),
					"refresh" => false,
					"std" => 1,
					"type" => "checkbox"
					),
				'header_style_sport' => array(
					"title" => esc_html__('Header style', 'autoparts'),
					"desc" => wp_kses_data( __('Select style to display the site header on the sport pages', 'autoparts') ),
					"std" => 'inherit',
					"options" => array(),
					"type" => "select"
					),
				'header_position_sport' => array(
					"title" => esc_html__('Header position', 'autoparts'),
					"desc" => wp_kses_data( __('Select position to display the site header on the sport pages', 'autoparts') ),
					"std" => 'inherit',
					"options" => array(),
					"type" => "select"
					),
				'header_widgets_sport' => array(
					"title" => esc_html__('Header widgets', 'autoparts'),
					"desc" => wp_kses_data( __('Select set of widgets to show in the header on the sport pages', 'autoparts') ),
					"std" => 'hide',
					"options" => array(),
					"type" => "select"
					),
				'sidebar_widgets_sport' => array(
					"title" => esc_html__('Sidebar widgets', 'autoparts'),
					"desc" => wp_kses_data( __('Select sidebar to show on the sport pages', 'autoparts') ),
					"std" => 'hide',
					"options" => array(),
					"type" => "select"
					),
				'sidebar_position_sport' => array(
					"title" => esc_html__('Sidebar position', 'autoparts'),
					"desc" => wp_kses_data( __('Select position to show sidebar on the sport pages', 'autoparts') ),
					"refresh" => false,
					"std" => 'left',
					"options" => array(),
					"type" => "select"
					),
				'hide_sidebar_on_single_sport' => array(
					"title" => esc_html__('Hide sidebar on the single pages', 'autoparts'),
					"desc" => wp_kses_data( __("Hide sidebar on the single sport pages (competitions, rounds, matches or players)", 'autoparts') ),
					"std" => 0,
					"type" => "checkbox"
					),
				'widgets_above_page_sport' => array(
					"title" => esc_html__('Widgets above the page', 'autoparts'),
					"desc" => wp_kses_data( __('Select widgets to show above page (content and sidebar)', 'autoparts') ),
					"std" => 'hide',
					"options" => array(),
					"type" => "select"
					),
				'widgets_above_content_sport' => array(
					"title" => esc_html__('Widgets above the content', 'autoparts'),
					"desc" => wp_kses_data( __('Select widgets to show at the beginning of the content area', 'autoparts') ),
					"std" => 'hide',
					"options" => array(),
					"type" => "select"
					),
				'widgets_below_content_sport' => array(
					"title" => esc_html__('Widgets below the content', 'autoparts'),
					"desc" => wp_kses_data( __('Select widgets to show at the ending of the content area', 'autoparts') ),
					"std" => 'hide',
					"options" => array(),
					"type" => "select"
					),
				'widgets_below_page_sport' => array(
					"title" => esc_html__('Widgets below the page', 'autoparts'),
					"desc" => wp_kses_data( __('Select widgets to show below the page (content and sidebar)', 'autoparts') ),
					"std" => 'hide',
					"options" => array(),
					"type" => "select"
					),
				'footer_scheme_sport' => array(
					"title" => esc_html__('Footer Color Scheme', 'autoparts'),
					"desc" => wp_kses_data( __('Select color scheme to decorate footer area', 'autoparts') ),
					"std" => 'dark',
					"options" => array(),
					"type" => "select"
					),
				'footer_widgets_sport' => array(
					"title" => esc_html__('Footer widgets', 'autoparts'),
					"desc" => wp_kses_data( __('Select set of widgets to show in the footer', 'autoparts') ),
					"std" => 'footer_widgets',
					"options" => array(),
					"type" => "select"
					),
				'footer_columns_sport' => array(
					"title" => esc_html__('Footer columns', 'autoparts'),
					"desc" => wp_kses_data( __('Select number columns to show widgets in the footer. If 0 - autodetect by the widgets count', 'autoparts') ),
					"dependency" => array(
						'footer_widgets_sport' => array('^hide')
					),
					"std" => 0,
					"options" => autoparts_get_list_range(0,6),
					"type" => "select"
					),
				'footer_wide_sport' => array(
					"title" => esc_html__('Footer fullwide', 'autoparts'),
					"desc" => wp_kses_data( __('Do you want to stretch the footer to the entire window width?', 'autoparts') ),
					"std" => 0,
					"type" => "checkbox"
					)
				)
			);
		}
	}
}


// Setup internal plugin's parameters
if (!function_exists('autoparts_trx_addons_init_settings')) {
	add_filter( 'trx_addons_init_settings', 'autoparts_trx_addons_init_settings');
	function autoparts_trx_addons_init_settings($settings) {
		$settings['socials_type']	= autoparts_get_theme_setting('socials_type');
		$settings['icons_type']		= autoparts_get_theme_setting('icons_type');
		$settings['icons_selector']	= autoparts_get_theme_setting('icons_selector');
		return $settings;
	}
}



/* Plugin's support utilities
------------------------------------------------------------------------------- */

// Check if plugin installed and activated
if ( !function_exists( 'autoparts_exists_trx_addons' ) ) {
	function autoparts_exists_trx_addons() {
		return defined('TRX_ADDONS_VERSION');
	}
}

// Return true if cars is supported
if ( !function_exists( 'autoparts_exists_cars' ) ) {
	function autoparts_exists_cars() {
		return defined('TRX_ADDONS_CPT_CARS_PT');
	}
}

// Return true if courses is supported
if ( !function_exists( 'autoparts_exists_courses' ) ) {
	function autoparts_exists_courses() {
		return defined('TRX_ADDONS_CPT_COURSES_PT');
	}
}

// Return true if dishes is supported
if ( !function_exists( 'autoparts_exists_dishes' ) ) {
	function autoparts_exists_dishes() {
		return defined('TRX_ADDONS_CPT_DISHES_PT');
	}
}

// Return true if layouts is supported
if ( !function_exists( 'autoparts_exists_layouts' ) ) {
	function autoparts_exists_layouts() {
		return defined('TRX_ADDONS_CPT_LAYOUTS_PT');
	}
}

// Return true if portfolio is supported
if ( !function_exists( 'autoparts_exists_portfolio' ) ) {
	function autoparts_exists_portfolio() {
		return defined('TRX_ADDONS_CPT_PORTFOLIO_PT');
	}
}

// Return true if properties is supported
if ( !function_exists( 'autoparts_exists_properties' ) ) {
	function autoparts_exists_properties() {
		return defined('TRX_ADDONS_CPT_PROPERTIES_PT');
	}
}

// Return true if services is supported
if ( !function_exists( 'autoparts_exists_services' ) ) {
	function autoparts_exists_services() {
		return defined('TRX_ADDONS_CPT_SERVICES_PT');
	}
}

// Return true if sport is supported
if ( !function_exists( 'autoparts_exists_sport' ) ) {
	function autoparts_exists_sport() {
		return defined('TRX_ADDONS_CPT_COMPETITIONS_PT');
	}
}

// Return true if team is supported
if ( !function_exists( 'autoparts_exists_team' ) ) {
	function autoparts_exists_team() {
		return defined('TRX_ADDONS_CPT_TEAM_PT');
	}
}


// Return true if it's cars page
if ( !function_exists( 'autoparts_is_cars_page' ) ) {
	function autoparts_is_cars_page() {
		return function_exists('trx_addons_is_cars_page') && trx_addons_is_cars_page();
	}
}

// Return true if it's courses page
if ( !function_exists( 'autoparts_is_courses_page' ) ) {
	function autoparts_is_courses_page() {
		return function_exists('trx_addons_is_courses_page') && trx_addons_is_courses_page();
	}
}

// Return true if it's dishes page
if ( !function_exists( 'autoparts_is_dishes_page' ) ) {
	function autoparts_is_dishes_page() {
		return function_exists('trx_addons_is_dishes_page') && trx_addons_is_dishes_page();
	}
}

// Return true if it's properties page
if ( !function_exists( 'autoparts_is_properties_page' ) ) {
	function autoparts_is_properties_page() {
		return function_exists('trx_addons_is_properties_page') && trx_addons_is_properties_page();
	}
}

// Return true if it's portfolio page
if ( !function_exists( 'autoparts_is_portfolio_page' ) ) {
	function autoparts_is_portfolio_page() {
		return function_exists('trx_addons_is_portfolio_page') && trx_addons_is_portfolio_page();
	}
}

// Return true if it's services page
if ( !function_exists( 'autoparts_is_services_page' ) ) {
	function autoparts_is_services_page() {
		return function_exists('trx_addons_is_services_page') && trx_addons_is_services_page();
	}
}

// Return true if it's team page
if ( !function_exists( 'autoparts_is_team_page' ) ) {
	function autoparts_is_team_page() {
		return function_exists('trx_addons_is_team_page') && trx_addons_is_team_page();
	}
}

// Return true if it's sport page
if ( !function_exists( 'autoparts_is_sport_page' ) ) {
	function autoparts_is_sport_page() {
		return function_exists('trx_addons_is_sport_page') && trx_addons_is_sport_page();
	}
}

// Detect current blog mode
if ( !function_exists( 'autoparts_trx_addons_detect_blog_mode' ) ) {
	
	function autoparts_trx_addons_detect_blog_mode($mode='') {
		if ( autoparts_is_cars_page() )
			$mode = 'cars';
		else if ( autoparts_is_courses_page() )
			$mode = 'courses';
		else if ( autoparts_is_dishes_page() )
			$mode = 'dishes';
		else if ( autoparts_is_properties_page() )
			$mode = 'properties';
		else if ( autoparts_is_portfolio_page() )
			$mode = 'portfolio';
		else if ( autoparts_is_services_page() )
			$mode = 'services';
		else if ( autoparts_is_sport_page() )
			$mode = 'sport';
		else if ( autoparts_is_team_page() )
			$mode = 'team';
		return $mode;
	}
}

// Add team, courses, etc. to the supported posts list
if ( !function_exists( 'autoparts_trx_addons_list_post_types' ) ) {
	
	function autoparts_trx_addons_list_post_types($list=array()) {
		if (function_exists('trx_addons_get_cpt_list')) {
			$cpt_list = trx_addons_get_cpt_list();
			foreach ($cpt_list as $cpt => $title) {
				if (   
					   (defined('TRX_ADDONS_CPT_CARS_PT') && $cpt == TRX_ADDONS_CPT_CARS_PT)
					|| (defined('TRX_ADDONS_CPT_COURSES_PT') && $cpt == TRX_ADDONS_CPT_COURSES_PT)
					|| (defined('TRX_ADDONS_CPT_DISHES_PT') && $cpt == TRX_ADDONS_CPT_DISHES_PT)
					|| (defined('TRX_ADDONS_CPT_PORTFOLIO_PT') && $cpt == TRX_ADDONS_CPT_PORTFOLIO_PT)
					|| (defined('TRX_ADDONS_CPT_PROPERTIES_PT') && $cpt == TRX_ADDONS_CPT_PROPERTIES_PT)
					|| (defined('TRX_ADDONS_CPT_SERVICES_PT') && $cpt == TRX_ADDONS_CPT_SERVICES_PT)
					|| (defined('TRX_ADDONS_CPT_COMPETITIONS_PT') && $cpt == TRX_ADDONS_CPT_COMPETITIONS_PT)
					)
					$list[$cpt] = $title;
			}
		}
		return $list;
	}
}

// Return taxonomy for current post type
if ( !function_exists( 'autoparts_trx_addons_post_type_taxonomy' ) ) {
	
	function autoparts_trx_addons_post_type_taxonomy($tax='', $post_type='') {
		if ( defined('TRX_ADDONS_CPT_CARS_PT') && $post_type == TRX_ADDONS_CPT_CARS_PT )
			$tax = TRX_ADDONS_CPT_CARS_TAXONOMY_MAKER;
		else if ( defined('TRX_ADDONS_CPT_COURSES_PT') && $post_type == TRX_ADDONS_CPT_COURSES_PT )
			$tax = TRX_ADDONS_CPT_COURSES_TAXONOMY;
		else if ( defined('TRX_ADDONS_CPT_DISHES_PT') && $post_type == TRX_ADDONS_CPT_DISHES_PT )
			$tax = TRX_ADDONS_CPT_DISHES_TAXONOMY;
		else if ( defined('TRX_ADDONS_CPT_PORTFOLIO_PT') && $post_type == TRX_ADDONS_CPT_PORTFOLIO_PT )
			$tax = TRX_ADDONS_CPT_PORTFOLIO_TAXONOMY;
		else if ( defined('TRX_ADDONS_CPT_PROPERTIES_PT') && $post_type == TRX_ADDONS_CPT_PROPERTIES_PT )
			$tax = TRX_ADDONS_CPT_PROPERTIES_TAXONOMY_TYPE;
		else if ( defined('TRX_ADDONS_CPT_SERVICES_PT') && $post_type == TRX_ADDONS_CPT_SERVICES_PT )
			$tax = TRX_ADDONS_CPT_SERVICES_TAXONOMY;
		else if ( defined('TRX_ADDONS_CPT_COMPETITIONS_PT') && $post_type == TRX_ADDONS_CPT_COMPETITIONS_PT )
			$tax = TRX_ADDONS_CPT_COMPETITIONS_TAXONOMY;
		else if ( defined('TRX_ADDONS_CPT_TEAM_PT') && $post_type == TRX_ADDONS_CPT_TEAM_PT )
			$tax = TRX_ADDONS_CPT_TEAM_TAXONOMY;
		return $tax;
	}
}

// Show categories of the team, courses, etc.
if ( !function_exists( 'autoparts_trx_addons_get_post_categories' ) ) {
	
	function autoparts_trx_addons_get_post_categories($cats='') {

		if ( defined('TRX_ADDONS_CPT_CARS_PT') ) {
			if (get_post_type()==TRX_ADDONS_CPT_CARS_PT) {
				$cats = autoparts_get_post_terms(', ', get_the_ID(), TRX_ADDONS_CPT_CARS_TAXONOMY_TYPE);
			}
		}
		if ( defined('TRX_ADDONS_CPT_COURSES_PT') ) {
			if (get_post_type()==TRX_ADDONS_CPT_COURSES_PT) {
				$cats = autoparts_get_post_terms(', ', get_the_ID(), TRX_ADDONS_CPT_COURSES_TAXONOMY);
			}
		}
		if ( defined('TRX_ADDONS_CPT_DISHES_PT') ) {
			if (get_post_type()==TRX_ADDONS_CPT_DISHES_PT) {
				$cats = autoparts_get_post_terms(', ', get_the_ID(), TRX_ADDONS_CPT_DISHES_TAXONOMY);
			}
		}
		if ( defined('TRX_ADDONS_CPT_PORTFOLIO_PT') ) {
			if (get_post_type()==TRX_ADDONS_CPT_PORTFOLIO_PT) {
				$cats = autoparts_get_post_terms(', ', get_the_ID(), TRX_ADDONS_CPT_PORTFOLIO_TAXONOMY);
			}
		}
		if ( defined('TRX_ADDONS_CPT_PROPERTIES_PT') ) {
			if (get_post_type()==TRX_ADDONS_CPT_PROPERTIES_PT) {
				$cats = autoparts_get_post_terms(', ', get_the_ID(), TRX_ADDONS_CPT_PROPERTIES_TAXONOMY_TYPE);
			}
		}
		if ( defined('TRX_ADDONS_CPT_SERVICES_PT') ) {
			if (get_post_type()==TRX_ADDONS_CPT_SERVICES_PT) {
				$cats = autoparts_get_post_terms(', ', get_the_ID(), TRX_ADDONS_CPT_SERVICES_TAXONOMY);
			}
		}
		if ( defined('TRX_ADDONS_CPT_COMPETITIONS_PT') ) {
			if (get_post_type()==TRX_ADDONS_CPT_COMPETITIONS_PT) {
				$cats = autoparts_get_post_terms(', ', get_the_ID(), TRX_ADDONS_CPT_COMPETITIONS_TAXONOMY);
			}
		}
		if ( defined('TRX_ADDONS_CPT_TEAM_PT') ) {
			if (get_post_type()==TRX_ADDONS_CPT_TEAM_PT) {
				$cats = autoparts_get_post_terms(', ', get_the_ID(), TRX_ADDONS_CPT_TEAM_TAXONOMY);
			}
		}
		return $cats;
	}
}

// Show post's date with the theme-specific format
if ( !function_exists( 'autoparts_trx_addons_get_post_date_wrap' ) ) {
	
	function autoparts_trx_addons_get_post_date_wrap($dt='') {
		return apply_filters('autoparts_filter_get_post_date', $dt);
	}
}

// Show date of the courses
if ( !function_exists( 'autoparts_trx_addons_get_post_date' ) ) {
	
	function autoparts_trx_addons_get_post_date($dt='') {

		if ( defined('TRX_ADDONS_CPT_COURSES_PT') && get_post_type()==TRX_ADDONS_CPT_COURSES_PT) {
			$meta = get_post_meta(get_the_ID(), 'trx_addons_options', true);
			$dt = $meta['date'];
			$dt = sprintf($dt < date('Y-m-d') 
					? esc_html__('Started on %s', 'autoparts') 
					: esc_html__('Starting %s', 'autoparts'), 
					date(get_option('date_format'), strtotime($dt)));

		} else if ( defined('TRX_ADDONS_CPT_COMPETITIONS_PT') && in_array(get_post_type(), array(TRX_ADDONS_CPT_COMPETITIONS_PT, TRX_ADDONS_CPT_ROUNDS_PT, TRX_ADDONS_CPT_MATCHES_PT))) {
			$meta = get_post_meta(get_the_ID(), 'trx_addons_options', true);
			$dt = $meta['date_start'];
			$dt = sprintf($dt < date('Y-m-d').(!empty($meta['time_start']) ? ' H:i' : '')
					? esc_html__('Started on %s', 'autoparts') 
					: esc_html__('Starting %s', 'autoparts'), 
					date(get_option('date_format') . (!empty($meta['time_start']) ? ' '.get_option('time_format') : ''), strtotime($dt.(!empty($meta['time_start']) ? ' '.trim($meta['time_start']) : ''))));

		} else if ( defined('TRX_ADDONS_CPT_COMPETITIONS_PT') && get_post_type() == TRX_ADDONS_CPT_PLAYERS_PT) {
			// Uncomment (remove) next line if you want to show player's birthday in the page title block
			if (false) {
				$meta = get_post_meta(get_the_ID(), 'trx_addons_options', true);
				$dt = !empty($meta['birthday']) ? sprintf(esc_html__('Birthday: %s', 'autoparts'), date(get_option('date_format'), strtotime($meta['birthday']))) : '';
			} else
				$dt = '';
		}
		return $dt;
	}
}

// Check if override options is allowed
if (!function_exists('autoparts_trx_addons_allow_override_options')) {
	
	function autoparts_trx_addons_allow_override_options($allow, $post_type) {
		return $allow
					|| (defined('TRX_ADDONS_CPT_CARS_PT') && in_array($post_type, array(
																				TRX_ADDONS_CPT_CARS_PT,
																				TRX_ADDONS_CPT_CARS_AGENTS_PT
																				)))
					|| (defined('TRX_ADDONS_CPT_COURSES_PT') && $post_type==TRX_ADDONS_CPT_COURSES_PT)
					|| (defined('TRX_ADDONS_CPT_DISHES_PT') && $post_type==TRX_ADDONS_CPT_DISHES_PT)
					|| (defined('TRX_ADDONS_CPT_PORTFOLIO_PT') && $post_type==TRX_ADDONS_CPT_PORTFOLIO_PT) 
					|| (defined('TRX_ADDONS_CPT_PROPERTIES_PT') && in_array($post_type, array(
																				TRX_ADDONS_CPT_PROPERTIES_PT,
																				TRX_ADDONS_CPT_AGENTS_PT
																				)))
					|| (defined('TRX_ADDONS_CPT_RESUME_PT') && $post_type==TRX_ADDONS_CPT_RESUME_PT) 
					|| (defined('TRX_ADDONS_CPT_SERVICES_PT') && $post_type==TRX_ADDONS_CPT_SERVICES_PT) 
					|| (defined('TRX_ADDONS_CPT_COMPETITIONS_PT') && in_array($post_type, array(
																				TRX_ADDONS_CPT_COMPETITIONS_PT,
																				TRX_ADDONS_CPT_ROUNDS_PT,
																				TRX_ADDONS_CPT_MATCHES_PT
																				)))
					|| (defined('TRX_ADDONS_CPT_TEAM_PT') && $post_type==TRX_ADDONS_CPT_TEAM_PT);
	}
}

// Check if theme icons is allowed
if (!function_exists('autoparts_trx_addons_allow_theme_icons')) {
	
	function autoparts_trx_addons_allow_theme_icons($allow, $post_type) {
		$screen = function_exists('get_current_screen') ? get_current_screen() : false;
		return $allow
					|| (defined('TRX_ADDONS_CPT_LAYOUTS_PT') && $post_type==TRX_ADDONS_CPT_LAYOUTS_PT)
					|| (!empty($screen->id) && in_array($screen->id, array(
																		'appearance_page_trx_addons_options',
																		'profile'
																	)
														)
						);
	}
}

// Set related posts and columns for the plugin's output
if (!function_exists('autoparts_trx_addons_args_related')) {
	
	function autoparts_trx_addons_args_related($args) {
		if (!empty($args['template_args_name']) && in_array($args['template_args_name'], array('trx_addons_args_sc_cars', 'trx_addons_args_sc_courses', 'trx_addons_args_sc_dishes', 'trx_addons_args_sc_properties'))) {
			$args['posts_per_page'] = autoparts_get_theme_option('related_posts');
			$args['columns'] = autoparts_get_theme_option('related_columns');
		}
		return $args;
	}
}

// Add layouts to the headers list
if ( !function_exists( 'autoparts_trx_addons_list_header_styles' ) ) {
	
	function autoparts_trx_addons_list_header_styles($list=array()) {
		if (autoparts_exists_layouts()) {
			$layouts = autoparts_get_list_posts(false, array(
							'post_type' => TRX_ADDONS_CPT_LAYOUTS_PT,
							'meta_key' => 'trx_addons_layout_type',
							'meta_value' => 'header',
							'not_selected' => false
							)
						);
			foreach ($layouts as $id=>$title) {
				if ($id != 'none') $list['header-custom-'.intval($id)] = $title;
			}
		}
		return $list;
	}
}

// Add layouts to the footers list
if ( !function_exists( 'autoparts_trx_addons_list_footer_styles' ) ) {
	
	function autoparts_trx_addons_list_footer_styles($list=array()) {
		if (autoparts_exists_layouts()) {
			$layouts = autoparts_get_list_posts(false, array(
							'post_type' => TRX_ADDONS_CPT_LAYOUTS_PT,
							'meta_key' => 'trx_addons_layout_type',
							'meta_value' => 'footer',
							'not_selected' => false
							)
						);
			foreach ($layouts as $id=>$title) {
				if ($id != 'none') $list['footer-custom-'.intval($id)] = $title;
			}
		}
		return $list;
	}
}


// Add theme-specific layouts to the list
if (!function_exists('autoparts_trx_addons_default_layouts')) {
	
	function autoparts_trx_addons_default_layouts($default_layouts=array()) {
		if (autoparts_storage_isset('trx_addons_default_layouts')) {
			$layouts = autoparts_storage_get('trx_addons_default_layouts');
		} else {
			require_once AUTOPARTS_THEME_DIR . 'theme-specific/trx_addons.layouts.php';
			if (!isset($layouts) || !is_array($layouts))
				$layouts = array();
			autoparts_storage_set('trx_addons_default_layouts', $layouts);
		}
		if (count($layouts) > 0)
			$default_layouts = array_merge($default_layouts, $layouts);
		return $default_layouts;
	}
}


// Add theme-specific components to the plugin's options
if (!function_exists('autoparts_trx_addons_default_components')) {
	
	function autoparts_trx_addons_default_components($options=array()) {
		if (empty($options['components_present'])) {
			if (autoparts_storage_isset('trx_addons_default_components')) {
				$components = autoparts_storage_get('trx_addons_default_components');
			} else {
				require_once AUTOPARTS_THEME_DIR . 'theme-specific/trx_addons.components.php';
				if (!isset($components) || !is_array($components))
					$components = array();
				autoparts_storage_set('trx_addons_default_components', $components);
			}
			$options = is_array($options) && count($components) > 0
									? array_merge($options, $components)
									: $components;
		}
		return $options;
	}
}


// Enqueue custom styles
if ( !function_exists( 'autoparts_trx_addons_frontend_scripts' ) ) {
	
	function autoparts_trx_addons_frontend_scripts() {
		if (autoparts_exists_trx_addons()) {
			if (autoparts_is_on(autoparts_get_theme_option('debug_mode')) && autoparts_get_file_dir('plugins/trx_addons/trx_addons.css')!='') {
				wp_enqueue_style( 'autoparts-trx-addons',  autoparts_get_file_url('plugins/trx_addons/trx_addons.css'), array(), null );
				wp_enqueue_style( 'autoparts-trx-addons-editor',  autoparts_get_file_url('plugins/trx_addons/trx_addons.editor.css'), array(), null );
			}
			if (autoparts_is_on(autoparts_get_theme_option('debug_mode')) && autoparts_get_file_dir('plugins/trx_addons/trx_addons.js')!='')
				wp_enqueue_script( 'autoparts-trx-addons', autoparts_get_file_url('plugins/trx_addons/trx_addons.js'), array('jquery'), null, true );
		}
		// Load custom layouts from the theme if plugin not exists
		if (!autoparts_exists_trx_addons() || autoparts_get_theme_option("header_style")=='header-default') {
			if ( autoparts_is_on(autoparts_get_theme_option('debug_mode')) ) {
				wp_enqueue_style( 'autoparts-layouts', autoparts_get_file_url('plugins/trx_addons/layouts/layouts.css') );
				wp_enqueue_style( 'autoparts-layouts-logo', autoparts_get_file_url('plugins/trx_addons/layouts/logo.css') );
				wp_enqueue_style( 'autoparts-layouts-menu', autoparts_get_file_url('plugins/trx_addons/layouts/menu.css') );
				wp_enqueue_style( 'autoparts-layouts-search', autoparts_get_file_url('plugins/trx_addons/layouts/search.css') );
				wp_enqueue_style( 'autoparts-layouts-title', autoparts_get_file_url('plugins/trx_addons/layouts/title.css') );
				wp_enqueue_style( 'autoparts-layouts-featured', autoparts_get_file_url('plugins/trx_addons/layouts/featured.css') );
			}
		}
	}
}
	
// Merge custom styles
if ( !function_exists( 'autoparts_trx_addons_merge_styles' ) ) {
	
	function autoparts_trx_addons_merge_styles($list) {
		// ALWAYS merge custom layouts from the theme
		$list[] = 'plugins/trx_addons/layouts/layouts.css';
		$list[] = 'plugins/trx_addons/layouts/logo.css';
		$list[] = 'plugins/trx_addons/layouts/menu.css';
		$list[] = 'plugins/trx_addons/layouts/search.css';
		$list[] = 'plugins/trx_addons/layouts/title.css';
		$list[] = 'plugins/trx_addons/layouts/featured.css';
		if (autoparts_exists_trx_addons()) {
			$list[] = 'plugins/trx_addons/trx_addons.css';
			$list[] = 'plugins/trx_addons/trx_addons.editor.css';
		}
		return $list;
	}
}
	
// Merge custom scripts
if ( !function_exists( 'autoparts_trx_addons_merge_scripts' ) ) {
	
	function autoparts_trx_addons_merge_scripts($list) {
		$list[] = 'plugins/trx_addons/trx_addons.js';
		return $list;
	}
}




// WP Editor addons
//------------------------------------------------------------------------

// Load required styles and scripts for admin mode
if ( !function_exists( 'autoparts_trx_addons_editor_load_scripts_admin' ) ) {
	
	function autoparts_trx_addons_editor_load_scripts_admin() {
		// Add styles in the WP text editor
		add_editor_style( array(
							autoparts_get_file_url('plugins/trx_addons/trx_addons.editor.css')
							)
						 );	
	}
}



// Plugin API - theme-specific wrappers for plugin functions
//------------------------------------------------------------------------

// Debug functions wrappers
if (!function_exists('ddo')) { function ddo($obj, $level=-1) { return var_dump($obj); } }
if (!function_exists('dco')) { function dco($obj, $level=-1) { print_r($obj); } }
if (!function_exists('dcl')) { function dcl($msg, $level=-1) { echo '<br><pre>' . esc_html($msg) . '</pre><br>'; } }


// Check if URL contain specified string
if (!function_exists('autoparts_check_url')) {
	function autoparts_check_url($val='', $defa=false) {
		return function_exists('trx_addons_check_url') 
					? trx_addons_check_url($val) 
					: $defa;
	}
}

// Check if layouts components are showed or set new state
if (!function_exists('autoparts_sc_layouts_showed')) {
	function autoparts_sc_layouts_showed($name, $val=null) {
		if (function_exists('trx_addons_sc_layouts_showed')) {
			if ($val!==null)
				trx_addons_sc_layouts_showed($name, $val);
			else
				return trx_addons_sc_layouts_showed($name);
		} else {
			if ($val!==null)
				return autoparts_storage_set_array('sc_layouts_components', $name, $val);
			else
				return autoparts_storage_get_array('sc_layouts_components', $name);
		}
	}
}

// Return image size multiplier
if (!function_exists('autoparts_get_retina_multiplier')) {
	function autoparts_get_retina_multiplier($force_retina=0) {
		static $mult = 0;
		if ($mult == 0) $mult = function_exists('trx_addons_get_retina_multiplier') ? trx_addons_get_retina_multiplier($force_retina) : 1;
		return max(1, $mult);
	}
}

// Return slider layout
if (!function_exists('autoparts_get_slider_layout')) {
	function autoparts_get_slider_layout($args) {
		return function_exists('trx_addons_get_slider_layout') 
					? trx_addons_get_slider_layout($args) 
					: '';
	}
}

// Return video player layout
if (!function_exists('autoparts_get_video_layout')) {
	function autoparts_get_video_layout($args) {
		return function_exists('trx_addons_get_video_layout') 
					? trx_addons_get_video_layout($args) 
					: '';
	}
}

// Return theme specific layout of the featured image block
if ( !function_exists( 'autoparts_trx_addons_featured_image' ) ) {
	
	function autoparts_trx_addons_featured_image($processed=false, $args=array()) {
		$args['show_no_image'] = true;
		$args['singular'] = false;
		$args['hover'] = isset($args['hover']) && $args['hover']=='' ? '' : autoparts_get_theme_option('image_hover');
		autoparts_show_post_featured($args);
		return true;
	}
}

// Return theme specific 'no-image' picture
if ( !function_exists( 'autoparts_trx_addons_no_image' ) ) {
	
	function autoparts_trx_addons_no_image($no_image='') {
		return autoparts_get_no_image($no_image);
	}
}

// Return theme-specific icons
if ( !function_exists( 'autoparts_trx_addons_get_list_icons' ) ) {
	
	function autoparts_trx_addons_get_list_icons($list, $prepend_inherit) {
		return autoparts_get_list_icons($prepend_inherit);
	}
}

// Return links to the social profiles
if (!function_exists('autoparts_get_socials_links')) {
	function autoparts_get_socials_links($style='icons') {
		return function_exists('trx_addons_get_socials_links') 
					? trx_addons_get_socials_links($style)
					: '';
	}
}

// Return links to share post
if (!function_exists('autoparts_get_share_links')) {
	function autoparts_get_share_links($args=array()) {
		return function_exists('trx_addons_get_share_links') 
					? trx_addons_get_share_links($args)
					: '';
	}
}

// Display links to share post
if (!function_exists('autoparts_show_share_links')) {
	function autoparts_show_share_links($args=array()) {
		if (function_exists('trx_addons_get_share_links')) {
			$args['echo'] = true;
			trx_addons_get_share_links($args);
		}
	}
}


// Return image from the category
if (!function_exists('autoparts_get_category_image')) {
	function autoparts_get_category_image($term_id=0) {
		return function_exists('trx_addons_get_category_image') 
					? trx_addons_get_category_image($term_id)
					: '';
	}
}

// Return small image (icon) from the category
if (!function_exists('autoparts_get_category_icon')) {
	function autoparts_get_category_icon($term_id=0) {
		return function_exists('trx_addons_get_category_icon') 
					? trx_addons_get_category_icon($term_id)
					: '';
	}
}

// Return string with counters items
if (!function_exists('autoparts_get_post_counters')) {
	function autoparts_get_post_counters($counters='views') {
		return function_exists('trx_addons_get_post_counters')
					? str_replace('post_counters_item', 'post_meta_item post_counters_item', trx_addons_get_post_counters($counters))
					: '';
	}
}

// Return list with animation effects
if (!function_exists('autoparts_get_list_animations_in')) {
	function autoparts_get_list_animations_in() {
		return function_exists('trx_addons_get_list_animations_in') 
					? trx_addons_get_list_animations_in()
					: array();
	}
}

// Return classes list for the specified animation
if (!function_exists('autoparts_get_animation_classes')) {
	function autoparts_get_animation_classes($animation, $speed='normal', $loop='none') {
		return function_exists('trx_addons_get_animation_classes') 
					? trx_addons_get_animation_classes($animation, $speed, $loop)
					: '';
	}
}

// Return string with the likes counter for the specified comment
if (!function_exists('autoparts_get_comment_counters')) {
	function autoparts_get_comment_counters($counters = 'likes') {
		return function_exists('trx_addons_get_comment_counters') 
					? trx_addons_get_comment_counters($counters)
					: '';
	}
}

// Display likes counter for the specified comment
if (!function_exists('autoparts_show_comment_counters')) {
	function autoparts_show_comment_counters($counters = 'likes') {
		if (function_exists('trx_addons_get_comment_counters'))
			trx_addons_get_comment_counters($counters, true);
	}
}

// Add query params to sort posts by views or likes
if (!function_exists('autoparts_trx_addons_query_sort_order')) {
	
	function autoparts_trx_addons_query_sort_order($q=array(), $orderby='date', $order='desc') {
		if ($orderby == 'views') {
			$q['orderby'] = 'meta_value_num';
			$q['meta_key'] = 'trx_addons_post_views_count';
		} else if ($orderby == 'likes') {
			$q['orderby'] = 'meta_value_num';
			$q['meta_key'] = 'trx_addons_post_likes_count';
		}
		return $q;
	}
}

// Return theme-specific logo to the plugin
if ( !function_exists( 'autoparts_trx_addons_theme_logo' ) ) {
	
	function autoparts_trx_addons_theme_logo($logo) {
		return autoparts_get_logo_image();
	}
}

// Return theme-specific post meta to the plugin
if ( !function_exists( 'autoparts_trx_addons_post_meta' ) ) {
	
	function autoparts_trx_addons_post_meta($meta, $args=array()) {
		return autoparts_show_post_meta(apply_filters('autoparts_filter_post_meta_args', $args, 'trx_addons', 1));
	}
}
	
// Redirect action 'get_mobile_menu' to the plugin
// Return stored items as mobile menu
if ( !function_exists( 'autoparts_trx_addons_get_mobile_menu' ) ) {
	
	function autoparts_trx_addons_get_mobile_menu($menu) {
		return apply_filters('trx_addons_filter_get_mobile_menu', $menu);
	}
}

// Redirect action 'login' to the plugin
if (!function_exists('autoparts_trx_addons_action_login')) {
	
	function autoparts_trx_addons_action_login($link_text='', $link_title='') {
		do_action( 'trx_addons_action_login', $link_text, $link_title );
	}
}

// Redirect action 'search' to the plugin
if (!function_exists('autoparts_trx_addons_action_search')) {
	
	function autoparts_trx_addons_action_search($style, $class, $ajax) {
		do_action( 'trx_addons_action_search', $style, $class, $ajax );
	}
}

// Redirect action 'breadcrumbs' to the plugin
if (!function_exists('autoparts_trx_addons_action_breadcrumbs')) {
	
	function autoparts_trx_addons_action_breadcrumbs() {
		do_action( 'trx_addons_action_breadcrumbs' );
	}
}

// Redirect action 'show_layout' to the plugin
if (!function_exists('autoparts_trx_addons_action_show_layout')) {
	
	function autoparts_trx_addons_action_show_layout($layout_id='') {
		do_action( 'trx_addons_action_show_layout', $layout_id );
	}
}

// Show user meta (socials)
if (!function_exists('autoparts_trx_addons_action_user_meta')) {
	
	function autoparts_trx_addons_action_user_meta() {
		do_action( 'trx_addons_action_user_meta' );
	}
}

// Redirect filter 'get_blog_title' to the plugin
if ( !function_exists( 'autoparts_trx_addons_get_blog_title' ) ) {
	
	function autoparts_trx_addons_get_blog_title($title='') {
		return apply_filters('trx_addons_filter_get_blog_title', $title);
	}
}

// Redirect filter 'prepare_css' to the plugin
if (!function_exists('autoparts_trx_addons_prepare_css')) {
	
	function autoparts_trx_addons_prepare_css($css='', $remove_spaces=true) {
		return apply_filters( 'trx_addons_filter_prepare_css', $css, $remove_spaces );
	}
}

// Redirect filter 'prepare_js' to the plugin
if (!function_exists('autoparts_trx_addons_prepare_js')) {
	
	function autoparts_trx_addons_prepare_js($js='', $remove_spaces=true) {
		return apply_filters( 'trx_addons_filter_prepare_js', $js, $remove_spaces );
	}
}

// Add plugin's specific variables to the scripts
if (!function_exists('autoparts_trx_addons_localize_script')) {
	
	function autoparts_trx_addons_localize_script($arr) {
		$arr['trx_addons_exists'] = autoparts_exists_trx_addons();
		return $arr;
	}
}

// Return text for the "I agree ..." checkbox
if ( ! function_exists( 'autoparts_trx_addons_privacy_text' ) ) {
    add_filter( 'trx_addons_filter_privacy_text', 'autoparts_trx_addons_privacy_text' );
    function autoparts_trx_addons_privacy_text( $text='' ) {
        return autoparts_get_privacy_text();
    }
}

// Add plugin-specific colors and fonts to the custom CSS
if (autoparts_exists_trx_addons()) { require_once AUTOPARTS_THEME_DIR . 'plugins/trx_addons/trx_addons.styles.php'; }

// Return image from the term
if ( ! function_exists( 'autoparts_get_term_image' ) ) {
    function autoparts_get_term_image( $term_id = 0 ) {
        return function_exists( 'trx_addons_get_term_image' )
            ? trx_addons_get_term_image( $term_id )
            : '';
    }
}

?>
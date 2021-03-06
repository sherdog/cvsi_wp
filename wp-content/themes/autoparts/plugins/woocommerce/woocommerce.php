<?php
/* Woocommerce support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 1 - register filters, that add/remove lists items for the Theme Options
if (!function_exists('autoparts_woocommerce_theme_setup1')) {
	add_action( 'after_setup_theme', 'autoparts_woocommerce_theme_setup1', 1 );
	function autoparts_woocommerce_theme_setup1() {

		add_theme_support( 'woocommerce' );

		// Next setting from the WooCommerce 3.0+ enable built-in image zoom on the single product page
		add_theme_support( 'wc-product-gallery-zoom' );

		// Next setting from the WooCommerce 3.0+ enable built-in image slider on the single product page
		add_theme_support( 'wc-product-gallery-slider' ); 

		// Next setting from the WooCommerce 3.0+ enable built-in image lightbox on the single product page
		add_theme_support( 'wc-product-gallery-lightbox' );

		add_filter( 'autoparts_filter_list_sidebars', 	'autoparts_woocommerce_list_sidebars' );
		add_filter( 'autoparts_filter_list_posts_types',	'autoparts_woocommerce_list_post_types');
	}
}

// Theme init priorities:
// 3 - add/remove Theme Options elements
if (!function_exists('autoparts_woocommerce_theme_setup3')) {
	add_action( 'after_setup_theme', 'autoparts_woocommerce_theme_setup3', 3 );
	function autoparts_woocommerce_theme_setup3() {
		if (autoparts_exists_woocommerce()) {
		
			autoparts_storage_merge_array('options', '', array(
				// Section 'WooCommerce' - settings for show pages
				'shop' => array(
					"title" => esc_html__('Shop', 'autoparts'),
					"desc" => wp_kses_data( __('Select parameters to display the shop pages', 'autoparts') ),
					"type" => "section"
					),
				'shop_body_style_full' => array(
				"title" => esc_html__('Shop full screen', 'autoparts'),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Content', 'autoparts')
				),
				"refresh" => true,
				"std" => 0,
				"type" => "checkbox"
				),
				'expand_content_shop' => array(
					"title" => esc_html__('Expand content', 'autoparts'),
					"desc" => wp_kses_data( __('Expand the content width if the sidebar is hidden', 'autoparts') ),
					"refresh" => false,
					"std" => 1,
					"type" => "checkbox"
					),
				'stretch_tabs_area' => array(
					"title" => esc_html__('Stretch tabs area', 'autoparts'),
					"desc" => wp_kses_data( __('Stretch area with tabs on the single product to the screen width if the sidebar is hidden', 'autoparts') ),
					"std" => 1,
					"type" => "checkbox"
					),
				'related_posts_shop' => array(
					"title" => esc_html__('Related products', 'autoparts'),
					"desc" => wp_kses_data( __('How many related products should be displayed in the single product page?', 'autoparts') ),
					"std" => 3,
					"options" => autoparts_get_list_range(0,9),
					"type" => "select"
					),
				'related_columns_shop' => array(
					"title" => esc_html__('Related columns', 'autoparts'),
					"desc" => wp_kses_data( __('How many columns should be used to output related products in the single product page?', 'autoparts') ),
					"std" => 3,
					"options" => autoparts_get_list_range(1,4),
					"type" => "select"
					),
				'shop_mode' => array(
					"title" => esc_html__('Shop mode', 'autoparts'),
					"desc" => wp_kses_data( __('Select style for the products list', 'autoparts') ),
					"std" => 'thumbs',
					"options" => array(
						'thumbs'=> esc_html__('Thumbnails', 'autoparts'),
						'list'	=> esc_html__('List', 'autoparts'),
					),
					"type" => "select"
					),
				'shop_hover' => array(
					"title" => esc_html__('Hover style', 'autoparts'),
					"desc" => wp_kses_data( __('Hover style on the products in the shop archive', 'autoparts') ),
					"std" => 'none',
					"options" => apply_filters('autoparts_filter_shop_hover', array(
						'none' => esc_html__('None', 'autoparts'),
						'shop' => esc_html__('Icons', 'autoparts'),
						'shop_buttons' => esc_html__('Buttons', 'autoparts')
					)),
					"type" => "select"
					),
				'header_style_shop' => array(
					"title" => esc_html__('Header style', 'autoparts'),
					"desc" => wp_kses_data( __('Select style to display the site header on the shop archive', 'autoparts') ),
					"std" => 'inherit',
					"options" => array(),
					"type" => "select"
					),
				'header_position_shop' => array(
					"title" => esc_html__('Header position', 'autoparts'),
					"desc" => wp_kses_data( __('Select position to display the site header on the shop archive', 'autoparts') ),
					"std" => 'inherit',
					"options" => array(),
					"type" => "select"
					),
				'header_widgets_shop' => array(
					"title" => esc_html__('Header widgets', 'autoparts'),
					"desc" => wp_kses_data( __('Select set of widgets to show in the header on the shop pages', 'autoparts') ),
					"std" => 'hide',
					"options" => array(),
					"type" => "select"
					),
				'sidebar_widgets_shop' => array(
					"title" => esc_html__('Sidebar widgets', 'autoparts'),
					"desc" => wp_kses_data( __('Select sidebar to show on the shop pages', 'autoparts') ),
					"std" => 'woocommerce_widgets',
					"options" => array(),
					"type" => "select"
					),
				'sidebar_position_shop' => array(
					"title" => esc_html__('Sidebar position', 'autoparts'),
					"desc" => wp_kses_data( __('Select position to show sidebar on the shop pages', 'autoparts') ),
					"refresh" => false,
					"std" => 'left',
					"options" => array(),
					"type" => "select"
					),
				'hide_sidebar_on_single_shop' => array(
					"title" => esc_html__('Hide sidebar on the single product', 'autoparts'),
					"desc" => wp_kses_data( __("Hide sidebar on the single product's page", 'autoparts') ),
					"std" => 0,
					"type" => "checkbox"
					),
				'widgets_above_page_shop' => array(
					"title" => esc_html__('Widgets at the top of the page', 'autoparts'),
					"desc" => wp_kses_data( __('Select widgets to show at the top of the page (above content and sidebar)', 'autoparts') ),
					"std" => 'hide',
					"options" => array(),
					"type" => "select"
					),
				'widgets_above_content_shop' => array(
					"title" => esc_html__('Widgets above the content', 'autoparts'),
					"desc" => wp_kses_data( __('Select widgets to show at the beginning of the content area', 'autoparts') ),
					"std" => 'hide',
					"options" => array(),
					"type" => "select"
					),
				'widgets_below_content_shop' => array(
					"title" => esc_html__('Widgets below the content', 'autoparts'),
					"desc" => wp_kses_data( __('Select widgets to show at the ending of the content area', 'autoparts') ),
					"std" => 'hide',
					"options" => array(),
					"type" => "select"
					),
				'widgets_below_page_shop' => array(
					"title" => esc_html__('Widgets at the bottom of the page', 'autoparts'),
					"desc" => wp_kses_data( __('Select widgets to show at the bottom of the page (below content and sidebar)', 'autoparts') ),
					"std" => 'hide',
					"options" => array(),
					"type" => "select"
					),
				'footer_scheme_shop' => array(
					"title" => esc_html__('Footer Color Scheme', 'autoparts'),
					"desc" => wp_kses_data( __('Select color scheme to decorate footer area', 'autoparts') ),
					"std" => 'dark',
					"options" => array(),
					"type" => "select"
					),
				'footer_widgets_shop' => array(
					"title" => esc_html__('Footer widgets', 'autoparts'),
					"desc" => wp_kses_data( __('Select set of widgets to show in the footer', 'autoparts') ),
					"std" => 'footer_widgets',
					"options" => array(),
					"type" => "select"
					),
				'footer_columns_shop' => array(
					"title" => esc_html__('Footer columns', 'autoparts'),
					"desc" => wp_kses_data( __('Select number columns to show widgets in the footer. If 0 - autodetect by the widgets count', 'autoparts') ),
					"dependency" => array(
						'footer_widgets_shop' => array('^hide')
					),
					"std" => 0,
					"options" => autoparts_get_list_range(0,6),
					"type" => "select"
					),
				'footer_wide_shop' => array(
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

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('autoparts_woocommerce_theme_setup9')) {
	add_action( 'after_setup_theme', 'autoparts_woocommerce_theme_setup9', 9 );
	function autoparts_woocommerce_theme_setup9() {
		
		if (autoparts_exists_woocommerce()) {
			add_action( 'wp_enqueue_scripts', 								'autoparts_woocommerce_frontend_scripts', 1100 );
			add_filter( 'autoparts_filter_merge_styles',						'autoparts_woocommerce_merge_styles' );
			add_filter( 'autoparts_filter_merge_scripts',						'autoparts_woocommerce_merge_scripts');
			add_filter( 'autoparts_filter_get_post_info',		 				'autoparts_woocommerce_get_post_info');
			add_filter( 'autoparts_filter_post_type_taxonomy',				'autoparts_woocommerce_post_type_taxonomy', 10, 2 );
			if (!is_admin()) {
				add_filter( 'autoparts_filter_detect_blog_mode',				'autoparts_woocommerce_detect_blog_mode' );
				add_filter( 'autoparts_filter_get_post_categories', 			'autoparts_woocommerce_get_post_categories');
				add_filter( 'autoparts_filter_allow_override_header_image',	'autoparts_woocommerce_allow_override_header_image' );
				add_action( 'autoparts_action_before_post_meta',				'autoparts_woocommerce_action_before_post_meta');
				add_action( 'pre_get_posts',								'autoparts_woocommerce_pre_get_posts' );
				add_filter( 'autoparts_filter_localize_script',				'autoparts_woocommerce_localize_script' );
                add_filter( 'autoparts_filter_get_blog_title', 'autoparts_woocommerce_get_blog_title' );
			}
		}
		if (is_admin()) {
			add_filter( 'autoparts_filter_tgmpa_required_plugins',			'autoparts_woocommerce_tgmpa_required_plugins' );
		}

		// Add wrappers and classes to the standard WooCommerce output
		if (autoparts_exists_woocommerce()) {

			// Remove WOOC sidebar
			remove_action( 'woocommerce_sidebar', 						'woocommerce_get_sidebar', 10 );

			// Remove link around product item
			remove_action('woocommerce_before_shop_loop_item',			'woocommerce_template_loop_product_link_open', 10);
			remove_action('woocommerce_after_shop_loop_item',			'woocommerce_template_loop_product_link_close', 5);


			// Remove link around product category
			remove_action('woocommerce_before_subcategory',				'woocommerce_template_loop_category_link_open', 10);
			remove_action('woocommerce_after_subcategory',				'woocommerce_template_loop_category_link_close', 10);
			
			// Open main content wrapper - <article>
			remove_action( 'woocommerce_before_main_content',			'woocommerce_output_content_wrapper', 10);
			add_action(    'woocommerce_before_main_content',			'autoparts_woocommerce_wrapper_start', 10);
			// Close main content wrapper - </article>
			remove_action( 'woocommerce_after_main_content',			'woocommerce_output_content_wrapper_end', 10);		
			add_action(    'woocommerce_after_main_content',			'autoparts_woocommerce_wrapper_end', 10);

			// Close header section
			add_action(    'woocommerce_archive_description',			'autoparts_woocommerce_archive_description', 15 );

			// Add theme specific search form
			add_filter(    'get_product_search_form',					'autoparts_woocommerce_get_product_search_form' );

			// Change text on 'Add to cart' button
			add_filter(    'woocommerce_product_add_to_cart_text',		'autoparts_woocommerce_add_to_cart_text' );
			add_filter(    'woocommerce_product_single_add_to_cart_text','autoparts_woocommerce_add_to_cart_text' );

			// Add list mode buttons
			add_action(    'woocommerce_before_shop_loop', 				'autoparts_woocommerce_before_shop_loop', 10 );

			// Open product/category item wrapper
			add_action(    'woocommerce_before_subcategory_title',		'autoparts_woocommerce_item_wrapper_start', 9 );
			add_action(    'woocommerce_before_shop_loop_item_title',	'autoparts_woocommerce_item_wrapper_start', 9 );
			// Close featured image wrapper and open title wrapper
			add_action(    'woocommerce_before_subcategory_title',		'autoparts_woocommerce_title_wrapper_start', 20 );
			add_action(    'woocommerce_before_shop_loop_item_title',	'autoparts_woocommerce_title_wrapper_start', 20 );

			// Wrap product title into link
			add_action(    'the_title',									'autoparts_woocommerce_the_title');
			// Wrap category title into link
            remove_action( 'woocommerce_shop_loop_subcategory_title', 'woocommerce_template_loop_category_title', 10 );
			add_action(		'woocommerce_shop_loop_subcategory_title',  'autoparts_woocommerce_shop_loop_subcategory_title', 9, 1);

			// Close title wrapper and add description in the list mode
			add_action(    'woocommerce_after_shop_loop_item_title',	'autoparts_woocommerce_title_wrapper_end', 7);
			add_action(    'woocommerce_after_subcategory_title',		'autoparts_woocommerce_title_wrapper_end2', 10 );
			// Close product/category item wrapper
			add_action(    'woocommerce_after_subcategory',				'autoparts_woocommerce_item_wrapper_end', 20 );
			add_action(    'woocommerce_after_shop_loop_item',			'autoparts_woocommerce_item_wrapper_end', 20 );

			// Add 'Out of stock' label
			add_action( 'woocommerce_before_shop_loop_item_title', 		'autoparts_woocommerce_add_out_of_stock_label' );

			// Add product ID into product meta section (after categories and tags)
			add_action(    'woocommerce_product_meta_end',				'autoparts_woocommerce_show_product_id', 10);
			
			// Set columns number for the product's thumbnails
			add_filter(    'woocommerce_product_thumbnails_columns',	'autoparts_woocommerce_product_thumbnails_columns' );


			// Detect current shop mode
			if (!is_admin()) {
				$shop_mode = autoparts_get_value_gpc('autoparts_shop_mode');
				if (empty($shop_mode) && autoparts_check_theme_option('shop_mode'))
					$shop_mode = autoparts_get_theme_option('shop_mode');
				if (empty($shop_mode))
					$shop_mode = 'thumbs';
				autoparts_storage_set('shop_mode', $shop_mode);
			}
		}
	}
}



// Theme init priorities:
// Action 'wp'
// 1 - detect override mode. Attention! Only after this step you can use overriden options (separate values for the shop, courses, etc.)
if (!function_exists('autoparts_woocommerce_theme_setup_wp')) {
	add_action( 'wp', 'autoparts_woocommerce_theme_setup_wp' );
	function autoparts_woocommerce_theme_setup_wp() {
		if (autoparts_exists_woocommerce()) {
			// Set columns number for the related products
			if ((int) autoparts_get_theme_option('related_posts') == 0) {
				remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
			} else {
				add_filter(    'woocommerce_output_related_products_args',	'autoparts_woocommerce_output_related_products_args' );
				add_filter(    'woocommerce_related_products_columns',		'autoparts_woocommerce_related_products_columns' );
			}
		}
	}
}


// Check if WooCommerce installed and activated
if ( !function_exists( 'autoparts_exists_woocommerce' ) ) {
	function autoparts_exists_woocommerce() {
		return class_exists('Woocommerce');
	}
}

// Return true, if current page is any woocommerce page
if ( !function_exists( 'autoparts_is_woocommerce_page' ) ) {
	function autoparts_is_woocommerce_page() {
		$rez = false;
		if (autoparts_exists_woocommerce())
			$rez = is_woocommerce() || is_shop() || is_product() || is_product_category() || is_product_tag() || is_product_taxonomy() || is_cart() || is_checkout() || is_account_page();
		return $rez;
	}
}

// Detect current blog mode
if ( !function_exists( 'autoparts_woocommerce_detect_blog_mode' ) ) {
	
	function autoparts_woocommerce_detect_blog_mode($mode='') {
		if (is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy())
			$mode = 'shop';
		else if (is_product() || is_cart() || is_checkout() || is_account_page())
			$mode = 'shop';
		return $mode;
	}
}

// Return current page title
if ( ! function_exists( 'autoparts_woocommerce_get_blog_title' ) ) {

    function autoparts_woocommerce_get_blog_title( $title = '' ) {
        if ( ! autoparts_exists_trx_addons() && autoparts_exists_woocommerce() && autoparts_is_woocommerce_page() && is_shop() ) {
            $id    = autoparts_woocommerce_get_shop_page_id();
            $title = $id ? get_the_title( $id ) : esc_html__( 'Shop', 'autoparts' );
        }
        return $title;
    }
}


// Return taxonomy for current post type
if ( !function_exists( 'autoparts_woocommerce_post_type_taxonomy' ) ) {
	
	function autoparts_woocommerce_post_type_taxonomy($tax='', $post_type='') {
		if ($post_type == 'product')
			$tax = 'product_cat';
		return $tax;
	}
}

// Return true if page title section is allowed
if ( !function_exists( 'autoparts_woocommerce_allow_override_header_image' ) ) {
	
	function autoparts_woocommerce_allow_override_header_image($allow=true) {
		return is_product() ? false : $allow;
	}
}

// Return shop page ID
if ( !function_exists( 'autoparts_woocommerce_get_shop_page_id' ) ) {
	function autoparts_woocommerce_get_shop_page_id() {
		return get_option('woocommerce_shop_page_id');
	}
}

// Return shop page link
if ( !function_exists( 'autoparts_woocommerce_get_shop_page_link' ) ) {
	function autoparts_woocommerce_get_shop_page_link() {
		$url = '';
		$id = autoparts_woocommerce_get_shop_page_id();
		if ($id) $url = get_permalink($id);
		return $url;
	}
}

// Show categories of the current product
if ( !function_exists( 'autoparts_woocommerce_get_post_categories' ) ) {
	
	function autoparts_woocommerce_get_post_categories($cats='') {
		if (get_post_type()=='product') {
			$cats = autoparts_get_post_terms(', ', get_the_ID(), 'product_cat');
		}
		return $cats;
	}
}

// Add 'product' to the list of the supported post-types
if ( !function_exists( 'autoparts_woocommerce_list_post_types' ) ) {
	
	function autoparts_woocommerce_list_post_types($list=array()) {
		$list['product'] = esc_html__('Products', 'autoparts');
		return $list;
	}
}

// Show price of the current product in the widgets and search results
if ( !function_exists( 'autoparts_woocommerce_get_post_info' ) ) {
	
	function autoparts_woocommerce_get_post_info($post_info='') {
		if (get_post_type()=='product') {
			global $product;
			if ( $price_html = $product->get_price_html() ) {
				$post_info = '<div class="post_price product_price price">' . trim($price_html) . '</div>' . $post_info;
			}
		}
		return $post_info;
	}
}

// Show price of the current product in the search results streampage
if ( !function_exists( 'autoparts_woocommerce_action_before_post_meta' ) ) {
	
	function autoparts_woocommerce_action_before_post_meta() {
		if (get_post_type()=='product') {
			global $product;
			if ( $price_html = $product->get_price_html() ) {
				?><div class="post_price product_price price"><?php autoparts_show_layout($price_html); ?></div><?php
			}
		}
	}
}
	
// Enqueue WooCommerce custom styles
if ( !function_exists( 'autoparts_woocommerce_frontend_scripts' ) ) {
	
	function autoparts_woocommerce_frontend_scripts() {
			if (autoparts_is_on(autoparts_get_theme_option('debug_mode')) && autoparts_get_file_dir('plugins/woocommerce/woocommerce.css')!='')
				wp_enqueue_style( 'autoparts-woocommerce',  autoparts_get_file_url('plugins/woocommerce/woocommerce.css'), array(), null );
			if (autoparts_is_on(autoparts_get_theme_option('debug_mode')) && autoparts_get_file_dir('plugins/woocommerce/woocommerce.js')!='')
				wp_enqueue_script( 'autoparts-woocommerce', autoparts_get_file_url('plugins/woocommerce/woocommerce.js'), array('jquery'), null, true );
	}
}
	
// Merge custom styles
if ( !function_exists( 'autoparts_woocommerce_merge_styles' ) ) {
	
	function autoparts_woocommerce_merge_styles($list) {
		$list[] = 'plugins/woocommerce/woocommerce.css';
		return $list;
	}
}
	
// Merge custom scripts
if ( !function_exists( 'autoparts_woocommerce_merge_scripts' ) ) {
	
	function autoparts_woocommerce_merge_scripts($list) {
		$list[] = 'plugins/woocommerce/woocommerce.js';
		return $list;
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'autoparts_woocommerce_tgmpa_required_plugins' ) ) {
	
	function autoparts_woocommerce_tgmpa_required_plugins($list=array()) {
		if (in_array('woocommerce', autoparts_storage_get('required_plugins')))
			$list[] = array(
					'name' 		=> esc_html__('WooCommerce', 'autoparts'),
					'slug' 		=> 'woocommerce',
					'required' 	=> false
				);

		return $list;
	}
}


// Add label 'out of stock'
if ( ! function_exists( 'autoparts_woocommerce_add_out_of_stock_label' ) ) {
	
	function autoparts_woocommerce_add_out_of_stock_label() {
		global $product;
		$cat = autoparts_storage_get( 'in_product_category' );
		if ( empty($cat) || ! is_object($cat) ) {
			if ( is_object( $product ) && ! $product->is_in_stock() ) {
				?>
				<span class="outofstock_label"><?php esc_html_e( 'Out of stock', 'autoparts' ); ?></span>
				<?php
			}
		}
	}
}


// Add WooCommerce specific items into lists
//------------------------------------------------------------------------

// Add sidebar
if ( !function_exists( 'autoparts_woocommerce_list_sidebars' ) ) {
	
	function autoparts_woocommerce_list_sidebars($list=array()) {
		$list['woocommerce_widgets'] = array(
											'name' => esc_html__('WooCommerce Widgets', 'autoparts'),
											'description' => esc_html__('Widgets to be shown on the WooCommerce pages', 'autoparts')
											);
		return $list;
	}
}




// Decorate WooCommerce output: Loop
//------------------------------------------------------------------------

// Add query vars to set products per page
if (!function_exists('autoparts_woocommerce_pre_get_posts')) {
	
	function autoparts_woocommerce_pre_get_posts($query) {
		if (!$query->is_main_query()) return;
		if ($query->get('post_type') == 'product') {
			$ppp = get_theme_mod('posts_per_page_shop', 0);
			if ($ppp > 0)
				$query->set('posts_per_page', $ppp);
		}
	}
}


// Before main content
if ( !function_exists( 'autoparts_woocommerce_wrapper_start' ) ) {
	
	function autoparts_woocommerce_wrapper_start() {
		if (is_product() || is_cart() || is_checkout() || is_account_page()) {
			?>
			<article class="post_item_single post_type_product">
			<?php
		} else {
			?>
			<div class="list_products shop_mode_<?php echo !autoparts_storage_empty('shop_mode') ? autoparts_storage_get('shop_mode') : 'thumbs'; ?>">
				<div class="list_products_header">
			<?php
		}
	}
}

// After main content
if ( !function_exists( 'autoparts_woocommerce_wrapper_end' ) ) {
	
	function autoparts_woocommerce_wrapper_end() {
		if (is_product() || is_cart() || is_checkout() || is_account_page()) {
			?>
			</article><!-- /.post_item_single -->
			<?php
		} else {
			?>
			</div><!-- /.list_products -->
			<?php
		}
	}
}

// Close header section
if ( !function_exists( 'autoparts_woocommerce_archive_description' ) ) {
	
	function autoparts_woocommerce_archive_description() {
		?>
		</div><!-- /.list_products_header -->
		<?php
	}
}

// Add list mode buttons
if ( !function_exists( 'autoparts_woocommerce_before_shop_loop' ) ) {
	
	function autoparts_woocommerce_before_shop_loop() {
		?>
		<div class="autoparts_shop_mode_buttons"><form action="<?php echo esc_url(autoparts_get_current_url()); ?>" method="post"><input type="hidden" name="autoparts_shop_mode" value="<?php echo esc_attr(autoparts_storage_get('shop_mode')); ?>" /><a href="#" class="woocommerce_thumbs icon-th" title="<?php esc_attr_e('Show products as thumbs', 'autoparts'); ?>"></a><a href="#" class="woocommerce_list icon-th-list" title="<?php esc_attr_e('Show products as list', 'autoparts'); ?>"></a></form></div><!-- /.autoparts_shop_mode_buttons -->
		<?php
	}
}


// Open item wrapper for categories and products
if ( !function_exists( 'autoparts_woocommerce_item_wrapper_start' ) ) {
	
	
	function autoparts_woocommerce_item_wrapper_start($cat='') {
		autoparts_storage_set('in_product_item', true);
		$hover = autoparts_get_theme_option('shop_hover');
		?>
		<div class="post_item post_layout_<?php echo esc_attr(autoparts_storage_get('shop_mode')); ?>">
			<div class="post_featured hover_<?php echo esc_attr($hover); ?>">
				<?php do_action('autoparts_action_woocommerce_item_featured_start'); ?>
				<a href="<?php echo esc_url(is_object($cat) ? get_term_link($cat->slug, 'product_cat') : get_permalink()); ?>">
				<?php
	}
}

// Open item wrapper for categories and products
if ( !function_exists( 'autoparts_woocommerce_open_item_wrapper' ) ) {
	
	
	function autoparts_woocommerce_title_wrapper_start($cat='') {
				?></a><?php
				if (($hover = autoparts_get_theme_option('shop_hover')) != 'none') {
					?><div class="mask"></div><?php
					autoparts_hovers_add_icons($hover, array('cat'=>$cat));
				}
				do_action('autoparts_action_woocommerce_item_featured_end');
				?>
			</div><!-- /.post_featured -->
			<div class="post_data">
				<div class="post_data_inner">
					<div class="post_header entry-header">
					<?php
	}
}


// Display product's tags before the title
if ( !function_exists( 'autoparts_woocommerce_title_tags' ) ) {
	
	function autoparts_woocommerce_title_tags() {
		global $product;
		autoparts_show_layout(wc_get_product_tag_list( $product->get_id(), ', ', '<div class="post_tags product_tags">', '</div>' ));
	}
}

// Wrap product title into link
if ( !function_exists( 'autoparts_woocommerce_the_title' ) ) {
	
	function autoparts_woocommerce_the_title($title) {
		if (autoparts_storage_get('in_product_item') && get_post_type()=='product') {
			$title = '<a href="'.esc_url(get_permalink()).'">'.esc_html($title).'</a>';
		}
		return $title;
	}
}

// Wrap category title into link
if ( !function_exists( 'autoparts_woocommerce_shop_loop_subcategory_title' ) ) {
	
	function autoparts_woocommerce_shop_loop_subcategory_title($cat) {
		if (autoparts_storage_get('in_product_item') && is_object($cat)) {
			$cat->name = sprintf('<a href="%s">%s</a>', esc_url(get_term_link($cat->slug, 'product_cat')), $cat->name);
		}
        ?>
        <h2 class="woocommerce-loop-category__title">
        <?php
        autoparts_show_layout($cat->name);

        if ( $cat->count > 0 ) {
            echo apply_filters( 'woocommerce_subcategory_count_html', ' <mark class="count">(' . esc_html( $cat->count ) . ')</mark>', $cat );
        }
        ?>
        </h2><?php
	}
}

// Add excerpt in output for the product in the list mode
if ( !function_exists( 'autoparts_woocommerce_title_wrapper_end' ) ) {
	
	function autoparts_woocommerce_title_wrapper_end() {
			?>
			</div><!-- /.post_header -->
		<?php
		if (autoparts_storage_get('shop_mode') == 'list' && (is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy()) && !is_product()) {
		    $excerpt = apply_filters('the_excerpt', get_the_excerpt());
			?>
			<div class="post_content entry-content"><?php autoparts_show_layout($excerpt); ?></div>
			<?php
		}
	}
}

// Add excerpt in output for the product in the list mode
if ( !function_exists( 'autoparts_woocommerce_title_wrapper_end2' ) ) {
	
	function autoparts_woocommerce_title_wrapper_end2($category) {
			?>
			</div><!-- /.post_header -->
		<?php
		if (autoparts_storage_get('shop_mode') == 'list' && is_shop() && !is_product()) {
			?>
			<div class="post_content entry-content"><?php autoparts_show_layout($category->description); ?></div><!-- /.post_content -->
			<?php
		}
	}
}

// Close item wrapper for categories and products
if ( !function_exists( 'autoparts_woocommerce_close_item_wrapper' ) ) {
	
	
	function autoparts_woocommerce_item_wrapper_end($cat='') {
				?>
				</div><!-- /.post_data_inner -->
			</div><!-- /.post_data -->
		</div><!-- /.post_item -->
		<?php
		autoparts_storage_set('in_product_item', false);
	}
}


// Change text on 'Add to cart' button
if ( ! function_exists( 'autoparts_woocommerce_add_to_cart_text' ) ) {
    function autoparts_woocommerce_add_to_cart_text( $text = '' ) {
        global $product;
        return is_object( $product ) && $product->is_in_stock()
        && 'grouped' !== $product->get_type()
        && ( 'external' !== $product->get_type() || $product->get_button_text() == '' )
            ? esc_html__( 'Buy now', 'autoparts' )
            : $text;
    }
}

// Decorate price
if ( !function_exists( 'autoparts_woocommerce_get_price_html' ) ) {
	
	function autoparts_woocommerce_get_price_html($price='') {
		if (!is_admin() && !empty($price)) {
			$sep = get_option('woocommerce_price_decimal_sep');
			if (empty($sep)) $sep = '.';
			$price = preg_replace('/([0-9,]+)(\\'.trim($sep).')([0-9]{2})/', '\\1<span class="decimals">\\3</span>', $price);
		}
		return $price;
	}
}



// Decorate WooCommerce output: Single product
//------------------------------------------------------------------------

// Add WooCommerce specific vars into localize array
if (!function_exists('autoparts_woocommerce_localize_script')) {
	
	function autoparts_woocommerce_localize_script($arr) {
		$arr['stretch_tabs_area'] = !autoparts_sidebar_present() ? autoparts_get_theme_option('stretch_tabs_area') : 0;
		return $arr;
	}
}

// Add Product ID for the single product
if ( !function_exists( 'autoparts_woocommerce_show_product_id' ) ) {
	
	function autoparts_woocommerce_show_product_id() {
		$authors = wp_get_post_terms(get_the_ID(), 'pa_product_author');
		if (is_array($authors) && count($authors)>0) {
			echo '<span class="product_author">'.esc_html__('Author: ', 'autoparts');
			$delim = '';
			foreach ($authors as $author) {
				echo  esc_html($delim) . '<span>' . esc_html($author->name) . '</span>';
				$delim = ', ';
			}
			echo '</span>';
		}
		echo '<span class="product_id">'.esc_html__('Product ID: ', 'autoparts') . '<span>' . get_the_ID() . '</span></span>';
	}
}

// Number columns for the product's thumbnails
if ( !function_exists( 'autoparts_woocommerce_product_thumbnails_columns' ) ) {
	
	function autoparts_woocommerce_product_thumbnails_columns($cols) {
		return 4;
	}
}

// Set products number for the related products
if ( !function_exists( 'autoparts_woocommerce_output_related_products_args' ) ) {
	
	function autoparts_woocommerce_output_related_products_args($args) {
		$args['posts_per_page'] = max(0, min(9, autoparts_get_theme_option('related_posts')));
		$args['columns'] = max(1, min(4, autoparts_get_theme_option('related_columns')));
		return $args;
	}
}


// Set columns number for the related products
if ( !function_exists( 'autoparts_woocommerce_related_products_columns' ) ) {
	
	function autoparts_woocommerce_related_products_columns($columns) {
		$columns = max(1, min(4, autoparts_get_theme_option('related_columns')));
		return $columns;
	}
}

// Price filter change step
if ( ! function_exists( 'autoparts_woocommerce_price_filter_widget_step' ) ) {
    add_filter('woocommerce_price_filter_widget_step', 'autoparts_woocommerce_price_filter_widget_step');
    function autoparts_woocommerce_price_filter_widget_step( $step = '' ) {
        $step = 1;
        return $step;
    }
}

// Decorate WooCommerce output: Widgets
//------------------------------------------------------------------------

// Search form
if ( !function_exists( 'autoparts_woocommerce_get_product_search_form' ) ) {
	
	function autoparts_woocommerce_get_product_search_form($form) {
		return '
		<form role="search" method="get" class="search_form" action="' . esc_url(home_url('/')) . '">
			<input type="text" class="search_field" placeholder="' . esc_attr__('Search &hellip;', 'autoparts') . '" value="' . get_search_query() . '" name="s" /><button class="search_button" type="submit">' . esc_html__('Search', 'autoparts') . '</button>
			<input type="hidden" name="post_type" value="product" />
		</form>
		';
	}
}

function autoparts_hide_price_add_cart_not_logged_in() {
 
      remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
      remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
      remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
      remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
  
}


// Add plugin-specific colors and fonts to the custom CSS
if (autoparts_exists_woocommerce()) { require_once AUTOPARTS_THEME_DIR . 'plugins/woocommerce/woocommerce.styles.php'; }

?>
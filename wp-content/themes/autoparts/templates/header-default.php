<?php
/**
 * The template to display default site header
 *
 * @package WordPress
 * @subpackage AUTOPARTS
 * @since AUTOPARTS 1.0
 */

$autoparts_header_css = $autoparts_header_image = '';
$autoparts_header_video = autoparts_get_header_video();
if (true || empty($autoparts_header_video)) {
	$autoparts_header_image = get_header_image();
	if (autoparts_is_on(autoparts_get_theme_option('header_image_override')) && apply_filters('autoparts_filter_allow_override_header_image', true)) {
		if (is_category()) {
			if (($autoparts_cat_img = autoparts_get_category_image()) != '')
				$autoparts_header_image = $autoparts_cat_img;
		} else if (is_singular() || autoparts_storage_isset('blog_archive')) {
			if (has_post_thumbnail()) {
				$autoparts_header_image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
				if (is_array($autoparts_header_image)) $autoparts_header_image = $autoparts_header_image[0];
			} else
				$autoparts_header_image = '';
		}
	}
}

?><header class="top_panel top_panel_default default_header default_header_bg_img <?php
					echo !empty($autoparts_header_image) || !empty($autoparts_header_video) ? ' with_bg_image' : ' without_bg_image';
					if ($autoparts_header_video!='') echo ' with_bg_video';
					if ($autoparts_header_image!='') echo ' '.esc_attr(autoparts_add_inline_css_class('background-image: url('.esc_url($autoparts_header_image).');'));
					if (is_single() && has_post_thumbnail()) echo ' with_featured_image';
					if (autoparts_is_on(autoparts_get_theme_option('header_fullheight'))) echo ' header_fullheight trx-stretch-height';
					?> scheme_<?php echo esc_attr(autoparts_is_inherit(autoparts_get_theme_option('header_scheme')) 
													? autoparts_get_theme_option('color_scheme') 
													: autoparts_get_theme_option('header_scheme'));
					?>"><?php

	// Background video
	if (!empty($autoparts_header_video)) {
		get_template_part( 'templates/header-video' );
	}
	
	// Main menu
	if (autoparts_get_theme_option("menu_style") == 'top') {
		get_template_part( 'templates/header-navi' );
	}

	// Page title and breadcrumbs area
	get_template_part( 'templates/header-title');

	// Header widgets area
	get_template_part( 'templates/header-widgets' );


?></header>
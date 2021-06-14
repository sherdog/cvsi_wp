<?php
/**
 * The template to display custom header from the ThemeREX Addons Layouts
 *
 * @package WordPress
 * @subpackage AUTOPARTS
 * @since AUTOPARTS 1.0.06
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

$autoparts_header_id = str_replace('header-custom-', '', autoparts_get_theme_option("header_style"));
if ((int) $autoparts_header_id == 0) {
	$autoparts_header_id = autoparts_get_post_id(array(
			'name' => $autoparts_header_id,
			'post_type' => defined('TRX_ADDONS_CPT_LAYOUTS_PT') ? TRX_ADDONS_CPT_LAYOUTS_PT : 'cpt_layouts'
		)
	);
} else {
	$autoparts_header_id = apply_filters('trx_addons_filter_get_translated_layout', $autoparts_header_id);
}
$autoparts_header_meta = get_post_meta($autoparts_header_id, 'trx_addons_options', true);

?><header class="top_panel top_panel_custom top_panel_custom_<?php echo esc_attr($autoparts_header_id); 
						?> top_panel_custom_<?php echo esc_attr(sanitize_title(get_the_title($autoparts_header_id)));
						echo !empty($autoparts_header_image) || !empty($autoparts_header_video) 
							? ' with_bg_image' 
							: ' without_bg_image';
						if ($autoparts_header_video!='') 
							echo ' with_bg_video';
						if ($autoparts_header_image!='') 
							echo ' '.esc_attr(autoparts_add_inline_css_class('background-image: url('.esc_url($autoparts_header_image).');'));
						if (!empty($autoparts_header_meta['margin']) != '') 
							echo ' '.esc_attr(autoparts_add_inline_css_class('margin-bottom: '.esc_attr(autoparts_prepare_css_value($autoparts_header_meta['margin'])).';'));
						if (is_single() && has_post_thumbnail()) 
							echo ' with_featured_image';
						if (autoparts_is_on(autoparts_get_theme_option('header_fullheight'))) 
							echo ' header_fullheight trx-stretch-height';
						?> scheme_<?php echo esc_attr(autoparts_is_inherit(autoparts_get_theme_option('header_scheme')) 
														? autoparts_get_theme_option('color_scheme') 
														: autoparts_get_theme_option('header_scheme'));
						?>"><?php

	// Background video
	if (!empty($autoparts_header_video)) {
		get_template_part( 'templates/header-video' );
	}
		
	// Custom header's layout
	do_action('autoparts_action_show_layout', $autoparts_header_id);

	// Header widgets area
	get_template_part( 'templates/header-widgets' );
		
?></header>
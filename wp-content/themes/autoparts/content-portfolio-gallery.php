<?php
/**
 * The Gallery template to display posts
 *
 * Used for index/archive/search.
 *
 * @package WordPress
 * @subpackage AUTOPARTS
 * @since AUTOPARTS 1.0
 */

$autoparts_blog_style = explode('_', autoparts_get_theme_option('blog_style'));
$autoparts_columns = empty($autoparts_blog_style[1]) ? 2 : max(2, $autoparts_blog_style[1]);
$autoparts_post_format = get_post_format();
$autoparts_post_format = empty($autoparts_post_format) ? 'standard' : str_replace('post-format-', '', $autoparts_post_format);
$autoparts_animation = autoparts_get_theme_option('blog_animation');
$autoparts_image = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'full' );

?><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_portfolio post_layout_gallery post_layout_gallery_'.esc_attr($autoparts_columns).' post_format_'.esc_attr($autoparts_post_format) ); ?>
	<?php echo (!autoparts_is_off($autoparts_animation) ? ' data-animation="'.esc_attr(autoparts_get_animation_classes($autoparts_animation)).'"' : ''); ?>
	data-size="<?php if (!empty($autoparts_image[1]) && !empty($autoparts_image[2])) echo intval($autoparts_image[1]) .'x' . intval($autoparts_image[2]); ?>"
	data-src="<?php if (!empty($autoparts_image[0])) echo esc_url($autoparts_image[0]); ?>"
	>

	<?php

	// Sticky label
	if ( is_sticky() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	// Featured image
	$autoparts_image_hover = 'icon';
	if (in_array($autoparts_image_hover, array('icons', 'zoom'))) $autoparts_image_hover = 'dots';
	$autoparts_components = autoparts_is_inherit(autoparts_get_theme_option_from_meta('meta_parts')) 
								? 'categories,date,counters,share'
								: autoparts_array_get_keys_by_value(autoparts_get_theme_option('meta_parts'));
	$autoparts_counters = autoparts_is_inherit(autoparts_get_theme_option_from_meta('counters')) 
								? 'comments'
								: autoparts_array_get_keys_by_value(autoparts_get_theme_option('counters'));
	autoparts_show_post_featured(array(
		'hover' => $autoparts_image_hover,
		'thumb_size' => autoparts_get_thumb_size( strpos(autoparts_get_theme_option('body_style'), 'full')!==false || $autoparts_columns < 3 ? 'masonry-big' : 'masonry' ),
		'thumb_only' => true,
		'show_no_image' => true,
		'post_info' => '<div class="post_details">'
							. '<h2 class="post_title"><a href="'.esc_url(get_permalink()).'">'. esc_html(get_the_title()) . '</a></h2>'
							. '<div class="post_description">'
								. (!empty($autoparts_components)
										? autoparts_show_post_meta(apply_filters('autoparts_filter_post_meta_args', array(
											'components' => $autoparts_components,
											'counters' => $autoparts_counters,
											'seo' => false,
											'echo' => false
											), $autoparts_blog_style[0], $autoparts_columns))
										: '')
								. '<div class="post_description_content">'
									. apply_filters('the_excerpt', get_the_excerpt())
								. '</div>'
								. '<a href="'.esc_url(get_permalink()).'" class="theme_button post_readmore"><span class="post_readmore_label">' . esc_html__('Learn more', 'autoparts') . '</span></a>'
							. '</div>'
						. '</div>'
	));
	?>
</article>
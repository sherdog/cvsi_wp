<?php
/**
 * The Portfolio template to display the content
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

?><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_portfolio post_layout_portfolio_'.esc_attr($autoparts_columns).' post_format_'.esc_attr($autoparts_post_format).(is_sticky() && !is_paged() ? ' sticky' : '') ); ?>
	<?php echo (!autoparts_is_off($autoparts_animation) ? ' data-animation="'.esc_attr(autoparts_get_animation_classes($autoparts_animation)).'"' : ''); ?>>
	<?php

	// Sticky label
	if ( is_sticky() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	$autoparts_image_hover = autoparts_get_theme_option('image_hover');
	// Featured image
	autoparts_show_post_featured(array(
		'thumb_size' => autoparts_get_thumb_size(strpos(autoparts_get_theme_option('body_style'), 'full')!==false || $autoparts_columns < 3 ? 'masonry-big' : 'masonry'),
		'show_no_image' => true,
		'class' => $autoparts_image_hover == 'dots' ? 'hover_with_info' : '',
		'post_info' => $autoparts_image_hover == 'dots' ? '<div class="post_info">'.esc_html(get_the_title()).'</div>' : ''
	));
	?>
</article>
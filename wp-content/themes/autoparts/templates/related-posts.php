<?php
/**
 * The template 'Style 1' to displaying related posts
 *
 * @package WordPress
 * @subpackage AUTOPARTS
 * @since AUTOPARTS 1.0
 */

$autoparts_link = get_permalink();
$autoparts_post_format = get_post_format();
$autoparts_post_format = empty($autoparts_post_format) ? 'standard' : str_replace('post-format-', '', $autoparts_post_format);
?><div id="post-<?php the_ID(); ?>" 
	<?php post_class( 'related_item related_item_style_1 post_format_'.esc_attr($autoparts_post_format) ); ?>><?php
	autoparts_show_post_featured(array(
		'thumb_size' => autoparts_get_thumb_size( (int) autoparts_get_theme_option('related_posts') == 1 ? 'huge' : 'big' ),
		'show_no_image' => false,
		'singular' => false,
		'post_info' => '<div class="post_header entry-header">'
							. '<div class="post_categories">' . autoparts_get_post_categories('') . '</div>'
							. '<h6 class="post_title entry-title"><a href="' . esc_url($autoparts_link) . '">' . wp_kses( get_the_title() ,'autoparts_kses_content' ) . '</a></h6>'
							. (in_array(get_post_type(), array('post', 'attachment'))
									? '<span class="post_date"><a href="' . esc_url($autoparts_link) . '">' . autoparts_get_date() . '</a></span>'
									: '')
						. '</div>'
		)
	);
?></div>
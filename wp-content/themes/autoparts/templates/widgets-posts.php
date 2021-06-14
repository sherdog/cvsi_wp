<?php
/**
 * The template to display posts in widgets and/or in the search results
 *
 * @package WordPress
 * @subpackage AUTOPARTS
 * @since AUTOPARTS 1.0
 */

$autoparts_post_id    = get_the_ID();
$autoparts_post_date  = autoparts_get_date();
$autoparts_post_title = get_the_title();
$autoparts_post_link  = get_permalink();
$autoparts_post_author_id   = get_the_author_meta('ID');
$autoparts_post_author_name = get_the_author_meta('display_name');
$autoparts_post_author_url  = get_author_posts_url($autoparts_post_author_id, '');

$autoparts_args = get_query_var('autoparts_args_widgets_posts');
$autoparts_show_date = isset($autoparts_args['show_date']) ? (int) $autoparts_args['show_date'] : 1;
$autoparts_show_image = isset($autoparts_args['show_image']) ? (int) $autoparts_args['show_image'] : 1;
$autoparts_show_author = isset($autoparts_args['show_author']) ? (int) $autoparts_args['show_author'] : 1;
$autoparts_show_counters = isset($autoparts_args['show_counters']) ? (int) $autoparts_args['show_counters'] : 1;
$autoparts_show_categories = isset($autoparts_args['show_categories']) ? (int) $autoparts_args['show_categories'] : 1;

$autoparts_output = autoparts_storage_get('autoparts_output_widgets_posts');

$autoparts_post_counters_output = '';
if ( $autoparts_show_counters ) {
	$autoparts_post_counters_output = '<span class="post_info_item post_info_counters">'
								. autoparts_get_post_counters('comments')
							. '</span>';
}


$autoparts_output .= '<article class="post_item with_thumb">';

if ($autoparts_show_image) {
	$autoparts_post_thumb = get_the_post_thumbnail($autoparts_post_id, autoparts_get_thumb_size('tiny'), array(
		'alt' => the_title_attribute( array( 'echo' => false ) )
	));
	if ($autoparts_post_thumb) $autoparts_output .= '<div class="post_thumb">' . ($autoparts_post_link ? '<a href="' . esc_url($autoparts_post_link) . '">' : '') . ($autoparts_post_thumb) . ($autoparts_post_link ? '</a>' : '') . '</div>';
}

$autoparts_output .= '<div class="post_content">'
			. ($autoparts_show_categories 
					? '<div class="post_categories">'
						. autoparts_get_post_categories()
						. $autoparts_post_counters_output
						. '</div>' 
					: '')
			. '<h6 class="post_title">' . ($autoparts_post_link ? '<a href="' . esc_url($autoparts_post_link) . '">' : '') . ($autoparts_post_title) . ($autoparts_post_link ? '</a>' : '') . '</h6>'
			. apply_filters('autoparts_filter_get_post_info', 
								'<div class="post_info">'
									. ($autoparts_show_date 
										? '<span class="post_info_item post_info_posted">'
											. ($autoparts_post_link ? '<a href="' . esc_url($autoparts_post_link) . '" class="post_info_date">' : '') 
											. esc_html($autoparts_post_date) 
											. ($autoparts_post_link ? '</a>' : '')
											. '</span>'
										: '')
									. ($autoparts_show_author 
										? '<span class="post_info_item post_info_posted_by">' 
											. esc_html__('by', 'autoparts') . ' ' 
											. ($autoparts_post_link ? '<a href="' . esc_url($autoparts_post_author_url) . '" class="post_info_author">' : '') 
											. esc_html($autoparts_post_author_name) 
											. ($autoparts_post_link ? '</a>' : '') 
											. '</span>'
										: '')
									. (!$autoparts_show_categories && $autoparts_post_counters_output
										? $autoparts_post_counters_output
										: '')
								. '</div>')
		. '</div>'
	. '</article>';
autoparts_storage_set('autoparts_output_widgets_posts', $autoparts_output);
?>
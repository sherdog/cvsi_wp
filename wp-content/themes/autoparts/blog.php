<?php
/**
 * The template to display blog archive
 *
 * @package WordPress
 * @subpackage AUTOPARTS
 * @since AUTOPARTS 1.0
 */

/*
Template Name: Blog archive
*/

/**
 * Make page with this template and put it into menu
 * to display posts as blog archive
 * You can setup output parameters (blog style, posts per page, parent category, etc.)
 * in the Theme Options section (under the page content)
 * You can build this page in the WPBakery Page Builder to make custom page layout:
 * just insert %%CONTENT%% in the desired place of content
 */

// Get template page's content
$autoparts_content = '';
$autoparts_blog_archive_mask = '%%CONTENT%%';
$autoparts_blog_archive_subst = sprintf('<div class="blog_archive">%s</div>', $autoparts_blog_archive_mask);
if ( have_posts() ) {
	the_post(); 
	if (($autoparts_content = apply_filters('the_content', get_the_content())) != '') {
		if (($autoparts_pos = strpos($autoparts_content, $autoparts_blog_archive_mask)) !== false) {
			$autoparts_content = preg_replace('/(\<p\>\s*)?'.$autoparts_blog_archive_mask.'(\s*\<\/p\>)/i', $autoparts_blog_archive_subst, $autoparts_content);
		} else
			$autoparts_content .= $autoparts_blog_archive_subst;
		$autoparts_content = explode($autoparts_blog_archive_mask, $autoparts_content);
		// Add VC custom styles to the inline CSS
		$vc_custom_css = get_post_meta( get_the_ID(), '_wpb_shortcodes_custom_css', true );
		if ( !empty( $vc_custom_css ) ) autoparts_add_inline_css(strip_tags($vc_custom_css));
	}
}

// Prepare args for a new query
$autoparts_args = array(
	'post_status' => current_user_can('read_private_pages') && current_user_can('read_private_posts') ? array('publish', 'private') : 'publish'
);
$autoparts_args = autoparts_query_add_posts_and_cats($autoparts_args, '', autoparts_get_theme_option('post_type'), autoparts_get_theme_option('parent_cat'));
$autoparts_page_number = get_query_var('paged') ? get_query_var('paged') : (get_query_var('page') ? get_query_var('page') : 1);
if ($autoparts_page_number > 1) {
	$autoparts_args['paged'] = $autoparts_page_number;
	$autoparts_args['ignore_sticky_posts'] = true;
}
$autoparts_ppp = autoparts_get_theme_option('posts_per_page');
if ((int) $autoparts_ppp != 0)
	$autoparts_args['posts_per_page'] = (int) $autoparts_ppp;
// Make a new query
query_posts( $autoparts_args );
// Set a new query as main WP Query
$GLOBALS['wp_the_query'] = $GLOBALS['wp_query'];

// Set query vars in the new query!
if (is_array($autoparts_content) && count($autoparts_content) == 2) {
	set_query_var('blog_archive_start', $autoparts_content[0]);
	set_query_var('blog_archive_end', $autoparts_content[1]);
}

get_template_part('index');
?>
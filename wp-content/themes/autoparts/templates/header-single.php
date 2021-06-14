<?php
/**
 * The template to display the featured image in the single post
 *
 * @package WordPress
 * @subpackage AUTOPARTS
 * @since AUTOPARTS 1.0
 */

if ( get_query_var('autoparts_header_image')=='' && is_singular() && has_post_thumbnail() && in_array(get_post_type(), array('post', 'page')) )  {
	$autoparts_src = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );
	if (!empty($autoparts_src[0])) {
		autoparts_sc_layouts_showed('featured', true);
		?><div class="sc_layouts_featured with_image <?php echo esc_attr(autoparts_add_inline_css_class('background-image:url('.esc_url($autoparts_src[0]).');')); ?>"></div><?php
	}
}
?>
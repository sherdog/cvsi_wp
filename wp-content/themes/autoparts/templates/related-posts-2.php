<?php
/**
 * The template 'Style 2' to displaying related posts
 *
 * @package WordPress
 * @subpackage AUTOPARTS
 * @since AUTOPARTS 1.0
 */

$autoparts_link = get_permalink();
$autoparts_post_format = get_post_format();
$autoparts_post_format = empty($autoparts_post_format) ? 'standard' : str_replace('post-format-', '', $autoparts_post_format);
?><div id="post-<?php the_ID(); ?>" 
	<?php post_class( 'related_item related_item_style_2 post_format_'.esc_attr($autoparts_post_format) ); ?>><?php
	autoparts_show_post_featured(array(
		'thumb_size' => autoparts_get_thumb_size( (int) autoparts_get_theme_option('related_posts') == 1 ? 'huge' : 'extra' ),
		'show_no_image' => false,
		'singular' => false
		)
	);
	?><div class="post_header entry-header">
		<h6 class="post_title entry-title"><a href="<?php echo esc_url($autoparts_link); ?>"><?php echo the_title(); ?></a></h6><?php
		if ( in_array(get_post_type(), array( 'post', 'attachment' ) ) ) {
			?><span class="post_date"><a href="<?php echo esc_url($autoparts_link); ?>"><?php echo autoparts_get_date(); ?></a></span><?php
		}
		?><div class="related_excerpt"><?php echo mb_substr( strip_tags( get_the_excerpt() ), 0, 70 ).'...';?></div><?php
		?>
	</div>
</div>
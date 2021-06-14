<?php
/**
 * The Sticky template to display the sticky posts
 *
 * Used for index/archive
 *
 * @package WordPress
 * @subpackage AUTOPARTS
 * @since AUTOPARTS 1.0
 */

$autoparts_columns = max(1, min(3, count(get_option( 'sticky_posts' ))));
$autoparts_post_format = get_post_format();
$autoparts_post_format = empty($autoparts_post_format) ? 'standard' : str_replace('post-format-', '', $autoparts_post_format);
$autoparts_animation = autoparts_get_theme_option('blog_animation');

?><div class="column-1_<?php echo esc_attr($autoparts_columns); ?>"><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_sticky post_format_'.esc_attr($autoparts_post_format) ); ?>
	<?php echo (!autoparts_is_off($autoparts_animation) ? ' data-animation="'.esc_attr(autoparts_get_animation_classes($autoparts_animation)).'"' : ''); ?>
	>

	<?php
	if ( is_sticky() && is_home() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	// Featured image
	autoparts_show_post_featured(array(
		'thumb_size' => autoparts_get_thumb_size($autoparts_columns==1 ? 'big' : ($autoparts_columns==2 ? 'med' : 'avatar'))
	));

	if ( !in_array($autoparts_post_format, array('link', 'aside', 'status', 'quote')) ) {
		?>
		<div class="post_header entry-header">
			<?php
			// Post title
			the_title( sprintf( '<h6 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h6>' );
			// Post meta
			autoparts_show_post_meta(apply_filters('autoparts_filter_post_meta_args', array(), 'sticky', $autoparts_columns));
			?>
		</div><!-- .entry-header -->
		<?php
	}
	?>
</article></div>
<?php
/**
 * The Classic template to display the content
 *
 * Used for index/archive/search.
 *
 * @package WordPress
 * @subpackage AUTOPARTS
 * @since AUTOPARTS 1.0
 */

$autoparts_blog_style = explode('_', autoparts_get_theme_option('blog_style'));
$autoparts_columns = empty($autoparts_blog_style[1]) ? 2 : max(2, $autoparts_blog_style[1]);
$autoparts_expanded = !autoparts_sidebar_present() && autoparts_is_on(autoparts_get_theme_option('expand_content'));
$autoparts_post_format = get_post_format();
$autoparts_post_format = empty($autoparts_post_format) ? 'standard' : str_replace('post-format-', '', $autoparts_post_format);
$autoparts_animation = autoparts_get_theme_option('blog_animation');
$autoparts_components = autoparts_is_inherit(autoparts_get_theme_option_from_meta('meta_parts')) 
							? 'categories,date,counters'.($autoparts_columns < 3 ? ',edit' : '')
							: autoparts_array_get_keys_by_value(autoparts_get_theme_option('meta_parts'));
$autoparts_counters = autoparts_is_inherit(autoparts_get_theme_option_from_meta('counters')) 
							? 'comments'
							: autoparts_array_get_keys_by_value(autoparts_get_theme_option('counters'));

?><div class="<?php echo esc_attr($autoparts_blog_style[0] == 'classic' ? 'column' : 'masonry_item masonry_item'); ?>-1_<?php echo esc_attr($autoparts_columns); ?>"><article id="post-<?php the_ID(); ?>"
	<?php post_class( 'post_item post_format_'.esc_attr($autoparts_post_format)
					. ' post_layout_classic post_layout_classic_'.esc_attr($autoparts_columns)
					. ' post_layout_'.esc_attr($autoparts_blog_style[0]) 
					. ' post_layout_'.esc_attr($autoparts_blog_style[0]).'_'.esc_attr($autoparts_columns)
					); ?>
	<?php echo (!autoparts_is_off($autoparts_animation) ? ' data-animation="'.esc_attr(autoparts_get_animation_classes($autoparts_animation)).'"' : ''); ?>>
	<?php

	// Sticky label
	if ( is_sticky() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	// Featured image
	autoparts_show_post_featured( array( 'thumb_size' => autoparts_get_thumb_size($autoparts_blog_style[0] == 'classic'
													? (strpos(autoparts_get_theme_option('body_style'), 'full')!==false 
															? ( $autoparts_columns > 2 ? 'big' : 'huge' )
															: (	$autoparts_columns > 2
																? ($autoparts_expanded ? 'med' : 'small')
																: ($autoparts_expanded ? 'big' : 'med')
																)
														)
													: (strpos(autoparts_get_theme_option('body_style'), 'full')!==false 
															? ( $autoparts_columns > 2 ? 'masonry-big' : 'full' )
															: (	$autoparts_columns <= 2 && $autoparts_expanded ? 'masonry-big' : 'masonry')
														)
								) ) );

	if ( !in_array($autoparts_post_format, array('link', 'aside', 'status', 'quote')) ) {
		?>
		<div class="post_header entry-header">
			<?php 
			do_action('autoparts_action_before_post_title'); 

			// Post title
			the_title( sprintf( '<h4 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h4>' );

			do_action('autoparts_action_before_post_meta'); 

			// Post meta
			if (!empty($autoparts_components))
				autoparts_show_post_meta(apply_filters('autoparts_filter_post_meta_args', array(
					'components' => $autoparts_components,
					'counters' => $autoparts_counters,
					'seo' => false
					), $autoparts_blog_style[0], $autoparts_columns)
				);

			do_action('autoparts_action_after_post_meta'); 
			?>
		</div><!-- .entry-header -->
		<?php
	}		
	?>

	<div class="post_content entry-content">
		<div class="post_content_inner">
			<?php
			$autoparts_show_learn_more = false;
			if (has_excerpt()) {
				the_excerpt();
			} else if (strpos(get_the_content('!--more'), '!--more')!==false) {
				the_content( '' );
			} else if (in_array($autoparts_post_format, array('link', 'aside', 'status'))) {
				the_content();
			} else if ($autoparts_post_format == 'quote') {
				if (($quote = autoparts_get_tag(get_the_content(), '<blockquote>', '</blockquote>'))!='')
					autoparts_show_layout(wpautop($quote));
				else
					the_excerpt();
			} else if (substr(get_the_content(), 0, 1)!='[') {
				the_excerpt();
			}
			?>
		</div>
		<?php
		// Post meta
		if (in_array($autoparts_post_format, array('link', 'aside', 'status', 'quote'))) {
			if (!empty($autoparts_components))
				autoparts_show_post_meta(apply_filters('autoparts_filter_post_meta_args', array(
					'components' => $autoparts_components,
					'counters' => $autoparts_counters
					), $autoparts_blog_style[0], $autoparts_columns)
				);
		}
		// More button
		if ( $autoparts_show_learn_more ) {
			?><p><a class="more-link" href="<?php the_permalink(); ?>"><?php esc_html_e('Read more', 'autoparts'); ?></a></p><?php
		}
		?>
	</div><!-- .entry-content -->

</article></div>
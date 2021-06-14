<?php
/**
 * The default template to display the content
 *
 * Used for index/archive/search.
 *
 * @package WordPress
 * @subpackage AUTOPARTS
 * @since AUTOPARTS 1.0
 */

$autoparts_post_format = get_post_format();
$autoparts_post_format = empty($autoparts_post_format) ? 'standard' : str_replace('post-format-', '', $autoparts_post_format);
$autoparts_animation = autoparts_get_theme_option('blog_animation');

?><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_excerpt post_format_'.esc_attr($autoparts_post_format) ); ?>
	<?php echo (!autoparts_is_off($autoparts_animation) ? ' data-animation="'.esc_attr(autoparts_get_animation_classes($autoparts_animation)).'"' : ''); ?>
	><?php

	// Sticky label
	if ( is_sticky() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	// Featured image
	autoparts_show_post_featured(array( 'thumb_size' => autoparts_get_thumb_size( strpos(autoparts_get_theme_option('body_style'), 'full')!==false ? 'full' : 'big' ) ));

	// Title and post meta
	if (get_the_title() != '') {
		?>
		<div class="post_header entry-header">
			<?php
			do_action('autoparts_action_before_post_title'); 

			// Post title
			the_title( sprintf( '<h2 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );

			do_action('autoparts_action_before_post_meta'); 

			// Post meta
			$autoparts_components = autoparts_is_inherit(autoparts_get_theme_option_from_meta('meta_parts')) 
										? 'categories,date,counters,edit'
										: autoparts_array_get_keys_by_value(autoparts_get_theme_option('meta_parts'));
			$autoparts_counters = autoparts_is_inherit(autoparts_get_theme_option_from_meta('counters')) 
										? 'views,likes,comments'
										: autoparts_array_get_keys_by_value(autoparts_get_theme_option('counters'));

			if (!empty($autoparts_components))
				autoparts_show_post_meta(apply_filters('autoparts_filter_post_meta_args', array(
					'components' => $autoparts_components,
					'counters' => $autoparts_counters,
					'seo' => false
					), 'excerpt', 1)
				);
			?>
		</div><!-- .post_header --><?php
	}
	
	// Post content
	?><div class="post_content entry-content"><?php
		if (autoparts_get_theme_option('blog_content') == 'fullpost') {
			// Post content area
			?><div class="post_content_inner"><?php
				the_content( '' );
			?></div><?php
			// Inner pages
			wp_link_pages( array(
				'before'      => '<div class="page_links"><span class="page_links_title">' . esc_html__( 'Pages:', 'autoparts' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'autoparts' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			) );

		} else {

			$autoparts_show_learn_more = !in_array($autoparts_post_format, array('link', 'aside', 'status', 'quote'));

			// Post content area
			?><div class="post_content_inner"><?php
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
			?></div><?php
			// More button
			if ( $autoparts_show_learn_more ) {
				?><p><a class="more-link" href="<?php the_permalink(); ?>"><?php esc_html_e('Read more', 'autoparts'); ?></a></p><?php
			}

		}
	?></div><!-- .entry-content -->
</article>
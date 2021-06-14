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
$autoparts_columns = empty($autoparts_blog_style[1]) ? 1 : max(1, $autoparts_blog_style[1]);
$autoparts_expanded = !autoparts_sidebar_present() && autoparts_is_on(autoparts_get_theme_option('expand_content'));
$autoparts_post_format = get_post_format();
$autoparts_post_format = empty($autoparts_post_format) ? 'standard' : str_replace('post-format-', '', $autoparts_post_format);
$autoparts_animation = autoparts_get_theme_option('blog_animation');

?><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_chess post_layout_chess_'.esc_attr($autoparts_columns).' post_format_'.esc_attr($autoparts_post_format) ); ?>
	<?php echo (!autoparts_is_off($autoparts_animation) ? ' data-animation="'.esc_attr(autoparts_get_animation_classes($autoparts_animation)).'"' : ''); ?>>

	<?php
	// Add anchor
	if ($autoparts_columns == 1 && shortcode_exists('trx_sc_anchor')) {
		echo do_shortcode('[trx_sc_anchor id="post_'.esc_attr(get_the_ID()).'" title="'.the_title_attribute( array( 'echo' => false ) ).'"]');
	}

	// Sticky label
	if ( is_sticky() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	// Featured image
	autoparts_show_post_featured( array(
											'class' => $autoparts_columns == 1 ? 'trx-stretch-height' : '',
											'show_no_image' => true,
											'thumb_bg' => true,
											'thumb_size' => autoparts_get_thumb_size(
																	strpos(autoparts_get_theme_option('body_style'), 'full')!==false
																		? ( $autoparts_columns > 1 ? 'huge' : 'original' )
																		: (	$autoparts_columns > 2 ? 'big' : 'huge')
																	)
											) 
										);

	?><div class="post_inner"><div class="post_inner_content"><?php 

		?><div class="post_header entry-header"><?php 
			do_action('autoparts_action_before_post_title'); 

			// Post title
			the_title( sprintf( '<h3 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' );
			
			do_action('autoparts_action_before_post_meta'); 

			// Post meta
			$autoparts_components = autoparts_is_inherit(autoparts_get_theme_option_from_meta('meta_parts')) 
										? 'categories,date'.($autoparts_columns < 3 ? ',counters' : '').($autoparts_columns == 1 ? ',edit' : '')
										: autoparts_array_get_keys_by_value(autoparts_get_theme_option('meta_parts'));
			$autoparts_counters = autoparts_is_inherit(autoparts_get_theme_option_from_meta('counters')) 
										? 'comments'
										: autoparts_array_get_keys_by_value(autoparts_get_theme_option('counters'));
			$autoparts_post_meta = empty($autoparts_components) 
										? '' 
										: autoparts_show_post_meta(apply_filters('autoparts_filter_post_meta_args', array(
												'components' => $autoparts_components,
												'counters' => $autoparts_counters,
												'seo' => false,
												'echo' => false
												), $autoparts_blog_style[0], $autoparts_columns)
											);
			autoparts_show_layout($autoparts_post_meta);
		?></div><!-- .entry-header -->
	
		<div class="post_content entry-content">
			<div class="post_content_inner">
				<?php
				$autoparts_show_learn_more = !in_array($autoparts_post_format, array('link', 'aside', 'status', 'quote'));
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
					echo mb_substr( strip_tags( get_the_excerpt() ), 0, 120 ).'...';
				}
				?>
			</div>
			<?php
			// Post meta
			if (in_array($autoparts_post_format, array('link', 'aside', 'status', 'quote'))) {
				autoparts_show_layout($autoparts_post_meta);
			}
			// More button
			if ( $autoparts_show_learn_more ) {
				?><p class="more-link"><a href="<?php the_permalink(); ?>" class="sc_button sc_button_simple"><?php esc_html_e('Learn more', 'autoparts'); ?></a></p><?php
			}
			?>
		</div><!-- .entry-content -->

	</div></div><!-- .post_inner -->

</article>
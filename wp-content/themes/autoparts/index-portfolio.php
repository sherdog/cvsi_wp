<?php
/**
 * The template for homepage posts with "Portfolio" style
 *
 * @package WordPress
 * @subpackage AUTOPARTS
 * @since AUTOPARTS 1.0
 */

autoparts_storage_set('blog_archive', true);

// Load scripts for both 'Gallery' and 'Portfolio' layouts!
wp_enqueue_script( 'imagesloaded' );
wp_enqueue_script( 'masonry' );
wp_enqueue_script( 'classie', autoparts_get_file_url('js/theme.gallery/classie.min.js'), array(), null, true );
wp_enqueue_script( 'autoparts-gallery-script', autoparts_get_file_url('js/theme.gallery/theme.gallery.js'), array(), null, true );

get_header(); 

if (have_posts()) {

	echo get_query_var('blog_archive_start');

	$autoparts_stickies = is_home() ? get_option( 'sticky_posts' ) : false;
	$autoparts_sticky_out = autoparts_get_theme_option('sticky_style')=='columns' 
							&& is_array($autoparts_stickies) && count($autoparts_stickies) > 0 && get_query_var( 'paged' ) < 1;
	
	// Show filters
	$autoparts_cat = autoparts_get_theme_option('parent_cat');
	$autoparts_post_type = autoparts_get_theme_option('post_type');
	$autoparts_taxonomy = autoparts_get_post_type_taxonomy($autoparts_post_type);
	$autoparts_show_filters = autoparts_get_theme_option('show_filters');
	$autoparts_tabs = array();
	if (!autoparts_is_off($autoparts_show_filters)) {
		$autoparts_args = array(
			'type'			=> $autoparts_post_type,
			'child_of'		=> $autoparts_cat,
			'orderby'		=> 'name',
			'order'			=> 'ASC',
			'hide_empty'	=> 1,
			'hierarchical'	=> 0,
			'exclude'		=> '',
			'number'		=> '',
			'taxonomy'		=> $autoparts_taxonomy,
			'pad_counts'	=> false
		);
		$autoparts_portfolio_list = get_terms($autoparts_args);
		if (is_array($autoparts_portfolio_list) && count($autoparts_portfolio_list) > 0) {
			$autoparts_tabs[$autoparts_cat] = esc_html__('All', 'autoparts');
			foreach ($autoparts_portfolio_list as $autoparts_term) {
				if (isset($autoparts_term->term_id)) $autoparts_tabs[$autoparts_term->term_id] = $autoparts_term->name;
			}
		}
	}
	if (count($autoparts_tabs) > 0) {
		$autoparts_portfolio_filters_ajax = true;
		$autoparts_portfolio_filters_active = $autoparts_cat;
		$autoparts_portfolio_filters_id = 'portfolio_filters';
		if (!is_customize_preview())
			wp_enqueue_script('jquery-ui-tabs', false, array('jquery', 'jquery-ui-core'), null, true);
		?>
		<div class="portfolio_filters autoparts_tabs autoparts_tabs_ajax">
			<ul class="portfolio_titles autoparts_tabs_titles">
				<?php
				foreach ($autoparts_tabs as $autoparts_id=>$autoparts_title) {
					?><li><a href="<?php echo esc_url(autoparts_get_hash_link(sprintf('#%s_%s_content', $autoparts_portfolio_filters_id, $autoparts_id))); ?>" data-tab="<?php echo esc_attr($autoparts_id); ?>"><?php echo esc_html($autoparts_title); ?></a></li><?php
				}
				?>
			</ul>
			<?php
			$autoparts_ppp = autoparts_get_theme_option('posts_per_page');
			if (autoparts_is_inherit($autoparts_ppp)) $autoparts_ppp = '';
			foreach ($autoparts_tabs as $autoparts_id=>$autoparts_title) {
				$autoparts_portfolio_need_content = $autoparts_id==$autoparts_portfolio_filters_active || !$autoparts_portfolio_filters_ajax;
				?>
				<div id="<?php echo esc_attr(sprintf('%s_%s_content', $autoparts_portfolio_filters_id, $autoparts_id)); ?>"
					class="portfolio_content autoparts_tabs_content"
					data-blog-template="<?php echo esc_attr(autoparts_storage_get('blog_template')); ?>"
					data-blog-style="<?php echo esc_attr(autoparts_get_theme_option('blog_style')); ?>"
					data-posts-per-page="<?php echo esc_attr($autoparts_ppp); ?>"
					data-post-type="<?php echo esc_attr($autoparts_post_type); ?>"
					data-taxonomy="<?php echo esc_attr($autoparts_taxonomy); ?>"
					data-cat="<?php echo esc_attr($autoparts_id); ?>"
					data-parent-cat="<?php echo esc_attr($autoparts_cat); ?>"
					data-need-content="<?php echo (false===$autoparts_portfolio_need_content ? 'true' : 'false'); ?>"
				>
					<?php
					if ($autoparts_portfolio_need_content) 
						autoparts_show_portfolio_posts(array(
							'cat' => $autoparts_id,
							'parent_cat' => $autoparts_cat,
							'taxonomy' => $autoparts_taxonomy,
							'post_type' => $autoparts_post_type,
							'page' => 1,
							'sticky' => $autoparts_sticky_out
							)
						);
					?>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	} else {
		autoparts_show_portfolio_posts(array(
			'cat' => $autoparts_cat,
			'parent_cat' => $autoparts_cat,
			'taxonomy' => $autoparts_taxonomy,
			'post_type' => $autoparts_post_type,
			'page' => 1,
			'sticky' => $autoparts_sticky_out
			)
		);
	}

	echo get_query_var('blog_archive_end');

} else {

	if ( is_search() )
		get_template_part( 'content', 'none-search' );
	else
		get_template_part( 'content', 'none-archive' );

}

get_footer();
?>
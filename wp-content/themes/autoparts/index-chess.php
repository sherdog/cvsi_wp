<?php
/**
 * The template for homepage posts with "Chess" style
 *
 * @package WordPress
 * @subpackage AUTOPARTS
 * @since AUTOPARTS 1.0
 */

autoparts_storage_set('blog_archive', true);

get_header(); 

if (have_posts()) {

	echo get_query_var('blog_archive_start');

	$autoparts_stickies = is_home() ? get_option( 'sticky_posts' ) : false;
	$autoparts_sticky_out = autoparts_get_theme_option('sticky_style')=='columns' 
							&& is_array($autoparts_stickies) && count($autoparts_stickies) > 0 && get_query_var( 'paged' ) < 1;
	if ($autoparts_sticky_out) {
		?><div class="sticky_wrap columns_wrap"><?php	
	}
	if (!$autoparts_sticky_out) {
		?><div class="chess_wrap posts_container"><?php
	}
	while ( have_posts() ) { the_post(); 
		if ($autoparts_sticky_out && !is_sticky()) {
			$autoparts_sticky_out = false;
			?></div><div class="chess_wrap posts_container"><?php
		}
		get_template_part( 'content', $autoparts_sticky_out && is_sticky() ? 'sticky' :'chess' );
	}
	
	?></div><?php

	autoparts_show_pagination();

	echo get_query_var('blog_archive_end');

} else {

	if ( is_search() )
		get_template_part( 'content', 'none-search' );
	else
		get_template_part( 'content', 'none-archive' );

}

get_footer();
?>
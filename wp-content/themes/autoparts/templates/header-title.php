<?php
/**
 * The template to display the page title and breadcrumbs
 *
 * @package WordPress
 * @subpackage AUTOPARTS
 * @since AUTOPARTS 1.0
 */

// Page (category, tag, archive, author) title

if ( autoparts_need_page_title() ) {
	autoparts_sc_layouts_showed('title', true);
	autoparts_sc_layouts_showed('postmeta', false);
	?>
	<div class="top_panel_title sc_layouts_row sc_layouts_row_type_normal">
		<div class="content_wrap">
			<div class="sc_layouts_column sc_layouts_column_align_left">
				<div class="sc_layouts_item">
					<div class="sc_layouts_title sc_align_left">
						<?php
						// Post meta on the single post
						if ( false && is_single() )  {
							?><div class="sc_layouts_title_meta"><?php
								autoparts_show_post_meta(apply_filters('autoparts_filter_post_meta_args', array(
									'components' => 'categories,date,counters,edit',
									'counters' => 'views,comments,likes',
									'seo' => true
									), 'header', 1)
								);
							?></div><?php
						}
						
						// Blog/Post title
						?><div class="sc_layouts_title_title"><?php
							$autoparts_blog_title = autoparts_get_blog_title();
							$autoparts_blog_title_text = $autoparts_blog_title_class = $autoparts_blog_title_link = $autoparts_blog_title_link_text = '';
							if (is_array($autoparts_blog_title)) {
								$autoparts_blog_title_text = $autoparts_blog_title['text'];
								$autoparts_blog_title_class = !empty($autoparts_blog_title['class']) ? ' '.$autoparts_blog_title['class'] : '';
								$autoparts_blog_title_link = !empty($autoparts_blog_title['link']) ? $autoparts_blog_title['link'] : '';
								$autoparts_blog_title_link_text = !empty($autoparts_blog_title['link_text']) ? $autoparts_blog_title['link_text'] : '';
							} else
								$autoparts_blog_title_text = $autoparts_blog_title;
							?>
							<h1 class="sc_layouts_title_caption<?php echo esc_attr($autoparts_blog_title_class); ?>"><?php
								$autoparts_top_icon = autoparts_get_category_icon();
								if (!empty($autoparts_top_icon)) {
									$autoparts_attr = autoparts_getimagesize($autoparts_top_icon);
									?><img src="<?php echo esc_url($autoparts_top_icon); ?>" alt="img" <?php if (!empty($autoparts_attr[3])) autoparts_show_layout($autoparts_attr[3]);?>><?php
								}
								echo wp_kses($autoparts_blog_title_text ,'autoparts_kses_content');
							?></h1>
							<?php
							if (!empty($autoparts_blog_title_link) && !empty($autoparts_blog_title_link_text)) {
								?><a href="<?php echo esc_url($autoparts_blog_title_link); ?>" class="theme_button theme_button_small sc_layouts_title_link"><?php echo esc_html($autoparts_blog_title_link_text); ?></a><?php
							}
							
							// Category/Tag description
							if ( is_category() || is_tag() || is_tax() ) 
								the_archive_description( '<div class="sc_layouts_title_description">', '</div>' );
		
						?></div><?php
	
						// Breadcrumbs
						?><div class="sc_layouts_title_breadcrumbs"><?php
							do_action( 'autoparts_action_breadcrumbs');
						?></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}
?>
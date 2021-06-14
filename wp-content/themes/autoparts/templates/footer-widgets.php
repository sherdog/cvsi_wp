<?php
/**
 * The template to display the widgets area in the footer
 *
 * @package WordPress
 * @subpackage AUTOPARTS
 * @since AUTOPARTS 1.0.10
 */

// Footer sidebar
$autoparts_footer_name = autoparts_get_theme_option('footer_widgets');
$autoparts_footer_present = !autoparts_is_off($autoparts_footer_name) && is_active_sidebar($autoparts_footer_name);
if ($autoparts_footer_present) { 
	autoparts_storage_set('current_sidebar', 'footer');
	$autoparts_footer_wide = autoparts_get_theme_option('footer_wide');
	ob_start();
	if ( is_active_sidebar($autoparts_footer_name) ) {
		dynamic_sidebar($autoparts_footer_name);
	}
	$autoparts_out = trim(ob_get_contents());
	ob_end_clean();
	if (!empty($autoparts_out)) {
		$autoparts_out = preg_replace("/<\\/aside>[\r\n\s]*<aside/", "</aside><aside", $autoparts_out);
		$autoparts_need_columns = true;
		if ($autoparts_need_columns) {
			$autoparts_columns = max(0, (int) autoparts_get_theme_option('footer_columns'));
			if ($autoparts_columns == 0) $autoparts_columns = min(4, max(1, substr_count($autoparts_out, '<aside ')));
			if ($autoparts_columns > 1)
				$autoparts_out = preg_replace("/class=\"widget /", "class=\"column-1_".esc_attr($autoparts_columns).' widget ', $autoparts_out);
			else
				$autoparts_need_columns = false;
		}
		?>
		<div class="footer_widgets_wrap widget_area<?php echo !empty($autoparts_footer_wide) ? ' footer_fullwidth' : ''; ?> sc_layouts_row  sc_layouts_row_type_normal">
			<div class="footer_widgets_inner widget_area_inner">
				<?php 
				if (!$autoparts_footer_wide) { 
					?><div class="content_wrap"><?php
				}
				if ($autoparts_need_columns) {
					?><div class="columns_wrap"><?php
				}
				do_action( 'autoparts_action_before_sidebar' );
				autoparts_show_layout($autoparts_out);
				do_action( 'autoparts_action_after_sidebar' );
				if ($autoparts_need_columns) {
					?></div><!-- /.columns_wrap --><?php
				}
				if (!$autoparts_footer_wide) {
					?></div><!-- /.content_wrap --><?php
				}
				?>
			</div><!-- /.footer_widgets_inner -->
		</div><!-- /.footer_widgets_wrap -->
		<?php
	}
}
?>
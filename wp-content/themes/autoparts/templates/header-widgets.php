<?php
/**
 * The template to display the widgets area in the header
 *
 * @package WordPress
 * @subpackage AUTOPARTS
 * @since AUTOPARTS 1.0
 */

// Header sidebar
$autoparts_header_name = autoparts_get_theme_option('header_widgets');
$autoparts_header_present = !autoparts_is_off($autoparts_header_name) && is_active_sidebar($autoparts_header_name);
if ($autoparts_header_present) { 
	autoparts_storage_set('current_sidebar', 'header');
	$autoparts_header_wide = autoparts_get_theme_option('header_wide');
	ob_start();
	if ( is_active_sidebar($autoparts_header_name) ) {
		dynamic_sidebar($autoparts_header_name);
	}
	$autoparts_widgets_output = ob_get_contents();
	ob_end_clean();
	if (!empty($autoparts_widgets_output)) {
		$autoparts_widgets_output = preg_replace("/<\/aside>[\r\n\s]*<aside/", "</aside><aside", $autoparts_widgets_output);
		$autoparts_need_columns = strpos($autoparts_widgets_output, 'columns_wrap')===false;
		if ($autoparts_need_columns) {
			$autoparts_columns = max(0, (int) autoparts_get_theme_option('header_columns'));
			if ($autoparts_columns == 0) $autoparts_columns = min(6, max(1, substr_count($autoparts_widgets_output, '<aside ')));
			if ($autoparts_columns > 1)
				$autoparts_widgets_output = preg_replace("/class=\"widget /", "class=\"column-1_".esc_attr($autoparts_columns).' widget ', $autoparts_widgets_output);
			else
				$autoparts_need_columns = false;
		}
		?>
		<div class="header_widgets_wrap widget_area<?php echo !empty($autoparts_header_wide) ? ' header_fullwidth' : ' header_boxed'; ?>">
			<div class="header_widgets_inner widget_area_inner">
				<?php 
				if (!$autoparts_header_wide) { 
					?><div class="content_wrap"><?php
				}
				if ($autoparts_need_columns) {
					?><div class="columns_wrap"><?php
				}
				do_action( 'autoparts_action_before_sidebar' );
				autoparts_show_layout($autoparts_widgets_output);
				do_action( 'autoparts_action_after_sidebar' );
				if ($autoparts_need_columns) {
					?></div>	<!-- /.columns_wrap --><?php
				}
				if (!$autoparts_header_wide) {
					?></div>	<!-- /.content_wrap --><?php
				}
				?>
			</div>	<!-- /.header_widgets_inner -->
		</div>	<!-- /.header_widgets_wrap -->
		<?php
	}
}
?>
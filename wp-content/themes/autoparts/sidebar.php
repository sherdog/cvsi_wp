<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package WordPress
 * @subpackage AUTOPARTS
 * @since AUTOPARTS 1.0
 */

$autoparts_sidebar_position = autoparts_get_theme_option('sidebar_position');
if (autoparts_sidebar_present()) {
	ob_start();
	$autoparts_sidebar_name = autoparts_get_theme_option('sidebar_widgets');
	autoparts_storage_set('current_sidebar', 'sidebar');
	if ( is_active_sidebar($autoparts_sidebar_name) ) {
		dynamic_sidebar($autoparts_sidebar_name);
	}
	$autoparts_out = trim(ob_get_contents());
	ob_end_clean();
	if (!empty($autoparts_out)) {
		?>
		<div class="sidebar <?php echo esc_attr($autoparts_sidebar_position); ?> widget_area<?php if (!autoparts_is_inherit(autoparts_get_theme_option('sidebar_scheme'))) echo ' scheme_'.esc_attr(autoparts_get_theme_option('sidebar_scheme')); ?>" role="complementary">
			<div class="sidebar_inner">
				<?php
				do_action( 'autoparts_action_before_sidebar' );
				autoparts_show_layout(preg_replace("/<\/aside>[\r\n\s]*<aside/", "</aside><aside", $autoparts_out));
				do_action( 'autoparts_action_after_sidebar' );
				?>
			</div><!-- /.sidebar_inner -->
		</div><!-- /.sidebar -->
		<?php
	}
}
?>
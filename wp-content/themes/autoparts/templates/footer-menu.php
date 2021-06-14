<?php
/**
 * The template to display menu in the footer
 *
 * @package WordPress
 * @subpackage AUTOPARTS
 * @since AUTOPARTS 1.0.10
 */

// Footer menu
$autoparts_menu_footer = autoparts_get_nav_menu(array(
											'location' => 'menu_footer',
											'class' => 'sc_layouts_menu sc_layouts_menu_default'
											));
if (!empty($autoparts_menu_footer)) {
	?>
	<div class="footer_menu_wrap">
		<div class="footer_menu_inner">
			<?php autoparts_show_layout($autoparts_menu_footer); ?>
		</div>
	</div>
	<?php
}
?>
<?php
/**
 * The template to display the socials in the footer
 *
 * @package WordPress
 * @subpackage AUTOPARTS
 * @since AUTOPARTS 1.0.10
 */


// Socials
if ( autoparts_is_on(autoparts_get_theme_option('socials_in_footer')) && ($autoparts_output = autoparts_get_socials_links()) != '') {
	?>
	<div class="footer_socials_wrap socials_wrap">
		<div class="footer_socials_inner">
			<?php autoparts_show_layout($autoparts_output); ?>
		</div>
	</div>
	<?php
}
?>
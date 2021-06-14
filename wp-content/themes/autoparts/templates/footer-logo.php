<?php
/**
 * The template to display the site logo in the footer
 *
 * @package WordPress
 * @subpackage AUTOPARTS
 * @since AUTOPARTS 1.0.10
 */

// Logo
if (autoparts_is_on(autoparts_get_theme_option('logo_in_footer'))) {
	$autoparts_logo_image = '';
	if (autoparts_get_retina_multiplier(2) > 1)
		$autoparts_logo_image = autoparts_get_theme_option( 'logo_footer_retina' );
	if (empty($autoparts_logo_image)) 
		$autoparts_logo_image = autoparts_get_theme_option( 'logo_footer' );
	$autoparts_logo_text   = get_bloginfo( 'name' );
	if (!empty($autoparts_logo_image) || !empty($autoparts_logo_text)) {
		?>
		<div class="footer_logo_wrap">
			<div class="footer_logo_inner">
				<?php
				if (!empty($autoparts_logo_image)) {
					$autoparts_attr = autoparts_getimagesize($autoparts_logo_image);
					echo '<a href="'.esc_url(home_url('/')).'"><img src="'.esc_url($autoparts_logo_image).'" class="logo_footer_image" alt="img"'.(!empty($autoparts_attr[3]) ? sprintf(' %s', $autoparts_attr[3]) : '').'></a>' ;
				} else if (!empty($autoparts_logo_text)) {
					echo '<h1 class="logo_footer_text"><a href="'.esc_url(home_url('/')).'">' . esc_html($autoparts_logo_text) . '</a></h1>';
				}
				?>
			</div>
		</div>
		<?php
	}
}
?>
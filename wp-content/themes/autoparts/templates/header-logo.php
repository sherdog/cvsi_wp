<?php
/**
 * The template to display the logo or the site name and the slogan in the Header
 *
 * @package WordPress
 * @subpackage AUTOPARTS
 * @since AUTOPARTS 1.0
 */

$autoparts_args = get_query_var('autoparts_logo_args');

// Site logo
$autoparts_logo_image  = autoparts_get_logo_image(isset($autoparts_args['type']) ? $autoparts_args['type'] : '');
$autoparts_logo_text   = autoparts_is_on(autoparts_get_theme_option('logo_text')) ? get_bloginfo( 'name' ) : '';
$autoparts_logo_slogan = get_bloginfo( 'description', 'display' );
if (!empty($autoparts_logo_image) || !empty($autoparts_logo_text)) {
	?><a class="sc_layouts_logo" href="<?php echo is_front_page() ? '#' : esc_url(home_url('/')); ?>"><?php
		if (!empty($autoparts_logo_image)) {
			$autoparts_attr = autoparts_getimagesize($autoparts_logo_image);
			echo '<img src="'.esc_url($autoparts_logo_image).'" alt="img"'.(!empty($autoparts_attr[3]) ? sprintf(' %s', $autoparts_attr[3]) : '').'>' ;
		} else {
			autoparts_show_layout(autoparts_prepare_macros($autoparts_logo_text), '<span class="logo_text">', '</span>');
			autoparts_show_layout(autoparts_prepare_macros($autoparts_logo_slogan), '<span class="logo_slogan">', '</span>');
		}
	?></a><?php
}
?>
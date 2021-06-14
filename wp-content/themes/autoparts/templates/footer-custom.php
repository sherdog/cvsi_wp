<?php
/**
 * The template to display default site footer
 *
 * @package WordPress
 * @subpackage AUTOPARTS
 * @since AUTOPARTS 1.0.10
 */

$autoparts_footer_scheme =  autoparts_is_inherit(autoparts_get_theme_option('footer_scheme')) ? autoparts_get_theme_option('color_scheme') : autoparts_get_theme_option('footer_scheme');
$autoparts_footer_id = str_replace('footer-custom-', '', autoparts_get_theme_option("footer_style"));
if ((int) $autoparts_footer_id == 0) {
	$autoparts_footer_id = autoparts_get_post_id(array(
			'name' => $autoparts_footer_id,
			'post_type' => defined('TRX_ADDONS_CPT_LAYOUTS_PT') ? TRX_ADDONS_CPT_LAYOUTS_PT : 'cpt_layouts'
		)
	);
} else {
	$autoparts_footer_id = apply_filters('trx_addons_filter_get_translated_layout', $autoparts_footer_id);
}
$autoparts_footer_meta = get_post_meta($autoparts_footer_id, 'trx_addons_options', true);
?>
<footer class="footer_wrap footer_custom footer_custom_<?php echo esc_attr($autoparts_footer_id); 
						?> footer_custom_<?php echo esc_attr(sanitize_title(get_the_title($autoparts_footer_id))); 
						if (!empty($autoparts_footer_meta['margin']) != '') 
							echo ' '.esc_attr(autoparts_add_inline_css_class('margin-top: '.esc_attr(autoparts_prepare_css_value($autoparts_footer_meta['margin'])).';'));
						?> scheme_<?php echo esc_attr($autoparts_footer_scheme); 
						?>">
	<?php
    // Custom footer's layout
    do_action('autoparts_action_show_layout', $autoparts_footer_id);
	?>
</footer><!-- /.footer_wrap -->

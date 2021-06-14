<?php
/**
 * The style "default" of the Widget "Audio"
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.6.10
 */

$args = get_query_var('trx_addons_args_theme_activation');
if (is_array($args)) {
	extract($args);
}
?>
<div class="notice-success notice">
	<p><strong><?php echo esc_html__('Congratulations! Your theme is activated successfully.', 'trx_addons'); ?></strong></p>
</div>

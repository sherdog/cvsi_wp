<?php
/**
 * Theme is not active, so show activation form
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 */

$args = get_query_var('trx_addons_args_theme_activation');
extract($args);

?>
<div class="notice-warning notice trx_addons_theme_panel_theme_<?php echo esc_attr($theme_status); ?>" id="trx_addons_admin_notice">
	<h3 class="trx_addons_notice_title"><?php echo sprintf(esc_html__('Activate your theme %s', 'trx_addons'), wp_get_theme()->name); ?></h3>
	<div class="trx_addons_theme_panel_section_description">
		<p><?php esc_html_e('Thank you for your awesome taste and choosing our theme!', 'trx_addons'); ?></p>
		<p><?php esc_html_e('Please activate your copy of the theme in order to get access to plugins, demo content, support and updates', 'trx_addons'); ?></p>
	</div>
	<form action="<?php echo esc_url( add_query_arg( array('action' => 'trx_addons_theme_activation') ) ); ?>" class="trx_addons_theme_panel_section_form" name="trx_addons_theme_panel_activate_form" method="post">
		<input type="hidden" name="trx_addons_nonce" value="<?php echo esc_attr(wp_create_nonce(admin_url())); ?>" />
		<div class="trx_addons_theme_panel_section_form_field">
			<label>
				<span class="trx_addons_theme_panel_section_form_field_label"><?php esc_attr_e('Name:', 'trx_addons'); ?></span>
				<input type="text" name="trx_addons_user_name" placeholder="<?php esc_attr_e('Your name', 'trx_addons'); ?>">
			</label>
		</div>
		<div class="trx_addons_theme_panel_section_form_field">
			<label>
				<span class="trx_addons_theme_panel_section_form_field_label"><?php esc_attr_e('E-mail:', 'trx_addons'); ?></span>
				<input type="text" name="trx_addons_user_email" placeholder="<?php esc_attr_e('Your e-mail', 'trx_addons'); ?>">
			</label>
		</div>
		<div class="trx_addons_theme_panel_section_form_field">
			<label>
				<span class="trx_addons_theme_panel_section_form_field_label"><?php esc_attr_e('Purchase code', 'trx_addons'); ?> <sup class="required">*</sup></span>
				<input type="text" name="trx_addons_activate_theme_code" required placeholder="<?php esc_attr_e('Purchase code (required)', 'trx_addons'); ?>">
			</label>
			<span class="trx_addons_theme_panel_section_form_field_description">
					<?php
					echo esc_html__( "Can't find the purchase code?", 'trx_addons' )
						. ' '
						. '<a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-" target="_blank">'
						. esc_html__('Follow this guide', 'trx_addons')
						. '</a>';
					?>
				</span>
		</div>
		<div class="trx_addons_theme_panel_section_form_field">
			<label>
				<input type="checkbox" name="trx_addons_user_agree" checked="checked" value="1">
				<span class="trx_addons_theme_panel_section_form_field_label"><?php
					echo sprintf( wp_kses_post(__('Your data is stored and processed in accordance with our "%s"', 'trx_addons')),
						'<a href="' . apply_filters('trx_addons_filter_privacy_url', 'https://themerex.net/privacy-policy/') . '" target="_blank">' . esc_html__('Privacy Policy', 'trx_addons') . '</a>');
					?></span>
			</label>
		</div>
		<div class="trx_addons_theme_panel_section_form_field">
			<input type="submit" class="button button-primary" value="<?php esc_attr_e('Submit', 'trx_addons'); ?>">
			<a href="#" class="button trx_addons_hide_notice"><i class="dashicons dashicons-dismiss"></i> <?php esc_html_e('Hide Notice', 'trx_addons'); ?></a>
		</div>
		<?php if (!empty($errors)): ?>
		<ul class="trx_addons_theme_panel_section_form_errors">
			<?php
			foreach ($errors as $error) {
				echo "<li>" . wp_kses_post($error) . "</li>";
			}
			?>
		</ul>
		<?php endif;?>
	</form>
</div>
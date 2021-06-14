<?php
/**
 * The template to display Admin notices
 *
 * @package WordPress
 * @subpackage AUTOPARTS
 * @since AUTOPARTS 1.0.1
 */
?>
<div class="update-nag" id="autoparts_admin_notice">
	<h3 class="autoparts_notice_title"><?php echo sprintf(esc_html__('Welcome to %s', 'autoparts'), wp_get_theme()->name); ?></h3>
	<?php
	if (!autoparts_exists_trx_addons()) {
		?><p><?php echo wp_kses_data(__('<b>Attention!</b> Plugin "ThemeREX Addons is required! Please, install and activate it!', 'autoparts')); ?></p><?php
	}
	?><p><?php
		if (autoparts_get_value_gp('page')!='tgmpa-install-plugins') {
			?>
			<a href="<?php echo esc_url(admin_url().'themes.php?page=tgmpa-install-plugins'); ?>" class="button-primary"><i class="dashicons dashicons-admin-plugins"></i> <?php esc_html_e('Install plugins', 'autoparts'); ?></a>
			<?php
		}
		if (function_exists('autoparts_exists_trx_addons') && autoparts_exists_trx_addons() && autoparts_trx_addons_is_theme_activated()) {
			?>
			<a href="<?php echo esc_url(admin_url().'themes.php?page=trx_importer'); ?>" class="button-primary"><i class="dashicons dashicons-download"></i> <?php esc_html_e('One Click Demo Data', 'autoparts'); ?></a>
			<?php
		}
		?>
        <a href="<?php echo esc_url(admin_url().'customize.php'); ?>" class="button-primary"><i class="dashicons dashicons-admin-appearance"></i> <?php esc_html_e('Theme Customizer', 'autoparts'); ?></a>
        <a href="#" class="button autoparts_hide_notice"><i class="dashicons dashicons-dismiss"></i> <?php esc_html_e('Hide Notice', 'autoparts'); ?></a>
	</p>
</div>
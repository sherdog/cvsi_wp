<?php
/**
 * The Footer: widgets area, logo, footer menu and socials
 *
 * @package WordPress
 * @subpackage AUTOPARTS
 * @since AUTOPARTS 1.0
 */

						// Widgets area inside page content
						autoparts_create_widgets_area('widgets_below_content');
						?>				
					</div><!-- </.content> -->

					<?php
					// Show main sidebar
					get_sidebar();

					$autoparts_body_style = autoparts_get_theme_option('body_style');
					if ($autoparts_body_style != 'fullscreen') {
						?></div><!-- </.content_wrap> --><?php
					}
					?>

					<div class="content_container">
						<?php
						// Widgets area below page content
						autoparts_create_widgets_area('widgets_below_page');
						?>
					</div>

			</div><!-- </.page_content_wrap> -->

			<?php
			// Footer
			$autoparts_footer_style = autoparts_get_theme_option("footer_style");
			if (strpos($autoparts_footer_style, 'footer-custom-')===0) $autoparts_footer_style = 'footer-custom';
			get_template_part( "templates/{$autoparts_footer_style}");
			?>

		</div><!-- /.page_wrap -->

	</div><!-- /.body_wrap -->

	<?php if (autoparts_is_on(autoparts_get_theme_option('debug_mode')) && autoparts_get_file_dir('images/makeup.jpg')!='') { ?>
		<img src="<?php echo esc_url(autoparts_get_file_url('images/makeup.jpg')); ?>" id="makeup">
	<?php } ?>

	<?php wp_footer(); ?>

</body>
</html>
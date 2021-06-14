<?php
/**
 * The template to display the main menu
 *
 * @package WordPress
 * @subpackage AUTOPARTS
 * @since AUTOPARTS 1.0
 */
?>
<div class="top_panel_navi sc_layouts_row sc_layouts_row_type_narrow sc_layouts_row_fixed
			scheme_<?php echo esc_attr(autoparts_is_inherit(autoparts_get_theme_option('menu_scheme')) 
												? (autoparts_is_inherit(autoparts_get_theme_option('header_scheme')) 
													? autoparts_get_theme_option('color_scheme') 
													: autoparts_get_theme_option('header_scheme')) 
												: autoparts_get_theme_option('menu_scheme')); ?>">
	<div class="content_wrap_header_default">
		<div class="columns_wrap">
			<div class="sc_layouts_column sc_layouts_column_align_left sc_layouts_column_icons_position_left column-3_4">
				<?php
				// Logo
				?><div class="sc_layouts_item"><?php
					get_template_part( 'templates/header-logo' );
				?></div>


				<div class="sc_layouts_item">
					<?php
					// Main menu
					$autoparts_menu_main = autoparts_get_nav_menu(array(
							'location' => 'menu_main',
							'class' => 'sc_layouts_menu sc_layouts_menu_default sc_layouts_hide_on_mobile'
						)
					);
					if (empty($autoparts_menu_main)) {
						$autoparts_menu_main = autoparts_get_nav_menu(array(
								'class' => 'sc_layouts_menu sc_layouts_menu_default sc_layouts_hide_on_mobile'
							)
						);
					}
					autoparts_show_layout($autoparts_menu_main);
					// Mobile menu button
					?>
					<div class="sc_layouts_iconed_text sc_layouts_menu_mobile_button">
						<a class="sc_layouts_item_link sc_layouts_iconed_text_link" href="#">
							<span class="sc_layouts_item_icon sc_layouts_iconed_text_icon trx_addons_icon-menu"></span>
						</a>
					</div>
				</div>
			</div><?php
			$phone_top = autoparts_get_theme_option('phone_top');
			if(isset($phone_top) && !empty($phone_top)){
			?><div class="sc_layouts_column sc_layouts_column_align_right sc_layouts_column_icons_position_left column-1_4">
					<span class="default_header_phone"><?php autoparts_show_layout($phone_top);?></span>
				</div>
				<?php
				}
				?>
		</div><!-- /.sc_layouts_row -->
	</div><!-- /.content_wrap -->
</div><!-- /.top_panel_navi -->
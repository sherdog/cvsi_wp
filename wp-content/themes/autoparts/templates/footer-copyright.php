<?php
/**
 * The template to display the copyright info in the footer
 *
 * @package WordPress
 * @subpackage AUTOPARTS
 * @since AUTOPARTS 1.0.10
 */

// Copyright area
$autoparts_footer_scheme =  autoparts_is_inherit(autoparts_get_theme_option('footer_scheme')) ? autoparts_get_theme_option('color_scheme') : autoparts_get_theme_option('footer_scheme');
$autoparts_copyright_scheme = autoparts_is_inherit(autoparts_get_theme_option('copyright_scheme')) ? $autoparts_footer_scheme : autoparts_get_theme_option('copyright_scheme');
?> 
<div class="footer_copyright_wrap scheme_<?php echo esc_attr($autoparts_copyright_scheme); ?>">
	<div class="footer_copyright_inner">
		<div class="content_wrap">
			<div class="copyright_text"><?php
				// Replace {{...}} and [[...]] on the <i>...</i> and <b>...</b>
				$autoparts_copyright = autoparts_prepare_macros(autoparts_get_theme_option('copyright'));
				if (!empty($autoparts_copyright)) {
					// Replace {date_format} on the current date in the specified format
					if (preg_match("/(\\{[\\w\\d\\\\\\-\\:]*\\})/", $autoparts_copyright, $autoparts_matches)) {
						$autoparts_copyright = str_replace($autoparts_matches[1], date(str_replace(array('{', '}'), '', $autoparts_matches[1])), $autoparts_copyright);
                        $autoparts_copyright = str_replace(array('{{Y}}', '{Y}'), date('Y'), $autoparts_copyright);
					}
					// Display copyright
					echo wp_kses_data(nl2br($autoparts_copyright));
				}
			?></div>
		</div>
	</div>
</div>

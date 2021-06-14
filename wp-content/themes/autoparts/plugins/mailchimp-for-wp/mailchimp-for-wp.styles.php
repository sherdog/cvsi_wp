<?php
// Add plugin-specific colors and fonts to the custom CSS
if (!function_exists('autoparts_mailchimp_get_css')) {
	add_filter('autoparts_filter_get_css', 'autoparts_mailchimp_get_css', 10, 4);
	function autoparts_mailchimp_get_css($css, $colors, $fonts, $scheme='') {
		
		if (isset($css['fonts']) && $fonts) {
			$css['fonts'] .= <<<CSS

CSS;
		
			
			$rad = autoparts_get_border_radius();
			$css['fonts'] .= <<<CSS


CSS;
		}

		
		if (isset($css['colors']) && $colors) {
			$css['colors'] .= <<<CSS

.mc4wp-form input[type="email"],
.vc_row.scheme_self .mc4wp-form input[type="email"] {
	background-color: {$colors['input_light']};
	border-color: {$colors['input_bd_color']};
	color: {$colors['input_text']};
}

.mc4wp-form .mc4wp-alert {
	background-color: {$colors['bg_color']};
	border-color: {$colors['text_link2']};
	color: {$colors['input_text']};
}
.mc4wp-form .mc4wp-form-fields input[type="submit"]{
	border-color: {$colors['text_link2']};
}

.rev_slider .mc4wp-form .mc4wp-form-fields input[type="submit"]:hover,
.revslider-initialised .mc4wp-form .mc4wp-form-fields input[type="submit"]:hover{
	background-color: {$colors['text_link3']};
}

.rev_slider .mc4wp-form input[type="email"],
.revslider-initialised .mc4wp-form input[type="email"] {
	color: {$colors['inverse_text']};
}

CSS;
		}

		return $css;
	}
}
?>
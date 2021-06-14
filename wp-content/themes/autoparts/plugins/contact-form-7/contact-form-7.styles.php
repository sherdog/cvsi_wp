<?php
// Add plugin-specific colors and fonts to the custom CSS
if ( !function_exists( 'autoparts_contact_form_7_get_css' ) ) {
	add_filter( 'autoparts_filter_get_css', 'autoparts_contact_form_7_get_css', 10, 4 );
	function autoparts_contact_form_7_get_css($css, $colors, $fonts, $scheme='') {
		if (isset($css['fonts']) && $fonts) {
			$css['fonts'] .= <<<CSS
			
.callback_form {
	{$fonts['p_font-family']}
}

CSS;
		}

		if (isset($css['colors']) && $colors) {
			$css['colors'] .= <<<CSS

/* Contact Form 7 */
.callback_form input[type="submit"] {
	background-color: {$colors['text_link3']};
	color: {$colors['text']};
}

CSS;
		}
		
		return $css;
	}
}
?>
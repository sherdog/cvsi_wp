<?php
/**
 * Setup theme-specific fonts and colors
 *
 * @package WordPress
 * @subpackage AUTOPARTS
 * @since AUTOPARTS 1.0.22
 */

// Theme init priorities:
// 1 - register filters to add/remove lists items in the Theme Options
// 2 - create Theme Options
// 3 - add/remove Theme Options elements
// 5 - load Theme Options
// 9 - register other filters (for installer, etc.)
//10 - standard Theme init procedures (not ordered)
if ( !function_exists('autoparts_customizer_theme_setup1') ) {
	add_action( 'after_setup_theme', 'autoparts_customizer_theme_setup1', 1 );
	function autoparts_customizer_theme_setup1() {
		
		// -----------------------------------------------------------------
		// -- Theme fonts (Google and/or custom fonts)
		// -----------------------------------------------------------------
		
		// Fonts to load when theme start
		// It can be Google fonts or uploaded fonts, placed in the folder /css/font-face/font-name inside the theme folder
		// Attention! Font's folder must have name equal to the font's name, with spaces replaced on the dash '-'
		
		autoparts_storage_set('load_fonts', array(
			// Google font
			array(
				'name'	 => 'Ubuntu',
				'family' => 'sans-serif',
				'styles' => '300,300italic,400,400italic,700,700italic'		// Parameter 'style' used only for the Google fonts
			),
			// Font-face packed with theme
			array(
				'name'   => 'Montserrat',
				'family' => 'sans-serif'
				)
		));
		
		// Characters subset for the Google fonts. Available values are: latin,latin-ext,cyrillic,cyrillic-ext,greek,greek-ext,vietnamese
		autoparts_storage_set('load_fonts_subset', 'latin,latin-ext');
		
		// Settings of the main tags
		autoparts_storage_set('theme_fonts', array(
			'p' => array(
				'title'				=> esc_html__('Main text', 'autoparts'),
				'description'		=> esc_html__('Font settings of the main text of the site', 'autoparts'),
				'font-family'		=> 'Montserrat, sans-serif',
				'font-size' 		=> '1rem',
				'font-weight'		=> '400',
				'font-style'		=> 'normal',
				'line-height'		=> '1.7143em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '',
				'margin-top'		=> '0em',
				'margin-bottom'		=> '1.4em'
				),
			'h1' => array(
				'title'				=> esc_html__('Heading 1', 'autoparts'),
				'font-family'		=> 'Montserrat, sans-serif',
				'font-size' 		=> '4.571em',
				'font-weight'		=> '700',
				'font-style'		=> 'normal',
				'line-height'		=> '1em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'uppercase',
				'letter-spacing'	=> '0',
				'margin-top'		=> '1em',
				'margin-bottom'		=> '0.425em'
				),
			'h2' => array(
				'title'				=> esc_html__('Heading 2', 'autoparts'),
				'font-family'		=> 'Montserrat, sans-serif',
				'font-size' 		=> '3.571em',
				'font-weight'		=> '700',
				'font-style'		=> 'normal',
				'line-height'		=> '1.2em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'uppercase',
				'letter-spacing'	=> '0',
				'margin-top'		=> '1.2em',
				'margin-bottom'		=> '0.45em'
				),
			'h3' => array(
				'title'				=> esc_html__('Heading 3', 'autoparts'),
				'font-family'		=> 'Montserrat, sans-serif',
				'font-size' 		=> '2.714em',
				'font-weight'		=> '700',
				'font-style'		=> 'normal',
				'line-height'		=> '1.263em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'uppercase',
				'letter-spacing'	=> '0',
				'margin-top'		=> '1.6em',
				'margin-bottom'		=> '0.65em'
				),
			'h4' => array(
				'title'				=> esc_html__('Heading 4', 'autoparts'),
				'font-family'		=> 'Montserrat, sans-serif',
				'font-size' 		=> '2em',
				'font-weight'		=> '700',
				'font-style'		=> 'normal',
				'line-height'		=> '1.4286em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'uppercase',
				'letter-spacing'	=> '0',
				'margin-top'		=> '1.9565em',
				'margin-bottom'		=> '0.7em'
				),
			'h5' => array(
				'title'				=> esc_html__('Heading 5', 'autoparts'),
				'font-family'		=> 'Montserrat, sans-serif',
				'font-size' 		=> '1.857em',
				'font-weight'		=> '400',
				'font-style'		=> 'normal',
				'line-height'		=> '1.3em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '0px',
				'margin-top'		=> '2em',
				'margin-bottom'		=> '0.98em'
				),
			'h6' => array(
				'title'				=> esc_html__('Heading 6', 'autoparts'),
				'font-family'		=> 'Montserrat, sans-serif',
				'font-size' 		=> '1.571em',
				'font-weight'		=> '400',
				'font-style'		=> 'normal',
				'line-height'		=> '1.364em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '0px',
				'margin-top'		=> '2.085em',
				'margin-bottom'		=> '0.9412em'
				),
			'logo' => array(
				'title'				=> esc_html__('Logo text', 'autoparts'),
				'description'		=> esc_html__('Font settings of the text case of the logo', 'autoparts'),
				'font-family'		=> 'Montserrat, sans-serif',
				'font-size' 		=> '1.8em',
				'font-weight'		=> '400',
				'font-style'		=> 'normal',
				'line-height'		=> '1.25em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'uppercase',
				'letter-spacing'	=> '1px'
				),
			'button' => array(
				'title'				=> esc_html__('Buttons', 'autoparts'),
				'font-family'		=> 'Montserrat, sans-serif',
				'font-size' 		=> '13px',
				'font-weight'		=> '700',
				'font-style'		=> 'normal',
				'line-height'		=> '1.25em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'uppercase',
				'letter-spacing'	=> '0'
				),
			'input' => array(
				'title'				=> esc_html__('Input fields', 'autoparts'),
				'description'		=> esc_html__('Font settings of the input fields, dropdowns and textareas', 'autoparts'),
				'font-family'		=> 'Montserrat, sans-serif',
				'font-size' 		=> '13px',
				'font-weight'		=> '400',
				'font-style'		=> 'normal',
				'line-height'		=> '1.2em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'uppercase',
				'letter-spacing'	=> '0px'
				),
			'info' => array(
				'title'				=> esc_html__('Post meta', 'autoparts'),
				'description'		=> esc_html__('Font settings of the post meta: date, counters, share, etc.', 'autoparts'),
				'font-family'		=> 'Montserrat, sans-serif',
				'font-size' 		=> '13px',
				'font-weight'		=> '600',
				'font-style'		=> 'normal',
				'line-height'		=> '1.5em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'uppercase',
				'letter-spacing'	=> '0px',
				'margin-top'		=> '0.4em',
				'margin-bottom'		=> ''
				),
			'menu' => array(
				'title'				=> esc_html__('Main menu', 'autoparts'),
				'description'		=> esc_html__('Font settings of the main menu items', 'autoparts'),
				'font-family'		=> 'Montserrat, sans-serif',
				'font-size' 		=> '13px',
				'font-weight'		=> '400',
				'font-style'		=> 'normal',
				'line-height'		=> '3.692em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'uppercase',
				'letter-spacing'	=> '0px'
				),
			'submenu' => array(
				'title'				=> esc_html__('Dropdown menu', 'autoparts'),
				'description'		=> esc_html__('Font settings of the dropdown menu items', 'autoparts'),
				'font-family'		=> 'Montserrat, sans-serif',
				'font-size' 		=> '14px',
				'font-weight'		=> '400',
				'font-style'		=> 'normal',
				'line-height'		=> '1.714em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '0px'
				)
		));
		
		
		// -----------------------------------------------------------------
		// -- Theme colors for customizer
		// -- Attention! Inner scheme must be last in the array below
		// -----------------------------------------------------------------
		autoparts_storage_set('scheme_color_groups', array(
			'main'	=> array(
							'title'			=> esc_html__('Main', 'autoparts'),
							'description'	=> esc_html__('Colors of the main content area', 'autoparts')
							),
			'alter'	=> array(
							'title'			=> esc_html__('Alter', 'autoparts'),
							'description'	=> esc_html__('Colors of the alternative blocks (sidebars, etc.)', 'autoparts')
							),
			'extra'	=> array(
							'title'			=> esc_html__('Extra', 'autoparts'),
							'description'	=> esc_html__('Colors of the extra blocks (dropdowns, price blocks, table headers, etc.)', 'autoparts')
							),
			'inverse' => array(
							'title'			=> esc_html__('Inverse', 'autoparts'),
							'description'	=> esc_html__('Colors of the inverse blocks - when link color used as background of the block (dropdowns, blockquotes, etc.)', 'autoparts')
							),
			'input'	=> array(
							'title'			=> esc_html__('Input', 'autoparts'),
							'description'	=> esc_html__('Colors of the form fields (text field, textarea, select, etc.)', 'autoparts')
							),
			)
		);
		autoparts_storage_set('scheme_color_names', array(
			'bg_color'	=> array(
							'title'			=> esc_html__('Background color', 'autoparts'),
							'description'	=> esc_html__('Background color of this block in the normal state', 'autoparts')
							),
			'bg_hover'	=> array(
							'title'			=> esc_html__('Background hover', 'autoparts'),
							'description'	=> esc_html__('Background color of this block in the hovered state', 'autoparts')
							),
			'bd_color'	=> array(
							'title'			=> esc_html__('Border color', 'autoparts'),
							'description'	=> esc_html__('Border color of this block in the normal state', 'autoparts')
							),
			'bd_hover'	=>  array(
							'title'			=> esc_html__('Border hover', 'autoparts'),
							'description'	=> esc_html__('Border color of this block in the hovered state', 'autoparts')
							),
			'text'		=> array(
							'title'			=> esc_html__('Text', 'autoparts'),
							'description'	=> esc_html__('Color of the plain text inside this block', 'autoparts')
							),
			'text_dark'	=> array(
							'title'			=> esc_html__('Text dark', 'autoparts'),
							'description'	=> esc_html__('Color of the dark text (bold, header, etc.) inside this block', 'autoparts')
							),
			'text_light'=> array(
							'title'			=> esc_html__('Text light', 'autoparts'),
							'description'	=> esc_html__('Color of the light text (post meta, etc.) inside this block', 'autoparts')
							),
			'text_link'	=> array(
							'title'			=> esc_html__('Link', 'autoparts'),
							'description'	=> esc_html__('Color of the links inside this block', 'autoparts')
							),
			'text_hover'=> array(
							'title'			=> esc_html__('Link hover', 'autoparts'),
							'description'	=> esc_html__('Color of the hovered state of links inside this block', 'autoparts')
							),
			'text_link2'=> array(
							'title'			=> esc_html__('Link 2', 'autoparts'),
							'description'	=> esc_html__('Color of the accented texts (areas) inside this block', 'autoparts')
							),
			'text_hover2'=> array(
							'title'			=> esc_html__('Link 2 hover', 'autoparts'),
							'description'	=> esc_html__('Color of the hovered state of accented texts (areas) inside this block', 'autoparts')
							),
			'text_link3'=> array(
							'title'			=> esc_html__('Link 3', 'autoparts'),
							'description'	=> esc_html__('Color of the other accented texts (buttons) inside this block', 'autoparts')
							),
			'text_hover3'=> array(
							'title'			=> esc_html__('Link 3 hover', 'autoparts'),
							'description'	=> esc_html__('Color of the hovered state of other accented texts (buttons) inside this block', 'autoparts')
							)
			)
		);
		autoparts_storage_set('schemes', array(
		
			// Color scheme: 'default'
			'default' => array(
				'title'	 => esc_html__('Default', 'autoparts'),
				'colors' => array(
					
					// Whole block border and background
					'bg_color'			=> '#eaeaea',
					'bd_color'			=> '#cdcdcd',
		
					// Text and links colors
					'text'				=> '#1e1e1e',
					'text_light'		=> '#b5b5b5',
					'text_dark'			=> '#1e1e1e',
					'text_link'			=> '#1e1e1e',
					'text_hover'		=> '#e43315',
					'text_link2'		=> '#e43315',
					'text_hover2'		=> '#1e1e1e',
					'text_link3'		=> '#01a3c1',
					'text_hover3'		=> '#1e1e1e',

					// Alternative blocks (sidebar, tabs, alternative blocks, etc.)
					'alter_bg_color'	=> '#ffffff',
					'alter_bg_hover'	=> '#ffffff',
					'alter_bd_color'	=> '#eaeaea',
					'alter_bd_hover'	=> '#eaeaea',
					'alter_text'		=> '#1e1e1e',
					'alter_light'		=> '#ffffff',
					'alter_dark'		=> '#01a3c1',
					'alter_link'		=> '#1e1e1e',
					'alter_hover'		=> '#e43315',
					'alter_link2'		=> '#e43315',
					'alter_hover2'		=> '#1e1e1e',
					'alter_link3'		=> '#01a3c1',
					'alter_hover3'		=> '#e43315',
		
					// Extra blocks (submenu, tabs, color blocks, etc.)
					'extra_bg_color'	=> '#181818',
					'extra_bg_hover'	=> '#181818',
					'extra_bd_color'	=> '#cdcdcd',
					'extra_bd_hover'	=> '#cdcdcd',
					'extra_text'		=> '#1e1e1e',
					'extra_light'		=> '#dbdada',
					'extra_dark'		=> '#1e1e1e',
					'extra_link'		=> '#f6c629',
					'extra_hover'		=> '#01a3c1',
					'extra_link2'		=> '#1e1e1e',
					'extra_hover2'		=> '#01a3c1',
					'extra_link3'		=> '#1e1e1e',
					'extra_hover3'		=> '#01a3c1',
		
					// Input fields (form's fields and textarea)
					'input_bg_color'	=> '#f0f0f0',
					'input_bg_hover'	=> '#f0f0f0',
					'input_bd_color'	=> '#dedede',
					'input_bd_hover'	=> '#dedede',
					'input_text'		=> '#1e1e1e',
					'input_light'		=> '#f0f0f0',
					'input_dark'		=> '#1e1e1e',
					
					// Inverse blocks (text and links on the 'text_link' background)
					'inverse_bd_color'	=> '#cdcdcd',
					'inverse_bd_hover'	=> '#cdcdcd',
					'inverse_text'		=> '#ffffff',
					'inverse_light'		=> '#f7f7f7',
					'inverse_dark'		=> '#121212',
					'inverse_link'		=> '#ffffff',
					'inverse_hover'		=> '#01a3c1'
				)
			),
		
			// Color scheme: 'dark'
			'dark' => array(
				'title'  => esc_html__('Dark', 'autoparts'),
				'colors' => array(
					
					// Whole block border and background
					'bg_color'			=> '#eaeaea',
					'bd_color'			=> '#cdcdcd',
		
					// Text and links colors
					'text'				=> '#1e1e1e',
					'text_light'		=> '#b5b5b5',
					'text_dark'			=> '#5b5b5b',
					'text_link'			=> '#1e1e1e',
					'text_hover'		=> '#e43315',
					'text_link2'		=> '#e43315',
					'text_hover2'		=> '#1e1e1e',
					'text_link3'		=> '#01a3c1',
					'text_hover3'		=> '#1e1e1e',

					// Alternative blocks (sidebar, tabs, alternative blocks, etc.)
					'alter_bg_color'	=> '#181818',
					'alter_bg_hover'	=> '#181818',
					'alter_bd_color'	=> '#202020',
					'alter_bd_hover'	=> '#202020',
					'alter_text'		=> '#5b5b5b',
					'alter_light'		=> '#ffffff',
					'alter_dark'		=> '#01a3c1',
					'alter_link'		=> '#5b5b5b',
					'alter_hover'		=> '#c2c2c2',
					'alter_link2'		=> '#e43315',
					'alter_hover2'		=> '#c2c2c2',
					'alter_link3'		=> '#ffffff',
					'alter_hover3'		=> '#e43315',

					// Extra blocks (submenu, tabs, color blocks, etc.)
					'extra_bg_color'	=> '#181818',
					'extra_bg_hover'	=> '#181818',
					'extra_bd_color'	=> '#202020',
					'extra_bd_hover'	=> '#01a3c1',
					'extra_text'		=> '#ffffff',
					'extra_light'		=> '#dbdada',
					'extra_dark'		=> '#5b5b5b',
					'extra_link'		=> '#f6c629',
					'extra_hover'		=> '#c2c2c2',
					'extra_link2'		=> '#b9b9b9',
					'extra_hover2'		=> '#ffffff',
					'extra_link3'		=> '#01a3c1',
					'extra_hover3'		=> '#ffffff',

					// Input fields (form's fields and textarea)
					'input_bg_color'	=> '#202020',
					'input_bg_hover'	=> '#202020',
					'input_bd_color'	=> '#333333',
					'input_bd_hover'	=> '#333333',
					'input_text'		=> '#5b5b5b',
					'input_light'		=> '#f0f0f0',
					'input_dark'		=> '#ffffff',
					
					// Inverse blocks (text and links on the 'text_link' background)
					'inverse_bd_color'	=> '#cdcdcd',
					'inverse_bd_hover'	=> '#202020',
					'inverse_text'		=> '#ffffff',
					'inverse_light'		=> '#f7f7f7',
					'inverse_dark'		=> '#121212',
					'inverse_link'		=> '#1e1e1e',
					'inverse_hover'		=> '#e43315'
				)
			)
		
		));
	}
}

			
// Additional (calculated) theme-specific colors
// Attention! Don't forget setup custom colors also in the theme.customizer.color-scheme.js
if (!function_exists('autoparts_customizer_add_theme_colors')) {
	function autoparts_customizer_add_theme_colors($colors) {
		if (substr($colors['text'], 0, 1) == '#') {
			$colors['bg_color_0']  = autoparts_hex2rgba( $colors['bg_color'], 0 );
			$colors['text_02']  = autoparts_hex2rgba( $colors['text'], 0.2 );
			$colors['text_05']  = autoparts_hex2rgba( $colors['text'], 0.5 );
			$colors['bg_color_02']  = autoparts_hex2rgba( $colors['bg_color'], 0.2 );
			$colors['bg_color_07']  = autoparts_hex2rgba( $colors['bg_color'], 0.7 );
			$colors['bg_color_08']  = autoparts_hex2rgba( $colors['bg_color'], 0.8 );
			$colors['bg_color_09']  = autoparts_hex2rgba( $colors['bg_color'], 0.9 );
			$colors['text_light_02']  = autoparts_hex2rgba( $colors['text_light'], 0.2 );
			$colors['text_light_05']  = autoparts_hex2rgba( $colors['text_light'], 0.5 );
			$colors['alter_light_05']  = autoparts_hex2rgba( $colors['alter_light'], 0.5 );
			$colors['alter_bg_color_07']  = autoparts_hex2rgba( $colors['alter_bg_color'], 0.7 );
			$colors['alter_bg_color_04']  = autoparts_hex2rgba( $colors['alter_bg_color'], 0.4 );
			$colors['alter_bg_color_02']  = autoparts_hex2rgba( $colors['alter_bg_color'], 0.2 );
			$colors['alter_bd_color_02']  = autoparts_hex2rgba( $colors['alter_bd_color'], 0.2 );
			$colors['extra_text_008']  = autoparts_hex2rgba( $colors['extra_text'], 0.08 );
			$colors['extra_text_015']  = autoparts_hex2rgba( $colors['extra_text'], 0.15 );
			$colors['extra_text_03']  = autoparts_hex2rgba( $colors['extra_text'], 0.3 );
			$colors['extra_bg_color_09']  = autoparts_hex2rgba( $colors['extra_bg_color'], 0.9 );
			$colors['text_dark_07']  = autoparts_hex2rgba( $colors['text_dark'], 0.7 );
			$colors['text_link_02']  = autoparts_hex2rgba( $colors['text_link'], 0.2 );
			$colors['text_link_05']  = autoparts_hex2rgba( $colors['text_link'], 0.5 );
			$colors['text_link_07']  = autoparts_hex2rgba( $colors['text_link'], 0.7 );
			$colors['text_link3_01']  = autoparts_hex2rgba( $colors['text_link3'], 0.1 );
			$colors['inverse_text_01']  = autoparts_hex2rgba( $colors['inverse_text'], 0.1 );
			$colors['inverse_text_03']  = autoparts_hex2rgba( $colors['inverse_text'], 0.3 );
			$colors['inverse_text_05']  = autoparts_hex2rgba( $colors['inverse_text'], 0.5 );
			$colors['text_link_blend'] = autoparts_hsb2hex(autoparts_hex2hsb( $colors['text_link'], 2, -5, 5 ));
			$colors['alter_link_blend'] = autoparts_hsb2hex(autoparts_hex2hsb( $colors['alter_link'], 2, -5, 5 ));
		} else {
			$colors['bg_color_0'] = '{{ data.bg_color_0 }}';
			$colors['text_02'] = '{{ data.text_02 }}';
			$colors['text_05'] = '{{ data.text_05 }}';
			$colors['bg_color_02'] = '{{ data.bg_color_02 }}';
			$colors['bg_color_07'] = '{{ data.bg_color_07 }}';
			$colors['bg_color_08'] = '{{ data.bg_color_08 }}';
			$colors['bg_color_09'] = '{{ data.bg_color_09 }}';
			$colors['alter_bg_color_07'] = '{{ data.alter_bg_color_07 }}';
			$colors['alter_bg_color_04'] = '{{ data.alter_bg_color_04 }}';
			$colors['alter_bg_color_02'] = '{{ data.alter_bg_color_02 }}';
			$colors['alter_bd_color_02'] = '{{ data.alter_bd_color_02 }}';
			$colors['extra_text_008'] = '{{ data.extra_text_008 }}';
			$colors['extra_text_015'] = '{{ data.extra_text_015 }}';
			$colors['extra_text_03'] = '{{ data.extra_text_03 }}';
			$colors['extra_bg_color_09'] = '{{ data.extra_bg_color_09 }}';
			$colors['text_light_02'] = '{{ data.text_light_02 }}';
			$colors['text_light_05'] = '{{ data.text_light_05 }}';
			$colors['text_dark_07'] = '{{ data.text_dark_07 }}';
			$colors['text_link_02'] = '{{ data.text_link_02 }}';
			$colors['text_link_05'] = '{{ data.text_link_05 }}';
			$colors['text_link_07'] = '{{ data.text_link_07 }}';
			$colors['inverse_text_03'] = '{{ data.inverse_text_03 }}';
			$colors['inverse_text_05'] = '{{ data.inverse_text_05 }}';
			$colors['text_link_blend'] = '{{ data.text_link_blend }}';
			$colors['alter_link_blend'] = '{{ data.alter_link_blend }}';
		}
		return $colors;
	}
}


			
// Additional theme-specific fonts rules
// Attention! Don't forget setup fonts rules also in the theme.customizer.color-scheme.js
if (!function_exists('autoparts_customizer_add_theme_fonts')) {
	function autoparts_customizer_add_theme_fonts($fonts) {
		$rez = array();	
		foreach ($fonts as $tag => $font) {
			if (substr($font['font-family'], 0, 2) != '{{') {
				$rez[$tag.'_font-family'] 		= !empty($font['font-family']) && !autoparts_is_inherit($font['font-family'])
														? 'font-family:' . trim($font['font-family']) . ';' 
														: '';
				$rez[$tag.'_font-size'] 		= !empty($font['font-size']) && !autoparts_is_inherit($font['font-size'])
														? 'font-size:' . autoparts_prepare_css_value($font['font-size']) . ";"
														: '';
				$rez[$tag.'_line-height'] 		= !empty($font['line-height']) && !autoparts_is_inherit($font['line-height'])
														? 'line-height:' . trim($font['line-height']) . ";"
														: '';
				$rez[$tag.'_font-weight'] 		= !empty($font['font-weight']) && !autoparts_is_inherit($font['font-weight'])
														? 'font-weight:' . trim($font['font-weight']) . ";"
														: '';
				$rez[$tag.'_font-style'] 		= !empty($font['font-style']) && !autoparts_is_inherit($font['font-style'])
														? 'font-style:' . trim($font['font-style']) . ";"
														: '';
				$rez[$tag.'_text-decoration'] 	= !empty($font['text-decoration']) && !autoparts_is_inherit($font['text-decoration'])
														? 'text-decoration:' . trim($font['text-decoration']) . ";"
														: '';
				$rez[$tag.'_text-transform'] 	= !empty($font['text-transform']) && !autoparts_is_inherit($font['text-transform'])
														? 'text-transform:' . trim($font['text-transform']) . ";"
														: '';
				$rez[$tag.'_letter-spacing'] 	= !empty($font['letter-spacing']) && !autoparts_is_inherit($font['letter-spacing'])
														? 'letter-spacing:' . trim($font['letter-spacing']) . ";"
														: '';
				$rez[$tag.'_margin-top'] 		= !empty($font['margin-top']) && !autoparts_is_inherit($font['margin-top'])
														? 'margin-top:' . autoparts_prepare_css_value($font['margin-top']) . ";"
														: '';
				$rez[$tag.'_margin-bottom'] 	= !empty($font['margin-bottom']) && !autoparts_is_inherit($font['margin-bottom'])
														? 'margin-bottom:' . autoparts_prepare_css_value($font['margin-bottom']) . ";"
														: '';
			} else {
				$rez[$tag.'_font-family']		= '{{ data["'.$tag.'_font-family"] }}';
				$rez[$tag.'_font-size']			= '{{ data["'.$tag.'_font-size"] }}';
				$rez[$tag.'_line-height']		= '{{ data["'.$tag.'_line-height"] }}';
				$rez[$tag.'_font-weight']		= '{{ data["'.$tag.'_font-weight"] }}';
				$rez[$tag.'_font-style']		= '{{ data["'.$tag.'_font-style"] }}';
				$rez[$tag.'_text-decoration']	= '{{ data["'.$tag.'_text-decoration"] }}';
				$rez[$tag.'_text-transform']	= '{{ data["'.$tag.'_text-transform"] }}';
				$rez[$tag.'_letter-spacing']	= '{{ data["'.$tag.'_letter-spacing"] }}';
				$rez[$tag.'_margin-top']		= '{{ data["'.$tag.'_margin-top"] }}';
				$rez[$tag.'_margin-bottom']		= '{{ data["'.$tag.'_margin-bottom"] }}';
			}
		}
		return $rez;
	}
}


//-------------------------------------------------------
//-- Thumb sizes
//-------------------------------------------------------

if ( !function_exists('autoparts_customizer_theme_setup') ) {
	add_action( 'after_setup_theme', 'autoparts_customizer_theme_setup' );
	function autoparts_customizer_theme_setup() {

		// Enable support for Post Thumbnails
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size(370, 0, false);
		
		// Add thumb sizes
		// ATTENTION! If you change list below - check filter's names in the 'trx_addons_filter_get_thumb_size' hook
		$thumb_sizes = apply_filters('autoparts_filter_add_thumb_sizes', array(
			'autoparts-thumb-huge'		=> array(1170, 658, true),
			'autoparts-thumb-big' 		=> array( 760, 428, true),
			'autoparts-thumb-extra' 		=> array( 600, 450, true),
			'autoparts-thumb-med' 		=> array( 370, 208, true),
			'autoparts-thumb-tall' 		=> array( 370, 515, true),
			'autoparts-thumb-tiny' 		=> array(  90,  90, true),
			'autoparts-thumb-masonry-big' => array( 760,   0, false),		// Only downscale, not crop
			'autoparts-thumb-masonry'		=> array( 370,   0, false),		// Only downscale, not crop
			)
		);
		$mult = autoparts_get_theme_option('retina_ready', 1);
		if ($mult > 1) $GLOBALS['content_width'] = apply_filters( 'autoparts_filter_content_width', 1170*$mult);
		foreach ($thumb_sizes as $k=>$v) {
			// Add Original dimensions
			add_image_size( $k, $v[0], $v[1], $v[2]);
			// Add Retina dimensions
			if ($mult > 1) add_image_size( $k.'-@retina', $v[0]*$mult, $v[1]*$mult, $v[2]);
		}

	}
}

if ( !function_exists('autoparts_customizer_image_sizes') ) {
	add_filter( 'image_size_names_choose', 'autoparts_customizer_image_sizes' );
	function autoparts_customizer_image_sizes( $sizes ) {
		$thumb_sizes = apply_filters('autoparts_filter_add_thumb_sizes', array(
			'autoparts-thumb-huge'		=> esc_html__( 'Fullsize image', 'autoparts' ),
			'autoparts-thumb-big'			=> esc_html__( 'Large image', 'autoparts' ),
			'autoparts-thumb-extra'		=> esc_html__( 'Extra image', 'autoparts' ),
			'autoparts-thumb-med'			=> esc_html__( 'Medium image', 'autoparts' ),
			'autoparts-thumb-tall'		=> esc_html__( 'Tall image', 'autoparts' ),
			'autoparts-thumb-tiny'		=> esc_html__( 'Small square avatar', 'autoparts' ),
			'autoparts-thumb-masonry-big'	=> esc_html__( 'Masonry Large (scaled)', 'autoparts' ),
			'autoparts-thumb-masonry'		=> esc_html__( 'Masonry (scaled)', 'autoparts' ),
			)
		);
		$mult = autoparts_get_theme_option('retina_ready', 1);
		foreach($thumb_sizes as $k=>$v) {
			$sizes[$k] = $v;
			if ($mult > 1) $sizes[$k.'-@retina'] = $v.' '.esc_html__('@2x', 'autoparts' );
		}
		return $sizes;
	}
}

// Remove some thumb-sizes from the ThemeREX Addons list
if ( !function_exists( 'autoparts_customizer_trx_addons_add_thumb_sizes' ) ) {
	add_filter( 'trx_addons_filter_add_thumb_sizes', 'autoparts_customizer_trx_addons_add_thumb_sizes');
	function autoparts_customizer_trx_addons_add_thumb_sizes($list=array()) {
		if (is_array($list)) {
			foreach ($list as $k=>$v) {
				if (in_array($k, array(
								'trx_addons-thumb-huge',
								'trx_addons-thumb-big',
								'trx_addons-thumb-extra',
								'trx_addons-thumb-medium',
								'trx_addons-thumb-tiny',
								'trx_addons-thumb-masonry-big',
								'trx_addons-thumb-masonry',
								)
							)
						) unset($list[$k]);
			}
		}
		return $list;
	}
}

// and replace removed styles with theme-specific thumb size
if ( !function_exists( 'autoparts_customizer_trx_addons_get_thumb_size' ) ) {
	add_filter( 'trx_addons_filter_get_thumb_size', 'autoparts_customizer_trx_addons_get_thumb_size');
	function autoparts_customizer_trx_addons_get_thumb_size($thumb_size='') {
		return str_replace(array(
							'trx_addons-thumb-huge',
							'trx_addons-thumb-huge-@retina',
							'trx_addons-thumb-big',
							'trx_addons-thumb-big-@retina',
							'trx_addons-thumb-extra',
							'trx_addons-thumb-extra-@retina',
							'trx_addons-thumb-medium',
							'trx_addons-thumb-medium-@retina',
							'trx_addons-thumb-tiny',
							'trx_addons-thumb-tiny-@retina',
							'trx_addons-thumb-masonry-big',
							'trx_addons-thumb-masonry-big-@retina',
							'trx_addons-thumb-masonry',
							'trx_addons-thumb-masonry-@retina',
							),
							array(
							'autoparts-thumb-huge',
							'autoparts-thumb-huge-@retina',
							'autoparts-thumb-big',
							'autoparts-thumb-big-@retina',
							'autoparts-thumb-extra',
							'autoparts-thumb-extra-@retina',
							'autoparts-thumb-med',
							'autoparts-thumb-med-@retina',
							'autoparts-thumb-tiny',
							'autoparts-thumb-tiny-@retina',
							'autoparts-thumb-masonry-big',
							'autoparts-thumb-masonry-big-@retina',
							'autoparts-thumb-masonry',
							'autoparts-thumb-masonry-@retina',
							),
							$thumb_size);
	}
}
?>
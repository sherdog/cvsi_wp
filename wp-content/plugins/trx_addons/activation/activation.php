<?php
/**
 * ThemeREX The Activation
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 */

// Don't load directly
if ( ! defined( 'TRX_ADDONS_VERSION' ) ) {
	die( '-1' );
}


/**
 * Activation Utils
 */

// Check 'theme activated' status
if ( !function_exists( 'trx_addons_is_theme_activated' ) ) {
	function trx_addons_is_theme_activated() {
		return get_option( sprintf( 'trx_addons_theme_%s_activated', get_option( 'template' ) ) ) == 1
			&& get_option( sprintf( 'purchase_code_%s', get_option( 'template' ) ) ) != '';
	}
}

// Set 'theme activated' status
if ( !function_exists( 'trx_addons_set_theme_activated' ) ) {
	function trx_addons_set_theme_activated($code='', $pro_key='') {
		update_option( sprintf( 'trx_addons_theme_%s_activated', get_option( 'template' ) ), 1);
		if ( !empty($code) ) {
			update_option( sprintf( 'purchase_code_%s', get_option( 'template' ) ), $code );
			if ( substr($pro_key, 0, 4) == 'env_' ) {
				update_option( sprintf( 'envato_purchase_code_%s', get_option( 'template' ) ), $code );
			}
		}
	}
}

// Return 'theme activated' status
if ( !function_exists( 'trx_addons_get_theme_activated_status' ) ) {
	function trx_addons_get_theme_activated_status() {
		return trx_addons_is_theme_activated() ? 'active' : 'inactive';
	}
}

// Return theme activation code
if ( !function_exists( 'trx_addons_get_theme_activation_code' ) ) {
    add_filter('trx_updater_filter_theme_purchase_key', 'trx_addons_get_theme_activation_code');
    function trx_addons_get_theme_activation_code() {
        return get_option( sprintf( 'trx_addons_theme_%s_activated', get_option( 'template' ) ) ) == 1
            ? get_option( sprintf( 'purchase_code_%s', get_option( 'template' ) ) )
            : '';
    }
}

// Return theme activation message
if ( !function_exists( 'trx_addons_get_theme_activation_error_message' ) ) {
	function trx_addons_get_theme_activation_error_message() {
		$val = get_option('trx_addons_theme_activation_error_message');
		return is_array($val) && !empty($val) ? $val : array();
	}
}

// Setup theme activation message
if ( !function_exists( 'trx_addons_set_theme_activation_error_message' ) ) {
	function trx_addons_set_theme_activation_error_message($msg) {
		$val = get_option('trx_addons_theme_activation_error_message');
		$val = is_array($val) ? $val : array();
		$val[] = $msg;
		update_option('trx_addons_theme_activation_error_message', $val );
	}
}


/**
 * Activation WordPress functions
 */

// Load required styles and scripts for the frontend
if ( !function_exists( 'trx_addons_widget_activation_load_scripts' ) ) {
	add_action("admin_enqueue_scripts", 'trx_addons_widget_activation_load_scripts');
	function trx_addons_widget_activation_load_scripts() {
		if (!trx_addons_is_theme_activated()) {
			wp_enqueue_style( 'trx-addons-theme-activation', trx_addons_get_file_url('activation/activation.css'), array(), null );
		}
	}
}

// Load required styles and scripts for the frontend
if ( !function_exists( 'trx_addons_activation_load_scripts' ) ) {
	add_action("admin_enqueue_scripts", 'trx_addons_activation_load_scripts');
	function trx_addons_activation_load_scripts() {
		if (!trx_addons_is_theme_activated()) {
			wp_enqueue_script( 'trx-addons-theme-activation', trx_addons_get_file_url('activation/activation.js'), array('jquery'), null, true );
		}
	}
}

// Return theme-specific data to the dashboard widget
if ( ! function_exists( 'trx_addons_activation_get_theme_info' ) ) {
	function trx_addons_activation_get_theme_info( $cache = true ) {
		static $cached_info = false;
		if ($cached_info !== false) {
			$theme_info = $cached_info;
		} else {
			$theme = wp_get_theme();
			$theme_info['theme_slug'] = get_option('template');
			$theme_info['theme_name'] = 'Car Parts Store & Auto Services WordPress Theme';
			$theme_info['theme_pro_key'] = 'env-themerex';
			$theme_info['theme_activated'] = trx_addons_is_theme_activated();

			$theme_info = apply_filters('trx-addons-activation-theme-info', $theme_info);

			if ($cache) {
				$cached_info = $theme_info;
			}
		}
		return $theme_info;
	}
}

// AJAX handler for the hide activation action
if ( !function_exists( 'trx_addons_hide_activation_notice' ) ) {
	add_action('wp_ajax_trx_addons_hide_activation_notice', 'trx_addons_hide_activation_notice');
	function trx_addons_hide_activation_notice() {
		if ( wp_verify_nonce( trx_addons_get_value_gp('nonce'), admin_url() ) ) {
			update_option( 'trx_addons_show_activation_notice', '0' );
			//Run cron checker, to remind activate the theme
			trx_addons_activation_setup_cron_check();
		}
		wp_die();
	}
}

// First setup
if ( !function_exists( 'trx_addons_activation_setup_notice' ) ) {
	add_action('admin_init', 'trx_addons_activation_setup_notice');
	function trx_addons_activation_setup_notice() {
		if ( get_option( 'trx_addons_show_activation_notice') === false ) {
			update_option( 'trx_addons_show_activation_notice', '1' );
		}
	}
}

// Add a new interval of a week
if ( !function_exists( 'trx_addons_activation_add_weekly_cron_schedule' ) ) {
	add_filter('cron_schedules', 'trx_addons_activation_add_weekly_cron_schedule');
	function trx_addons_activation_add_weekly_cron_schedule($schedules) {
		$schedules['weekly'] = array(
			'interval' => 604800, // 1 week in seconds
			'display' => esc_html__('Once Weekly', 'trx_addons'),
		);

		return $schedules;
	}
}

// Schedule activation check
if ( !function_exists( 'trx_addons_activation_setup_cron_check' ) ) {
	function trx_addons_activation_setup_cron_check() {
		if ( ! wp_next_scheduled( 'trx_addons_activation_cron_check' ) ) {
			wp_schedule_event( time(), 'weekly', 'trx_addons_activation_cron_check' );
		}
	}
}

// Remove schedule activation check
if ( !function_exists( 'trx_addons_activation_remove_cron_check' ) ) {
	function trx_addons_activation_remove_cron_check() {
		$timestamp = wp_next_scheduled( 'trx_addons_activation_cron_check' );
		wp_unschedule_event( $timestamp, 'trx_addons_activation_cron_check' );
	}
}

// Schedule activation check hook
if ( !function_exists( 'trx_addons_activation_turnon_notice' ) ) {
	add_action('trx_addons_activation_cron_check', 'trx_addons_activation_turnon_notice');
	function trx_addons_activation_turnon_notice() {
		if ( !trx_addons_is_theme_activated() ) {
			update_option( 'trx_addons_show_activation_notice', '1' );
		}
	}
}


/**
 * API support
 */

// Activate theme
if ( !function_exists( 'trx_addons_theme_panel_activate_theme' ) ) {
	add_action('admin_init', 'trx_addons_theme_panel_activate_theme', 1);
	function trx_addons_theme_panel_activate_theme() {
		if (empty($_REQUEST['action']) || ($_REQUEST['action'] !== 'trx_addons_theme_activation')) {
			return;
		}

		// If submit form with activation code
		$nonce = trx_addons_get_value_gp('trx_addons_nonce');
		$code = trx_addons_get_value_gp('trx_addons_activate_theme_code');
		if ( empty( $nonce ) ) {
			return;
		}
		// Check nonce
		if ( !wp_verify_nonce( $nonce, admin_url() ) ) {
			trx_addons_set_theme_activation_error_message( esc_html__('Security code is invalid! Theme is not activated!', 'trx_addons') );
			// Check code
		} else {
			$theme_info = trx_addons_activation_get_theme_info();
			$upgrade_url = sprintf(
				'http://upgrade.themerex.net/upgrade.php?key=%1$s&src=%2$s&theme_slug=%3$s&theme_name=%4$s&action=check',
				urlencode( $code ),
				urlencode( $theme_info['theme_pro_key'] ),
				urlencode( $theme_info['theme_slug'] ),
				urlencode( $theme_info['theme_name'] )
			);
			if ( (int) trx_addons_get_value_gp('trx_addons_user_agree') == 1 ) {
				$user_name = sanitize_text_field(trx_addons_get_value_gp('trx_addons_user_name'));
				$user_email = sanitize_email(trx_addons_get_value_gp('trx_addons_user_email'));
				if (!empty($user_name) && !empty($user_email)) {
					$upgrade_url .= '&user_name=' . urlencode($user_name) . '&user_email=' . urlencode($user_email);
				}
			}
			$result = trx_addons_fgc( $upgrade_url );
			if ( substr( $result, 0, 5 ) == 'a:2:{' && substr( $result, -1, 1 ) == '}' ) {
				try {
					$result = trx_addons_unserialize( $result );
				} catch ( Exception $e ) {
					$result = array(
						'error' => '',
						'data' => -1
					);
				}
				if ( $result['data'] === 1 ) {
					trx_addons_set_theme_activated($code, $theme_info['theme_pro_key']);
					//Kill cron check
					trx_addons_activation_remove_cron_check();

					update_option('trx_addons_theme_activation_show_success_message', true );
				} elseif ( $result['data'] === -1 ) {
					trx_addons_set_theme_activation_error_message( esc_html__('Bad server answer! Theme is not activated!', 'trx_addons'));
				}
				else {
					trx_addons_set_theme_activation_error_message( esc_html__('Your purchase code is invalid! Theme is not activated!', 'trx_addons'));
				}
			}
		}
	}
}

// Add 'Activate Your Copy' parameters in the ThemeREX Addons Options
if (!function_exists('trx_addons_activation_options')) {
	add_action( 'trx_addons_filter_options', 'trx_addons_activation_options');
	function trx_addons_activation_options($options) {
		$show = !trx_addons_is_theme_activated() && !get_option('trx_addons_show_activation_notice');

		trx_addons_array_insert_after($options, 'theme_specific_section', array(
			// Layouts settings
			'activation_info' => array(
				"title" => esc_html__('Theme Activation', 'trx_addons'),
				"desc" => wp_kses_data( __('Activation gives an access to advanced options like: premium plugins, demo content and customer support.', 'trx_addons') ),
				"std" => '',
				"type" => $show ? "info" : "hidden"
			),
			'activation_restore' => array(
				"title" => esc_html__('Activate Your Copy', 'trx_addons'),
				"desc" => wp_kses_data( __('Press button above to show activation form.', 'trx_addons') ),
				"std" => 'trx_addons_activation_restore',
				"type" =>  $show ? "button" : "hidden"
			)
		));
		return $options;
	}
}

// Callback for the 'Create Layouts' button
if ( !function_exists( 'trx_addons_callback_ajax_trx_addons_activation_restore' ) ) {
	add_action('wp_ajax_trx_addons_activation_restore', 'trx_addons_callback_ajax_trx_addons_activation_restore');
	function trx_addons_callback_ajax_trx_addons_activation_restore() {
		if ( !wp_verify_nonce( trx_addons_get_value_gp('nonce'), admin_url() ) ) {
			wp_die();
		}
		$response = array(
			'error' => '',
			'success' => esc_html__('Please reload this page and fill out the activation form.', 'trx_addons')
		);
		trx_addons_activation_turnon_notice();

		echo json_encode($response);
		die();
	}
}


/**
 * Templates
 */

// Display the theme activation form
if ( !function_exists( 'trx_addons_theme_panel_activation_form' ) ) {
	add_action('admin_notices', 'trx_addons_theme_panel_activation_form');
	function trx_addons_theme_panel_activation_form() {
		if ( get_option('trx_addons_show_activation_notice') && !trx_addons_is_theme_activated() ) {
			$args = array();
			$args['theme_status'] = trx_addons_get_theme_activated_status();
			$args['errors'] = trx_addons_get_theme_activation_error_message();
			update_option('trx_addons_theme_activation_error_message', false );
			trx_addons_get_template_part('activation/tpl.activation-notice.php','trx_addons_args_theme_activation', $args );
		}
	}
}

// Display the theme activation form
if ( !function_exists( 'trx_addons_theme_panel_activation_form_success' ) ) {
	add_action('admin_notices', 'trx_addons_theme_panel_activation_form_success');
	function trx_addons_theme_panel_activation_form_success() {
		$args = array();
		if ( get_option('trx_addons_theme_activation_show_success_message') && trx_addons_is_theme_activated()) {
			//show once
			update_option('trx_addons_theme_activation_show_success_message', false);
			trx_addons_get_template_part('activation/tpl.activation-success.php','trx_addons_args_theme_activation', $args );
		}
	}
}
<?php
/**
 * CustomFacebookFeed PRO plugin.
 *
 * The main Custom_Facebook_Feed_Pro class that runs the plugins & registers all the ressources.
 *
 * @since 3.18
 */
namespace CustomFacebookFeed;
use CustomFacebookFeed\Admin\CFF_Tracking;
use CustomFacebookFeed\Admin\CFF_About;
use CustomFacebookFeed\Admin\CFF_Notifications;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

final class Custom_Facebook_Feed_Pro{

	/**
	 * Instance
	 *
	 * @since 3.18
	 * @access private
	 * @static
	 * @var Custom_Facebook_Feed_Pro
	 */
	private static $instance;

	/**
	 * cff_blocks.
	 *
	 * Blocks.
	 *
	 * @since 3.18
	 * @access public
	 *
	 * @var cff_blocks
	 */
	public $cff_blocks;

	/**
	 * CFF_Error_Reporter.
	 *
	 * Error Reporter panel.
	 *
	 * @since 3.18
	 * @access public
	 *
	 * @var CFF_Error_Reporter
	 */
	public $cff_error_reporter;

	/**
	 * CFF_Oembed.
	 *
	 * Oembed Element.
	 *
	 * @since 3.18
	 * @access public
	 *
	 * @var CFF_Oembed
	 */
	public $cff_oembed;


	/**
	 * CFF_Tracking.
	 *
	 * Tracking System.
	 *
	 * @since 3.18
	 * @access public
	 *
	 * @var CFF_Tracking
	 */
	public $cff_tracking;

	/**
	 * CFF_About.
	 *
	 * About page panel.
	 *
	 * @since 3.18
	 * @access public
	 *
	 * @var CFF_About
	 */
	public $cff_about;

	/**
	 * CFF_Notifications.
	 *
	 * Notifications System.
	 *
	 * @since 3.18
	 * @access public
	 *
	 * @var CFF_Notifications
	 */
	public $cff_notifications;

	/**
	 * CFF_SiteHealth.
	 *
	 *
	 * @since 3.18
	 * @access public
	 *
	 * @var CFF_SiteHealth
	 */
	public $cff_sitehealth;

	/**
	 * CFF_Shortcode.
	 *
	 * Shortcode Class.
	 *
	 * @since 3.18
	 * @access public
	 *
	 * @var CFF_Shortcode
	 */
	public $cff_shortcode;

	/**
	 * CFF_Cache_System.
	 *
	 * Cache System Class.
	 *
	 * @since 3.18
	 * @access public
	 *
	 * @var CFF_Cache_System
	 */
	public $cff_cache_system;


	/**
	 * Custom_Facebook_Feed_Pro Instance.
	 *
	 * Just one instance of the Custom_Facebook_Feed_Pro class
	 *
	 * @since 3.18
	 * @access public
	 * @static
	 *
	 * @return Custom_Facebook_Feed_Pro
	 */
	public static function instance() {
		if ( null === self::$instance) {
			self::$instance = new self();


			if( !class_exists('CFF_Utils') ) include CFF_PLUGIN_DIR. 'inc/CFF_Utils.php';
			//Load Composer Autoload
			require CFF_PLUGIN_DIR . 'vendor/autoload.php';
			CFF_GDPR_Integrations::init();
			self::$instance->cff_tracking 			= new CFF_Tracking();
			self::$instance->cff_blocks 			= new CFF_Blocks();
			self::$instance->cff_error_reporter 	= new CFF_Error_Reporter();
			self::$instance->cff_oembed				= new CFF_Oembed();
			self::$instance->cff_shortcode			= new CFF_Shortcode();
			self::$instance->cff_cache_system		= new CFF_Cache_System();

			add_action( 'init', [ self::$instance, 'sw_version_check' ], 0 );

			add_action( 'plugins_loaded', [ self::$instance, 'load_textdomain' ], 10 );
			add_action( 'plugins_loaded', [ self::$instance, 'init' ], 0 );

			add_action( 'wp_head', [ self::$instance, 'cff_custom_css' ] );
			add_action( 'wp_footer', [ self::$instance, 'cff_js' ] );

			add_filter( 'cron_schedules', [ self::$instance, 'cff_pro_cron_custom_interval' ] );

			add_action( 'wp_loaded', [ self::$instance, 'cff_pro_check_for_db_updates' ]  );

			add_action( 'admin_init', [ self::$instance, 'cff_plugin_updates' ], 0 );

			//Locator Ajax Calls
			add_action( 'wp_ajax_cff_do_locator', array('CustomFacebookFeed\CFF_Feed_Locator','cff_do_locator') );
			add_action( 'wp_ajax_nopriv_cff_do_locator', array('CustomFacebookFeed\CFF_Feed_Locator','cff_do_locator') );

			register_activation_hook( CFF_FILE, [ self::$instance, 'cff_pro_activate' ] );
			register_deactivation_hook( CFF_FILE, [ self::$instance, 'cff_pro_deactivate' ] );
			register_uninstall_hook( CFF_FILE, 'CustomFacebookFeed\Custom_Facebook_Feed_Pro::cff_pro_uninstall' );

		}
		return self::$instance;
	}

	/**
	 * Init.
	 *
	 * Initialize Custom_Facebook_Feed_Pro plugin.
	 *
	 * @since 3.18
	 * @access public
	 */
	public function init() {
		$this->register_assets();
		$this->group_posts_process();
		if ( $this->cff_blocks->allow_load() ) {
			$this->cff_blocks->load();
		}
		if ( is_admin() ) {
			$this->cff_about		= new CFF_About();
			if ( version_compare( PHP_VERSION,  '5.3.0' ) >= 0 && version_compare( get_bloginfo('version'), '4.6' , '>' ) ) {
				$this->cff_notifications = new CFF_Notifications();
				$this->cff_notifications->init();

				require_once trailingslashit( CFF_PLUGIN_DIR ) . 'admin/addon-functions.php';
				$this->cff_sitehealth = new CFF_SiteHealth();
				if ( $this->cff_sitehealth->allow_load() ) {
					$this->cff_sitehealth->load();
				}
			}
			add_action( 'admin_notices', [$this, 'social_wall_notice'] );
		}
	}

	/**
 	 * Launch the Group Posts Cache Process
 	 *
 	 * @since 3.19
 	 *
 	 * @return void
	 * @access public
 	*/
	function group_posts_process(){
		 $defaults = array(
	        'cff_timezone' => 'America/Chicago',
	        'cff_load_more' => true,
	        'cff_num_mobile' => ''
	    );
		$options = wp_parse_args(get_option('cff_style_settings'), $defaults);
    	$cff_timezone = $options[ 'cff_timezone' ];
		$cff_cache_cron_time_val = get_option( 'cff_cache_cron_time', '1' );
    	$cff_cache_cron_am_pm_val = get_option( 'cff_cache_cron_am_pm', 'am' );
    	$cff_cache_cron_interval_val = get_option( 'cff_cache_cron_interval', '12hours' );

		switch ($cff_cache_cron_interval_val) {
            case "30mins":
            $cff_cron_schedule = '30mins';
            break;
            case "1hour":
            $cff_cron_schedule = 'hourly';
            break;
            case "12hours":
            $cff_cron_schedule = 'twicedaily';
            break;
            default:
            $cff_cron_schedule = 'daily';
        }
        $cff_cache_cron_time_unix = strtotime( $cff_cache_cron_time_val . $cff_cache_cron_am_pm_val . ' ' . $cff_timezone );
        if( $cff_cache_cron_interval_val == '30mins' || $cff_cache_cron_interval_val == '1hour' ) $cff_cache_cron_time_unix = time();

        CFF_Group_Posts::group_schedule_event($cff_cache_cron_time_unix, $cff_cron_schedule);
	}

	/**
 	 * Social Wall Notice
 	 *
 	 * @since 3.18
 	 *
 	 * @return void
	 * @access public
 	*/
	function social_wall_notice(){
		if( defined( 'SWVER' ) && version_compare( SWVER, '1.0.3', '<' ) ){
			?>
			<div class="notice notice-error">
                    <div>
                        <p>
                        	<strong><?php echo esc_html__('Important:','custom-facebook-feed') ?> </strong> <?php echo __('An update to the <strong>Social Wall</strong> plugin is required to be compatible with the latest version of the <strong>Custom Facebook Feed</strong> plugin. Please update the plugin on the WordPress ','custom-facebook-feed') ?><a href="<?php echo esc_url( admin_url( '/plugins.php' ) ) ?>"><?php echo esc_html__('Plugins page','custom-facebook-feed') ?></a>.
                        </p>
                   	</div>
			</div>
			<?php
		}
	}

	function sw_version_check(){
		if( defined( 'SWVER' ) && version_compare( SWVER, '1.0.3', '<' ) ){
			add_shortcode( 'social-wall', [$this, 'sw_cff_update_notice'] );
		}
	}
	function sw_cff_update_notice(){
		if ( ! is_user_logged_in()  || ! current_user_can( 'edit_posts' )) {
			return '';
		}
		$sw_update_output = '<div class="cff-sw-update-ntc">';
			$sw_update_output .= '<span>'. esc_html__('This error message is only visible to WordPress admins', 'custom-facebook-feed' ) .'</span><br />';
			$sw_update_output .= '<p><b>'. esc_html__('Social Wall plugin needs to be updated.', 'custom-facebook-feed' ) .' <a href="' . admin_url( 'plugins.php' ) . '">'. esc_html__('Click Here.', 'custom-facebook-feed' ).'</a></b></p>';
		$sw_update_output .= '</div>';
		return $sw_update_output;
	}

	/**
 	 * Load Custom_Facebook_Feed_Pro textdomain.
 	 *
 	 * @since 3.18
 	 *
 	 * @return void
	 * @access public
 	*/
	public function load_textdomain(){
		load_plugin_textdomain( 'custom-facebook-feed' );
	}


	/**
	 * Register Assets
	 *
	 * @since 3.18
	 */
	public function register_assets(){
		add_action( 'wp_enqueue_scripts' , array( $this, 'enqueue_styles_assets' ) );
		add_action( 'wp_enqueue_scripts' , array( $this, 'enqueue_scripts_assets' ) );
	}

	/**
	 * Enqueue & Register Styles
	 *
	 * @since 3.18
	 */
	public function enqueue_styles_assets(){
		$options = get_option('cff_style_settings');
		isset($options[ 'cff_minify' ]) ? $cff_minify = $options[ 'cff_minify' ] : $cff_minify = '';
		$cff_minify ? $cff_min = '.min' : $cff_min = '';

        $cff_ext_options = get_option('cff_extensions_status');
        $cff_extensions_carousel_active = false;
        if( isset($cff_ext_options[ 'cff_extensions_carousel_active' ]) ) $cff_extensions_carousel_active = $cff_ext_options[ 'cff_extensions_carousel_active' ];
        ( is_plugin_active( 'cff-carousel/cff-carousel.php' ) || $cff_extensions_carousel_active ) ? $cff_carousel_active = true : $cff_carousel_active = false;

        // enqueue_css_in_shortcode

		wp_register_style(
			'cff',
			CFF_PLUGIN_URL . 'assets/css/cff-style'.$cff_min.'.css' ,
			array(),
			CFFVER
		);
		$options['cff_enqueue_with_shortcode'] = isset( $options['cff_enqueue_with_shortcode'] ) ? $options['cff_enqueue_with_shortcode'] : false;
        if ( isset( $options['cff_enqueue_with_shortcode'] ) && !$options['cff_enqueue_with_shortcode'] ) {
            wp_enqueue_style( 'cff' );
        }

        if ( CFF_GDPR_Integrations::doing_gdpr( $options ) ) {
		    $options[ 'cff_font_source' ] = 'local';
	    }

        // Carousel extension still uses Font Awesome so we need to load this if it is activated
		if( $cff_carousel_active ){
			wp_enqueue_style(
                'sb-font-awesome',
                CFF_PLUGIN_URL . 'assets/css/font-awesome.min.css',
                array()
            );
		}			
        
	    //Remove masonry extension CSS
		wp_dequeue_style( 'cff_masonry_css' );
	}


	/**
	 * Enqueue & Register Scripts
	 *
	 *
	 * @since 3.18
	 * @access public
	 */
	public function enqueue_scripts_assets(){
		$options = get_option('cff_style_settings');
		$cff_min = isset( $options[ 'cff_minify' ] ) && !empty( $options[ 'cff_minify' ] )  ? '.min' : '';

		wp_register_script(
			'cffscripts',
			CFF_PLUGIN_URL . 'assets/js/cff-scripts'.$cff_min.'.js' ,
			array('jquery'),
			CFFVER,
			true
		);

		$data = array(
			'placeholder' => CFF_PLUGIN_URL. 'assets/img/placeholder.png',
			'resized_url' => Cff_Utils::cff_get_resized_uploads_url(),
		);
		$options['cff_enqueue_with_shortcode'] = isset( $options['cff_enqueue_with_shortcode'] ) ? $options['cff_enqueue_with_shortcode'] : false;
		wp_localize_script( 'cffscripts', 'cffOptions', $data );
        if ( isset( $options['cff_enqueue_with_shortcode'] ) && !$options['cff_enqueue_with_shortcode'] ) {
            wp_enqueue_script( 'cffscripts' );
        }

		wp_dequeue_script( 'cff_masonry_js' );
	}


	/**
	 * Custom CSS
	 *
	 * Adding custom CSS
	 *
	 * @since 3.18
	 * @access public
	 */
	function cff_custom_css() {
		$custom_css_output = '';
		$options = get_option('cff_style_settings');
		isset($options[ 'cff_custom_css' ]) ? $cff_custom_css = $options[ 'cff_custom_css' ] : $cff_custom_css = '';
		if( !empty($cff_custom_css) ){
			$custom_css_output .= '<!-- Custom Facebook Feed Custom CSS -->';
			$custom_css_output .= "\r\n";
			$custom_css_output .= '<style type="text/css">';
			$custom_css_output .= "\r\n";
			$custom_css_output .= stripslashes($cff_custom_css);
			$custom_css_output .= "\r\n";
			$custom_css_output .= '</style>';
			$custom_css_output .= "\r\n";
		}

	    //Lightbox CSS
		isset($options[ 'cff_lightbox_bg_color' ]) ? $cff_lightbox_bg_color = $options[ 'cff_lightbox_bg_color' ] : $cff_lightbox_bg_color = '';
		isset($options[ 'cff_lightbox_text_color' ]) ? $cff_lightbox_text_color = $options[ 'cff_lightbox_text_color' ] : $cff_lightbox_text_color = '';
		isset($options[ 'cff_lightbox_link_color' ]) ? $cff_lightbox_link_color = $options[ 'cff_lightbox_link_color' ] : $cff_lightbox_link_color = '';
		if( !empty($cff_lightbox_bg_color) || !empty($cff_lightbox_text_color) || !empty($cff_lightbox_link_color) ){
			$custom_css_output .= '<!-- CFF Lightbox CSS -->';
			$custom_css_output .= "\r\n";
			$custom_css_output .= '<style type="text/css">';
			$custom_css_output .= "\r\n";
			if( !empty($cff_lightbox_bg_color) ) $custom_css_output .= '#cff-lightbox-wrapper .cff-lightbox-sidebar, .cff-lightbox-dataContainer, .cff-lightbox-wrapper.cff-enable-lb-comments .cff-lightbox-sidebar::-webkit-scrollbar-track{ background-color: ' . $cff_lightbox_bg_color . '; } .cff-lightbox-wrapper.cff-enable-lb-comments .cff-lightbox-sidebar::-webkit-scrollbar-thumb{ border: 3px solid ' . $cff_lightbox_bg_color . '; border-left: none; background-color: rgba(255,255,255,0.8); } ';
			if( !empty($cff_lightbox_text_color) ) $custom_css_output .= '#cff-lightbox-wrapper .cff-lightbox-sidebar, #cff-lightbox-wrapper .cff-lightbox-caption, #cff-lightbox-wrapper .cff-author .cff-date{ color: ' . $cff_lightbox_text_color . '; }';
			if( !empty($cff_lightbox_link_color) ) $custom_css_output .= '#cff-lightbox-wrapper .cff-lightbox-sidebar a, .cff-lightbox-dataContainer a{ color: ' . $cff_lightbox_link_color . ' !important; }';
			if( !empty($cff_lightbox_link_color) ) $custom_css_output .= "\r\n";
			$custom_css_output .= '</style>';
			$custom_css_output .= "\r\n";
		}

	    //Link hashtags?
		isset($options[ 'cff_link_hashtags' ]) ? $cff_link_hashtags = $options[ 'cff_link_hashtags' ] : $cff_link_hashtags = '';
		isset($options[ 'cff_title_link' ]) ? $cff_title_link = $options[ 'cff_title_link' ] : $cff_title_link = '';
		($cff_link_hashtags == 'true' || $cff_link_hashtags == 'on') ? $cff_link_hashtags = 'true' : $cff_link_hashtags = 'false';
		if($cff_title_link == 'true' || $cff_title_link == 'on') $cff_link_hashtags = 'false';

	    //Ajax caching?
	    //Does the transient exist?
		( false === ( $cff_cached_meta = get_transient( 'cff_meta' ) ) ) ? $cff_cached_meta = true : $cff_cached_meta = false;
	    //Is the user disabling ajax caching?
		isset($options[ 'cff_disable_ajax_cache' ]) ? $cff_disable_ajax_cache = $options[ 'cff_disable_ajax_cache' ] : $cff_disable_ajax_cache = '';
		if( $cff_disable_ajax_cache ) $cff_cached_meta = false;

		$custom_css_output .= '<!-- Custom Facebook Feed JS vars -->';
		$custom_css_output .= "\r\n";
		$custom_css_output .= '<script type="text/javascript">';
		$custom_css_output .= "\r\n";
		$custom_css_output .= 'var cffsiteurl = "' . plugins_url() . '";';
		$custom_css_output .= "\r\n";
		$custom_css_output .= 'var cffajaxurl = "' . admin_url('admin-ajax.php') . '";';
		$custom_css_output .= "\r\n";
		#$custom_css_output .= ( $cff_cached_meta ) ? 'var cffmetatrans = "false";' : 'var cffmetatrans = "true";';
		$custom_css_output .= "\r\n";
		if( $cff_disable_ajax_cache ) $custom_css_output .= 'var cffdisablecommentcaching = "true";';
		$custom_css_output .= "\r\n";
		$custom_css_output .= 'var cfflinkhashtags = "' . $cff_link_hashtags . '";';
		$custom_css_output .= "\r\n";
		$custom_css_output .= '</script>';
		$custom_css_output .= "\r\n";

		echo $custom_css_output;
	}

	/**
	 * Custom JS
	 *
	 * Adding custom JS
	 *
	 * @since 3.18
	 * @access public
	 */
	function cff_js() {
		$custom_js_output = '';

		$options = get_option('cff_style_settings');
		isset($options[ 'cff_custom_js' ]) ? $cff_custom_js = $options[ 'cff_custom_js' ] : $cff_custom_js = '';
	    //Replace "cff-item" with "cff-new" so that it only runs on new items loaded into the feed
		$cff_custom_js = str_replace(".cff-item", ".cff-new", $cff_custom_js);
		if( !empty($cff_custom_js) ){
			$custom_js_output .= '<!-- Custom Facebook Feed JS -->';
			$custom_js_output .= "\r\n";
			$custom_js_output .= '<script type="text/javascript">';
			$custom_js_output .= "\r\n";
			$custom_js_output .= "function cff_custom_js($){";
			$custom_js_output .= "\r\n";
			$custom_js_output .= "var $ = jQuery;";
			$custom_js_output .= "\r\n";
			$custom_js_output .= stripslashes($cff_custom_js);
			$custom_js_output .= "\r\n";
			$custom_js_output .= "}";
			$custom_js_output .= "\r\n";
			$custom_js_output .= "cff_custom_js($);";
			$custom_js_output .= "\r\n";
			$custom_js_output .= '</script>';
			$custom_js_output .= "\r\n";
			echo $custom_js_output;
		}
	}


	/**
	 * Activate
	 *
	 * CFF activation action.
	 *
	 * @since 3.18
	 * @access public
	 */
	function cff_pro_activate( $network_wide ) {
		$options = get_option('cff_style_settings');
	    //If the post types are all set to false then set them to be true as this likely means there was an issue with the settings not saving on activation
		if( !isset($options[ 'cff_show_links_type' ]) && !isset($options[ 'cff_show_event_type' ]) && !isset($options[ 'cff_show_video_type' ]) && !isset($options[ 'cff_show_photos_type' ]) && !isset($options[ 'cff_show_status_type' ]) && !isset($options[ 'cff_show_albums_type' ]) ){
			$options[ 'cff_show_links_type' ] = true;
			$options[ 'cff_show_event_type' ] = true;
			$options[ 'cff_show_video_type' ] = true;
			$options[ 'cff_show_photos_type' ] = true;
			$options[ 'cff_show_status_type' ] = true;
			$options[ 'cff_show_albums_type' ] = true;
		}

	    //Show all parts of the feed by default on activation if they're all unset
		if( !isset($options[ 'cff_show_author' ]) && !isset($options[ 'cff_show_text' ]) && !isset($options[ 'cff_show_desc' ]) && !isset($options[ 'cff_show_shared_links' ]) && !isset($options[ 'cff_show_date' ]) && !isset($options[ 'cff_show_media' ]) && !isset($options[ 'cff_show_event_title' ]) && !isset($options[ 'cff_show_event_details' ]) && !isset($options[ 'cff_show_meta' ]) && !isset($options[ 'cff_show_link' ]) && !isset($options[ 'cff_show_facebook_link' ]) && !isset($options[ 'cff_show_facebook_share' ]) && !isset($options[ 'cff_event_title_link' ]) ){
			$options[ 'cff_show_author' ] = true;
			$options[ 'cff_show_text' ] = true;
			$options[ 'cff_show_desc' ] = true;
			$options[ 'cff_show_shared_links' ] = true;
			$options[ 'cff_show_date' ] = true;
			$options[ 'cff_show_media' ] = true;
			$options[ 'cff_show_event_title' ] = true;
			$options[ 'cff_show_event_details' ] = true;
			$options[ 'cff_show_meta' ] = true;
			$options[ 'cff_show_link' ] = true;
			$options[ 'cff_show_facebook_link' ] = true;
			$options[ 'cff_show_facebook_share' ] = true;
			$options[ 'cff_event_title_link' ] = true;
			$options[ 'cff_show_like_box' ] = true;
		}

	    //Save the settings
		update_option( 'cff_style_settings', $options );

	    //Set transient for welcome page
		set_transient( '_cff_activation_redirect', true, 30 );

	    //Run cron twice daily when plugin is first activated for new users
		if ( ! wp_next_scheduled( 'cff_cron_job' ) ) {
			wp_schedule_event( time(), 'twicedaily', 'cff_cron_job' );
		}
		if ( ! wp_next_scheduled( 'cff_feed_issue_email' ) ) {
			$options = get_option( 'cff_style_settings' );

			$input = isset( $options[ 'email_notification' ] ) ? $options[ 'email_notification' ] : 'monday';
			$timestamp = strtotime( 'next ' . $input );
			$timestamp = $timestamp + (3600 * 24 * 7);

			$six_am_local = $timestamp + CFF_Utils::cff_get_utc_offset() + (6*60*60);

			wp_schedule_event( $six_am_local, 'cffweekly', 'cff_feed_issue_email' );
		}
		if ( ! wp_next_scheduled( 'cff_notification_update' ) ) {
			$timestamp = strtotime( 'next monday' );
			$timestamp = $timestamp + (3600 * 24 * 7);
			$six_am_local = $timestamp + CFF_Utils::cff_get_utc_offset() + (6*60*60);

			wp_schedule_event( $six_am_local, 'cffweekly', 'cff_notification_update' );
		}

		if ( is_multisite() && $network_wide && function_exists( 'get_sites' ) && class_exists( 'WP_Site_Query' ) ) {

			// Get all blogs in the network and activate plugin on each one
			$sites = get_sites();
			foreach ( $sites as $site ) {
				switch_to_blog( $site->blog_id );

				$upload     = wp_upload_dir();
				$upload_dir = $upload['basedir'];
				$upload_dir = trailingslashit( $upload_dir ) . CFF_UPLOADS_NAME;
				if ( ! file_exists( $upload_dir ) ) {
					$created = wp_mkdir_p( $upload_dir );
					if ( $created ) {
						\cff_main_pro()->cff_error_reporter->remove_error( 'upload_dir' );
					} else {
						\cff_main_pro()->cff_error_reporter->add_error( 'upload_dir', array(
							__( 'There was an error creating the folder for storing resized images.', 'custom-facebook-feed' ),
							$upload_dir
						) );
					}
				} else {
					\cff_main_pro()->cff_error_reporter->remove_error( 'upload_dir' );
				}

				$this->cff_create_database_table();
				restore_current_blog();
			}

		}  else {
			$upload     = wp_upload_dir();
			$upload_dir = $upload['basedir'];
			$upload_dir = trailingslashit( $upload_dir ) . CFF_UPLOADS_NAME;
			if ( ! file_exists( $upload_dir ) ) {
				$created = wp_mkdir_p( $upload_dir );
				if ( $created ) {
					\cff_main_pro()->cff_error_reporter->remove_error( 'upload_dir' );
				} else {
					\cff_main_pro()->cff_error_reporter->add_error( 'upload_dir', array(
						__( 'There was an error creating the folder for storing resized images.', 'instagram-feed' ),
						$upload_dir
					) );
				}
			} else {
				\cff_main_pro()->cff_error_reporter->remove_error( 'upload_dir' );
			}

			$this->cff_create_database_table();
		}
	}

	/**
	 * Deactivate
	 *
	 * CFF deactivation action.
	 *
	 * @since 3.18
	 * @access public
	 */
	function cff_pro_deactivate() {
		wp_clear_scheduled_hook('cff_cron_job');
		wp_clear_scheduled_hook('cff_notification_update');
	}

	/**
	 * Uninstall
	 *
	 * CFF uninstallation action.
	 *
	 * @since 3.18
	 * @access public
	 */
	static function cff_pro_uninstall(){
		if ( ! current_user_can( 'activate_plugins' ) )
			return;

	    //Delete avatar transients after uninstalling
		global $wpdb;
		$table_name = $wpdb->prefix . "options";
		$wpdb->query( "
			DELETE
			FROM $table_name
			WHERE `option_name` LIKE ('%\_transient\_fb\_avatar\_%')
			" );
		$wpdb->query( "
			DELETE
			FROM $table_name
			WHERE `option_name` LIKE ('%\_transient\_timeout\_fb\_avatar\_%')
			" );

	    //If the user is preserving the settings then don't delete them
		$cff_preserve_settings = get_option('cff_preserve_settings');
		if($cff_preserve_settings) return;

	    //Settings
		delete_option( 'cff_show_access_token' );
		delete_option( 'cff_access_token' );
		delete_option( 'cff_page_id' );
		delete_option( 'cff_page_type' );
		delete_option( 'cff_num_show' );
		delete_option( 'cff_post_limit' );
		delete_option( 'cff_show_others' );
		delete_option( 'cff_cache_time' );
		delete_option( 'cff_cache_time_unit' );
		delete_option( 'cff_locale' );
		delete_option( 'cff_ajax' );
		delete_option( 'cff_preserve_settings' );
		delete_option('cff_extensions_status');
		delete_option('cff_welcome_seen');

	    //Style & Layout
		delete_option( 'cff_title_length' );
		delete_option( 'cff_body_length' );
		delete_option( 'cff_style_settings' );

	    //Deactivate and delete license
	    // retrieve the license from the database
		$license = trim( get_option( 'cff_license_key' ) );
	    // data to send in our API request
		$api_params = array(
			'edd_action'=> 'deactivate_license',
			'license'   => $license,
	        'item_name' => urlencode( WPW_SL_ITEM_NAME ) // the name of our product in EDD
	    );
	    // Call the custom API.
		$response = wp_remote_get( add_query_arg( $api_params, WPW_SL_STORE_URL ), array( 'timeout' => 15, 'sslverify' => false ) );
		delete_option( 'cff_license_status' );
		delete_option( 'cff_license_key' );
		wp_clear_scheduled_hook( 'cff_feed_issue_email' );

		delete_option( 'cff_usage_tracking_config' );
		delete_option( 'cff_usage_tracking' );
		delete_option( 'cff_statuses' );
		delete_option( 'cff_rating_notice' );
		delete_option( 'cff_notifications' );
		delete_option( 'cff_db_version' );

		global $wp_roles;
		$wp_roles->remove_cap( 'administrator', 'manage_custom_facebook_feed_options' );
		wp_clear_scheduled_hook( 'cff_usage_tracking_cron' );

		$upload = wp_upload_dir();

		global $wpdb;

		$posts_table_name = $wpdb->prefix . CFF_POSTS_TABLE;
		$feeds_posts_table_name = $wpdb->prefix . CFF_FEEDS_POSTS_TABLE;

		$image_files = glob( trailingslashit( $upload['basedir'] ) . trailingslashit( CFF_UPLOADS_NAME ) . '*'  ); // get all file names
		foreach ( $image_files as $file ) { // iterate files
			if ( is_file( $file ) ) {
				unlink( $file );
			}
		}

		//Delete tables
		$wpdb->query( "DROP TABLE IF EXISTS $posts_table_name" );
		$wpdb->query( "DROP TABLE IF EXISTS $feeds_posts_table_name" );

		global $wp_filesystem;

		$wp_filesystem->delete( trailingslashit( $upload['basedir'] ) . trailingslashit( 'sb-facebook-feed-images' ) , true );
	}


	/**
	 * Create Database Tables
	 *
	 *
	 * @since 3.18
	 * @access public
	 */
	function cff_create_database_table() {
		global $wpdb;
		global $wp_version;

		$table_name = esc_sql( $wpdb->prefix . CFF_POSTS_TABLE );
		$feeds_posts_table_name = esc_sql( $wpdb->prefix . CFF_FEEDS_POSTS_TABLE );
		$charset_collate = '';

		if ( version_compare( $wp_version, '3.5', '>' ) ) {
			$charset_collate = $wpdb->get_charset_collate();
		}

		if ( $wpdb->get_var( "show tables like '$table_name'" ) != $table_name ) {
			$sql = "CREATE TABLE " . $table_name . " (
			id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
			facebook_id VARCHAR(1000) DEFAULT '' NOT NULL,
			created_on DATETIME,
			last_requested DATE,
			time_stamp DATETIME,
			json_data LONGTEXT DEFAULT '' NOT NULL,
			media_id VARCHAR(1000) DEFAULT '' NOT NULL,
			sizes VARCHAR(1000) DEFAULT '' NOT NULL,
			aspect_ratio DECIMAL (4,2) DEFAULT 0 NOT NULL,
			images_done TINYINT(1) DEFAULT 0 NOT NULL
		) $charset_collate;";
		$wpdb->query( $sql );
	}

	if ( $wpdb->get_var( "show tables like '$feeds_posts_table_name'" ) != $feeds_posts_table_name ) {
		$sql = "CREATE TABLE " . $feeds_posts_table_name . " (
		record_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
		id INT(11) UNSIGNED NOT NULL,
		feed_id VARCHAR(1000) DEFAULT '' NOT NULL,
		INDEX feed_id (feed_id(100))
	) $charset_collate;";
	$wpdb->query( $sql );
}

return $wpdb->get_var( "show tables like '$table_name'" ) === $table_name;
}

	/**
	 * Cron Custom Interval
	 *
	 * Cron Job Custom Interval
	 *
	 * @since 3.18
	 * @access public
	 */
	function cff_pro_cron_custom_interval( $schedules ) {
		$schedules['cffweekly'] = array(
			'interval' => 3600 * 24 * 7,
			'display'  => __( 'Weekly' )
		);

		return $schedules;
	}


	/**
	 * Check for update
	 *
	 *
	 * @since 3.18
	 * @access public
	 */
	function cff_pro_check_for_db_updates() {

		$db_ver = get_option( 'cff_db_version', 0 );

		if ( (float) $db_ver < 1.1 ) {
			if ( ! wp_next_scheduled( 'cff_feed_issue_email' ) ) {
				$timestamp = strtotime( 'next monday' );
				$timestamp = $timestamp + (3600 * 24 * 7);
				$six_am_local = $timestamp + get_option( 'gmt_offset', 0 ) * HOUR_IN_SECONDS + (6*60*60);

				wp_schedule_event( $six_am_local, 'cffweekly', 'cff_feed_issue_email' );
			}

			update_option( 'cff_db_version', CFF_DBVERSION );
		}

		if ( (float) $db_ver < 1.2 ) {
			$upload     = wp_upload_dir();
			$upload_dir = $upload['basedir'];
			$upload_dir = trailingslashit( $upload_dir ) . CFF_UPLOADS_NAME;
			if ( ! file_exists( $upload_dir ) ) {
				$created = wp_mkdir_p( $upload_dir );
				if ( $created ) {
					\cff_main_pro()->cff_error_reporter->remove_error( 'upload_dir' );
				}
			}

			$this->cff_create_database_table();

			update_option( 'cff_db_version', CFF_DBVERSION );
		}

		if ( (float) $db_ver < 1.3 ) {
			if ( ! wp_next_scheduled( 'cff_notification_update' ) ) {
				$timestamp = strtotime( 'next monday' );
				$timestamp = $timestamp + (3600 * 24 * 7);
				$six_am_local = $timestamp + CFF_Utils::cff_get_utc_offset() + (6*60*60);

				wp_schedule_event( $six_am_local, 'cffweekly', 'cff_notification_update' );
			}
			update_option( 'cff_db_version', CFF_DBVERSION );
		}

		if ( (float) $db_ver < 1.4 ) {
			$cff_statuses_option = get_option( 'cff_statuses', array() );
			$options = get_option( 'cff_style_settings', array() );
			$disable_resizing = isset( $options['cff_disable_resize'] ) ? $options['cff_disable_resize'] === 'on' || $options['cff_disable_resize'] === true : false;

			if ( $disable_resizing || ! CFF_GDPR_Integrations::gdpr_tests_successful( true ) ) {
				$cff_statuses_option['gdpr']['from_update_success'] = false;
			} else {
				$cff_statuses_option['gdpr']['from_update_success'] = true;
			}

			update_option( 'cff_statuses', $cff_statuses_option );

			update_option( 'cff_db_version', CFF_DBVERSION );
		}


		if ( (float) $db_ver < 1.5 ) {
			CFF_Feed_Locator::create_table();
			update_option( 'cff_db_version', CFF_DBVERSION );
		}



	}


	/**
	 * Update plugin using EDD
	 *
	 *
	 * @since 3.18
	 * @access public
	 */
	function cff_plugin_updates() {
	    // retrieve our license key from the DB
		$cff_license_key = trim( get_option( 'cff_license_key' ) );
	    // setup the updater
		$edd_updater = new EDD_SL_Plugin_Updater( WPW_SL_STORE_URL, CFF_FILE, array(
	            'version'   => CFFVER,           // current version number
	            'license'   => $cff_license_key,        // license key (used get_option above to retrieve from DB)
	            'item_name' => WPW_SL_ITEM_NAME,    // name of this plugin
	            'author'    => 'Smash Balloon'      // author of this plugin
	        )
		);
	}

}
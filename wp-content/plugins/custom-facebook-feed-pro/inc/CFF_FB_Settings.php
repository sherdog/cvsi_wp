<?php
/**
 * Class CFF_FB_Options
 *
 * Creates a list of necessary options and atts for the Shortcode class
 *
 * @since 3.18
 */
namespace CustomFacebookFeed;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CFF_FB_Settings{
	/**
	 * @var array
	 */
	protected $atts;

	/**
	 * @var array
	 */
	protected $options;

	/**
	 * @var array
	 */
	protected static $ext_options;

	/**
	 * @var array
	 */
	protected $settings;

	/**
	 * @var id
	 */
	protected $page_id;

	/**
	 * @var string
	 */
	protected $access_token;

	/**
	 * @var array
	 */
	public static $extensions_list = [
		'multifeed' => [
			'opt' 	=> 'cff_extensions_multifeed_active',
			'path' 	=> 'cff-multifeed/cff-multifeed.php'
		],
		'date_range' => [
			'opt' 	=> 'cff_extensions_date_range_active',
			'path' 	=> 'cff-date-range/cff-date-range.php'
		],
		'featured_post' => [
			'opt' 	=> 'cff_extensions_featured_post_active',
			'path' 	=> 'cff-featured-post/cff-featured-post.php'
		],
		'album' => [
			'opt' 	=> 'cff_extensions_album_active',
			'path' 	=> 'cff-album/cff-album.php'
		],
		'carousel' => [
			'opt' 	=> 'cff_extensions_carousel_active',
			'path' 	=> 'cff-carousel/cff-carousel.php'
		],
		'reviews' => [
			'opt' 	=> 'cff_extensions_reviews_active',
			'path' 	=> 'cff-reviews/cff-reviews.php'
		]
	];


	/**
	 * CFF_FB_Options constructor.
	 *
	 *
	 * @param array $atts shortcode settings
	 * @param array $options settings from the wp_options table
	 *
 	 * @since 3.18
	 */
	public function __construct( $atts, $options ) {
		$this->atts 					= $atts;
		$this->options   				= $options;

		$include_string 				= $this->get_include_string();
		$type_string 					= $this->get_type_string();
		$cff_reviews_string 			= $this->get_reviews_string();
		$cff_masonry_options			= get_option('cff_masonry_options');

		$cff_ext_multifeed_active		= self::check_active_extension('multifeed');
		$cff_ext_date_active			= self::check_active_extension('date_range');
		$cff_featured_post_active		= self::check_active_extension('featured_post');
		$cff_album_active				= self::check_active_extension('album');
		$cff_carousel_active			= self::check_active_extension('carousel');
		$cff_reviews_active				= self::check_active_extension('reviews');

		$this->settings = shortcode_atts(
		    array(
		        'accesstoken' 				=> trim(get_option('cff_access_token')),
		        'ownaccesstoken' 			=> true,
		        'pagetoken' 				=> get_option('cff_page_access_token'),
		        'id' 						=> get_option('cff_page_id'),
		        'pagetype' 					=> get_option('cff_page_type'),
		        'num' 						=> get_option('cff_num_show'),
		        'limit' 					=> get_option('cff_post_limit'),
		        'others' 					=> '',
		        'showpostsby' 				=> get_option('cff_show_others'),
		        'cachetype' 				=> get_option('cff_caching_type'),
		        'cachetime' 				=> get_option('cff_cache_time'),
		        'cacheunit' 				=> get_option('cff_cache_time_unit'),
		        'locale' 					=> get_option('cff_locale'),
		        'ajax' 						=> get_option('cff_ajax'),
		        'offset' 					=> '',
		        'account' 					=> '',

		        //General
		        'cff_enqueue_with_shortcode' => isset($options[ 'cff_enqueue_with_shortcode' ]) ? $options[ 'cff_enqueue_with_shortcode' ] : false,
		        'width' 					=> isset($options[ 'cff_feed_width' ]) ? $options[ 'cff_feed_width' ] : '',
		        'widthresp' 				=> isset($options[ 'cff_feed_width_resp' ]) ? $options[ 'cff_feed_width_resp' ] : '',
		        'height' 					=> isset($options[ 'cff_feed_height' ]) ? $options[ 'cff_feed_height' ] : '',
		        'padding' 					=> isset($options[ 'cff_feed_padding' ]) ? $options[ 'cff_feed_padding' ] : '',
		        'bgcolor' 					=> isset($options[ 'cff_bg_color' ]) ? $options[ 'cff_bg_color' ] : '',
		        'showauthor' 				=> '',
		        'showauthornew' 			=> isset($options[ 'cff_show_author' ]) ? $options[ 'cff_show_author' ] : '',
		        'class' 					=> isset($options[ 'cff_class' ]) ? $options[ 'cff_class' ] : '',
		        'type' 						=> $type_string,
		        'gdpr' 						=> isset($options[ 'gdpr' ]) ? $options[ 'gdpr' ] : 'auto',
        		'loadiframes' => 'false',

			    //Events only
		        'eventsource' 				=> isset($options[ 'cff_events_source' ]) ? $options[ 'cff_events_source' ] : '',
		        'eventoffset' 				=> isset($options[ 'cff_event_offset' ]) ? $options[ 'cff_event_offset' ] : '',
		        'eventimage' 				=> isset($options[ 'cff_event_image_size' ]) ? $options[ 'cff_event_image_size' ] : '',
		        'pastevents' 				=> 'false',
		        //Albums only
		        'albumsource' 				=> isset($options[ 'cff_albums_source' ]) ? $options[ 'cff_albums_source' ] : '',
		        'showalbumtitle' 			=> isset($options[ 'cff_show_album_title' ]) ? $options[ 'cff_show_album_title' ] : '',
		        'showalbumnum' 				=> isset($options[ 'cff_show_album_number' ]) ? $options[ 'cff_show_album_number' ] : '',
		        'albumcols' 				=> isset($options[ 'cff_album_cols' ]) ? $options[ 'cff_album_cols' ] : '',
		        //Photos only
		        'photosource' 				=> isset($options[ 'cff_photos_source' ]) ? $options[ 'cff_photos_source' ] : '',
		        'photocols' 				=> isset($options[ 'cff_photos_cols' ]) ? $options[ 'cff_photos_cols' ] : '',
		        //Videos only
		        'videosource' 				=> isset($options[ 'cff_videos_source' ]) ? $options[ 'cff_videos_source' ] : '',
		        'showvideoname' 			=> isset($options[ 'cff_show_video_name' ]) ? $options[ 'cff_show_video_name' ] : '',
		        'showvideodesc' 			=> isset($options[ 'cff_show_video_desc' ]) ? $options[ 'cff_show_video_desc' ] : '',
		        'videocols' 				=> isset($options[ 'cff_video_cols' ]) ? $options[ 'cff_video_cols' ] : '',
		        'playlist' 					=> '',

		        //Lightbox
		        'disablelightbox' 			=> isset($options[ 'cff_disable_lightbox' ]) ? $options[ 'cff_disable_lightbox' ] : '',

		        //Filters
		        'filter' 					=> isset($options[ 'cff_filter_string' ]) ? trim($options[ 'cff_filter_string' ]) : '',
		        'exfilter' 					=> isset($options[ 'cff_exclude_string' ]) ? $options[ 'cff_exclude_string' ] : '',

		        //Post Layout
		        'layout' 					=> isset($options[ 'cff_preset_layout' ]) ? $options[ 'cff_preset_layout' ] : '',
		        'enablenarrow' 				=> isset($options[ 'cff_enable_narrow' ]) ? $options[ 'cff_enable_narrow' ] : '',
		        'oneimage' 					=> isset($options[ 'cff_one_image' ]) ? $options[ 'cff_one_image' ] : '',

		        'mediaposition' 			=> isset($options[ 'cff_media_position' ]) ? $options[ 'cff_media_position' ] : '',
		        'include' 					=> $include_string,
		        'exclude' 					=> '',

		        //Masonry
		        'masonry' 					=> isset($cff_masonry_options[ 'cff_masonry_enabled' ]) ? $cff_masonry_options[ 'cff_masonry_enabled' ] : '',
		        'masonrycols' 				=> isset($cff_masonry_options[ 'cff_masonry_desktop_col' ]) ? $cff_masonry_options[ 'cff_masonry_desktop_col' ] : '',
		        'masonrycolsmobile' 		=> isset($cff_masonry_options[ 'cff_masonry_mobile_col' ]) ? $cff_masonry_options[ 'cff_masonry_mobile_col' ] : '',
		        'masonryjs' 				=> true,

		        //New masonry options
		        'cols' 						=> isset($options[ 'cff_masonry_desktop_col' ]) ? $options[ 'cff_masonry_desktop_col' ] : '',
		        'colsmobile'				=> isset($options[ 'cff_masonry_mobile_col' ]) ? $options[ 'cff_masonry_mobile_col' ] : '',
		        'colsjs' 					=> true,

		        //Mobile settings
			    'nummobile' 				=> isset($options[ 'cff_num_mobile' ]) ? max( 0, (int)$options[ 'cff_num_mobile' ] ) : '',

		        //Post Style
		        'poststyle' 				=> isset($options[ 'cff_post_style' ]) ? $options[ 'cff_post_style' ] : '',
		        'postbgcolor' 				=> isset($options[ 'cff_post_bg_color' ]) ? $options[ 'cff_post_bg_color' ] : '',
		        'postcorners' 				=> isset($options[ 'cff_post_rounded' ]) ? $options[ 'cff_post_rounded' ] : '',
		        'boxshadow' 				=> isset($options[ 'cff_box_shadow' ]) ? $options[ 'cff_box_shadow' ] : '',

		        //Typography
		        'textformat' 				=> isset($options[ 'cff_title_format' ]) ? $options[ 'cff_title_format' ] : '',
		        'textsize' 					=> isset($options[ 'cff_title_size' ]) ? $options[ 'cff_title_size' ] : '',
		        'textweight' 				=> isset($options[ 'cff_title_weight' ]) ? $options[ 'cff_title_weight' ] : '',
		        'textcolor' 				=> isset($options[ 'cff_title_color' ]) ? $options[ 'cff_title_color' ] : '',
		        'textlinkcolor' 			=> isset($options[ 'cff_posttext_link_color' ]) ? $options[ 'cff_posttext_link_color' ] : '',
		        'textlink' 					=> isset($options[ 'cff_title_link' ]) ? $options[ 'cff_title_link' ] : '',
		        'posttags' 					=> isset($options[ 'cff_post_tags' ]) ? $options[ 'cff_post_tags' ] : '',
		        'linkhashtags' 				=> isset($options[ 'cff_link_hashtags' ]) ? $options[ 'cff_link_hashtags' ] : '',
		        'lightboxcomments' 			=> isset($options[ 'cff_lightbox_comments' ]) ? $options[ 'cff_lightbox_comments' ] : true,

		        //Author
		        'authorsize' 				=> isset($options[ 'cff_author_size' ]) ? $options[ 'cff_author_size' ] : '',
		        'authorcolor' 				=> isset($options[ 'cff_author_color' ]) ? $options[ 'cff_author_color' ] : '',

		        //Description
		        'descsize' 					=> isset($options[ 'cff_body_size' ]) ? $options[ 'cff_body_size' ] : '',
		        'descweight' 				=> isset($options[ 'cff_body_weight' ]) ? $options[ 'cff_body_weight' ] : '',
		        'desccolor' 				=> isset($options[ 'cff_body_color' ]) ? $options[ 'cff_body_color' ] : '',
		        'linktitleformat' 			=> isset($options[ 'cff_link_title_format' ]) ? $options[ 'cff_link_title_format' ] : '',
		        'linktitlesize' 			=> isset($options[ 'cff_link_title_size' ]) ? $options[ 'cff_link_title_size' ] : '',
		        'linkdescsize' 				=> isset($options[ 'cff_link_desc_size' ]) ? $options[ 'cff_link_desc_size' ] : '',
		        'linkurlsize' 				=> isset($options[ 'cff_link_url_size' ]) ? $options[ 'cff_link_url_size' ] : '',
		        'linkdesccolor' 			=> isset($options[ 'cff_link_desc_color' ]) ? $options[ 'cff_link_desc_color' ] : '',
		        'linktitlecolor' 			=> isset($options[ 'cff_link_title_color' ]) ? $options[ 'cff_link_title_color' ] : '',
		        'linkurlcolor' 				=> isset($options[ 'cff_link_url_color' ]) ? $options[ 'cff_link_url_color' ] : '',
		        'linkbgcolor' 				=> isset($options[ 'cff_link_bg_color' ]) ? $options[ 'cff_link_bg_color' ] : '',
		        'linkbordercolor' 			=> isset($options[ 'cff_link_border_color' ]) ? $options[ 'cff_link_border_color' ] : '',
		        'disablelinkbox' 			=> isset($options[ 'cff_disable_link_box' ]) ? $options[ 'cff_disable_link_box' ] : '',


		        //Event title
		        'eventtitleformat' 			=> isset($options[ 'cff_event_title_format' ]) ? $options[ 'cff_event_title_format' ] : '',
		        'eventtitlesize' 			=> isset($options[ 'cff_event_title_size' ]) ? $options[ 'cff_event_title_size' ] : '',
		        'eventtitleweight' 			=> isset($options[ 'cff_event_title_weight' ]) ? $options[ 'cff_event_title_weight' ] : '',
		        'eventtitlecolor' 			=> isset($options[ 'cff_event_title_color' ]) ? $options[ 'cff_event_title_color' ] : '',
		        'eventtitlelink' 			=> isset($options[ 'cff_event_title_link' ]) ? $options[ 'cff_event_title_link' ] : '',
		        //Event date
		        'eventdatesize' 			=> isset($options[ 'cff_event_date_size' ]) ? $options[ 'cff_event_date_size' ] : '',
		        'eventdateweight' 			=> isset($options[ 'cff_event_date_weight' ]) ? $options[ 'cff_event_date_weight' ] : '',
		        'eventdatecolor' 			=> isset($options[ 'cff_event_date_color' ]) ? $options[ 'cff_event_date_color' ] : '',
		        'eventdatepos' 				=> isset($options[ 'cff_event_date_position' ]) ? $options[ 'cff_event_date_position' ] : '',
		        'eventdateformat' 			=> isset($options[ 'cff_event_date_formatting' ]) ? $options[ 'cff_event_date_formatting' ] : '',
		        'eventdatecustom' 			=> isset($options[ 'cff_event_date_custom' ]) ? $options[ 'cff_event_date_custom' ] : '',
		        'timezoneoffset' 			=> 'false',

		        //Event details
		        'eventdetailssize' 			=> isset($options[ 'cff_event_details_size' ]) ? $options[ 'cff_event_details_size' ] : '',
		        'eventdetailsweight' 		=> isset($options[ 'cff_event_details_weight' ]) ? $options[ 'cff_event_details_weight' ] : '',
		        'eventdetailscolor' 		=> isset($options[ 'cff_event_details_color' ]) ? $options[ 'cff_event_details_color' ] : '',
		        'eventlinkcolor' 			=> isset($options[ 'cff_event_link_color' ]) ? $options[ 'cff_event_link_color' ] : '',

		        //Date
		        'datepos' 					=> isset($options[ 'cff_date_position' ]) ? $options[ 'cff_date_position' ] : '',
		        'datesize' 					=> isset($options[ 'cff_date_size' ]) ? $options[ 'cff_date_size' ] : '',
		        'dateweight' 				=> isset($options[ 'cff_date_weight' ]) ? $options[ 'cff_date_weight' ] : '',
		        'datecolor' 				=> isset($options[ 'cff_date_color' ]) ? $options[ 'cff_date_color' ] : '',
		        'dateformat' 				=> isset($options[ 'cff_date_formatting' ]) ? $options[ 'cff_date_formatting' ] : '',
		        'datecustom' 				=> isset($options[ 'cff_date_custom' ]) ? $options[ 'cff_date_custom' ] : '',
		        'timezone' 					=> isset($options[ 'cff_timezone' ]) ? $options[ 'cff_timezone' ] : 'America/Chicago',
		        'beforedate' 				=> isset($options[ 'cff_date_before' ]) ? $options[ 'cff_date_before' ] : '',
		        'afterdate' 				=> isset($options[ 'cff_date_after' ]) ? $options[ 'cff_date_after' ] : '',

		        //Link to Facebook
		        'linksize' 					=> isset($options[ 'cff_link_size' ]) ? $options[ 'cff_link_size' ] : '',
		        'linkweight' 				=> isset($options[ 'cff_link_weight' ]) ? $options[ 'cff_link_weight' ] : '',
		        'linkcolor' 				=> isset($options[ 'cff_link_color' ]) ? $options[ 'cff_link_color' ] : '',
		        'viewlinktext' 				=> isset($options[ 'cff_view_link_text' ]) ? $options[ 'cff_view_link_text' ] : '',
		        'linktotimeline' 			=> isset($options[ 'cff_link_to_timeline' ]) ? $options[ 'cff_link_to_timeline' ] : '',

		        //Load more button
		        'buttoncolor' 				=> isset($options[ 'cff_load_more_bg' ]) ? $options[ 'cff_load_more_bg' ] : '',
		        'buttonhovercolor' 			=> isset($options[ 'cff_load_more_bg_hover' ]) ? $options[ 'cff_load_more_bg_hover' ] : '',
		        'buttontextcolor' 			=> isset($options[ 'cff_load_more_text_color' ]) ? $options[ 'cff_load_more_text_color' ] : '',
		        'buttontext' 				=> isset($options[ 'cff_load_more_text' ]) ? $options[ 'cff_load_more_text' ] : '',
		        'nomoretext' 				=> isset($options[ 'cff_no_more_posts_text' ]) ? $options[ 'cff_no_more_posts_text' ] : '',

		        //Social
		        'iconstyle' 				=> isset($options[ 'cff_icon_style' ]) ? $options[ 'cff_icon_style' ] : '',
		        'socialtextcolor' 			=> isset($options[ 'cff_meta_text_color' ]) ? $options[ 'cff_meta_text_color' ] : '',
		        'socialbgcolor' 			=> isset($options[ 'cff_meta_bg_color' ]) ? $options[ 'cff_meta_bg_color' ] : '',
		        'sociallinkcolor' 			=> isset($options[ 'cff_meta_link_color' ]) ? $options[ 'cff_meta_link_color' ] : '',
		        'expandcomments' 			=> isset($options[ 'cff_expand_comments' ]) ? $options[ 'cff_expand_comments' ] : '',
		        'commentsnum' 				=> isset($options[ 'cff_comments_num' ]) ? $options[ 'cff_comments_num' ] : '',
		        'hidecommentimages' 		=> isset($options[ 'cff_hide_comment_avatars' ]) ? $options[ 'cff_hide_comment_avatars' ] : '',
		        'loadcommentsjs' 			=> 'false',
		        'salesposts' 			    => 'false',
		        'storytags' => 'false',

		        //Misc
		        'textlength' 				=> get_option('cff_title_length'),
		        'desclength' 				=> get_option('cff_body_length'),
		        'likeboxpos' 				=> isset($options[ 'cff_like_box_position' ]) ? $options[ 'cff_like_box_position' ] : '',
		        'likeboxoutside' 			=> isset($options[ 'cff_like_box_outside' ]) ? $options[ 'cff_like_box_outside' ] : '',
		        'likeboxcolor' 				=> isset($options[ 'cff_likebox_bg_color' ]) ? $options[ 'cff_likebox_bg_color' ] : '',
		        'likeboxtextcolor' 			=> isset($options[ 'cff_like_box_text_color' ]) ? $options[ 'cff_like_box_text_color' ] : '',
		        'likeboxwidth' 				=> isset($options[ 'cff_likebox_width' ]) ? $options[ 'cff_likebox_width' ] : '',
		        'likeboxfaces' 				=> isset($options[ 'cff_like_box_faces' ]) ? $options[ 'cff_like_box_faces' ] : '',
		        'likeboxborder' 			=> isset($options[ 'cff_like_box_border' ]) ? $options[ 'cff_like_box_border' ] : '',
		        'likeboxcover' 				=> isset($options[ 'cff_like_box_cover' ]) ? $options[ 'cff_like_box_cover' ] : '',
		        'likeboxsmallheader' 		=> isset($options[ 'cff_like_box_small_header' ]) ? $options[ 'cff_like_box_small_header' ] : '',
		        'likeboxhidebtn' 			=> isset($options[ 'cff_like_box_hide_cta' ]) ? $options[ 'cff_like_box_hide_cta' ] : '',

		        'credit' 					=> isset($options[ 'cff_show_credit' ]) ? $options[ 'cff_show_credit' ] : '',
		        'textissue' 				=> isset($options[ 'cff_format_issue' ]) ? $options[ 'cff_format_issue' ] : '',
		        'disablesvgs' 				=> isset($options[ 'cff_disable_svgs' ]) ? $options[ 'cff_disable_svgs' ] : '',
		        'restrictedpage' 			=> isset($options[ 'cff_restricted_page' ]) ? $options[ 'cff_restricted_page' ] : '',
		        'hidesupporterposts' 		=> isset($options[ 'cff_hide_supporter_posts' ]) ? $options[ 'cff_hide_supporter_posts' ] : '',
		        'privategroup' 				=> 'false',
		        'nofollow' 					=> 'true',
		        'timelinepag' 				=> isset($options[ 'cff_timeline_pag' ]) ? $options[ 'cff_timeline_pag' ] : '',
		        'gridpag' 					=> isset($options[ 'cff_grid_pag' ]) ? $options[ 'cff_grid_pag' ] : '',
		        'disableresize' 			=> isset($options[ 'cff_disable_resize' ]) ? $options[ 'cff_disable_resize' ] : false,


		        //Page Header
		        'showheader' 				=> isset($options[ 'cff_show_header' ]) ? $options[ 'cff_show_header' ] : '',
		        'headertype' 				=> isset($options[ 'cff_header_type' ]) ? $options[ 'cff_header_type' ] : '',
		        'headercover' 				=> isset($options[ 'cff_header_cover' ]) ? $options[ 'cff_header_cover' ] : '',
		        'headeravatar' 				=> isset($options[ 'cff_header_avatar' ]) ? $options[ 'cff_header_avatar' ] : '',
		        'headername' 				=> isset($options[ 'cff_header_name' ]) ? $options[ 'cff_header_name' ] : '',
		        'headerbio' 				=> isset($options[ 'cff_header_bio' ]) ? $options[ 'cff_header_bio' ] : '',
		        'headercoverheight' 		=> isset($options[ 'cff_header_cover_height' ]) ? $options[ 'cff_header_cover_height' ] : '',
		        'headerlikes' 				=> isset($options[ 'cff_header_likes' ]) ? $options[ 'cff_header_likes' ] : '',
		        'headeroutside' 			=> isset($options[ 'cff_header_outside' ]) ? $options[ 'cff_header_outside' ] : '',
		        'headertext' 				=> isset($options[ 'cff_header_text' ]) ? $options[ 'cff_header_text' ] : '',
		        'headerbg' 					=> isset($options[ 'cff_header_bg_color' ]) ? $options[ 'cff_header_bg_color' ] : '',
		        'headerpadding' 			=> isset($options[ 'cff_header_padding' ]) ? $options[ 'cff_header_padding' ] : '',
		        'headertextsize' 			=> isset($options[ 'cff_header_text_size' ]) ? $options[ 'cff_header_text_size' ] : '',
		        'headertextweight' 			=> isset($options[ 'cff_header_text_weight' ]) ? $options[ 'cff_header_text_weight' ] : '',
		        'headertextcolor' 			=> isset($options[ 'cff_header_text_color' ]) ? $options[ 'cff_header_text_color' ] : '',
		        'headericon' 				=> isset($options[ 'cff_header_icon' ]) ? $options[ 'cff_header_icon' ] : '',
		        'headericoncolor' 			=> isset($options[ 'cff_header_icon_color' ]) ? $options[ 'cff_header_icon_color' ] : '',
		        'headericonsize' 			=> isset($options[ 'cff_header_icon_size' ]) ? $options[ 'cff_header_icon_size' ] : '',
		        'headerinc' 				=> '',
		        'headerexclude' 			=> '',

		        //Load More button
		        'loadmore' 					=> get_option('cff_load_more'),

		        //Misc
		        'fulllinkimages' 			=> isset($options[ 'cff_full_link_images' ]) ? $options[ 'cff_full_link_images' ] : '',
		        'linkimagesize' 			=> isset($options[ 'cff_link_image_size' ]) ? $options[ 'cff_link_image_size' ] : '',
		        'postimagesize' 			=> isset($options[ 'cff_image_size' ]) ? $options[ 'cff_image_size' ] : '',
		        'videoheight' 				=> isset($options[ 'cff_video_height' ]) ? $options[ 'cff_video_height' ] : '',
		        'videoaction' 				=> isset($options[ 'cff_video_action' ]) ? $options[ 'cff_video_action' ] : '',
		        'videoplayer' 				=> isset($options[ 'cff_video_player' ]) ? $options[ 'cff_video_player' ] : '',
		        'sepcolor' 					=> isset($options[ 'cff_sep_color' ]) ? $options[ 'cff_sep_color' ] : '',
		        'sepsize' 					=> isset($options[ 'cff_sep_size' ]) ? $options[ 'cff_sep_size' ] : '',

		        //Translate
		        'seemoretext' 				=> isset( $options[ 'cff_see_more_text' ] ) ? stripslashes( esc_attr( $options[ 'cff_see_more_text' ] ) ) : '',
		        'seelesstext' 				=> isset( $options[ 'cff_see_less_text' ] ) ? stripslashes( esc_attr( $options[ 'cff_see_less_text' ] ) ) : '',
		        'photostext' 				=> isset( $options[ 'cff_translate_photos_text' ] ) ? stripslashes( esc_attr( $options[ 'cff_translate_photos_text' ] ) ) : '',
		        'facebooklinktext' 			=> isset( $options[ 'cff_facebook_link_text' ] ) ? stripslashes( esc_attr( $options[ 'cff_facebook_link_text' ] ) ) : '',
		        'sharelinktext' 			=> isset( $options[ 'cff_facebook_share_text' ] ) ? stripslashes( esc_attr( $options[ 'cff_facebook_share_text' ] ) ) : '',
		        'showfacebooklink' 			=> isset($options[ 'cff_show_facebook_link' ]) ? $options[ 'cff_show_facebook_link' ] : '',
		        'showsharelink' 			=> isset($options[ 'cff_show_facebook_share' ]) ? $options[ 'cff_show_facebook_share' ] : '',
		        'buyticketstext' 			=> isset($options[ 'cff_buy_tickets_text' ]) ? $options[ 'cff_buy_tickets_text' ] : '',

		        'maptext' 					=> isset( $options[ 'cff_map_text' ] ) ? stripslashes( esc_attr( $options[ 'cff_map_text' ] ) ) : '',
		        'interestedtext' 			=> isset( $options[ 'cff_interested_text' ] ) ? stripslashes( esc_attr( $options[ 'cff_interested_text' ] ) ) : '',
		        'goingtext' 				=> isset( $options[ 'cff_going_text' ] ) ? stripslashes( esc_attr( $options[ 'cff_going_text' ] ) ) : '',

		        'previouscommentstext' 		=> isset( $options[ 'cff_translate_view_previous_comments_text' ] ) ? stripslashes( esc_attr( $options[ 'cff_translate_view_previous_comments_text' ] ) ) : '',
		        'commentonfacebooktext' 	=> isset( $options[ 'cff_translate_comment_on_facebook_text' ] ) ? stripslashes( esc_attr( $options[ 'cff_translate_comment_on_facebook_text' ] ) ) : '',
		        'likesthistext' 			=> isset( $options[ 'cff_translate_likes_this_text' ] ) ? stripslashes( esc_attr( $options[ 'cff_translate_likes_this_text' ] ) ) : '',
		        'likethistext' 				=> isset( $options[ 'cff_translate_like_this_text' ] ) ? stripslashes( esc_attr( $options[ 'cff_translate_like_this_text' ] ) ) : '',
		        'reactedtothistext' 		=> isset( $options[ 'cff_translate_reacted_text' ] ) ? stripslashes( esc_attr( $options[ 'cff_translate_reacted_text' ] ) ) : '',
		        'andtext' 					=> isset( $options[ 'cff_translate_and_text' ] ) ? stripslashes( esc_attr( $options[ 'cff_translate_and_text' ] ) ) : '',
		        'othertext' 				=> isset( $options[ 'cff_translate_other_text' ] ) ? stripslashes( esc_attr( $options[ 'cff_translate_other_text' ] ) ) : '',
		        'otherstext' 				=> isset( $options[ 'cff_translate_others_text' ] ) ? stripslashes( esc_attr( $options[ 'cff_translate_others_text' ] ) ) : '',
		        'noeventstext' 				=> isset( $options[ 'cff_no_events_text' ] ) ? stripslashes( esc_attr( $options[ 'cff_no_events_text' ] ) ) : '',
		        'replytext' 				=> isset( $options[ 'cff_translate_reply_text' ] ) ? stripslashes( esc_attr( $options[ 'cff_translate_reply_text' ] ) ) : '',
		        'repliestext' 				=> isset( $options[ 'cff_translate_replies_text' ] ) ? stripslashes( esc_attr( $options[ 'cff_translate_replies_text' ] ) ) : '',

		        'learnmoretext' 			=> isset( $options[ 'cff_translate_learn_more_text' ] ) ? stripslashes( esc_attr( $options[ 'cff_translate_learn_more_text' ] ) ) : '',
		        'shopnowtext' 				=> isset( $options[ 'cff_translate_shop_now_text' ] ) ? stripslashes( esc_attr( $options[ 'cff_translate_shop_now_text' ] ) ) : '',
		        'messagepage' 				=> isset( $options[ 'cff_translate_message_page_text' ] ) ? stripslashes( esc_attr( $options[ 'cff_translate_message_page_text' ] ) ) : '',
		        'getdirections' 			=> isset( $options[ 'cff_translate_get_directions_text' ] ) ? stripslashes( esc_attr( $options[ 'cff_translate_get_directions_text' ] ) ) : '',

		        'secondtext' 				=> isset( $options[ 'cff_translate_second' ] ) ? stripslashes( esc_attr( $options[ 'cff_translate_second' ] ) ) : 'second',
		        'secondstext' 				=> isset( $options[ 'cff_translate_seconds' ] ) ? stripslashes( esc_attr( $options[ 'cff_translate_seconds' ] ) ) : 'seconds',
		        'minutetext' 				=> isset( $options[ 'cff_translate_minute' ] ) ? stripslashes( esc_attr( $options[ 'cff_translate_minute' ] ) ) : 'minute',
		        'minutestext' 				=> isset( $options[ 'cff_translate_minutes' ] ) ? stripslashes( esc_attr( $options[ 'cff_translate_minutes' ] ) ) : 'minutes',
		        'hourtext' 					=> isset( $options[ 'cff_translate_hour' ] ) ? stripslashes( esc_attr( $options[ 'cff_translate_hour' ] ) ) : 'hour',
		        'hourstext' 				=> isset( $options[ 'cff_translate_hours' ] ) ? stripslashes( esc_attr( $options[ 'cff_translate_hours' ] ) ) : 'hours',
		        'daytext' 					=> isset( $options[ 'cff_translate_day' ] ) ? stripslashes( esc_attr( $options[ 'cff_translate_day' ] ) ) : 'day',
		        'daystext' 					=> isset( $options[ 'cff_translate_days' ] ) ? stripslashes( esc_attr( $options[ 'cff_translate_days' ] ) ) : 'days',
		        'weektext' 					=> isset( $options[ 'cff_translate_week' ] ) ? stripslashes( esc_attr( $options[ 'cff_translate_week' ] ) ) : 'week',
		        'weekstext' 				=> isset( $options[ 'cff_translate_weeks' ] ) ? stripslashes( esc_attr( $options[ 'cff_translate_weeks' ] ) ) : 'weeks',
		        'monthtext' 				=> isset( $options[ 'cff_translate_month' ] ) ? stripslashes( esc_attr( $options[ 'cff_translate_month' ] ) ) : 'month',
		        'monthstext' 				=> isset( $options[ 'cff_translate_months' ] ) ? stripslashes( esc_attr( $options[ 'cff_translate_months' ] ) ) : 'months',
		        'yeartext' 					=> isset( $options[ 'cff_translate_year' ] ) ? stripslashes( esc_attr( $options[ 'cff_translate_year' ] ) ) : 'year',
		        'yearstext' 				=> isset( $options[ 'cff_translate_years' ] ) ? stripslashes( esc_attr( $options[ 'cff_translate_years' ] ) ) : 'years',
		        'agotext' 					=> isset( $options[ 'cff_translate_ago' ] ) ? stripslashes( esc_attr( $options[ 'cff_translate_ago' ] ) ) : 'ago',

		        //Active extensions
		        'multifeedactive' 			=> $cff_ext_multifeed_active,
		        'daterangeactive' 			=> $cff_ext_date_active,
		        'featuredpostactive' 		=> $cff_featured_post_active,
		        'albumactive' 				=> $cff_album_active,
		        'masonryactive' 			=> false, //Deprecated
		        'carouselactive' 			=> $cff_carousel_active,
		        'reviewsactive' 			=> $cff_reviews_active,

		        //Extension settings
		        'from' 						=> get_option( 'cff_date_from' ),
		        'until' 					=> get_option( 'cff_date_until' ),
		        'featuredpost' 				=> get_option( 'cff_featured_post_id' ),
		        'album' 					=> '',
		        'lightbox' 					=> get_option('cff_lightbox'),
		        //Reviews
		        'reviewsrated' 				=> $cff_reviews_string,
		        'starsize' 					=> isset($options[ 'cff_star_size' ]) ? $options[ 'cff_star_size' ] : '',
		        'hidenegative' 				=> isset( $options[ 'cff_reviews_hide_negative' ] ) ? stripslashes( esc_attr( $options[ 'cff_reviews_hide_negative' ] ) ) : '',
		        'reviewslinktext' 			=> isset( $options[ 'cff_reviews_link_text' ] ) ? stripslashes( esc_attr( $options[ 'cff_reviews_link_text' ] ) ) : '',
		        'reviewshidenotext' 		=> isset( $options[ 'cff_reviews_no_text' ] ) ? stripslashes( esc_attr( $options[ 'cff_reviews_no_text' ] ) ) : '',
		        'reviewsmethod' 			=> isset( $options[ 'cff_reviews_method' ] ) ? stripslashes( esc_attr( $options[ 'cff_reviews_method' ] ) ) : ''

		   	), $atts, 'custom_facebook_feed' );
	}


	/**
	 * @return array
	 *
	 * @since 3.18
	 */
	public function get_settings() {
		return $this->settings;
	}


	/**
	 * Get Include String for the Shortcode
	 * @return array
	 *
	 * @since 3.18
	 */
	public function get_include_string() {
		$include_string = '';
		$include_str_array = [
			'cff_show_author'				=> 'author,',
			'cff_show_text'					=> 'text,',
			'cff_show_desc'					=> 'desc,',
			'cff_show_shared_links'			=> 'sharedlinks,',
			'cff_show_date'					=> 'date,',
			'cff_show_media'				=> 'media,',
			'cff_show_media_link'			=> 'medialink,',
			'cff_show_event_title'			=> 'eventtitle,',
			'cff_show_event_details'		=> 'eventdetails,',
			'cff_show_meta'					=> 'social,',
			'cff_show_link'					=> 'link,',
			'cff_show_like_box'				=> 'likebox,'
		];
		foreach ($include_str_array as $key => $value) {
			if( isset( $this->options[$key] ) &&  $this->options[$key]) $include_string .= $value;
		}
		return $include_string;
	}


	/**
	 * Get Type String for the Shortcode
	 * @return array
	 *
	 * @since 3.18
	 */
	public function get_type_string() {
		$type_string = '';
	    if($this->options[ 'cff_show_links_type' ]) $type_string .= 'links,';
	    if($this->options[ 'cff_show_event_type' ]) $type_string .= 'events,';
	    if($this->options[ 'cff_show_video_type' ]) $type_string .= 'videos,';
	    if($this->options[ 'cff_show_photos_type' ]) $type_string .= 'photos,';
	    if($this->options[ 'cff_show_albums_type' ]) $type_string .= 'albums,';
	    if($this->options[ 'cff_show_status_type' ]) $type_string .= 'statuses,';
	    return $type_string;
	}


	/**
	 * Get Reviews String for the Shortcode
	 * @return array
	 *
	 * @since 3.18
	 */
	public function get_reviews_string(){
		$cff_reviews_string = '';
	    if(    isset($this->options[ 'cff_reviews_rated_5' ])
	    	&& isset($this->options[ 'cff_reviews_rated_4' ])
	    	&& isset($this->options[ 'cff_reviews_rated_3' ])
	    	&& isset($this->options[ 'cff_reviews_rated_2' ])
	    	&& isset($this->options[ 'cff_reviews_rated_1' ])
	    ){
	        if($this->options[ 'cff_reviews_rated_5' ]) $cff_reviews_string .= '5,';
	        if($this->options[ 'cff_reviews_rated_4' ]) $cff_reviews_string .= '4,';
	        if($this->options[ 'cff_reviews_rated_3' ]) $cff_reviews_string .= '3,';
	        if($this->options[ 'cff_reviews_rated_2' ]) $cff_reviews_string .= '2,';
	        if($this->options[ 'cff_reviews_rated_1' ]) $cff_reviews_string .= '1';
	    }
	    return $cff_reviews_string;
	}

	/**
	 * Check Active Extensions
	 * @return array
	 *
	 * @since 3.18
	 */
	public static function check_active_extension($extension_name){
    	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		$extensions_list 	= get_option('cff_extensions_status');
		$extension_array 	= self::$extensions_list[$extension_name];
		return (
			is_plugin_active( $extension_array[ 'path' ] ) ||
			( isset( $extensions_list[ $extension_array[ 'opt' ] ] )
				&& $extensions_list[ $extension_array[ 'opt' ] ] ) );
	}


	/**
	 * @return array
	 *
	 * @since 3.19
	 */
	public function get_id_and_token() {
		$id_and_token = [
			'id' 		=> trim($this->settings['id']),
			'token' 	=> $this->settings['accesstoken'],
			'pagetype' 	=> $this->settings['pagetype']
		];
		//If an 'account' is specified then use that instead of the Page ID/token from the settings
	    $cff_account = trim($this->settings['account']);
		$cff_connected_accounts = get_option('cff_connected_accounts');
		$cff_connected_accounts = json_decode( str_replace('\"','"', $cff_connected_accounts) );
	    if( !empty( $cff_account ) ){
	        if( !empty($cff_connected_accounts) && isset($cff_connected_accounts->{ $cff_account })  ){
	            //Grab the ID and token from the connected accounts setting
	            $id_and_token = [
					'id' 	=> $cff_connected_accounts->{ $cff_account }->{'id'},
					'token' => $cff_connected_accounts->{ $cff_account }->{'accesstoken'},
					'name' => $cff_connected_accounts->{ $cff_account }->{'name'},
					'pagetype' => $cff_connected_accounts->{ $cff_account }->{'pagetype'}
				];
	            //Replace the encryption string in the Access Token
	            if (strpos($id_and_token['token'], '02Sb981f26534g75h091287a46p5l63') !== false) {
	                $id_and_token['token'] = str_replace("02Sb981f26534g75h091287a46p5l63","",$id_and_token['token']);
	            }
	        }
	    }else{
	        if( !empty($cff_connected_accounts) ){
	        	$id_and_token['name'] = isset($cff_connected_accounts->{ $this->settings['id'] }->{'name'}) ? $cff_connected_accounts->{ $this->settings['id'] }->{'name'} : $this->settings['id'];
	        	$id_and_token['pagetype'] = isset($cff_connected_accounts->{ $this->settings['id'] }->{'pagetype'}) ? $cff_connected_accounts->{ $this->settings['id'] }->{'pagetype'} : $this->settings['pagetype'];
	    	}
	    }
	    $id_and_token['id'] 	= $this->check_page_id( $id_and_token['id'] );
		$this->page_id 	 		= $id_and_token['id'];
		$this->access_token 	= $id_and_token['token'];

		return $id_and_token;
	}
	/**
	 *
	 * Check the Page ID
	 * @return array
	 * @since 3.19
	 */
	function check_page_id( $page_id ){
		 //If user pastes their full URL into the Page ID field then strip it out
	    $cff_page_id_url_check = CFF_Utils::stripos($page_id, 'facebook.com' );
	    if ( $cff_page_id_url_check ) {
	    	$fb_url_pattern = '/^https?:\/\/(?:www|m)\.facebook.com\/(?:profile\.php\?id=)?([a-zA-Z0-9\.]+)$/';
			$page_id = ( !preg_match($fb_url_pattern, $page_id, $matches) ) ? '' : $matches[1];
	    }
		return $page_id;
	}

	/**
	 * @return array
	 *
	 * @since 3.19
	 */
	function set_page_id($page_id){
		$this->settings['id'] = $page_id;
	}

}
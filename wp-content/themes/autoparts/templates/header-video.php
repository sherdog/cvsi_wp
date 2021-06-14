<?php
/**
 * The template to display the background video in the header
 *
 * @package WordPress
 * @subpackage AUTOPARTS
 * @since AUTOPARTS 1.0.14
 */
$autoparts_header_video = autoparts_get_header_video();
$autoparts_embed_video = '';
if (!empty($autoparts_header_video) && !autoparts_is_from_uploads($autoparts_header_video)) {
	if (autoparts_is_youtube_url($autoparts_header_video) && preg_match('/[=\/]([^=\/]*)$/', $autoparts_header_video, $matches) && !empty($matches[1])) {
		?><div id="background_video" data-youtube-code="<?php echo esc_attr($matches[1]); ?>"></div><?php
	} else {
		global $wp_embed;
		if (false && is_object($wp_embed)) {
			$autoparts_embed_video = do_shortcode($wp_embed->run_shortcode( '[embed]' . trim($autoparts_header_video) . '[/embed]' ));
			$autoparts_embed_video = autoparts_make_video_autoplay($autoparts_embed_video);
		} else {
			$autoparts_header_video = str_replace('/watch?v=', '/embed/', $autoparts_header_video);
			$autoparts_header_video = autoparts_add_to_url($autoparts_header_video, array(
				'feature' => 'oembed',
				'controls' => 0,
				'autoplay' => 1,
				'showinfo' => 0,
				'modestbranding' => 1,
				'wmode' => 'transparent',
				'enablejsapi' => 1,
				'origin' => home_url(),
				'widgetid' => 1
			));
			$autoparts_embed_video = '<iframe src="' . esc_url($autoparts_header_video) . '" width="1170" height="658" allowfullscreen="0" frameborder="0"></iframe>';
		}
		?><div id="background_video"><?php autoparts_show_layout($autoparts_embed_video); ?></div><?php
	}
}
?>
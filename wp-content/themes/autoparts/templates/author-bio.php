<?php
/**
 * The template to display the Author bio
 *
 * @package WordPress
 * @subpackage AUTOPARTS
 * @since AUTOPARTS 1.0
 */
?>

<div class="author_info scheme_dark author vcard" itemprop="author" itemscope itemtype="//schema.org/Person">

	<div class="author_avatar" itemprop="image">
		<?php 
		$autoparts_mult = autoparts_get_retina_multiplier();
		echo get_avatar( get_the_author_meta( 'user_email' ), 300*$autoparts_mult );
		?>
	</div><!-- .author_avatar -->

	<div class="author_description">
		<div class="author_title_about"><?php esc_html_e('About', 'autoparts'); ?></div>
		<h5 class="author_title" itemprop="name"><?php echo get_the_author(); ?></h5>
		<div class="author_bio" itemprop="description">
			<?php echo wp_kses(wpautop(get_the_author_meta( 'description' )),'autoparts_kses_content'); ?>
			<a class="author_link" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
				<?php printf( esc_html__( 'View all posts %s', 'autoparts' ), '<span class="author_name">' . '</span>' ); ?>
			</a>
			<?php do_action('autoparts_action_user_meta'); ?>
		</div><!-- .author_bio -->

	</div><!-- .author_description -->

</div><!-- .author_info -->

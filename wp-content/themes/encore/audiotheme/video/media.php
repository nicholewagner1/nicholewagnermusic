<?php
/**
 * The template for displaying a video media.
 *
 * @package Encore
 * @since 1.0.0
 */

$video_url = get_audiotheme_video_url();

if ( ! $video_url ) {
	return;
}
?>

<meta itemprop="embedUrl" content="<?php echo esc_url( $video_url ); ?>">

<figure class="entry-media entry-video">
	<?php the_audiotheme_video(); ?>
</figure>

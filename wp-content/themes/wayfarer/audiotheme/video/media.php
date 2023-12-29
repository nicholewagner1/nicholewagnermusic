<?php
/**
 * The template for displaying a video media object or thumbnail.
 *
 * @package Wayfarer
 * @since 1.0.0
 */

$thumbnail = get_post_thumbnail_id();
$video_url = get_audiotheme_video_url();
?>

<?php if ( ! empty( $video_url ) ) : ?>

	<figure class="entry-video responsive-video">
		<?php the_audiotheme_video(); ?>
	</figure>

<?php elseif ( ! empty( $thumbnail ) ) : ?>

	<figure class="entry-video entry-image">
		<?php the_post_thumbnail( 'large' ); ?>
	</figure>

<?php endif; ?>

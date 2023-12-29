<?php
/**
 * The template for displaying a track artwork.
 *
 * @package Wayfarer
 * @since 1.0.0
 */

$thumbnail_id = get_audiotheme_track_thumbnail_id();

if ( ! $thumbnail_id ) {
	return;
}
?>

<figure class="record-artwork">
	<a class="post-thumbnail" href="<?php echo esc_url( get_permalink( $post->post_parent ) ); ?>">
		<?php echo wp_get_attachment_image( $thumbnail_id, 'post-thumbnail' ); ?>
	</a>
</figure>

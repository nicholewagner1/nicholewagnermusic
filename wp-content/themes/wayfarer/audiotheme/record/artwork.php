<?php
/**
 * The template for displaying a record artwork.
 *
 * @package Wayfarer
 * @since 1.0.0
 */

if ( ! has_post_thumbnail() ) {
	return;
}
?>

<figure class="record-artwork">
	<a class="post-thumbnail" href="<?php echo esc_url( wp_get_attachment_url( get_post_thumbnail_id() ) ); ?>">
		<?php the_post_thumbnail(); ?>
	</a>
</figure>

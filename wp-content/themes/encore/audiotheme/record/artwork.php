<?php
/**
 * The template for displaying a record artwork.
 *
 * @package Encore
 * @since 1.0.0
 */

if ( has_post_thumbnail() ) :
?>

	<figure class="entry-media record-artwork">
		<a class="post-thumbnail" href="<?php echo esc_url( wp_get_attachment_url( get_post_thumbnail_id() ) ); ?>" itemprop="image">
			<?php the_post_thumbnail( 'post-thumbnail' ); ?>
		</a>
	</figure>

<?php
endif;

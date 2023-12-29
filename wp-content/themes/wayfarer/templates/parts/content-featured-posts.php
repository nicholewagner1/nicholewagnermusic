<?php
/**
 * The template part for displaying the featured post items.
 *
 * @package Wayfarer
 * @since 1.0.0
 */

$show_titles = get_theme_mod( 'featured_content_titles', '1' );
?>

<article id="featured-post-<?php the_ID(); ?>" <?php post_class( array( 'block-grid-item', 'featured-post-item' ) ); ?>>

	<?php do_action( 'wayfarer_featured_post_top' ); ?>

	<a class="block-grid-item-image" href="<?php the_permalink(); ?>">
		<?php wayfarer_the_post_image(); ?>
	</a>

	<?php if ( ! empty( $show_titles ) ) : ?>

		<?php the_title( '<h3 class="block-grid-item-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' ); ?>

	<?php else : ?>

		<?php the_title( '<h3 class="block-grid-item-title screen-reader-text"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' ); ?>

	<?php endif; ?>

	<?php do_action( 'wayfarer_featured_post_bottom' ); ?>

</article>

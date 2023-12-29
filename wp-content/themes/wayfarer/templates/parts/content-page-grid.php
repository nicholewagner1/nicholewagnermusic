<?php
/**
 * The template used for displaying articles in a Grid Page template.
 *
 * @package Wayfarer
 * @since 1.0.0
 */

?>

<article id="block-grid-item-<?php the_ID(); ?>" <?php post_class( 'block-grid-item' ); ?>>

	<?php do_action( 'wayfarer_block_grid_top' ); ?>

	<a class="block-grid-item-image" href="<?php the_permalink(); ?>">
		<?php the_post_thumbnail( 'wayfarer-block-grid-16x9' ); ?>
	</a>

	<?php the_title( '<h1 class="block-grid-item-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h1>' ); ?>

	<?php do_action( 'wayfarer_block_grid_bottom' ); ?>

</article>

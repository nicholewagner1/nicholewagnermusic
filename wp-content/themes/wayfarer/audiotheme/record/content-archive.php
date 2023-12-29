<?php
/**
 * The template used for displaying records on archives.
 *
 * @package Wayfarer
 * @since 1.0.0
 */

?>

<article id="block-grid-item-<?php the_ID(); ?>" <?php post_class( 'block-grid-item' ); ?>>

	<?php do_action( 'wayfarer_block_grid_top' ); ?>

	<a class="block-grid-item-image" href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>

	<?php the_title( '<h1 class="block-grid-item-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h1>' ); ?>

	<p class="block-grid-item-meta screen-reader-text">
		<?php echo esc_html( get_audiotheme_record_artist() ); ?>
	</p>

	<?php do_action( 'wayfarer_block_grid_bottom' ); ?>

</article>

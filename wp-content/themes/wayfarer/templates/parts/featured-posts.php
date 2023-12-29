<?php
/**
 * The template part for displaying the featured posts.
 *
 * @package Wayfarer
 * @since 1.0.0
 */

?>

<div class="wayfarer-featured-posts wayfarer-featured-posts--<?php echo esc_attr( get_theme_mod( 'featured_content_style', 'color' ) ); ?>">

	<h2 class="wayfarer-featured-posts-title screen-reader-text"><?php esc_html_e( 'Featured', 'wayfarer' ); ?></h2>

	<div class="block-grid block-grid-<?php echo esc_attr( get_theme_mod( 'featured_content_layout', '3' ) ); ?> block-grid--<?php echo esc_attr( get_theme_mod( 'featured_content_image_ratio', '7x5' ) ); ?>">

		<?php foreach ( (array) $posts as $order => $post ) : setup_postdata( $post ); ?>

			<?php get_template_part( 'templates/parts/content-featured-posts', wayfarer_post_template_name() ); ?>

		<?php endforeach; ?>

		<?php wp_reset_postdata(); ?>

	</div>

</div>

<?php
/**
 * The template part for displaying the hero image with content overlay.
 *
 * @package Wayfarer
 * @since 1.0.0
 */

if ( ! wayfarer_get_hero_image() ) {
	return;
}
?>

<div class="hero hero-banner">
	<?php do_action( 'wayfarer_hero_top' ); ?>

	<div class="hero-inside">
		<?php wayfarer_hero_title( '<h1 class="hero-title">', '</h1>' ); ?>
		<?php wayfarer_hero_content( '<div class="hero-content">', '</div>' ); ?>
	</div>

	<?php do_action( 'wayfarer_hero_bottom' ); ?>
</div>

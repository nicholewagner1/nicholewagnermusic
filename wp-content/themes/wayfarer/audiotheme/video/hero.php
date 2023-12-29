<?php
/**
 * The template part for displaying the hero video content.
 *
 * @package Wayfarer
 * @since 1.0.0
 */

?>

<div class="hero hero-banner hero-banner--background-blur">
	<?php do_action( 'wayfarer_hero_top' ); ?>

	<div class="hero-inside">
		<?php get_template_part( 'audiotheme/video/media' ); ?>
		<?php wayfarer_hero_title( '<h1 class="hero-title">', '</h1>' ); ?>
		<?php wayfarer_hero_content( '<div class="hero-content">', '</div>' ); ?>
	</div>

	<?php do_action( 'wayfarer_hero_bottom' ); ?>
</div>

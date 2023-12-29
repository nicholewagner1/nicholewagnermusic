<?php
/**
 * The template part for displaying the hero record content.
 *
 * @package Wayfarer
 * @since 1.0.0
 */

?>

<div class="hero hero-banner hero-banner--background-blur">
	<?php do_action( 'wayfarer_hero_top' ); ?>

	<div class="hero-inside">
		<?php get_template_part( 'audiotheme/track/artwork' ); ?>
		<?php wayfarer_hero_title( '<h1 class="hero-title record-title">', '</h1>' ); ?>
		<?php wayfarer_hero_content( '<div class="hero-content record-content">', '</div>' ); ?>
		<?php get_template_part( 'audiotheme/track/meta' ); ?>
		<?php get_template_part( 'audiotheme/track/meta', 'links' ); ?>
	</div>

	<?php do_action( 'wayfarer_hero_bottom' ); ?>
</div>

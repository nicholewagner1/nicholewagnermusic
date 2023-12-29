<?php
/**
 * The template used for displaying the site header.
 *
 * @package Wayfarer
 * @since 1.0.0
 */

?>

<header id="masthead" class="site-header" role="banner" itemscope itemtype="https://schema.org/WPHeader">

	<?php do_action( 'wayfarer_header_top' ); ?>

	<?php wayfarer_mobile_navigation(); ?>

	<div class="site-identity">
		<?php the_custom_logo(); ?>

		<div class="site-title-description">
			<?php wayfarer_site_title( '<h1 class="site-title">', '</h1>' ); ?>
			<?php wayfarer_site_description( '<p class="site-description">', '</p>' ); ?>
		</div>

		<?php wayfarer_player(); ?>
	</div>

	<div class="site-navigation-panel">
		<?php wayfarer_site_navigation(); ?>
		<?php wayfarer_social_navigation(); ?>
	</div>

	<?php do_action( 'wayfarer_header_bottom' ); ?>

</header>

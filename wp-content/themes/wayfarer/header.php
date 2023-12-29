<?php
/**
 * The template for displaying the theme header.
 *
 * @package Wayfarer
 * @since 1.0.0
 */

?><!DOCTYPE html>
<html class="no-js no-cssfilters" <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> <?php wayfarer_body_schema(); ?>>

	<?php wp_body_open(); ?>

	<?php do_action( 'wayfarer_before' ); ?>

	<div id="page" class="hfeed site">
		<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'wayfarer' ); ?></a>

		<?php do_action( 'wayfarer_header_before' ); ?>
		<?php get_template_part( 'templates/parts/site-header' ); ?>
		<?php do_action( 'wayfarer_header_after' ); ?>

		<div id="content" class="site-content">
			<?php do_action( 'wayfarer_content_top' ); ?>
			<div class="site-content-inside">
				<?php do_action( 'wayfarer_main_before' ); ?>

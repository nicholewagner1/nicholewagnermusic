<?php
/**
 * The template for displaying the theme header.
 *
 * @package Encore
 * @since 1.0.0
 */

?><!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> itemscope itemtype="http://schema.org/WebPage">

	<?php wp_body_open(); ?>

	<?php do_action( 'encore_before' ); ?>

	<div id="page" class="hfeed site">
		<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'encore' ); ?></a>

		<?php do_action( 'encore_header_before' ); ?>

		<?php get_template_part( 'templates/parts/site-header' ); ?>

		<?php do_action( 'encore_header_after' ); ?>

		<div id="content" class="site-content">

			<?php
			do_action( 'encore_main_before' );

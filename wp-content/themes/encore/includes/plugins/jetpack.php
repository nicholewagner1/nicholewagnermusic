<?php
/**
 * Jetpack integration.
 *
 * @package Encore
 * @since 1.0.0
 * @link https://jetpack.com/
 */

/**
 * Set up Jetpack theme support.
 *
 * Adds support for Infinite Scroll.
 *
 * @since 1.0.0
 */
function encore_jetpack_setup() {
	// Add support for Global Styles.
	add_theme_support( 'jetpack-global-styles', array(
		'enable_theme_default' => true,
	) );

	// Add support for Infinite Scroll.
	add_theme_support( 'infinite-scroll', apply_filters( 'encore_infinite_scroll_args', array(
		'container'      => 'primary',
		'footer'         => 'footer',
		'footer_widgets' => 'footer-widgets',
		'render'         => 'encore_jetpack_infinite_scroll_render',
	) ) );
}
add_action( 'after_setup_theme', 'encore_jetpack_setup' );

/**
 * Load required assets for Jetpack support.
 *
 * @since 1.2.0
 */
function encore_jetpack_enqueue_assets() {
	wp_enqueue_style(
		'encore-jetpack',
		get_template_directory_uri() . '/assets/css/jetpack.css',
		array( 'encore-style' )
	);

	wp_style_add_data( 'encore-jetpack', 'rtl', 'replace' );
}
add_action( 'wp_enqueue_scripts', 'encore_jetpack_enqueue_assets' );

/**
 * Infinite scroll credit text.
 *
 * @since 1.0.0
 *
 * @return string
 */
function encore_infinite_scroll_credit() {
	return encore_get_credits();
}
add_filter( 'infinite_scroll_credit', 'encore_infinite_scroll_credit' );

if ( ! function_exists( 'encore_jetpack_infinite_scroll_render' ) ) :
/**
 * Callback for the Infinite Scroll module in Jetpack to render additional posts.
 *
 * @since 1.0.0
 */
function encore_jetpack_infinite_scroll_render() {
	while ( have_posts() ) {
		the_post();
		get_template_part( 'templates/parts/content', get_post_format() );
	}
}
endif;

<?php
/**
 * Jetpack integration.
 *
 * @package Wayfarer
 * @since 1.0.0
 * @link https://jetpack.com
 */

/**
 * Register support for Global Styles.
 *
 * @since 2.0.0
 */
function wayfarer_jetpack_global_styles_setup() {
	// Add support for Global Styles.
	add_theme_support( 'jetpack-global-styles', array(
		'enable_theme_default' => true,
	) );
}
add_action( 'after_setup_theme', 'wayfarer_jetpack_global_styles_setup' );

/**
 * Load required assets for Jetpack support.
 *
 * @since 1.0.0
 */
function wayfarer_jetpack_enqueue_assets() {
	wp_enqueue_style(
		'wayfarer-jetpack',
		get_template_directory_uri() . '/assets/css/jetpack.css',
		array( 'wayfarer-style' )
	);

	wp_style_add_data( 'wayfarer-jetpack', 'rtl', 'replace' );
}
add_action( 'wp_enqueue_scripts', 'wayfarer_jetpack_enqueue_assets', 20 );


/*
 * Content Options
 * -----------------------------------------------------------------------------
 */

/**
 * Set up Jetpack theme support for content options.
 *
 * @since 1.0.0
 */
function wayfarer_jetpack_content_options_setup() {
	add_theme_support( 'jetpack-content-options', array(
		'blog-display' => 'content',
		'author-bio'   => false,
		'post-details' => array(
			'stylesheet' => 'wayfarer-style',
			'author'     => '.author-box',
			'date'       => '.entry-date',
			'categories' => '.term-group--category',
			'tags'       => '.term-group--post_tag',
		),
	) );
}
add_action( 'after_setup_theme', 'wayfarer_jetpack_content_options_setup' );


/*
 * Infinite Scroll
 * -----------------------------------------------------------------------------
 */

/**
 * Set up Jetpack Infinite Scroll.
 *
 * @since 1.0.0
 */
function wayfarer_jetpack_infinite_scroll_setup() {
	add_theme_support( 'infinite-scroll', array(
		'container'      => 'posts-container',
		'footer'         => 'footer',
		'footer_widgets' => 'sidebar-2',
		'render'         => 'wayfarer_jetpack_infinite_scroll_render',
		'wrapper'        => true,
		'posts_per_page' => 9,
	) );
}
add_action( 'after_setup_theme', 'wayfarer_jetpack_infinite_scroll_setup' );

/**
 * Infinite scroll credit text.
 *
 * @since 1.0.0
 *
 * @return string
 */
function wayfarer_infinite_scroll_credit() {
	return wayfarer_get_credits();
}
add_filter( 'infinite_scroll_credit', 'wayfarer_infinite_scroll_credit' );

/**
 * Filter Infinite Scroll JavaScript settings.
 *
 * @since 1.0.0
 *
 * @param  array $settings Settings.
 * @return array
 */
function wayfarer_jetpack_infinite_scroll_js_settings( $settings ) {
	$settings['text'] = esc_html_x( 'Load More', 'load posts button text', 'wayfarer' );
	return $settings;
}
add_filter( 'infinite_scroll_js_settings', 'wayfarer_jetpack_infinite_scroll_js_settings' );

/**
 * Callback for the Infinite Scroll module in Jetpack to render additional posts.
 *
 * @since 1.0.0
 */
function wayfarer_jetpack_infinite_scroll_render() {
	while ( have_posts() ) {
		the_post();
		if ( is_search() ) {
			get_template_part( 'templates/parts/content-search', wayfarer_post_template_name() );
		} else {
			get_template_part( 'templates/parts/content-archive', wayfarer_post_template_name() );
		}
	}
}

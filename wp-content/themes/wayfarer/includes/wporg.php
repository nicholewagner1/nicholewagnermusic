<?php
/**
 * Functionality specific to self-hosted installations of WordPress, including
 * support for plugins.
 *
 * @package Wayfarer
 * @since 1.0.0
 */

/**
 * Wayfarer only works in WordPress 4.5 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '4.5-alpha', '<' ) ) {
	require get_template_directory() . '/includes/back-compat.php';
}

/**
 * Load helper functions and libraries.
 */
require get_template_directory() . '/includes/colors.php';
require get_template_directory() . '/includes/customizer-colors.php';

/**
 * Set up custom fonts for self-hosted sites.
 *
 * @since 1.0.0
 */
function wayfarer_wporg_setup_custom_fonts() {
	wayfarer_theme()->fonts
		->add_support()
		->register_text_groups( array(
			array(
				'id'         => 'site-title',
				'label'      => esc_html__( 'Site Title', 'wayfarer' ),
				'selector'   => '.site-title',
				'family'     => 'Oswald',
				'variations' => '400,700',
				'tags'       => array( 'content', 'heading', 'site-title' ),
			),
			array(
				'id'         => 'site-navigation',
				'label'      => esc_html__( 'Site Navigation', 'wayfarer' ),
				'selector'   => '.site-navigation, .breadcrumbs',
				'family'     => 'Arimo',
				'variations' => '400,700',
				'tags'       => array( 'content', 'heading' ),
			),
			array(
				'id'         => 'wayfarer-player',
				'label'      => esc_html__( 'Site-wide Player', 'wayfarer' ),
				'selector'   => '.wayfarer-player',
				'family'     => 'Arimo',
				'variations' => '400',
				'tags'       => array( 'content', 'heading' ),
			),
			array(
				'id'         => 'headings',
				'label'      => esc_html__( 'Headings', 'wayfarer' ),
				'selector'   => 'h1, h2, h3, h4, h5, h6, .entry-title, .page-title, .widget-title',
				'family'     => 'Arimo',
				'variations' => '700',
				'tags'       => array( 'content', 'heading' ),
			),
			array(
				'id'         => 'content',
				'label'      => esc_html__( 'Content', 'wayfarer' ),
				'selector'   => 'body',
				'family'     => 'Arimo',
				'variations' => '400,400italic,700,700italic',
				'tags'       => array( 'content' ),
			),
		) );
}
add_action( 'after_setup_theme', 'wayfarer_wporg_setup_custom_fonts' );

/**
 * Add support for wp.org-specific theme functions.
 *
 * @since 1.0.0
 */
function wayfarer_wporg_setup() {
	// Add support for featured content.
	wayfarer_theme()->featured_content->add_support();
}
add_action( 'after_setup_theme', 'wayfarer_wporg_setup' );

/**
 * Filter the style sheet URI to point to the parent theme when a child theme is
 * being used.
 *
 * @since 1.0.0
 *
 * @param  string $uri Style sheet URI.
 * @return string
 */
function wayfarer_stylesheet_uri( $uri ) {
	if ( is_child_theme() ) {
		$uri = get_template_directory_uri() . '/style.css';
	}

	return $uri;
}
add_filter( 'stylesheet_uri', 'wayfarer_stylesheet_uri' );

/**
 * Enqueue the child theme styles.
 *
 * The action priority must be set to load after any stylesheet that need to be
 * overridden in the child theme stylesheet.
 *
 * @since 1.0.0
 */
function wayfarer_enqueue_child_assets() {
	if ( is_child_theme() ) {
		wp_enqueue_style( 'wayfarer-child-style', get_stylesheet_directory_uri() . '/style.css' );
	}
}
add_action( 'wp_enqueue_scripts', 'wayfarer_enqueue_child_assets', 20 );

/**
 * Enqueue scripts and styles for the front-end.
 *
 * @since 1.0.0
 */
function wayfarer_wporg_enqueue_assets() {
	wp_enqueue_script(
		'jquery-fitvids',
		get_template_directory_uri() . '/assets/js/vendor/jquery.fitvids.js',
		array( 'jquery' ),
		'1.1',
		true
	);
}
add_action( 'wp_enqueue_scripts', 'wayfarer_wporg_enqueue_assets' );


/*
 * Template Tags
 * -----------------------------------------------------------------------------
 */

/**
 * Retrieve featured posts.
 *
 * @since 1.0.0
 *
 * @return array An array of WP_Post objects.
 */
function wayfarer_wporg_get_featured_posts() {
	return wayfarer_theme()->featured_content->get_posts();
}
add_filter( 'wayfarer_get_featured_posts', 'wayfarer_wporg_get_featured_posts' );

/**
 * Display an add to cart link.
 *
 * @since 1.0.0
 *
 * @param array $args Menu item arguments.
 */
function wayfarer_wporg_cart_menu_item( $args = array() ) {
	$args = apply_filters( 'wayfarer_cart_menu_item_args', wp_parse_args( $args, array(
		'prefix'   => 'wayfarer',
		'classes'  => '',
		'label'    => '',
		'quantity' => '',
		'url'      => home_url( '/checkout/' ),
	) ) );

	$classes = sprintf(
		'menu-item menu-item-type-cart menu-item-type-%1$s-cart%2$s',
		$args['prefix'],
		$args['classes']
	);

	$label = sprintf(
		'<span class="cart-label">%s</span>',
		$args['label']
	);

	$quantity = sprintf(
		'<span class="cart-quantity %1$s-cart-quantity">%2$s</span>',
		esc_attr( $args['prefix'] ),
		$args['quantity']
	);

	$menu_item = sprintf(
		'<li class="%1$s"><a href="%2$s">%3$s</a></li>',
		esc_attr( $classes ),
		esc_url( $args['url'] ),
		wayfarer_allowed_tags( $label . ' ' . $quantity )
	);

	return apply_filters( 'wayfarer_cart_menu_item', $menu_item, $classes, $label, $quantity, $args );
}


/*
 * Plugin support
 * -----------------------------------------------------------------------------
 */

/**
 * Load AudioTheme support or display a notice that it's needed.
 */
if ( function_exists( 'audiotheme_load' ) ) {
	include get_template_directory() . '/includes/plugins/audiotheme.php';
} else {
	include get_template_directory() . '/includes/vendor/class-audiotheme-themenotice.php';
	new Audiotheme_ThemeNotice();
}

/**
 * Load Breadcrumb Trail support.
 */
if ( class_exists( 'Breadcrumb_Trail' ) ) {
	include get_template_directory() . '/includes/plugins/breadcrumb-trail.php';
}

/**
 * Load Cue support.
 */
if ( class_exists( 'Cue' ) ) {
	include get_template_directory() . '/includes/plugins/cue.php';
}

/**
 * Load Jetpack support.
 */
if ( class_exists( 'Jetpack' ) ) {
	include get_template_directory() . '/includes/plugins/jetpack.php';
}

/**
 * Load Meta Slider support.
 */
if ( class_exists( 'MetaSliderPlugin' ) ) {
	include get_template_directory() . '/includes/plugins/meta-slider.php';
}

/**
 * Load Subtitles support.
 */
if ( class_exists( 'Subtitles' ) ) {
	include get_template_directory() . '/includes/plugins/subtitles.php';
}

/**
 * Load WooCommerce support.
 */
if ( class_exists( 'WooCommerce' ) ) {
	include get_template_directory() . '/includes/plugins/woocommerce.php';
}

/**
 * Load Yoast SEO support.
 */
if ( class_exists( 'WPSEO_Breadcrumbs' ) ) {
	include get_template_directory() . '/includes/plugins/wpseo-breadcrumbs.php';
}

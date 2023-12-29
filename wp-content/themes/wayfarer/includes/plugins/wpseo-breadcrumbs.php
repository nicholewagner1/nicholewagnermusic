<?php
/**
 * WP SEO Breadcrumbs Compatibility File
 *
 * @package Wayfarer
 * @since 1.0.0
 */

/**
 * Register the breadcrumbs site header section.
 *
 * @since 1.0.0
 */
add_filter( 'wayfarer_site_header_sections', 'wayfarer_register_breadcrumbs_header_section' );

/**
 * Register template parts to load throughout the theme.
 *
 * @since 1.0.0
 */
function wayfarer_wpseo_register_template_parts() {
	if ( ! is_front_page() ) {
		add_action( 'wayfarer_header_bottom', 'wayfarer_wpseo_breadcrumbs', 20 );
	}
}
add_action( 'wayfarer_register_template_parts', 'wayfarer_wpseo_register_template_parts' );

/**
 * Wrap breadcrumb separator for more styling control.
 *
 * @since 1.0.0
 *
 * @param  string $value Separator.
 * @return string
 */
function wayfarer_wpseo_breadcrumb_separator( $value ) {
	return '<span class="sep">' . $value . '</span>';
}
add_filter( 'wpseo_breadcrumb_separator', 'wayfarer_wpseo_breadcrumb_separator' );

/**
 * Display WPSEO breadcrumbs.
 *
 * @since 1.0.0
 */
function wayfarer_wpseo_breadcrumbs() {
	if ( function_exists( 'yoast_breadcrumb' ) ) {
		yoast_breadcrumb( '<nav class="breadcrumbs" role="navigation">', '</nav>' );
	}
}

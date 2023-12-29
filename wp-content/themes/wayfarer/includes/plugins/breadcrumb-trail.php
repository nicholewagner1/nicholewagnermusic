<?php
/**
 * Breadcrumb Trail integration.
 *
 * @package Wayfarer
 * @since 1.0.0
 * @link https://wordpress.org/plugins/breadcrumb-trail/
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
function wayfarer_breadcrumb_trail_register_template_parts() {
	add_action( 'wayfarer_header_bottom', 'wayfarer_breadcrumb_trail' );
}
add_action( 'wayfarer_register_template_parts', 'wayfarer_breadcrumb_trail_register_template_parts' );

/**
 * Display breadcrumb trail.
 *
 * @since 1.0.0
 */
function wayfarer_breadcrumb_trail() {
	if ( ! function_exists( 'breadcrumb_trail' ) ) {
		return;
	}

	breadcrumb_trail( apply_filters( 'wayfarer_breadcrumb_trail_args', array(
		'show_on_front' => false,
		'show_browse'   => false,
	) ) );
}

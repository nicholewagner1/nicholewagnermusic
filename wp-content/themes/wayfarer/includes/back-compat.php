<?php
/**
 * Wayfarer backward compatibility functionality.
 *
 * Prevents Wayfarer from running on WordPress versions prior to 4.5,
 * since this theme is not meant to be backward compatible beyond that and
 * relies on many newer functions and markup changes introduced in 4.5.
 *
 * @package Wayfarer
 * @since 1.0.0
 */

/**
 * Prevent switching to Wayfarer on old versions of WordPress.
 *
 * Switches to the default theme.
 *
 * @since 1.0.0
 */
function wayfarer_switch_theme() {
	switch_theme( WP_DEFAULT_THEME, WP_DEFAULT_THEME );
	unset( $_GET['activated'] );
	add_action( 'admin_notices', 'wayfarer_upgrade_notice' );
}
add_action( 'after_switch_theme', 'wayfarer_switch_theme' );

/**
 * Add message for unsuccessful theme switch.
 *
 * Prints an update nag after an unsuccessful attempt to switch to
 * Wayfarer on WordPress versions prior to 4.5.
 *
 * @since 1.0.0
 */
function wayfarer_upgrade_notice() {
	$message = sprintf( __( 'Wayfarer requires at least WordPress version 4.5. You are running version %s. Please upgrade and try again.', 'wayfarer' ), $GLOBALS['wp_version'] );
	printf( '<div class="error"><p>%s</p></div>', $message );
}

/**
 * Prevent the Customizer from being loaded on WordPress versions prior to 4.5.
 *
 * @since 1.0.0
 */
function wayfarer_customize() {
	wp_die( sprintf( __( 'Wayfarer requires at least WordPress version 4.5. You are running version %s. Please upgrade and try again.', 'wayfarer' ), $GLOBALS['wp_version'] ), '', array(
		'back_link' => true,
	) );
}
add_action( 'load-customize.php', 'wayfarer_customize' );

/**
 * Prevent the Theme Preview from being loaded on WordPress versions prior to 4.5.
 *
 * @since 1.0.0
 */
function wayfarer_preview() {
	if ( isset( $_GET['preview'] ) ) {
		wp_die( sprintf( __( 'Wayfarer requires at least WordPress version 4.5. You are running version %s. Please upgrade and try again.', 'wayfarer' ), $GLOBALS['wp_version'] ) );
	}
}
add_action( 'template_redirect', 'wayfarer_preview' );

<?php
/**
 * Custom color functions and definitions.
 *
 * @package Wayfarer
 * @since 1.0.0
 */

/**
 * Build CSS.
 *
 * @since 1.0.0
 */
function wayfarer_custom_colors_inline_style() {
	if ( ! wayfarer_is_custom_colors_active() ) {
		return;
	}

	$colors = wayfarer_get_custom_colors_settings();
	$css    = wayfarer_get_custom_colors_css( $colors );

	if ( empty( $css ) ) {
		return;
	}

	wp_add_inline_style( 'wayfarer-style', $css );
}
add_action( 'wp_enqueue_scripts', 'wayfarer_custom_colors_inline_style', 15 );

/**
 * Retrieve color settings.
 *
 * @since 1.0.0
 */
function wayfarer_get_custom_colors_settings() {
	$accent   = get_theme_mod( 'wayfarer_accent_color', '#565b66' );
	$contrast = get_theme_mod( 'wayfarer_contrast_color', '#ffffff' );

	$colors = apply_filters( 'wayfarer_custom_colors_settings', array(
		'color'     => $accent,
		'contrast'  => $contrast,
		'readable'  => $accent,
		'time_rail' => vsprintf( 'rgba( %1$s, %2$s, %3$s, 0.7)', wayfarer_hex2rgb( $contrast ) ),
	) );

	return $colors;
}

/**
 * Get accent color CSS.
 *
 * @since 1.0.0
 *
 * @param array $colors Color settings.
 * @return string
 */
function wayfarer_get_custom_colors_css( $colors = array() ) {
	$colors = wp_parse_args( $colors, wayfarer_get_custom_colors_settings() );

	$css  = '';
	$css .= wayfarer_get_accent_color_css( $colors['color'] );
	$css .= wayfarer_get_contrast_color_css( $colors['contrast'], $colors['time_rail'] );
	$css .= wayfarer_get_readable_color_css( $colors['readable'] );

	return apply_filters( 'wayfarer_custom_colors_css', $css, $colors );
}

/**
 * Get accent color CSS.
 *
 * @since 1.0.0
 *
 * @param string $color Color value.
 * @return string
 */
function wayfarer_get_accent_color_css( $color ) {
	$css = <<<CSS
.site-identity {
	background-color: {$color};
}

.wayfarer-featured-posts .block-grid-item-title a {
	border-color: {$color};
}

.post-type-navigation .current-menu-item {
	box-shadow: 0 -1px 0 {$color}, 0 1px 0 {$color};
}

@media (min-width: 1024px) {
	.site-navigation .current-menu-item,
	.site-navigation .current-menu-parent,
	.site-navigation .current-menu-ancestor {
		box-shadow: 0 -1px 0 {$color}, 0 1px 0 {$color};
	}

	.site-navigation .sub-menu {
		color: {$color};
	}
}
CSS;

	return $css;
}

/**
 * Get readable color CSS.
 *
 * @since 1.0.0
 *
 * @param string $color Color value.
 * @return string
 */
function wayfarer_get_readable_color_css( $color ) {
	$css = <<<CSS
.entry-content a,
.entry-content a:hover,
.entry-content a:focus,
.breadcrumbs a:hover,
.breadcrumbs a:focus,
.gig-list .gig-card .gig-date:hover,
.gig-list .gig-card .gig-date:focus,
.post-type-navigation li:hover,
.posts-navigation a:hover,
.posts-navigation a:focus,
.site-navigation .menu > li:hover,
.widget a:not(.button):hover,
.widget a:not(.button):focus,
.widget_text a:not(.button) {
	color: {$color};
}

@media (min-width: 1024px) {
	.site-navigation .menu > li:hover,
	.social-navigation li:hover {
		color: {$color};
	}
}

CSS;

	return $css;
}

/**
 * Get contrast color CSS.
 *
 * @since 1.0.0
 *
 * @param string $color Color value.
 * @param string $rail  Color value.
 * @return string
 */
function wayfarer_get_contrast_color_css( $color, $rail ) {
	$css = <<<CSS
.site-identity,
.wayfarer-player.is-tracklist-closed {
	color: {$color};
}
.wayfarer-player.is-tracklist-closed .mejs-container .mejs-controls .mejs-time-rail .mejs-time-current {
	background-color: {$color};
}
.wayfarer-player.is-tracklist-closed .mejs-container .mejs-controls .mejs-time-rail {
	background-color: {$rail};
}

CSS;

	return $css;
}

/**
 * Determine if the page can display custom colors.
 *
 * @since 1.0.0
 *
 * @return bool
 */
function wayfarer_is_custom_colors_active() {
	$colors = wayfarer_get_custom_colors_settings();
	$enable = ( isset( $colors['color'] ) && '#565b66' !== $colors['color'] );
	return apply_filters( 'wayfarer_is_custom_colors_active', $enable );
}

/**
 * Convert HEX to RGB.
 *
 * @since 1.0.0
 *
 * @param string $color The original color, in 3- or 6-digit hexadecimal form.
 * @return array Array containing RGB (red, green, and blue) values for the given
 *               HEX code, empty array otherwise.
 */
function wayfarer_hex2rgb( $color ) {
	$color = trim( $color, '#' );

	if ( strlen( $color ) === 3 ) {
		$r = hexdec( substr( $color, 0, 1 ) . substr( $color, 0, 1 ) );
		$g = hexdec( substr( $color, 1, 1 ) . substr( $color, 1, 1 ) );
		$b = hexdec( substr( $color, 2, 1 ) . substr( $color, 2, 1 ) );
	} elseif ( strlen( $color ) === 6 ) {
		$r = hexdec( substr( $color, 0, 2 ) );
		$g = hexdec( substr( $color, 2, 2 ) );
		$b = hexdec( substr( $color, 4, 2 ) );
	} else {
		return array();
	}

	return array(
		'red'   => $r,
		'green' => $g,
		'blue'  => $b,
	);
}

<?php
/**
 * Custom colors Customizer integration.
 *
 * @package Wayfarer
 * @since 1.0.0
 */

/**
 * Register and configure Customizer settings.
 *
 * @since 1.0.0
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 */
function wayfarer_customize_colors_register( $wp_customize ) {
	$wp_customize->add_setting( 'wayfarer_accent_color', array(
		'default'           => '#565b66',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_setting( 'wayfarer_contrast_color', array(
		'default'           => '#ffffff',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'wayfarer_accent_color', array(
		'label'    => esc_html__( 'Accent Color', 'wayfarer' ),
		'section'  => 'colors',
		'settings' => 'wayfarer_accent_color',
	) ) );
}
add_action( 'customize_register', 'wayfarer_customize_colors_register' );

/**
 * Enqueue assets to load in the Customizer preview.
 *
 * @since 1.0.0
 */
function wayfarer_customize_colors_enqueue_assets() {
	wp_enqueue_script(
		'wayfarer-customize-preview-colors',
		get_template_directory_uri() . '/assets/js/customize-preview-colors.js',
		array( 'customize-preview' ),
		'20160411',
		true
	);
}
add_action( 'customize_preview_init', 'wayfarer_customize_colors_enqueue_assets' );

/**
 * Enqueue scripts for the Customizer.
 *
 * @since 1.0.0
 */
function wayfarer_customize_colors_enqueue_controls_assets() {
	wp_enqueue_script(
		'wayfarer-customize-controls',
		get_template_directory_uri() . '/assets/js/customize-controls.js',
		array( 'customize-controls', 'iris', 'underscore', 'wp-util' ),
		'20160804',
		true
	);
}
add_action( 'customize_controls_enqueue_scripts', 'wayfarer_customize_colors_enqueue_controls_assets' );

/**
 * Print an Underscore template with CSS to generate based on options
 * selected in the Customizer.
 *
 * @since 1.0.0
 */
function wayfarer_customize_colors_print_styles_template() {
	$colors = array(
		'color'     => '{{ data.accentColor }}',
		'contrast'  => '{{ data.contrastColor }}',
		'readable'  => '{{ data.readableColor }}',
		'time_rail' => '{{ data.timeRailColor }}',
	);

	printf(
		'<script type="text/html" id="tmpl-wayfarer-customizer-styles">%s</script>',
		wayfarer_get_customize_color_css( $colors )
	);
}
add_action( 'customize_controls_print_footer_scripts', 'wayfarer_customize_colors_print_styles_template' );

/**
 * Retrieve CSS rules for implementing custom colors.
 *
 * @since 1.0.0
 *
 * @param  array $colors Optional. An array of CSS settings.
 * @return string
 */
function wayfarer_get_customize_color_css( $colors = array() ) {
	$colors = wp_parse_args( $colors, wayfarer_get_custom_colors_settings() );
	$css    = wayfarer_get_custom_colors_css( $colors );
	return $css;
}

<?php
/**
 * Customizer integration.
 *
 * @package Wayfarer
 * @since 1.0.0
 */

/**
 * Load customizer controls.
 *
 * @since 1.0.0
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 */
function wayfarer_load_customize_controls( $wp_customize ) {
	require_once get_template_directory() . '/includes/controls/class-wayfarer-customize-control-sortable.php';

	$wp_customize->register_control_type( 'Wayfarer_Customize_Control_Sortable' );
}
add_action( 'customize_register', 'wayfarer_load_customize_controls', 0 );

/**
 * Register and configure Customizer settings.
 *
 * @since 1.0.0
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 */
function wayfarer_customize_register( $wp_customize ) {
	/*
	 * Selective Refresh
	 */

	$wp_customize->selective_refresh->add_partial( 'blogname', array(
		'selector'            => '.site-title a',
		'container_inclusive' => false,
		'render_callback'     => 'wayfarer_customize_partial_blogname',
	) );

	$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
		'selector'            => '.site-description',
		'container_inclusive' => false,
		'render_callback'     => 'wayfarer_customize_partial_blogdescription',
	) );

	/*
	 * Theme Options Panel
	 */

	$wp_customize->add_panel( 'theme_options', array(
		'title'    => esc_html__( 'Theme Options', 'wayfarer' ),
		'priority' => 120,
	) );

	/*
	 * General Options
	 */

	$wp_customize->add_section( 'theme_options', array(
		'title'    => esc_html__( 'General', 'wayfarer' ),
		'panel'    => 'theme_options',
		'priority' => 10,
	) );

	$wp_customize->add_setting( 'grid_page_type_order', array(
		'capability'        => 'edit_theme_options',
		'default'           => 'menu_order',
		'sanitize_callback' => 'wayfarer_customize_sanitize_page_type_order',
	) );

	$wp_customize->add_control( 'wayfarer_grid_page_type_order', array(
		'label'       => esc_html__( 'Grid Page Order', 'wayfarer' ),
		'description' => esc_html__( 'Set Grid page template item order.', 'wayfarer' ),
		'section'     => 'theme_options',
		'settings'    => 'grid_page_type_order',
		'type'        => 'select',
		'choices'     => array(
			'menu_order' => esc_html__( 'Page Order', 'wayfarer' ),
			'date'       => esc_html__( 'Date', 'wayfarer' ),
			'title'      => esc_html__( 'Title', 'wayfarer' ),
		),
	) );

	/*
	 * Layout Options
	 */

	$wp_customize->add_section( 'theme_options_layout', array(
		'title'    => esc_html__( 'Layout', 'wayfarer' ),
		'panel'    => 'theme_options',
		'priority' => 20,
	) );

	$wp_customize->add_setting( 'site_header_layout', array(
		'default'           => 'site-navigation-panel,site-identity,hero,breadcrumbs',
		'sanitize_callback' => 'wayfarer_customize_sanitize_header_layout',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( new Wayfarer_Customize_Control_Sortable( $wp_customize, 'site_header_layout', array(
		'label'    => esc_html__( 'Site Header Layout', 'wayfarer' ),
		'section'  => 'theme_options_layout',
		'settings' => 'site_header_layout',
		'priority' => 5,
		'choices'  => wayfarer_get_site_header_sections(),
	) ) );

	$wp_customize->add_setting( 'enable_hero_overlay', array(
		'capability'        => 'edit_theme_options',
		'default'           => 1,
		'sanitize_callback' => 'wayfarer_customize_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'wayfarer_enable_hero_overlay', array(
		'label'    => esc_html__( 'Enable hero content overlay?', 'wayfarer' ),
		'section'  => 'theme_options_layout',
		'settings' => 'enable_hero_overlay',
		'type'     => 'checkbox',
		'priority' => 5,
	) );

	$wp_customize->add_setting( 'site_layout', array(
		'capability'        => 'edit_theme_options',
		'default'           => 'align-left',
		'sanitize_callback' => 'wayfarer_customize_sanitize_site_layout',
	) );

	$wp_customize->add_control( 'wayfarer_site_layout', array(
		'label'    => esc_html__( 'Site Layout', 'wayfarer' ),
		'section'  => 'theme_options_layout',
		'settings' => 'site_layout',
		'type'     => 'select',
		'priority' => 80,
		'choices'  => array(
			'align-left'   => esc_html__( 'Left Aligned', 'wayfarer' ),
			'align-center' => esc_html__( 'Center Aligned', 'wayfarer' ),
			'full-width'   => esc_html__( 'Full-width', 'wayfarer' ),
		),
	) );

	$wp_customize->add_setting( 'site_footer_layout', array(
		'capability'        => 'edit_theme_options',
		'default'           => '3',
		'sanitize_callback' => 'wayfarer_customize_sanitize_columns',
	) );

	$wp_customize->add_control( 'wayfarer_site_footer_layout', array(
		'label'    => esc_html__( 'Footer Widget Layout', 'wayfarer' ),
		'section'  => 'theme_options_layout',
		'settings' => 'site_footer_layout',
		'type'     => 'select',
		'priority' => 90,
		'choices'  => array(
			'1' => esc_html__( '1 Column', 'wayfarer' ),
			'2' => esc_html__( '2 Columns', 'wayfarer' ),
			'3' => esc_html__( '3 Columns', 'wayfarer' ),
			'4' => esc_html__( '4 Columns', 'wayfarer' ),
		),
	) );

	/*
	 * Featured Content
	 */

	$wp_customize->add_setting( 'featured_content_layout', array(
		'capability'        => 'edit_theme_options',
		'default'           => '3',
		'sanitize_callback' => 'wayfarer_customize_sanitize_columns',
	) );

	$wp_customize->add_control( 'wayfarer_featured_content_layout', array(
		'label'    => esc_html__( 'Featured Content Layout', 'wayfarer' ),
		'section'  => 'featured_content',
		'settings' => 'featured_content_layout',
		'type'     => 'select',
		'priority' => 10,
		'choices'  => array(
			'2' => esc_html__( '2 Columns', 'wayfarer' ),
			'3' => esc_html__( '3 Columns', 'wayfarer' ),
		),
	) );

	$wp_customize->add_setting( 'featured_content_style', array(
		'capability'        => 'edit_theme_options',
		'default'           => 'color',
		'sanitize_callback' => 'wayfarer_customize_sanitize_featured_content_style',
	) );

	$wp_customize->add_control( 'wayfarer_featured_content_style', array(
		'label'    => esc_html__( 'Featured Content Style', 'wayfarer' ),
		'section'  => 'featured_content',
		'settings' => 'featured_content_style',
		'type'     => 'select',
		'priority' => 10,
		'choices'  => array(
			'color'           => esc_html__( 'Color', 'wayfarer' ),
			'grayscale'       => esc_html__( 'Grayscale', 'wayfarer' ),
			'grayscale-hover' => esc_html__( 'Grayscale Hover', 'wayfarer' ),
		),
	) );

	$wp_customize->add_setting( 'featured_content_image_ratio', array(
		'capability'        => 'edit_theme_options',
		'default'           => '7x5',
		'sanitize_callback' => 'wayfarer_customize_sanitize_aspect_ratio',
	) );

	$wp_customize->add_control( 'wayfarer_featured_content_image_ratio', array(
		'label'    => esc_html__( 'Featured Content Image Ratio', 'wayfarer' ),
		'section'  => 'featured_content',
		'settings' => 'featured_content_image_ratio',
		'type'     => 'select',
		'priority' => 10,
		'choices'  => array(
			'1x1'  => esc_html__( 'Square 1:1', 'wayfarer' ),
			'4x3'  => esc_html__( 'Classic 4:3', 'wayfarer' ),
			'7x5'  => esc_html__( 'Photo 7:5', 'wayfarer' ),
			'16x9' => esc_html__( 'HD Video 16:9', 'wayfarer' ),
			'21x9' => esc_html__( 'Cinemascope 21:9', 'wayfarer' ),
		),
	) );

	$wp_customize->add_setting( 'featured_content_titles', array(
		'capability'        => 'edit_theme_options',
		'default'           => '1',
		'sanitize_callback' => 'wayfarer_customize_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'wayfarer_featured_content_titles', array(
		'label'    => esc_html__( 'Enable featured content titles.', 'wayfarer' ),
		'section'  => 'featured_content',
		'settings' => 'featured_content_titles',
		'type'     => 'checkbox',
		'priority' => 10,
	) );
}
add_action( 'customize_register', 'wayfarer_customize_register' );

/**
 * Bind JS handlers to make the Customizer preview reload changes asynchronously.
 *
 * @since 1.0.0
 */
function wayfarer_customize_preview_assets() {
	wp_enqueue_script(
		'wayfarer-customize-preview',
		get_template_directory_uri() . '/assets/js/customize-preview.js',
		array( 'customize-preview' ),
		'20160324',
		true
	);
}
add_action( 'customize_preview_init', 'wayfarer_customize_preview_assets' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @since 1.0.0
 */
function wayfarer_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site description for the selective refresh partial.
 *
 * @since 1.0.0
 */
function wayfarer_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Sanitization callback for checkbox controls in the Customizer.
 *
 * @since 1.0.0
 *
 * @param string $value Setting value.
 * @return string 1 if checked, empty string otherwise.
 */
function wayfarer_customize_sanitize_checkbox( $value ) {
	return empty( $value ) || ! $value ? '' : '1';
}

/**
 * Sanitization callback for the site header layout controls in the Customizer.
 *
 * @since 1.0.0
 *
 * @param string $value Setting value.
 * @return string Empty by default, value string otherwise.
 */
function wayfarer_customize_sanitize_header_layout( $value ) {
	$section_ids = array_filter( explode( ',', $value ) );
	return implode( ',', array_map( 'sanitize_key', $section_ids ) );
}

/**
 * Sanitization callback for the column counts in the Customizer.
 *
 * @since 1.0.0
 *
 * @param int $value Setting value.
 * @return int
 */
function wayfarer_customize_sanitize_columns( $value ) {
	if ( ! absint( $value ) && ! in_array( $value, range( 1, 4 ), true ) ) {
		$value = '3';
	}

	return $value;
}

/**
 * Sanitize the featured content style value.
 *
 * @since 1.0.0
 *
 * @param string $value Setting value.
 * @return string (color|grayscale|grayscale-hover).
 */
function wayfarer_customize_sanitize_featured_content_style( $value ) {
	if ( ! in_array( $value, array( 'color', 'grayscale', 'grayscale-hover' ), true ) ) {
		$value = 'color';
	}

	return $value;
}

/**
 * Sanitize the featured content image ratio value.
 *
 * @since 1.0.0
 *
 * @param string $value Setting value.
 * @return string (1x1|4x3|7x5|16x9|21x9).
 */
function wayfarer_customize_sanitize_aspect_ratio( $value ) {
	if ( ! in_array( $value, array( '1x1', '4x3', '7x5', '16x9', '21x9' ), true ) ) {
		$value = '7x5';
	}

	return $value;
}

/**
 * Sanitize the site layout value.
 *
 * @since 1.0.0
 *
 * @param string $value Setting value.
 * @return string (align-center|align-left|full-width).
 */
function wayfarer_customize_sanitize_site_layout( $value ) {
	if ( ! in_array( $value, array( 'align-center', 'align-left', 'full-width' ), true ) ) {
		$value = 'align-left';
	}

	return $value;
}

/**
 * Sanitize callback for layout settings in the Customizer.
 *
 * @since 1.0.0
 *
 * @param string $value Setting value.
 * @return string (menu_order|date|title).
 */
function wayfarer_customize_sanitize_page_type_order( $value ) {
	if ( ! in_array( $value, array( 'menu_order', 'date', 'title' ), true ) ) {
		$value = 'menu_order';
	}

	return $value;
}

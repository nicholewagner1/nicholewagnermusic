<?php
/**
 * Meta Slider integration.
 *
 * @package Wayfarer
 * @since 1.0.0
 * @link https://wordpress.org/plugins/ml-slider/
 */

/**
 * Register template parts to load throughout the theme.
 *
 * @since 1.0.0
 */
function wayfarer_metaslider_register_template_parts() {
	if ( wayfarer_metaslider_is_slider_active() ) {
		add_action( 'wayfarer_header_bottom', 'wayfarer_metaslider_front_page_slider' );
	}
}
add_action( 'wayfarer_register_template_parts', 'wayfarer_metaslider_register_template_parts' );

/**
 * Determine if the page has hero slider content.
 *
 * @since 1.0.0
 *
 * @param bool $has_hero Whether the hero area is active.
 * @return bool
 */
function wayfarer_metaslider_has_hero( $has_hero ) {
	if ( wayfarer_metaslider_is_slider_active() ) {
		$has_hero = true;
	}

	return $has_hero;
}
add_filter( 'wayfarer_has_hero', 'wayfarer_metaslider_has_hero' );

/**
 * Determine if the front page can display a Meta Slider.
 *
 * @since 1.0.0
 */
function wayfarer_metaslider_is_slider_active() {
	$slider_id = get_theme_mod( 'metaslider_front_page' );
	return ( is_front_page() && ! empty( $slider_id ) );
}

/**
 * Replace the front page hero image with a slider.
 *
 * @since 1.0.0
 */
function wayfarer_metaslider_front_page_slider() {
	$slider_id = get_theme_mod( 'metaslider_front_page' );

	if ( empty( $slider_id ) ) {
		return;
	}

	echo '<div class="hero hero-slider">';
	echo do_shortcode( "[metaslider id={$slider_id}]" );
	echo '</div>';
}

/**
 * Register Customizer support for the front page slider.
 *
 * @since 1.0.0
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function wayfarer_metaslider_customize_register( $wp_customize ) {
	$wp_customize->add_setting( 'metaslider_front_page', array(
		'sanitize_callback' => 'absint',
	) );

	$wp_customize->add_control( 'wayfarer_metaslider_front_page', array(
		'type'     => 'select',
		'label'    => __( 'Front Page Slider', 'wayfarer' ),
		'section'  => 'theme_options',
		'settings' => 'metaslider_front_page',
		'choices'  => array( '' => '' ) + wp_list_pluck( wayfarer_metaslider_get_sliders(), 'title', 'id' ),
		'priority' => 30,
	) );
}
add_action( 'customize_register', 'wayfarer_metaslider_customize_register' );

/**
 * Retrieve Meta Sliders.
 *
 * @since 1.0.0
 */
function wayfarer_metaslider_get_sliders() {
	$posts = apply_filters( 'metaslider_all_meta_sliders_args', get_posts( array(
		'post_type'      => 'ml-slider',
		'post_status'    => 'publish',
		'orderby'        => 'title',
		'order'          => 'ASC',
		'posts_per_page' => -1,
	) ) );

	$sliders = array();

	foreach ( $posts as $post ) {
		$sliders[] = array(
			'title' => $post->post_title,
			'id'    => $post->ID,
		);
	}

	return $sliders;
}

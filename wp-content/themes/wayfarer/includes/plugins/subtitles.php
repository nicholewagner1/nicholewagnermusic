<?php
/**
 * Subtitles integration.
 *
 * @package Wayfarer
 * @since 1.0.0
 * @link https://wordpress.org/plugins/subtitles/
 */

/**
 * Display a subtitle in the hero area.
 *
 * @since 1.0.0
 *
 * @param string $content Hero content.
 * @return string
 */
function wayfarer_subtitles_hero_subtitles( $content ) {
	global $post;

	if ( is_singular() && get_the_subtitle( $post ) ) {
		$content = the_subtitle( '<h2 class="hero-subtitle">', '</h2>' . $content, false );
	}

	return $content;
}
add_filter( 'wayfarer_hero_content', 'wayfarer_subtitles_hero_subtitles' );

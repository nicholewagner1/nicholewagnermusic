<?php
/**
 * Cue integration.
 *
 * @package Wayfarer
 * @since 1.0.0
 * @link https://audiotheme.com/view/cue/
 */

/**
 * Register a Cue player.
 *
 * @since 1.0.0
 *
 * @param array $players List of players.
 */
function wayfarer_register_cue_players( $players ) {
	$players['wayfarer_player'] = esc_html__( 'Site-wide Player', 'wayfarer' );
	return $players;
}
add_filter( 'cue_players', 'wayfarer_register_cue_players' );

/**
 * Filter tracks for the site-wide player.
 *
 * Returns tracks from the Cue playlist associated with the player location.
 *
 * @since 1.0.0
 *
 * @param array $tracks List of tracks.
 */
function wayfarer_cue_player_tracks( $tracks ) {
	return get_cue_player_tracks( 'wayfarer_player', array( 'context' => 'wp-playlist' ) );
}
add_filter( 'pre_wayfarer_player_tracks', 'wayfarer_cue_player_tracks' );

/**
 * Display a purchase link in the player tracklist.
 *
 * @since 1.0.0
 *
 * @param array $track Track.
 */
function wayfarer_cue_player_track_action_links( $track ) {
	// Check for Cue Pro function to display action links.
	if ( ! function_exists( 'cue_track_action_links' ) ) {
		return;
	}

	cue_track_action_links( $track );
}
add_action( 'wayfarer_player_track_bottom', 'wayfarer_cue_player_track_action_links' );

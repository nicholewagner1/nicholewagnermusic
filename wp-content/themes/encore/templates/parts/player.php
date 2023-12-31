<?php
/**
 * The template part for displaying the site-wide player.
 *
 * @package Encore
 * @since 1.0.0
 */

?>

<div class="encore-player cue-playlist content-box">
	<audio src="<?php echo esc_url( $settings['tracks'][0]['src'] ); ?>" controls preload="none" style="width: 100%; height: auto"></audio>

	<div class="playlist cue-tracklist">
		<ol class="tracklist cue-tracks">
			<?php foreach ( $settings['tracks'] as $track ) : ?>
				<li class="cue-track" itemprop="track" itemscope itemtype="http://schema.org/MusicRecording">
					<?php do_action( 'encore_player_track_top', $track ); ?>

					<span class="cue-track-details">
						<span class="cue-track-title" itemprop="name"><?php echo esc_html( $track['title'] ); ?></span>
						<span class="cue-track-artist" itemprop="byArtist"><?php echo esc_html( $track['meta']['artist'] ); ?></span>
					</span>

					<span class="cue-track-length"><?php echo esc_html( $track['meta']['length_formatted'] ); ?></span>

					<?php do_action( 'encore_player_track_bottom', $track ); ?>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>

	<script type="application/json" class="cue-playlist-data"><?php echo wp_json_encode( $settings ); ?></script>
</div>

<?php
/**
 * The template used for displaying a tacklist for individual tracks.
 *
 * @package Encore
 * @since 1.0.0
 */

$track_url = get_audiotheme_track_file_url();

if ( ! $track_url ) {
	return;
}
?>

<div class="tracklist-area">
	<h2 class="screen-reader-text"><?php esc_html_e( 'Record Tracklist', 'encore' ); ?></h2>

	<ol class="tracklist record-tracklist">
		<li id="track-<?php the_ID(); ?>" class="track" itemprop="track" itemscope itemtype="http://schema.org/MusicRecording">
			<?php the_title( '<span class="track-title" itemprop="name">', '</span>' ); ?>
			<meta content="<?php the_permalink(); ?>" itemprop="url" />
			<span class="track-meta">
				<span class="track-current-time">-:--</span>
				<span class="track-sep-duration"> / </span>
				<span class="track-duration"><?php encore_audiotheme_track_length(); ?></span>
			</span>
		</li>

		<?php enqueue_audiotheme_tracks( get_the_ID(), 'record' ); ?>
	</ol>
</div>

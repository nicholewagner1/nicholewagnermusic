<?php
/**
 * The template used for displaying a tacklist for individual records.
 *
 * @package Encore
 * @since 1.0.0
 */

$tracks = get_audiotheme_record_tracks();

if ( ! $tracks ) {
	return;
}
?>

<div class="tracklist-area">
	<h2 class="screen-reader-text"><?php esc_html_e( 'Record Tracklist', 'encore' ); ?></h2>
	<meta itemprop="numTracks" content="<?php echo esc_attr( count( $tracks ) ); ?>" />

	<ol class="tracklist record-tracklist">
		<?php foreach ( $tracks as $track ) : ?>
			<li id="track-<?php echo absint( $track->ID ); ?>" class="track" itemprop="track" itemscope itemtype="http://schema.org/MusicRecording">
				<?php
				encore_audiotheme_track_title(
					$track->ID,
					array(
						'before_link' => '<span itemprop="name">',
						'after_link'  => '</span>',
					)
				);
				?>

				<span class="track-meta">
					<span class="track-current-time">-:--</span>
					<span class="track-sep-duration"> / </span>
					<span class="track-duration"><?php encore_audiotheme_track_length( $track->ID ); ?></span>
				</span>
			</li>
		<?php endforeach; ?>

		<?php enqueue_audiotheme_tracks( wp_list_pluck( $tracks, 'ID' ), 'record' ); ?>
	</ol>
</div>

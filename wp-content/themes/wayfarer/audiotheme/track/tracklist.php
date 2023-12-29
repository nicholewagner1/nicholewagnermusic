<?php
/**
 * The template used for displaying a tracklist for individual tracks.
 *
 * @package Wayfarer
 * @since 1.0.0
 */

if ( ! get_audiotheme_track_file_url() ) {
	return;
}
?>

<div class="tracklist-area">
	<h2 class="screen-reader-text"><?php esc_html_e( 'Record Tracklist', 'wayfarer' ); ?></h2>

	<ol class="tracklist record-tracklist track-tracklist">
		<li id="track-<?php the_ID(); ?>" <?php wayfarer_track_attributes( get_the_ID() ); ?>>
			<?php the_title( '<span class="track-title" itemprop="name">', '</span>' ); ?>
			<meta content="<?php the_permalink(); ?>" itemprop="url" />
			<span class="track-meta">
				<span class="track-current-time">-:--</span>
				<span class="track-duration-sep"> / </span>
				<span class="track-duration"><?php wayfarer_audiotheme_track_length(); ?></span>
			</span>
		</li>

		<?php enqueue_audiotheme_tracks( get_the_ID(), 'record' ); ?>
	</ol>
</div>

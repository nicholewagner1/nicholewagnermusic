<?php
/**
 * The template used for displaying a meta on single record pages.
 *
 * @package Wayfarer
 * @since 1.0.0
 */

$artist = get_audiotheme_record_artist();
$year   = get_audiotheme_record_release_year();
$genre  = get_audiotheme_record_genre();

if ( ! $artist && ! $year && ! $genre ) {
	return;
}
?>

<div class="record-meta">
	<h2 class="screen-reader-text"><?php esc_html_e( 'Record Details', 'wayfarer' ); ?></h2>

	<dl>
		<?php if ( ! empty( $artist ) ) : ?>
			<dt class="record-artist screen-reader-text"><?php esc_html_e( 'Artist:', 'wayfarer' ); ?></dt>
			<dd class="record-artist"><?php echo esc_html( $artist ); ?></dd>
		<?php endif; ?>

		<?php if ( ! empty( $year ) ) : ?>
			<dt class="record-year screen-reader-text"><?php esc_html_e( 'Released:', 'wayfarer' ); ?></dt>
			<dd class="record-year"><?php echo esc_html( $year ); ?></dd>
		<?php endif; ?>

		<?php if ( ! empty( $genre ) ) : ?>
			<dt class="record-genre screen-reader-text"><?php esc_html_e( 'Genre:', 'wayfarer' ); ?></dt>
			<dd class="record-genre"><?php echo esc_html( $genre ); ?></dd>
		<?php endif; ?>
	</dl>
</div>

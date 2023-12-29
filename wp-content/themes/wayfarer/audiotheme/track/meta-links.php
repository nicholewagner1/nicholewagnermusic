<?php
/**
 * The template used for displaying a meta links on single track pages.
 *
 * @package Wayfarer
 * @since 1.0.0
 */

$purchase_url = get_audiotheme_track_purchase_url();
$download_url = is_audiotheme_track_downloadable();

if ( ! $purchase_url && ! $download_url ) {
	return;
}
?>

<div class="record-links">
	<h2 class="screen-reader-text"><?php esc_html_e( 'Track Links', 'wayfarer' ); ?></h2>

	<ul>
		<?php if ( $purchase_url ) : ?>
			<li>
				<a class="button js-maybe-exernal" href="<?php echo esc_url( $purchase_url ); ?>" itemprop="url"><?php esc_html_e( 'Purchase', 'wayfarer' ); ?></a>
			</li>
		<?php endif; ?>

		<?php if ( $download_url ) : ?>
			<li>
				<a class="button" href="<?php echo esc_url( $download_url ); ?>" itemprop="url" download="<?php esc_attr( basename( $download_url ) ); ?>"><?php esc_html_e( 'Download', 'wayfarer' ); ?></a>
			</li>
		<?php endif; ?>
	</ul>
</div>

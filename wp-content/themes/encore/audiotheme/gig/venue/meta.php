<?php
/**
 * The template used for displaying venue meta on single gig pages.
 *
 * @package Encore
 * @since 1.0.0
 */

if ( audiotheme_gig_has_venue() ) :
?>

	<div class="venue-meta" itemprop="location" itemscope itemtype="http://schema.org/EventVenue">
		<h2 class="screen-reader-text"><?php esc_html_e( 'Venue Details', 'encore' ); ?></h2>

		<dl class="venue-address screen-reader-text">
			<dt><?php esc_html_e( 'Address', 'encore' ); ?></dt>
			<dd>
				<?php
				the_audiotheme_venue_vcard( array(
					'container' => '',
				) );
				?>
			</dd>
		</dl>

		<?php $venue = get_audiotheme_venue( get_audiotheme_gig()->venue->ID ); ?>

		<?php if ( $venue->phone ) : ?>
			<dl class="venue-phone">
				<dt class="screen-reader-text"><?php esc_html_e( 'Phone', 'encore' ); ?></dt>
				<dd><?php echo esc_html( $venue->phone ); ?></dd>
			</dl>
		<?php endif; ?>

		<?php if ( $venue->website ) : ?>
			<dl class="venue-website">
				<dt class="screen-reader-text"><?php esc_html_e( 'Website', 'encore' ); ?></dt>
				<dd><a href="<?php echo esc_url( $venue->website ); ?>" itemprop="url"><?php echo esc_html( audiotheme_simplify_url( $venue->website ) ); ?></a></dd>
			</dl>
		<?php endif; ?>
	</div>

<?php
endif;

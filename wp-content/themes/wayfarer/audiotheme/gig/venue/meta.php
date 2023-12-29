<?php
/**
 * The template used for displaying venue meta on single gig pages.
 *
 * @package Wayfarer
 * @since 1.0.0
 */

$venue = get_audiotheme_venue( get_audiotheme_gig()->venue->ID );
?>

<div class="venue-meta" itemprop="location" itemscope itemtype="https://schema.org/EventVenue">
	<h2 class="screen-reader-text"><?php esc_html_e( 'Venue Details', 'wayfarer' ); ?></h2>

	<?php get_template_part( 'audiotheme/gig/venue/map' ); ?>

	<dl class="venue-address">
		<dt class="screen-reader-text"><?php esc_html_e( 'Address', 'wayfarer' ); ?></dt>
		<dd>
			<?php
			the_audiotheme_venue_vcard( array(
				'container'  => '',
				'separator'  => ', ',
				'show_name'  => false,
				'show_phone' => false,
			) );
			?>

			<?php if ( $venue->phone ) : ?>
				<span class="venue-phone"><?php echo esc_html( $venue->phone ); ?></span>
			<?php endif; ?>

			<?php if ( $venue->website ) : ?>
				<a class="venue-website" href="<?php echo esc_url( $venue->website ); ?>" itemprop="url"><?php echo esc_html( audiotheme_simplify_url( $venue->website ) ); ?></a>
			<?php endif; ?>
		</dd>
	</dl>
</div>

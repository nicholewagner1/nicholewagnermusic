<?php
/**
 * The template used for displaying a gig in gig archives.
 *
 * @package Wayfarer
 * @since 1.0.0
 */

?>

<div id="post-<?php the_ID(); ?>" <?php post_class( array( 'gig-card', 'vevent' ) ); ?> itemscope itemtype="https://schema.org/MusicEvent">

	<a class="gig-date date" href="<?php the_permalink(); ?>">
		<meta itemprop="startDate" content="<?php echo esc_attr( get_audiotheme_gig_time( 'c' ) ); ?>">
		<time class="dtstart" datetime="<?php echo esc_attr( get_audiotheme_gig_time( 'c' ) ); ?>">
			<?php echo esc_html( get_audiotheme_gig_time( get_option( 'date_format', 'F d, Y' ) ) ); ?>
		</time>
	</a>

	<?php if ( audiotheme_gig_has_venue() ) : ?>

		<h3 class="gig-title" itemprop="name">
			<?php echo wayfarer_allowed_tags( get_audiotheme_gig_title() ); ?>
		</h3>

		<div class="gig-venue">
			<h4 class="gig-location"><?php echo wayfarer_allowed_tags( get_audiotheme_gig_location() ); ?></h4>
			<figure class="gig-venue-vcard location vcard screen-reader-text" itemprop="location" itemscope itemtype="https://schema.org/EventVenue">
				<?php
				the_audiotheme_venue_vcard( array(
					'container' => '',
				) );
				?>
			</figure>
		</div>

		<?php the_audiotheme_gig_description( '<div class="gig-description" itemprop="description">', '</div>' ); ?>

		<div class="gig-actions">
			<?php
			$gig_tickets_url = get_audiotheme_gig_tickets_url();

			if ( $gig_tickets_url ) :
			?>
				<a class="gig-tickets-link button button-small js-maybe-external" href="<?php echo esc_url( $gig_tickets_url ); ?>">
					<?php esc_html_e( 'Tickets', 'wayfarer' ); ?>
				</a>
			<?php endif; ?>

			<a class="gig-info-link" href="<?php the_permalink(); ?>" itemprop="url">
				<?php esc_html_e( 'Info', 'wayfarer' ); ?>
			</a>
		</div>

	<?php else : ?>

		<div class="gig-venue no-gig-venue">
			<?php esc_html_e( 'Gig venue details are missing or incomplete.', 'wayfarer' ); ?>
			<?php edit_post_link( esc_html__( 'Edit Gig', 'wayfarer' ) ); ?>
		</div>

	<?php endif; ?>

</div>

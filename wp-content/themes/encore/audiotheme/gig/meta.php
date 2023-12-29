<?php
/**
 * The template used for displaying a meta single gig pages.
 *
 * @package Encore
 * @since 1.0.0
 */

?>

<div class="gig-meta">
	<h2 class="screen-reader-text"><?php esc_html_e( 'Gig Details', 'encore' ); ?></h2>

	<dl class="gig-time">
		<dt><?php esc_html_e( 'Time', 'encore' ); ?></dt>
		<dd>
			<?php
			echo esc_html( get_audiotheme_gig_time(
				'',
				get_option( 'time_format', 'g:i A' ),
				false,
				array( 'empty_time' => esc_html__( 'TBD', 'encore' ) )
			) );
			?>
		</dd>
	</dl>

	<?php if ( audiotheme_gig_has_ticket_meta() ) : ?>
		<dl class="gig-tickets" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
			<dt><?php esc_html_e( 'Tickets', 'encore' ); ?></dt>
			<dd>
				<?php
				$gig_tickets_price = get_audiotheme_gig_tickets_price();
				$gig_tickets_url   = get_audiotheme_gig_tickets_url();

				if ( $gig_tickets_price ) :
				?>
					<span class="gig-tickets-price" itemprop="price"><?php echo esc_html( $gig_tickets_price ); ?></span>
				<?php endif; ?>

				<?php if ( $gig_tickets_url ) : ?>
					<span class="gig-tickets-url-sep">&ndash;</span>
					<a class="gig-tickets-url js-maybe-external" href="<?php echo esc_url( $gig_tickets_url ); ?>" itemprop="url">
						<?php esc_html_e( 'Buy', 'encore' ); ?>
					</a>
				<?php endif; ?>
			</dd>
		</dl>
	<?php endif; ?>

	<?php if ( apply_filters( 'encore_show_gig_subscribe_links', true ) ) : ?>
		<dl class="gig-subscribe">
			<dt><?php esc_html_e( 'Subscribe', 'encore' ); ?></dt>
			<dd class="gig-subscribe-ical">
				<a href="<?php the_audiotheme_gig_ical_link(); ?>"><?php esc_html_e( 'iCal', 'encore' ); ?></a>
			</dd>
			<dd class="gig-subscribe-gcal">
				<a class="js-popup" href="<?php the_audiotheme_gig_gcal_link(); ?>" data-popup-width="800" data-popup-height="600"><?php esc_html_e( 'Google', 'encore' ); ?></a>
			</dd>
		</dl>
	<?php endif; ?>
</div>

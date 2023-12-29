<?php
/**
 * The template used for displaying a meta single gig pages.
 *
 * @package Wayfarer
 * @since 1.0.0
 */

?>

<ul class="gig-meta">
	<?php if ( audiotheme_gig_has_venue() ) : ?>
		<li class="gig-location">
			<?php $venue = get_audiotheme_venue( get_audiotheme_gig()->venue->ID ); ?>
			<?php echo wayfarer_allowed_tags( get_audiotheme_venue_location( $venue->ID ) ); ?>
		</li>
	<?php endif; ?>

	<li class="gig-date-time">
		<span class="gig-date date">
			<meta itemprop="startDate" content="<?php echo esc_attr( get_audiotheme_gig_time( 'c' ) ); ?>">
			<time class="dtstart" datetime="<?php echo esc_attr( get_audiotheme_gig_time( 'c' ) ); ?>">
				<?php echo esc_html( get_audiotheme_gig_time( get_option( 'date_format', 'F d, Y' ) ) ); ?>
			</time>
		</span>
		<span class="gig-time-sep">@</span>
		<span class="gig-time">
			<?php
			echo esc_html( get_audiotheme_gig_time(
				'',
				get_option( 'time_format', 'l \@ g:i A' ),
				false,
				array(
					'empty_time' => esc_html__( 'TBD', 'wayfarer' ),
				)
			) );
			?>
		</span>
	</li>

	<?php
	wayfarer_audiotheme_gig_ticket_meta(
		'<li class="gig-ticket" itemprop="offers" itemscope itemtype="https://schema.org/Offer">',
		'</li>'
	);
	?>
</ul>

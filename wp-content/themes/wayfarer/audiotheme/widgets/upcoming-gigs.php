<?php
/**
 * Template to display an Upcoming Gigs widget.
 *
 * @package Wayfarer
 * @since 1.0.0
 */

if ( ! empty( $title ) ) {
	echo $before_title . $title . $after_title;
}
?>

<?php if ( $loop->have_posts() ) : ?>

	<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>

		<dl class="vevent" itemscope itemtype="http://schema.org/MusicEvent">

			<?php
			$gig = get_audiotheme_gig();
			echo get_audiotheme_gig_link( $gig, array(
				'before' => '<dt>',
				'after'  => '</dt>',
			) );
			?>

			<?php if ( audiotheme_gig_has_venue() ) : ?>
				<dd class="location">
					<a href="<?php the_permalink(); ?>"><span class="gig-title"><?php echo get_audiotheme_gig_location(); ?></span></a>
				</dd>
			<?php endif; ?>

			<dd class="date">
				<meta content="<?php echo esc_attr( get_audiotheme_gig_time( 'c' ) ); ?>" itemprop="startDate">
				<time class="dtstart" datetime="<?php echo esc_attr( get_audiotheme_gig_time( 'c' ) ); ?>">
					<?php echo get_audiotheme_gig_time( $date_format ); ?>
				</time>
			</dd>

			<?php if ( ! empty( $gig->post_title ) && audiotheme_gig_has_venue() ) : ?>
				<dd class="venue"><?php echo esc_html( get_audiotheme_venue( $gig->venue->ID )->name ); ?></dd>
			<?php endif; ?>

			<?php
			$gig_description = get_audiotheme_gig_description();

			if ( $gig_description ) :
			?>
				<dd class="description"><?php echo wp_strip_all_tags( $gig_description ); ?></dd>
			<?php endif; ?>

			<?php
			$gig_tickets_url = get_audiotheme_gig_tickets_url();

			if ( $gig_tickets_url ) :
			?>
				<dd class="tickets">
					<a class="gig-tickets-link button button-small js-maybe-external" href="<?php echo esc_url( $gig_tickets_url ); ?>">
						<?php esc_html_e( 'Tickets', 'wayfarer' ); ?>
					</a>
				</dd>
			<?php endif; ?>

		</dl>

	<?php endwhile; ?>

<?php endif; ?>

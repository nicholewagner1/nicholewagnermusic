<?php
/**
 * The template for displaying a single gig.
 *
 * @package   AudioTheme\Template
 * @copyright Copyright 2012 AudioTheme
 * @license   GPL-2.0+
 * @link      https://audiotheme.com/
 * @since     1.2.0
 */

get_header();
?>

<?php do_action( 'audiotheme_before_main_content' ); ?>

<?php
while ( have_posts() ) :
	the_post();
	$gig = get_audiotheme_gig();
	$venue = get_audiotheme_venue( $gig->venue->ID );
	?>

	<dl id="audiotheme-gig" <?php post_class( array( 'audiotheme-gig-single', 'audiotheme-clearfix' ) ) ?> itemscope itemtype="http://schema.org/MusicEvent">
<!-- childtheme version-->
		<?php if ( audiotheme_gig_has_venue() ) : ?>

			<dt class="audiotheme-gig-header">
				<?php the_title( '<h1 class="audiotheme-gig-title entry-title" itemprop="name">', '</h1>' ); ?>
							</dt><!-- /.gig-header -->

<div class="gig-details">
	
				<p class="gig-date">
	<time class="gig-time" datetime="<?php echo esc_html( get_audiotheme_gig_time( 'c' ) ); ?>"><?php echo esc_html( get_audiotheme_gig_time( get_option( 'date_format', 'F d, Y' ) ) ); ?></time>

	<span class="gig-day"><?php echo esc_html( get_audiotheme_gig_time( 'l' ) ); ?></span>
	<span class="gig-time-sep">&commat;</span>
	<span class="gig-time">
		<?php
		echo esc_html( get_audiotheme_gig_time(
			'',
			get_option( 'time_format', 'g:i A' ),
			false,
			array( 'empty_time' => esc_html__( 'TBD', 'audiotheme' ) )
		) );
		?>
	</span>
</p><!-- /.gig-date -->

		<?php endif; ?>
		
</div>
		<div class="gig-details">

		
		<dd class="audiotheme-gig-venue audiotheme-clearfix" itemprop="location" itemscope itemtype="http://schema.org/EventVenue">
				<?php
				the_audiotheme_venue_vcard( array(
					'container'         => '',
					'show_name_link'    => true,
					'show_phone'        => false,
					'separator_country' => ' ',
				) );
				?>

				<div class="audiotheme-venue-meta">
					<?php if ( $venue->phone ) : ?>
						<span class="audiotheme-venue-phone hide" style="display:none;"><?php echo esc_html( $venue->phone ); ?></span>
					<?php endif; ?>

					<?php if ( $venue->website ) : ?>
						<span class="hide audiotheme-venue-website" style="display:none;"><a href="<?php echo esc_url( $venue->website ); ?>" itemprop="url">Venue Website</a></span>
					<?php endif; ?>
				</div>
			<?php if ( audiotheme_gig_has_venue() ) : ?>

				<p class="audiotheme-gig-place">
					<?php echo get_audiotheme_venue_location( $gig->venue->ID ); ?>
				</p>

			<?php endif; ?>
</div>
</div>

		<dd class="audiotheme-gig-description">
			
			
			

			<?php the_audiotheme_gig_description( '<div class="audiotheme-gig-note" itemprop="description">', '</div>' ); ?>
			<div class="audiotheme-gig-note" style="display:none;" itemprop="performer">Nichole Wagner</div>
		</dd><!-- /.gig-description -->
<dd class="audiotheme-content entry-content">

			<?php the_content(); ?>

		</dd>
		<dd class="audiotheme-gig-meta audiotheme-meta-list">
			<span class="audiotheme-gig-time audiotheme-meta-item hide" style="display:none;">
				<strong class="audiotheme-label"><?php _e( 'Time', 'audiotheme' ); ?></strong>
				<?php echo get_audiotheme_gig_time( '', 'g:i A', false, array( 'empty_time' => __( 'TBD', 'audiotheme' ) ) ); ?>
			</span>

			<?php if ( audiotheme_gig_has_ticket_meta() ) : ?>

				<span class="audiotheme-gig-tickets audiotheme-meta-item" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
					<strong class="audiotheme-label"><?php _e( ' ', 'audiotheme' ); ?></strong>

					<?php if ( $gig_tickets_price = get_audiotheme_gig_tickets_price() ) : ?>
						<span class="audiotheme-gig-tickets-price" itemprop="price">$<?php echo esc_html( $gig_tickets_price ); ?></span>
					<?php endif; ?>

					<?php if ( $gig_tickets_url = get_audiotheme_gig_tickets_url() ) : ?>
						<span class="audiotheme-gig-tickets-link"><a href="<?php echo esc_url( $gig_tickets_url ); ?>" target="_blank" itemprop="url"><?php _e( 'Buy Tickets', 'audiotheme' ); ?></a></span>
					<?php endif; ?>
				</span>

			<?php endif; ?>

		</dd><!-- /.gig-meta -->

		<?php if ( audiotheme_gig_has_venue() ) : ?>

			

				<div class="audiotheme-venue-map">
					<?php echo get_audiotheme_google_map_embed( array( 'width' => '100%', 'height' => 220 ), $venue->ID ); ?>
				</div>
			</dd><!-- /.gig-venue -->

		<?php endif; ?>

		<!-- /.gig-content -->

	</dl><!-- /#audiotheme-gig -->

<?php endwhile; ?>

<?php do_action( 'audiotheme_after_main_content' ); ?>

<?php get_footer(); ?>

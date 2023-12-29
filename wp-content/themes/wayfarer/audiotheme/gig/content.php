<?php
/**
 * The template used for displaying individual gigs.
 *
 * @package Wayfarer
 * @since 1.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'vevent' ); ?> itemscope itemtype="https://schema.org/MusicEvent">

	<?php do_action( 'wayfarer_entry_top' ); ?>

	<header class="entry-header">
		<?php do_action( 'wayfarer_entry_header_top' ); ?>

		<h1 class="entry-title" itemprop="name">
			<?php echo wayfarer_allowed_tags( get_audiotheme_gig_title() ); ?>
		</h1>

		<?php if ( audiotheme_gig_has_venue() ) : ?>
			<h2 class="entry-subtitle">
				<?php $venue = get_audiotheme_venue( get_audiotheme_gig()->venue->ID ); ?>
				<span class="gig-venue-name-sep">@</span>
				<span class="gig-venue-name">
					<?php echo esc_html( $venue->name ); ?>
				</span>
			</h2>
		<?php endif; ?>

		<?php the_audiotheme_gig_description( '<div class="gig-description" itemprop="description">', '</div>' ); ?>

		<?php do_action( 'wayfarer_entry_header_bottom' ); ?>
	</header>

	<?php if ( audiotheme_gig_has_venue() ) : ?>
		<div class="entry-sidebar-wrapper">
			<?php get_template_part( 'audiotheme/gig/meta' ); ?>
		</div>
	<?php endif; ?>

	<div class="entry-content-wrapper">
		<?php get_template_part( 'audiotheme/gig/venue/meta' ); ?>

		<div class="entry-content" itemprop="text">
			<?php do_action( 'wayfarer_entry_content_top' ); ?>
			<?php the_content(); ?>
			<?php do_action( 'wayfarer_entry_content_bottom' ); ?>
		</div>
	</div>

	<?php do_action( 'wayfarer_entry_bottom' ); ?>

</article>

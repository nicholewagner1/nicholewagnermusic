<?php
/**
 * The template used for displaying individual gigs.
 *
 * @package Encore
 * @since 1.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'vevent' ); ?> itemscope itemtype="http://schema.org/MusicEvent">
	<header class="entry-header">
		<h1 class="entry-title" itemprop="name">
			<span class="gig-date">
				<meta content="<?php echo esc_html( get_audiotheme_gig_time( 'c' ) ); ?>" itemprop="startDate">
				<time datetime="<?php echo esc_html( get_audiotheme_gig_time( 'c' ) ); ?>">
					<?php echo esc_html( get_audiotheme_gig_time( get_option( 'date_format', 'F d, Y' ) ) ); ?>
				</time>
			</span>
			<span class="gig-title-sep">&mdash;</span>
			<span class="gig-title"><?php echo encore_allowed_tags( get_audiotheme_gig_title() ); ?></span>
		</h1>

		<h2 class="gig-location">
			<?php echo encore_allowed_tags( get_audiotheme_venue_location( get_audiotheme_gig()->venue->ID ) ); ?>
		</h2>
	</header>

	<?php get_template_part( 'audiotheme/gig/venue/map' ); ?>

	<div class="entry-meta">
		<?php get_template_part( 'audiotheme/gig/meta' ); ?>
		<?php get_template_part( 'audiotheme/gig/venue/meta' ); ?>
	</div>

	<div class="entry-content" itemprop="text">
		<?php do_action( 'encore_entry_content_top' ); ?>
		<?php the_content(); ?>
		<?php do_action( 'encore_entry_content_bottom' ); ?>
	</div>
</article>

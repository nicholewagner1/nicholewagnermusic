<?php
/**
 * The template used for displaying gig archives.
 *
 * @package Encore
 * @since 1.0.0
 */

?>

<div class="content-box">
	<header class="page-header">
		<?php the_audiotheme_archive_title( '<h1 class="page-title" itemprop="headline">', '</h1>' ); ?>
		<?php the_audiotheme_archive_description( '<div class="page-content" itemprop="text">', '</div>' ); ?>
	</header>

	<div id="gigs" class="gig-list vcalendar">
		<h2 class="gig-list-header">
			<span class="gig-list-header-date"><?php esc_html_e( 'Date', 'encore' ); ?></span>
			<span class="gig-list-header-location-sep">,&nbsp;</span>
			<span class="gig-list-header-location"><?php esc_html_e( 'Location', 'encore' ); ?></span>
			<span class="gig-list-header-venue-sep">,&nbsp;</span>
			<span class="gig-list-header-venue"><?php esc_html_e( 'Venue', 'encore' ); ?></span>
		</h2>

		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'audiotheme/gig/card' ); ?>
		<?php endwhile; ?>
	</div>

	<?php encore_content_navigation(); ?>
</div>

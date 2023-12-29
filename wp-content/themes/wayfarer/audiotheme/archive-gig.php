<?php
/**
 * The template for displaying a list of gigs.
 *
 * @package Wayfarer
 * @since 1.0.0
 */

get_header();
?>

<div id="primary" <?php audiotheme_archive_class( array( 'content-area', 'archive-gig' ) ); ?>>

	<?php do_action( 'wayfarer_primary_top' ); ?>

	<main id="main" class="site-main" role="main" itemprop="mainContentOfPage">

		<?php do_action( 'wayfarer_main_top' ); ?>

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<?php the_audiotheme_archive_title( '<h1 class="page-title" itemprop="headline">', '</h1>' ); ?>
				<?php wayfarer_post_type_navigation( 'audiotheme_gig' ); ?>
			</header>

			<?php if ( ! is_paged() ) : ?>

				<?php the_audiotheme_archive_description( '<div class="page-content" itemprop="text">', '</div>' ); ?>

			<?php endif; ?>

			<div id="posts-container" <?php wayfarer_posts_class(); ?>>
				<h2 class="gig-list-header screen-reader-text">
					<span class="gig-list-header-date"><?php esc_html_e( 'Date', 'wayfarer' ); ?></span>
					<span class="gig-list-header-location-sep">,&nbsp;</span>
					<span class="gig-list-header-location"><?php esc_html_e( 'Location', 'wayfarer' ); ?></span>
					<span class="gig-list-header-venue-sep">,&nbsp;</span>
					<span class="gig-list-header-venue"><?php esc_html_e( 'Venue', 'wayfarer' ); ?></span>
				</h2>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'audiotheme/gig/card', wayfarer_post_template_name() ); ?>

				<?php endwhile; ?>

			</div>

		<?php else : ?>

			<?php get_template_part( 'audiotheme/gig/content', 'none' ); ?>

		<?php endif; ?>

		<?php do_action( 'wayfarer_main_bottom' ); ?>

	</main>

	<?php do_action( 'wayfarer_primary_bottom' ); ?>

</div>

<?php
get_footer();

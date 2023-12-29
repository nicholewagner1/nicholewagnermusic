<?php
/**
 * The template for displaying a single video.
 *
 * @package Wayfarer
 * @since 1.0.0
 */

get_header();
?>

<div id="primary" class="content-area single-video">

	<?php do_action( 'wayfarer_primary_top' ); ?>

	<main id="main" class="site-main" role="main" itemprop="mainContentOfPage">

		<?php do_action( 'wayfarer_main_top' ); ?>

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'audiotheme/video/content', wayfarer_post_template_name() ); ?>

		<?php endwhile; ?>

		<?php do_action( 'wayfarer_main_bottom' ); ?>

	</main>

	<?php do_action( 'wayfarer_primary_bottom' ); ?>

</div>

<?php
get_sidebar( 'single-video' );

get_footer();

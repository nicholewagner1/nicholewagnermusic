<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Wayfarer
 * @since 1.0.0
 */

get_header();
?>

<div id="primary" class="content-area archive-content">

	<?php do_action( 'wayfarer_primary_top' ); ?>

	<main id="main" class="site-main" role="main">

		<?php do_action( 'wayfarer_main_top' ); ?>

		<?php if ( have_posts() ) : ?>

			<div id="posts-container" <?php wayfarer_posts_class(); ?>>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'templates/parts/content-archive', wayfarer_post_template_name() ); ?>

				<?php endwhile; ?>

			</div>

		<?php else : ?>

			<?php get_template_part( 'templates/parts/content', 'none' ); ?>

		<?php endif; ?>

		<?php do_action( 'wayfarer_main_bottom' ); ?>

	</main>

	<?php do_action( 'wayfarer_primary_bottom' ); ?>

</div>

<?php get_sidebar(); ?>

<?php
get_footer();

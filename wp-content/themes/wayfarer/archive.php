<?php
/**
 * The template for displaying archive pages.
 *
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

			<header class="page-header">
				<?php the_archive_title( '<h1 class="page-title" itemprop="headline"><span>', '</span></h1>' ); ?>
			</header>

			<?php if ( ! is_paged() ) : ?>

				<?php the_archive_description( '<div class="page-content" itemprop="text">', '</div>' ); ?>

			<?php endif; ?>

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

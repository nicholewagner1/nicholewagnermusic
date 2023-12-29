<?php
/**
 * The template used for displaying search results.
 *
 * @package Wayfarer
 * @since 1.0.0
 */

get_header();
?>

<div id="primary" class="content-area">

	<?php do_action( 'wayfarer_primary_top' ); ?>

	<main id="main" class="site-main" role="main" itemprop="mainContentOfPage">

		<?php do_action( 'wayfarer_main_top' ); ?>

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title archive-title">
					<span><?php esc_html_e( 'Search Results for:', 'wayfarer' ); ?></span> <?php echo esc_html( get_search_query() ); ?>
				</h1>
			</header>

			<div id="posts-container" <?php wayfarer_posts_class(); ?>>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'templates/parts/content-search', wayfarer_post_template_name() ); ?>

				<?php endwhile; ?>

			</div>

		<?php else : ?>

			<?php get_template_part( 'templates/parts/content', 'none' ); ?>

		<?php endif; ?>

		<?php do_action( 'wayfarer_main_bottom' ); ?>

	</main>

	<?php do_action( 'wayfarer_primary_bottom' ); ?>

</div>

<?php
get_sidebar( 'search' );

get_footer();

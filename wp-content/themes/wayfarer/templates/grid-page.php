<?php
/**
 * Template Name: Grid Page
 *
 * @package Wayfarer
 * @since 1.0.0
 */

get_header();
?>

<div id="primary" class="content-area archive-grid">

	<?php do_action( 'wayfarer_primary_top' ); ?>

	<main id="main" class="site-main" role="main" itemprop="mainContentOfPage">

		<?php do_action( 'wayfarer_main_top' ); ?>

		<?php while ( have_posts() ) : the_post(); ?>

			<header class="page-header">
				<?php the_title( '<h1 class="page-title" itemprop="headline">', '</h1>' ); ?>

				<?php if ( wayfarer_has_content() ) : ?>
					<div class="page-content" itemprop="text"><?php the_content(); ?></div>
				<?php endif; ?>
			</header>

		<?php endwhile; ?>

		<?php
		$loop = wayfarer_page_type_query();
		if ( $loop->have_posts() ) :
		?>

			<?php $classes = array( 'block-grid', 'block-grid-3', 'block-grid--gutters', 'block-grid--16x9' ); ?>
			<div id="posts-container" <?php wayfarer_posts_class( $classes ); ?>>

				<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>

					<?php get_template_part( 'templates/parts/content-page', 'grid' ); ?>

				<?php endwhile; ?>

				<?php wp_reset_postdata(); ?>

			</div>

		<?php else : ?>

			<?php wayfarer_page_type_notice(); ?>

		<?php endif; ?>

		<?php do_action( 'wayfarer_main_bottom' ); ?>

	</main>

	<?php do_action( 'wayfarer_primary_bottom' ); ?>

</div>

<?php
get_footer();

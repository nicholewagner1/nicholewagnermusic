<?php
/*
Template Name: download Page
*/

get_header();
?>


	<?php do_action( 'wayfarer_primary_top' ); ?>

<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main" itemprop="mainContentOfPage">

		<?php do_action( 'wayfarer_main_top' ); ?>

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'templates/parts/content', wayfarer_post_template_name() ); ?>

		<?php endwhile; ?>
				
			<?php get_template_part( 'download'); ?>


	</main>
		</div>

		<?php do_action( 'wayfarer_main_bottom' ); ?>



	<?php do_action( 'wayfarer_primary_bottom' ); ?>


<?php
get_sidebar( 'page' );

get_footer();

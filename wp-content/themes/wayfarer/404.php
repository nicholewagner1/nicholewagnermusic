<?php
/**
 * The template for displaying 404 (Not Found) pages.
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

		<section class="not-found">
			<header class="page-header">
				<h1 class="page-title"><?php esc_html_e( '404 Error', 'wayfarer' ); ?></h1>
			</header>

			<div class="page-content">
				<p>
					<?php esc_html_e( 'It looks like nothing was found at this location. Maybe try a search?', 'wayfarer' ); ?>
				</p>

				<?php get_search_form(); ?>
			</div>
		</section>

		<?php do_action( 'wayfarer_main_bottom' ); ?>

	</main>

	<?php do_action( 'wayfarer_primary_bottom' ); ?>

</div>

<?php
get_footer();

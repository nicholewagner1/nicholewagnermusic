<?php
/**
 * The template used for displaying content.
 *
 * @package Wayfarer
 * @since 1.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> <?php wayfarer_post_schema(); ?>>

	<?php do_action( 'wayfarer_entry_top' ); ?>

	<header class="entry-header">
		<?php do_action( 'wayfarer_entry_header_top' ); ?>
		<?php wayfarer_entry_title(); ?>
		<div class="entry-meta">
			<?php wayfarer_entry_date(); ?>
		</div>
		<?php do_action( 'wayfarer_entry_header_bottom' ); ?>
	</header>

	<div class="entry-content" itemprop="articleBody">
		<?php do_action( 'wayfarer_entry_content_top' ); ?>
		<?php the_content(); ?>
		<?php wayfarer_page_links(); ?>
		<?php do_action( 'wayfarer_entry_content_bottom' ); ?>
	</div>

	<footer class="entry-footer">
		<?php do_action( 'wayfarer_entry_footer_top' ); ?>
		<?php wayfarer_entry_terms(); ?>
		<?php do_action( 'wayfarer_entry_footer_bottom' ); ?>
	</footer>

	<?php do_action( 'wayfarer_entry_bottom' ); ?>

</article>

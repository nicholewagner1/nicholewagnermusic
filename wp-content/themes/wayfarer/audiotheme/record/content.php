<?php
/**
 * The template used for displaying individual records.
 *
 * @package Wayfarer
 * @since 1.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php do_action( 'wayfarer_entry_top' ); ?>

	<header class="entry-header">
		<?php do_action( 'wayfarer_entry_header_top' ); ?>
		<?php the_title( '<h1 class="entry-title screen-reader-text" itemprop="name">', '</h1>' ); ?>
		<?php do_action( 'wayfarer_entry_header_bottom' ); ?>
	</header>

	<div class="entry-content-wrapper">
		<?php get_template_part( 'audiotheme/record/tracklist' ); ?>

		<div class="entry-content" itemprop="description">
			<?php do_action( 'wayfarer_entry_content_top' ); ?>
			<?php the_content(); ?>
			<?php do_action( 'wayfarer_entry_content_bottom' ); ?>
		</div>
	</div>

</article>

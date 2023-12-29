<?php
/**
 * The template used for displaying search content.
 *
 * @package Wayfarer
 * @since 1.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php do_action( 'wayfarer_entry_top' ); ?>

	<header class="entry-header">
		<?php do_action( 'wayfarer_entry_header_top' ); ?>
		<?php wayfarer_entry_title(); ?>
		<?php do_action( 'wayfarer_entry_header_bottom' ); ?>
	</header>

	<div class="entry-content" itemprop="text">
		<?php do_action( 'wayfarer_entry_content_top' ); ?>
		<?php the_content(); ?>
		<?php do_action( 'wayfarer_entry_content_bottom' ); ?>
	</div>

	<?php do_action( 'wayfarer_entry_bottom' ); ?>

</article>

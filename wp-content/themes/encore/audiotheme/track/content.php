<?php
/**
 * The template used for displaying individual tracks.
 *
 * @package Encore
 * @since 1.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title" itemprop="name">', '</h1>' ); ?>

		<div class="entry-meta">
			<?php get_template_part( 'audiotheme/track/meta' ); ?>
			<?php get_template_part( 'audiotheme/track/meta-links' ); ?>
		</div>
	</header>

	<?php get_template_part( 'audiotheme/track/artwork' ); ?>

	<?php get_template_part( 'audiotheme/track/tracklist' ); ?>

	<div class="entry-content" itemprop="description">
		<?php do_action( 'encore_entry_content_top' ); ?>
		<?php the_content(); ?>
		<?php do_action( 'encore_entry_content_bottom' ); ?>
	</div>
</article>

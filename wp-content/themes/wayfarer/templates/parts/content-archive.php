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
		<?php wayfarer_entry_date(); ?>
		<?php do_action( 'wayfarer_entry_header_bottom' ); ?>
	</header>

	<div class="entry-content" itemprop="articleBody">
		<?php do_action( 'wayfarer_entry_content_top' ); ?>
		<?php
		the_content( sprintf(
			/* translators: 1: Name of current post */
			wp_kses( __( 'Continue reading<span class="screen-reader-text"> "%1$s"</span>', 'wayfarer' ), array( 'span' => array( 'class' => array() ) ) ),
			get_the_title()
		) );
		?>
		<?php do_action( 'wayfarer_entry_content_bottom' ); ?>
	</div>

	<footer class="entry-footer">
		<?php do_action( 'wayfarer_entry_footer_top' ); ?>
		<div class="screen-reader-text">
			<?php wayfarer_entry_author(); ?>
		</div>
		<?php do_action( 'wayfarer_entry_footer_bottom' ); ?>
	</footer>

	<?php do_action( 'wayfarer_entry_bottom' ); ?>

</article>

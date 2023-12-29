<?php
/**
 * The template for displaying comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package Wayfarer
 * @since 1.0.0
 */

// If the current post is protected by a password and the visitor has not yet
// entered the password, return early without loading the comments.
if ( post_password_required() ) {
	return;
}

// Don't show comments if commenting is closed and the current post doesn't
// have any comments associated with it.
if ( ! comments_open() && '0' === get_comments_number() ) {
	return;
}

$comments_by_type = $wp_query->comments_by_type;
$comments_count   = count( $comments_by_type['comment'] );

do_action( 'wayfarer_comments_before' );
?>

<section id="comments" class="comments-area">

	<?php do_action( 'wayfarer_comments_top' ); ?>

	<?php the_comments_navigation(); ?>

	<div class="comments-area-inside">

		<?php if ( have_comments() ) : ?>

			<?php if ( $comments_count > 0 ) : ?>
				<header class="comments-header">
					<h2 class="comments-title title-divider">
						<?php
						echo esc_html( sprintf(
							/* translators: 1: Comments count */
							_n( '%1$s Comment', '%1$s Comments', $comments_count, 'wayfarer' ),
							number_format_i18n( $comments_count )
						) );
						?>
					</h2>
				</header>
			<?php endif; ?>

			<ol class="comment-list<?php if ( get_option( 'show_avatars' ) ) { echo ' show-avatars'; } ?>">
				<?php
				wp_list_comments( array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 60,
				) );
				?>
			</ol>

		<?php endif; ?>

		<?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>

			<footer class="comments-footer">
				<p><?php esc_html_e( 'Comments are closed.', 'wayfarer' ); ?></p>
			</footer>

		<?php endif; ?>

		<?php comment_form(); ?>

	</div>

	<?php do_action( 'wayfarer_comments_bottom' ); ?>

</section>

<?php
do_action( 'wayfarer_comments_after' );

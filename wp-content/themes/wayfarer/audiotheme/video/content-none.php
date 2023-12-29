<?php
/**
 * The template for displaying a message when there aren't any videos.
 *
 * @package Wayfarer
 * @since 1.0.0
 */

?>

<section class="no-results not-found">

	<header class="page-header">
		<h1 class="page-title"><?php esc_html_e( 'Nothing Found', 'wayfarer' ); ?></h1>
		<?php wayfarer_post_type_navigation( 'audiotheme_video' ); ?>
	</header>

	<div class="page-content">
		<?php if ( current_user_can( 'publish_posts' ) ) : ?>

			<p>
				<?php
				echo wayfarer_allowed_tags( sprintf(
					/* translators: 1: Admin new post link */
					_x( 'Ready to publish your first video? <a href="%1$s">Get started here</a>.', 'add post type link', 'wayfarer' ),
					esc_url( add_query_arg( 'post_type', get_post_type_object( 'audiotheme_video' )->name, admin_url( 'post-new.php' ) ) )
				) );
				?>
			</p>

		<?php else : ?>

			<p>
				<?php esc_html_e( "There currently aren't any videos available.", 'wayfarer' ); ?>
			</p>

		<?php endif; ?>
	</div>

</section>

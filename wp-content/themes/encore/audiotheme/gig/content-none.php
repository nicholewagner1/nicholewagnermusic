<?php
/**
 * The template for displaying a message when there aren't any upcoming gigs.
 *
 * @package Encore
 * @since 1.0.0
 */

$post_type        = 'audiotheme_gig';
$post_type_object = get_post_type_object( $post_type );
$archive_id       = get_audiotheme_post_type_archive( $post_type );
$recent_gigs      = encore_audiotheme_recent_gigs_query();
?>

<section class="content-box no-results not-found">
	<header class="page-header">
		<h1 class="page-title"><?php echo esc_html( get_post( $archive_id )->post_title ); ?></h1>
	</header>

	<div class="page-content">
		<?php if ( current_user_can( 'publish_posts' ) ) : ?>
			<p>
				<?php
				echo encore_allowed_tags( sprintf( _x( 'Ready to publish your next gig? <a href="%1$s">Get started here</a>.', 'add post type link', 'encore' ),
					esc_url( add_query_arg( 'post_type', $post_type_object->name, admin_url( 'post-new.php' ) ) )
				) );
				?>
			</p>
		<?php else : ?>
			<p>
				<?php esc_html_e( "There currently aren't any upcoming shows. Check back soon!", 'encore' ); ?>
			</p>
		<?php endif; ?>
	</div>

	<?php if ( $recent_gigs->have_posts() ) : ?>
		<div id="gigs" class="gig-list vcalendar">
			<?php while ( $recent_gigs->have_posts() ) : $recent_gigs->the_post(); ?>
				<?php get_template_part( 'audiotheme/gig/card' ); ?>
			<?php endwhile; ?>
		</div>
	<?php endif; ?>
</section>

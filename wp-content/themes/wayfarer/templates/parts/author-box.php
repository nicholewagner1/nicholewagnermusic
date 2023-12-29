<?php
/**
 * Template part used to display the author box on posts.
 *
 * Loaded via wayfarer_author_box() template tag.
 *
 * @package Wayfarer
 * @since 1.0.0
 */

$user_url = get_the_author_meta( 'user_url' );
?>

<aside class="author-box" itemprop="author" itemscope itemtype="https://schema.org/Person">

	<?php do_action( 'wayfarer_author_box_top' ); ?>

	<h2 class="author-box-title screen-reader-text">
		<?php esc_html_e( 'About The Author', 'wayfarer' ); ?>
	</h2>

	<figure class="author-box-image">
		<?php
		echo get_avatar( get_the_author_meta( 'user_email' ), 120, '', esc_html( get_the_author() ), array( 'extra_attr' => 'itemprop="image"' ) );
		?>
	</figure>

	<header class="author-box-header">
		<h3 class="author-box-name">
			<?php echo wayfarer_allowed_tags( wayfarer_get_entry_author_link() ); ?>
		</h3>

		<?php if ( $user_url ) : ?>
			<a class="author-box-user-url" href="<?php echo esc_url( $user_url ); ?>"><?php echo esc_html( wayfarer_get_clean_url( $user_url ) ); ?></a>
		<?php endif; ?>
	</header>

	<div class="author-box-content" itemprop="description">
		<?php echo wayfarer_allowed_tags( wpautop( get_the_author_meta( 'description' ) ) ); ?>
	</div>

	<?php do_action( 'wayfarer_author_box_bottom' ); ?>

</aside>

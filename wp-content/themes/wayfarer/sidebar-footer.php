<?php
/**
 * The template containing the footer widget area.
 *
 * @package Wayfarer
 * @since 1.0.0
 */

if ( ! is_active_sidebar( 'sidebar-2' ) ) {
	return;
}
?>

<aside id="footer-widgets" class="footer-widgets" role="complementary">
	<div class="footer-widgets-inside">

		<?php do_action( 'wayfarer_footer_widgets_top' ); ?>

		<div class="widget-area block-grid block-grid--gutters block-grid-<?php echo absint( get_theme_mod( 'site_footer_layout', 3 ) ); ?>">

			<?php dynamic_sidebar( 'sidebar-2' ); ?>

		</div>

		<?php do_action( 'wayfarer_footer_widgets_bottom' ); ?>

	</div>
</aside>

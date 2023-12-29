<?php
/**
 * The template for the sidebar containing the main widget area.
 *
 * @package Wayfarer
 * @since 1.0.0
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>

<aside id="secondary" class="sidebar-area" role="complementary" itemscope itemtype="http://schema.org/WPSideBar">

	<?php do_action( 'wayfarer_sidebar_top' ); ?>

	<div class="widget-area block-grid block-grid--gutters block-grid-<?php echo absint( wayfarer_get_mapped_sidebar_widget_count( 'sidebar-1' ) ); ?>">

		<?php dynamic_sidebar( 'sidebar-1' ); ?>

	</div>

	<?php do_action( 'wayfarer_sidebar_bottom' ); ?>

</aside>


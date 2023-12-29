<?php
/**
 * The template used for displaying the site footer.
 *
 * @package Wayfarer
 * @since 1.0.0
 */

?>

<footer id="footer" class="site-footer" role="contentinfo" itemscope itemtype="https://schema.org/WPFooter">

	<?php do_action( 'wayfarer_footer_top' ); ?>

	<?php get_sidebar( 'footer' ); ?>

	<?php wayfarer_credits(); ?>

	<?php do_action( 'wayfarer_footer_bottom' ); ?>

</footer>

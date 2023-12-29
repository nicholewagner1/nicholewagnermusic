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

<div class="footercopyright">&copy;2014-<?php echo date('Y');?> Nichole Wagner </div>
	<?php do_action( 'wayfarer_footer_bottom' ); ?>
	<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-1LG881PQ5J"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-1LG881PQ5J');
</script>

</footer>

<?php
/**
 * The template for displaying the site footer.
 *
 * @package Encore
 * @since 1.0.0
 */

?>


			<?php do_action( 'encore_main_after' ); ?>

		</div><!-- #content -->

		<?php get_sidebar(); ?>

		<?php do_action( 'encore_footer_before' ); ?>

		<?php get_template_part( 'templates/parts/site-footer' ); ?>

		<?php do_action( 'encore_footer_after' ); ?>

	</div><!-- #page -->

	<?php do_action( 'encore_after' ); ?>

	<?php wp_footer(); ?>

</body>
</html>

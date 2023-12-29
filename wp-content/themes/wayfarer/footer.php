<?php
/**
 * The template for displaying the site footer.
 *
 * @package Wayfarer
 * @since 1.0.0
 */

?>


				<?php do_action( 'wayfarer_main_after' ); ?>
			</div><!-- .site-content-inside -->
			<?php do_action( 'wayfarer_content_bottom' ); ?>
		</div><!-- #content -->

		<?php do_action( 'wayfarer_footer_before' ); ?>

		<?php get_template_part( 'templates/parts/site-footer' ); ?>

		<?php do_action( 'wayfarer_footer_after' ); ?>

	</div><!-- #page -->

	<?php do_action( 'wayfarer_after' ); ?>

	<?php wp_footer(); ?>

</body>
</html>

<?php
/**
 * The template used for displaying a gig map on single gig pages.
 *
 * @package Encore
 * @since 1.0.0
 */

if ( audiotheme_gig_has_venue() ) :
?>

	<figure class="venue-map">
		<?php
		echo get_audiotheme_google_map_embed(
			array(
				'width'  => '100%',
				'height' => 320,
			),
			get_audiotheme_gig()->venue->ID
		);
		?>
	</figure>

<?php
endif;

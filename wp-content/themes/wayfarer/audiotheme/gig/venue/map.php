<?php
/**
 * The template used for displaying a gig map on single gig pages.
 *
 * @package Wayfarer
 * @since 1.0.0
 */

?>

<figure class="venue-map">
	<?php
	echo get_audiotheme_google_map_embed(
		array(
			'width'  => '100%',
			'height' => 300,
		),
		get_audiotheme_gig()->venue->ID
	);
	?>
</figure>

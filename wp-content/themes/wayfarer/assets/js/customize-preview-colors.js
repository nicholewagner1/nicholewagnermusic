/*global wp:false */

/**
 * Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Customizer preview reload changes asynchronously.
 */

(function( $, wp ) {
	'use strict';

	var api = wp.customize,
		$style = $( '#wayfarer-custom-css' );

	if ( ! $style.length ) {
		$style = $( 'head' ).append( '<style type="text/css" id="wayfarer-custom-css"></style>' )
		                    .find( '#wayfarer-custom-css' );
	}

	// Color Scheme CSS.
	api.bind( 'preview-ready', function() {
		api.preview.bind( 'update-colors-css', function( css ) {
			$style.html( css );
		} );
	} );

})( jQuery, wp );

/*global _:false, Color:false, wp:false */
/*jshint newcap:false */

(function( $, _, wp, undefined ) {
	'use strict';

	var api = wp.customize,
		cssTemplate = wp.template( 'wayfarer-customizer-styles' ),
		colorSettings = [
			'wayfarer_accent_color'
		];

	function updateCSS() {
		var css,
			accentColor = Color( api( 'wayfarer_accent_color' )() ),
			backgroundColor = Color( '#ffffff' ),
			contrastColor = Color( accentColor ).getReadableContrastingColor( accentColor );

		css = cssTemplate({
			accentColor: accentColor.toString(),
			contrastColor: contrastColor.toString(),
			readableColor: accentColor.getReadableContrastingColor( backgroundColor ),
			timeRailColor: contrastColor.toCSS( 'rgba', 0.7 )
		});

		api( 'wayfarer_contrast_color' ).set( contrastColor.toString() );

		api.previewer.send( 'update-colors-css', css );
	}

	// Update CSS when colors are changed.
	_.each( colorSettings, function( settingKey ) {
		api( settingKey, function( setting ) {
			setting.bind(function( value ) {
				updateCSS();
			});
		});
	});

})( jQuery, _, wp );

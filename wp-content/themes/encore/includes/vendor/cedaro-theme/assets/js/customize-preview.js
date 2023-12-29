(function( $, api ) {
	'use strict';

	function toggleHeaderText( value ) {
		var $headerText = $( '.site-title, .site-description' );

		if ( ! value ) {
			$headerText.css({
				'position': 'absolute',
				'clip': 'rect(1px 1px 1px 1px)'
			});
		} else {
			$headerText.css({
				'position': 'static',
				'clip': 'auto'
			});
		}
	}

	api( 'header_text', function( setting ) {
		toggleHeaderText( setting() );
		setting.bind( toggleHeaderText );
	} );

})( jQuery, wp.customize );

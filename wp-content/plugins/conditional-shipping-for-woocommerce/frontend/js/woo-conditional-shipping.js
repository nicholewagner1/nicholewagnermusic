jQuery(document).ready(function($) {
	/**
	 * Notices in checkout
	 */
	$( document.body ).on( 'updated_checkout', function() {
		var noticesEl = $( '#wcs-notices-pending' );

		if ( noticesEl.length > 0 ) {
			// Clear existing notices
			$( '#wcs-notices' ).remove();

			var shippingRow = $( 'tr.woocommerce-shipping-totals td:eq(0)' );
			
			if ( shippingRow.length > 0 ) {
				shippingRow.append( noticesEl );
				noticesEl.css( 'display', 'block' ).attr( 'id', 'wcs-notices' );
			}
		}
	} );

	/**
	 * Notices in cart
	 */
	 $( document.body ).on( 'wcs_updated_cart', function() {
		var noticesEl = $( '#wcs-notices-pending' );

		if ( noticesEl.length > 0 ) {
			// Clear existing notices
			$( '#wcs-notices' ).remove();

			var shippingRow = $( 'tr.woocommerce-shipping-totals td:eq(0)' );
			
			if ( shippingRow.length > 0 ) {
				shippingRow.append( noticesEl );
				noticesEl.css( 'display', 'block' ).attr( 'id', 'wcs-notices' );
			}
		}
	} );
	$( document.body ).trigger( 'wcs_updated_cart' );
	$( document.body ).on( 'updated_cart_totals', function() {
		$( document.body ).trigger( 'wcs_updated_cart' );
	} );


	var wcsDebug = {
		init: function() {
			this.toggleDebug();
			this.setInitial();

			var self = this;
			$( document.body ).on( 'updated_checkout', function( data ) {
				self.setInitial();
			} );
		},

		/**
		 * Toggle debug on click
		 */
		toggleDebug: function() {
			var self = this;

			$( document.body ).on( 'click', '#wcs-debug-header', function( e ) {
				if ( $( '#wcs-debug-contents' ).is( ':visible' ) ) {
					$( '#wcs-debug' ).toggleClass( 'closed', true );
				} else {
					$( '#wcs-debug' ).toggleClass( 'closed', false );
				}

				$( '#wcs-debug-contents' ).slideToggle( 200, function() {
					self.saveStatus();
				} );
			} );
		},

		/**
		 * Save debug open / closed status to cookies
		 */
		saveStatus: function() {
			if ( typeof Cookies == 'undefined' ) {
				return;
			}

			Cookies.set( 'wcs_debug_status', $( '#wcs-debug-contents' ).is( ':visible' ) );
		},

		/**
		 * Set initial stage for debug
		 */
		setInitial: function() {
			if ( typeof Cookies == 'undefined' ) {
				return;
			}

			var status = Cookies.get( 'wcs_debug_status' );

			$( '#wcs-debug-contents' ).toggle( status === 'true' );
			$( '#wcs-debug' ).toggleClass( 'closed', $( '#wcs-debug-contents' ).is( ':hidden' ) );
		}
	}

	wcsDebug.init();
});

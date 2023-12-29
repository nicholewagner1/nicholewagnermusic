/*global _:false, _encoreSettings:false, AudiothemeTracks:false */

window.cue = window.cue || {};
window.encore = window.encore || {};

(function( window, $, undefined ) {
	'use strict';

	var $window        = $( window ),
		$html          = $( 'html' ),
		$body          = $( 'body' ),
		$sidebarHeader = $( '.offscreen-sidebar--header' ),
		$socialMenu    = $( '.social-navigation .menu' ),
		$socialItems   = $socialMenu.find( 'li' ),
		$socialToggle  = $( '.social-navigation-toggle' ),
		l10n           = _encoreSettings.l10n,
		cue            = window.cue,
		encore         = window.encore;

	// Localize jquery.cue.js.
	cue.l10n = $.extend( cue.l10n, l10n );

	$.extend( encore, {
		config: {
			player: {
				classPrefix: 'mejs-',
				cueDisableControlsSizing: true,
				cueId: 'encore-player',
				cuePlaylistToggle: function( $tracklist ) {
					$tracklist.slideToggle( 200 );
				},
				cueSkin: 'encore-playbar',
				enableAutosize: false,
				features: [
					'cuehistory',
					'cueartwork',
					'playpause',
					'cuecurrentdetails',
					'cueplaylist',
					'cueplaylisttoggle'
				],
				pluginPath: _encoreSettings.mejs.pluginPath,
				setDimensions: false,
				timeFormat: 'm:ss'
			},
			tracklist: {
				classPrefix: 'mejs-',
				cueDisableControlsSizing: true,
				cueSkin: 'encore-tracklist',
				cueSelectors: {
					playlist: '.tracklist-area',
					track: '.track',
					trackCurrentTime: '.track-current-time',
					trackDuration: '.track-duration'
				},
				enableAutosize: false,
				features: [ 'cueplaylist' ],
				pluginPath: _encoreSettings.mejs.pluginPath,
				setDimensions: false,
				timeFormat: 'm:ss'
			}
		},

		init: function() {
			$body.addClass( 'ontouchstart' in window || 'onmsgesturechange' in window ? 'touch' : 'no-touch' );

			// Open external links in a new window.
			$( '.js-maybe-external' ).each(function() {
				if ( this.hostname && this.hostname !== window.location.hostname ) {
					$( this ).attr( 'target', '_blank' );
				}
			});

			// Open new windows for links with a class of '.js-popup'.
			$( '.js-popup' ).on( 'click', function( e ) {
				var $this       = $( this ),
					popupId     = $this.data( 'popup-id' ) || 'popup',
					popupUrl    = $this.data( 'popup-url' ) || $this.attr( 'href' ),
					popupWidth  = $this.data( 'popup-width' ) || 550,
					popupHeight = $this.data( 'popup-height' ) || 260;

				e.preventDefault();

				window.open( popupUrl, popupId, [
					'width=' + popupWidth,
					'height=' + popupHeight,
					'directories=no',
					'location=no',
					'menubar=no',
					'scrollbars=no',
					'status=no',
					'toolbar=no'
				].join( ',' ) );
			});

			// Move the footer widgets depending on the screen width.
			$( '.widget-area .widget' ).appendAround({
				set: $( '.widget-area' )
			});

			// Move the social navigation menu depending on the screen width.
			$( '.social-navigation .menu' ).appendAround({
				set: $( '.social-navigation' )
			});

			_.bindAll( this, 'onResize' );
			$window.on( 'load orientationchange resize', _.throttle( this.onResize, 100 ) );
		},

		/**
		 * Set up the main navigation.
		 */
		setupNavigation: function() {
			var $navigation = $( '.site-navigation' );

			$navigation.find( '.menu' ).cedaroNavMenu({
				breakpoint: 960,
				submenuToggleInsert: 'append'
			});
		},

		/**
		 * Initialize the theme player and playlist.
		 */
		setupPlayer: function() {
			if ( 'function' === typeof $.fn.cuePlaylist ) {
				$( '.encore-player' ).cuePlaylist( this.config.player );
			}
		},

		/**
		 * Set up the offscreen sidebar.
		 */
		setupSidebar: function() {
			var $toggle = $( '.offscreen-sidebar-toggle' ),
				$siteHeader = $( '#masthead' ),
				$siteOverlay = $siteHeader.append( '<div class="site-overlay" />' ).find( '.site-overlay' );

			function toggleSidebar( e ) {
				e.preventDefault();

				$body.toggleClass( 'offscreen-sidebar-is-open' );

				if ( $body.hasClass( 'offscreen-sidebar-is-open' ) ) {
					$html.css( 'overflow', 'hidden' );
				} else {
					$html.css( 'overflow', 'auto' );
				}
			}

			$toggle.on( 'click', toggleSidebar );
			$siteOverlay.on( 'click', toggleSidebar );
		},

		/**
		 * Set up the social navigation sidebar.
		 */
		setupSocialNavigation: function() {
			$socialToggle.on( 'click', function() {
				$body.toggleClass( 'social-navigation-is-open' );
			});
		},

		setupTracklist: function() {
			var $tracklist = $( '.tracklist-area' );

			if ( $tracklist.length && 'function' === typeof $.fn.cuePlaylist ) {
				$tracklist.cuePlaylist( $.extend( this.config.tracklist, {
					cuePlaylistTracks: AudiothemeTracks.record
				}));
			}
		},

		/**
		 * Set up videos.
		 *
		 * - Makes videos responsive.
		 * - Moves videos embedded in page content to an '.entry-video'
		 *   container. Used primarily with the WPCOM single video templates.
		 */
		setupVideos: function() {
			if ( 'function' === typeof $.fn.fitVids ) {
				$( '.hentry' ).fitVids();
			}

			$( 'body.page' ).find( '.single-video' ).find( '.jetpack-video-wrapper' ).first().appendTo( '.entry-video' );
		},

		onResize: function() {
			var itemCount, itemWidth,
				vw = this.viewportWidth();

			$body.css( 'padding-top', function() {
				return ( 960 <= vw ) ? $sidebarHeader.height() : '';
			});

			if ( 960 <= vw && ! $body.hasClass( '.has-widget-area' ) ) {
				$body.removeClass( 'offscreen-sidebar-is-open' );
			}

			if ( 960 <= vw ) {
				// Toggle the social toggle button.
				itemCount = $socialItems.length;
				itemWidth = $socialItems.slice( 0, 1 ).width();
				$socialToggle.toggle( $socialMenu.width() < itemWidth * itemCount );
			}
		},

		isNavOffscreen: function() {
			return 960 > this.viewportWidth();
		},

		viewportWidth: function() {
			return window.innerWidth || $window.width();
		}
	});

	// Document ready.
	jQuery(function() {
		encore.init();
		encore.setupNavigation();
		encore.setupPlayer();
		encore.setupSidebar();
		encore.setupSocialNavigation();
		encore.setupTracklist();
		encore.setupVideos();
	});

})( this, jQuery );

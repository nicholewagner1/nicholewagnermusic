/*global _wayfarerSettings:false, AudiothemeTracks:false */

window.cue = window.cue || {};
window.wayfarer = window.wayfarer || {};

(function( window, $, undefined ) {
	'use strict';

	var $window   = $( window ),
		$document = $( document ),
		$body     = $( 'body' ),
		$html     = $( 'html' ),
		l10n      = _wayfarerSettings.l10n,
		cue       = window.cue,
		wayfarer  = window.wayfarer;

	// Localize jquery.cue.js.
	cue.l10n = $.extend( cue.l10n, l10n );

	function throttle ( callback, wait ) {
		var doCallback = true;

		return function() {
			if ( doCallback ) {
				callback.call();
				doCallback = false;
				setTimeout(function() {
					doCallback = true;
				}, wait );
			}
		};
	}

	$.extend( wayfarer, {
		config: {
			player: {
				classPrefix: 'mejs-',
				cueDisableControlsSizing: true,
				cueId: 'wayfarer-player',
				cuePlaylistToggle: function( $tracklist ) {
					$tracklist.slideToggle( 200 );
				},
				cueSelectors: {
					playlist: '.wayfarer-player'
				},
				cueSkin: 'wayfarer-playbar',
				enableAutosize: false,
				features: [
					'cuehistory',
					'cueartwork',
					'cuecurrentdetails',
					'progress',
					'cueprevioustrack',
					'playpause',
					'cuenexttrack',
					'cueplaylist',
					'cueplaylisttoggle'

					// 'current',
					// 'duration',
				],
				pluginPath: _wayfarerSettings.mejs.pluginPath,
				setDimensions: false,
				timeFormat: 'm:ss'
			},
			tracklist: {
				classPrefix: 'mejs-',
				cueSkin: 'wayfarer-tracklist',
				cueSelectors: {
					playlist: '.tracklist-area',
					track: '.track',
					trackCurrentTime: '.track-current-time',
					trackDuration: '.track-duration'
				},
				enableAutosize: false,
				features: [ 'cueplaylist' ],
				pluginPath: _wayfarerSettings.mejs.pluginPath,
				setDimensions: false,
				timeFormat: 'm:ss'
			}
		},

		initialize: function() {
			wayfarer.setupCSSFilters();
			wayfarer.setupExternalLinks();
			wayfarer.setupHeader();
			wayfarer.setupNavigation();
			wayfarer.setupPlayer();
			wayfarer.setupSidebar();
			wayfarer.setupTracklist();
			wayfarer.setupVideos();
		},

		/**
		 * Add body class if browser does not support CSS filters.
		 * IE10+ is the primary issue.
		 *
		 * The method for checking CSS Filter support was pulled from Modernizer.
		 * https://github.com/Modernizr/Modernizr/pull/615/files
		 */
		setupCSSFilters: function() {
			var el       = document.createElement( 'a' ),
				prefixes = ' -webkit- -moz- -o- -ms- '.split(' ');

			el.style.cssText = prefixes.join( 'filter:blur(2px); ' );

			if ( !! el.style.length && ( ( document.documentMode === undefined || document.documentMode > 9 ) ) ) {
				$html.removeClass( 'no-cssfilters' );
			}
		},

		/**
		 * Set up external links to open in a new window.
		 */
		setupExternalLinks: function() {
			// Open external links in a new window.
			$( '.js-maybe-external' ).each(function() {
				if ( this.hostname && this.hostname !== window.location.hostname ) {
					$( this ).attr( 'target', '_blank' );
				}
			});
		},

		/**
		 * Set up the header.
		 *
		 * Repositions the element that displays before the hero so that it can
		 * overlay on the hero.
		 */
		setupHeader: function() {
			var $overlay, throttledRepositionOverlay,
				$items = $( '.site-header' ).children(),
				$hero = $items.filter( '.hero' ),
				position = parseInt( $hero.css( 'order' ), 10 );

			if ( ! $body.hasClass( 'has-hero-overlay' ) ) {
				return;
			}

			// Find the element before the hero.
			$overlay = $items.filter(function() {
				var $this = $( this );
				return parseInt( $this.css( 'order' ), 10 ) === ( position - 1 ) && ! $this.hasClass( 'mobile-navigation' );
			});

			function repositionOverlay() {
				if ( wayfarer.viewportWidth() < 1024 && $overlay.hasClass( 'site-navigation-panel' ) ) {
					// Remove the top position on smaller screens if the
					//  navigation panel is the overlaid element.
					$overlay.css( 'top', '' );
				} else {
					$overlay.css( 'top', $hero.position().top ).removeClass( 'is-open' );
				}
			}

			throttledRepositionOverlay = throttle( repositionOverlay, 250 );
			throttledRepositionOverlay();
			$( window ).on( 'load orientationchange resize', throttledRepositionOverlay );
		},

		/**
		 * Set up the main navigation.
		 */
		setupNavigation: function() {
			var $navigation       = $( '.site-navigation' ),
				$navigationToggle = $( '.site-navigation-toggle' ),
				$player           = $( '.wayfarer-player' ),
				$playerToggle     = $( '.wayfarer-player-toggle' );

			// Toggle the main menu.
			$navigationToggle.on( 'click', function() {
				var $this = $( this );
				$this.toggleClass( 'is-active' );
				$navigation.parent().toggleClass( 'is-open' );
				$player.removeClass( 'is-open' );
				$playerToggle.removeClass( 'is-active' );
			});

			// Toggle the player.
			$playerToggle.on( 'click', function() {
				var $this = $( this );
				$this.toggleClass( 'is-active' );
				$player.toggleClass( 'is-open' );
				$navigation.parent().removeClass( 'is-open' );
				$navigationToggle.removeClass( 'is-active' );
			});

			$navigation.find( '.menu' ).cedaroNavMenu({
				breakpoint: 1024,
				submenuToggleInsert: 'append'
			});
		},

		/**
		 * Initialize the theme player and playlist.
		 */
		setupPlayer: function() {
			var $player = $( '.wayfarer-player' );

			if ( 'function' === typeof $.fn.cuePlaylist ) {
				$player.cuePlaylist( this.config.player );
			}
		},

		/**
		 * Move the sidebar widgets depending on the screen width.
		 */
		setupSidebar: function() {
			$( '.sidebar-area .widget-area' ).appendAround({
				set: $( '.sidebar-area' )
			});
		},

		/**
		 * Setup player tracklist.
		 */
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
		 * - Add a "no-content" class for video pages that only have a URL in
		 *   the content area. The URL is moved via JS and the content is empty.
		 */
		setupVideos: function() {
			if ( 'function' === typeof $.fn.fitVids ) {
				$( '.entry, .responsive-video' ).fitVids();
			}

			$( 'body.single-video' ).find( '.entry' ).find( '.jetpack-video-wrapper' ).first().appendTo( '.entry-video' );

			if ( $body.hasClass( 'single-video' ) ) {
				var $entryContent = $( '.entry-content' );

				if( ! $.trim( $entryContent.html() ).length ) {
					$body.addClass( 'no-content' );
				}
			}
		},

		/**
		 * Update the scrollbar width CSS variable.
		 *
		 * This doesn't modify the scrollbar at all, but sets a CSS variable
		 * with the width of the scrollbar so it can be accounted for in
		 * full-width layouts.
		 */
		updateScrollbarWidth: function() {
			var docEl = document.documentElement,
				prevWidth = window.getComputedStyle( docEl ).getPropertyValue( '--scrollbar-width' ),
				newWidth = window.innerWidth - document.body.clientWidth + 'px';

			if ( newWidth !== prevWidth ) {
				docEl.style.setProperty( '--scrollbar-width', newWidth );
			}
		},

		/**
		 * Retrieve the viewport width.
		 */
		viewportWidth: function() {
			return window.innerWidth || $window.width();
		}
	});

	$document.ready( wayfarer.initialize );
	$window.on( 'load orientationchange', wayfarer.updateScrollbarWidth );

	wayfarer.updateScrollbarWidth();

})( this, jQuery );

<?php
/**
 * Encore functions and definitions.
 *
 * Sets up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme (see https://codex.wordpress.org/Theme_Development
 * and https://codex.wordpress.org/Child_Themes), you can override certain
 * functions (those wrapped in a function_exists() call) by defining them first
 * in your child theme's functions.php file. The child theme's functions.php
 * file is included before the parent theme's file, so the child theme
 * functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * see https://codex.wordpress.org/Plugin_API
 *
 * @package Encore
 * @since 1.0.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 800;
}

/**
 * Adjust the content width for full width pages.
 *
 * @since 1.0.0
 */
function encore_content_width() {
	global $content_width;

	if ( is_front_page() && is_page() ) {
		$content_width = 880;
	}
}
add_action( 'template_redirect', 'encore_content_width' );

/**
 * Load helper functions and libraries.
 */
require get_template_directory() . '/includes/customizer.php';
require get_template_directory() . '/includes/hooks.php';
require get_template_directory() . '/includes/template-helpers.php';
require get_template_directory() . '/includes/template-tags.php';
require get_template_directory() . '/includes/vendor/cedaro-theme/autoload.php';
encore_theme()->load();

/**
 * Set up theme defaults and register support for various WordPress features.
 *
 * @since 1.0.0
 */
function encore_setup() {
	// Add support for translating strings in this theme.
	load_theme_textdomain( 'encore', get_template_directory() . '/languages' );

	// This theme styles the visual editor to resemble the theme style.
	add_editor_style( array(
		is_rtl() ? 'assets/css/editor-style-rtl.css' : 'assets/css/editor-style.css',
		encore_fonts_icon_url(),
	) );

	// Add support for default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Add support for the title tag.
	add_theme_support( 'title-tag' );

	// Add support for post thumbnails.
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'encore-block-grid-16x9', 425, 241, array( 'center', 'top' ) );
	add_image_size( 'encore-featured-image', 880, 500, true );
	add_image_size( 'encore-site-logo', 880, 640 );
	set_post_thumbnail_size( 425, 425, array( 'center', 'top' ) );

	// Add support for a logo.
	add_theme_support( 'site-logo', array(
		'size' => 'encore-site-logo',
	) );

	// Add support for Custom Background functionality.
	add_theme_support( 'custom-background', array(
		'default-color' => 'f2efea',
	) );

	// Add HTML5 markup for the comment forms, search forms and comment lists.
	add_theme_support( 'html5', array(
		'caption',
		'comment-form',
		'comment-list',
		'gallery',
		'search-form',
	) );

	// Add support for block styles.
	add_theme_support( 'wp-block-styles' );

	// Add support for full and wide alignment.
	add_theme_support( 'align-wide' );

	// Add support for responsive embedded content.
	add_theme_support( 'responsive-embeds' );

	// Register default nav menus.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'encore' ),
		'social'  => esc_html__( 'Social Links Menu', 'encore' ),
	) );

	// Register support for archive content settings.
	encore_theme()->archive_content->add_support();
}
add_action( 'after_setup_theme', 'encore_setup' );

/**
 * Register widget area.
 *
 * @since 1.0.0
 */
function encore_register_sidebars() {
	register_sidebar( array(
		'id'            => 'sidebar-1',
		'name'          => esc_html__( 'Widget Area', 'encore' ),
		'description'   => esc_html__( 'Widgets appear in an offscreen container on the left side of every page.', 'encore' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'encore_register_sidebars' );

/**
 * Enqueue scripts and styles.
 *
 * @since 1.0.0
 */
function encore_enqueue_assets() {
	// Add Themicons font, used in the main stylesheet.
	wp_enqueue_style( 'themicons', encore_fonts_icon_url(), array(), '2.3.1' );

	// Load main style sheet.
	wp_enqueue_style( 'encore-style', get_stylesheet_uri() );

	// Load RTL style sheet.
	wp_style_add_data( 'encore-style', 'rtl', 'replace' );

	// Load theme scripts.
	wp_enqueue_script(
		'wp-nav-menus',
		get_theme_file_uri( '/assets/js/vendor/wp-nav-menus.js' ),
		array(),
		'1.0.0',
		true
	);

	wp_localize_script( 'wp-nav-menus', '_cedaroNavMenuL10n', array(
		'collapseSubmenu' => esc_html__( 'Collapse submenu', 'encore' ),
		'expandSubmenu'   => esc_html__( 'Expand submenu', 'encore' ),
	) );

	wp_enqueue_script(
		'encore-plugins',
		get_template_directory_uri() . '/assets/js/plugins.js',
		array( 'jquery' ),
		'20150410',
		true
	);

	wp_enqueue_script(
		'encore',
		get_template_directory_uri() . '/assets/js/main.js',
		array( 'jquery', 'encore-plugins', 'underscore', 'wp-nav-menus' ),
		'20201210',
		true
	);

	// Localize the main theme script.
	wp_localize_script( 'encore', '_encoreSettings', array(
		'l10n' => array(
			'expand'         => '<span class="screen-reader-text">' . esc_html__( 'Expand', 'encore' ) . '</span>',
			'collapse'       => '<span class="screen-reader-text">' . esc_html__( 'Collapse', 'encore' ) . '</span>',
			'nextTrack'      => esc_html__( 'Next Track', 'encore' ),
			'previousTrack'  => esc_html__( 'Previous Track', 'encore' ),
			'togglePlaylist' => esc_html__( 'Toggle Playlist', 'encore' ),
		),
		'mejs' => array(
			'pluginPath' => includes_url( 'js/mediaelement/', 'relative' ),
		),
	) );

	// Load script to support comment threading when it's enabled.
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Register scripts and styles for enqueueing on demand.
	wp_register_style( 'encore-fonts', encore_fonts_url(), array(), null );

	// Register scripts for enqueueing on demand.
	wp_register_script(
		'jquery-cue',
		get_template_directory_uri() . '/assets/js/vendor/jquery.cue.js',
		array( 'jquery', 'mediaelement' ),
		'1.2.5',
		true
	);
}
add_action( 'wp_enqueue_scripts', 'encore_enqueue_assets' );

/**
 * Enqueue block editor assets.
 *
 * @since 2.0.0
 */
function encore_enqueue_block_editor_assets() {
	wp_enqueue_style(
		'encore-block-editor',
		get_theme_file_uri( 'assets/css/block-editor.css' ),
		array( 'encore-fonts', 'wp-edit-blocks' )
	);
}
add_action( 'enqueue_block_editor_assets', 'encore_enqueue_block_editor_assets' );

/**
 * Print offscreen background color styles.
 *
 * @since 1.0.0
 */
function encore_offscreen_navigation_style() {
	$color = get_background_color();

	if ( empty( $color ) && ! is_customize_preview() ) {
		$color = 'f2efea';
	}

	$css = <<<CSS
	.site-header .toggle-button,
	.site-header .toggle-button:focus,
	.site-header .toggle-button:hover {
		background-color: #{$color};
	}

	@media only screen and ( min-width: 960px ) {
		.offscreen-sidebar--header {
			background-color: #{$color};
		}
	}
CSS;

	wp_add_inline_style( 'encore-style', $css );
}
add_action( 'wp_enqueue_scripts', 'encore_offscreen_navigation_style' );

/**
 * JavaScript detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @since 1.0.0
 */
function encore_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'encore_javascript_detection', 0 );

/**
 * Configure MediaElement.js elements to aid styling.
 *
 * Extends the core _wpmejsSettings object to add a new feature via the
 * MediaElement.js plugin API.
 *
 * @since 1.0.0
 */
function encore_mejs_setup() {
	if ( ! wp_script_is( 'mediaelement', 'done' ) ) {
		return;
	}
	?>
	<script>
	(function() {
		var settings = window._wpmejsSettings || {};
		settings.features = settings.features || mejs.MepDefaults.features;
		settings.features.push( 'encoretheme' );

		MediaElementPlayer.prototype.buildencoretheme = function( player ) {
			var container = player.container[0] || player.container;

			if ( 'AUDIO' === player.node.nodeName ) {
				player.options.setDimensions = false;
				container.classList.add( 'encore-mejs-container' );
				container.style.height = '';
				container.style.width = '';
			}
		};
	})();
	</script>
	<?php
}
add_action( 'wp_print_footer_scripts', 'encore_mejs_setup' );

/**
 * Return the Google font stylesheet URL, if available.
 *
 * The default Google font usage is localized. For languages that use characters
 * not supported by the font, the font can be disabled.
 *
 * As of 1.1.0, this is only used on WordPress.com. It's still available and is
 * registered (but not enqueued) for backward compatibility. Custom fonts are
 * loaded on self-hosted installations by the Cedaro Theme library.
 *
 * @since 1.0.0
 *
 * @return string Font stylesheet or empty string if disabled.
 */
function encore_fonts_url() {
	$fonts_url = '';
	$fonts     = array();
	$subsets   = 'latin';

	/*
	 * translators: If there are characters in your language that are not
	 * supported by these fonts, translate this to 'off'.
	 * Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Cousine: on or off', 'encore' ) ) {
		$fonts[] = 'Cousine:400,700,400italic,700italic';
	}

	if ( 'off' !== _x( 'on', 'Rajdhani: on or off', 'encore' ) ) {
		$fonts[] = 'Rajdhani:500,600';
	}

	/*
	 * translators: To add a character subset specific to your language,
	 * translate this to 'latin-ext', 'cyrillic', 'greek', or 'vietnamese'.
	 * Do not translate into your own language.
	 */
	$subset = _x( 'no-subset', 'Add new subset (latin-ext)', 'encore' );

	if ( 'latin-ext' === $subset ) {
		$subsets .= ',latin-ext';
	} elseif ( 'cyrillic' === $subset ) {
		$subsets .= ',cyrillic,cyrillic-ext';
	} elseif ( 'greek' === $subset ) {
		$subsets .= ',greek,greek-ext';
	} elseif ( 'vietnamese' === $subset ) {
		$subsets .= ',vietnamese';
	}

	if ( $fonts ) {
		$query_args = array(
			'family' => urlencode( implode( '|', $fonts ) ),
			'subset' => urlencode( $subsets ),
		);

		$fonts_url = esc_url_raw( add_query_arg( $query_args, 'https://fonts.googleapis.com/css' ) );
	}

	return $fonts_url;
}

/**
 * Retrieve the icon font style sheet URL.
 *
 * @since 1.0.0
 *
 * @return string Font stylesheet.
 */
function encore_fonts_icon_url() {
	return get_template_directory_uri() . '/assets/css/themicons.css';
}

/**
 * Wrapper for accessing the Cedaro_Theme instance.
 *
 * @since 1.0.0
 *
 * @return Cedaro_Theme
 */
function encore_theme() {
	static $instance;

	if ( null === $instance ) {
		Cedaro_Theme_Autoloader::register();
		$instance = new Cedaro_Theme( array( 'prefix' => 'encore' ) );
	}

	return $instance;
}

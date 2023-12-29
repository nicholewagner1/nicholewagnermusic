<?php
/**
 * Wayfarer functions and definitions.
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
 * @package Wayfarer
 * @since 1.0.0
 */

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @since 1.0.0
 */
function wayfarer_setup_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'wayfarer_content_width', 720 );
}
add_action( 'after_setup_theme', 'wayfarer_setup_content_width', 0 );

/**
 * Load helper functions and libraries.
 */
require get_template_directory() . '/includes/customizer.php';
require get_template_directory() . '/includes/hooks.php';
require get_template_directory() . '/includes/template-helpers.php';
require get_template_directory() . '/includes/template-tags.php';
require get_template_directory() . '/includes/vendor/cedaro-theme/autoload.php';
wayfarer_theme()->load();

/**
 * Set up theme defaults and register support for various WordPress features.
 *
 * @since 1.0.0
 */
function wayfarer_setup() {
	// Add support for translating strings in this theme.
	load_theme_textdomain( 'wayfarer', get_template_directory() . '/languages' );

	// This theme styles the visual editor to resemble the theme style.
	add_editor_style( array(
		is_rtl() ? 'assets/css/editor-style-rtl.css' : 'assets/css/editor-style.css',
		wayfarer_fonts_icon_url(),
	) );

	// Add support for default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Add support for the title tag.
	add_theme_support( 'title-tag' );

	// Add support for post thumbnails.
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'wayfarer-block-grid-16x9', 530, 298, array( 'center', 'top' ) );
	add_image_size( 'wayfarer-hero', 1440, 600, array( 'center', 'top' ) );
	set_post_thumbnail_size( 530, 530, array( 'center', 'top' ) );

	// Add support for a logo.
	add_theme_support( 'custom-logo', array(
		'width'       => 280,
		'height'      => 280,
		'flex-height' => true,
		'flex-width'  => true,
		'header-text' => array( 'site-title', 'site-description' ),
	) );

	// Add support to allow widgets to use selective refresh in the Customizer.
	add_theme_support( 'customize-selective-refresh-widgets' );

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

	// Register default nav menus.
	register_nav_menus( array(
		'menu-1' => esc_html__( 'Header', 'wayfarer' ),
		'social' => esc_html__( 'Social Menu', 'wayfarer' ),
	) );

	// Register support for page type templates.
	wayfarer_theme()->page_types->add_support()->register( 'grid', array(
		'archive_template' => 'templates/grid-page.php',
		'single_template'  => 'templates/grid-page-child.php',
	) );

	// Register support for archive content settings.
	wayfarer_theme()->archive_content->add_support();

	// Register support for archive image settings.
	wayfarer_theme()->archive_images->add_support();
}
add_action( 'after_setup_theme', 'wayfarer_setup' );

/**
 * Register widget areas.
 *
 * @since 1.0.0
 */
function wayfarer_register_sidebars() {
	register_sidebar( array(
		'id'            => 'sidebar-1',
		'name'          => esc_html__( 'Sidebar', 'wayfarer' ),
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'wayfarer' ),
		'before_widget' => '<div id="%1$s" class="widget block-grid-item %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'id'            => 'sidebar-2',
		'name'          => esc_html__( 'Footer', 'wayfarer' ),
		'description'   => esc_html__( 'Widgets that appear at the bottom of every page.', 'wayfarer' ),
		'before_widget' => '<div id="%1$s" class="widget block-grid-item %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'wayfarer_register_sidebars' );

/**
 * Enqueue scripts and styles.
 *
 * @since 1.0.0
 */
function wayfarer_enqueue_assets() {
	// Add Themicons font, used in the main stylesheet.
	wp_enqueue_style( 'themicons', wayfarer_fonts_icon_url(), array(), '2.3.1' );

	// Load main style sheet.
	wp_enqueue_style( 'wayfarer-style', get_stylesheet_uri() );

	// Load RTL style sheet.
	wp_style_add_data( 'wayfarer-style', 'rtl', 'replace' );

	// Register scripts for enqueueing on demand.
	wp_register_script(
		'jquery-cue',
		get_template_directory_uri() . '/assets/js/vendor/jquery.cue.js',
		array( 'jquery', 'mediaelement' ),
		'1.2.5',
		true
	);

	wp_enqueue_script(
		'appendaround',
		get_template_directory_uri() . '/assets/js/vendor/appendAround.js',
		array( 'jquery' ),
		'20160907',
		true
	);

	wp_enqueue_script(
		'wp-nav-menus',
		get_theme_file_uri( '/assets/js/vendor/wp-nav-menus.js' ),
		array(),
		'1.0.0',
		true
	);

	wp_localize_script( 'wp-nav-menus', '_cedaroNavMenuL10n', array(
		'collapseSubmenu' => esc_html__( 'Collapse submenu', 'wayfarer' ),
		'expandSubmenu'   => esc_html__( 'Expand submenu', 'wayfarer' ),
	) );

	wp_enqueue_script(
		'wayfarer-script',
		get_template_directory_uri() . '/assets/js/main.js',
		array( 'jquery', 'appendaround', 'wp-nav-menus' ),
		'20210330',
		true
	);

	// Localize the main theme script.
	wp_localize_script( 'wayfarer-script', '_wayfarerSettings', array(
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		'mejs'    => array(
			'pluginPath' => includes_url( 'js/mediaelement/', 'relative' ),
		),
		'l10n'    => array(
			'expand'         => '<span class="screen-reader-text">' . esc_html__( 'Expand', 'wayfarer' ) . '</span>',
			'collapse'       => '<span class="screen-reader-text">' . esc_html__( 'Collapse', 'wayfarer' ) . '</span>',
			'nextTrack'      => esc_html__( 'Next Track', 'wayfarer' ),
			'previousTrack'  => esc_html__( 'Previous Track', 'wayfarer' ),
			'togglePlaylist' => esc_html__( 'Toggle Playlist', 'wayfarer' ),
		),
	) );

	// Load script to support comment threading when it's enabled.
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'wayfarer_enqueue_assets' );

/**
 * Enqueue block editor assets.
 *
 * @since 2.0.0
 */
function wayfarer_enqueue_block_editor_assets() {
	wp_enqueue_style(
		'wayfarer-block-editor',
		get_theme_file_uri( 'assets/css/block-editor.css' ),
		array( 'wp-edit-blocks' )
	);
}
add_action( 'enqueue_block_editor_assets', 'wayfarer_enqueue_block_editor_assets' );

/**
 * Add featured image as background image to post navigation elements.
 *
 * @since 1.0.0
 */
function wayfarer_hero_background_inline_styles() {
	if ( ! wayfarer_has_hero() ) {
		return;
	}

	$image = wayfarer_get_hero_image();

	if ( empty( $image ) ) {
		return;
	}

	$css = sprintf(
		'.hero-banner:before { background-image: url(%s) }',
		esc_url( $image )
	);

	wp_add_inline_style( 'wayfarer-style', $css );
}
add_action( 'wp_enqueue_scripts', 'wayfarer_hero_background_inline_styles', 15 );

/**
 * Add embedded styles to render custom order for site header.
 *
 * @since 1.0.0
 */
function wayfarer_site_header_layout_inline_styles() {
	$sections = wayfarer_get_site_header_sections();
	$count    = count( $sections );
	$order    = -1;

	$css = '';
	foreach ( $sections as $id => $label ) {
		$css .= sprintf(
			'.site-header .%1$s { -ms-flex-order: %2$s; -webkit-order: %2$s; order: %2$s; }',
			sanitize_html_class( $id ),
			absint( ++$order )
		);

		$css .= '@media (min-width: 1024px) {';
		$css .= sprintf(
			'.site-header .%1$s { z-index: %2$s }',
			sanitize_html_class( $id ),
			absint( --$count )
		);
		$css .= '}';
	}

	wp_add_inline_style( 'wayfarer-style', $css );
}
add_action( 'wp_enqueue_scripts', 'wayfarer_site_header_layout_inline_styles', 15 );

/**
 * JavaScript detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @since 1.0.0
 */
function wayfarer_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'wayfarer_javascript_detection', 0 );

/**
 * Configure MediaElement.js elements to aid styling.
 *
 * Extends the core _wpmejsSettings object to add a new feature via the
 * MediaElement.js plugin API.
 *
 * @since 1.0.0
 */
function wayfarer_mejs_setup() {
	if ( ! wp_script_is( 'mediaelement', 'done' ) ) {
		return;
	}
	?>
	<script>
	(function() {
		var settings = window._wpmejsSettings || {};
		settings.features = settings.features || mejs.MepDefaults.features;
		settings.features.push( 'wayfarertheme' );

		MediaElementPlayer.prototype.buildwayfarertheme = function( player ) {
			var container = player.container[0] || player.container;

			if ( 'AUDIO' === player.node.nodeName ) {
				player.options.setDimensions = false;
				container.classList.add( 'wayfarer-mejs-container' );
				container.style.height = '';
				container.style.width = '';
			}
		};
	})();
	</script>
	<?php
}
add_action( 'wp_print_footer_scripts', 'wayfarer_mejs_setup' );

if ( ! function_exists( 'wayfarer_fonts_icon_url' ) ) :
/**
 * Retrieve the icon font style sheet URL.
 *
 * @since 1.0.0
 *
 * @return string Font stylesheet.
 */
function wayfarer_fonts_icon_url() {
	return get_template_directory_uri() . '/assets/css/themicons.css';
}
endif;

/**
 * Wrapper for accessing the Cedaro_Theme instance.
 *
 * @since 1.0.0
 *
 * @return Cedaro_Theme
 */
function wayfarer_theme() {
	static $instance;

	if ( null === $instance ) {
		Cedaro_Theme_Autoloader::register();
		$instance = new Cedaro_Theme( array( 'prefix' => 'wayfarer' ) );
	}

	return $instance;
}

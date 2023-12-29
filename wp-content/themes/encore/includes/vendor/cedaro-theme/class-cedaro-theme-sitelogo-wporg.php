<?php
/**
 * Site logo support.
 *
 * Backward compatibility for themes that declare site-logo support before
 * WordPress implemented custom logo functionality.
 *
 * @since 2.0.0
 *
 * @package Cedaro\Theme
 * @copyright Copyright (c) 2014, Cedaro
 * @license GPL-2.0+
 */

/**
 * Class for the site logo feature.
 *
 * @package Cedaro\Theme
 * @since 2.0.0
 */
class Cedaro_Theme_SiteLogo_WPorg extends Cedaro_Theme_SiteLogo {
	/*
	 * Public API methods.
	 */

	/**
	 * Attach theme hooks for the site logo feature.
	 *
	 * The Jetpack site logo doesn't load until init:10, so we can't check for
	 * jetpack_the_site_logo() until then and the check needs to occur before
	 * wp_loaded:10 to determine if our functionality should be attached instead.
	 *
	 * @since 3.0.0
	 *
	 * @see Cedaro_Theme_SiteLogo::wp_loaded()
	 */
	public function add_support() {
		$logo_settings = get_theme_support( 'site-logo' );

		// Add support for a logo.
		add_theme_support( 'custom-logo', array(
			'height'      => $logo_settings[0]['size'],
			'width'       => $logo_settings[0]['size'],
			'flex-height' => true,
			'flex-width'  => true,
			'header-text' => array( 'site-title', 'site-description' ),
		) );

		// Remove legacy support for the site logo feature.
		remove_theme_support( 'site-logo' );

		add_action( 'wp_loaded', array( $this, 'wp_loaded' ), 0 );
		return $this;
	}

	/**
	 * Retrieve the logo HTML.
	 *
	 * Returns early if the current theme doesn't support 'custom-logo'.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function html() {
		if ( ! current_theme_supports( 'custom-logo' ) ) {
			return '';
		}

		return get_custom_logo();
	}

	/*
	 * Hook callbacks.
	 */

	/**
	 * Set up site logo support.
	 *
	 * If the current theme doesn't support site logos or a site logo plugin
	 * exists, don't worry about setting anything up.
	 *
	 * @since 2.0.0
	 */
	public function wp_loaded() {
		// Bail if the current theme doesn't support site logos
		// Or if a plugin that provides site logo support is available.
		if ( ! current_theme_supports( 'custom-logo' ) ) {
			return;
		}

		add_filter( 'body_class', array( $this, 'body_class' ) );
		add_action( 'admin_init', array( $this, 'migrate_site_logo' ) );
		add_filter( 'get_custom_logo_image_attributes', array( $this, 'filter_logo_attributes' ) );
		add_filter( 'get_custom_logo', array( $this, 'filter_custom_logo_html' ) );

		add_action( 'customize_register', array( $this, 'customize_register' ) );
		add_action( 'customize_preview_init', array( $this, 'enqueue_customizer_preview_assets' ) );
	}

	/**
	 * Filter logo attributes to add a custom class for backwards compatibility.
	 *
	 * @since 4.0.0
	 *
	 * @param array $attributes Array of attributes.
	 * @return array
	 */
	public function filter_logo_attributes( $attributes ) {
		$attributes['class'] .= ' site-logo';
		return $attributes;
	}

	/**
	 * Filter the custom logo HTML to add a class for backwards compatibility.
	 *
	 * @since 4.0.0
	 *
	 * @param string $html Custom logo HTML.
	 * @return string
	 */
	public function filter_custom_logo_html( $html ) {
		return str_replace( 'class="custom-logo-link', 'class="custom-logo-link site-logo-link', $html );
	}

	/**
	 * Add a class to the body element if a custom logo exists.
	 *
	 * This class is added for backwards compatibility.
	 *
	 * @since 3.0.0
	 *
	 * @param array $classes Body classes.
	 * @return array
	 */
	public function body_class( $classes ) {
		if ( has_custom_logo() ) {
			$classes[] = 'has-site-logo';
		}
		return $classes;
	}

	/**
	 * Register Customizer settings and controls.
	 *
	 * @since 2.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 */
	public function customize_register( $wp_customize ) {
		$setting = $wp_customize->get_setting( 'header_text' );
		if ( $setting ) {
			$setting->transport = 'postMessage';
		}
	}

	/**
	 * Enqueue assets when previewing the site in the Customizer.
	 *
	 * @since 2.0.0
	 */
	public function enqueue_customizer_preview_assets() {
		wp_enqueue_script(
			'cedaro-theme-customize-preview',
			$this->theme->get_library_uri( 'assets/js/customize-preview.js' ),
			array( 'customize-preview' ),
			'1.0.0',
			true
		);
	}



	/**
	 * Migrate old logo data to the native custom logo theme mod.
	 *
	 * Jetpack 9.9 made breaking changes to the site_logo option in preparation
	 * for WordPress 5.8. Moving legacy data to the native custom_logo theme mod
	 * allows us to rely on core functionality.
	 *
	 * @since 4.0.0
	 */
	public function migrate_site_logo() {
		// Bail if the custom logo theme mod exists.
		if ( has_custom_logo() ) {
			return;
		}

		// Migrate the header text display setting.
		$header_text = get_theme_mod( 'site_logo_header_text' );
		if ( false !== $header_text ) {
			set_theme_mod( 'header_text', $header_text );
			remove_theme_mod( 'site_logo_header_text' );
		}

		// Use data from the site_logo option if it exists.
		$logo = $this->get_logo_data();

		if ( ! empty( $logo ) && is_int( $logo ) ) {
			set_theme_mod( 'custom_logo', $logo );
			return;
		}

		if ( ! empty( $logo['id'] ) ) {
			set_theme_mod( 'custom_logo', $logo['id'] );

			// Delete legacy data.
			delete_option( 'site_logo' );
			return;
		}

		// Fallback to the cedaro_site_logo_url option if it exists.
		$logo_url = get_option( 'cedaro_site_logo_url', '' );

		if ( ! empty( $logo_url ) ) {
			$attachment_id = $this->get_attachment_id_by_url( $logo_url );

			if ( $attachment_id ) {
				set_theme_mod( 'custom_logo', $attachment_id );
				delete_option( 'cedaro_site_logo_url' );
			}
		}
	}

	/**
	 * Return an ID of an attachment by searching the database with the file URL.
	 *
	 * First checks to see if the $url is pointing to a file that exists in the
	 * wp-content directory. If so, then we search the database for a partial match
	 * consisting of the remaining path AFTER the wp-content directory. Finally, if
	 * a match is found the attachment ID will be returned.
	 *
	 * @since 4.0.0
	 *
	 * @link http://frankiejarrett.com/get-an-attachment-id-by-url-in-wordpress/
	 * @link https://core.trac.wordpress.org/ticket/16830
	 * @todo https://core.trac.wordpress.org/changeset/24240
	 *
	 * @param string $url URL.
	 * @return int Attachment ID.
	 */
	protected function get_attachment_id_by_url( $url ) {
		global $wpdb;

		// Split the $url into two parts with the wp-content directory as the separator.
		$parse_url = explode( parse_url( WP_CONTENT_URL, PHP_URL_PATH ), $url );

		// Get the host of the current site and the host of the $url, ignoring www.
		$this_host = str_ireplace( 'www.', '', parse_url( home_url(), PHP_URL_HOST ) );
		$file_host = str_ireplace( 'www.', '', parse_url( $url, PHP_URL_HOST ) );

		// Return nothing if there aren't any $url parts or if the current host and $url host do not match.
		if ( ! isset( $parse_url[1] ) || empty( $parse_url[1] ) || ( $this_host !== $file_host ) ) {
			return null;
		}

		// Search the DB for an attachment GUID with a partial path match.
		$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='attachment' AND guid RLIKE %s", $parse_url[1] ) );

		// Returns null if no attachment is found.
		return $attachment[0];
	}
}

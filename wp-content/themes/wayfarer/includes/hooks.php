<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * @package Wayfarer
 * @since 1.0.0
 */

/**
 * Adjust the content width based on the current template.
 *
 * @since 1.0.0
 */
function wayfarer_content_width() {
	if ( wayfarer_is_full_width() ) {
		$GLOBALS['content_width'] = 1100;
	}
}
add_action( 'template_redirect', 'wayfarer_content_width' );

/**
 * Register template parts to load throughout the theme.
 *
 * Take note of the priorities. Changing them will allow template parts to be
 * loaded in a different order.
 *
 * To remove any of these parts, use remove_action() in the
 * "wayfarer_register_template_parts" hook or later.
 *
 * @since 1.0.0
 */
function wayfarer_register_template_parts() {
	$page_on_front = get_option( 'page_on_front' );

	if ( wayfarer_has_hero() ) {
		add_action( 'wayfarer_header_bottom', 'wayfarer_hero' );
		add_action( 'wayfarer_entry_top', 'wayfarer_entry_image_meta' );
	}

	if ( is_front_page() ) {
		add_action( 'wayfarer_header_after', 'wayfarer_featured_posts' );
	}

	if ( ! is_front_page() && 'page' === get_option( 'show_on_front' ) && empty( $page_on_front ) ) {
		add_action( 'wayfarer_header_after', 'wayfarer_featured_posts' );
	}

	if ( is_home() || is_archive() || is_search() ) {
		add_action( 'wayfarer_content_bottom', 'wayfarer_posts_navigation' );
	}

	if ( is_singular( 'post' ) ) {
		add_action( 'wayfarer_entry_footer_top', 'wayfarer_author_info' );
		add_action( 'wayfarer_footer_before', 'wayfarer_post_navigation' );
	}

	if ( is_singular() ) {
		add_action( 'wayfarer_footer_before', 'wayfarer_entry_comments', 20 );
		add_action( 'wayfarer_footer_before', 'wayfarer_append_mobile_sidebar', 30 );
	}

	do_action( 'wayfarer_register_template_parts' );
}
add_action( 'template_redirect', 'wayfarer_register_template_parts', 9 );

/**
 * Replace archive header image.
 *
 * @since 1.0.0
 */
function wayfarer_archive_header_image_html() {
	$archive_images = wayfarer_theme()->archive_images;

	if ( 'header' === $archive_images->get_mode() ) {
		remove_action( 'wayfarer_entry_content_top', array( $archive_images, 'print_the_image_html' ) );
		add_action( 'wayfarer_entry_header_bottom', array( $archive_images, 'print_the_image_html' ) );
	}
}
add_action( 'template_redirect', 'wayfarer_archive_header_image_html' );

/**
 * Add classes to the 'body' element.
 *
 * @since 1.0.0
 *
 * @param array $classes Default classes.
 * @return array
 */
function wayfarer_body_class( $classes ) {
	$classes[] = 'layout-site-' . get_theme_mod( 'site_layout', 'align-left' );

	if ( wayfarer_is_full_width() ) {
		$classes[] = 'layout-content-full-width';
	}

	if ( is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'layout-content-sidebar';
	}

	if ( wayfarer_is_player_active() ) {
		$classes[] = 'has-player';
	}

	if ( wayfarer_has_hero() ) {
		$classes[] = 'has-hero';

		$overlay_selector = wayfarer_get_hero_overlay_selector();
		if ( ! empty( $overlay_selector ) ) {
			$classes[] = 'has-hero-overlay';
			$classes[] = $overlay_selector . '-hero-overlay';
		}
	}

	if (
		is_page()
		&& ! is_page_template( 'templates/grid-page.php' )
		&& ! wayfarer_has_content()
	) {
		$classes[] = 'no-content';
	}

	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	return $classes;
}
add_filter( 'body_class', 'wayfarer_body_class' );

/**
 * Add custom classes to the array of post classes.
 *
 * @since 1.0.0
 *
 * @param array $classes Post classes.
 * @return array
 */
function wayfarer_post_classes( $classes ) {
	$classes[] = 'entry';

	if ( ! is_singular( 'post' ) ) {
		$classes = array_diff( $classes, array( 'hentry' ) );
	}

	$archive_images = wayfarer_theme()->archive_images;
	$mode           = $archive_images->get_mode();

	if ( $archive_images->in_supported_loop() && ! empty( $mode ) ) {
		$classes[] = 'archive-image-' . $mode;
	}

	return $classes;
}
add_filter( 'post_class', 'wayfarer_post_classes' );

/**
 * Filter the archive title based on the queried object.
 *
 * @since 1.0.0
 *
 * @param string $title Archive title.
 * @return string
 */
function wayfarer_get_the_archive_title( $title ) {
	if ( is_category() ) {
		$title = single_cat_title( '', false );
	} elseif ( is_tag() ) {
		$title = single_tag_title( '', false );
	} elseif ( is_author() ) {
		$title = '<span class="vcard">' . get_the_author() . '</span>';
	} elseif ( is_year() ) {
		$title = get_the_date( 'Y' );
	} elseif ( is_month() ) {
		$title = get_the_date( 'F Y' );
	} elseif ( is_day() ) {
		$title = get_the_date( 'F j, Y' );
	} elseif ( is_tax() ) {
		$title = single_term_title( '', false );
	} elseif ( is_post_type_archive() ) {
		$title = post_type_archive_title( '', false );
	}

	return $title;
}
add_filter( 'get_the_archive_title', 'wayfarer_get_the_archive_title' );

/**
 * Display author description on author archive pages.
 *
 * @since 1.0.0
 *
 * @param string $description Author description.
 * @return string
 */
function wayfarer_author_archive_description( $description ) {
	if ( is_author() ) {
		$description = wayfarer_allowed_tags( wpautop( get_the_author_meta( 'description' ) ) );
	}

	return $description;
}
add_filter( 'get_the_archive_description', 'wayfarer_author_archive_description' );

/**
 * Add an image itemprop attribute to image attachments.
 *
 * @since 1.0.0
 *
 * @param  array $attr Attributes for the image markup.
 * @return array
 */
function wayfarer_attachment_image_attributes( $attr ) {
	$attr['itemprop'] = 'image';
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'wayfarer_attachment_image_attributes' );

/**
 * Remove "Protected:" prefix from private post titles; use a font icon.
 *
 * @since 1.0.0
 */
function wayfarer_private_title_format() {
	return '%s';
}
add_filter( 'private_title_format', 'wayfarer_private_title_format' );

/**
 * Filter the sidebar status on certain pages.
 *
 * @since 1.0.0
 *
 * @param bool   $is_active Whether the sidebar is active.
 * @param string $index             The sidebar id.
 * @return bool
 */
function wayfarer_sidebar_status( $is_active, $index ) {
	if ( 'sidebar-1' !== $index ) {
		return $is_active;
	}

	if (
		is_page_template( 'templates/no-sidebar-page.php' )
		|| wayfarer_is_full_width()
	) {
		$is_active = false;
	}

	return $is_active;
}
add_filter( 'is_active_sidebar', 'wayfarer_sidebar_status', 10, 2 );

/**
 * Use hero image for archive header image size.
 *
 * @since 1.0.0
 *
 * @param string $size Image size name.
 * @param string $mode Mode.
 * @return string
 */
function wayfarer_archive_header_image_size( $size, $mode ) {
	if ( 'header' === $mode ) {
		$size = 'wayfarer-hero';
	}

	return $size;
}
add_filter( 'wayfarer_archive_image_size', 'wayfarer_archive_header_image_size', 10, 2 );

/**
 * Change how page type content is ordered.
 *
 * @param  array $args WP Query arguments.
 * @return array
 */
function wayfarer_page_type_query_args( $args ) {

	if ( wayfarer_is_page_type_archive( 'grid' ) ) {
		$args['orderby'] = get_theme_mod( 'grid_page_type_order', 'menu_order' );
	}

	return $args;
}
add_filter( 'wayfarer_page_type_query_args', 'wayfarer_page_type_query_args' );

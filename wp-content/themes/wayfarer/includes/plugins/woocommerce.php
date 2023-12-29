<?php
/**
 * WooCommerce integration.
 *
 * @package Wayfarer
 * @since 1.0.0
 * @link https://docs.woothemes.com/document/third-party-custom-theme-compatibility/
 */

/**
 * Set up WooCommerce theme support.
 *
 * @since 1.0.0
 */
function wayfarer_woocommerce_setup() {
	add_theme_support( 'woocommerce' );

	// Disable the page title for the catalog and product archive pages.
	add_filter( 'woocommerce_show_page_title', '__return_false' );
}
add_action( 'after_setup_theme', 'wayfarer_woocommerce_setup', 11 );

/**
 * Register WooCommerce template parts.
 *
 * @since 1.0.0
 */
function wayfarer_woocommerce_register_template_parts() {
	// Remove pagination.
	remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );

	// Remove breadcrumbs.
	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

	// Remove the comments/reviews section.
	// Reviews are displayed in the tabbed content area.
	if ( is_singular( 'product' ) ) {
		remove_action( 'wayfarer_footer_before', 'wayfarer_entry_comments', 20 );
	}
}
add_action( 'wayfarer_register_template_parts', 'wayfarer_woocommerce_register_template_parts' );

/**
 * Load assets for WooCommerce support.
 *
 * @since 1.0.0
 */
function wayfarer_woocommerce_enqueue_assets() {
	wp_enqueue_style(
		'wayfarer-woocommerce',
		get_template_directory_uri() . '/assets/css/woocommerce.css',
		array( 'wayfarer-style' )
	);

	wp_style_add_data( 'wayfarer-woocommerce', 'rtl', 'replace' );
}
add_action( 'wp_enqueue_scripts', 'wayfarer_woocommerce_enqueue_assets', 20 );

/**
 * Remove the default WooCommerce content wrappers.
 *
 * @since 1.0.0
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper' );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end' );

/**
 * Print the content open tag.
 *
 * Wraps WooCommerce content with the same elements used throughout the theme.
 *
 * @since 1.0.0
 */
function wayfarer_woocommerce_before_main_content() {
	echo '<div id="primary" class="content-area">';
	do_action( 'wayfarer_primary_top' );
	echo '<main id="main" class="site-main">';
	do_action( 'wayfarer_main_top' );
}
add_action( 'woocommerce_before_main_content', 'wayfarer_woocommerce_before_main_content' );

/**
 * Print the default theme content wrapper close tag.
 *
 * @since 1.0.0
 */
function wayfarer_woocommerce_after_main_content() {
	do_action( 'wayfarer_main_bottom' );
	echo '</main>';
	do_action( 'wayfarer_primary_bottom' );
	echo '</div>';
}
add_action( 'woocommerce_after_main_content', 'wayfarer_woocommerce_after_main_content' );

/**
 * Retrieve a menu item linking to the WooCommerce cart.
 *
 * @since 1.0.0
 *
 * @return string
 */
function wayfarer_woocommerce_cart_menu_item() {
	$quantity = WC()->cart->get_cart_contents_count();
	$classes  = is_cart() ? ' current-menu-item' : '';
	$classes .= $quantity ? '' : ' screen-reader-text';

	return wayfarer_wporg_cart_menu_item( array(
		'prefix'   => 'woocommerce',
		'classes'  => $classes,
		'quantity' => $quantity,
		'url'      => wc_get_cart_url(),
		'label'    => get_page( wc_get_page_id( 'cart' ) )->post_title,
	) );
}

/**
 * Display a cart item in the Primary navigation.
 *
 * @since 1.0.0
 *
 * @param  string $items The HTML list content for the menu items.
 * @param  object $args  An object containing wp_nav_menu() arguments.
 * @return string
 */
function wayfarer_woocommerce_wp_nav_menu_items( $items, $args ) {
	if ( isset( $args->theme_location ) && 'menu-1' === $args->theme_location ) {
		$items .= wayfarer_woocommerce_cart_menu_item();
	}

	return $items;
}
add_filter( 'wp_nav_menu_items', 'wayfarer_woocommerce_wp_nav_menu_items', 10, 2 );

/**
 * This function updates the Top Navigation WooCommerce cart link contents when
 * an item is added via AJAX.
 *
 * @since 1.0.0
 *
 * @param array $fragments HTML fragments to update when adding an item to the cart via AJAX.
 */
function wayfarer_woocommerce_add_to_cart_fragments( $fragments ) {
	$fragments['li.menu-item-type-woocommerce-cart'] = wayfarer_woocommerce_cart_menu_item();
	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'wayfarer_woocommerce_add_to_cart_fragments' );

/**
 * Determine if the page can show hero content.
 *
 * @since 1.0.0
 *
 * @param bool $is_active Hero is showing or not showing.
 */
function wayfarer_woocommerce_is_hero_active( $is_active ) {
	if ( is_shop() ) {
		$is_active = true;
	} elseif ( is_singular( 'product' ) ) {
		$is_active = false;
	}

	return $is_active;
}
add_filter( 'wayfarer_is_hero_active', 'wayfarer_woocommerce_is_hero_active' );

/**
 * Determine if a page's content should be displayed in full-width.
 *
 * @since 1.0.0
 *
 * @param bool $is_full_width Page is full-width or not full-width.
 * @return bool
 */
function wayfarer_woocommerce_is_full_width( $is_full_width ) {

	if ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) {
		$is_full_width = true;
	}

	return $is_full_width;
}
add_filter( 'wayfarer_is_full_width', 'wayfarer_woocommerce_is_full_width' );

/**
 * Return shop page ID.
 *
 * @since 1.0.0
 *
 * @param int $post_id Post ID.
 * @return int
 */
function wayfarer_woocommerce_shop_post_id( $post_id ) {
	if ( is_shop() ) {
		$post_id = wc_get_page_id( 'shop' );
	}

	return $post_id;
}
add_filter( 'wayfarer_main_post_id', 'wayfarer_woocommerce_shop_post_id' );

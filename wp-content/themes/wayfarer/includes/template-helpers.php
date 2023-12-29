<?php
/**
 * Helper methods for loading or displaying template partials.
 *
 * These are typically miscellaneous template parts used outside the loop.
 * Although if the partial requires any sort of set up or tearddown, moving that
 * logic into a helper keeps the parent template a little more lean, clean,
 * reusable and easier to override in child themes.
 *
 * Loading these partials within an action hook will allow them to be easily
 * added, removed, or reordered without changing the parent template file.
 *
 * Take a look at wayfarer_register_template_parts() to see where most of these
 * are inserted.
 *
 * This approach tries to blend the two common approaches to theme development
 * (hooks or partials).
 *
 * @package Wayfarer
 * @since 1.0.0
 */

/**
 * Display author info.
 *
 * @since 1.0.0
 */
function wayfarer_author_info() {
	$description = get_the_author_meta( 'description' );

	if ( ! empty( $description ) ) {
		get_template_part( 'templates/parts/author-box' );
	} else {
		wayfarer_entry_author();
	}
}

/**
 * Display entry comments.
 *
 * @since 1.0.0
 */
function wayfarer_entry_comments() {
	comments_template( '', true );
}

/**
 * Inject image meta into entry content.
 *
 * Featured images for singular posts are displayed as hero images outside of
 * the article content.
 *
 * @since 1.0.0
 */
function wayfarer_entry_image_meta() {
	$thumbnail_id = get_post_thumbnail_id();

	if ( empty( $thumbnail_id ) ) {
		return;
	}

	printf(
		'<meta itemprop="image" content="%s">',
		esc_url( wp_get_attachment_url( $thumbnail_id, 'full' ) )
	);
}

/**
 * Display featured posts if available.
 *
 * @since 1.0.0
 */
function wayfarer_featured_posts() {
	global $post;

	if ( ! wayfarer_has_featured_posts() ) {
		return;
	}

	$posts = wayfarer_get_featured_posts();

	include locate_template( 'templates/parts/featured-posts.php' );
}

/**
 * Display hero content.
 *
 * @since 1.0.0
 */
function wayfarer_hero() {
	get_template_part( 'templates/parts/hero', wayfarer_post_template_name() );
}

/**
 * Load the player template part.
 *
 * @since 1.0.0
 */
function wayfarer_player() {
	if ( ! wayfarer_is_player_active() ) {
		return;
	}

	wp_enqueue_script( 'jquery-cue' );

	$tracks = wayfarer_get_player_tracks();

	$settings = array(
		'cueSignature' => md5( implode( ',', wp_list_pluck( $tracks, 'src' ) ) ),
		'tracks'       => $tracks,
	);

	include locate_template( 'templates/parts/player.php' );
}

/**
 * Display sidebar area container to move the sidebar widgets depending on
 * the screen width.
 *
 * @since 1.0.0
 */
function wayfarer_append_mobile_sidebar() {
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		return;
	}

	echo '<aside class="sidebar-area sidebar-area--mobile"></aside>';
}

<?php
/**
 * AudioTheme integration.
 *
 * @package Wayfarer
 * @since 1.0.0
 * @link https://audiotheme.com/
 */

/**
 * Set up theme defaults and register support for AudioTheme features.
 *
 * @since 1.0.0
 */
function wayfarer_audiotheme_setup() {
	// Add thumbnail support to archive pages.
	add_post_type_support( 'audiotheme_archive', 'thumbnail' );

	// Register nav menus.
	register_nav_menus( array(
		'audiotheme_gig'    => esc_html__( 'Gigs Menu', 'wayfarer' ),
		'audiotheme_record' => esc_html__( 'Records Menu', 'wayfarer' ),
		'audiotheme_video'  => esc_html__( 'Videos Menu', 'wayfarer' ),
	) );

	// Add support for AudioTheme widgets.
	add_theme_support( 'audiotheme-widgets', array(
		'record',
		'track',
		'upcoming-gigs',
		'video',
	) );

	// Add post type support for featured content.
	wayfarer_theme()->featured_content->add_post_types( array(
		'audiotheme_archive',
		'audiotheme_record',
		'audiotheme_video',
	) );

	// Remove AudioTheme video wrappers.
	remove_filter( 'embed_oembed_html', 'audiotheme_oembed_html', 10 );
	remove_filter( 'embed_handler_html', 'audiotheme_oembed_html', 10 );
}
add_action( 'after_setup_theme', 'wayfarer_audiotheme_setup', 11 );

/**
 * Load required scripts for AudioTheme support.
 *
 * @since 1.0.0
 */
function wayfarer_audiotheme_enqueue_assets() {
	wp_enqueue_style(
		'wayfarer-audiotheme',
		get_template_directory_uri() . '/assets/css/audiotheme.css',
		array( 'wayfarer-style' )
	);

	wp_style_add_data( 'wayfarer-audiotheme', 'rtl', 'replace' );

	if ( in_array( get_post_type(), array( 'audiotheme_record', 'audiotheme_track' ), true ) ) {
		wp_enqueue_script( 'jquery-cue' );
	}
}
add_action( 'wp_enqueue_scripts', 'wayfarer_audiotheme_enqueue_assets', 20 );

/**
 * Add classes to the 'body' element.
 *
 * @since 1.0.0
 *
 * @param array $classes Default classes.
 * @return array
 */
function wayfarer_audiotheme_body_class( $classes ) {
	if ( is_singular( 'audiotheme_video' ) && ! wayfarer_has_content() ) {
		$classes[] = 'no-content';
	}

	return $classes;
}
add_filter( 'body_class', 'wayfarer_audiotheme_body_class' );


/*
 * Admin hooks.
 * -----------------------------------------------------------------------------
 */

/**
 * Activate the archive settings meta box for the Gigs archive.
 */
add_filter( 'add_audiotheme_archive_settings_meta_box_audiotheme_gig', '__return_true' );

/**
 * Save AudioTheme archive settings.
 *
 * @since 1.0.0
 *
 * @param int     $post_id   Post ID.
 * @param WP_Post $post      Post object.
 * @param string  $post_type Post type name.
 */
function wayfarer_audiotheme_save_archive_settings( $post_id, $post, $post_type ) {
	$value = isset( $_POST['wayfarer_sidebar_singular'] ) ? 'enabled' : 'disabled';
	update_post_meta( $post_id, 'wayfarer_sidebar_singular', $value );
}
add_action( 'save_audiotheme_archive_settings', 'wayfarer_audiotheme_save_archive_settings', 10, 3 );

/**
 * Display AudioTheme archive setting fields.
 *
 * @since 1.0.0
 *
 * @param WP_Post $post Archive post.
 */
function wayfarer_audiotheme_archive_settings_meta_box( $post ) {
	$post_type = is_audiotheme_post_type_archive_id( $post->ID );

	if ( ! $post_type ) {
		return;
	}

	$sidebar_status = get_audiotheme_archive_meta( 'wayfarer_sidebar_singular', true, 'enabled', $post_type );
	?>
	<p>
		<label for="wayfarer-sidebar-singular-status">
			<input type="checkbox" name="wayfarer_sidebar_singular" id="wayfarer-sidebar-singular-status" value="1" <?php checked( $sidebar_status, 'enabled' ); ?>>
			<?php esc_html_e( 'Enable sidebar for single posts in this archive?', 'wayfarer' ); ?>
		</label>
	</p>
	<?php
}
add_action( 'audiotheme_archive_settings_meta_box', 'wayfarer_audiotheme_archive_settings_meta_box', 20 );

/**
 * Filter the sidebar status for singular AudioTheme posts.
 *
 * @since 1.0.0
 *
 * @param bool   $is_active_sidebar Whether the sidebar is active.
 * @param string $index             The sidebar id.
 * @return bool
 */
function wayfarer_audiotheme_sidebar_status( $is_active_sidebar, $index ) {
	if ( 'sidebar-1' !== $index || ! $is_active_sidebar ) {
		return $is_active_sidebar;
	}

	if ( is_singular( array( 'audiotheme_gig', 'audiotheme_record', 'audiotheme_track', 'audiotheme_video' ) ) ) {
		$post_type = get_post_type();

		if ( 'audiotheme_track' === $post_type ) {
			$post_type = 'audiotheme_record';
		}

		$sidebar_status    = get_audiotheme_archive_meta( 'wayfarer_sidebar_singular', true, 'enabled', $post_type );
		$is_active_sidebar = ( 'enabled' === $sidebar_status );
	}

	return $is_active_sidebar;
}
add_filter( 'is_active_sidebar', 'wayfarer_audiotheme_sidebar_status', 10, 2 );


/*
 * Plugin hooks.
 * -----------------------------------------------------------------------------
 */

/**
 * Activate default archive setting fields.
 *
 * @since 1.0.0
 *
 * @param array  $fields    List of default fields to activate.
 * @param string $post_type Post type archive.
 * @return array
 */
function wayfarer_audiotheme_archive_settings_fields( $fields, $post_type ) {
	if ( ! in_array( $post_type, array( 'audiotheme_record', 'audiotheme_video' ), true ) ) {
		return $fields;
	}

	$fields['columns'] = array(
		'choices' => range( 2, 4 ),
		'default' => 3,
	);

	$fields['posts_per_archive_page'] = true;

	return $fields;
}
add_filter( 'audiotheme_archive_settings_fields', 'wayfarer_audiotheme_archive_settings_fields', 10, 2 );

/**
 * Adjust AudioTheme widget image sizes.
 *
 * @since 1.0.0
 *
 * @param string|array $size Image size.
 * @return array
 */
function wayfarer_audiotheme_widget_image_size( $size ) {
	return array( 340, 340 ); // Max footer widget column width.
}
add_filter( 'audiotheme_widget_record_image_size', 'wayfarer_audiotheme_widget_image_size' );
add_filter( 'audiotheme_widget_track_image_size', 'wayfarer_audiotheme_widget_image_size' );
add_filter( 'audiotheme_widget_video_image_size', 'wayfarer_audiotheme_widget_image_size' );

/**
 * Disable Jetpack Infinite Scroll on AudioTheme post types.
 *
 * @since 1.0.0
 *
 * @param bool $supported Whether Infinite Scroll is supported for the current request.
 * @return bool
 */
function wayfarer_audiotheme_infinite_scroll_archive_supported( $supported ) {
	$post_type = get_post_type() ? get_post_type() : get_query_var( 'post_type' );

	if ( $post_type && false !== strpos( $post_type, 'audiotheme_' ) ) {
		$supported = false;
	}

	return $supported;
}
add_filter( 'infinite_scroll_archive_supported', 'wayfarer_audiotheme_infinite_scroll_archive_supported' );


/*
 * Theme hooks.
 * -----------------------------------------------------------------------------
 */

/**
 * Enable hero banner on AudioTheme archives.
 *
 * @since 1.0.0
 *
 * @param bool $is_active Whether the hero area is enabled.
 * @return bool
 */
function wayfarer_audiotheme_is_hero_active( $is_active ) {
	if (
		is_audiotheme_post_type_archive()
		|| is_tax( array( 'audiotheme_record_type', 'audiotheme_video_category' ) )
	) {
		$is_active = true;
	}

	return $is_active;
}
add_filter( 'wayfarer_is_hero_active', 'wayfarer_audiotheme_is_hero_active' );

/**
 * Get track hero background image.
 *
 * @since 1.0.0
 *
 * @param  string $image Image url.
 * @return string
 */
function wayfarer_audiotheme_track_hero_background_image( $image ) {
	if ( is_singular( 'audiotheme_track' ) && ! has_post_thumbnail() ) {
		$post_parent = get_post()->post_parent;
		$image       = get_the_post_thumbnail_url( $post_parent, 'wayfarer-hero' );
	}

	return $image;
}
add_filter( 'wayfarer_hero_background_image', 'wayfarer_audiotheme_track_hero_background_image' );

/**
 * Return AudioTheme archive post ID.
 *
 * @since 1.0.0
 *
 * @param int $post_id Post ID.
 * @return int
 */
function wayfarer_audiotheme_archive_post_id( $post_id ) {
	if (
		is_audiotheme_post_type_archive()
		|| is_tax( array( 'audiotheme_record_type', 'audiotheme_video_category' ) )
	) {
		$post_id = get_audiotheme_post_type_archive();
	}

	return $post_id;
}
add_filter( 'wayfarer_main_post_id', 'wayfarer_audiotheme_archive_post_id' );

/**
 * Register template parts to load throughout the theme.
 *
 * @since 1.0.0
 */
function wayfarer_audiotheme_register_template_parts() {
	if (
		wayfarer_has_hero()
		&& is_singular( array(
			'audiotheme_gig',
			'audiotheme_record',
			'audiotheme_track',
			'audiotheme_video',
		) )
	) {
		remove_action( 'wayfarer_header_bottom', 'wayfarer_hero' );
		add_action( 'wayfarer_header_bottom', 'wayfarer_audiotheme_hero' );
	}

	if ( is_singular( array( 'audiotheme_record', 'audiotheme_track' ) ) ) {
		add_action( 'wayfarer_entry_top', 'wayfarer_audiotheme_record_meta' );
	}

	if ( is_singular( 'audiotheme_video' ) ) {
		add_action( 'wayfarer_entry_top', 'wayfarer_audiotheme_video_meta' );
	}
}
add_filter( 'wayfarer_register_template_parts', 'wayfarer_audiotheme_register_template_parts' );

/**
 * Filter posts navigation values for Record and Video archives.
 *
 * @since 1.0.0
 *
 * @param  array $args Posts navigation arguments.
 * @return array
 */
function wayfarer_audiotheme_posts_navigation( $args ) {
	if (
		is_post_type_archive( array( 'audiotheme_record', 'audiotheme_video' ) )
		|| is_tax( array( 'audiotheme_record_type', 'audiotheme_video_category' ) )
	) {
		$post_type_object = get_post_type_object( get_post_type() );

		$args = array(
			'class'              => 'sort-natural',
			'prev_text'          => esc_html__( 'Next', 'wayfarer' ),
			'next_text'          => esc_html__( 'Previous', 'wayfarer' ),
			'screen_reader_text' => esc_html( sprintf(
				/* translators: 1: post type object label */
				__( '%1$s navigation', 'wayfarer' ), $post_type_object->label
			) ),
		);
	}

	return $args;
}
add_filter( 'wayfarer_posts_navigation_args', 'wayfarer_audiotheme_posts_navigation' );

/**
 * Disable sidebar functionality on AudioTheme archives.
 *
 * @since 1.0.0
 *
 * @param bool $is_full_width Whether the template should be full-width.
 * @return array
 */
function wayfarer_audiotheme_is_full_width( $is_full_width ) {
	if (
		is_audiotheme_post_type_archive()
		|| is_tax( array( 'audiotheme_record_type', 'audiotheme_video_category' ) )
	) {
		$is_full_width = true;
	}

	return $is_full_width;
}
add_filter( 'wayfarer_is_full_width', 'wayfarer_audiotheme_is_full_width' );

/**
 * Add classes to gig archive block grids.
 *
 * @since 1.0.0
 *
 * @param array $classes Array of HTML classes for the gig archive posts wrapper.
 * @return array
 */
function wayfarer_audiotheme_gig_posts_class( $classes ) {
	if ( is_post_type_archive( 'audiotheme_gig' ) ) {
		$classes[] = 'gig-list';
		$classes[] = 'vcalendar';
	}

	return $classes;
}
add_filter( 'wayfarer_posts_class', 'wayfarer_audiotheme_gig_posts_class' );

/**
 * Add classes to record archive block grids.
 *
 * @since 1.0.0
 *
 * @param array $classes Array of HTML classes for the record archive posts wrapper.
 * @return array
 */
function wayfarer_audiotheme_record_posts_class( $classes ) {
	if ( is_post_type_archive( 'audiotheme_record' ) || is_tax( 'audiotheme_record_type' ) ) {
		$classes[] = 'block-grid';
		$classes[] = 'block-grid-' . get_audiotheme_archive_meta( 'columns', true, 3 );
		$classes[] = 'block-grid--gutters';
	}

	return $classes;
}
add_filter( 'wayfarer_posts_class', 'wayfarer_audiotheme_record_posts_class' );

/**
 * Add classes to video archive block grids.
 *
 * @since 1.0.0
 *
 * @param array $classes Array of HTML classes for the video archive posts wrapper.
 * @return array
 */
function wayfarer_audiotheme_video_posts_class( $classes ) {
	if ( is_post_type_archive( 'audiotheme_video' ) || is_tax( 'audiotheme_video_category' ) ) {
		$classes[] = 'block-grid';
		$classes[] = 'block-grid-' . get_audiotheme_archive_meta( 'columns', true, 3 );
		$classes[] = 'block-grid--gutters';
		$classes[] = 'block-grid--16x9';
	}

	return $classes;
}
add_filter( 'wayfarer_posts_class', 'wayfarer_audiotheme_video_posts_class' );


/*
 * Template Tags
 * -----------------------------------------------------------------------------
 */

/**
 * Display hero content.
 *
 * @since 1.0.0
 */
function wayfarer_audiotheme_hero() {
	$slug = sprintf(
		'audiotheme/%s/hero',
		str_replace( 'audiotheme_', '', get_post_type() )
	);

	get_template_part( $slug );
}

/**
 * Inject record artist meta into content.
 *
 * The record artist name is displayed in the hero banner outside of the
 * article content.
 *
 * @since 1.0.0
 */
function wayfarer_audiotheme_record_meta() {
	$post    = get_post();
	$post_id = is_singular( 'audiotheme_track' ) ? $post->post_parent : $post->ID;

	$artist = get_audiotheme_record_artist( $post_id );
	$year   = get_audiotheme_record_release_year( $post_id );
	$genre  = get_audiotheme_record_genre( $post_id );

	if ( ! empty( $artist ) ) {
		printf(
			'<meta itemprop="byArtist" content="%s">',
			esc_attr( $artist )
		);
	}

	if ( ! empty( $year ) ) {
		printf(
			'<meta itemprop="dateCreated" content="%s">',
			esc_attr( $year )
		);
	}

	if ( ! empty( $genre ) ) {
		printf(
			'<meta itemprop="genre" content="%s">',
			esc_attr( $genre )
		);
	}
}

/**
 * Inject video meta into content.
 *
 * The record artist name is displayed in the hero banner outside of the
 * article content.
 *
 * @since 1.0.0
 */
function wayfarer_audiotheme_video_meta() {
	$thumbnail = get_post_thumbnail_id();
	$video_url = get_audiotheme_video_url();

	if ( ! empty( $thumbnail ) ) {
		printf(
			'<meta itemprop="thumbnailUrl" content="%s">',
			esc_url( wp_get_attachment_url( $thumbnail, 'full' ) )
		);
	}

	if ( ! empty( $video_url ) ) {
		printf(
			'<meta itemprop="embedUrl" content="%s">',
			esc_url( $video_url )
		);
	}
}

/**
 * Return a set of recent gigs.
 *
 * @since  1.0.0
 */
function wayfarer_audiotheme_recent_gigs_query() {
	$args = array(
		'order'          => 'desc',
		'posts_per_page' => 5,
		'meta_query'     => array(
			array(
				'key'     => '_audiotheme_gig_datetime',
				'value'   => current_time( 'mysql' ),
				'compare' => '<=',
				'type'    => 'DATETIME',
			),
		),
	);

	return new Audiotheme_Gig_Query( apply_filters( 'wayfarer_recent_gigs_query_args', $args ) );
}

if ( ! function_exists( 'wayfarer_audiotheme_track_length' ) ) :
/**
 * Display a track's duration.
 *
 * @since 1.0.0
 *
 * @param int $track_id Track ID.
 */
function wayfarer_audiotheme_track_length( $track_id = 0 ) {
	$track_id = empty( $track_id ) ? get_the_ID() : $track_id;
	$length   = get_audiotheme_track_length( $track_id );

	if ( empty( $length ) ) {
		$length = esc_html_x( '-:--', 'default track length', 'wayfarer' );
	}

	echo esc_html( $length );
}
endif;

/**
 * Add HTML attributes to a track element.
 *
 * @since 1.0.0
 *
 * @param int $track_id Optional. The track ID. Defaults to the current track in the loop.
 */
function wayfarer_track_attributes( $track_id = 0 ) {
	$track = get_post( $track_id );

	$classes = 'track';
	if ( get_audiotheme_track_file_url( $track->ID ) ) {
		$classes .= ' is-playable js-play-record';
	}

	$attributes = array(
		'class'          => $classes,
		'itemprop'       => 'track',
		'itemtype'       => 'https://schema.org/MusicRecording',
		'data-record-id' => absint( $track->post_parent ),
		'data-track-id'  => absint( $track->ID ),
	);

	foreach ( $attributes as $key => $value ) {
		printf(
			' %1$s="%2$s"',
			$key, // phpcs:ignore
			esc_attr( $value )
		);
	}
}

/**
 * Display a track's title.
 *
 * This is for backward compatibility with versions of AudioTheme prior to
 * 2.1.0.
 *
 * @since 1.1.1
 *
 * @param int|WP_Post $post Optional. Post ID or object.
 * @param array       $args Optional. Track title args.
 */
function wayfarer_audiotheme_track_title( $post = 0, $args = array() ) {
	$post = get_post( $post );

	if ( function_exists( 'get_audiotheme_track_title' ) ) {
		echo get_audiotheme_track_title( $post, $args );
	} else {
		printf(
			'<a href="%s" class="track-title" itemprop="url"><span itemprop="name">%s</span></a>',
			esc_url( get_permalink( $post ) ),
			get_the_title( $post )
		);
	}
}

/**
 * Display the current gig meta with optional content.
 *
 * @param string $before Optional. Content to prepend to the meta.
 * @param string $after  Optional. Content to append to the meta.
 */
function wayfarer_audiotheme_gig_ticket_meta( $before = '', $after = '' ) {
	$meta = wayfarer_audiotheme_get_gig_ticket_meta();

	if ( ! empty( $meta ) ) {
		echo $before . wayfarer_allowed_tags( $meta ) . $after; // phpcs:ignore
	}
}

/**
 * Retrieve gig ticket meta.
 *
 * @since 1.0.0
 *
 * @return string
 */
function wayfarer_audiotheme_get_gig_ticket_meta() {
	if ( ! audiotheme_gig_has_ticket_meta() ) {
		return;
	}

	$meta  = '';
	$price = get_audiotheme_gig_tickets_price();
	$url   = get_audiotheme_gig_tickets_url();

	if ( ! empty( $price ) ) {
		$meta = sprintf(
			'<span class="gig-ticktes-price" itemprop="price">%s</span>',
			esc_html( $price )
		);
	}

	if ( ! empty( $url ) && ! empty( $price ) ) {
		$meta = sprintf(
			'<a class="gig-tickets-url button js-maybe-external" href="%1$s">%2$s</a>',
			esc_url( $url ),
			sprintf(
				'%1$s <span class="gig-tickets-price-sep">&middot;</span> %2$s',
				esc_html__( 'Buy Tickets', 'wayfarer' ),
				$meta
			)
		);
	}

	if ( empty( $price ) && ! empty( $url ) ) {
		$meta = sprintf(
			'<a class="gig-tickets-url button js-maybe-external" href="%1$s">%2$s</a>',
			esc_url( $url ),
			esc_html__( 'Buy Tickets', 'wayfarer' )
		);
	}

	return apply_filters( 'wayfarer_audiotheme_gig_ticket_meta', $meta, $price, $url );
}

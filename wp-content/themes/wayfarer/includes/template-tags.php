<?php
/**
 * Custom template tags.
 *
 * @package Wayfarer
 * @since 1.0.0
 */

if ( ! function_exists( 'wp_body_open' ) ) {
	/**
	 * Add wp_body_open() to header.
	 *
	 * @since 1.1.5
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
}

/**
 * Retrieve site header sections.
 *
 * @since 1.0.0
 *
 * @return array
 */
function wayfarer_get_site_header_sections() {
	$sections = apply_filters( 'wayfarer_site_header_sections', array(
		'site-navigation-panel' => esc_html__( 'Navigation', 'wayfarer' ),
		'site-identity'         => esc_html__( 'Site Identity', 'wayfarer' ),
		'hero'                  => esc_html__( 'Hero', 'wayfarer' ),
	) );

	$ids = explode( ',', get_theme_mod( 'site_header_layout', '' ) );

	$sorted = array();
	foreach ( $ids as $id ) {
		if ( isset( $sections[ $id ] ) ) {
			$sorted[ $id ] = $sections[ $id ];
		}
	}

	// Append registered sections that aren't in the sorted array.
	$extra  = array_diff_key( $sections, $sorted );
	$sorted = array_merge( $sorted, $extra );

	return $sorted;
}

/**
 * Register a breadcrumbs site header section.
 *
 * @since 1.0.0
 *
 * @param array $sections Array of sections.
 * @return array
 */
function wayfarer_register_breadcrumbs_header_section( $sections ) {
	$sections['breadcrumbs'] = esc_html__( 'Breadcrumbs', 'wayfarer' );
	return $sections;
}

if ( ! function_exists( 'wayfarer_site_title' ) ) :
/**
 * Display the site title with link to homepage and optional content.
 *
 * @since 1.0.0
 *
 * @param string $before Optional. Content to prepend to the title.
 * @param string $after  Optional. Content to append to the title.
 */
function wayfarer_site_title( $before = '', $after = '' ) {
	$title = get_bloginfo( 'name', 'display' );

	if ( empty( $title ) ) {
		return;
	}

	$title = sprintf(
		'<a href="%1$s" rel="home">%2$s</a>',
		esc_url( home_url( '/' ) ),
		esc_html( $title )
	);

	echo $before . $title . $after; // phpcs:ignore
}
endif;

if ( ! function_exists( 'wayfarer_site_description' ) ) :
/**
 * Display the site description with optional content.
 *
 * @since 1.0.0
 *
 * @param string $before Optional. Content to prepend to the description.
 * @param string $after  Optional. Content to append to the description.
 */
function wayfarer_site_description( $before = '', $after = '' ) {
	$description = get_bloginfo( 'description', 'display' );

	if ( empty( $description ) ) {
		return;
	}

	echo $before . $description . $after; // phpcs:ignore
}
endif;

if ( ! function_exists( 'wayfarer_mobile_navigation' ) ) :
/**
 * Display mobile navigation buttons.
 *
 * @since 1.0.0
 */
function wayfarer_mobile_navigation() {
	?>
	<div class="mobile-navigation">
		<button class="site-navigation-toggle toggle-button">
			<span><?php esc_html_e( 'Menu', 'wayfarer' ); ?></span>
		</button>

		<?php if ( wayfarer_is_player_active() ) : ?>
			<button class="wayfarer-player-toggle toggle-button">
				<span class="screen-reader-text"><?php esc_html_e( 'Player', 'wayfarer' ); ?></span>
			</button>
		<?php endif; ?>
	</div>
	<?php
}
endif;

if ( ! function_exists( 'wayfarer_site_navigation' ) ) :
/**
 * Display primary navigation menu.
 *
 * @since 1.0.0
 */
function wayfarer_site_navigation() {
	if ( ! has_nav_menu( 'menu-1' ) ) {
		return;
	}
	?>
	<nav id="site-navigation" class="site-navigation" role="navigation" itemscope itemtype="https://schema.org/SiteNavigationElement">
		<?php
		wp_nav_menu( array(
			'theme_location' => 'menu-1',
			'container'      => false,
			'menu_class'     => 'menu',
			'depth'          => 3,
		) );
		?>
	</nav>
	<?php
}
endif;

if ( ! function_exists( 'wayfarer_social_navigation' ) ) :
/**
 * Display social navigation menu.
 *
 * @since 1.0.0
 */
function wayfarer_social_navigation() {
	if ( ! has_nav_menu( 'social' ) ) {
		return;
	}
	?>
	<nav class="social-navigation" role="navigation">
		<?php
		wp_nav_menu( array(
			'theme_location' => 'social',
			'container'      => false,
			'depth'          => 1,
			'link_before'    => '<span class="screen-reader-text">',
			'link_after'     => '</span>',
		) );
		?>
	</nav>
	<?php
}
endif;

if ( ! function_exists( 'wayfarer_post_type_navigation' ) ) :
/**
 * Display navigation menu for a post type archive.
 *
 * Navigation menus need to be registered using register_nav_menus() with the
 * location name set as the post type name.
 *
 * @since 1.0.0
 *
 * @param string $post_type Optional. Post type string.
 */
function wayfarer_post_type_navigation( $post_type = '' ) {
	$post_type = empty( $post_type ) ? get_post_type() : $post_type;

	$args = apply_filters( 'wayfarer_post_type_navigation_args', array(
		'theme_location' => $post_type,
		'container'      => false,
		'menu_class'     => 'menu',
		'depth'          => 1,
		'fallback_cb'    => false,
	) );

	if ( ! $args['theme_location'] || ! has_nav_menu( $args['theme_location'] ) ) {
		return;
	}
	?>
	<nav class="navigation post-type-navigation" role="navigation">
		<?php wp_nav_menu( $args ); ?>
	</nav>
	<?php
}
endif;

if ( ! function_exists( 'wayfarer_post_navigation' ) ) :
/**
 * Display navigation to next/previous posts when applicable.
 *
 * @since 1.0.0
 *
 * @param string $reverse Whether to reverse the previous and next link text.
 */
function wayfarer_post_navigation( $reverse = '' ) {
	$prev_text = _x( 'Previous Article <span class="nav-link-title">%title</span>', 'Previous link', 'wayfarer' );
	$next_text = _x( 'Next Article <span class="nav-link-title">%title</span>', 'Next link', 'wayfarer' );

	if ( 'reverse' === $reverse ) {
		the_post_navigation( array(
			'prev_text' => wayfarer_allowed_tags( $next_text ),
			'next_text' => wayfarer_allowed_tags( $prev_text ),
		) );
	} else {
		the_post_navigation( array(
			'prev_text' => wayfarer_allowed_tags( $prev_text ),
			'next_text' => wayfarer_allowed_tags( $next_text ),
		) );
	}
}
endif;

if ( ! function_exists( 'wayfarer_posts_navigation' ) ) :
/**
 * Display navigation to next/previous posts when applicable.
 *
 * @since 1.0.0
 *
 * @param array $args {
 *  Optional. Default posts navigation arguments. Default empty array.
 *
 *  @type string $class              HTML class to append to the posts container.
 *  @type string $prev_text          Anchor text to display in the previous posts link.
 *                                   Default 'Older'.
 *  @type string $next_text          Anchor text to display in the next posts link.
 *                                   Default 'Newer'.
 *  @type string $screen_reader_text Screen reader text for nav element.
 *                                   Default 'Posts navigation'.
 * }
 */
function wayfarer_posts_navigation( $args = array() ) {
	$args = wp_parse_args( $args, array(
		'class'              => '',
		'prev_text'          => esc_html__( 'Older', 'wayfarer' ),
		'next_text'          => esc_html__( 'Newer', 'wayfarer' ),
		'screen_reader_text' => esc_html__( 'Posts navigation', 'wayfarer' ),
	) );

	$args = apply_filters( 'wayfarer_posts_navigation_args', $args );

	$navigation = get_the_posts_navigation( $args );

	if ( ! empty( $args['class'] ) ) {
		// Inject a custom class into the container.
		$navigation = preg_replace(
			'/class=(?P<quote>[\'"])(.*?posts-navigation.*?)(?(quote)(?P=quote))/',
			'class=$1$2 ' . $args['class'] . '$1',
			$navigation
		);
	}

	echo $navigation; // phpcs:ignore
}
endif;

if ( ! function_exists( 'wayfarer_page_links' ) ) :
/**
 * Wrapper for wp_link_pages() to maintain consistent markup.
 *
 * @since 1.0.0
 */
function wayfarer_page_links() {
	if ( ! is_singular() ) {
		return;
	}

	wp_link_pages( array(
		'before'      => '<nav class="page-links"><h2 class="page-links-title">' . esc_html__( 'Pages', 'wayfarer' ) . '</h2>',
		'after'       => '</nav>',
		'link_before' => '<span class="page-links-number">',
		'link_after'  => '</span>',
		'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'wayfarer' ) . ' </span>%',
		'separator'   => '<span class="screen-reader-text">, </span>',
	) );
}
endif;

if ( ! function_exists( 'wayfarer_entry_title' ) ) :
/**
 * Display an entry title.
 *
 * Includes the link on archives.
 *
 * @since 1.0.0
 */
function wayfarer_entry_title() {
	$title = get_the_title();

	if ( empty( $title ) ) {
		return;
	}

	if ( ! is_singular() ) {
		$title = sprintf(
			'<a class="permalink" href="%1$s" rel="bookmark" itemprop="url">%2$s</a>',
			esc_url( get_the_permalink() ),
			$title
		);
	}

	printf( '<h1 class="entry-title" itemprop="headline">%s</h1>', wayfarer_allowed_tags( $title ) ); // phpcs:ignore
}
endif;

if ( ! function_exists( 'wayfarer_entry_author' ) ) :
/**
 * Display post author byline.
 *
 * @since 1.0.0
 */
function wayfarer_entry_author() {
	?>
	<span class="entry-author byline">
		<?php
		/* translators: %s: Author name */
		$output = sprintf( __( '<span class="sep">by</span> %s', 'wayfarer' ), wayfarer_get_entry_author() );
		echo wayfarer_allowed_tags( $output ); // phpcs:ignore
		?>
	</span>
	<?php
}
endif;

if ( ! function_exists( 'wayfarer_get_entry_author' ) ) :
/**
 * Retrieve entry author.
 *
 * @since 1.0.0
 *
 * @return string
 */
function wayfarer_get_entry_author() {
	return sprintf(
		'<span class="author vcard" itemprop="author" itemscope itemtype="https://schema.org/Person">%s</span>',
		wayfarer_get_entry_author_link()
	);
}
endif;

if ( ! function_exists( 'wayfarer_get_entry_author_link' ) ) :
/**
 * Retrieve entry author link.
 *
 * @since 1.0.0
 *
 * @return string
 */
function wayfarer_get_entry_author_link() {
	return sprintf(
		'<a class="url fn n" href="%1$s" rel="author" itemprop="url"><span itemprop="name">%2$s</span></a>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_html( get_the_author() )
	);
}
endif;

if ( ! function_exists( 'wayfarer_entry_date' ) ) :
/**
 * Display post date/time with link.
 *
 * @since 1.0.0
 */
function wayfarer_entry_date() {
	?>
	<span class="entry-date">
		<?php
		$html = sprintf(
			'<a href="%1$s" rel="bookmark">%2$s</a>',
			esc_url( get_permalink() ),
			wayfarer_get_entry_date()
		);

		/* translators: %s: Publish date */
		echo wayfarer_allowed_tags( sprintf( __( '<span class="sep">on</span> %s', 'wayfarer' ), $html ) ); // phpcs:ignore
		?>
	</span>
	<?php
}
endif;

if ( ! function_exists( 'wayfarer_get_entry_date' ) ) :
/**
 * Retrieve HTML with meta information for the current post-date/time.
 *
 * @since 1.0.0
 *
 * @param bool $updated Optional. Whether to print the updated time, too. Defaults to true.
 * @return string
 */
function wayfarer_get_entry_date( $updated = true ) {
	$time_string = '<time class="entry-time published" datetime="%1$s" itemprop="datePublished">%2$s</time>';

	// To appease rich snippets, an updated class needs to be defined.
	// Default to the published time if the post has not been updated.
	if ( $updated ) {
		if ( get_the_time( 'U' ) === get_the_modified_time( 'U' ) ) {
			$time_string .= '<time class="entry-time updated" datetime="%1$s">%2$s</time>';
		} else {
			$time_string .= '<time class="entry-time updated" datetime="%3$s" itemprop="dateModified">%4$s</time>';
		}
	}

	return sprintf(
		$time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);
}
endif;

if ( ! function_exists( 'wayfarer_entry_terms' ) ) :
/**
 * Display terms for a given taxonomy.
 *
 * @since 1.0.0
 *
 * @param array $taxonomies Optional. List of taxonomy objects with labels.
 */
function wayfarer_entry_terms( $taxonomies = array() ) {
	if ( ! is_singular() || post_password_required() ) {
		return;
	}

	echo wp_kses_post( wayfarer_get_entry_terms( $taxonomies ) );
}
endif;

if ( ! function_exists( 'wayfarer_get_entry_terms' ) ) :
/**
 * Retrieve terms for a given taxonomy.
 *
 * @since 1.0.0
 *
 * @param array       $taxonomies Optional. List of taxonomy objects with labels.
 * @param int|WP_Post $post       Optional. Post ID or object. Defaults to the current post.
 * @return string
 */
function wayfarer_get_entry_terms( $taxonomies = array(), $post = null ) {
	$default = array(
		'category' => esc_html__( 'Categories', 'wayfarer' ),
		'post_tag' => esc_html__( 'Tags', 'wayfarer' ),
	);

	// Set default taxonomies if empty or not an array.
	if ( ! $taxonomies || ! is_array( $taxonomies ) ) {
		$taxonomies = $default;
	}

	// Allow plugins and themes to override taxonomies and labels.
	$taxonomies = apply_filters( 'wayfarer_entry_terms_taxonomies', $taxonomies );

	// Return early if the taxonomies are empty or not an array.
	if ( ! $taxonomies || ! is_array( $taxonomies ) ) {
		return '';
	}

	$post   = get_post( $post );
	$output = '';

	// Get object taxonomy list to validate taxonomy later on.
	$object_taxonomies = get_object_taxonomies( get_post_type() );

	// Loop through each taxonomy and set up term list html.
	foreach ( (array) $taxonomies as $taxonomy => $label ) {
		// Continue if taxonomy is not in the object taxonomy list.
		if ( ! in_array( $taxonomy, $object_taxonomies, true ) ) {
			continue;
		}

		// Get term list.
		$term_list = get_the_term_list( $post->ID, $taxonomy, '', '<span>, </span>', '' );

		// Continue if there is not one or more terms in the taxonomy.
		if ( ! $term_list || ! wayfarer_theme()->template->has_multiple_terms( $taxonomy ) ) {
			continue;
		}

		if ( $label ) {
			$label = sprintf( '<h3 class="term-title">%s</h3>', $label );
		}

		// Set term list output html.
		$output .= sprintf(
			'<div class="term-group term-group--%1$s">%2$s%3$s</div>',
			esc_attr( $taxonomy ),
			$label,
			$term_list
		);
	}

	// Return if no term lists were created.
	if ( empty( $output ) ) {
		return '';
	}

	return sprintf( '<div class="entry-terms">%s</div>', $output );
}
endif;

/**
 * Display a post template name.
 *
 * @since 1.0.0
 */
function wayfarer_post_template_name() {
	$name = get_post_type();

	if ( 'post' === $name && has_post_format() ) {
		$name = get_post_format();
	}

	return apply_filters( 'wayfarer_post_template_name', $name );
}

/**
 * Display HTML classes for the posts container.
 *
 * @since 1.0.0
 *
 * @param string|array $classes One or more classes to add to the class list.
 */
function wayfarer_posts_class( $classes = array() ) {
	printf(
		' class="%s"',
		esc_attr( implode( ' ', wayfarer_get_posts_class( $classes ) ) )
	);
}

/**
 * Retrieve HTML classes for the posts container as an array.
 *
 * @since 1.0.0
 *
 * @param string|array $classes One or more classes to add to the class list.
 * @return array Array of classes.
 */
function wayfarer_get_posts_class( $classes = array() ) {
	if ( ! empty( $classes ) && ! is_array( $classes ) ) {
		// Split a string.
		$classes = preg_split( '#\s+#', $classes );
	}

	$classes[] = 'posts-container';

	return array_unique( apply_filters( 'wayfarer_posts_class', $classes ) );
}

/**
 * Determine if a page is the singular page of a registered type.
 *
 * @since 1.0.0
 *
 * @param string $type A registered page type.
 * @return bool
 */
function wayfarer_is_page_type( $type = '' ) {
	return wayfarer_theme()->page_types->is_type( $type );
}

/**
 * Determine if a page is the archive page of a registered type.
 *
 * @since 1.0.0
 *
 * @param string $type A registered page type.
 * @return bool
 */
function wayfarer_is_page_type_archive( $type = '' ) {
	return wayfarer_theme()->page_types->is_archive( $type );
}

/**
 * Determine if a page's content should be displayed in full-width.
 *
 * @since 1.0.0
 *
 * @return bool
 */
function wayfarer_is_full_width() {
	$is_full_width = is_page_template( array(
		'templates/full-width-page.php',
		'templates/grid-page.php',
	) );
	return (bool) apply_filters( 'wayfarer_is_full_width', $is_full_width );
}

/**
 * Whether the player is visible for the current request.
 *
 * @since 1.0.0
 *
 * @return bool
 */
function wayfarer_is_player_active() {
	static $is_active;

	if ( null === $is_active ) {
		$tracks = wayfarer_get_player_tracks();

		// Only visible if tracks have been assigned.
		$is_active = (bool) apply_filters( 'wayfarer_is_player_active', ! empty( $tracks ) );
	}

	return $is_active;
}

/**
 * Retrieve the tracks for the site-wide player.
 *
 * Uses values set by a filter, otherwise uses an option from the Customizer.
 *
 * @since 1.0.0
 * @see wp_get_playlist()
 *
 * @return array Array of tracks.
 */
function wayfarer_get_player_tracks() {
	return wayfarer_theme()->template->get_tracks( 'wayfarer_player', get_theme_mod( 'wayfarer_attachment_ids' ) );
}

/**
 * Retrieve featured content.
 *
 * @since 1.0.0
 *
 * @return array An array of WP_Post objects.
 */
function wayfarer_get_featured_posts() {
	return (array) apply_filters( 'wayfarer_get_featured_posts', array() );
}

/**
 * Determine if any featured posts are available.
 *
 * @since 1.0.0
 *
 * @param int $minimum The minimum number of featured posts that should be available.
 * @return bool Whether there are featured posts.
 */
function wayfarer_has_featured_posts( $minimum = 1 ) {
	$posts = wayfarer_get_featured_posts();
	return ( is_array( $posts ) && absint( $minimum ) <= count( $posts ) );
}

/**
 * Determine if a post has content.
 *
 * @since 1.0.0
 *
 * @param int|WP_Post $post_id Optional. Post ID or WP_Post object. Defaults to the current global post.
 * @return bool
 */
function wayfarer_has_content( $post_id = null ) {
	$post_id = empty( $post_id ) ? get_the_ID() : $post_id;
	$content = get_post_field( 'post_content', $post_id );
	return empty( $content ) ? false : true;
}

/**
 * Retrieve a WP Query object with pages for a specific page type.
 *
 * @since 1.0.0
 *
 * @param int|WP_Post $post Optional. Post ID or object. Defaults to the current post.
 * @param array       $args           Optional. Default WP Query arguments. Default empty array.
 * @return object WP Query
 */
function wayfarer_page_type_query( $post = 0, $args = array() ) {
	$post = get_post( $post );

	$args = wp_parse_args( $args, array(
		'post_type'      => 'page',
		'post_parent'    => $post->ID,
		'posts_per_page' => 50,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'no_found_rows'  => true,
	) );

	$args = apply_filters( 'wayfarer_page_type_query_args', $args );

	if ( 'date' === $args['orderby'] ) {
		$args['order'] = 'DESC';
	}

	return new WP_Query( $args );
}

/**
 * Display a notice when there aren't any page types available.
 *
 * @since 1.0.0
 */
function wayfarer_page_type_notice() {
	$notice = sprintf(
		/* translators: 1: Archive template label */
		_x( 'There are currently not any %1$s available.', 'archive template label', 'wayfarer' ),
		esc_html( get_the_title() )
	);

	if ( current_user_can( 'publish_posts' ) ) {
		$notice = sprintf(
			/* translators: there is a space at the beginning of this sentence. */
			_x( ' Create a <a href="%1$s">new page</a> with this page as its <a href="%2$s">parent</a>.', 'archive template label; create page link', 'wayfarer' ),
			esc_url( add_query_arg( 'post_type', 'page', admin_url( 'post-new.php' ) ) ),
			esc_url( 'https://en.support.wordpress.com/pages/page-options/#parent-page' )
		);
	}

	echo wayfarer_allowed_tags( wpautop( $notice ) ); // phpcs:ignore
}

/**
 * Display body schema markup.
 *
 * @since 1.0.0
 */
function wayfarer_body_schema() {
	$schema = '';

	if ( is_singular( 'post' ) || is_home() || is_category() || is_tag() ) {
		$schema = 'itemscope="itemscope" itemtype="https://schema.org/Blog"';
	} elseif ( is_author() ) {
		$schema = 'itemscope="itemscope" itemtype="https://schema.org/ProfilePage"';
	} elseif ( is_search() ) {
		$schema = 'itemscope="itemscope" itemtype="https://schema.org/SearchResultsPage"';
	} else {
		$schema = 'itemscope="itemscope" itemtype="https://schema.org/WebPage"';
	}

	echo apply_filters( 'wayfarer_body_schema', $schema );
}

/**
 * Display post schema markup.
 *
 * @since 1.0.0
 */
function wayfarer_post_schema() {
	$post_type = get_post_type();

	switch ( $post_type ) {
		case 'page':
			$schema = 'itemscope itemtype="https://schema.org/CreativeWork"';
			break;
		case 'post':
			$schema = 'itemscope itemtype="https://schema.org/BlogPosting" itemprop="blogPost"';
			break;
		default:
			$schema = '';
			break;
	}

	echo apply_filters( 'wayfarer_post_schema', $schema, $post_type );
}

if ( ! function_exists( 'wayfarer_allowed_tags' ) ) :
/**
 * Allow only the allowedtags array in a string.
 *
 * @since 1.0.0
 *
 * @link https://www.tollmanz.com/wp-kses-performance/
 *
 * @param  string $string The unsanitized string.
 * @return string         The sanitized string.
 */
function wayfarer_allowed_tags( $string ) {
	global $allowedtags;

	$theme_tags = array(
		'a'    => array(
			'class'    => true,
			'href'     => true,
			'itemprop' => true,
			'rel'      => true,
			'title'    => true,
		),
		'br'   => array(),
		'h2'   => array(
			'class' => true,
		),
		'p'    => array(),
		'span' => array(
			'class'     => true,
			'itemprop'  => true,
			'itemscope' => true,
			'itemtype'  => true,
		),
		'time' => array(
			'class'    => true,
			'datetime' => true,
			'itemprop' => true,
		),
	);

	return wp_kses( $string, array_merge( $allowedtags, $theme_tags ) );
}
endif;

if ( ! function_exists( 'wayfarer_credits' ) ) :
/**
 * Theme credits text.
 *
 * @since 1.0.0
 */
function wayfarer_credits() {
	printf( '<div class="credits">%s</div>', wayfarer_allowed_tags( wayfarer_get_credits() ) ); // phpcs:ignore
}
endif;

/**
 * Retrieve theme credits text.
 *
 * @since 1.0.0
 *
 * @return string
 */
function wayfarer_get_credits() {
	$text = sprintf(
		/* translators: %s: Author link */
		__( 'Powered by <a href="%s">AudioTheme</a>', 'wayfarer' ),
		'https://audiotheme.com/'
	);

	return apply_filters( 'wayfarer_credits', $text );
}

/**
 * Retrieve the ID of the page.
 *
 * @return int Post ID.
 */
function wayfarer_get_main_post_id() {
	$post_id = 0;

	if ( is_singular() ) {
		$post_id = get_the_ID();
	}

	if ( 'page' === get_option( 'show_on_front' ) ) {
		if ( is_front_page() ) {
			$post_id = (int) get_option( 'page_on_front' );
		} elseif ( is_home() ) {
			$post_id = (int) get_option( 'page_for_posts' );
		}
	}

	return apply_filters( 'wayfarer_main_post_id', $post_id );
}

/**
 * Determine if the page can show hero content.
 *
 * @since 1.0.0
 */
function wayfarer_is_hero_active() {
	return apply_filters( 'wayfarer_is_hero_active', is_singular() || is_home() );
}

/**
 * Determine if the page has hero content.
 *
 * @since 1.0.0
 */
function wayfarer_has_hero() {
	$content = '';

	if ( wayfarer_is_hero_active() ) {
		$content = wayfarer_get_hero_image();
	}

	return apply_filters( 'wayfarer_has_hero', ! empty( $content ) );
}

/**
 * Get hero background image.
 *
 * @since 1.0.0
 */
function wayfarer_get_hero_image() {
	$post_id = wayfarer_get_main_post_id();
	$image   = '';

	if ( ! empty( $post_id ) ) {
		$image = get_the_post_thumbnail_url( $post_id, 'wayfarer-hero' );
	}

	return apply_filters( 'wayfarer_hero_background_image', $image, $post_id );
}

/**
 * Display the current post title with optional content.
 *
 * @since 1.0.0
 *
 * @param string $before Optional. Content to prepend to the title.
 * @param string $after  Optional. Content to append to the title.
 */
function wayfarer_hero_title( $before = '', $after = '' ) {
	$title = wayfarer_get_hero_title();

	if ( ! empty( $title ) ) {
		echo $before . esc_html( $title ) . $after; // phpcs:ignore
	}
}

/**
 * Retrieve hero title.
 *
 * @since 1.0.0
 *
 * @return string
 */
function wayfarer_get_hero_title() {
	$post_id = wayfarer_get_main_post_id();
	$title   = '';

	if ( ! empty( $post_id ) ) {
		$title = get_the_title( $post_id );
	}

	if ( is_archive() || is_search() ) {
		$title = get_the_archive_title();
	}

	return apply_filters( 'wayfarer_hero_title', $title, $post_id );
}

/**
 * Display or retrieve the current post title with optional content.
 *
 * @since 1.0.0
 *
 * @param string $before Optional. Content to prepend to the content.
 * @param string $after  Optional. Content to append to the content.
 */
function wayfarer_hero_content( $before = '', $after = '' ) {
	$content = wayfarer_get_hero_content();

	if ( empty( $content ) ) {
		return;
	}

	echo wp_kses_post( $before . $content . $after );
}

/**
 * Retrieve hero content.
 *
 * @since 1.0.0
 *
 * @return string
 */
function wayfarer_get_hero_content() {
	$post_id = wayfarer_get_main_post_id();
	return apply_filters( 'wayfarer_hero_content', '', $post_id );
}

/**
 * Get clean URL without scheme or "www".
 *
 * @since 1.0.0
 *
 * @param string $url Website URL.
 * @return string Modified URL.
 */
function wayfarer_get_clean_url( $url ) {
	// In case scheme relative URI is passed, e.g., //www.google.com/.
	$url = trim( $url, '/' );

	// If scheme not included, prepend it.
	if ( ! preg_match( '#^http(s)?://#', $url ) ) {
		$url = 'http://' . $url;
	}

	$url_parts = wp_parse_url( $url );

	// Remove www.
	$url = preg_replace( '/^www\./', '', $url_parts['host'] );

	return $url;
}

/**
 * Counts the number of widgets in a specific sidebar.
 *
 * @param string $sidebar_id Sidebar identifier.
 * @return integer number of widgets in the sidebar.
 */
function wayfarer_get_sidebar_widget_count( $sidebar_id = '' ) {
	$count           = 0;
	$sidebar_widgets = wp_get_sidebars_widgets();

	if ( array_key_exists( $sidebar_id, $sidebar_widgets ) ) {
		$count = (int) count( (array) $sidebar_widgets[ $sidebar_id ] );
	}

	return $count;
}

/**
 * Retrieve mapped sidebar widget count.
 *
 * This is primarily useful for setting block grid columns for sidebars that
 * don't display to the side of the content on smaller screens.
 *
 * @param string $sidebar_id Sidebar ID.
 * @return int Mapped number of sidebar widgets.
 */
function wayfarer_get_mapped_sidebar_widget_count( $sidebar_id ) {
	$count = wayfarer_get_sidebar_widget_count( $sidebar_id );
	return ( $count >= 3 ) ? 3 : absint( $count );
}

/**
 * Display the post image.
 *
 * @since 1.0.0
 *
 * @param string|array $size Optional. The size of the image to return. Defaults to large.
 * @param array        $attr Optional. Attributes for the image tag.
 */
function wayfarer_the_post_image( $size = 'post-thumbnail', $attr = array() ) {
	echo wp_kses( wayfarer_get_the_post_image( $size, 0, $attr ), array(
		'img' => array(
			'src' => true,
			'alt' => true,
		),
	) );
}

/**
 * Retrieve the post image.
 *
 * @since 1.0.0
 *
 * @param string|array $size Optional. The size of the image to return. Defaults to large.
 * @param int|WP_Post  $post Optional. Post ID or object. Defaults to the current post.
 * @param array        $attr Optional. Attributes for the image tag.
 * @return string Image URL.
 */
function wayfarer_get_the_post_image( $size = 'post-thumbnail', $post = 0, $attr = array() ) {
	return wayfarer_theme()->post_media->get_image( $post, $size, $attr );
}

/**
 * Determine the CSS selector of the element that should be used to overlay the
 * hero content.
 *
 * @since 1.0.0
 *
 * @return string CSS selector
 */
function wayfarer_get_hero_overlay_selector() {
	$enable = get_theme_mod( 'enable_hero_overlay', '1' );

	// Return early if overlay is not enabled.
	if ( empty( $enable ) ) {
		return '';
	}

	$section_ids = array_keys( wayfarer_get_site_header_sections() );

	// Get hero position using its array key.
	$hero_key = array_search( 'hero', $section_ids, true );

	// Return early if the hero is the first item in array.
	if ( 0 === $hero_key ) {
		return '';
	}

	// Return the selector that is shown directly before the hero content.
	return $section_ids[ --$hero_key ];
}

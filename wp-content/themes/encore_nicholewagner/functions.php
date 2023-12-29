<? 
//add JSON to gigs
add_filter( 'audiotheme_prepare_gig_for_jsonld', function ($item) {
//add performer
  	  $item['performer'] = array(
			'@type'  => 'Person', // Organization, Person
			'name'   => 'Nichole Wagner',
			'sameAs' => 'https://www.nicholewagnermusic.com', // Wikipedia URL
		);
	//if no show poster default to image
		if ( has_post_thumbnail() ) {
			$item['image'] = get_the_post_thumbnail_url( $post, 'full' );
		}
		else {
			$item['image'] = ["https://nicholewagnermusic.com/wordpress/images/16x9/nichole_wagner_live_in_concert.jpg","https://nicholewagnermusic.com/wordpress/images/4x3/nichole_wagner_live_in_concert.jpg","https://nicholewagnermusic.com/wordpress/images/1x1/nichole_wagner_live_in_concert.jpg"];
}; 
//default to free if doesn't exist
$tickets_url = get_audiotheme_gig_tickets_url( $post );
		if ( ! empty( $tickets_url ) ) {
			$item['offers'] = array(
				'@type' => 'Offer',
				'url'   => esc_url( $tickets_url ),
			);
		}

		else {
				$item['offers'] = array(
				'@type' => 'Offer',
				'price'         => '0',
				'priceCurrency' => 'USD',
				'validFrom'    => date( DateTime::ATOM, strtotime( get_the_date( '', $post_id ) ) ),
				'url'         => get_permalink( $post ),

				'availability'  => 'inStock',

				);	
				};	

	return $item;
}, 10, 2 );

// Get the meta data to add to the Audiotheme Gig calendar permalink slug

add_filter( 'wp_unique_post_slug', 'custom_unique_post_slug', 10, 4 );
 
function custom_unique_post_slug( $slug, $post_ID, $post_status, $post_type ) {
    if ( $post_type == 'audiotheme_gig' )  {
        $post = get_post($post_ID);
        $showdate = get_post_meta( $post->ID, '_audiotheme_gig_datetime', true );
		$showtime  = strtotime($showdate);
		$showday   = date('d',$showtime);
		$showmonth = date('m',$showtime);
		$showyear  = date('Y',$showtime);
		
		 if ($showyear != '1970') {
        $prefix = $showyear . '-' . $showmonth . '-' . $showday .'-' ;
        if ( is_numeric($slug[0]) ) {
	        //don't re-run date logic if the slug starts with a number
} else {
   
        if ( empty($post->post_name) || $slug != $post->post_name  || (strripos($slug, $prefix) !== 0  ) ) {
		
         $slug = $prefix . $slug;
         $slug = str_replace("-1970-01-01","", $slug);
    		}
    		}
    		}
    }
    return $slug;
}

// change archive names
 function shows_archive_title( $title ) {

    if(is_post_type_archive('audiotheme_gig'))
        return 'Upcoming Shows';

    return $title;
}
add_filter( 'wp_title', 'shows_archive_title' );
add_filter( 'get_the_archive_title', 'shows_archive_title' );

//unbloat Elementor
add_action('wp_loaded', function () {
    if (is_admin()) {
        return; // Do not trigger in the Dashboard
    }

    ob_start( function ($htmlSource) {
        $srcContainsFormat = preg_quote('/elementor/assets/lib/swiper/swiper', '/');

	    $regExpPattern = '#<script[^>]*src(|\s+)=(|\s+)[^>]*'. $srcContainsFormat. '.*(>)#Usmi';

	    preg_match_all($regExpPattern, $htmlSource, $matchesSourcesFromTags, PREG_SET_ORDER);

	    if (isset($matchesSourcesFromTags[0][0])) {
	        $htmlSource = str_replace($matchesSourcesFromTags[0][0].'</script>', '', $htmlSource);
        }

	    return $htmlSource;
    } );
}, 1);

	// check for clear-cart get param to clear the cart, append ?clear-cart to any site url to trigger this
add_action( 'init', 'woocommerce_clear_cart_url' );
function woocommerce_clear_cart_url() {
	if ( isset( $_GET['clear-cart'] ) ) {
		global $woocommerce;
		$woocommerce->cart->empty_cart();
	}
}


/* Change Place order button */
add_filter( 'woocommerce_product_info_button_text', 'woocommerce_custom_product_info_button_text' ); 

function woocommerce_custom_product_info_button_text() {
    return __( 'Learn More', 'woocommerce' ); 
}

/*Woocommerce Hide Checkout for Free*/


function sv_free_checkout_fields() {
	
	// Bail we're not at checkout, or if we're at checkout but payment is needed
/*
	if ( function_exists( 'is_checkout' ) && ( ! is_checkout() || ( is_checkout() && WC()->cart->needs_payment() ) ) ) {
		return;
	}
*/

if ( WC()->cart->cart_contents_total != 0 )  {
		return;
	}
	
	//Change the Billing Details checkout label to Your Details
function wc_billing_field_strings( $translated_text, $text, $domain ) {
    switch ( $translated_text ) {
        case 'Billing details' :
            $translated_text = __( 'Confirm Download', 'woocommerce' );
            break;
    }
    return $translated_text;
}
add_filter( 'gettext', 'wc_billing_field_strings', 20, 3 );


/* Change Place order buttno */
add_filter( 'woocommerce_order_button_text', 'woo_custom_order_button_text' ); 

function woo_custom_order_button_text() {
    return __( 'Get Download', 'woocommerce' ); 
}

  
	// Remove the "Additional Info" order notes
	add_filter( 'woocommerce_enable_order_notes_field', '__return_false' );
	// Unset the fields we don't want in a free checkout
	function unset_unwanted_checkout_fields( $fields ) {
	
		// add or remove billing fields you do not want
		// list of fields: http://docs.woothemes.com/document/tutorial-customising-checkout-fields-using-actions-and-filters/#section-2
		$billing_keys = array(
			'billing_company',
			'billing_first_name',
			'billing_last_name',
			'billing_phone',
			'billing_address_1',
			'billing_address_2',
			'billing_city',
			'billing_postcode',
			'billing_country',
			'billing_state',
		);
		
		$account_keys = array(
			'account_username',
			'account_password',
			'account_password-2',
		);
		
		
		// unset each of those unwanted fields
		foreach( $billing_keys as $key ) {
			unset( $fields['billing'][$key] );
		}
				foreach( $account_keys as $key ) {
			unset( $fields['account'][$key] );
		}
		
		return $fields;
	}
	add_filter( 'woocommerce_checkout_fields', 'unset_unwanted_checkout_fields' );
	
	// A tiny CSS tweak for the account fields; this is optional
	function print_custom_css() {
		echo '<style>.col-1 {width:100% !important;} .col-2 {width:100% !important;} .woocommerce-info {display:none;} #billing_email_field {width:100%;} #order_review_heading {display:none;} .woocommerce-additional-fields {display:none;}</style>';
	}

	add_action( 'wp_head', 'print_custom_css' );
}
add_action( 'wp', 'sv_free_checkout_fields' );


/* woocommerce skip cart and redirect to checkout. */
/*
function woocommerce_skip_cart() {
	$checkout_url = WC()->cart->get_checkout_url();
	return $checkout_url;
}
add_filter ('woocommerce_add_to_cart_redirect', 'woocommerce_skip_cart');
*/

//Allow Jp2 images
function my_custom_mime_types( $mimes ) {
 
// New allowed mime types.
$mimes['svg'] = 'image/svg+xml';
$mimes['svgz'] = 'image/svg+xml';
$mimes['doc'] = 'application/msword';
$mimes['jp2'] = 'image/jp2';
$mimes['webp'] = 'image/webp';

// Optional. Remove a mime type.
unset( $mimes['exe'] );
 
return $mimes;
}
add_filter( 'upload_mimes', 'my_custom_mime_types' );

//** * Enable preview / thumbnail for webp image files.*/
function webp_is_displayable($result, $path) {
    if ($result === false) {
        $displayable_image_types = array( IMAGETYPE_WEBP );
        $info = @getimagesize( $path );

        if (empty($info)) {
            $result = false;
        } elseif (!in_array($info[2], $displayable_image_types)) {
            $result = false;
        } else {
            $result = true;
        }
    }

    return $result;
}
add_filter('file_is_displayable_image', 'webp_is_displayable', 10, 2);

?>
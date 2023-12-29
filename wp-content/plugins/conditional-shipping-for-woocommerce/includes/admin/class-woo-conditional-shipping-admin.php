<?php

/**
 * Prevent direct access to the script.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Woo_Conditional_Shipping_Admin {
  /**
   * Constructor
   */
  public function __construct() {
    add_filter( 'woocommerce_get_sections_shipping', array( $this, 'register_section' ), 10, 1 );

		add_action( 'woocommerce_settings_shipping', array( $this, 'output' ) );
		
		add_action( 'woocommerce_settings_save_shipping', array( $this, 'save_ruleset' ), 10, 0 );
		add_action( 'woocommerce_settings_save_shipping', array( $this, 'save_settings' ), 10, 0 );

    // Add admin JS
    add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
    
    // Add link to conditions to the plugins page
    add_filter( 'plugin_action_links_' . WOO_CONDITIONAL_SHIPPING_BASENAME, array( $this, 'add_conditions_link' ) );

		// Hide default settings from conditions settings
		// WooCommerce 3.6.2 at least has a bug which causes default shipping options to be output
		// without standard section
    add_filter( 'woocommerce_get_settings_shipping', array( $this, 'hide_default_settings' ), 100, 2 );
    
    // Admin AJAX action for toggling ruleset activity
    add_action( 'wp_ajax_wcs_toggle_ruleset', array( $this, 'toggle_ruleset' ) );
	}
	
  /**
   * Add conditions link to the plugins page.
   */
  public function add_conditions_link( $links ) {
    $url = admin_url( 'admin.php?page=wc-settings&tab=shipping&section=woo_conditional_shipping' );
    $link = '<a href="' . $url . '">' . __( 'Conditions', 'conditional-shipping-for-woocommerce' ) . '</a>';

    return array_merge( array( $link ), $links );
  }

  /**
   * Add admin JS
   */
  public function admin_enqueue_scripts() {
    // Only load on Conditional Shipping page to avoid JS conflicts
	  if ( ! isset( $_GET['section'] ) || $_GET['section'] !== 'woo_conditional_shipping' ) {
		  return;
	  }

    wp_enqueue_script( 'jquery-ui-autocomplete' );
    
    wp_enqueue_script( 'woo_conditional_shipping_admin_js', WOO_CONDITIONAL_SHIPPING_URL . 'admin/js/woo-conditional-shipping.js', [ 'jquery', 'wp-util', 'jquery-ui-sortable' ], WOO_CONDITIONAL_SHIPPING_ASSETS_VERSION );
    
    wp_enqueue_style( 'woo_conditional_shipping_admin_css', WOO_CONDITIONAL_SHIPPING_URL . 'admin/css/woo-conditional-shipping.css', [], WOO_CONDITIONAL_SHIPPING_ASSETS_VERSION );
    
    $ajax_url = add_query_arg( [
      'action' => 'wcs_toggle_ruleset',
    ], admin_url( 'admin-ajax.php' ) );

    wp_localize_script( 'woo_conditional_shipping_admin_js', 'woo_conditional_shipping', [
      'ajax_url' => $ajax_url,
      'nonces' => [
        'ruleset_toggle' => wp_create_nonce( 'wcs-toggle-ruleset' ),
      ]
    ] );
  }
  
  /**
   * Register section under "Shipping" settings in WooCommerce
   */
  public function register_section( $sections ) {
    $sections['woo_conditional_shipping'] = __( 'Conditions', 'conditional-shipping-for-woocommerce' );

    return $sections;
	}
	
  /**
   * Output conditions page
   */
  public function output() {
    global $current_section;
    global $hide_save_button;

    if ( 'woo_conditional_shipping' === $current_section ) {
			if ( isset( $_REQUEST['ruleset_id'] ) ) {
        $hide_save_button = true;

        if ( $_REQUEST['ruleset_id'] === 'new' ) {
          $ruleset_id = false;
        } else {
          $ruleset_id = wc_clean( wp_unslash( $_REQUEST['ruleset_id'] ) );
        }

        if ( $ruleset_id && isset( $_REQUEST['action'] ) && 'delete' === $_REQUEST['action'] ) {
          wp_delete_post( $ruleset_id, false );

          $url = admin_url( 'admin.php?page=wc-settings&tab=shipping&section=woo_conditional_shipping' );
          wp_safe_redirect( $url );
          exit;
        }

        $ruleset = new Woo_Conditional_Shipping_Ruleset( $ruleset_id );

        include 'views/ruleset.html.php';
      } else {
        $hide_save_button = true;

        $rulesets = woo_conditional_shipping_get_rulesets();
        
        $add_ruleset_url = admin_url( 'admin.php?page=wc-settings&tab=shipping&section=woo_conditional_shipping&ruleset_id=new' );

        $health = $this->health_check();
        
        include 'views/settings.html.php';
      }
    }
  }

  /**
   * Save general settings
   */
  public function save_settings() {
    global $current_section;
    
    if ( 'woo_conditional_shipping' === $current_section && isset( $_POST['wcs_settings'] ) ) {
      update_option( 'wcs_debug_mode', ( isset( $_POST['wcs_debug_mode'] ) && $_POST['wcs_debug_mode'] ) );
      update_option( 'wcs_disable_all', ( isset( $_POST['wcs_disable_all'] ) && $_POST['wcs_disable_all'] ) );

      // Ruleset ordering
      $ruleset_order = isset( $_POST['wcs_ruleset_order'] ) ? wc_clean( wp_unslash( $_POST['wcs_ruleset_order'] ) ) : '';
      $order = [];
      if ( is_array( $ruleset_order ) && count( $ruleset_order ) > 0 ) {
        $loop = 0;
        foreach ( $ruleset_order as $ruleset_id ) {
          $order[ esc_attr( $ruleset_id ) ] = $loop;
          $loop++;
        }
      }
      update_option( 'wcs_ruleset_order', $order );

      // Increments the transient version to invalidate cache.
      WC_Cache_Helper::get_transient_version( 'shipping', true );
    }
  }

	/**
	 * Save ruleset
	 */
	public function save_ruleset() {
		global $current_section;
    
    if ( 'woo_conditional_shipping' === $current_section && isset( $_POST['ruleset_id'] ) ) {
      $post = false;
      if ( $_POST['ruleset_id'] ) {
        $post = get_post( $_POST['ruleset_id'] );

        if ( ! $post && 'wcs_ruleset' !== get_post_type( $post ) ) {
          $post = false;
        }
      }

      if ( ! $post ) {
        $post_id = wp_insert_post( array(
          'post_type' => 'wcs_ruleset',
          'post_title' => wp_strip_all_tags( $_POST['ruleset_name'] ),
          'post_status' => 'publish',
        ) );

        $post = get_post( $post_id );
      } else {
        $post->post_title = wp_strip_all_tags( $_POST['ruleset_name'] );

        wp_update_post( $post, false );
      }

      $operator = isset( $_POST['wcs_operator'] ) ? $_POST['wcs_operator'] : 'and';
      update_post_meta( $post->ID, '_wcs_operator', $operator );

      $conditions = isset( $_POST['wcs_conditions'] ) ? $_POST['wcs_conditions'] : array();
      update_post_meta( $post->ID, '_wcs_conditions', $this->preprocess_conditions( array_values( (array) $conditions ) ) );

      $actions = isset( $_POST['wcs_actions'] ) ? $_POST['wcs_actions'] : array();
      update_post_meta( $post->ID, '_wcs_actions', array_values( (array) $actions ) );
      
      $enabled = ( isset( $_POST['ruleset_enabled'] ) && $_POST['ruleset_enabled'] ) ? 'yes' : 'no';
      update_post_meta( $post->ID, '_wcs_enabled', $enabled );

      $pro_features = isset( $_POST['wcs_pro_features'] ) ? (bool) $_POST['wcs_pro_features'] : false;
      update_option( 'wcs_pro_features', ($pro_features ? '1' : '0') );

      // Increments the transient version to invalidate cache.
      WC_Cache_Helper::get_transient_version( 'shipping', true );

      $url = add_query_arg( array(
        'ruleset_id' => $post->ID,
      ), admin_url( 'admin.php?page=wc-settings&tab=shipping&section=woo_conditional_shipping' ) );
      wp_safe_redirect( $url );
      exit;
    }
  }

  /**
   * Preprocess conditions
   */
  public function preprocess_conditions( $conditions ) {
    foreach ( $conditions as $key => $condition ) {
      if ( isset( $condition['coupon_ids'] ) && is_array( $condition['coupon_ids'] ) ) {
        $conditions[$key]['coupon_ids'] = array_values( array_unique( $condition['coupon_ids'] ) );
      }
    }

    return $conditions;
  }
  
  /**
   * Toggle reulset
   */
  public function toggle_ruleset() {
    check_ajax_referer( 'wcs-toggle-ruleset', 'security' );
    if ( ! current_user_can( 'manage_woocommerce' ) ) {
      http_response_code( 403 );
      die( 'Permission denied' );
    }

    $ruleset_id = $_POST['id'];

    $post = get_post( $ruleset_id );

    if ( $post && get_post_type( $post ) === 'wcs_ruleset' ) {
      $enabled = get_post_meta( $post->ID, '_wcs_enabled', true ) === 'yes';
      $new_status = $enabled ? 'no' : 'yes';
      update_post_meta( $post->ID, '_wcs_enabled', $new_status );

      // Increments the transient version to invalidate cache.
		  WC_Cache_Helper::get_transient_version( 'shipping', true );

      echo json_encode( array(
        'enabled' => ( get_post_meta( $post->ID, '_wcs_enabled', true ) === 'yes' ),
      ) );
      
      die;
    }

    http_response_code(422);
    die;
  }

  /**
   * Health check
   */
  private function health_check() {
    return array(
      'enables' => $this->health_check_enables(),
      'disables' => $this->health_check_disables(),
    );
  }

  /**
   * Check if there are disabled shipping methods in the rulesets
   * 
   * Conditional Shipping can only process shipping methods which are enabled
   * in the shipping zones
   */
  private function health_check_disables() {
    // Get all rulesets
    $rulesets = woo_conditional_shipping_get_rulesets( true );

    $shipping_method_actions = array(
      'enable_shipping_methods', 'disable_shipping_methods',
      'set_price', 'increase_price', 'decrease_price',
    );

    $disables = array();
    foreach ( $rulesets as $ruleset ) {
      foreach ( $ruleset->get_actions() as $action ) {
        if ( in_array( $action['type'], $shipping_method_actions, true ) && isset( $action['shipping_method_ids'] ) && is_array( $action['shipping_method_ids'] ) ) {
          foreach ( $action['shipping_method_ids'] as $instance_id ) {
            $instance = woo_conditional_shipping_method_get_instance( $instance_id );

            if ( $instance && ! $instance->is_enabled ) {
              $disables[] = array(
                'instance_id' => $instance_id,
                'zone_id' => $instance->zone_id,
                'ruleset' => $ruleset,
                'action' => $action,
              );
            }
          }
        }
      }
    }

    return $disables;
  }

  /**
   * Check for multiple "Enable shipping methods" for the same shipping method
   */
  private function health_check_enables() {
    // Get all rulesets
    $rulesets = woo_conditional_shipping_get_rulesets( true );

    // Check if there are overlapping "Enable shipping methods"
    $enables = array();
    foreach ( $rulesets as $ruleset ) {
      foreach ( $ruleset->get_actions() as $action ) {
        if ( $action['type'] === 'enable_shipping_methods' && isset( $action['shipping_method_ids'] ) && is_array( $action['shipping_method_ids'] ) ) {
          foreach ( $action['shipping_method_ids'] as $id ) {
            if ( ! isset( $enables[$id] ) ) {
              $enables[$id] = array();
            }

            $enables[$id][] = $ruleset->get_id();
          }
        }
      }
    }

    // Filter out if there is only one "Enable shipping methods" for a shipping method
    $enables = array_filter( $enables, function( $ruleset_ids ) {
      return count( $ruleset_ids ) > 1;
    } );

    return $enables;
  }

	/**
	 * Hide default settings from condition settings
	 */
	public function hide_default_settings( $settings, $section ) {
		if ( $section === 'woo_conditional_shipping' ) {
			return array();
		}

		return $settings;
	}
}

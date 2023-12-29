<?php
/*
Plugin Name: miniExtensions Gallery for Airtable
Plugin URI: https://miniextensions.com/embed-airtable-gallery-on-your-website/
Description: A gallery to display Airtable Records on your website.
Version: 1.0.5
Tested up to: 5.4.2
Author: miniExtensions
Author URI: https://miniextensions.com/
License: GPL2
*/

class MiniExtensionsGallery extends WP_Widget {

	// Main constructor
	public function __construct() {
		parent::__construct(
			'miniextension-gallery',
			__( 'miniExtensions Gallery for Airtable', 'text_domain' ),
			array(
				'customize_selective_refresh' => true,
			)
		);
	}

	// The widget form (for the backend )
	public function form( $instance ) {

		// Set widget defaults
		$defaults = array(
			'extensionDataId'    => isset( $instance['extensionDataId'] ) ? $instance['extensionDataId'] : '',
		);
		
		// Parse current settings with defaults
		extract( wp_parse_args( ( array ) $instance, $defaults ) ); ?>

		<?php // Widget Title ?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'extensionDataId' ) ); ?>"><?php _e( 'Extension ID', 'text_domain' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'extensionDataId' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'extensionDataId' ) ); ?>" type="text" value="<?php echo esc_attr( $extensionDataId ); ?>" />
		</p>

	<?php }

	// Update widget settings
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['extensionDataId']    = isset( $new_instance['extensionDataId'] ) ? wp_strip_all_tags( $new_instance['extensionDataId'] ) : '';
		return $instance;
	}

	// Display the widget
	public function widget( $args, $instance ) {

		extract( $args );

		// Check the widget options
		$extensionDataId    = isset( $instance['extensionDataId'] ) ? apply_filters( 'widget_title', $instance['extensionDataId'] ) : '';

		if ($extensionDataId) {
			echo $before_widget . '<div>' . '<div id="miniextensions-iframe-embed-' . $extensionDataId . '"></div><script src="https://us-central1-app-store-81d55.cloudfunctions.net/api/v1/iframe-embed/' . $extensionDataId . '.js?absoluteShareUrl=https%3A%2F%2Fapp.miniextensions.com%2Fcards-gallery%2F' . $extensionDataId . '"></script>' . '</div>' . $after_widget;
		}
	}

}

// Register the widget
function miniextension_Gallery_register_gallery_extension() {
	register_widget( 'MiniExtensionsGallery' );
}
add_action( 'widgets_init', 'miniextension_Gallery_register_gallery_extension' );

// Display the widget
function miniExtensions_Gallery_show_gallery($atts, $content) {
	$extensionDataId = $content;
	if (!$extensionDataId) {
		return '<div style="color: red">miniExtensions: Extension ID is missing</div>';
	} else {
		return '<div>' . '<div id="miniextensions-iframe-embed-' . $extensionDataId . '"></div><script src="https://us-central1-app-store-81d55.cloudfunctions.net/api/v1/iframe-embed/' . $extensionDataId . '.js?absoluteShareUrl=https%3A%2F%2Fapp.miniextensions.com%2Fcards-gallery%2F' . $extensionDataId . '"></script>' . '</div>';
	}
}

add_shortcode('miniextension-gallery', 'miniExtensions_Gallery_show_gallery');
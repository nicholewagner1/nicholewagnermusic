<?php
/**
 * Sortable control for the Customizer.
 *
 * @package Wayfarer
 * @since 1.0.0
 */

/**
 * Sortable Customizer control.
 *
 * @since 1.0.0
 */
class Wayfarer_Customize_Control_Sortable extends WP_Customize_Control {

	/**
	 * Control type.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $type = 'wayfarer-sortable';

	/**
	 * Enqueue control scripts.
	 *
	 * @since 1.0.0
	 */
	public function enqueue() {
		wp_enqueue_script(
			'wayfarer-customize-controls-sortable',
			get_template_directory_uri() . '/includes/controls/assets/js/customize-controls-sortable.js',
			array( 'customize-controls', 'jquery-ui-sortable', 'wp-backbone' ),
			'1.0.0',
			true
		);

		wp_enqueue_style(
			'wayfarer-customize-controls-sortable',
			get_template_directory_uri() . '/includes/controls/assets/css/customize-controls-sortable.css',
			array( 'dashicons' )
		);

		add_action( 'customize_controls_print_footer_scripts', array( $this, 'print_templates' ) );
	}

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @since 1.0.0
	 * @uses WP_Customize_Control::to_json()
	 */
	public function to_json() {
		parent::to_json();

		$this->json['choices'] = $this->get_choices();
	}

	/**
	 * Print an Underscore.js template to render the control.
	 *
	 * @since 1.0.0
	 */
	public function content_template() {
		?>
		<label>
			<# if ( data.label ) { #>
				<span class="customize-control-title activate-drag">{{ data.label }}</span>
			<# } #>

			<# if ( data.description ) { #>
				<span class="customize-control-description description">{{{ data.description }}}</span>
			<# } #>
		</label>
		<?php
	}

	/**
	 * Print JavaScript templates in the Customizer footer.
	 *
	 * @since 1.0.0
	 */
	public function print_templates() {
		?>
		<script type="text/html" id="tmpl-wayfarer-sortable-item">
			<# if ( data.title ) { #>
				<h4 class="wayfarer-sortable-item-title">
					<i class="wayfarer-sortable-item-icon dashicons dashicons-menu"></i> <span class="text">{{ data.title }}</span>
				</h4>
			<# } #>
		</script>
		<?php
	}

	/**
	 * Retrieve sortable items.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	protected function get_choices() {
		$data    = array();
		$choices = $this->choices;
		$value   = $this->value();

		if ( ! empty( $value ) ) {
			$value = explode( ',', $value );

			foreach ( $value as $choice ) {
				if ( ! isset( $choices[ $choice ] ) ) {
					continue;
				}

				$data[ $choice ] = array(
					'id'    => $choice,
					'title' => $choices[ $choice ],
				);
			}
		}

		// Append registered choices that weren't in the value array.
		$extra = array_diff_key( $choices, $data );

		foreach ( $extra as $id => $title ) {
			$data[ $id ] = array(
				'id'    => $id,
				'title' => $title,
			);
		}

		return array_values( $data );
	}
}

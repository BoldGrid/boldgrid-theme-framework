<?php
/**
 * Class: Boldgrid_Framework_Customizer_Edit
 *
 * Responsible for the edit buttons in customizer preview.
 *
 * @since 2.9
 * @link http://www.boldgrid.com.
 * @package bolgrid_theme_framework
 * @author BoldGrid <wpb@boldgrid.com>.
 */

/**
 * Class responsible for edit buttons in customizer.
 *
 * @since 2.9
 */
class Boldgrid_Framework_Customizer_Edit {

	/**
	 * Configs
	 *
	 * Array of theme configs
	 *
	 * @since 2.9
	 * @var   array
	 */
	public $configs = array();

	/**
	 * Edit Params
	 *
	 * Array of Edit button parameters.
	 *
	 * @since 2.9
	 * @var array
	 */
	public $edit_params = array();

	/**
	 * Constructor
	 *
	 * @since 2.9
	 * @param array $configs Configs array.
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
	}

	/**
	 * Generate Edit Parameters
	 *
	 * Creates an array of edit button parameters based on control configs.
	 *
	 * @since 2.9
	 */
	public function generate_edit_params() {
		if ( empty( $this->configs['customizer'] ) || empty( $this->configs['customizer']['controls'] ) ) {
			return;
		}

		foreach ( array( 'control', 'section', 'panel' ) as $control_type ) {
			if ( isset( $this->configs['customizer-options'] )
				&& isset( $this->configs['customizer-options']['edit'] )
				&& isset( $this->configs['customizer-options']['edit'][ $control_type . 's' ] ) ) {
				$controls = array_merge( $this->configs['customizer'][ $control_type . 's' ], $this->configs['customizer-options']['edit'][ $control_type . 's' ] );
			} else {
				$controls = $this->configs['customizer'][ $control_type . 's' ];
			}

			foreach ( $controls as $control_id => $control_params ) {
				$this->control_edit_params( $control_id, $control_params, $control_type );
			}
		}
	}

	/**
	 * Control Edit Params.
	 *
	 * Generates edit button paramaters for a control.
	 *
	 * @since 2.9.0
	 *
	 * @param string $control_id     ID of the control.
	 * @param array  $control_params The control parameters.
	 * @param string $control_type   The control type ( control, section, panel ).
	 */
	public function control_edit_params( $control_id, $control_params, $control_type ) {
		if ( empty( $control_params['edit_vars'] ) ) {
			return;
		}

		$edit_vars = $control_params['edit_vars'];
		foreach ( $edit_vars as $edit_var ) {
			$selector = $edit_var['selector'];
			if ( is_array( $selector ) ) {
				foreach ( $selector as $separate_selector ) {
					$this->append_selector( $separate_selector, $control_id, $control_type, $edit_var );
				}
			} else {
				$this->append_selector( $selector, $control_id, $control_type, $edit_var );
			}
		}
	}

	/**
	 * Append Selector.
	 *
	 * Appends a button to a selector.
	 *
	 * @since 2.9.0
	 *
	 * @param string $selector     Selector String.
	 * @param string $control_id   Control Id.
	 * @param string $control_type Control Type.
	 * @param array  $edit_var     Edit Button Params.
	 */
	public function append_selector( $selector, $control_id, $control_type, $edit_var ) {
		if ( ! isset( $this->edit_params[ $selector ] ) ) {
				$this->edit_params[ $selector ] = array();
			}

		$this->edit_params[ $selector ][ $control_id ] = array(
			'type'        => $control_type,
			'label'       => $edit_var['label'],
			'description' => $edit_var['description'],
		);
	}

	/**
	 * WP Enqueue Scripts
	 */
	public function wp_enqueue_scripts() {
		if ( is_customize_preview() ) {
			// Minify if script debug is off.
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			wp_register_script(
				'boldgrid-framework-customizer-edit-js',
				$this->configs['framework']['js_dir'] . 'customizer/edit' . $suffix . '.js',
				array( 'jquery' ),
				$this->configs['version']
			);

			/*
			 * Get the link to edit this page.
			 *
			 * The WordPress Customizer adds a filter to get_edit_post_link, which returns an empty
			 * string for all calls to get_edit_post_link. In order for us to get the appropriate
			 * link, we need to remove that filter, get our link, then add the filter back.
			 */
			remove_filter( 'get_edit_post_link', '__return_empty_string' );
			$edit_post_link = get_edit_post_link( get_the_ID() );
			add_filter( 'get_edit_post_link', '__return_empty_string' );

			wp_localize_script(
				'boldgrid-framework-customizer-edit-js',
				'boldgridFrameworkCustomizerEdit',
				array(
					'editPostLink' => $edit_post_link,
					'goThereNow'   => __( 'Go there now', 'bgtfw' ),
					'menu'         => esc_attr__( 'Menu', 'bgtfw' ),
					'params'       => $this->edit_params,
					'postType'     => get_post_type(),
					'widgetArea'   => esc_attr__( 'Widget Area', 'bgtfw' ),
				)
			);

			wp_enqueue_script( 'boldgrid-framework-customizer-edit-js' );

			wp_enqueue_style( 'wp-jquery-ui-dialog' );
			wp_enqueue_script( 'jquery-ui-dialog' );
			wp_enqueue_script( 'jquery-effects-bounce' );
		}
	}

	/**
	 * Include our partial template file.
	 * Get Edit Params.
	 *
	 * @since 2.9.0
	 */
	public function wp_footer() {
		if ( is_customize_preview() ) {
			include dirname( dirname( __FILE__ ) ) . '/partials/customizer-edit.php';
		}
	}

	/**
	 * Get Edit Params.
	 *
	 * @since 2.9
	 *
	 * @param string $selector Optional selector.
	 *
	 * @return array Edit Button Parameters.
	 */
	public function get_edit_parameters( $selector = null ) {
		if ( empty( $this->edit_params ) ) {
			$this->generate_edit_params();
		}

		if ( $selector ) {
			return isset( $this->edit_params[ $selector ] ) ? $this->edit_params[ $selector ] : array();
		}

		return $this->edit_params;
	}
}

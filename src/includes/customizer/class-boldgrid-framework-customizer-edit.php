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

		if ( isset( $this->configs['customizer-options'] ) && isset( $this->configs['customizer-options']['edit'] ) ) {
			$controls = array_merge( $this->configs['customizer']['controls'], $this->configs['customizer-options']['edit'] );
		} else {
			$controls = $this->configs['customizer']['controls'];
		}

		foreach ( $controls as $control_id => $control_params ) {
			if ( empty( $control_params['edit_vars'] ) ) {
				continue;
			}

			$edit_vars = $control_params['edit_vars'];
			$selector  = $edit_vars['selector'];

			if ( ! isset( $this->edit_params[ $selector ] ) ) {
				$this->edit_params[ $selector ] = array();
			}

			$this->edit_params[ $selector ][ $control_id ] = array(
				'label'       => $edit_vars['label'],
				'description' => $edit_vars['description'],
			);
		}

		error_log( 'edit_params: ' . json_encode( $this->edit_params ) );
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

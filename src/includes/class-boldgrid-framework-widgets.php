<?php
/**
 * Class: BoldGrid_Framework_Widgets
 *
 * This class contains additional functionality that widgets
 * utilize in a BoldGrid theme.
 *
 * @since      1.0.0
 * @package    Boldgrid_Framework
 * @subpackage Boldgrid_Framework_Widgets
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

// If this file is called directly, abort.
defined( 'WPINC' ) ? : die;

/**
 * Class: BoldGrid_Framework_Widgets
 *
 * Class responsible for widget configurations in the BoldGrid
 * Theme Framework.
 *
 * @since 1.0.0
 */
class Boldgrid_Framework_Widgets {

	/**
	 * The BoldGrid Theme Framework configurations.
	 *
	 * @since     1.0.0
	 * @access    protected
	 * @var       string     $configs       The BoldGrid Theme Framework configurations.
	 */
	protected $configs;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param     string $configs       The BoldGrid Theme Framework configurations.
	 * @since     1.0.0
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
	}

	/**
	 * Clear all Widget areas
	 *
	 * @since 1.0.0
	 */
	public function empty_widget_areas() {
		$auto_created = $this->configs['widget']['sidebars'];
		$all_widgets = get_option( 'sidebars_widgets' );

		foreach ( $all_widgets as $key => $widget ) {
			if ( ! empty( $auto_created[ $key ] ) ) {
				$all_widgets[ $key ] = array();
			}
		}

		/**
		 * The call to update_option returns true / false based on the success of the update.
		 * The call will fail if:
		 * 1. The first parameter, 'sidebars_widgets', is empty (which will never be).
		 * 2. The old value == the new value.
		 * 3. The SQL failed to update the database.
		 * In an obscure bug, the call below is failing because of scenario #2 above.
		 * Below, we'll try to fix this by emptying the value before setting it.
		 */
		update_option( 'sidebars_widgets', array() );
		update_option( 'sidebars_widgets', $all_widgets );
	}

	/**
	 * Create sidebars based on config file
	 * WP_Widget_Black_Studio_TinyMCE
	 *
	 * @since     1.0.0
	 */
	public function create_config_widgets() {

		foreach ( $this->configs['widget']['sidebars'] as $config ) {
			register_sidebar( $config );
		}
	}

	/**
	 * While viewing a widget areas in the customizer, wrap in a div that hold the widgets id.
	 *
	 * @since     1.0.0
	 */
	public function wrap_widget_areas() {
		$before_function = function ( $index, $filled = false ) {
			$filled_data = '';
			if ( false === $filled ) {
				$filled_data = 'data-empty-area="true"';
			}

			echo '<div data-widget-area="accordion-section-sidebar-widgets-' . esc_attr( $index ) . '" ' . $filled_data . '>';
		};

		$after_function = function ( $index, $filled = false ) {
			echo '</div>';
		};

		add_action( 'dynamic_sidebar_before',  $before_function, 10, 2 );
		add_action( 'dynamic_sidebar_after',  $after_function, 10, 2 );
	}
}

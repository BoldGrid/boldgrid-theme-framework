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
class BoldGrid_Framework_Widgets {

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
	 * Wrap BG widgets with additional markup.
	 *
	 * @param    array $params Additional parameters to wrap.
	 * @return   array
	 * @since    1.0.0
	 */
	public function wrap_bg_widgets( $params ) {
		$boldgrid_widgets = get_option( 'boldgrid_widgets_created', array() );
		foreach ( $params as &$param ) {
			if ( ! empty( $param['widget_id'] ) && in_array( $param['widget_id'], $boldgrid_widgets, true ) ) {
				if ( ! empty( $param['before_bg_widget'] ) && ! empty( $param['after_bg_widget'] ) ) {
					$param['before_widget'] = sprintf( $param['before_bg_widget'], $param['widget_id'], 'boldgrid-widget' );
					$param['after_widget'] = $param['after_bg_widget'];
				}
			}
		}

		return $params;
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
				$filled_data = "data-empty-area='true'";
			}
		
			echo '<div data-widget-area="accordion-section-sidebar-widgets-' .
				esc_attr( $index ) . '" ' .  esc_attr( $filled_data ) . '>';
		};
		
		$after_function = function ( $index, $filled = false ) {
			echo '</div>';
		};

		add_action( 'dynamic_sidebar_before',  $before_function, 10, 2 );
		add_action( 'dynamic_sidebar_after',  $after_function, 10, 2 );
	}

	/**
	 * Add Sidebar Widgets to BoldGrid Theme
	 *
	 * @since     1.0.0
	 */
	public function sidebar_widgets() {
		register_sidebar( array(
			'name'          => __( 'Sidebar #1', 'bgtfw' ),
			'id'            => 'sidebar-1',
			'description'   => '',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h1 class="widget-title">',
			'after_title'   => '</h1>',
		) );
	}
}

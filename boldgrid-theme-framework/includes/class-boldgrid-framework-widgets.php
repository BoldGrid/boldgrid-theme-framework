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
	 * Get the type of widget from the id
	 *
	 * @param string $id Id of widget to get the base ID of.
	 * @return string
	 * @since 1.0.0
	 */
	public function get_widget_id_base( $id ) {
		return preg_replace( '/-[0-9]+$/', '', $id );
	}

	/**
	 * Get the id num of a widget from widget string id
	 *
	 * @param string $id Id of widget to get the key of.
	 * @return array
	 * @since 1.0.0
	 */
	public function get_widget_key( $id ) {
		preg_match( '/-([0-9]+$)/', $id, $matches );
		return $matches;
	}

	/**
	 * Delete all widgets that were created automatically
	 *
	 * @since 1.0.0
	 */
	public function remove_saved_widgets() {
		// Remove only created widgets.
		// Grab all widget data and update in a temp array.
		$widgets = array();
		$sidebar_widgets = get_option( 'sidebars_widgets', array() );

		foreach ( get_option( 'boldgrid_widgets_created', array() ) as $widget_id ) {
			// Example: black-studio-tinymce-102.
			$widget_name = $this->get_widget_id_base( $widget_id );
			// Example: black-studio-tinymce.
			$widget_key = $this->get_widget_key( $widget_id );
			// Example: 102.
			$widget_key = $widget_key[1];

			// If we havn't grabbed the widgets of this type, for example $widgets['black-studio-tinymce'].
			if ( empty( $widgets[ $widget_name ] ) ) {
				// Then grab and set those widgets.
				$widgets[ $widget_name ] = get_option( 'widget_' . $widget_name, array() );
			}

			// Remove this widget from all widget areas, including inactive widgets.
			foreach ( $sidebar_widgets as $widget_area => $widgets_in_area ) {
				// If there are no widgets in this widget area, continue.
				if ( ! is_array( $sidebar_widgets[ $widget_area ] ) ) {
					continue;
				}

				// Search for our widget in this widget area. If it exists, remove it.
				$key = array_search( $widget_id, $sidebar_widgets[ $widget_area ] );
				if ( false !== $key ) {
					unset( $sidebar_widgets[ $widget_area ][ $key ] );
				}
			}

			// Unset the Widget Key.
			unset( $widgets[ $widget_name ][ $widget_key ] );
		}

		// Save the temp array of widget data.
		foreach ( $widgets as $widget_name => $widget_update_data ) {
			update_option( 'widget_' . $widget_name, $widget_update_data );
		}
		update_option( 'sidebars_widgets', $sidebar_widgets );
		$sidebar_widgets = get_option( 'sidebars_widgets', array() );

		// Clear cleanup storage.
		update_option( 'boldgrid_widgets_created', array() );
	}

	/**
	 * Set widget areas
	 *
	 * Can create multiple widgets in one area.
	 *
	 * @since 1.0.0
	 */
	public function set_widget_areas() {
		$auto_created_widget_ids = array();

		global $_wp_sidebars_widgets;
		global $wp_registered_widgets;

		$ids_created = array();

		foreach ( $this->configs['widget']['widget_instances'] as $location => $widget_single ) {
			if ( false === is_array( $widget_single ) ) {
				continue;
			}

			foreach ( $widget_single as $widget_data ) {
				if ( ! is_array( $widget_data ) || empty( $widget_data['label'] ) ) {
					continue;
				}

				// Create a "boldgrid_widgets_created" key for this widget based on widget title.
				$widget_key = ( isset( $widget_data['title'] ) ? $widget_data['title'] : 'unknown' );
				$widget_key = trim( strtolower( $widget_key ) );
				$widget_key = preg_replace( "/[^A-Za-z0-9]/", '_', $widget_key );

				$widget_label = $widget_data['label'];

				$widgets = get_option( 'widget_' . $widget_label );
				$widgets[] = $widget_data;
				end( $widgets );
				$counter = key( $widgets );

				update_option( 'widget_' . $widget_label, $widgets );

				$sidebar_widgets = get_option( 'sidebars_widgets', array() );
				$ids_created[] = $counter;
				$new_widget_id = "$widget_label-$counter";
				$sidebar_widgets[ $location ][] = $new_widget_id;
				$auto_created_widget_ids[$widget_key] = $new_widget_id;

				update_option( 'sidebars_widgets', $sidebar_widgets );

				$_wp_sidebars_widgets = $sidebar_widgets;
			}
		}

		/**
		 * TODO: Address this issue
		 * This is a hack fix to make sure that widgets display properly
		 * If we wanted to programmatically create any other type of widget, we would
		 * need to fix this issue
		 *
		 * The problem is that on first load widgets are not displaying. It takes 2 page laods for
		 * widgets to appear
		 *
		 * This issue is prominent on inspiration previews.
		 *
		 * @since 1.0.0
		 */
		foreach ( $ids_created as $id ) {
			$black_studio = new WP_Widget_Black_Studio_TinyMCE();
			$black_studio->id = 'black-studio-tinymce-' . $id;
			$black_studio->number = $id;
			$wp_registered_widgets[ "black-studio-tinymce-{$id}" ] = array(
				'name' => __( 'Visual Editor', 'bgtfw' ),
				'id' => 'black-studio-tinymce-' . $id,
				'callback' => array(
					$black_studio,
					'display_callback',
				),
				'params' => array(
					array(
						'number' => $id,
					),
				),
				'classname' => 'widget_black_studio_tinymce',
				'description' => __( 'Arbitrary text or HTML with visual editor', 'bgtfw' ),
			);
		}

		$widgets_created = get_option( 'boldgrid_widgets_created', array() );
		update_option( 'boldgrid_widgets_created', array_merge( $widgets_created, $auto_created_widget_ids ) );
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
			'before_title'  => '<h2 class="widget-title alt-font">',
			'after_title'   => '</h2>',
		) );
	}
}

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
			echo '<div data-widget-area="accordion-section-sidebar-widgets-' . esc_attr( $index ) . '" ' . $filled ? '' : 'data-empty-area="true"' . '>';
		};

		$after_function = function ( $index, $filled = false ) {
			echo '</div>';
		};

		add_action( 'dynamic_sidebar_before', $before_function, 10, 2 );
		add_action( 'dynamic_sidebar_after', $after_function, 10, 2 );
	}

	/**
	 * Adds CSS for hiding sidebar areas in the admin widgets.php page.
	 *
	 * @since 2.0.0
	 */
	public function admin_sidebar_display() {
		$inactive_sidebars = $this->get_inactive_column_sidebars( [ 'header', 'footer' ] );
		$css = $this->generate_css( $inactive_sidebars );

		wp_register_style( 'bgtfw-widgets-display', false );
		wp_enqueue_style( 'bgtfw-widgets-display' );
		wp_add_inline_style( 'bgtfw-widgets-display', $css );
	}

	/**
	 * Get inactive column sidebar IDs from theme_mods.
	 *
	 * @param array $types Widget area types.
	 *
	 * @return array $ids  Inactive widget column IDs.
	 */
	public function get_inactive_column_sidebars( $types ) {
		$ids = [];
		foreach ( $types as $type ) {
			$theme_mod = get_theme_mod( "bgtfw_{$type}_layout" );
			$columns = 0;

			foreach ( $theme_mod as $section ) {
				if ( ! empty( $section['items'] ) ) {
					foreach ( $section['items'] as $item ) {
						if ( false !== strpos( $item['type'], $type ) ) {
							$columns ++;
						}
					}
				}
			}

			$registered = array_filter( array_keys( $this->configs['widget']['sidebars'] ), function( $sidebar ) use ( $type ) {
				return 0 === strpos( $sidebar, $type );
			} );

			$max = count( $registered );
			$difference = $max - $columns;

			for ( $i = 0; $i < $difference; $i++ ) {
				$id = $max - $i;
				$ids[] = "{$type}-{$id}";
			}
		}

		return $ids;
	}

	/**
	 * Generate CSS to hide widget areas in the admin.
	 *
	 * @since 2.0.0
	 *
	 * @param array $sidebars Sidebar IDs to generate CSS for.
	 *
	 * @return string $css Generated CSS.
	 */
	public function generate_css( $sidebars ) {
		$css = '';
		$inactive_translated = wp_filter_nohtml_kses( __( 'Inactive', 'bgtfw' ) );
		if ( ! empty( $sidebars ) ) {
			foreach ( $sidebars as $sidebar ) {
				$css .= "#{$sidebar} .sidebar-name h2:after {
					content: \"{$inactive_translated}\";
					float: right;
					font-style: italic;
					font-weight: 400;
					font-size: .75em;
					color: red;
				}";
			}
		}

		return $css;
	}

	/**
	 * Sort the display of sidebars in widgets.php
	 *
	 * This sorts the sidebars, so all the inactive areas are
	 * moved to the end.
	 *
	 * @since 2.0.0
	 */
	public function sort_sidebars() {
		global $wp_registered_sidebars;
		$inactive_sidebars = $this->get_inactive_column_sidebars( [ 'header', 'footer' ] );

		// Alphabetical sort of inactive_sidebar IDs.
		sort( $inactive_sidebars );

		// Check each sidebar in global wp_registered_sidebars to see if it's inactive.
		uksort( $wp_registered_sidebars, function( $a, $b ) use ( $inactive_sidebars ) {
			$a = in_array( $a, $inactive_sidebars, true );
			$b = in_array( $b, $inactive_sidebars, true );

			return strcasecmp( $a, $b );
		} );

		// Set primary sidebar as first in array.
		$sidebars = [ 'primary-sidebar' => $wp_registered_sidebars['primary-sidebar'] ] + $wp_registered_sidebars;

		// Unregister all registered sidebars.
		foreach( $sidebars as $sidebar => $settings ) {
			unregister_sidebar( $sidebar );
		}

		// Register all sidebars in our custom order.
		foreach( $sidebars as $sidebar => $settings ) {
			register_sidebar( $settings );
		}
	}

	/**
	 * This takes each sidebar specified in the configs and creates
	 * and action to be used.
	 *
	 * @since 2.0.3
	 */
	public function add_dynamic_actions() {
		foreach ( $this->configs['widget']['sidebars'] as $widget ) {
			$action = function() use ( $widget ) {
				bgtfw_widget( $widget['id'], true );
			};

			// Add our dynamic actions we created, so they can be hooked into ( For example: 'bgtfw_sidebar_header-1' ).
			add_action( 'bgtfw_sidebar_' . $widget['id'], $action );
		}
	}
}

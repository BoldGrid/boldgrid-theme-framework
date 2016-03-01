<?php
/**
 * Class: Boldgrid_Framework_Activate
 *
 * This class contians code that will run on activation
 * of a theme that utilizes the BoldGrid Theme Framework.
 *
 * @since 1.0.0
 * @package Boldgrid_Framework
 * @subpackage Boldgrid_Framework_Activate
 * @author BoldGrid <support@boldgrid.com>
 * @link https://boldgrid.com
 */

/**
 * Class: Boldgrid_Framework_Activate
 *
 * This class contians code that will run on activation
 * of a theme that utilizes the BoldGrid Theme Framework.
 *
 * @since 1.0.0
 */
class Boldgrid_Framework_Activate {

	/**
	 * The BoldGrid Theme Framework configurations.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @var string $configs The BoldGrid Theme Framework configurations.
	 */
	protected $configs;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $configs The BoldGrid Theme Framework configurations.
	 * @since 1.0.0
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
	 * Delete menus created by framework.
	 *
	 * Menus created by the framework will be stored in the boldgrid_menus_created option. Simply
	 * iterate through each menu and delete it.
	 *
	 * @since 1.0.5
	 */
	public function remove_saved_menus() {
		// Get a list of all menus we've created.
		$menus_created = get_option( 'boldgrid_menus_created', array() );

		// If we haven't created any menus, abort.
		if ( empty( $menus_created ) ) {
			return;
		}

		// Delete each menu.
		foreach ( $menus_created as $menu_name ) {
			wp_delete_nav_menu( $menu_name );
		}

		// Reset the boldgrid_menus_created option.
		update_option( 'boldgrid_menus_created', array() );
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
	 * Reset The Boldgrid Theme Framework
	 * Removing any menu locations and widget locations
	 *
	 * @since 1.0.0
	 */
	public function reset() {
		$this->reset_nav_locations();
		$this->remove_saved_widgets();

		// Delete all 'default menus' created.
		$this->remove_saved_menus();

		// Delete Option indicating that the framework needs to be setup.
		delete_option( 'boldgrid_framework_init' );

		// Do action for 3rd party.
		do_action( 'boldgrid_theme_reset' );
	}

	/**
	 * Copy over menu locations to child theme
	 *
	 * @param array  $old Old menus in theme.
	 * @param string $new New menus in theme.
	 * @since 1.0.0
	 */
	public function transfer_menus( $old, $new = null ) {
		if ( is_child_theme() && $new ) {
			$old_theme_mods = get_option( 'theme_mods_' . $new->get_stylesheet() );
			$old_locations = ! empty( $old_theme_mods['nav_menu_locations'] ) ? $old_theme_mods['nav_menu_locations'] : null;

			if ( $old_locations ) {
				set_theme_mod( 'nav_menu_locations', $old_locations );
			}
		}
	}

	/**
	 * Activate Boldgrid theme framework
	 *
	 * @since 1.0.0
	 */
	public function do_activate() {
		if ( is_child_theme() ) {
			return;
		}

		// Before running the activation, run deactivate just to be sure.
		$this->reset();

		$this->empty_widget_areas();
		$this->set_widget_areas();

		// Create Default Menus.
		$boldgrid_framework_menu = new Boldgrid_Framework_Menu( $this->configs );
		$boldgrid_framework_menu->create_default_menus();

		// Then update the menu_check option to make sure this code only runs once.
		update_option( 'boldgrid_framework_init', true );

		// Do action for 3rd party.
		do_action( 'boldgrid_theme_activate' );
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
	 * Remove any theme mods that were transferred to this theme
	 *
	 * @since 1.0.0
	 */
	public function undo_theme_mod_transfer() {
		$accept = true;
		$data = wp_unslash( $_POST['data']['accept'] );

		if ( ! empty( $data ) ) {
			$accept = $data;
		}
		if ( false === $accept ) {
			$theme_mods = get_option( 'theme_mods_' . get_stylesheet(), array() );
			if ( ! empty( $theme_mods['transferred_theme_mods'] ) && is_array( $theme_mods['transferred_theme_mods'] ) ) {
				foreach ( $theme_mods['transferred_theme_mods'] as $theme_mod ) {
					unset( $theme_mods[ $theme_mod ] );
				}
				$theme_mods['transferred_theme_mods'] = array();
			}

			update_option( 'theme_mods_' . get_stylesheet(), $theme_mods );

			// Compile All SCSS again!
			$boldgrid_theme_helper_scss = new Boldgrid_Framework_SCSS( $this->configs );
			$boldgrid_theme_helper_scss->force_update_css();
		} else {
			// If accepted, reset the array of theme mods.
			set_theme_mod( 'transferred_theme_mods', array() );
		}

		wp_die();
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
				if ( empty( $widget_data['label'] ) ) {
					continue;
				}

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
				$auto_created_widget_ids[] = $new_widget_id;

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
	 * Reset Menu Locations.
	 *
	 * @since 1.0.0
	 */
	public function reset_nav_locations() {
		$locations = get_theme_mod( 'nav_menu_locations', array() );
		foreach ( $locations as $location_name => $menu_id ) {
			if ( 'primary' !== $location_name ) {
				$locations[ $location_name ] = 0;
			}
		}
		set_theme_mod( 'nav_menu_locations', $locations );
	}

	/**
	 * Dusavke Advanced Nav Options.
	 *
	 * Disable advanced navigation options that are in the menu
	 * section of the customizer.
	 *
	 * @since 1.0.0
	 */
	public function disable_advanced_nav_options() {
		$user = wp_get_current_user();

		update_user_option(
			$user->ID,
			'managenav-menuscolumnshidden',
			array(
				0 => 'link-target',
				1 => 'css-classes',
				2 => 'xfn',
				3 => 'description',
				4 => 'title-attr',
			),
			true
		);
	}
}

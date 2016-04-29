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
		$this->widgets = new Boldgrid_Framework_Widgets( $this->configs );
		$this->menus   = new Boldgrid_Framework_Menu( $this->configs );
	}

	/**
	 * Reset The Boldgrid Theme Framework
	 * Removing any menu locations and widget locations
	 *
	 * @since 1.0.0
	 *
	 * @param bool $active Are we resetting an active installation.
	 */
	public function reset( $active = true ) {

		$this->widgets->remove_saved_widgets();
		$this->menus->reset_nav_locations();
		$this->menus->remove_saved_menus( $active );

		// Delete Option indicating that the framework needs to be setup.
		delete_option( 'boldgrid_framework_init' );

		// Do action for 3rd party.
		do_action( 'boldgrid_theme_reset' );
	}

	/**
	 * Activate Boldgrid theme framework
	 *
	 * @since 1.0.0
	 */
	public function do_activate() {
		if ( $this->menus->is_user_child() ) {
			return;
		}

		// Before running the activation, run deactivate just to be sure.
		$this->reset();

		$this->widgets->empty_widget_areas();
		$this->widgets->set_widget_areas();

		// Create Default Menus.
		$this->menus->create_default_menus();

		// Then update the menu_check option to make sure this code only runs once.
		update_option( 'boldgrid_framework_init', true );

		// Do action for 3rd party.
		do_action( 'boldgrid_theme_activate' );
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
}

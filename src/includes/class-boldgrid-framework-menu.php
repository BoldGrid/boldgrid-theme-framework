<?php
/**
 * Class: BoldGrid_Framework_Menu
 *
 * This class is responsible for registering all menu locations,
 * setting the menu locations, and assigning menus to locations.
 *
 * @since      1.0.0
 * @package    Boldgrid_Framework
 * @subpackage Boldgrid_Framework_Menu
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

/**
 * Class: BoldGrid_Framework_Menu
 *
 * This class is responsible for registering all menu locations,
 * setting the menu locations, and assigning menus to locations.
 *
 * @since      1.0.0
 */
class Boldgrid_Framework_Menu {

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
	 * Delete menus created by framework.
	 *
	 * Menus created by the framework will be stored in the boldgrid_menus_created option. Simply
	 * iterate through each menu and delete it.
	 *
	 * @since 1.0.5
	 *
	 * @param bool $active Are we resetting an active installation.
	 */
	public function remove_saved_menus( $active ) {
		// Get a list of all menus we've created.
		if ( $active ) {
			$menus_created = get_option( 'boldgrid_menus_created', array() );
		} else {
			$menus_created = get_option( 'boldgrid_staging_boldgrid_menus_created', array() );
		}

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
	 * Disable Advanced Nav Options.
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

	/**
	 * This takes each menu location specified in the configs and allows it to be used
	 * depending on if the configs have set a location for the menu.
	 *
	 * @since     1.0.0
	 */
	public function add_dynamic_actions() {
		foreach ( $this->configs['menu']['prototype'] as $menu ) {
			$action = function () use ( $menu ) {
				if ( has_nav_menu( $menu['theme_location'] ) ) {
					wp_nav_menu( $menu );
				}
			};

			// Add action(boldgrid_menu_footer_center).
			add_action( $this->configs['menu']['action_prefix'] . $menu['theme_location'], $action );
		}
	}

	/**
	 * Register all of our menu locations at once.
	 *
	 * @since     1.0.0
	 */
	public function register_navs() {

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( $this->configs['menu']['locations'] );

	}

	/**
	 * BoldGrid displays menu items on a new installation of the theme
	 * automatically, so this will grab the menu items specified in the
	 * configs and set it to the corresponding menu locations.
	 *
	 * @since     1.0.0
	 */
	public function create_default_menus() {
		// Keep track of any menus we create.
		$boldgrid_menus_created = array();

		foreach ( $this->configs['menu']['default-menus'] as $menu_configs ) {

			// Menu name.
			$name = $menu_configs['label'];

			// Before creating the menu, make sure the menu name is unique.
			// If it is not unique, the menu will fail to be created.
			$name = $this->create_unique_menu_name( $name );

			// Create the menu.
			$menu_id = wp_create_nav_menu( $name );

			// Make sure the menu was created successfully.
			if ( ! is_wp_error( $menu_id ) ) {
				// Add this menu to our array of menus created.
				$boldgrid_menus_created[] = $name;

				// Get the menu object by its name.
				$menu = get_term_by( 'name', $name, 'nav_menu' );

				foreach ( $menu_configs['items'] as $configs ) {
					// Then add the actual link/ menu item and you do this for each item you want to add.
					wp_update_nav_menu_item( $menu->term_id, 0, $configs );
				}

				// Then you set the wanted theme location.
				$locations = get_theme_mod( 'nav_menu_locations' );
				$locations[ $menu_configs['location'] ] = $menu->term_id;
				set_theme_mod( 'nav_menu_locations', $locations );
			}
		}

		// Save the menus we created as an option.
		update_option( 'boldgrid_menus_created', $boldgrid_menus_created );
	}

	/**
	 * Create a unique menu name.
	 *
	 * If you attempt to create a menu with a menu name that already exists, the menu will fail to be
	 * created. This method will attempt to make a menu name unique if it is not already unique. The
	 * uniqueness is done by appending -# until we find a unique name, such as menu-name-2.
	 *
	 * @since 1.0.5
	 *
	 * @param string $name A potential menu name.
	 * @return string A unique menu name.
	 */
	public function create_unique_menu_name( $name ) {
		// Check to see if the menu exists.
		$menu_exists = is_nav_menu( $name );

		// If the menu does not exist, then we have a unique menu name.
		if ( ! $menu_exists ) {
			return $name;
		}

		// Make this menu name a unique name by appending 'dash number' to it. Ex: menu-name-2.
		for ( $x = 2; $x <= 100; $x++ ) {
			$new_menu_name = $name . '-' . $x;
			$menu_exists = is_nav_menu( $new_menu_name );

			if ( ! $menu_exists ) {
				return $new_menu_name;
			}
		}
	}

	/**
	 * Check if theme is child of specified parent theme.
	 *
	 * This will check the configs to see if the specified parent
	 * theme is the same as the theme's directory.
	 *
	 * @return boolean $is_child Returns true if theme is a child created by a user.
	 * @since 1.1.3
	 */
	public function is_user_child() {
		$parent_name = $this->configs['theme-parent-name'];
		$parent_stylesheet_name = strtolower( wp_get_theme( basename( get_template_directory() ) )->Name );
		$is_user_child = is_child_theme() && $parent_stylesheet_name !== $parent_name;
		return $is_user_child;
	}

	/**
	 * Copy over menu locations to child theme
	 *
	 * @param array  $old Old menus in theme.
	 * @param string $new New menus in theme.
	 * @since 1.0.0
	 */
	public function transfer_menus( $old, $new = null ) {
		if ( $this->is_user_child() && $new ) {
			$old_theme_mods = get_option( 'theme_mods_' . $new->get_stylesheet() );
			$old_locations = ! empty( $old_theme_mods['nav_menu_locations'] ) ? $old_theme_mods['nav_menu_locations'] : null;

			if ( $old_locations ) {
				set_theme_mod( 'nav_menu_locations', $old_locations );
			}
		}
	}
}

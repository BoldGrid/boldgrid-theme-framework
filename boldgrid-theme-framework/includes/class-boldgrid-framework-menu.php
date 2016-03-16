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
}

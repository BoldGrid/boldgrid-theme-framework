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

		/*
		 * Delete our menus.
		 *
		 * The boldgrid_menus_created option may be saved in 1 of 2 formats. The if conditional
		 * matchs the new format, the else matches the original format.
		 *
		 * Please see doc block of create_default_menus method for more info.
		 */
		if ( isset( $menus_created['option_version'] ) ) {
			unset( $menus_created['option_version'] );

			foreach ( $menus_created as $menu_id => $menu_key ) {
				wp_delete_nav_menu( $menu_id );
			}
		} else {
			foreach ( $menus_created as $menu_name ) {
				wp_delete_nav_menu( $menu_name );
			}
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
	 * @since 1.0.0
	 *
	 * @param array $args      Arguments to override BGTFW default configs for wp_nav_menu().
	 * @param array $add_class Array of wp_nav_menu args that are CSS class overrides.
	 */
	public function add_dynamic_actions( $args = array(), $add_class = array() ) {
		$bgtfw_menus = $this;
		foreach ( $this->configs['menu']['prototype'] as $menu ) {

			/**
			 * Optionally you can pass in $args to override the defaults being passed in during
			 * the dynamic instantiation.  Additionally if you pass in strings to override in
			 * $add_class, the string of CSS classes will be extended instead of overwritten. By
			 * default menu_class and container_class are able to be extended.  This can be useful
			 * for generating dynamic classes to add to menus, or even dynamic IDs for multiple
			 * new menu locations if needed.
			 *
			 * @link https://developer.wordpress.org/reference/functions/wp_nav_menu/
			 * See link for a list of arguments that can be passed to wp_nav_menu().
			 *
			 * @param array $args      Arguments to override BGTFW default configs for wp_nav_menu().
			 * @param array $add_class Array of wp_nav_menu args that are CSS class overrides.
			 */
			$action = function( $args, $add_class = array() ) use ( $menu, &$bgtfw_menus ) {

				// Combine classes in $args from hook, and merge the remaining items in array.
				$add_class = ( ! empty( $add_class ) && is_array( $add_class ) ) ? $add_class : array( 'menu_class', 'container_class' );
				$menu = $this->parse_nav_args( $args, $menu, $add_class );

				// Allow hamburgers at all menu locations.
				$this->add_hamburger( $menu );

				/*
				 * IF we're in the customizer and edit buttons are enabled:
				 * # Modify 'fallback_cb' and force the "edit button's fallback_cb".
				 * # Print the nav menu.
				 *
				 * ELSE:
				 * # Follow standard practice and print the nav menu if it's configured.
				 */
				if ( is_customize_preview() && true === $bgtfw_menus->configs['customizer-options']['edit']['enabled'] ) {
					$menu['fallback_cb'] = 'Boldgrid_Framework_Customizer_Edit::fallback_cb';
					wp_nav_menu( $menu );
				} elseif ( has_nav_menu( $menu['theme_location'] ) ) {
					wp_nav_menu( $menu );
				}
			};

			// Add our dynamic actions we created, so they can be hooked into ( For example: 'boldgrid_menu_main' ).
			add_action( $this->configs['menu']['action_prefix'] . $menu['theme_location'], $action, 10, 2 );
		}
	}

	/**
	 * Parses $args being passed to create dynamic actions for bgtfw menus
	 * to be inserted into, and calling wp_nav_menu.
	 *
	 * @since 2.0.0
	 *
	 * @see BoldGrid_Framework_Menu::add_dynamic_actions
	 *
	 * @param  array $args     Arguments passed in to parse.
	 * @param  array $defaults Default arguments to override.
	 * @param  array $classes  CSS classes to extend instead of override(add to existing CSS classes).
	 *
	 * @return array $defaults Merged and extended default configuration array.
	 */
	public function parse_nav_args( $args = array(), $defaults = array(), $classes = array() ) {

		// Parse query strings to an array.
		if ( is_string( $args ) ) {
			$args = wp_parse_args( $args );
		}

		if ( ! empty( $args ) && is_array( $args ) ) {

			// Classes to override.
			if ( ! empty( $classes ) ) {

				// Loop through each of the classes we wish to override.
				foreach ( $classes as $class ) {

					// Join existing bgtfw configs for menu classes passed in $args.
					if ( isset( $args[ $class ] ) && ! empty( $args[ $class ] ) ) {

						// Check for defaults from bgtfw configs.
						if ( isset( $defaults[ $class ] ) && ! empty( $defaults[ $class ] ) && is_string( $defaults[ $class ] ) ) {

							// Combine the strings to create menu with.
							$defaults[ $class ] = implode( ' ', array( $defaults[ $class ], $args[ $class ] ) );

							// Remove container_class from $args since it's been processed.
							unset( $args[ $class ] );
						}
					}
				}
			}

			// Merge in the remaining $defaults with passed $args.
			$defaults = wp_parse_args( $args, $defaults );
		}

		return $defaults;
	}

	/**
	 * Add hamburger menu to menu location.
	 *
	 * @since 2.0.0
	 */
	public function add_hamburger( $menu ) {
		$hamburger = get_theme_mod( 'bgtfw_menu_hamburger_' . $menu['theme_location'] );
		$hidden = get_theme_mod( 'bgtfw_menu_hamburger_' . $menu['theme_location'] . '_toggle' ) ? '' : 'hidden';
		?>
		<input id="<?php echo esc_attr( $menu['menu_id'] ); ?>-state" type="checkbox" <?php BoldGrid::add_class( $menu['theme_location'] . '_menu_hamburger_input', [ $hidden ] ); ?> />
		<label id="<?php echo esc_attr( $menu['menu_id'] ); ?>-btn" class="<?php echo esc_attr( $menu['menu_id'] ); ?>-btn" for="<?php echo esc_attr( $menu['menu_id'] ); ?>-state">
			<div id="<?php echo esc_attr( $menu['menu_id'] ); ?>-hamburger" <?php BoldGrid::add_class( $menu['theme_location'] . '_menu_hamburger', [ 'hamburger', $hamburger, $hidden ] ); ?>>
				<span class="hamburger-box">
					<span class="hamburger-inner"></span>
				</span>
			</div>
			<span class="screen-reader-text"><?php esc_html_e( 'Toggle menu visibility.', 'bgtfw' ); ?></span>
		</label>
		<?php
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
	 * As of version 1.3.1:
	 *
	 * The boldgrid_menus_created option is being saved in this format:
	 * [ menu id ] = [default-menus][key]   Example: [2984]='social'
	 * ... instead of this format:
	 * [ incrementing key ] = Menu name     Example: [0]='Social Media'
	 *
	 * This will make it easier to identify which menus we created and the id of those menu. Before
	 * making this change, if the user renamed the 'Social Media' menu, we would never be able to
	 * find it. Now we can find it by id.
	 *
	 * @since     1.0.0
	 */
	public function create_default_menus() {
		// Keep track of any menus we create.
		$boldgrid_menus_created = array();

		foreach ( $this->configs['menu']['default-menus'] as $key => $menu_configs ) {

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
				$boldgrid_menus_created[ $menu_id ] = $key;

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

		/*
		 * Set a flag to show we're saving this data in a different format. Please see comment in
		 * this method's doc block regarding "As of version 1.3.1".
		 */
		$boldgrid_menus_created['option_version'] = 2;

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

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
			 * @param array $args            Arguments to override BGTFW default configs for wp_nav_menu().
			 * @param array $add_class       Array of wp_nav_menu args that are CSS class overrides.
			 * @param bool  $force_print_nav Forces this nav location to be printed.
			 */
			$action = function( $args, $add_class = array() ) use ( $menu, &$bgtfw_menus ) {
				global $wp_customize;
				// Combine classes in $args from hook, and merge the remaining items in array.
				$add_class = ( ! empty( $add_class ) && is_array( $add_class ) ) ? $add_class : array( 'menu_class', 'container_class' );
				$menu = $this->parse_nav_args( $args, $menu, $add_class );
				// Allow hamburgers at all menu locations.
				$this->add_hamburger( $menu );

				// Set menu classes.
				$menu = $this->add_menu_bg( $menu );
				$menu = $this->add_menu_border( $menu );
				$menu = $this->add_menu_link( $menu );

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
					error_log( 'is_customize_preview(): ' . json_encode( $menu ) );
					wp_nav_menu( $menu );
				} elseif ( has_nav_menu( $menu['theme_location'] ) ) {
					error_log( 'has_nav_menu: ' . json_encode( $menu ) );
					wp_nav_menu( $menu );
				} elseif ( isset( $menu['menu'] ) ) {
					error_log( 'isset( $menu[menu]: ' . json_encode( $menu ) );
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
					if ( isset( $args[ $class ] ) ) {

						// Check for defaults from bgtfw configs.
						if ( isset( $defaults[ $class ] ) && is_string( $defaults[ $class ] ) ) {

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
	 *
	 * @param array $menu Menu location settings.
	 */
	public function add_hamburger( $menu ) {
		$hamburger = get_theme_mod( 'bgtfw_menu_hamburger_' . $menu['theme_location'] );
		$hidden    = ! get_theme_mod( 'bgtfw_menu_hamburger_' . $menu['theme_location'] . '_toggle' ) ? 'hidden' : '';
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
	 * Adds appropriate border class to register menu's UL elements.
	 *
	 * @since 2.0.0
	 *
	 * @param array $menu Menu location settings.
	 *
	 * @return array $menu Modfied menu location settings.
	 */
	public function add_menu_border( $menu ) {
		$color = get_theme_mod( 'bgtfw_menu_border_color_' . $menu['theme_location'] );
		$color = explode( ':', $color );
		$color = array_shift( $color );

		// Get array of current menu classes.
		$classes = explode( ' ', $menu['menu_class'] );

		if ( ! empty( $color ) ) {
			if ( strpos( $color, 'neutral' ) !== false ) {
				$classes[] = $color . '-border-color';
			} else {
				$classes[] = str_replace( '-', '', $color ) . '-border-color';
			}
		}

		// Convert back to string.
		$menu['menu_class'] = implode( ' ', $classes );

		return $menu;
	}


	/**
	 * Adds appropriate background class to register menu's UL elements.
	 *
	 * @since 2.0.0
	 *
	 * @param array $menu Menu location settings.
	 *
	 * @return array $menu Modfied menu location settings.
	 */
	public function add_menu_bg( $menu ) {
		$color = get_theme_mod( 'bgtfw_menu_background_' . $menu['theme_location'] );
		$color = explode( ':', $color );
		$color = array_shift( $color );

		// Get array of current menu classes.
		$classes = explode( ' ', $menu['menu_class'] );

		if ( ! empty( $color ) ) {
			if ( strpos( $color, 'neutral' ) !== false ) {
				$classes[] = $color . '-background-color';
			} else {
				$classes[] = str_replace( '-', '', $color ) . '-background-color';
			}
		}

		// Convert back to string.
		$menu['menu_class'] = implode( ' ', $classes );

		return $menu;
	}

	/**
	 * Adds appropriate background class to register menu's UL elements.
	 *
	 * @since 2.0.0
	 *
	 * @param array $menu Menu location settings.
	 *
	 * @return array $menu Modfied menu location settings.
	 */
	public function add_menu_link( $menu ) {
		$color = get_theme_mod( 'bgtfw_menu_items_link_color_' . $menu['theme_location'] );
		$color = explode( ':', $color );
		$color = array_shift( $color );

		$background_color = null;
		$is_transparent = strpos( $menu['menu_class'], 'transparent' ) !== false;

		// Check if a transparent BG has been applied.
		if ( $is_transparent ) {
			$background_color = 'header';

			if ( in_array( $menu['theme_location'], $this->configs['menu']['footer_menus'], true ) ) {
				$background_color = 'footer';
			}

			$background_color = get_theme_mod( "bgtfw_{$background_color}_color" );
		} else {
			$background_color = get_theme_mod( 'bgtfw_menu_background_' . $menu['theme_location'] );
		}

		// Get array of current menu classes.
		$classes = explode( ' ', $menu['menu_class'] );

		if ( ! empty( $color ) ) {
			if ( ! is_null( $background_color ) ) {
				$background_color = explode( ':', $background_color );
				$background_color = array_shift( $background_color );

				if ( strpos( $background_color, 'neutral' ) !== false ) {
					$classes[] = $background_color . '-background-color';
				} else {
					$classes[] = str_replace( '-', '', $background_color ) . '-background-color';
				}
			}

			$classes[] = $color . '-link-color';
		}

		// Convert back to string.
		$menu['menu_class'] = implode( ' ', $classes );

		return $menu;
	}

	/**
	 * Register all of our menu locations at once.
	 *
	 * @since     1.0.0
	 */
	public function register_navs() {
		$menus = $this->configs['menu']['locations'];

		// Handle deregistration/registration of locations when not in the customizer.
		if ( ! is_customize_preview() ) {
			$locations = [];
			$theme_mods = [];
			$theme_mods[] = BoldGrid::create_uids( 'bgtfw_header_layout' );
			$theme_mods[] = BoldGrid::create_uids( 'bgtfw_footer_layout' );
			$theme_mods[] = BoldGrid::create_uids( 'bgtfw_sticky_header_layout' );

			foreach ( $theme_mods as $theme_mod ) {
				foreach ( $theme_mod as $key => $section ) {
					if ( ! empty( $section['items'] ) ) {
						foreach ( $section['items'] as $item ) {
							if ( ! empty( $item['type'] ) ) {
								if ( false !== strpos( $item['type'], 'boldgrid_menu' ) ) {
									$locations[] = str_replace( 'boldgrid_menu_', '', $item['type'] );
								}
							}
						}
					}
				}
			}

			$locations = apply_filters( 'boldgrid_custom_menu_locations', $locations );

			if ( is_array( $this->configs['menu']['locations'] ) && ! empty( $locations ) ) {
				$menus = array_intersect_key( $this->configs['menu']['locations'], array_flip( $locations ) );
			}
		}

		// This theme uses wp_nav_menu() in one location.
		if ( ! empty( $menus ) ) {
			register_nav_menus( $menus );
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
}

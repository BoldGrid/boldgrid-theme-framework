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
		$edit_enabled = $this->configs['customizer-options']['edit']['enabled'];

		foreach ( $this->configs['menu']['prototype'] as $menu ) {
			$action = function () use ( $menu, $edit_enabled ) {
				/*
				 * IF we're in the customizer and edit buttons are enabled:
				 * # Modify 'fallback_cb' and force the "edit button's fallback_cb".
				 * # Print the nav menu.
				 *
				 * ELSE:
				 * # Follow standard practice and print the nav menu if it's configured.
				 */
				if ( is_customize_preview() && true === $edit_enabled ) {
					$menu['fallback_cb'] = 'Boldgrid_Framework_Customizer_Edit::fallback_cb';
					wp_nav_menu( $menu );
				} elseif ( has_nav_menu( $menu['theme_location'] ) ) {
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

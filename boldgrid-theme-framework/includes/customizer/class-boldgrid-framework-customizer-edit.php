<?php

/**
 * Functionality used within Customizer.
 *
 * @since 1.1
 * @link http://www.boldgrid.com.
 * @package Boldgrid_Inspiration.
 * @subpackage Boldgrid_Inspiration/includes.
 * @author BoldGrid <wpb@boldgrid.com>.
 */
class Boldgrid_Framework_Customizer_Edit {

	/**
	 * The BoldGrid Theme Framework configurations.
	 *
	 * @since     xxx
	 * @access    protected
	 * @var       string     $configs       The BoldGrid Theme Framework configurations.
	 */
	protected $configs;

	/**
	 * Feature switch.
	 *
	 * @since	1.1.6
	 * @access	public
	 * @var		bool
	 */
	public $enabled = false;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param     string $configs       The BoldGrid Theme Framework configurations.
	 * @since     xxx
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;

		$this->enabled = $configs['customizer-options']['edit']['enabled'];
	}

	/**
	 * Print an empty container for an empty nav.
	 *
	 * @since xxx
	 *
	 * @param array $menu An array of menu settings.
	 */
	public static function fallback_cb( $menu ) {
		printf( "<%s id='%s' class='empty-menu' data-theme-location='%s'></%s>",
			$menu['container'],
			$menu['container_id'],
			$menu['theme_location'],
			$menu['container']
		);
	}

	/**
	 * Return true for ALL has_nav_menu() calls.
	 *
	 * This is done however ONLY if we're in the customizer and edit_buttons are enabled.
	 *
	 * For further details as to why we're doing this, please see:
	 * Boldgrid_Framework_Menu::add_dynamic_actions
	 *
	 * @since 1.1.7
	 *
	 * @param  bool   $has_nav_menu Whether there is a menu assigned to a location.
	 * @param  string $location     Menu location.
	 * @return bool   $has_nav_menu
	 */
	public function has_nav_menu( $has_nav_menu, $location ) {
		if ( is_customize_preview() && true === $this->enabled ) {
			return true;
		} else {
			return $has_nav_menu;
		}
	}

	/**
	 * Enqueue scripts needed to add edit buttons to the customizer.
	 *
	 * Ideally, this method would hook into customize_preview_init. We need to get the page ID,
	 * which is not avaialable in that hook. Instead, we hook into wp_enqueue_scripts and check to
	 * see if we're in the is_customize_preview.
	 *
	 * @since 1.1
	 */
	public function wp_enqueue_scripts() {
		if ( is_customize_preview() && true === $this->enabled ) {
			// Minify if script debug is off.
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			$stylesheet = get_stylesheet();

			wp_register_script(
				'boldgrid-framework-customizer-edit-js',
				$this->configs['framework']['js_dir'] . 'customizer/edit' . $suffix . '.js',
				array( 'jquery' ),
				$this->configs['version']
			);

			wp_localize_script(
				'boldgrid-framework-customizer-edit-js',
				'boldgridFrameworkCustomizerEdit',
				array(
					'editPostLink'	=> get_edit_post_link( get_the_ID() ),
					'goThereNow'	=> __( 'Go there now', 'bgtfw' ),
					'widget'		=> __( 'Widget', 'bgtfw' ),
					'menu'			=> __( 'Menu', 'bgtfw' ),
					'buttons'		=> $this->configs['customizer-options']['edit']['buttons'],
				)
			);

			wp_enqueue_script( 'boldgrid-framework-customizer-edit-js' );

			wp_enqueue_style( 'wp-jquery-ui-dialog' );
			wp_enqueue_script( 'jquery-ui-dialog' );
			wp_enqueue_script( 'jquery-effects-bounce' );
		}
	}

	/**
	 * Include our partial template file.
	 *
	 * @since 1.1.6
	 */
	public function wp_footer() {
		if ( is_customize_preview() && true === $this->enabled ) {
			include dirname( dirname( __FILE__ ) ) . '/partials/customizer-edit.php';
		}
	}

	/**
	 * Ensure each menu location has a unique class.
	 *
	 * That unique classname will be LOCATION-menu-location.
	 *
	 * @since 1.1.6
	 *
	 * @param array $args Array of wp_nav_menu() arguments.
	 * @return array.
	 */
	public function wp_nav_menu_args( $args ) {
		if ( is_customize_preview() && true === $this->enabled && ! empty( $args['theme_location'] ) ) {
			$class = str_replace( '_', '-', $args['theme_location'] ) . '-menu-location';

			$args['container_class'] .= ' ' . $class;
		}

		return $args;
	}
}
<?php
/**
 * Class: Boldgrid_Framework_Customizer_Edit
 *
 * Responsible for the edit button functionality in customizer.
 *
 * @since 1.3
 * @link http://www.boldgrid.com.
 * @package Boldgrid_Inspiration.
 * @subpackage Boldgrid_Inspiration/includes.
 * @author BoldGrid <wpb@boldgrid.com>.
 */

/**
 * Class responsible for edit buttons in customizer.
 *
 * @since 1.3
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
	 * @since     1.3
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;

		$this->enabled = (bool) $configs['customizer-options']['edit']['enabled'];

		/*
		 * Disable edit icons based on $_GET['customize_messenger_channel'].
		 *
		 * According to remove_frameless_preview_messenger_channel(), that parameter is removed from
		 * the preview window when it is not in an iframe. So if we don't have this url parameter
		 * set, then we're not in the Customizer's iframe, so disable edit icons.
		 *
		 * In order to be compatible with both WP 4.6 and WP 4.7, we need to also take into
		 * consideration that $_GET['customize_changeset_uuid'] was not introduced until 4.7.
		 */
		if ( ! empty( $_GET['customize_changeset_uuid'] ) && empty( $_GET['customize_messenger_channel'] ) ) {
			$this->enabled = false;
		}
	}

	/**
	 * Print an empty container for an empty nav.
	 *
	 * @since 1.3
	 *
	 * @param array $menu An array of menu settings.
	 */
	public static function fallback_cb( $menu ) {
		printf( "<div id='%s' class='empty-menu' data-theme-location='%s'></div>",
			esc_attr( $menu['menu_id'] ),
			esc_attr( $menu['theme_location'] )
		);
	}

	/**
	 * Enqueue scripts needed to add edit buttons to the customizer.
	 *
	 * Ideally, this method would hook into customize_preview_init. We need to get the page ID,
	 * which is not avaialable in that hook. Instead, we hook into wp_enqueue_scripts and check to
	 * see if we're in the is_customize_preview.
	 *
	 * @since 1.3
	 */
	public function wp_enqueue_scripts() {
		if ( is_customize_preview() && true === $this->enabled ) {
			// Minify if script debug is off.
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			wp_register_script(
				'boldgrid-framework-customizer-edit-js',
				$this->configs['framework']['js_dir'] . 'customizer/edit' . $suffix . '.js',
				array( 'jquery' ),
				$this->configs['version']
			);

			/*
			 * Get the link to edit this page.
			 *
			 * The WordPress Customizer adds a filter to get_edit_post_link, which returns an empty
			 * string for all calls to get_edit_post_link. In order for us to get the appropriate
			 * link, we need to remove that filter, get our link, then add the filter back.
			 */
			remove_filter( 'get_edit_post_link', '__return_empty_string' );
			$edit_post_link = get_edit_post_link( get_the_ID() );
			add_filter( 'get_edit_post_link', '__return_empty_string' );

			wp_localize_script(
				'boldgrid-framework-customizer-edit-js',
				'boldgridFrameworkCustomizerEdit',
				array(
					'editPostLink'	=> $edit_post_link,
					'goThereNow'	=> __( 'Go there now', 'bgtfw' ),
					'menu'			=> esc_attr__( 'Menu', 'bgtfw' ),
					'config'		=> $this->configs['customizer-options']['edit'],
					'postType'      => get_post_type(),
					'widgetArea'    => esc_attr__( 'Widget Area', 'bgtfw' ),
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
	 * @return array $args Modfied wp_nav_menu() arguments.
	 */
	public function wp_nav_menu_args( $args ) {
		if ( is_customize_preview() && true === $this->enabled && ! empty( $args['theme_location'] ) ) {
			$class = str_replace( '_', '-', $args['theme_location'] ) . '-menu-location';

			$args['container_class'] .= ' ' . $class;
		}

		return $args;
	}
}

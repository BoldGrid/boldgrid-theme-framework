<?php
/**
 * Class: Boldgrid_Framework_Welcome
 *
 * @since      2.0.0
 * @package    Boldgrid_Framework
 * @subpackage Boldgrid_Framework_Welcome
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

// If this file is called directly, abort.
defined( 'WPINC' ) ? : die;

/**
 * Boldgrid_Framework_Welcome Class
 *
 * @since 2.0.0
 */
class Boldgrid_Framework_Welcome {

	/**
	 * The BoldGrid Theme Framework configurations.
	 *
	 * @since     2.0.0
	 * @access    protected
	 * @var       array $configs The BoldGrid Theme Framework configurations.
	 */
	protected $configs;

	/**
	 * Menu slug for welcome page.
	 *
	 * @since  2.0.0
	 * @access protected
	 * @var    string
	 */
	protected $menu_slug = 'crio-welcome';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 2.0.0
	 *
	 * @param array $configs The BoldGrid Theme Framework configurations.
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
	}

	/**
	 * Redirect to Welcome page after theme is activated.
	 *
	 * @since 2.0.0
	 */
	public function redirect_on_activation() {
		global $pagenow;

		if ( 'themes.php' === $pagenow && is_admin() && isset( $_GET['activated'] ) ) {
			wp_safe_redirect( admin_url( 'admin.php?page=' . $this->menu_slug ) );
			exit();
		}
	}

	/**
	 * Add menu item.
	 *
	 * @since 2.0.0
	 */
	public function add_admin_menu() {
		add_menu_page(
			__( 'BoldGrid Crio', 'bgtfw' ),
			__( 'BoldGrid Crio', 'bgtfw' ),
			'manage_options',
			$this->menu_slug,
			array( $this, 'page_welcome' ),
			'none',
			2
		);

		// Override first item and change it to: Welcome.
		add_submenu_page(
			$this->menu_slug,
			__( 'Welcome', 'bgtfw' ),
			__( 'Welcome', 'bgtfw' ),
			'manage_options',
			$this->menu_slug,
			array( $this, 'page_welcome' ),
			'none',
			2
		);

		add_submenu_page(
			$this->menu_slug,
			__( 'Registration', 'bgtfw' ),
			__( 'Registration', 'bgtfw' ),
			'manage_options',
			'boldgrid-connect.php',
			array( $this, 'page_welcome' )
		);

		add_submenu_page(
			$this->menu_slug,
			__( 'Starter Content', 'bgtfw' ),
			__( 'Starter Content', 'bgtfw' ),
			'manage_options',
			'crio-starter-content',
			array( $this, 'page_starter_content' )
		);
	}

	/**
	 * Set BoldGrid Crio > Registration as active menu item.
	 *
	 * Hook into library's Boldgrid\Library\Library\Page\Connect\addScripts action and add js to
	 * set 'Registration' as active menu item.
	 *
	 * @since 2.0.0
	 */
	public function connect_scripts() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_script(
			'boldgrid-framework-registration',
			$this->configs['framework']['js_dir'] . 'registration' . $suffix . '.js',
			array( 'jquery' ),
			$this->configs['version']
		);
	}

	/**
	 * Display starter content page.
	 *
	 * @since 2.0.0
	 */
	public function page_starter_content() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		// Enqueue style used for Welcome Panel on the Dashboard.
		wp_enqueue_style(
			'wp-dashboard',
			admin_url( 'css/dashboard' . $suffix . '.css' )
			);

		wp_enqueue_style( 'boldgrid-customizer-controls-base',
			$this->configs['framework']['css_dir'] . 'welcome.css' );

		$starter_content_plugins = new BoldGrid_Framework_Customizer_Starter_Content_Plugins( $this->configs );
		$starter_content_plugins->enqueue();

		include $this->configs['framework']['includes_dir'] . 'partials/starter-content.php';
	}

	/**
	 * Display Welcome page.
	 *
	 * @since 2.0.0
	 */
	public function page_welcome() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		// Enqueue style used for Welcome Panel on the Dashboard.
		wp_enqueue_style(
			'wp-dashboard',
			admin_url( 'css/dashboard' . $suffix . '.css' )
		);

		wp_enqueue_style( 'boldgrid-customizer-controls-base',
			$this->configs['framework']['css_dir'] . 'welcome.css' );

		$starter_content_plugins = new BoldGrid_Framework_Customizer_Starter_Content_Plugins( $this->configs );
		$starter_content_plugins->enqueue();

		$theme = wp_get_theme();

		include $this->configs['framework']['includes_dir'] . 'partials/welcome.php';
	}
}

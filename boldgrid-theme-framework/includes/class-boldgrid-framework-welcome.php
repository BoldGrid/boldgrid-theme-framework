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
			wp_safe_redirect( admin_url( '?page=' . $this->menu_slug ) );
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
			__( 'Crio', 'bgtfw' ),
			__( 'Crio', 'bgtfw' ),
			'manage_options',
			$this->menu_slug,
			array( $this, 'page_welcome' )
		);
	}

	/**
	 * Display Welcome page.
	 *
	 * @since 2.0.0
	 */
	public function page_welcome() {
		include $this->configs['framework']['includes_dir'] . 'partials/welcome.php';
	}
}

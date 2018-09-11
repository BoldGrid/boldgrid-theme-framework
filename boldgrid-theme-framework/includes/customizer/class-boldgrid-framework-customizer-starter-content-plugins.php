<?php
/**
 * Class: BoldGrid_Framework_Customizer_Starter_Content_Plugins
 *
 * This is used to install plugins required by a Starter Content set.
 *
 * @since      2.0.0
 * @category   Customizer
 * @package    Boldgrid_Framework
 * @subpackage Boldgrid_Framework_Customizer
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

/**
 * BoldGrid_Framework_Customizer_Starter_Content_Plugins
 *
 * @since 2.0.0
 */
class BoldGrid_Framework_Customizer_Starter_Content_Plugins {

	/**
	 * The BoldGrid Theme Framework configurations.
	 *
	 * @since  2.0.0
	 * @access protected
	 * @var    array     $configs The BoldGrid Theme Framework configurations.
	 */
	protected $configs;

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
	 * Install a starter content's plugins using tgm.
	 *
	 * @since 2.0.0
	 */
	public function tgmpa_bulk_install() {

		// Definition required to avoid Undefined index: hook_suffix in /wp-admin/includes/class-wp-screen.php.
		global $hook_suffix;
		$hook_suffix = 'bgtfw_install_plugins';

		require_once( ABSPATH . '/wp-admin/includes/class-wp-upgrader-skin.php' );
		require_once( ABSPATH . '/wp-admin/includes/class-bulk-upgrader-skin.php' );

		$tgm = new TGMPA_List_Table();
		$tgm->process_bulk_actions();
	}
}

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
	 * Whether or not we are in an ajax call trying to either (1)install or (2)activate starter
	 * content plugins.
	 *
	 * @since  2.0.0
	 * @access private
	 * @var    bool
	 */
	private $in_ajax_call = false;

	/**
	 * Our $_POST['action'].
	 *
	 * @since  2.0.0
	 * @access private
	 * @var    string
	 */
	private $action = null;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 2.0.0
	 *
	 * @param array $configs The BoldGrid Theme Framework configurations.
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;

		$page = ! empty( $_GET['page'] ) ? $_GET['page'] : null;

		$this->action = ! empty( $_POST['action'] ) ? $_POST['action'] : null;

		$this->in_ajax_call =
			is_admin() &&
			defined( 'DOING_AJAX' ) &&
			'bgtfw-install-plugins' === $page &&
			in_array( $this->action, array( 'tgmpa-bulk-install', 'tgmpa-bulk-activate' ), true );
	}

	/**
	 * Helper function to extract the file path of the plugin file from the plugin slug, if the plugin
	 * is installed.
	 *
	 * THIS METHOD STOLEN FROM TGM.
	 *
	 * @since 2.0.0
	 *
	 * @param  string $slug Plugin slug (typically folder name) as provided by the developer.
	 * @return string       Either file path for plugin if installed, or just the plugin slug.
	 */
	public static function get_plugin_basename_from_slug( $slug ) {
		$plugins = get_plugins();

		foreach ( array_keys( $plugins ) as $key ) {
			if ( preg_match( '|^' . $slug . '/|', $key ) ) {
				return $key;
			}
		}

		return $slug;
	}

	/**
	 * Get a starter content set's plugin info.
	 *
	 * Determine which plugins need to be installed, and which need to be activated.
	 *
	 * @since 2.0.0
	 *
	 * @param  array Starter content plugin config.
	 * @return array
	 */
	public static function get_plugin_info( $starter_content_plugins ) {
		$data = array(
			'to_install' => array(),
			'to_activate' => array(),
		);

		if( $starter_content_plugins ) {
			$plugins = get_plugins();
			$active_plugins = get_option( 'active_plugins', array() );

			foreach( $starter_content_plugins as $plugin ) {
				$path = self::get_plugin_basename_from_slug( $plugin['slug'] );

				$is_installed = array_key_exists( $path, $plugins );
				$is_active = in_array( $path, $active_plugins, true );

				if( ! $is_installed ) {
					$data['to_install'][] = $plugin['slug'];
				}

				if( ! $is_active ) {
					$data['to_activate'][] = $plugin['slug'];
				}
			}
		}

		return $data;
	}

	/**
	 * Actions to take after plugins have been activated.
	 *
	 * @since 2.0.0
	 */
	public function post_activate() {
		$post_activate_actions = ! empty( $this->configs['starter-content']['plugins_post_activate'] ) ? $this->configs['starter-content']['plugins_post_activate'] : array();

		foreach( $post_activate_actions as $action => $value ) {

			// For now, the only action configurable is delete_transient.
			switch( $action ) {
				case 'delete_transient':
					delete_transient( $value );
					break;
			}
		}
	}

	/**
	 * Install a starter content's plugins using tgm.
	 *
	 * @since 2.0.0
	 */
	public function tgmpa_bulk_install() {
		if( ! $this->in_ajax_call ) {
			wp_send_json_error( __( 'Not allowed', 'bgtfw' ) );
		}

		// Definition required to avoid Undefined index: hook_suffix in /wp-admin/includes/class-wp-screen.php.
		global $hook_suffix;
		$hook_suffix = 'bgtfw_install_plugins';

		require_once( ABSPATH . '/wp-admin/includes/class-wp-upgrader-skin.php' );
		require_once( ABSPATH . '/wp-admin/includes/class-bulk-upgrader-skin.php' );

		$tgm = new TGMPA_List_Table();
		$tgm->process_bulk_actions();

		// Post activate actions.
		if( 'tgmpa-bulk-activate' === $this->action ) {
			$this->post_activate();
		}

		wp_send_json_success();
	}

	/**
	 * Make sure tgmpa loads in ajax calls.
	 *
	 * @since 2.0.0
	 *
	 * @param  bool $load Whether or not to load tgmpa in ajax.
	 * @return true
	 */
	public function tgmpa_load( $load ){
		if( $this->in_ajax_call ) {
			$load = true;
		}

		return $load;
	}

	/**
	 * Register tgmpa.
	 *
	 * @since 2.0.0
	 */
	public function tgmpa_register() {
		if( ! $this->in_ajax_call ) {
			return;
		}

		$config = array(
			'id' => 'bgtfw_starter_content',
		);

		tgmpa( $this->configs['starter-content']['plugins'], $config );
	}
}

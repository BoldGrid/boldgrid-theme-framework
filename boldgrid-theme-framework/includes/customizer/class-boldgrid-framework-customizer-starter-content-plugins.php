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
	 * Determine whether or not the tgmpa plugins defined by the bgtfw should be registered.
	 *
	 * We don't want to register them in the actual ajax calls made to this method to install plugins.
	 * See defintion of filter for more info.
	 *
	 * @since 2.0.0
	 *
	 * @param  bool $register Whether or not to register.
	 * @return bool
	 */
	public function bgtfw_register_tgmpa( $register ) {
		if ( $this->in_ajax_call ) {
			$register = false;
		}

		return $register;
	}

	/**
	 * Enqueue scripts needed for installing starter content plugins.
	 *
	 * Currently starter content can be installed from either the "Welcome" page or the "Starter Content"
	 * page.
	 *
	 * @since 2.0.0
	 */
	public function enqueue() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		$handle = 'bgtfw-customizer-starter-content-plugins';
		wp_register_script(
			$handle,
			$this->configs['framework']['js_dir'] . 'customizer/starter-content-plugins' . $suffix . '.js',
			array( 'jquery' ),
			$this->configs['version']
		);
		// We need to know which plugins need to be install, and which need to be activated.
		$starter_content_plugins = ! empty( $this->configs['starter-content']['plugins'] ) ? $this->configs['starter-content']['plugins'] : array();
		$translations = array(
			'pluginData' => self::get_plugin_info( $starter_content_plugins ),
			'NoResponseInstall' => '<div class="error">' . esc_html__( 'No response from server when attempting to install plugins.', 'bgtfw' ) . '</div>',
			'NoResponseActivate' => '<div class="error">' . esc_html__( 'No response from server when attempting to activate plugins.', 'bgtfw' ) . '</div>',
			'unknownPostError' => '<div class="error">' . esc_html__( 'Unknown error after activating plugins.', 'bgtfw' ) . '</div>',
			'bulkPluginsNonce' => wp_create_nonce( 'bulk-plugins' ),
		);
		wp_localize_script( $handle, 'bgtfwCustomizerStarterContentPlugins', $translations );
		wp_enqueue_script( $handle );
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
	 * @param  array $starter_content_plugins Starter content plugin config.
	 * @return array
	 */
	public static function get_plugin_info( $starter_content_plugins ) {
		$data = array(
			'to_install' => array(),
			'to_activate' => array(),
			'installed' => array(),
			'activated' => array(),
		);

		if ( $starter_content_plugins ) {
			$data['installed'] = get_plugins();
			$data['activated'] = get_option( 'active_plugins', array() );

			foreach ( $starter_content_plugins as $plugin ) {
				$path = self::get_plugin_basename_from_slug( $plugin['slug'] );

				$is_installed = array_key_exists( $path, $data['installed'] );
				$is_active = in_array( $path, $data['activated'], true );

				if ( ! $is_installed ) {
					$data['to_install'][] = $plugin['slug'];
				}

				if ( ! $is_active ) {
					$data['to_activate'][] = $plugin['slug'];
				}
			}
		}

		return $data;
	}

	/**
	 * Whether or not plugin setup is complete.
	 *
	 * This method returns true when all Starter Content plugins are both installed and activated.
	 *
	 * @since 2.0.0
	 *
	 * @param  array $starter_content_plugins An array containing required plugins for Starter Content.
	 * @return bool
	 */
	public static function is_setup_complete( $starter_content_plugins = array() ) {
		$plugin_info = self::get_plugin_info( $starter_content_plugins );

		$all_installed = empty( $plugin_info['to_activate'] );
		$all_activated = empty( $plugin_info['to_install'] );

		return $all_installed && $all_activated;
	}

	/**
	 * Actions to take after plugins have been activated.
	 *
	 * @since 2.0.0
	 */
	public function post_activate() {
		$post_activate_actions = ! empty( $this->configs['starter-content']['plugins_post_activate'] ) ? $this->configs['starter-content']['plugins_post_activate'] : array();

		foreach ( $post_activate_actions as $action => $value ) {

			// For now, the only action configurable is delete_transient.
			switch ( $action ) {
				case 'delete_transient':
					delete_transient( $value );
					break;
			}
		}
	}

	/**
	 * Actions to take after Starter Content plugins have been installed and activated.
	 *
	 * Within the dashboard when a user clicks to install starter content, we make 3 ajax calls before
	 * redirecting to the Customizer. The first 2 ajax calls are to (1) install any required plugins
	 * and (2) activate those plugins. The third ajax call is made to this action, bgtfw-post-plugin-setup,
	 * which handles any final actions BEFORE the user is finally redirected to the Customizer.
	 *
	 * @since 2.0.0
	 */
	public function post_plugin_setup() {
		if ( ! check_ajax_referer( 'bulk-plugins', '_wpnonce', false ) ) {
			wp_die( sprintf(
				'<div class="error">%1$s</div>',
				esc_html__( 'Access denined running post plugin activcation scripts.', 'bgtfw' )
			));
		}

		/*
		 * In order for any starter content to be installed, we need to have a fresh site.
		 * Please see: https://github.com/WordPress/WordPress/blob/master/wp-includes/class-wp-customize-manager.php#L588-L595
		 */
		update_option( 'fresh_site', '1' );

		/**
		 * Take action before any starter content is installed.
		 *
		 * Any required plugins for the starter content have already been installed.
		 *
		 * @since 2.0.0
		 */
		do_action( 'bgtfw_pre_load_starter_content' );

		/*
		 * The prior 2 ajax calls before this used tgmpa to install / acticated plugins. Those requests
		 * were not clean ajax requests, and did not use wp_send_json_success or wp_send_json_error.
		 * They instead echoed the results of the plugin installer skin.
		 *
		 * This 3rd ajax request therefore is following the same structure. If there is an error, like
		 * in the invalid nonce above, we die with a "<div class='error'>". If a response is given
		 * and no error classes are found, then we've got success. This is why we are dying with a
		 * string below.
		 */
		wp_die( 'Success' );
	}

	/**
	 * Install a starter content's plugins using tgm.
	 *
	 * This method is called via AJAX. Nonce verification is handled in $tgm->process_bulk_actions.
	 *
	 * @since 2.0.0
	 */
	public function tgmpa_bulk_install() {
		if ( ! $this->in_ajax_call ) {
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
		if ( 'tgmpa-bulk-activate' === $this->action ) {
			$this->post_activate();
		}

		/*
		 * We are relying on tgmpa to output data. This is the standard data that's echoed when installing
		 * plugins, such as "Installing plugin XY&Z (1/2)". If there are any errors, our JS that made
		 * this call will parse them out and know when to fail.
		 *
		 * We could have a validation method here that says, "Did all the plugins that were requested
		 * to be installed actually installed?". We could get that hard success / fail that way, but
		 * we would be missing any error messages if it failed.
		 *
		 * Attempts were made to take control of the output buffering to BOTH look for tgmpa's echoed
		 * data and to do our own validation, but that ended up being very difficult with things like
		 * WordPress' wp_ob_end_flush_all. Workarounds were attempted, but accuracy wasn't 100%.
		 */
		wp_die();
	}

	/**
	 * Determine whether or not to die when TGMPA hits an error.
	 *
	 * This method hooks into a filter we added to TGMPA. See class-tgm-plugin-activation.php for
	 * more details.
	 *
	 * @since 2.0.0
	 *
	 * @param  bool $die Whether or not to die on error.
	 * @return bool
	 */
	public function tgmpa_die_on_api_error( $die ) {
		if ( $this->in_ajax_call ) {
			$die = false;
		}

		return $die;
	}

	/**
	 * Make sure tgmpa loads in ajax calls.
	 *
	 * @since 2.0.0
	 *
	 * @param  bool $load Whether or not to load tgmpa in ajax.
	 * @return true
	 */
	public function tgmpa_load( $load ) {
		if ( $this->in_ajax_call ) {
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
		if ( ! $this->in_ajax_call ) {
			return;
		}

		$configs = $this->configs['tgm']['configs'];
		$configs['is_automatic'] = false;

		tgmpa( $this->configs['starter-content']['plugins'], $configs );
	}
}

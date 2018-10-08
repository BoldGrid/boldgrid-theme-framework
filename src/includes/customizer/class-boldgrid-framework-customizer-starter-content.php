<?php
/**
 * Class: BoldGrid_Framework_Customizer_Starter_Content
 *
 * This is used for the starter content import functionality in the WordPress customizer.
 *
 * @since      2.0.0
 * @category   Customizer
 * @package    Boldgrid_Framework
 * @subpackage Boldgrid_Framework_Customizer
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

// If this file is called directly, abort.
defined( 'WPINC' ) ? : die;

/**
 * BoldGrid_Framework_Customizer_Starter_Content
 *
 * Responsible for the starter content import functionality in the WordPress customizer.
 *
 * @since 2.0.0
 */
class BoldGrid_Framework_Customizer_Starter_Content {

	/**
	 * The BoldGrid Theme Framework configurations.
	 *
	 * @since     2.0.0
	 * @access    protected
	 * @var       string     $configs       The BoldGrid Theme Framework configurations.
	 */
	protected $configs;

	/**
	 * Whether or not we are in the wp-admin/customize.php installing starter content.
	 *
	 * This value tells us whether or not we're good to go ahead and install our starter content. For
	 * example, if we're in customize.php with a changeset_uuid in the url, it's NOT safe to install
	 * the starter content because the user is actually trying to load a previously saved customization
	 * draft.
	 *
	 * @since  2.0.0
	 * @access public
	 * @var    bool
	 */
	public static $fresh_site_customize = false;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since     2.0.0
	 *
	 * @global string $pagenow
	 *
	 * @param     string $configs       The BoldGrid Theme Framework configurations.
	 */
	public function __construct( $configs ) {
		global $pagenow;

		$this->configs = $configs;

		self::$fresh_site_customize = get_option( 'fresh_site' ) && 'customize.php' === $pagenow && empty( $_GET['changeset_uuid'] );
	}

	/**
	 * Add hooks to customizer register action.
	 *
	 * @since 2.0.0
	 */
	public function add_hooks() {
		if ( self::has_valid_content() ) {
			add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue' ) );
		}
	}

	/**
	 * Filter get_theme_starter_content.
	 *
	 * @since 2.0.0
	 *
	 * @param  array $content Array of starter content.
	 * @param  array $config  Array of theme-specific starter content configuration.
	 * @return array
	 */
	public function get_theme_starter_content( $content, $config ) {
		if ( self::$fresh_site_customize ) {

			// Starter Content should only be installed if all required plugins are setup too.
			if ( $this->is_plugin_setup_complete() ) {
				update_option( 'bgtfw_starter_content_previewed', true );
			} else {
				$content = array();
			}
		}

		return $content;
	}

	/**
	 * Determine whether or not a specific changeset includes starter content.
	 *
	 * This is done by looking for any "'starter_content' => true" values within the post_content
	 * (https://pastebin.com/DkeimVYw) that indicate the customize_changeset has starter content.
	 *
	 * @param string $uuid customize_changeset id.
	 * @return boolean
	 */
	public static function changeset_has_starter( $uuid ) {
		$changeset = get_page_by_path( $uuid, OBJECT, 'customize_changeset' );

		if ( $changeset instanceof WP_Post ) {
			$post_content = json_decode( $changeset->post_content, true );

			foreach ( $post_content as $data ) {
				if ( ! empty( $data['starter_content'] ) ) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Enqueue scripts in customizer.
	 *
	 * @since 2.0.0
	 */
	public function enqueue() {

		// Minify if script debug is off.
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		$handle = 'bgtfw-customizer-starter-content';

		wp_register_script(
			$handle,
			$this->configs['framework']['js_dir'] . 'customizer/starter-content' . $suffix . '.js',
			array( 'customize-controls' ),
			$this->configs['version']
		);

		$translations = array(
			'notificationInstalling' => '
				<p>
					<span class="spinner" style="visibility: visible; float: none; vertical-align: bottom;"></span>
					' . esc_html__( 'Installing Starter Content', 'bgtfw' ) . '
				</p>',
			'notificationComplete' => '
				<p>
					<strong>' . esc_html__( 'Starter Content Installed!', 'bgtfw' ) . '</strong>
				</p>
				<p>
					' . wp_kses(
						__( 'To make this preview website your own, make any customizations you would like and then <strong>Save Draft</strong> or <strong>Publish</strong>.', 'bgtfw' ),
						array( 'strong' => array() )
						) . '
					<span class="help"><a href="https://www.boldgrid.com/support/boldgrid-crio/saving-a-draft-and-publishing-with-boldgrid-crio/" target="_blank"><span class="dashicons"></span>' . esc_html__( 'Help', 'bgtfw' ) . '</a></span>
				</p>
				<p>
					' . wp_kses(
						sprintf( __( 'If you\'d rather not keep these changes, <a href="%1$s">exit without saving</a> and return to your dashboard.', 'bgtfw' ), $this->configs['customizer']['starter-content']['return_to_dashboard'] ),
						array( 'a' => array( 'href' => array() ) )
						) . '
				</p>
				',
			'notificationFail' => '
				<p>
					<strong>' . esc_html( 'Starter Content Failed to Install', 'bgtfw' ) . '</strong>
				</p>
				<p>
					' . esc_html__( 'Sorry, an unknown error occurred when trying to install the Starter Content.', 'bgtfw' ) . '
				</p>',
			'install' => self::$fresh_site_customize && $this->is_plugin_setup_complete(),
		);

		wp_localize_script( $handle, 'bgtfwCustomizerStarterContent', $translations );

		wp_enqueue_script( $handle );
	}

	/**
	 * Whether or not the Starter Content has been previewed.
	 *
	 * When the ajax call is made to load the starter content, after (1) the plugins have been installed
	 * and (2) the Starter Content has been loaded, we flag 'bgtfw_starter_content_previewed' as being
	 * true. We don't know if the user went on to publish the Starter Content, but they've definately
	 * installed the plugins and previewed it.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public static function has_been_previewed() {
		$previewed = get_option( 'bgtfw_starter_content_previewed' );

		return ! empty( $previewed );
	}

	/**
	 * Determine whether or not the theme has valid starter content.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public static function has_valid_content() {
		$content = get_theme_support( 'starter-content' );
		return is_array( $content ) && ! empty( $content[0] ) && is_array( $content[0] ) && ( bool ) array_filter( $content[0] );
	}

	/**
	 * Whether or not our Starter Content plugins are setup and complete.
	 *
	 * This is a wrapper function to the BoldGrid_Framework_Customizer_Starter_Content_Plugins
	 * Class' is_setup_complete static function. It exists in THIS method as the logic is needed in
	 * multiple methods.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function is_plugin_setup_complete() {
		$starter_content_plugins = ! empty( $this->configs['starter-content']['plugins'] ) ? $this->configs['starter-content']['plugins'] : array();

		return BoldGrid_Framework_Customizer_Starter_Content_Plugins::is_setup_complete( $starter_content_plugins );
	}

	/**
	 *
	 */
	public function messages() {
		require_once $this->configs['framework']['includes_dir'] . '/partials/starter-content-messages.php';
	}

	/**
	 * Starter Content's hook for pre_get_posts.
	 *
	 * This method is ONLY ran on wp-admin/customize.php when we are requesting Starter Content be
	 * loaded. Checks for this scenario are in the BoldGrid_Framework class, rather than here, to
	 * prevent this method from being triggered far too many times.
	 *
	 * @since 2.0.0
	 *
	 * @param WP_Query $query WP Query.
	 */
	public function pre_get_posts( $query ) {
		$post_type = ! empty( $query->query['post_type'] ) ? $query->query['post_type'] : null;

		/*
		 * Prevent Customize Changesets from being returned.
		 *
		 * If we are in the Customizer and requesting that Starter Content be loaded, we will run
		 * into problems if an existing draft / changeset is found. What the user wants is a fresh
		 * preview of Starter Content, but if an existing draft / changeset is found, then the resulting
		 * preview cannot be guaranteed.
		 *
		 * To ensure a fresh preview of Starter Content, ensure NO previous drafts / changesets are
		 * found. If this query is looking for a customize_changeset, sabatoge and tell it to look
		 * for another post type.
		 */
		if ( 'customize_changeset' === $post_type ) {
			$query->query_vars['post_type'] = 'return_nothing';
		}
	}
}

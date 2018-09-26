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
	 * Initialize the class and set its properties.
	 *
	 * @param     string $configs       The BoldGrid Theme Framework configurations.
	 * @since     2.0.0
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
	}

	/**
	 * Add hooks to customizer register action.
	 *
	 * @since 2.0.0
	 */
	public function add_hooks() {
		if ( self::has_valid_content() ) {
			add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue' ) );
			add_action( 'wp_ajax_load_starter_content', array( $this, 'load_starter_content' ) );
		}
	}

	/**
	 * Determine whether or not a specific changeset includes starter content.
	 *
	 * This is done by looking for any "'starter_content' => true" values within the post_content
	 * (https://pastebin.com/DkeimVYw) that indicate the customize_changeset has starter content.
	 *
	 * @param  string  $uuid customize_changeset id.
	 * @return boolean
	 */
	public static function changeset_has_starter( $uuid ) {
		$changeset = get_page_by_path( $uuid, OBJECT, 'customize_changeset' );

		if ( $changeset instanceof WP_Post ) {
			$post_content = json_decode( $changeset->post_content, true );

			foreach( $post_content as $data ) {
				if( ! empty( $data['starter_content'] ) ) {
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
						array( 'strong' => array(), )
						) . '
					<span class="help"><a href="https://www.boldgrid.com/support/boldgrid-crio/saving-a-draft-and-publishing-with-boldgrid-crio/" target="_blank"><span class="dashicons"></span>' . esc_html__( 'Help', 'bgtfw' ) . '</a></span>
				</p>
				<p>
					' . wp_kses(
						sprintf( __( 'If you\'d rather not keep these changes, <a href="%1$s">exit without saving</a> and return to your dashboard.', 'bgtfw' ), admin_url( 'admin.php?page=crio-starter-content' ) ),
						array( 'a' => array( 'href' => array(), ), )
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
		);

		if( ! empty( $_POST['starter_content'] ) ) {
			$translations['post'] = array(
				'starter_content' => $_POST['starter_content'],
			);
		}

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
	 * Handles ajax request for loading starter content.
	 *
	 * @since 2.0.0
	 */
	public function load_starter_content() {
		global $wp_customize;
		if ( ! is_user_logged_in() ) {
			wp_send_json_error( 'unauthenticated' );
		}
		if ( empty( $wp_customize ) || ! $wp_customize->is_preview() ) {
			wp_send_json_error( 'not_preview' );
		}
		$action = 'preview-customize_' . $wp_customize->get_stylesheet();
		if ( ! check_ajax_referer( $action, 'nonce', false ) ) {
			wp_send_json_error( 'invalid_nonce' );
		}

		/**
		 * Take action before any starter content is installed.
		 *
		 * At this point, we're in an AJAX call to install the starter content. Any required plugins
		 * for the starter content have already been installed.
		 *
		 * @since 2.0.0
		 */
		do_action( 'bgtfw_pre_load_starter_content' );

		$starter_content_applied = 0;
		$wp_customize->import_theme_starter_content();
		foreach ( $wp_customize->changeset_data() as $setting_id => $setting_params ) {
			if ( ! empty( $setting_params['starter_content'] ) ) {
				$starter_content_applied += 1;
			}
		}

		if ( 0 === $starter_content_applied ) {
			wp_send_json_error( 'no_starter_content' );
		} else {
			update_option( 'bgtfw_starter_content_previewed', true );
			wp_send_json_success();
		}
	}
}

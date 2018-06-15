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
		$content = get_theme_support( 'starter-content' );

		// Check for valid starter-content being passed before loading.
		if ( is_array( $content ) && ! empty( $content[0] ) && is_array( $content[0] ) && ( bool ) array_filter( $content[0] ) ) {
			$this->starter_content_settings();
			add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue' ) );
			add_action( 'customize_controls_print_footer_scripts', array( $this, 'print_templates' ) );
			add_action( 'wp_ajax_load_starter_content', array( $this, 'load_starter_content' ) );
		}
	}

	/**
	 * Register starter content settings.
	 *
	 * @since 2.0.0
	 */
	public function starter_content_settings() {
		global $wp_customize;
		$wp_customize->add_setting( 'bgtfw_starter_content_loaded', array(
			'default' => false,
			'capability' => 'edit_theme_options',
			'transport' => 'postMessage',
			'sanitize_callback' => function( $value ) {
				return true === $value ? true : false;
			},
		) );
	}

	/**
	 * Enqueue scripts in customizer.
	 *
	 * @since 2.0.0
	 */
	public function enqueue() {

		// Minify if script debug is off.
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_script(
			'bgtfw-customizer-starter-content',
			$this->configs['framework']['js_dir'] . 'customizer/starter-content' . $suffix . '.js',
			array( 'customize-controls' ),
			$this->configs['version']
		);
	}

	/**
	 * Print template for the "Import Starter Content" button.
	 *
	 * @since 2.0.0
	 */
	public function print_templates() {
		?>
		<script type="text/html" id="tmpl-customize-starter-content-actions">
			<div class="theme-starter-content-actions">
				<!-- @todo Add a button for each set of sample data? -->
				<button type="button" class="button button-secondary dashicons-before dashicons-migrate"><?php _e( 'Import Starter Content', 'bgtfw' ) ?></button>
			</div>
		</script>
		<?php
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
			wp_send_json_success();
		}
	}
}

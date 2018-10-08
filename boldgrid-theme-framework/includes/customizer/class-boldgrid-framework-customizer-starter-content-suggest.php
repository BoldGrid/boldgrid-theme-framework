<?php
/**
 * Class: BoldGrid_Framework_Customizer_Starter_Content_Suggest
 *
 * Suggest to the user that they install starter content.
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
 * BoldGrid_Framework_Customizer_Starter_Content_Suggest
 *
 * @since 2.0.0
 */
class BoldGrid_Framework_Customizer_Starter_Content_Suggest {

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
	 * Via ajax, set flag that the user has been suggested to install starter content.
	 *
	 * @since 2.0.0
	 */
	public function ajax_suggested() {
		if ( ! check_ajax_referer( 'starter_content_suggested', 'security', false ) ) {
			wp_send_json_error( __( 'Invalid nonce.', 'bgtfw' ) );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'Permission denied.', 'bgtfw' ) );
		}

		$this->has_been_suggested( true );

		wp_send_json_success();
	}

	/**
	 * Get / set that the user has been suggested to user starter content.
	 *
	 * If no params passed in, then get the value. Otherwise, set it.
	 *
	 * @since 2.0.0
	 *
	 * @param  bool $value Whether or not the user has been suggested.
	 * @return bool
	 */
	public function has_been_suggested( $value = null ) {
		$option_name = 'bgtfw_starter_content_suggested';

		if ( ! is_null( $value ) ) {
			$value = (bool) $value;
			update_option( $option_name, $value );
			return $value;
		} else {
			$has_been_suggested = get_option( $option_name );
			return ! empty( $has_been_suggested );
		}
	}

	/**
	 * Determine whether or not we should suggest that the user install starter content.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function maybe_suggest() {
		// If we don't have a "Starter Content Page" to redirect the user to, abort.
		if ( empty( $this->configs['starter-content-suggest']['dashboard_url'] ) ) {
			return false;
		}

		// Determine if our referer is /wp-admin/customize.php (IE in customizer iframe).
		$str                = '/wp-admin/customize.php';
		$referer_path       = parse_url( wp_get_referer(), PHP_URL_PATH );
		$customizer_referer = ( substr( $referer_path, -1 * strlen( $str ) ) === $str );

		if ( ! ( is_customize_preview() && $customizer_referer ) ) {
			return false;
		}

		// If the user is already loading starter content.
		if ( ! empty( $_GET['customize_changeset_uuid'] ) && BoldGrid_Framework_Customizer_Starter_Content::changeset_has_starter( $_GET['customize_changeset_uuid'] ) ) {
			return false;
		}

		// If we've already suggested, don't suggest again.
		if ( $this->has_been_suggested() ) {
			return false;
		}

		// If they've already previewed the starter content, no need to suggest, they know the deal.
		if ( BoldGrid_Framework_Customizer_Starter_Content::has_been_previewed() ) {
			return false;
		}

		if ( ! BoldGrid_Framework_Customizer_Starter_Content::has_valid_content() ) {
			return false;
		}

		return true;
	}

	/**
	 * Enqueue scripts in customizer.
	 *
	 * @since 2.0.0
	 */
	public function wp_enqueue_scripts() {
		if ( $this->maybe_suggest() ) {
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			$handle = 'bgtfw-customizer-starter-content-suggest';
			wp_enqueue_script(
				$handle,
				$this->configs['framework']['js_dir'] . 'customizer/starter-content-suggest' . $suffix . '.js',
				array( 'jquery', 'jquery-ui-dialog' ),
				$this->configs['version']
			);
			wp_localize_script(
				$handle, 'boldgridFrameworkCustomizerSuggest', array(
					'ajaxurl'           => admin_url( 'admin-ajax.php' ),
					'starterContentUrl' => $this->configs['starter-content-suggest']['dashboard_url'],
					'yes'               => __( 'Yes', 'bgtfw' ),
					'no'                => __( 'No', 'bgtfw' ),
				)
			);
			wp_enqueue_script( $handle );
		}
	}

	/**
	 * Include our partial template file.
	 *
	 * @since 2.0.0
	 */
	public function wp_footer() {
		if ( $this->maybe_suggest() ) {
			include dirname( dirname( __FILE__ ) ) . '/partials/customizer-suggest.php';
		}
	}
}

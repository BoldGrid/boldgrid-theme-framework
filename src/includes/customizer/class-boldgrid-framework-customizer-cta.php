<?php
/**
 * Class: Boldgrid_Framework_Customizer_Cta
 *
 * This is used to add the device previewer to the WordPress customizer.
 *
 * @since      1.0.0
 * @package    Boldgrid_Framework
 * @subpackage BoldGrid_Framework_Device_Preview
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

/**
 * BoldGrid_Framework_Device_Preview Class
 *
 * Responsible for the device preview/resize buttons in the
 * customizer.
 *
 * @since 1.0.0
 */
class Boldgrid_Framework_Customizer_Cta {

	/**
	 * The BoldGrid Theme Framework configurations.
	 *
	 * @since     1.0.0
	 * @access    protected
	 * @var       string     $configs       The BoldGrid Theme Framework configurations.
	 */
	protected $configs;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param      string $configs       The BoldGrid Theme Framework configurations.
	 * @since      1.0.0
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
		$this->api = new BoldGrid( $this->configs );
	}

	/**
	 * Initialize.
	 *
	 * @since 1.3.5
	 */
	public function init() {
		// Abort if version check is not satisfied.
		if (  $this->api->framework_version( '1.4.0' ) ) return;
		// Load black studio tinymce widget.
		$this->load_bstw();
	}

	public function load_bstw() {
		require_once $this->configs['framework']['includes_dir'] . 'black-studio-tinymce-widget/black-studio-tinymce-widget.php';
	}
}

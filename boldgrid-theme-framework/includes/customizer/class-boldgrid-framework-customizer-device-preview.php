<?php
/**
 * Class: Boldgrid_Framework_Device_Preview
 *
 * This is used to add the device previewer to the WordPress customizer.
 *
 * @since      1.0.0
 * @package    Boldgrid_Framework
 * @subpackage Boldgrid_Framework_Device_Preview
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

// If this file is called directly, abort.
defined( 'WPINC' ) ? : die;

/**
 * Boldgrid_Framework_Device_Preview Class
 *
 * Responsible for the device preview/resize buttons in the
 * customizer.
 *
 * @since 1.0.0
 */
class Boldgrid_Framework_Device_Preview {

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
	}

	/**
	 * This will add the necessary js and css files to the customizer.
	 *
	 * @since      1.0.0
	 */
	public function enqueue_scripts() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_style( 'boldgrid-device-preview',
		$this->configs['framework']['css_dir'] . 'customizer/device-preview' . $suffix . '.css' );

		wp_enqueue_script( 'boldgrid-device-preview',
		$this->configs['framework']['js_dir'] . 'customizer/device-preview' . $suffix . '.js', array( 'customize-controls' ), $this->configs['version'], true );
		$exports = array(
			'screen' => array(
				'desktop',
				'tablet',
				'mobile',
			),
			'settings' => array(
				'mobileTheme' => false,
				'mobileMessage' => 'You have the Mobile Theme enabled. This view may not represent how your viewers see your blog.',
			),
		);
		wp_localize_script( 'boldgrid-device-preview', '_wpCustomizerDevicePreview', $exports );
	}

	/**
	 * This will print the template for the device previewer in the WordPress customizer.
	 *
	 * @action     customize_controls_print_footer_scripts
	 * @since      1.0.0
	 */
	public function print_templates() {
		?>
		<script id="tmpl-device" type="text/template">
			<div id="devices">
				<div class="devices-container">
					<span data-device="desktop" class="device screen-desktop" title="Desktop"></span>
					<span data-device="tablet" class="device screen-tablet" title="Tablet"></span>
					<span data-device="mobile" class="device screen-mobile" title="Mobile"></span>
				</div>
			</div>
		</script>
		<?php
	}
}

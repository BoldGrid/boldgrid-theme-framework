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

	/**
	 * Set the previewable device buttons.
	 *
	 * @since 2.0.0
	 *
	 * @param array $devices Array of previewable devices to use in customizer.
	 *
	 * @return array $devices Array of previewable devices to use in customizer.
	 */
	public function customize_previewable_devices( $devices ) {
		$large = [
			'large' => [
				'label' => __( 'Enter large display preview mode', 'bgtfw' ),
			],
		];
		$devices = array_merge( $large, $devices );

		return $devices;
	}

	/**
	 * Set device previewer icons, and iframe preview sizes.
	 *
	 * @since 2.0.0
	 */
	public function adjust_customizer_responsive_sizes() {
		?>
		<style>
			body.wp-customizer {
				overflow: auto;
			}
			.wp-customizer .wp-full-overlay {
				overflow: hidden;
			}
			_::-webkit-full-page-media, _:future, :root .wp-customizer .wp-full-overlay {
				overflow: visible;
			}
			.wp-customizer .preview-mobile .wp-full-overlay-main.previewed-from-mobile,
			.wp-customizer .preview-tablet .wp-full-overlay-main.previewed-from-tablet,
			.wp-customizer .preview-desktop .wp-full-overlay-main.previewed-from-desktop,
			.wp-customizer .preview-large .wp-full-overlay-main.previewed-from-large {
				margin: auto;
				width: 100%;
				height: 100%;
				left: 0;
				-webkit-transform: translateX(0);
				transform: translateX(0);
			}

			/* Large Device Previewing Customizer */
			.wp-full-overlay-footer .devices .preview-large:before {
				font: normal 20px/30px "icomoon";
				content: "\e903";
			}
			.wp-full-overlay-footer .devices .preview-large:hover {
				border-bottom: 4px solid #0073aa;
			}
			.wp-customizer .preview-desktop .wp-full-overlay-main.previewed-from-large {
				margin: auto;
				width: 992px;
				height: 100%;
				left: 50%;
				-webkit-transform: translateX(-50%);
				transform: translateX(-50%);
			}
			.wp-customizer .preview-tablet .wp-full-overlay-main.previewed-from-large {
				margin: auto;
				width: 768px;
				height: 100%;
				left: 50%;
				-webkit-transform: translateX(-50%);
				transform: translateX(-50%);
			}
			.wp-customizer .preview-mobile .wp-full-overlay-main.previewed-from-large {
				margin: auto;
				width: 420px;
				height: 100%;
				left: 50%;
				-webkit-transform: translateX(-50%);
				transform: translateX(-50%);
			}

			/* Desktop Device Previewing Customizer */
			.wp-full-overlay-footer .devices .preview-desktop:before {
				font: normal 20px/30px "icomoon";
				content: "\e902";
			}
			.wp-full-overlay-footer .devices .preview-desktop:hover {
				border-bottom: 4px solid #0073aa;
			}
			.wp-customizer .preview-large .wp-full-overlay-main.previewed-from-desktop {
				margin: auto;
				width: 1200px;
				height: 100%;
				left: 0;
				-webkit-transform: translateX(0);
				transform: translateX(0);
			}
			.wp-customizer .preview-tablet .wp-full-overlay-main.previewed-from-desktop {
				margin: auto;
				width: 768px;
				height: 100%;
				left: 50%;
				-webkit-transform: translateX(-50%);
				transform: translateX(-50%);
			}
			.wp-customizer .preview-mobile .wp-full-overlay-main.previewed-from-desktop {
				margin: auto;
				width: 380px;
				height: 100%;
				left: 50%;
				-webkit-transform: translateX(-50%);
				transform: translateX(-50%);
			}

			/* Tablet Device Previewing Customizer */
			.wp-full-overlay-footer .devices .preview-tablet:before {
				font: normal 20px/30px "icomoon";
				content: "\e901";
			}
			.wp-full-overlay-footer .devices .preview-tablet:hover {
				border-bottom: 4px solid #0073aa;
			}
			.wp-customizer .preview-large .wp-full-overlay-main.previewed-from-tablet {
				margin: auto;
				width: 1200px;
				height: 100%;
				left: 0;
				-webkit-transform: translateX(0);
				transform: translateX(0);
			}
			.wp-customizer .preview-desktop .wp-full-overlay-main.previewed-from-tablet {
				margin: auto;
				width: 992px;
				height: 100%;
				left: 0;
				-webkit-transform: translateX(0);
				transform: translateX(0);
			}
			.wp-customizer .preview-mobile .wp-full-overlay-main.previewed-from-tablet {
				margin: auto;
				width: 320px;
				height: 100%;
				left: 50%;
				-webkit-transform: translateX(-50%);
				transform: translateX(-50%);
			}

			/* Mobile Device Previewing Customizer */
			.wp-full-overlay-footer .devices .preview-mobile:before {
				font: normal 20px/30px "icomoon";
				content: "\e900";
			}
			.wp-full-overlay-footer .devices .preview-mobile:hover {
				border-bottom: 4px solid #0073aa;
			}
			.wp-customizer .preview-large .wp-full-overlay-main.previewed-from-mobile {
				margin: auto;
				width: 1200px;
				height: 100%;
				left: 0;
				-webkit-transform: translateX(0);
				transform: translateX(0);
			}
			.wp-customizer .preview-desktop .wp-full-overlay-main.previewed-from-mobile {
				margin: auto;
				width: 992px;
				height: 100%;
				left: 0;
				-webkit-transform: translateX(0);
				transform: translateX(0);
			}
			.wp-customizer .preview-tablet .wp-full-overlay-main.previewed-from-mobile {
				margin: auto;
				min-width: 768px;
				height: 100%;
				left: 0;
				-webkit-transform: translateX(0);
				transform: translateX(0);
			}
			@media screen and (max-width: 600px) {
				body.wp-customizer {
					overflow-x: hidden;
				}
				.wp-customizer .wp-full-overlay-footer .devices {
					display: none;
				}
				.wp-customizer .preview-mobile .wp-full-overlay-main.previewed-from-mobile,
				.wp-customizer .preview-tablet .wp-full-overlay-main.previewed-from-tablet,
				.wp-customizer .preview-desktop .wp-full-overlay-main.previewed-from-desktop,
				.wp-customizer .preview-large .wp-full-overlay-main.previewed-from-large {
					width: 100%;
				}
			}
			@media screen and (max-width: 1024px) {
				.wp-customizer .wp-full-overlay-footer .devices {
					display: block;
				}
			}
		</style>
		<?php
	}
}

<?php
/**
 * File: Boldgrid_Framework_Customizer_Generic
 *
 * Add generic css controls to the customizer.
 *
 * @since      1.0.0
 * @category   Customizer
 * @package    Boldgrid_Framework
 * @subpackage Boldgrid_Framework_Customizer_Generic
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

/**
 * Class: Add generic css controls to the customizer.
 *
 * @since 2.0.0
 */
class Boldgrid_Framework_Customizer_Generic {

	/**
	 * Device Widths used for creating mobile styles.
	 *
	 * @since 2.0.0
	 *
	 * @var array
	 */
	public static $device_sizes = array(

		// Phone size from 0 to 767px.
		'phone' => 767,

		// Tablet size from 768 to 991.
		'tablet' => 991,

		// Desktop from 992 to 1199.
		'desktop'  => 1199,

		// Large 1200 +++.
	);

	/**
	 * Range configuration.
	 *
	 * @var $range_config
	 */
	protected $range_config;

	/**
	 * BGTFW Configs
	 *
	 * @var array $configs
	 */
	protected $configs;

	/**
	 * Setup the boldgrid configs.
	 *
	 * @since 2.0.0
	 * @param array $configs Configurations.
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;

		$this->range_config = $this->create_range_configuration();
	}

	/**
	 * Add styles for all boldgrid generic controls.
	 *
	 * @since 2.0.0
	 */
	public function add_styles() {
		foreach ( $this->configs['customizer']['controls'] as $control ) {
			$name = ! empty( $control['choices']['name'] ) ? $control['choices']['name'] : null;

			if ( 'boldgrid_controls' === $name ) {
				$style_id = $control['settings'] . '-bgcontrol';
				$theme_mod_val = get_theme_mod( $control['settings'] );

				// If theme mod is set, use it to create styles.
				if ( $theme_mod_val && ! empty( $theme_mod_val['media'] ) ) {
					$css = ! empty( $theme_mod_val['css'] ) ? $theme_mod_val['css'] : false;

				// If theme mod is not set, try to generate styles from default settings.
				} else {
					$css = $this->get_default_styles( $control );
				}

				// Enqueue any css if applicable.
				if ( $css ) {
					$this->add_inline_style( $style_id, wp_specialchars_decode( $css, $quote_style = ENT_QUOTES ) );
				}
			}
		}
	}

	/**
	 * Get the default styles for a control.
	 *
	 * If the user has not customized this control, we will use any values defined in the
	 * defaults to generate css.
	 *
	 * @since 2.0.0
	 *
	 * @param  array $control Controls Configuration.
	 * @return string         Added ability to define device visibility defaults.
	 */
	public function get_default_styles( $control ) {
		$media_css = '';

		if ( 'DeviceVisibility' === $control['choices']['type'] ) {
			$media_css .= $this->device_visibility_styles( $control ) ?: '';
		} else {
			// @todo generic slider controls styles.
		}

		return $media_css;
	}

	/**
	 * Create styles for device visibility.
	 *
	 * @since 2.0.0
	 *
	 * @param  array $control Control Configuration.
	 * @return string         CSS to display.
	 */
	public function device_visibility_styles( $control ) {
		$css = '';
		$defaults = get_theme_mod( $control['settings'] );
		$defaults = is_array( $defaults ) ? $defaults : [];

		foreach ( $defaults as $device ) {
			$selector = implode( ',', $control['choices']['settings']['control']['selectors'] );
			$css .= $this->create_media_prefix( $device );
			$css .= $css ? "{ ${selector} { display: none !important;} }" : '';
		}

		return $css;
	}

	/**
	 * Create a media query for a given device.
	 *
	 * @since 2.0.0
	 *
	 * @param  string $device Device name (phone, tablet, desktop, large).
	 * @return string         Media prefix.
	 */
	public function create_media_prefix( $device ) {
		$prefix = '';
		$range = $this->range_config[ $device ];
		if ( ! $range ) {
			return $prefix;
		}

		if ( ! empty( $range['min'] ) && ! empty( $range['max'] ) ) {
			$prefix = "@media only screen and (max-width: {$range['max']}px) and (min-width: {$range['min']}px)";
		} else if ( ! empty( $range['min'] ) && empty( $range['max'] ) ) {
			$prefix = "@media only screen and ( min-width: {$range['min']}px )";
		} else if ( empty( $range['min'] ) && ! empty( $range['max'] ) ) {
			$prefix = "@media only screen and ( max-width: {$range['max']}px )";
		}

		return $prefix;
	}

	/**
	 * Add inline style without parent stylesheet.
	 *
	 * @since 2.0.0
	 *
	 * @param string $id  Desired ID, will have a suffix of -inline-style.
	 * @param string $css CSS to output.
	 */
	public function add_inline_style( $id, $css ) {
		wp_register_style( $id, false );
		wp_enqueue_style( $id );
		wp_add_inline_style( $id, $css );
	}

	/**
	 * Create an object of ranges tpo be used for creating media queries.
	 *
	 * @since 2.0.0
	 *
	 * @return array Ranges per devices.
	 */
	protected function create_range_configuration() {
		$device_config = self::$device_sizes;

		$ranges = [
			'phone' => [
				'max' => $device_config['phone'],
			],
			'tablet' => [
				'min' => $device_config['phone'] + 1,
				'max' => $device_config['tablet'],
			],
			'desktop' => [
				'min' => $device_config['tablet'] + 1,
				'max' => $device_config['desktop'],
			],
			'large' => [
				'min' => $device_config['desktop'] + 1,
			],
		];

		return $ranges;
	}

}

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
	public static $deviceSizes = array(

		// Phone size from 0 to 767px.
		'phone' => 767,

		// Tablet size from 768 to 991.
		'tablet' => 991,

		// Desktop from 992 to 1199.
		'desktop'  => 1199,

		// Large 1200 +++.
	);

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

				$theme_mod_val = get_theme_mod( $control['settings'] );
				if ( $theme_mod_val && ! empty( $theme_mod_val['css'] ) ) {
					$style_id = $control['settings'] . '-bgcontrol';
					$this->add_inline_style( $style_id, wp_specialchars_decode( $theme_mod_val['css'], $quote_style = ENT_QUOTES ) );
				}
			}
		}
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
}

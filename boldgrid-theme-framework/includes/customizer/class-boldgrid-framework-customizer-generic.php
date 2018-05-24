<?php
/**
 * Class: Boldgrid_Framework_Customizer_Help
 *
 * This adds help tooltips to the WordPress customizer options in
 * case a user needs additional direction while customizer their site.
 *
 * @since      1.0.0
 * @category   Customizer
 * @package    Boldgrid_Framework
 * @subpackage Boldgrid_Framework_Customizer_Help
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 * @uses       Kirki\Scripts\EnqueueScript
 */

/**
 * Class responsible for the customizer help bubbles.
 *
 * @since 1.0
 */
class Boldgrid_Framework_Customizer_Generic {

	protected $configs;

	public function __construct( $configs ) {
		$this->configs = $configs;
	}

	/**
	 * Add styles for all boldgrid generic controls.
	 *
	 * @since 2.0.0
	 */
	public function add_styles() {
		foreach( $this->configs['customizer']['controls'] as $control ) {
			$name = ! empty( $control['choices']['name'] ) ? $control['choices']['name'] : null;
			if ( 'boldgrid_controls' === $name ) {

				$theme_mod_val = get_theme_mod( $control['settings'] );
				if ( $theme_mod_val && ! empty( $theme_mod_val['css'] ) ) {
					$styleId = $control['settings'] . '-bgcontrol';
					wp_register_style( $styleId, false );
					wp_enqueue_style( $styleId );
					wp_add_inline_style( $styleId, $theme_mod_val['css'] );
					//wp_add_inline_style( 'style', );
				}
			}
		}
	}
}

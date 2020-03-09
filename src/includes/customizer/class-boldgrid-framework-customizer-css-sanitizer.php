<?php
/**
 * Class: Boldgrid_Framework_Customizer_Css_Sanitize.
 *
 * This contains sanitizer functions for CSS Margin / Padding settings.
 *
 * @since      SINCEVERSION
 * @category   Customizer
 * @package    Boldgrid_Framework_Customizer
 * @subpackage Boldgrid_Framework_Customizer_Css
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

/**
 * Boldgrid_Framework_Customizer_Css_Sanitize Class.
 *
 * Class responsible for sanitizing the CSS for Margin / Padding changes.
 *
 * @since SINCEVERSION
 */
class Boldgrid_Framework_Customizer_Css_Sanitize {

	/**
	 * Sanitize an array with css styles.
	 *
	 * @since SINCEVERSION
	 *
	 * @param  array $css Array of CSS styles.
	 * @return array
	 */
	public function sanitize_directional( $css ) {
		foreach ( $css as $line ) {
			$array = json_decode( $line, true );
			if ( null !== $array ) {
				foreach ( $array as $media_type ) {
					foreach ( $media_type['values'] as $direction ) {
						if ( 'integer' !== gettype( $direction ) ) {
							return new WP_Error( 'Values must be integers' );
						}
					}
				}
			}
		}
		return $css;
	}
}

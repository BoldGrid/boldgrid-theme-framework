<?php
/**
 * Class: Boldgrid_Framework_Customizer_Color_Sanitize
 *
 * This contains the color palette UI, and theme mod functionality
 * for the color palette selections in the WordPress customizer.
 *
 * @since      1.0.0
 * @category   Customizer
 * @package    Boldgrid_Framework_Customizer
 * @subpackage Boldgrid_Framework_Customizer_Colors
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

/**
 * Boldgrid_Framework_Customizer_Colors Class
 *
 * Class responsible for the color palette controls in customizer.
 *
 * @since 1.0.0
 */
class Boldgrid_Framework_Customizer_Color_Sanitize {

	/**
	 * Sanitize a color palette.
	 *
	 * @since 2.0.0
	 *
	 * @param  string $color Color seperated by colon.
	 * @return string        Sanitized color seperated by colon.
	 */
	public function sanitize_palette_selector( $color ) {

		// If no color specified.
		if ( empty( $color ) || is_string( $color ) && 'transparent' == trim( strtolower( $color ) ) ) {
			return 'transparent';
		}

		// If missing the token separator.
		if ( strpos( $color, ':' ) === false ) {
			return 'transparent';
		}

		$class = strtok( $color, ':' );

		// If the color class is missing/invalid.
		if ( ! preg_match( '/^color-([\d]|neutral)/', $class, $matches ) ) {
			return 'transparent';
		}

		// Validate the color is a real color.
		$color = ariColor::newColor( strtok( ':' ) );

		// Return a CSS value, using the auto-detected mode
		return strtolower( $class . ':' . $color->toCSS( $color->mode ) );
	}
}

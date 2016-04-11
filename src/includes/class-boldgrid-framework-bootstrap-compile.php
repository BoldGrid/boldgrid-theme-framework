<?php
/**
 * Class: Boldgrid_Framework_SCSS
 *
 * Functions for interfacing with Leafo\ScssPhp\Compiler
 *
 * @since      1.0.0
 * @package    Boldgrid_Framework
 * @subpackage Boldgrid_Framework_SCSS
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

use Leafo\ScssPhp\Compiler;

/**
 * Class: Boldgrid_Framework_Bootstrap_Compile
 *
 * Functions for interfacing with Leafo\ScssPhp\Compiler
 *
 * @since      1.0.0
 */
class Boldgrid_Framework_Bootstrap_Compile {

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
	 * @since     1.0.0
	 * @param     string $configs       The BoldGrid Theme Framework configurations.
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
	}

	/**
	 * Build Bootstrap from SCSS.
	 *
	 * Calls to compile bootstrap, and then save it.
	 *
	 * @since 1.1
	 */
	public function bootstrap_build() {
		$css = $this->compile_bootstrap( );
		$this->save_compiled_scss( $css );
	}

	/**
	 * Initialize the WP_Filesystem.
	 *
	 * @since 1.1
	 * @global $wp_filesystem WordPress Filesystem global.
	 */
	public function init_filesystem() {
		global $wp_filesystem;
		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';
			WP_Filesystem();
		}
	}

	/**
	 * Get Active Palette Colors.
	 *
	 * @since 1.1
	 * @return array $boldgrid_colors Array containing SCSS variable name.
	 */
	public function get_active_palette() {
		$boldgrid_colors = array();
		$palettes = json_decode( get_theme_mod( 'boldgrid_color_palette' ), true );

		if ( null !== $palettes ) {
			$current_palette = $palettes['state']['active-palette'];
			$colors = $palettes['state']['palettes'][ $current_palette ]['colors'];
			$i = 0;

			foreach ( $colors as $color ) {
				$i++;
				$boldgrid_colors[ $current_palette.'_'.$i ] = $color;
			}
		}

		return $boldgrid_colors;
	}

	/**
	 * Converts a hex color into an array of RGB.
	 *
	 * @since 1.1
	 * @param string $hex Hex color to conver to RGB.
	 * @return array $rgb An array with rgb values of color.
	 */
	public function convert_hex_to_rgb( $hex ) {
		$hex = str_replace( '#', '', $hex );

		if ( strlen( $hex ) === 3 ) {
			$r = hexdec( substr( $hex, 0, 1 ).substr( $hex, 0, 1 ) );
			$g = hexdec( substr( $hex, 1, 1 ).substr( $hex, 1, 1 ) );
			$b = hexdec( substr( $hex, 2, 1 ).substr( $hex, 2, 1 ) );
		} else {
			$r = hexdec( substr( $hex, 0, 2 ) );
			$g = hexdec( substr( $hex, 2, 2 ) );
			$b = hexdec( substr( $hex, 4, 2 ) );
		}

		$rgb = array( $r, $g, $b );

		return $rgb;
	}

	/**
	 * Calculate out the luminance of a given color.
	 *
	 * This can accept color in rgb or hexadecimal format to have it's
	 * luminance calculated out.
	 *
	 * @since 1.1
	 * @param string $color Color to get luminance of.
	 * @return string $luminance the luminance value of color.
	 */
	public function get_luminance( $color ) {
		// Check for RGB or hex first.
		if ( false !== strpos( $color, '#' ) ) {
			$rgb_arrays = self::convert_hex_to_rgb( $color );
		} elseif ( false !== strpos( $color, 'rgb' ) ) {
			$rgb_arrays = preg_replace( '/\D/', '', explode( ',', $color ) );
		}

		// Assign RGB.
		$r = intval( $rgb_arrays[0] );
		$g = intval( $rgb_arrays[1] );
		$b = intval( $rgb_arrays[2] );

		// Calculate Luminance.
		$luminance = strval( ( ( ( $r * .299 ) + ( $g * .587 ) + ( $b * .114 ) ) / 255 ) * 100 );

		return $luminance;
	}

	/**
	 * Get the text contrast color for a color.
	 *
	 * This will generate the text contrast colors in PHP to pass to scss compiler.
	 *
	 * @since 1.1
	 * @return array $text_contrast_colors Array of text contrast variables to pass.
	 */
	public function get_text_contrast() {
		$text_contrast_colors = array();
		// Color Configs.
		$configs = $this->configs['customizer-options']['colors'];
		// Get the active color palette.
		$colors = self::get_active_palette();
		// Determine luminance values of light and dark text.
		$light_text = self::get_luminance( $configs['light_text'] );
		$dark_text = self::get_luminance( $configs['dark_text'] );

		foreach ( $colors as $key => $color ) {
			$color = self::get_luminance( $color );
			$lightness = abs( $color - $light_text );
			$darkness = abs( $color - $dark_text );

			if ( $lightness > $darkness ) {
				$text_contrast_colors[ 'text-contrast-' . $key ] = $configs['light_text'];
			} else {
				$text_contrast_colors[ 'text-contrast-' . $key ] = $configs['dark_text'];
			}
		}

		return $text_contrast_colors;
	}

	/**
	 * Compile Bootstrap SCSS to CSS.
	 *
	 * @since 1.1
	 * @return string $compiled_scss Contains compiled SCSS code.
	 */
	public function compile_bootstrap() {
		if ( ! class_exists( '\Leafo\ScssPhp\Compiler' ) ) {
			require_once $this->configs['framework']['includes_dir'] . '/scssphp/scss.inc.php';
		}
		$scss = new Compiler();
		$path = $this->configs['framework']['asset_dir'] . 'scss/';
		$scss->setImportPaths( $path );

		if ( $this->configs['bootstrap'] ) {
			// BoldGrid specific variables to have available during compile.
			$boldgrid_variables = array_merge( $this->get_active_palette(), $this->get_text_contrast() );
			// Variables to assign before compile.
			$variables = array_merge( $boldgrid_variables, $this->configs['bootstrap'] );
			// Set the Variables.
			$scss->setVariables( $variables );
		}

		$compiled_scss = $scss->compile( '@import "bootstrap";' );

		return $compiled_scss;
	}

	/**
	 * Save Compiled SCSS.
	 *
	 * @since 1.1
	 * @param string $compiled_scss Contains the compiled Bootstrap SCSS to save.
	 */
	public function save_compiled_scss( $compiled_scss ) {
		global $wp_filesystem;
		$this->init_filesystem();
		// Write output to Bootstrap CSS file.
		$file = $this->configs['framework']['asset_dir'] . 'css/bootstrap/bootstrap.min.css';
		$wp_filesystem->put_contents( $file, $compiled_scss, FS_CHMOD_FILE );
	}
}

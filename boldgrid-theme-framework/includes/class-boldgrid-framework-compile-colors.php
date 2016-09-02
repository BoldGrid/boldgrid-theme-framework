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

/**
 * Class: Boldgrid_Framework_Bootstrap_Compile
 *
 * Functions for interfacing with Leafo\ScssPhp\Compiler
 *
 * @since      1.0.0
 */
class Boldgrid_Framework_Compile_Colors {

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
	 * Get Active Palette Colors.
	 *
	 * @since 1.1
	 * @return array $boldgrid_colors Array containing SCSS variable name.
	 */
	public function get_active_palette() {
		$boldgrid_colors = array();
		$palettes = json_decode( get_theme_mod( 'boldgrid_color_palette' ), true );

		if ( ! empty( $palettes ) ) {
			$current_palette = $palettes['state']['active-palette'];
			$colors = $palettes['state']['palettes'][ $current_palette ]['colors'];
			$i = 0;

			foreach ( $colors as $color ) {
				$i++;
				$boldgrid_colors[ $current_palette.'_'.$i ] = $color;
			}
		}
		if ( false !== self::get_neutral_color() ) {
			$boldgrid_colors = array_merge( $boldgrid_colors, self::get_neutral_color() );
		}
		return $boldgrid_colors;
	}

	/**
	 * Get SCSS list for $colors variable.
	 *
	 * @since 1.2.3
	 * @return array $boldgrid_colors Array containing SCSS variable name.
	 */
	public function get_color_list() {
		$boldgrid_colors = '';
		$palettes = json_decode( get_theme_mod( 'boldgrid_color_palette' ), true );

		if ( ! empty( $palettes ) ) {
			$current_palette = $palettes['state']['active-palette'];
			$colors = $palettes['state']['palettes'][ $current_palette ]['colors'];
			foreach ( $colors as $color ) {
				$boldgrid_colors .= "{$color} ";
			}
		}
		return $boldgrid_colors;
	}

	/**
	 * Get the neutral color if it exists.
	 *
	 * @since 1.2.3
	 * @return array $color_variables Array containing SCSS variables and values.
	 */
	public function get_neutral_color() {
		$neutral_color = false;
		$palettes = json_decode( get_theme_mod( 'boldgrid_color_palette' ), true );
		$current_palette = $palettes['state']['active-palette'];
		if ( ! empty( $palettes['state']['palettes'][ $current_palette ]['neutral-color'] ) ) {
			$neutral_color = array(
				$palettes['state']['active-palette'] . '-neutral-color' => $palettes['state']['palettes'][ $current_palette ]['neutral-color'],
			);
		}
		return $neutral_color;
	}

	/**
	 * Get all color variables for compiling.
	 *
	 * @since 1.2.3
	 * @return array $color_variables Array containing SCSS variables and values.
	 */
	public function get_scss_variables() {
		$color_variables = array();
		$text_colors = array(
			'light_text' => $this->configs['customizer-options']['colors']['light_text'],
			'dark_text' => $this->configs['customizer-options']['colors']['dark_text'],
		);
		$active_palette = array(
			'colors' => self::get_color_list(),
		);
		$color_variables = array_merge( $active_palette, $text_colors, self::get_active_palette(), self::get_text_contrast() );
		if ( true === $this->configs['components']['buttons']['enabled'] ) {
			$btn_variables = $this->configs['components']['buttons']['variables'];
			$color_variables = array_merge( $color_variables, $btn_variables );
		}
		return $color_variables;
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
	 * Get Button Colors to Compile.
	 *
	 * @since 1.1
	 * @return array $boldgrid_colors Array containing SCSS variable name.
	 */
	public static function get_button_colors() {
		$boldgrid_colors = '';
		$palettes = json_decode( get_theme_mod( 'boldgrid_color_palette' ), true );

		if ( ! empty( $palettes ) ) {
			$current_palette = $palettes['state']['active-palette'];
			$colors = $palettes['state']['palettes'][ $current_palette ]['colors'];
			$i = 0;

			foreach ( $colors as $color ) {
				$i++;
				$boldgrid_colors .= '("color-' . $i . '" $' . $current_palette . '_' . $i . ' $text-contrast-' . $current_palette . '_' . $i . ')';
			}
			if ( ! empty( $palettes['state']['palettes'][ $current_palette ]['neutral-color'] ) ) {
				$boldgrid_colors .= '("neutral-color" $' . $current_palette . '-neutral-color $text-contrast-' . $current_palette . '-neutral-color)';
			}
		}

		return $boldgrid_colors;
	}

	/**
	 * Grabs the appropriate files for default button configs to compile.
	 *
	 * This will see what files are needed based on the configs for
	 * button-primary and button-secondary button classes.
	 *
	 * @since 1.2.4
	 * @return array $files An array of files to use in color compile.
	 */
	public function get_button_color_files( $files ) {
		$s = $this->configs['components']['buttons']['variables'];
		$path = $this->configs['customizer-options']['colors']['settings']['scss_directory']['framework_dir'] . '/buttons/';
		$configs = array();

		// Build an array of button-classes that are needed.
		if ( ! empty( $s['button-primary-classes'] ) ) {
			$configs[] = $s['button-primary-classes'];
		}

		if ( ! empty( $s['button-secondary-classes'] ) ) {
			$configs[] = $s['button-secondary-classes'];
		}

		if ( ! empty( $configs ) ) {
			foreach( $configs as $config ) {
				// Remove whitespace out of strings
				$config = str_replace( ' ', '', $config );
				// Make an array to filter.
				$config = explode( ',', str_replace( '.btn-', '', $config ) );
				// We don't need the base class.
				if ( ( $key = array_search( '.btn', $config ) ) !== false ) {
					unset( $config[$key] );
				}
				// Remove any color classes that are defined since we don't need them.
				$config = array_filter( $config, function( $classes ) {
					return strpos( $classes, 'color' ) === false;
				});
				// Translate configs to file path.
				foreach( $config as $file ) {
					$file = $file .'.scss';
					if ( file_exists( $path . $file ) ) {
						$files[] = $path . $file;
					}
				}
			}
		}

		// Add base file after the rest.
		$base = $path . 'base.scss';
		if ( file_exists( $base ) ) {
			$files[] = $base;
		}
		// Remove any duplicates from array.
		$files = array_unique( $files );

		return $files;
	}
}

<?php
/**
 * Class: Boldgrid_Framework_SCSS
 *
 * Functions for interfacing with ScssPhp\ScssPhp\Compiler
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
 * Functions for interfacing with ScssPhp\ScssPhp\Compiler
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
		$palettes = $this->get_palette();

		if ( ! empty( $palettes ) ) {
			$current_palette = $palettes['state']['active-palette'];

			$colors = array();

			if ( empty( $palettes['state']['palettes'][ $current_palette ]['colors'] ) ) {
				$activate = new Boldgrid_Framework_Activate( $this->configs );
				$option = 'theme_mods_' . get_stylesheet();
				$activate->set_palette( $option );
			}

			if ( is_array( $palettes['state']['palettes'][ $current_palette ]['colors'] ) ) {
				$colors = $palettes['state']['palettes'][ $current_palette ]['colors'];
			}

			$i = 0;
			foreach ( $colors as $color ) {
				$i++;
				$boldgrid_colors[ $current_palette . '_' . $i ] = $color;
			}
		}
		if ( false !== self::get_neutral_color() ) {
			$boldgrid_colors = array_merge( $boldgrid_colors, self::get_neutral_color() );
		}
		return $boldgrid_colors;
	}

	/**
	 * Get current palette colors.
	 *
	 * @since 1.2.8
	 * @return array Current Active Palette.
	 */
	public function get_palette() {
		global $boldgrid_theme_framework;

		$palette = ! empty( $boldgrid_theme_framework->palette_changeset ) ?
			$boldgrid_theme_framework->palette_changeset :
			get_theme_mod( 'boldgrid_color_palette' );
		$palettes = json_decode( $palette, true );

		if ( empty( $palettes ) ) {
			$activate = new Boldgrid_Framework_Activate( $this->configs );
			$option = 'theme_mods_' . get_stylesheet();
			$activate->set_palette( $option );
			$palette = ! empty( $boldgrid_theme_framework->palette_changeset ) ?
			$boldgrid_theme_framework->palette_changeset : get_theme_mod( 'boldgrid_color_palette' );
			$palettes = json_decode( $palette, true );
		}

		return $palettes;
	}

	/**
	 * Get SCSS list for $colors variable.
	 *
	 * @since 1.2.3
	 * @return array $boldgrid_colors Array containing SCSS variable name.
	 */
	public function get_color_list() {
		$boldgrid_colors = '';
		$palettes = $this->get_palette();

		if ( ! empty( $palettes ) ) {
			$current_palette = $palettes['state']['active-palette'];
			$colors = is_array( $palettes['state']['palettes'][ $current_palette ]['colors'] ) ?
				$palettes['state']['palettes'][ $current_palette ]['colors'] : array();

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
		$palettes = $this->get_palette();

		// This prevents throwing warnings in php7.4 when $palettes is null.
		if ( ! $palettes ) {
			return false;
		}

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
			$color_variables['ubtn-colors'] = $this->get_button_colors();
			$color_variables['ubtn-bgcolor'] = '$' . get_theme_mod( 'boldgrid_palette_class', 'palette-primary' ) . '_' . $this->get_button_default_color() . ';';
			$color_variables['ubtn-font-color'] = '$text-contrast-' . get_theme_mod( 'boldgrid_palette_class', 'palette-primary' ) . '_' . $this->get_button_default_color() . ';';
			$color_variables['ubtn-theme-color'] = get_theme_mod( 'boldgrid_palette_class', 'palette-primary' ) . '_' . $this->get_button_default_color() . ';';
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
			$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
			$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
			$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
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
		$luminance = null;
		if ( ! empty( $color ) ) {
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
		}
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
	public function get_button_colors() {
		$boldgrid_colors = '';
		$palettes = $this->get_palette();

		if ( ! empty( $palettes ) ) {
			$current_palette = $palettes['state']['active-palette'];
			$colors = is_array( $palettes['state']['palettes'][ $current_palette ]['colors'] ) ?
				$palettes['state']['palettes'][ $current_palette ]['colors'] : array();

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
	 * Gets default button color class.
	 *
	 * @since 1.1
	 *
	 * @return string $class The class of the default button color found.
	 */
	public function get_button_default_color() {
		$s = $this->configs['components']['buttons']['variables'];
		$classes = ! empty( $s['button-primary-classes'] ) ? $s['button-primary-classes'] : null;
		$class = array();
		if ( ! empty( $classes ) ) {
			$classes = str_replace( ' ', '', $classes );
			$classes = explode( ',', str_replace( '.btn-', '', $classes ) );
			// Get the default color class if it's defined.
			$class = array_filter( $classes, function( $c ) {
				return strpos( $c, 'color' ) !== false;
			});
		}

		// Use the class found if one is located or use the first color from palette.
		if ( empty( $class ) ) {
			$class[] = '1';
		}

		$class = reset( $class );
		$class = str_replace( 'color-', '', $class );

		return $class;
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
			foreach ( $configs as $config ) {
				// Remove whitespace out of strings
				$config = str_replace( ' ', '', $config );
				// Make an array to filter.
				$config = explode( ',', str_replace( '.btn-', '', $config ) );
				// We don't need the base class.
				if ( ( $key = array_search( '.btn', $config ) ) !== false ) {
					unset( $config[ $key ] );
				}
				// Remove any color classes that are defined since we don't need them.
				$config = array_filter( $config, function( $classes ) {
					return strpos( $classes, 'color' ) === false;
				});
				// Translate configs to file path.
				foreach ( $config as $file ) {
					$file = $file . '.scss';
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

	/**
	 * Normalizes hex, rgb, and rgba colors to rgba.
	 *
	 * This is mainly used for conversion to rgba, and running
	 * comparisons on colors used.
	 *
	 * Examples:
	 *
	 * rgb( 255, 255, 255 )
	 * rgba(0,0,0,.1)
	 * #fff
	 * #B4D455
	 *
	 * @since 2.0.0
	 *
	 * @param string $color Hex, rgb, or rgba strings to normalize.
	 *
	 * @return string $normalized Normalized color string (or empty).
	 */
	public function normalize( $color ) {
		$normalized = '';

		// Prepare color strings to normalize for comparison.
		$test = preg_replace( '/\s+/', '', $color );

		// Hex normalization.
		if ( false !== strpos( $test, '#' ) ) {
			$rgb = $this->convert_hex_to_rgb( $test );
			$normalized = "rgba({$rgb[0]},{$rgb[1]},{$rgb[2]},1)";
		}

		// RGB normalization and validation.
		if ( preg_match( '/rgb\((?:\s*\d+\s*,){2}\s*[\d]+\)/', $test ) ) {
			$normalized = str_replace( 'rgb', 'rgba', str_replace( ')', ',1)', $test ) );
		}

		// RGBA validation.
		if ( preg_match( '/rgba\((\s*\d+\s*,){3}[\d\.]+\)/', $test ) ) {
			$normalized = $test;
		}

		return $normalized;
	}

	/**
	 * Get color class associated with a color passed in.
	 *
	 * @since 2.0.0
	 *
	 * @param string $color a color to check if in palette and obtain color class for it.
	 * @param string $type CSS property to get color class for. Eg. 'background-color'.
	 *
	 * @return mixed $class String containing color class found for color or false.
	 */
	public function get_color_class( $color, $type ) {
		$class = '';
		$palette = $this->get_palette();
		$current_palette = $palette['state']['active-palette'];
		$colors = is_array( $palette['state']['palettes'][ $current_palette ]['colors'] ) ? $palette['state']['palettes'][ $current_palette ]['colors'] : array();
		$neutral_color = $this->get_neutral_color();

		// Check palette colors.
		foreach ( $colors as $k => $v ) {
			if ( $this->normalize( $v ) === $color ) {
				$class = 'color' . abs( $k + 1 ) . '-' . $type;
			}
		}

		// Check neutral color.
		if ( ! empty( $neutral_color ) ) {
			$neutral_color = $neutral_color[ $current_palette . '-neutral-color' ];
			$neutral_color = $this->normalize( $neutral_color );
			if ( $neutral_color === $color ) {
				$class = 'color-neutral-' . $type;
			}
		}

		return '' !== $class ? $class : false;
	}

	/**
	 * Format color palette to easily get color classes to apply.
	 *
	 * @since  2.0.0
	 *
	 * @param  string $colors Palette to format.
	 *
	 * @return array  $formatted Fromatted color palette.
	 */
	public function color_format( $colors ) {
		$formatted = array();
		$active_palette = get_theme_mod( 'boldgrid_palette_class', 'palette-primary' );

		foreach ( $colors as $k => $v ) {
			if ( $k === $active_palette . '-neutral-color' ) {
				$formatted['color-neutral'] = $v;
			} elseif ( strpos( $k, $active_palette ) !== false ) {
				$formatted[ 'color-' . preg_replace( '/[^0-9]/', '', $k ) ] = $v;
			} else {
				$formatted[ $k ] = $v;
			}
		}

		return $formatted;
	}

	/**
	 * Gets the size of the palette control to add.
	 *
	 * @since 2.0.0
	 *
	 * @param array $palette     The active palette to get the size of.
	 * @param bool  $transparent Palette selector allows transparency option?.
	 *
	 * @return string            Size of each palette square.
	 */
	public function get_palette_size( $palette, $transparent = false ) {
		$colors = 0;
		$max_size = 225;

		if ( ! empty( $palette ) ) {
			$colors = count( $palette );
		}

		if ( true === $transparent ) {
			$colors += 1;
		}

		return 0 !== $colors ? ( string ) floor( $max_size / $colors ) : '0';
	}
}

<?php
/**
 * Class: Boldgrid_Framework_Customizer_Typography
 *
 * This is the class responsible for adding the typography
 * section to the WordPress Customizer.
 *
 * @since      1.0.0
 * @category   Customizer
 * @package    Boldgrid_Framework
 * @subpackage Boldgrid_Framework_Customizer_Typography
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

// If this file is called directly, abort.
defined( 'WPINC' ) ? : die;

/**
 * Boldgrid_Framework_Customizer_Typography Class
 *
 * This is responsible for adding Typography section to the
 * WordPress customizer.
 *
 * @since 1.1
 */
class Boldgrid_Framework_Customizer_Typography {

	/**
	 * The BoldGrid Theme Framework configurations.
	 *
	 * @since     1.0.0
	 * @access    protected
	 * @var       string     $configs       The BoldGrid Theme Framework configurations.
	 */
	protected $configs;

	/**
	 * A list of typography settings and css class name relationships.
	 *
	 * This could be autogenerated, code in config for now. Used for creating classes
	 * as well as pass values into the post and page builder.
	 *
	 * @since 2.0.0
	 *
	 * @var array $typography_settings.
	 */
	protected static $typography_settings = array(
		array(
			'settings' => 'bgtfw_body_typography',
			'class_name' => 'bg-font-family-body',
		),
		array(
			'settings' => 'bgtfw_headings_typography',
			'class_name' => 'bg-font-family-heading',
		),
		array(
			'settings' => 'bgtfw_site_title_typography',
			'class_name' => 'bg-font-family-site-title',
		),
		array(
			'settings' => 'bgtfw_tagline_typography',
			'class_name' => 'bg-font-family-tagline',
		),
	);

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 *
	 * @param array $configs The BoldGrid Theme Framework configurations.
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
		$this->set_menu_typography( $configs );
	}

	/**
	 * Set dynamic menu typography settings.
	 *
	 * @since 2.0.0
	 *
	 * @param array $configs The BoldGrid Theme Framework configurations.
	 */
	public function set_menu_typography( $configs ) {
		foreach ( array_keys( $configs['menu']['locations'] ) as $location ) {
			self::$typography_settings[] = [
				'settings' => "bgtfw_menu_typography_{$location}",
				'class_name' => 'bg-font-family-menu-' . str_replace( '_', '-', $location ),
			];
		}
	}

	/**
	 * Get the current font settings.
	 *
	 * This method includes the currently saved theme mod value.
	 *
	 * @since 2.0.0
	 *
	 * @return array Settings.
	 */
	public function get_typography_settings() {
		$configs = $this->configs['customizer']['controls'];

		$settings = self::$typography_settings;
		foreach ( $settings as &$setting ) {
			$default = isset( $configs [ $setting['settings'] ] ) ? $configs [ $setting['settings'] ] : $configs ['bgtfw_menu_typography_main'];
			$setting['value'] = get_theme_mod( $setting['settings'], $default );
		}

		return $settings;
	}

	/**
	 * Get the Default Button font settings.
	 *
	 * This method includes the currently saved theme mod value.
	 *
	 * @since 2.12.0
	 *
	 * @param array $configs The name of the setting.
	 *
	 * @return array Settings.
	 */
	public function default_button_typography( $configs ) {
		$body_typography = get_theme_mod( 'bgtfw_body_typography' );

		$default_button_typography = $body_typography ? $body_typography : array(
			'font-family'    => 'Roboto',
			'variant'        => 'regular',
			'font-size'      => '16px',
			'text-transform' => 'none',
		);

		return $default_button_typography;
	}

	/**
	 * Classes that represent the font families chosen for theme.
	 *
	 * @since 1.2.4
	 *
	 * @return string css.
	 */
	public function generate_font_classes() {
		$menu_font = get_theme_mod( 'navigation_main_typography', false );
		$menu_font_family = ! empty( $menu_font['font-family'] ) ? $menu_font['font-family'] :
			$this->configs['customizer-options']['typography']['defaults']['navigation_font_family'];

		$css = '';
		$css .= ".bg-font-family-menu { font-family: $menu_font_family !important }";

		foreach ( $this->get_typography_settings() as $typography_setting ) {
			$font_family = isset( $typography_setting['value']['font-family'] ) ? $typography_setting['value']['font-family'] : $typography_setting['value']['default']['font-family'];
			if ( preg_match( '/\s/', $font_family ) ) {
				$font_family = '"' . $font_family . '"';
			}
			$css .= ".{$typography_setting['class_name']} { font-family: {$font_family} !important }";
		}

		return $css;
	}

	/**
	 * Adds font size CSS to style.css inline.
	 *
	 * @since 2.0.0
	 */
	public function add_font_size_css( $css ) {
		return $this->generate_font_size_css( $css );
	}

	/**
	 * Generates font sizes based on Bootstrap's LESS implementation.
	 *
	 * @since  2.0.0
	 *
	 * @param  string $css CSS to append styles to.
	 *
	 * @return string $css Generated CSS styles.
	 */
	public function generate_font_size_css( $css = '' ) {
		$css .= $this->generate_body_css();
		$css .= $this->generate_headings_css();
		$css .= $this->generate_font_classes();

		return apply_filters( 'bgtfw_inline_css', $css );
	}

	/**
	 * Generate Kirki font CSS for PPB editor
	 *
	 * @param string $css CSS to append styles to.
	 *
	 * @since 2.11.0
	 */
	public function inline_font_css( $css ) {
		$kirki_styles    = apply_filters( 'kirki_bgtfw_dynamic_css', Kirki_Modules_CSS::loop_controls( 'bgtfw' ) );
		$filtered_styles = preg_replace( '/font-family:(\w+(\s\w+)+);/', 'font-family:"${1}";', $kirki_styles );

		return $css . $filtered_styles;
	}

	/**
	 * Overrides Kirki Inline CSS.
	 *
	 * @since 2.2.2
	 */
	public function override_kirki_styles() {
		global $wp_filesystem;

		if ( ! $wp_filesystem ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';
			WP_Filesystem();
		}
		$kirki_styles    = apply_filters( 'kirki_bgtfw_dynamic_css', Kirki_Modules_CSS::loop_controls( 'bgtfw' ) );
		$filtered_styles = preg_replace( '/font-family:(\w+(\s\w+)+);/', 'font-family:"${1}";', $kirki_styles );

		/*
		 * Kirki no longer creates the kirki-css/styles.css file including their dynamic css.
		 * Therefore we must create it for them in order to use the styles in the editor.
		 */
		if ( ! $wp_filesystem->is_dir( wp_upload_dir()['basedir'] . '/kirki-css' ) ) {
			$wp_filesystem->mkdir( wp_upload_dir()['basedir'] . '/kirki-css' );
		}
		$wp_filesystem->put_contents( wp_upload_dir()['basedir'] . '/kirki-css/styles.css', $filtered_styles );
		Boldgrid_Framework_Customizer_Generic::add_inline_style( 'boldgrid-kirki-override', $filtered_styles );
	}

	/**
	 * Generates headings CSS to apply to frontend.
	 *
	 * @since  2.0.0
	 *
	 * @param  string $css CSS to append body styles to.
	 *
	 * @return string $css CSS for body styles.
	 */
	public function generate_body_css( $css = '' ) {
		// Body Font.
		$body_font = get_theme_mod( 'bgtfw_body_typography' );
		$body_font_size = ! empty( $body_font['font-size'] ) ? $body_font['font-size'] : $this->configs['customizer-options']['typography']['defaults']['body_font_size'];

		$body_base = (int) preg_replace( '/[^0-9]./', '', $body_font_size );
		$body_unit = preg_replace( '/[^a-z]/i', '', $body_font_size );
		$body_unit = empty( $body_unit ) ? 'px' : $body_unit;

		// Blockquotes.
		$blockquote = $body_base * 1.25;
		$css .= 'blockquote, blockquote p, .mod-blockquote {font-size:' . $blockquote . $body_unit . ';}';

		return $css;
	}

	/**
	 * Sanitize Font Size.
	 *
	 * General sanitization of standalone font size control.
	 *
	 * @since 2.11.0
	 *
	 * @param string $value The value to sanitize.
	 *
	 * @return string Sanitized value.
	 */
	public function sanitize_font_size( $value ) {
		$matches  = array();
		$int_base = intval( $value );
		// If no number is present in value, unset value.
		if ( empty( $int_base ) ) {
			return false;
		}

		$unit = str_replace( $int_base, '', $value );

		if ( empty( $unit ) ) {
			$unit = 'px';
		}

		// Validate unit given.
		preg_match( '/(em|ex|%|px|cm|mm|in|pt|pc|rem)/', $unit, $matches );

		// If the unit matches, append the value with unit, otherwise use 'px'.
		if ( ! empty( $matches ) ) {
			$sanitized_value = $int_base . $matches[0];
		} else {
			$sanitized_value = $int_base . 'px';
		}

		return $sanitized_value;
	}

	/**
	 * Sanitize Responsive Fonts.
	 *
	 * Sanitize callback for responsive fonts validate_callback argument.
	 *
	 * @since 2.11.0
	 *
	 * @param string $value    Value to sanitize.
	 *
	 * @return string Sanitized value.
	 */
	public function sanitize_responsive_fonts( $value ) {
		$sanitized_value = array();
		$value           = is_string( $value ) ? json_decode( $value, true ) : $value;
		if ( is_array( $value ) ) {
			foreach ( $value as $device => $size ) {
				$matches  = array();
				$int_base = intval( $size );

				// If no number is present in value, unset value.
				if ( empty( $int_base ) ) {
					unset( $sanitized_value[ $device ] );
				}

				$unit = str_replace( $int_base, '', $size );
				if ( empty( $unit ) ) {
					$unit = 'px';
				}

				// Validate unit given.
				preg_match( '/(em|ex|%|px|cm|mm|in|pt|pc|rem)/', $unit, $matches );

				// If the unit matches, append the value with unit, otherwise use 'px'.
				if ( ! empty( $matches ) ) {
					$sanitized_value[ $device ] = $int_base . $matches[0];
				} else {
					$sanitized_value[ $device ] = $int_base . 'px';
				}
			}
		}
		return wp_json_encode( $sanitized_value );
	}

	/**
	 * WP Ajax Responsive Font Sizes.
	 *
	 * Ajax callback to get the font sizes for customizer
	 * Preview.
	 *
	 * @see BoldGrid_Framework::customizer_typography() for WP Ajax action hook definition.
	 *
	 * @since 2.11.0
	 */
	public function wp_ajax_responsive_font_sizes() {
		check_ajax_referer( 'bgtfw_responsive_font_sizes', 'responsiveFontSizesNonce' );
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			wp_die( -1 );
		}
		$selectors = $this->configs['customizer-options']['typography']['selectors'];
		$value     = $this->sanitize_responsive_fonts( $_POST['responsiveFontSizes'] );

		$control_id = $_POST['controlId'];
		if ( 'bgtfw_headings_responsive_font_size' === $control_id ) {
			$css = $this->generate_responsive_headings( $value, $selectors, '' );
		} else {
			$css = $this->generate_responsive_font_css( $control_id, $value );
		}

		wp_send_json_success( array(
			'css' => $css,
		) );
	}

	/** Generate Responive Font CSS
	 *
	 * Generate CSS for responsive font sizes.
	 *
	 * @param string $control_id The control ID.
	 * @param string $value      The value.
	 *
	 * @return string $css CSS for responsive font sizes.
	 *
	 * @since 2.11.0
	 */
	public function generate_responsive_font_css( $control_id, $value ) {
		$value     = is_string( $value ) ? json_decode( $value, true ) : $value;
		$css       = '';
		$selectors = $this->configs['customizer-options']['typography']['responsive_font_controls'][ $control_id ]['output_selector'];
		// XS / Phone.
		$font_size = ! empty( $value['phone'] ) ? $value['phone'] : false;
		if ( $font_size ) {
			$css .= '@media only screen and (max-width: 766px) {';
			$css .= $selectors;
			$css .= '{ font-size: ' . $font_size . '!important;}';
			$css .= '}';
		}

		// SM / Tablet.
		$font_size = ! empty( $value['tablet'] ) ? $value['tablet'] : false;
		if ( $font_size ) {
			$css .= '@media only screen and (min-width: 767px) and (max-width: 990px) {';
			$css .= $selectors;
			$css .= '{ font-size: ' . $font_size . '!important;}';
			$css .= '}';
		}

		// MD / Desktop.
		$font_size = ! empty( $value['desktop'] ) ? $value['desktop'] : false;
		if ( $font_size ) {
			$css .= '@media only screen and (min-width: 991px) and (max-width: 1198px) {';
			$css .= $selectors;
			$css .= '{ font-size: ' . $font_size . '!important;}';
			$css .= '}';
		}

		// LG / Large Desktop.
		$font_size = ! empty( $value['large'] ) ? $value['large'] : false;
		if ( $font_size ) {
			$css .= '@media only screen and (min-width: 1199px) {';
			$css .= $selectors;
			$css .= '{ font-size: ' . $font_size . '!important;}';
			$css .= '}';
		}

		return $css;
	}

	/**
	 * Add a nonce for Customizer for responsive heading sizes.
	 *
	 * @param array $nonces An array of customizer nonces.
	 *
	 * @return array An array of customizer nonces.
	 *
	 * @since 2.11.0
	 */
	public function header_column_nonces( $nonces ) {
		$nonces['bgtfw_responsive_font_sizes'] = wp_create_nonce( 'bgtfw_responsive_font_sizes' );
		return $nonces;
	}

	/**
	 * Generates headings CSS to apply to frontend.
	 *
	 * @since  2.0.0
	 *
	 * @param  string $css CSS to append headings styles to.
	 *
	 * @return string $css CSS for headings styles.
	 */
	public function generate_headings_css( $css = '' ) {
		$headings_font    = get_theme_mod( 'bgtfw_headings_typography' );
		$headings_base    = get_theme_mod( 'bgtfw_headings_font_size' );
		$base_int         = intval( $headings_base );
		$headings_unit    = str_replace( $base_int, '', $headings_base );
		$headings_unit    = empty( $headings_unit ) ? 'px' : $headings_unit;
		$headings_base    = $base_int;
		$responsive_sizes = json_decode( get_theme_mod( 'bgtfw_headings_responsive_font_size' ), true );
		$selectors        = $this->configs['customizer-options']['typography']['selectors'];

		foreach ( $selectors as $selector => $options ) {
			if ( 'subheadings' === $options['type'] ) {
				continue;
			}

			if ( 'headings' !== $options['type'] ) {
				continue;
			}

			$css .= $selector . '{font-size:';

			if ( 'floor' === $options['round'] ) {
				$css .= floor( $headings_base * $options['amount'] );
			}

			if ( 'ceil' === $options['round'] ) {
				$css .= ceil( $headings_base * $options['amount'] );
			}

			$css .= "$headings_unit;}";
		}

		if ( $responsive_sizes ) {
			$css .= $this->generate_responsive_headings( $responsive_sizes, $selectors, $css );
		}

		$css .= $this->generate_headings_color_css( 'bgtfw_headings_color', '', $selectors );

		return $css;
	}

	/**
	 * Generate Responsive Headings CSS.
	 *
	 * @param array  $responsive_sizes Responsive font sizes.
	 * @param array  $selectors        Heading selectors.
	 * @param string $css              CSS to append responsive headings styles to.
	 *
	 * @return string $css
	 */
	public function generate_responsive_headings( $responsive_sizes, $selectors, $css ) {
		$responsive_sizes = is_string( $responsive_sizes ) ? json_decode( $responsive_sizes, true ) : $responsive_sizes;
		if ( isset( $responsive_sizes['phone'] ) ) {
			$headings_size = preg_split( '/(?<=[0-9])(?=[a-z]+)/i', $responsive_sizes['phone'] );
			$headings_base = $headings_size[0];
			$headings_unit = $headings_size[1];

			$css .= '@media only screen and (max-width: 766px) {';
				foreach ( $selectors as $selector => $options ) {
					if ( 'subheadings' === $options['type'] ) {
						continue;
					}

					$css .= $selector . '{font-size:';

					if ( 'floor' === $options['round'] ) {
						$css .= floor( $headings_base * $options['amount'] );
					}

					if ( 'ceil' === $options['round'] ) {
						$css .= ceil( $headings_base * $options['amount'] );
					}

					$css .= "$headings_unit;}";
				}
				$css .= '}';
		}
		if ( isset( $responsive_sizes['tablet'] ) ) {
			$headings_size = preg_split( '/(?<=[0-9])(?=[a-z]+)/i', $responsive_sizes['tablet'] );
			$headings_base = $headings_size[0];
			$headings_unit = $headings_size[1];

			$css .= '@media only screen and (min-width: 767px) and (max-width: 990px) {';
				foreach ( $selectors as $selector => $options ) {
					if ( 'subheadings' === $options['type'] ) {
						continue;
					}

					$css .= $selector . '{font-size:';

					if ( 'floor' === $options['round'] ) {
						$css .= floor( $headings_base * $options['amount'] );
					}

					if ( 'ceil' === $options['round'] ) {
						$css .= ceil( $headings_base * $options['amount'] );
					}

					$css .= "$headings_unit;}";
				}
				$css .= '}';
		}
		if ( isset( $responsive_sizes['desktop'] ) ) {
			$headings_size = preg_split( '/(?<=[0-9])(?=[a-z]+)/i', $responsive_sizes['desktop'] );
			$headings_base = $headings_size[0];
			$headings_unit = $headings_size[1];

			$css .= '@media only screen and (min-width: 991px) and (max-width: 1198px) {';
			foreach ( $selectors as $selector => $options ) {
				if ( 'subheadings' === $options['type'] ) {
					continue;
				}

				$css .= $selector . '{font-size:';

				if ( 'floor' === $options['round'] ) {
					$css .= floor( $headings_base * $options['amount'] );
				}

				if ( 'ceil' === $options['round'] ) {
					$css .= ceil( $headings_base * $options['amount'] );
				}

				$css .= "$headings_unit;}";
			}
			$css .= '}';
		}
		if ( isset( $responsive_sizes['large'] ) ) {
			$headings_size = preg_split( '/(?<=[0-9])(?=[a-z]+)/i', $responsive_sizes['large'] );
			$headings_base = $headings_size[0];
			$headings_unit = $headings_size[1];

			$css .= '@media only screen and (min-width: 1199px) {';
			foreach ( $selectors as $selector => $options ) {
				if ( 'subheadings' === $options['type'] ) {
					continue;
				}

				$css .= $selector . '{font-size:';

				if ( 'floor' === $options['round'] ) {
					$css .= floor( $headings_base * $options['amount'] );
				}

				if ( 'ceil' === $options['round'] ) {
					$css .= ceil( $headings_base * $options['amount'] );
				}

				$css .= "$headings_unit;}";
			}
			$css .= '}';
		}

		return $css;
	}
	/**
	 * Generates headings color CSS to apply to frontend.
	 *
	 * @since  2.0.0
	 *
	 * @param  string $theme_mod Name of thememod to get color palette settings from.
	 * @param  string $section   CSS selector for section to apply heading colors to.
	 * @param  array  $selectors Array of heading CSS selectors from configs.
	 * @param  string $css       CSS to append headings styles to.
	 *
	 * @return string $css       Output CSS for headings color styles.
	 */
	public function generate_headings_color_css( $theme_mod, $section, $selectors = array(), $css = '' ) {
		$theme_mod = get_theme_mod( $theme_mod, false );

		if ( empty( $theme_mod ) ) {
			return;
		}

		list( $theme_mod ) = explode( ':', $theme_mod );
		$theme_mod = "var(--{$theme_mod})";

		if ( empty( $selectors ) ) {
			$selectors = $this->configs['customizer-options']['typography']['selectors'];
		}

		$found = array();

		foreach ( $selectors as $selector => $options ) {
			if ( 'headings' === $options['type'] ) {
				$found[] = "$section $selector";
			}
		}

		$found = implode( ', ', $found );
		$css .= "$found{color:{$theme_mod};}";

		return $css;
	}

	/**
	 * Retrieve formatted output configs for typography selectors.
	 *
	 * @since 2.11.0
	 *
	 * @param array  $configs  Config array.
	 * @param string $elements Element Selector string.
	 *
	 * @return array $values Formatted output values.
	 */
	public function get_typography_output( $configs, $elements ) {
		$props  = [ 'font-family', 'font-size', 'line-height', 'text-transform', 'variant', 'font-style' ];
		$values = [];
		foreach ( $props as $prop ) {
			$values[] = [
				'element'  => $elements,
				'property' => $prop,
				'choice'   => $prop,
				'context'  => array( 'front', 'editor' ),
			];
		}

		return $values;
	}

	/**
	 * Retrieves formatted output configs for headings selectors.
	 *
	 * @since  2.0.0
	 *
	 * @param  string $configs BGTFW Configurations.
	 *
	 * @return string $values  Formatted output configs.
	 */
	public function get_output_values( $configs ) {
		$selectors = array();
		foreach ( $configs['customizer-options']['typography']['selectors'] as $selector => $options ) {
			if ( 'headings' === $options['type'] ) {
				$selectors[ $selector ] = $options;
			}
		}
		$elements = implode( ', ', array_keys( $selectors ) );
		$props = [ 'font-family', 'line-height', 'text-transform', 'variant', 'font-style' ];
		$values = [];

		foreach ( $props as $prop ) {
			$values[] = [
				'element' => $elements,
				'property' => $prop,
				'choice' => $prop,
				'context' => [ 'front', 'editor' ],
			];
		}

		return $values;
	}
}

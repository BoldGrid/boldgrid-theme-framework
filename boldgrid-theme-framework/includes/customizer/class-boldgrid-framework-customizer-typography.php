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
	 * @since     1.0.0
	 * @param     string $configs       The BoldGrid Theme Framework configurations.
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
	}

	/**
	 * This outputs the javascript needed to automate the live settings preview.
	 * Also keep in mind that this function isn't necessary unless your settings
	 * are using 'transport'=>'postMessage' instead of the default 'transport'
	 * => 'refresh'
	 *
	 * Used by hook: 'customize_preview_init'
	 *
	 * @see add_action( 'customize_preview_init', $func )
	 * @since 1.0.0
	 */
	public function live_preview() {
		$handle = 'boldgrid-framework-customizer-typography-preview';
		wp_register_script(
			$handle,
			$this->configs['framework']['js_dir'] . 'customizer/typography-preview.js',
			array( 'jquery', 'customize-preview' ),
			$this->configs['version'],
			true
		);

		wp_enqueue_script( $handle );

		// Add data for script.
		$wp_scripts = wp_scripts();
		$font_configs = $this->configs['customizer-options']['typography']['selectors'];
		$wp_scripts->add_data( $handle, 'data', sprintf( 'var _typographyOptions = %s;', wp_json_encode( $font_configs ) ) );
		wp_localize_script( $handle, '_typographyClasses', $this->configs['template']['tagline-classes'] );
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
			$setting['value'] = get_theme_mod( $setting['settings'],
				$configs[ $setting['settings'] ]['default'] );
		}

		return $settings;
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
			$css .= ".{$typography_setting['class_name']} { font-family: {$typography_setting['value']['font-family']} !important }";
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

		$body_base = ( int ) preg_replace( '/[^0-9]./', '', $body_font_size );
		$body_unit = preg_replace( '/[^a-z]/i', '', $body_font_size );
		$body_unit = empty( $body_unit ) ? 'px' : $body_unit;

		// Blockquotes.
		$blockquote = $body_base * 1.25;
		$css .= 'blockquote, blockquote p, .mod-blockquote {font-size:' . $blockquote . $body_unit . ';}';

		return $css;
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
		$headings_font = get_theme_mod( 'bgtfw_headings_typography', $this->configs['customizer-options']['typography']['defaults']['headings_font_size'] );

		$headings_font_size = ! empty( $headings_font['font-size'] ) ? $headings_font['font-size'] : $this->configs['customizer-options']['typography']['defaults']['headings_font_size'];

		$headings_base = ( int ) preg_replace( '/[^0-9]./', '', $headings_font_size );
		$headings_unit = preg_replace( '/[^a-z]/i', '', $headings_font_size );
		$headings_unit = empty( $headings_unit ) ? 'px' : $headings_unit;

		$selectors = $this->configs['customizer-options']['typography']['selectors'];

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

		$css .= $this->generate_headings_color_css( 'bgtfw_headings_color', '', $selectors );
		$css .= $this->generate_headings_color_css( 'bgtfw_header_headings_color', '.site-header :not(.bgtfw-widget-row)', $selectors );
		$css .= $this->generate_headings_color_css( 'bgtfw_footer_headings_color', '.site-footer :not(.bgtfw-widget-row)', $selectors );

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

		$theme_mod = explode( ':', $theme_mod );
		$theme_mod = array_pop( $theme_mod );

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
}

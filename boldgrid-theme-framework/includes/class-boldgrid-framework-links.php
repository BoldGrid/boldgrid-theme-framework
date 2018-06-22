<?php
/**
 * File: Boldgrid_Framework_Links
 *
 * Class: Handle body link styling.
 *
 * @since      2.0.0
 * @category   Customizer
 * @package    Boldgrid_Framework
 * @subpackage Boldgrid_Framework_Links
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

/**
 * Class: Handle body link styling.
 *
 * @since 2.0.0
 */
class Boldgrid_Framework_Links {

	/**
	 * BGTFW Configs.
	 *
	 * @since 2.0.0
	 *
	 * @var array Configs.
	 */
	protected $configs;

	/**
	 * Set the BGTFW configs.
	 *
	 * @since 2.0.0
	 *
	 * @param array $configs BGTFW configs.
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
	}

	/**
	 * Selectors to use for creating content.
	 *
	 * NOTE: do not use this directly. Only set in here for passing into config.
	 * @var array
	 */
	public static $defaultLinkSelectors = [
		'#content a',
		'.mce-content-body a',
	];

	/**
	 * Add the styles to the front end.
	 *
	 * @since 2.0.0
	 */
	public function add_styles_frontend() {
		Boldgrid_Framework_Customizer_Generic::add_inline_style( "bgftw-body-link", $this->get_styles() );
	}

	/**
	 * Get the css and append it to the string for the mce content.
	 *
	 * @since 2.0.0
	 *
	 * @param string $css CSS for the content.
	 */
	public function add_styles_editor( $css ) {
		$css .= $this->get_styles();
		return $css;
	}

	/**
	 * Get the CSS needed for links.
	 *
	 * @since 2.0.0
	 *
	 * @return string CSS for the styling links.
	 */
	public function get_styles() {
		$color = get_theme_mod( 'bgtfw_body_link_color' ) ?: '';
		$color_hover = get_theme_mod( 'bgtfw_body_link_color_hover' ) ?: '';
		$decoration = get_theme_mod( 'bgtfw_body_link_decoration' );
		$decoration_hover = get_theme_mod( 'bgtfw_body_link_decoration_hover' );

		$color = explode( ':', $color )[1];
		$color_hover = explode( ':', $color_hover )[1];
		$decoration = $decoration ? 'underline' : 'none';
		$decoration_hover = $decoration_hover ? 'underline' : 'none';
		$excludes = ':not(.btn):not(.button-primary):not(.button-secondary)';

		// Grab the filtered selectors.
		$selectors = $this->configs['customizer']['controls']['bgtfw_body_link_color']['choices']['selectors'];

		$css = '';
		foreach ( $selectors as $selector ) {
			$selector = $selector . $excludes;
			$css .= "${selector} {color: ${color};text-decoration: ${decoration};}";
			$css .= "${selector}:hover {color: ${color_hover};text-decoration: ${decoration_hover};}";
		}

		return $css;
	}
}

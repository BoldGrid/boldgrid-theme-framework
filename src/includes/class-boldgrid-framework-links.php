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
	 *
	 * @var array
	 */
	public static $default_link_selectors = [
		'.main a',
		'.page-header-wrapper a',
		'.mce-content-body a',
		'.template-header a',
	];

	/**
	 * Prefixes for theme mods.
	 *
	 * NOTE: do not use this directly. Only set in here for passing into config.
	 *
	 * @var array
	 */
	public static $prefixes = [
		'bgtfw_body',
		'bgtfw_posts_date',
		'bgtfw_posts_byline',
		'bgtfw_posts_tags',
		'bgtfw_posts_cats',
		'bgtfw_posts_navigation',
		'bgtfw_blog_post_header_byline',
		'bgtfw_blog_post_header_date',
		'bgtfw_blog_post_tags',
		'bgtfw_blog_post_cats',
		'bgtfw_blog_post_comments',
		'bgtfw_blog_post_readmore',
	];

	/**
	 * Add the styles to the front end.
	 *
	 * @since 2.0.0
	 */
	public function add_styles_frontend() {
		foreach ( self::$prefixes as $prefix ) {
			$css_prefix = str_replace( '_', '-', $prefix );
			$styles = $this->get_styles( $prefix );
			if ( ! empty( $styles ) ) {
				Boldgrid_Framework_Customizer_Generic::add_inline_style( "${css_prefix}-link", $styles );
			}
		}
	}

	/**
	 * Get the css and append it to the string for the mce content.
	 *
	 * @since 2.0.0
	 *
	 * @param string $css CSS for the content.
	 */
	public function add_styles_editor( $css ) {
		foreach ( self::$prefixes as $prefix ) {
			$css .= $this->get_styles( $prefix );
		}
		return $css;
	}

	/**
	 * Get the CSS needed for links.
	 *
	 * @since 2.0.0
	 *
	 * @return string CSS for the styling links.
	 */
	public function get_styles( $prefix ) {
		$css = '';
		if ( empty( $this->configs['customizer']['controls'][ "${prefix}_link_color_display" ] ) || 'custom' === get_theme_mod( "${prefix}_link_color_display" ) ) {
			$color = get_theme_mod( "${prefix}_link_color" ) ?: '';
			$color_hover = get_theme_mod( "${prefix}_link_color_hover" ) ?: 0;
			$decoration = get_theme_mod( "${prefix}_link_decoration" );
			$decoration_hover = get_theme_mod( "${prefix}_link_decoration_hover" );

			// Apply color as CSS variable.
			list( $color_variable ) = explode( ':', $color );
			$color_variable = "var(--${color_variable})";

			if ( empty( $color ) ) {
				return $css;
			}

			$color = explode( ':', $color )[1];
			$ari_color = ariColor::newColor( $color );
			$lightness = min( $ari_color->lightness + $color_hover, 100 );
			$lightness = max( $lightness, 0 );
			$color_hover = $ari_color->getNew( 'lightness', $lightness )->toCSS( 'rgba' );
			$decoration = $decoration;
			$decoration_hover = $decoration_hover;
			$excludes = '';

			// Grab the filtered selectors.
			if ( ! empty( $this->configs['customizer']['controls'][ "${prefix}_link_color" ]['choices']['selectors'] ) ) {
				$selectors = $this->configs['customizer']['controls'][ "${prefix}_link_color" ]['choices']['selectors'];

				foreach ( $selectors as $selector ) {
					$selector = $selector . $excludes;
					$css .= "${selector} {color: ${color_variable};text-decoration: ${decoration};}";
					$css .= "${selector}:hover, ${selector}:focus {color: ${color_hover};text-decoration: ${decoration_hover};}";
				}
			}
		}

		return $css;
	}
}

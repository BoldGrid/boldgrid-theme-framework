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
	public static $default_link_selectors = array(
		'.main a:not(.btn)',
		'.custom-sub-menu a:not(.btn)',
		'.page-header-wrapper a:not(.btn)',
		'.mce-content-body *:not( .menu-item ) > a:not(.btn)',
		'.template-header a:not(.btn)',
		'.template-footer a:not(.btn)',
		'.template-sticky-header a:not(.btn)',
	);

	/**
	 * Selectors to use for edit vars.
	 *
	 * NOTE: do not use this directly. Only set in here for passing into config.
	 *
	 * @var array
	 */
	public static $edit_link_selectors = [
		'.article-wrapper a',
		'p.logged-in-as',
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
			$color = BoldGrid::color_from_class( $color_variable );
			$color_variable = "var(--${color_variable})";

			if ( empty( $color ) ) {
				return $css;
			}

			$ari_color = ariColor::newColor( $color );
			$lightness = min( $ari_color->lightness + $color_hover, 100 );
			$lightness = max( $lightness, 0 );
			$color_hover = $ari_color->getNew( 'lightness', $lightness )->toCSS( 'hsla' );
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

				if ( 'bgtfw_body' === $prefix ) {
					$footer_link_color  = explode( ':', get_theme_mod( 'bgtfw_footer_links' ) )[1];
					$footer_ari_color   = ariColor::newColor( $color );
					$footer_color_hover = get_theme_mod( "${prefix}_link_color_hover" ) ?: 0;
					$footer_lightness   = min( $footer_ari_color->lightness + $footer_color_hover, 100 );
					$footer_lightness   = max( $footer_lightness, 0 );
					$footer_color_hover = $footer_ari_color->getNew( 'lightness', $footer_lightness )->toCSS( 'hsla' );

					foreach ( array( '1', '2', '3', '4', '5', 'neutral' ) as $sidebar_color_class ) {
						$sidebar_color_value = BoldGrid::color_from_class( $sidebar_color_class );
						$sidebar_color_hover = get_theme_mod( 'bgtfw_body_link_color_hover' ) ? get_theme_mod( 'bgtfw_body_link_color_hover' ) : 0;
						$sidebar_ari_color   = ariColor::newColor( $sidebar_color_value );
						$sidebar_lightness   = min( $sidebar_ari_color->lightness + $sidebar_color_hover, 100 );
						$sidebar_lightness   = max( $sidebar_lightness, 0 );
						$sidebar_color_hover = $sidebar_ari_color->getNew( 'lightness', $sidebar_lightness )->toCSS( 'hsla' );

						$css .= ".sidebar.color-${sidebar_color_class}-link-color a:not( .btn ):hover, .sidebar.color-${sidebar_color_class}-link-color a:not( .btn ):focus { color: ${sidebar_color_hover} !important; }";
					}

					$css .= "#colophon .bgtfw-footer.footer-content *:not( .menu-item ) > a:not( .btn ) { text-decoration: ${decoration};}";
					$css .= "#colophon .bgtfw-footer.footer-content *:not( .menu-item ) > a:not( .btn ):hover, .bgtfw-footer.footer-content *:not( .menu-item ) > a:not( .btn ):focus {color: ${footer_color_hover};text-decoration: ${decoration_hover};}";
				}
			}
		}

		return $css;
	}
}

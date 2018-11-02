<?php
/**
 * File: Boldgrid_Framework_Sticky_Header
 *
 * Class: Handle sticky header items' display.
 *
 * @since      2.0.3
 * @package    Boldgrid_Framework
 * @subpackage Boldgrid_Framework_Sticky_Header
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

/**
 * Class: Boldgrid_Framework_Sticky_Header
 *
 * @since 2.0.0
 */
class Boldgrid_Framework_Sticky_Header {

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
	 * Add the styles to the front end.
	 *
	 * @since 2.0.3
	 */
	public function add_styles_frontend() {
		Boldgrid_Framework_Customizer_Generic::add_inline_style( 'sticky-header-display', $this->get_styles() );
	}


	/**
	 * Get the CSS needed for hiding and showing sticky-header items.
	 *
	 * @since 2.0.3
	 *
	 * @return string CSS for the styling sticky-header items' display.
	 */
	public function get_styles() {
		$selectors = [];
		$theme_mod = get_theme_mod( 'bgtfw_header_layout' );

		foreach ( $theme_mod as $section ) {
			if ( ! empty( $section['items'] ) ) {
				foreach ( $section['items'] as $item ) {
					if ( ! empty( $item['sticky'] ) ) {
						foreach ( $item['sticky'] as $sticky ) {
							if ( 'show' !== $sticky['display'] ) {
								$selectors[] = '.bgtfw-header-stick ' . $sticky['selector'];
							}
						}
					}
				}
			}
		}

		return implode( ', ', $selectors ) . '{ display:none }';
	}
}

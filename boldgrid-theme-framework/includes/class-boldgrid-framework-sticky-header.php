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
		$css = '';
		$theme_mods = [];
		$theme_mods[] = BoldGrid::create_uids( 'bgtfw_header_layout' );
		$theme_mods[] = BoldGrid::create_uids( 'bgtfw_sticky_header_layout' );
		$theme_mods[] = BoldGrid::create_uids( 'bgtfw_footer_layout' );

		foreach ( $theme_mods as $theme_mod ) {
			$selectors = [];
			foreach ( $theme_mod as $key => $section ) {
				if ( ! empty( $section['items'] ) ) {
					foreach ( $section['items'] as $k => $item ) {
						if ( ! empty( $item['display'] ) ) {
							foreach ( $item['display'] as $display ) {
								if ( 'show' !== $display['display'] ) {
									$selectors[] = '.bgtfw-header-stick .' . $item['uid'] . ' ' . $display['selector'];
								}
							}
						}
					}
				}
			}

			$css .= ! empty( $selectors ) ? implode( ', ', $selectors ) . '{ display: none; }' : '';
		}

		return apply_filters( 'bgtfw_sticky_header_display_css', $css );
	}
}

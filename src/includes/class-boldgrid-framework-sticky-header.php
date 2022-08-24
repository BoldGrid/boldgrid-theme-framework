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
		if ( 'default' !== get_theme_mod( 'bgtfw_header_preset' ) && 'custom' !== get_theme_mod( 'bgtfw_header_preset' ) ) {
			return;
		}

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
		$selectors = [];
		$theme_mods = [];

		foreach ( [ 'bgtfw_header_layout', 'bgtfw_header_layout_custom', 'bgtfw_sticky_header_layout', 'bgtfw_sticky_header_layout_custom', 'bgtfw_footer_layout' ] as $type ) {
			if ( false !== strpos( $type, 'custom' ) ) {
				$type = BoldGrid::create_uids( $type, 'custom' );
			} else {
				$type = BoldGrid::create_uids( $type );
			}

			if ( ! empty( $type ) ) {
				$theme_mods[] = $type;
			}
		}

		if ( ! empty( $theme_mods ) ) {
			foreach ( $theme_mods as $theme_mod ) {
				foreach ( $theme_mod as $key => $section ) {
					if ( ! empty( $section['items'] ) ) {
						foreach ( $section['items'] as $k => $item ) {
							if ( ! empty( $item['display'] ) ) {
								foreach ( $item['display'] as $display ) {
									if ( 'hide' === $display['display'] ) {
										$selectors[] = '.' . $item['uid'] . ' ' . $display['selector'];
									}
								}
							}
						}
					}
				}
			}

			$selectors = ! empty( $selectors ) ? implode( ', ', $selectors ) . '{ display: none !important; }' : ':not(BGTFW){}';
		}

		return apply_filters( 'bgtfw_sticky_header_display_css', $selectors );
	}
}

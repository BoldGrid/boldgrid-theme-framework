<?php
/**
 * Class: Boldgrid_Framework_Customizer_Footer
 *
 * This is the class responsible for adding the footer's functionality
 * to the footer.  It contains all controls for the custom panel in the
 * WordPress customizer under Advanced > Footer Settings.
 *
 * @since      1.0.0
 * @category   Customizer
 * @package    Boldgrid_Framework
 * @subpackage Boldgrid_Framework_Customizer_Footer
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

// If this file is called directly, abort.
defined( 'WPINC' ) ? : die;

/**
 * Class: Boldgrid_Framework_Customizer_Footer
 *
 * This is the class responsible for adding the footer's functionality
 * to the footer.  It contains all controls for the custom panel in the
 * WordPress customizer under Advanced > Footer Settings.
 *
 * @since      1.0.0
 */
class Boldgrid_Framework_Customizer_Footer {

	/**
	 *  Responsible for adding the attribution links to the footer of a BoldGrid theme.
	 *
	 *  @since     1.0.0
	 */
	public function attribution_display_action() {
		?>
		<div <?php BoldGrid::add_class( 'attribution_theme_mods_wrapper', [ 'attribution-theme-mods-wrapper' ] ); ?>>
		<?php

			$theme_mods = '';

			// BoldGrid.com Link.
			if ( ! get_theme_mod( 'hide_boldgrid_attribution' ) ) {
				$theme_mods .= sprintf(
					'<span class="link boldgrid-attribution-link">%s <a href="%s" rel="nofollow" target="_blank">%s</a></span>',
					__( 'Built with', 'bgtfw' ),
					'http://boldgrid.com/',
					__( 'BoldGrid', 'bgtfw' )
				);
			}

			// WordPress.org Link.
			if ( ! get_theme_mod( 'hide_wordpress_attribution' ) ) {
				$theme_mods .= sprintf(
					'<span class="link wordpress-attribution-link">%s <a href="%s" rel="nofollow" target="_blank">%s</a></span>',
					__( 'Powered by', 'bgtfw' ),
					'https://wordpress.org/',
					__( 'WordPress', 'bgtfw' )
				);
			}

			// Host Link.
			$host_attribution = get_theme_mod( 'host_attribution' );
			if ( ! empty( $host_attribution ) && ! get_theme_mod( 'hide_host_attribution' ) ) {
				$theme_mods .= '<span class="link host-attribution-link">' . $host_attribution . '</span>';
			}

			// Allow plugins or themes to add additional attribution links to footer.
			$additional_links = '';
			$additional_links = apply_filters( 'bgtfw_attribution_links', $additional_links );
			$theme_mods .= $additional_links;

			$allowed = [
				'a' => [
					'href' => [],
					'title' => [],
					'rel' => [],
					'target' => [],
				],
				'span' => [
					'class' => [],
				],
			];
			?>
			<span <?php BoldGrid::add_class( 'attribution_theme_mods', [ 'attribution-theme-mods' ] ); ?>><?php echo wp_kses( $theme_mods, $allowed ); ?></span>
		</div>
		<?php
	}

	/**
	 * Handles attribution links in footer customizer control.
	 *
	 * @since 2.1.1
	 */
	public static function customize_attribution() {
		?>
		<style type="text/css" id="bgtfw-attribution-cntrl">
			.repeater-control.attribution [data-attribution="hide_boldgrid_attribution"], .repeater-control.attribution [data-attribution="hide_boldgrid_attribution"] + label { display: none; }
		</style>
	<?php
	}

	/**
	 * Handles attribution links in footer for customizer.
	 *
	 * @since 2.1.1
	 */
	public static function customize_links() {
		$exists = false;
		$name = 'bgtfw_footer_layout';
		$theme_mod = get_theme_mod( $name );

		// Ensure array.
		if ( ! is_array( $theme_mod ) ) {
			$theme_mod = [];
		}

		// Check if exists.
		foreach ( $theme_mod as $row ) {
			if ( ! empty( $row['items'] ) ) {
				foreach ( $row['items'] as $item ) {
					if ( ! empty( $item['key'] ) && 'attribution' === $item['key'] ) {
						$exists = true;
					}
				}
			}
		}

		// Check settings.
		if ( get_theme_mod( 'hide_boldgrid_attribution' ) ) {
			set_theme_mod( 'hide_boldgrid_attribution', false );
		}

		// Merge Defaults.
		if ( ! $exists ) {
			$theme_mod[] = [
				'container' => 'container',
				'items' => [
					[
						'type' => 'boldgrid_display_attribution_links',
						'key' => 'attribution',
						'align' => 'w',
					],
				],
			];

			set_theme_mod( $name, $theme_mod );
		}

	}
}

<?php
/**
 * Customizer Section Functionality
 *
 * @link http://www.boldgrid.com
 *
 * @since 2.0.0
 *
 * @package Boldgrid_Theme_Framework_Customizer
 */
if ( class_exists( 'WP_Customize_Section' ) ) {

	/**
	 * Class: Boldgrid_Framework_Customizer_Section
	 *
	 * Extends the WordPress customizer's section implementation..
	 *
	 * @since      2.0.0
	 * @category   Customizer
	 * @package    Boldgrid_Framework
	 * @subpackage Boldgrid_Framework_Customizer
	 * @author     BoldGrid <support@boldgrid.com>
	 * @link       https://boldgrid.com
	 */
	class Boldgrid_Framework_Customizer_Notification {

		/**
		 * Notification template to render for customizer notifications.
		 *
		 * @since 2.0.0
		 *
		 * @return array The array to be exported to the client as JSON.
		 */
		public function print_template() {
			?>
			<script type="text/html" id="tmpl-bgtfw-notification">
				<li class="notice notice-{{ data.type || 'info' }} {{ data.alt ? 'notice-alt' : '' }} {{ data.dismissible ? 'is-dismissible' : '' }} {{ data.containerClasses || '' }}" data-code="{{ data.code }}" data-type="{{ data.type }}">
					<div class="notification-message">{{{ data.message || data.code }}}</div>
					<# if ( data.features ) { #>
						<button type="button" class="notice-count">
							<span class="num">{{{ data.features.length }}}</span>
							<span class="screen-reader-text">{{{ data.features.length }}} <?php esc_html_e( 'premium features', 'bgtfw' ); ?></span>
						</button>
						<div class="bgtfw-notice-expanded">
							<ul class="bgtfw-feature-list">
							<# _( data.features ).each( function( feature ) { #>
								<li class="bgtfw-feature">{{{ feature }}}</li>
							<# } ); #>
							</ul>
						</div>
						<div class="bgtfw-notice-more">
							<div class="text">
								<p><?php esc_html_e( 'More', 'bgtfw' ); ?></p>
							</div>
							<span class="dashicons dashicons-arrow-down-alt2"></span>
						</div>
					<# } #>
				</li>
			</script>
			<?php
		}
	}
}

<?php
/**
 * Class: Boldgrid_Framework_Customizer_Help
 *
 * This adds help tooltips to the WordPress customizer options in
 * case a user needs additional direction while customizer their site.
 *
 * @since      1.0.0
 * @category   Customizer
 * @package    Boldgrid_Framework
 * @subpackage Boldgrid_Framework_Customizer_Help
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 * @uses       Kirki\Scripts\EnqueueScript
 */

class Boldgrid_Framework_Customizer_Help extends Kirki_Customizer_Scripts_Enqueue {

	public function generate_script( $args = array() ) { }

	/**
	 * Add the help bubble
	 */
	function customize_controls_print_footer_scripts() {

		// Boldgrid - this files was copied from the kirki framework.
		global $boldgrid_theme_framework;
		$configs = $boldgrid_theme_framework->get_configs();
		$fields = apply_filters( 'boldgrid_customizer_tooltips', $configs['tooltips'] );

		$scripts = array();
		$script  = '';

		foreach ( $fields as $field ) {

			if ( ! empty( $field['help'] ) ) {
				// Boldgrid.
				$field['help'] = __( $field['help'], $configs['text_domain'] );

				$bubble_content = $field['help'];
				$content = "<a href='#' class='tooltip hint--left' data-hint='" . strip_tags( esc_html( $bubble_content ) ) . "'><span class='dashicons dashicons-info'></span></a>";
				$scripts[] = '$( "' . $content . '" ).prependTo( "#customize-control-' . $field['settings'] . '" );';
			}
		}

		// No need to echo anything if the script is empty.
		if ( empty( $scripts ) ) {
			return;
		}

		// Make sure we don't add any duplicates.
		$scripts = array_unique( $scripts );
		// Convert array to string.
		$script = implode( '', $scripts );

		echo Kirki_Scripts_Registry::prepare( $script );
	}


	public function customize_controls_print_scripts() {}

	public function customize_controls_enqueue_scripts() {}

	public function wp_footer() {}
}

<?php
/**
 * Class: Boldgrid_Customizer_Help_Overlay_Section.
 *
 * This is the class responsible for the help overlay customizer section displayed.
 *
 * @since      1.4.4
 * @category   Customizer
 * @package    Boldgrid_Framework
 * @subpackage Customizer
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

if ( class_exists( 'WP_Customize_Section' ) ) {

	/**
	 * Class: Boldgrid_Customizer_Help_Overlay_Section.
	 *
	 * This is the class responsible for the help overlay customizer section displayed.
	 */
	class Boldgrid_Framework_Customizer_Help_Overlay_Section extends WP_Customize_Section {

		/**
		 * The type of customize section being rendered.
		 *
		 * @since  1.0.0
		 * @access public
		 * @var    string
		 */
		public $type = 'boldgrid-customizer-help';

		/**
		 * Outputs the Underscore.js template.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		protected function render_template() {
			?><li id="accordion-section-{{ data.id }}" class="accordion-section control-section control-section-{{ data.type }}">
				<h3 class="accordion-section-title">
					{{ data.title }}
				</h3>
			</li><?php
		}
	}
}

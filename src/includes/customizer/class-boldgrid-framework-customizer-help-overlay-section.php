<?php
/**
 * Boldgrid Customizer Help Overlay Section.
 *
 * @since  1.0.0
 * @access public
 */
if ( class_exists( 'WP_Customize_Section' ) ) {
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
		protected function render_template() { ?>
			<li id="accordion-section-{{ data.id }}" class="accordion-section control-section control-section-{{ data.type }}">
				<h3 class="accordion-section-title">
					{{ data.title }}
				</h3>
			</li>
		<?php }
	}
}

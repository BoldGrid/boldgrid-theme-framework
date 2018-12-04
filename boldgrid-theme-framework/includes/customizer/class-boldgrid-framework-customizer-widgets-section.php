<?php
/**
 * Customizer Widget Section
 *
 * Responsible for the widgets panel additional controls added by BGTFW.
 *
 * @package Boldgrid_Framework_Customizer
 * @subpackage Boldgrid_Framework_Customizer_Widgets_Section
 */

if ( class_exists( 'WP_Customize_Section' ) ) {

	/**
	 * BGTFW Widgets Section.
	 *
	 * @since 2.1.0
	 * @access public
	 */
	class Boldgrid_Framework_Customizer_Widgets_Section extends WP_Customize_Section {

		/**
		 * The type of customize section being rendered.
		 *
		 * @since 2.1.0
		 * @access public
		 * @var string
		 */
		public $type = 'bgtfw-widgets-section';

		/**
		 * Section description.
		 *
		 * @since 2.1.0
		 * @access public
		 * @var string
		 */
		public $section_description = '';

		/**
		 * Header Section Title.
		 *
		 * @since 2.1.0
		 * @access public
		 * @var string
		 */
		public $header_title = '';

		/**
		 * Footer Section Title.
		 *
		 * @since 2.1.0
		 * @access public
		 * @var string
		 */
		public $footer_title = '';

		/**
		 * Add custom parameters to pass to the JS via JSON.
		 *
		 * @since 2.1.0
		 * @access public
		 * @return void
		 */
		public function json() {
			$json = parent::json();
			$json['section_description'] = $this->section_description;
			$json['header_title']  = $this->header_title;
			$json['footer_title']  = $this->footer_title;

			return $json;
		}

		/**
		 * Outputs the Underscore.js template.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		protected function render_template() { ?>
			<div id="accordion-section-{{ data.id }}" class="bgtfw-widgets-section">
				<p class="bgtfw-widgets-section-description boldgrid-subdescription">{{{ data.section_description }}}</p>
				<li onclick="event.preventDefault(); wp.customize.control( 'bgtfw_header_layout' ).focus();" id="accordion-section-{{ data.id }}-header" class="accordion-section control-section control-section-{{ data.type }}">
					<h3 class="accordion-section-title">
						{{ data.header_title }}
					</h3>
				</li>
				<li onclick="event.preventDefault(); wp.customize.control( 'bgtfw_footer_layout' ).focus();" id="accordion-section-{{ data.id }}-footer" class="accordion-section control-section control-section-{{ data.type }}">
					<h3 class="accordion-section-title">
						{{ data.footer_title }}
					</h3>
				</li>
				<div class="boldgrid-subdescription">
					<a href="<?php echo admin_url( 'widgets.php' ); ?>" type="button" class="button"><?php _e( 'Edit in Admin', 'bgtfw' ); ?></a>
				<div>
			</div>
		<?php }
	}
}

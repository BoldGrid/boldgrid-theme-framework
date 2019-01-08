<?php
/**
 * Class: Boldgrid_Framework_Customizer_Section_Upsell
 *
 * This class is responsible for creating the upsell button section
 * in the WordPress customizer.
 *
 * @since    2.1.1
 * @category Customizer
 * @package  Boldgrid_Framework
 * @author   BoldGrid <support@boldgrid.com>
 * @link     https://boldgrid.com
 */
if ( class_exists( 'WP_Customize_Section' ) ) {

	/**
	 * Class: Boldgrid_Framework_Customizer_Section_Upsell
	 *
	 * This class is responsible for creating the upsell button section
	 * in the WordPress customizer.
	 *
	 * @since 2.1.1
	 */
	class Boldgrid_Framework_Customizer_Section_Upsell extends WP_Customize_Section {

		/**
		 * The type of customize section being rendered.
		 *
		 * @since 2.1.1
		 * @access public
		 * @var string
		 */
		public $type = 'bgtfw-upsell';

		/**
		 * Text to output for upsell button.
		 *
		 * @since 2.1.1
		 * @access public
		 * @var string
		 */
		public $upsell_text = '';

		/**
		 * Upsell button's URL.
		 *
		 * @since 2.1.1
		 * @access public
		 * @var string
		 */
		public $upsell_url = '';

		/**
		 * Add custom parameters to pass to the JS via JSON.
		 *
		 * @since 2.1.1
		 * @access public
		 * @return Array $json Data to JSON encode for API.
		 */
		public function json() {
			$json = parent::json();
			$json['upsell_text'] = $this->upsell_text;
			$json['upsell_url'] = esc_url( $this->upsell_url );
			return $json;
		}

		/**
		 * Outputs the Underscore.js template.
		 *
		 * @since 2.1.1
		 * @access public
		 * @return void
		 */
		protected function render_template() { ?>

			<li id="accordion-section-{{ data.id }}" class="accordion-section control-section control-section-{{ data.type }} cannot-expand">

				<h3 class="accordion-section-title">
					{{ data.title }}
					<# if ( data.upsell_text && data.upsell_url ) { #>
						<a href="{{ data.upsell_url }}" class="button button-secondary alignright" target="_blank">{{ data.upsell_text }}</a>
					<# } #>
				</h3>
			</li>
		<?php }
	}
}

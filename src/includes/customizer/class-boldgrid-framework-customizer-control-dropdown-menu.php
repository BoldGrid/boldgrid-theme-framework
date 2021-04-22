<?php
/**
 * Class: Boldgrid_Framework_Customizer_Control_Dropdown_Menu
 *
 * This class is responsible for creating the dropdown helper menu.
 *
 * @since    SINCEVERSION
 * @category Customizer
 * @package  Boldgrid_Framework
 * @author   BoldGrid <support@boldgrid.com>
 * @link     https://boldgrid.com
 */
if ( class_exists( 'WP_Customize_Control' ) ) {

	/**
	 * Class: Boldgrid_Framework_Customizer_Control_Dropdown_Menu
	 *
	 * This class is responsible for creating the dropdown helper menu.
	 *
	 * @since SINCEVERSION
	 */
	class Boldgrid_Framework_Customizer_Control_Dropdown_Menu extends Kirki_Control_Base {
		/**
		 * Control Type.
		 *
		 * @var string
		 *
		 * @since SINCEVERSION
		 */
		public $type = 'bgtfw-dropdown-menu';

		/**
		 * Additional Controls.
		 *
		 * @var array
		 *
		 * @since SINCEVERSION
		 */
		public $additional_controls = array();

		/**
		 * FAQ Links.
		 *
		 * @var array
		 *
		 * @since SINCEVERSION
		 */
		public $faq_links = array();

		/**
		 * Similar Questions.
		 *
		 * @var array
		 *
		 * @since SINCEVERSION
		 */
		public $similar_questions = array();

		/**
		 * Refresh the parameters passed to the JavaScript via JSON.
		 *
		 * @since 2.0.0
		 */
		public function to_json() {

			// Call parent to_json() method to get the core defaults like "label", "description", etc.
			parent::to_json();

			$this->json['additional_controls'] = $this->additional_controls;

			$this->json['faq_links'] = $this->faq_links;

			$this->json['similar_questions'] = $this->similar_questions;
		}

		/**
		 * Render Content.
		 *
		 * Displays the control's content.
		 *
		 * @since SINCEVERSION
		 */
		protected function content_template() {
			?>
			<div class="bgtfw-dropdown-menu-wrapper">
				<div class="bgtfw-dropdown-menu-header collapsed">
					<span class="dashicons dashicons-arrow-up-alt2"></span>
					<h3 class="bgtfw-dropdown-menu-label">{{{ data.label }}}</h3>
				</div>
				<div class="bgtfw-dropdown-menu-content">
					<# if ( data.additional_controls ) { #>
						<p class="bgtfw-additional-controls-heading">Additional Controls</p><ul>
						<# data.additional_controls.forEach( ( additional_control ) => { #>
							<li class="bgtfw-additional-control"
								data-focus-id="{{ additional_control.focus_id }}"
								data-focus-type="{{ additional_control.focus_type }}">
								<span>{{{ additional_control.label }}}</span>
							</li>
						<# } ); #>
						</ul>
						<# console.log( { 'additional_controls': data.additional_controls } );
					}

					if ( data.faq_links ) {
						<p class="bgtfw-faq-links-heading">FAQs</p><ul>
						<# data.faq_links.forEach( ( faq_link ) => { #>
							<li class="bgtfw-faq-links">
								<a href="{{ faq_link.url }}">{{{ faq_link.label }}}</a>
							</li>
						<# } ); #>
						</ul>
						console.log( { 'faq_links': data.faq_links } );
					}

					<!-- if ( data.similar_questions ) {
						<p class="bgtfw-similar-questions-heading">FAQs</p><ul>
						<# data.similar_questions.forEach( ( similar_question ) => { #>
							<li class="bgtfw-similar-question">
								<a href="{{ similar_question.url }}">{{{ similar_question.label }}}</a>
							</li>
						<# } ); #>
						</ul>
						console.log( { 'similar_questions': data.similar_questions } );
					}
					#> -->
				</div>
			</div>
			<?php
		}
	}
}

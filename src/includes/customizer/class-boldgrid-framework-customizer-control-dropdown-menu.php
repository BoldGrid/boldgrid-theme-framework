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
		 * Help Text.
		 *
		 * @var string
		 *
		 * @since SINCEVERSION
		 */
		public $help_text = '';

		/**
		 * Label.
		 *
		 * @var string
		 *
		 * @since SINCEVERSION
		 */
		public $label = '';

		/**
		 * Refresh the parameters passed to the JavaScript via JSON.
		 *
		 * @since SINCEVERSION
		 */
		public function to_json() {

			// Call parent to_json() method to get the core defaults like "label", "description", etc.
			parent::to_json();

			$$this->json['utm_params'] = '?utm_campaign=Crio%20FTP&utm_medium=faq_link&utm_source=' . $this->section;

			$this->json['help_text'] = $this->help_text;

			$this->json['additional_controls'] = $this->additional_controls;

			$this->json['faq_links'] = $this->faq_links;
		}

		/**
		 * Help Text Template.
		 *
		 * Displays the Help Text content.
		 *
		 * @access private
		 *
		 * @since SINCEVERSION
		 */
		private function help_text_template() {
			?>
				<p class="bgtfw-dropdown-menu-help-text">{{{ data.help_text }}}</p>
			<?php
		}

		/**
		 * Additional Controls Template
		 *
		 * Displays the Additional Controls content.
		 *
		 * @access private
		 *
		 * @since SINCEVERSION
		 */
		private function additional_controls_template() {
			?>
				<p class="bgtfw-additional-controls-heading">Additional Controls</p>
				<ul>
					<# data.additional_controls.forEach( ( additional_control ) => { #>
					<li class="bgtfw-additional-control"
						data-focus-id="{{ additional_control.focus_id }}"
						data-focus-type="{{ additional_control.focus_type }}">
						<span>{{{ additional_control.label }}}</span>
					</li>
					<# } ); #>
				</ul>
				<# console.log( { 'additional_controls': data.additional_controls } ); #>
			<?php
		}

		/**
		 * FAQ Links Template
		 *
		 * Displays the FAQ Links Content.
		 *
		 * @access private
		 *
		 * @since SINCEVERSION
		 */
		private function faq_links_template() {
			?>
				<p class="bgtfw-faq-links-heading">FAQs</p>
				<ul>
					<# data.faq_links.forEach( ( faq_link ) => { #>
					<li class="bgtfw-faq-links">
						<a target="_blank" href="{{ faq_link.url }}{{data.utm_params}}">{{{ faq_link.label }}}</a>
					</li>
					<# } ); #>
				</ul>
					<# console.log( { 'faq_links': data.faq_links } ); #>
			<?php
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
					<div class="label">
						<p class="bgtfw-dropdown-menu-label">
							<span class="dashicons dashicons-menu-alt"></span>
							<span class="text"><?php _e( 'Related Options', 'bgtfw' ); ?></span>
						</p>
					</div>
					<div class="dropdown-button">
						<span class="dashicons dashicons-arrow-up-alt2"></span>
					</div>
				</div>
				<div class="bgtfw-dropdown-menu-content">
					<# if ( data.help_text ) { #>
						<?php $this->help_text_template(); ?>
					<# }

					if ( data.additional_controls ) { #>
						<?php $this->additional_controls_template(); ?>
					<# }

					if ( data.faq_links ) { #>
						<?php $this->faq_links_template(); ?>
					<# } #>
				</div>
			</div>
			<?php
		}
	}
}

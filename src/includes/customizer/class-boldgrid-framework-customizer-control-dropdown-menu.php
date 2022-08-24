<?php
/**
 * Class: Boldgrid_Framework_Customizer_Control_Dropdown_Menu
 *
 * This class is responsible for creating the dropdown helper menu.
 *
 * @since    2.10.0
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
	 * @since 2.10.0
	 */
	class Boldgrid_Framework_Customizer_Control_Dropdown_Menu extends Kirki_Control_Base {
		/**
		 * Control Type.
		 *
		 * @var string
		 *
		 * @since 2.10.0
		 */
		public $type = 'bgtfw-dropdown-menu';

		/**
		 * Additional Controls.
		 *
		 * @var array
		 *
		 * @since 2.10.0
		 */
		public $additional_controls = array();

		/**
		 * FAQ Links.
		 *
		 * @var array
		 *
		 * @since 2.10.0
		 */
		public $faq_links = array();

		/**
		 * Active Label.
		 *
		 * @var string
		 *
		 * @since 2.10.0
		 */
		public $active_label = '';

		/**
		 * Help Label.
		 *
		 * @var string
		 *
		 * @since 2.10.0
		 */
		public $help_label = '';

		/**
		 * Help Text.
		 *
		 * @var string
		 *
		 * @since 2.10.0
		 */
		public $help_text = '';

		/**
		 * Label.
		 *
		 * @var string
		 *
		 * @since 2.10.0
		 */
		public $label = '';

		/**
		 * Refresh the parameters passed to the JavaScript via JSON.
		 *
		 * @since 2.10.0
		 */
		public function to_json() {

			// Call parent to_json() method to get the core defaults like "label", "description", etc.
			parent::to_json();

			$this->json['utm_params'] = '?utm_campaign=Crio%20Customizer&utm_medium=faq_link&utm_source=' . $this->section;

			$this->json['help_text'] = $this->help_text;

			$this->json['help_label'] = $this->help_label;

			$this->json['active_label'] = $this->active_label;

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
		 * @since 2.10.0
		 */
		private function help_text_template() {
			?>
				<p class="bgtfw-dropdown-menu-help-label">{{{ data.help_label }}}</p>
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
		 * @since 2.10.0
		 */
		private function additional_controls_template() {
			?>
				<p class="bgtfw-additional-controls-heading">{{{ data.label }}} Elements</p>
				<ul>
					<li class="bgtfw-additional-control active">
						<span>{{{ data.active_label }}}</span>
					</li>
					<# data.additional_controls.forEach( ( additional_control ) => { #>
					<li class="bgtfw-additional-control"
						title="Go To {{{ additional_control.label }}} Section"
						data-focus-id="{{ additional_control.focus_id }}"
						data-focus-type="{{ additional_control.focus_type }}">
						<span>{{{ additional_control.label }}}</span>
					</li>
					<# } ); #>
				</ul>
			<?php
		}

		/**
		 * FAQ Links Template
		 *
		 * Displays the FAQ Links Content.
		 *
		 * @access private
		 *
		 * @since 2.10.0
		 */
		private function faq_links_template() {
			?>
				<p class="bgtfw-faq-links-heading">Documentation</p>
				<ul>
					<# data.faq_links.forEach( ( faq_link ) => { #>
					<li class="bgtfw-faq-links">
						<a target="_blank"
							title="Go to &#8220;{{ faq_link.label }}&#8221; Support Article"
							href="{{ faq_link.url }}{{data.utm_params}}">{{{ faq_link.label }}}<span class="dashicons dashicons-external"></span></a>
					</li>
					<# } ); #>
				</ul>
			<?php
		}

		/**
		 * Render Content.
		 *
		 * Displays the control's content.
		 *
		 * @since 2.10.0
		 */
		protected function content_template() {
			?>
			<div class="bgtfw-dropdown-menu-wrapper">
				<div class="bgtfw-dropdown-menu-header collapsed">
					<div class="label">
						<p class="bgtfw-dropdown-menu-label">
							<span class="dashicons dashicons-menu-alt"></span>
							<span class="text"><?php esc_html_e( 'Related Options', 'bgtfw' ); ?></span>
						</p>
					</div>
					<div class="dropdown-button">
						<span class="dashicons dashicons-arrow-up-alt2"></span>
					</div>
				</div>
				<div class="bgtfw-dropdown-menu-content">
					<# if ( data.help_text ) { #>
							<?php $this->help_text_template(); ?>
					<# } #>

					<# if ( data.additional_controls ) { #>
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

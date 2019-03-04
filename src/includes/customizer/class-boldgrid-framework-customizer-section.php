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
	class Boldgrid_Framework_Customizer_Section extends WP_Customize_Section {

		/**
		 * Section in which to show the section, making it a sub-section.
		 *
		 * @since 2.0.0
		 *
		 * @var string
		 *
		 * @access public
		 */
		public $section;

		/**
		 * Section type
		 *
		 * @access public
		 * @var    string The type of section to create.
		 */
		public $type = 'bgtfw_section';

		/**
		 * Section breadcrumb
		 *
		 * @since 2.0.0
		 *
		 * @access public
		 * @var    string The full breadcrumb.
		 */
		public $breadcrumb = '';

		/**
		 * Section notifications.
		 *
		 * @since 2.1.1
		 *
		 * @access public
		 * @var    Array BGTFW Notice defaults.
		 */
		public $notice_defaults = [
			'dismissible' => false,
			'message' => '',
			'type' => 'bgtfw-features',
			'templateId' => 'bgtfw-notification',
			'features' => [],
		];

		/**
		 * Section Icon
		 *
		 * @since 2.1.1
		 *
		 * @access public
		 * @var    String $icon Section icon.
		 */
		public $icon = null;

		/**
		 * Section notifications.
		 *
		 * @since 2.1.1
		 *
		 * @access public
		 * @var    Array BGTFW Notice defaults.
		 */
		public $notice = [];

		/**
		 * Gather the parameters passed to client JavaScript via JSON.
		 *
		 * @since 2.0.0
		 *
		 * @return array The array to be exported to the client as JSON.
		 */
		public function json() {
			$array = wp_array_slice_assoc( (array) $this, array( 'id', 'description', 'priority', 'panel', 'type', 'description_hidden', 'section' ) );
			$array['title'] = html_entity_decode( $this->title, ENT_QUOTES, get_bloginfo( 'charset' ) );
			$array['content'] = $this->get_content();
			$array['active'] = $this->active();
			$array['instanceNumber'] = $this->instance_number;

			if ( ! empty( $this->panel ) ) {
				$array['customizeAction'] = rtrim( $this->manager->get_panel( $this->panel )->breadcrumb ) . ' &#9656; ' . $this->manager->get_panel( $this->panel )->get_panel_link( $this->manager->get_panel( $this->panel )->id, $this->manager->get_panel( $this->panel )->title, $this->id );
			} else {
				$array['customizeAction'] = '<span class="dashicons dashicons-admin-home"></span>';
			}

			$array['icon'] = $this->get_icon();

			if ( isset( $this->notice ) && ! empty( $this->notice ) ) {
				$this->notice = wp_parse_args( $this->notice, $this->notice_defaults );
				$array['notice'] = $this->notice;
			}

			return $array;
		}

		/**
		 * Get the breadcrumb trails for the current panel.
		 *
		 * @since 2.0.0
		 *
		 * @return string $breadcrumb The breadcrumb trail displayed.
		 */
		public function get_icon() {
			if ( ! empty( $this->icon ) ) {
				if ( strpos( $this->icon, 'dashicons-' ) !== false ) {
					$this->icon = "dashicons-before {$this->icon}";
				} else if ( strpos( $this->icon, 'fa-' ) !== false ) {
					$this->icon = "fa {$this->icon}";
				} else {
					$this->icon = $this->icon;
				}
			}

			return $this->icon;
		}

		/**
		 * An Underscore (JS) template for rendering this section.
		 *
		 * Class variables for this section class are available in the `data` JS object;
		 * export custom variables by overriding WP_Customize_Section::json().
		 *
		 * @since 4.3.0
		 *
		 * @see WP_Customize_Section::print_template()
		 */
		protected function render_template() {
			?>
			<li id="accordion-section-{{ data.id }}" class="accordion-section control-section control-section-{{ data.type }}">
				<h3 class="accordion-section-title<# if ( ! _.isEmpty( data.icon ) ) { #> {{ data.icon }}<# } #>" tabindex="0">
					{{ data.title }}
					<span class="screen-reader-text"><?php esc_html_e( 'Press return or enter to open this section', 'bgtfw' ); ?></span>
				</h3>
				<ul class="accordion-section-content">
					<li class="customize-section-description-container section-meta <# if ( data.description_hidden ) { #>customize-info<# } #>">
						<div class="customize-section-title">
							<button class="customize-section-back" tabindex="-1">
								<span class="screen-reader-text"><?php esc_html_e( 'Back', 'bgtfw' ); ?></span>
							</button>
							<h3>
								<span class="customize-action">
									{{{ data.customizeAction }}}
								</span>
								<div class="bgtfw-section-title<# if ( ! _.isEmpty( data.icon ) ) { #> {{ data.icon }}<# } #>">{{ data.title }}</div>
							</h3>
							<# if ( data.description && data.description_hidden ) { #>
								<button type="button" class="customize-help-toggle dashicons dashicons-editor-help" aria-expanded="false"><span class="screen-reader-text"><?php esc_html_e( 'Help', 'bgtfw' ); ?></span></button>
								<div class="description customize-section-description">
									{{{ data.description }}}
								</div>
							<# } #>

							<div class="customize-control-notifications-container"></div>
						</div>

						<# if ( data.description && ! data.description_hidden ) { #>
							<div class="description customize-section-description">
								{{{ data.description }}}
							</div>
						<# } #>
					</li>
				</ul>
			</li>
			<?php
		}
	}
}

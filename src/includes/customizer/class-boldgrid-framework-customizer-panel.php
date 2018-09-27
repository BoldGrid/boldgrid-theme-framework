<?php
/**
 * Customizer Panel Functionality
 *
 * @link http://www.boldgrid.com
 *
 * @since 2.0.0
 *
 * @package Boldgrid_Theme_Framework_Customizer
 */
if ( class_exists( 'WP_Customize_Panel' ) ) {

	/**
	 * Class: Boldgrid_Framework_Customizer_Panel
	 *
	 * Extends the WordPress customizer's panel implementation..
	 *
	 * @since      2.0.0
	 * @category   Customizer
	 * @package    Boldgrid_Framework
	 * @subpackage Boldgrid_Framework_Customizer
	 * @author     BoldGrid <support@boldgrid.com>
	 * @link       https://boldgrid.com
	 */
	class Boldgrid_Framework_Customizer_Panel extends WP_Customize_Panel {

		/**
		 * Panel in which to show the panel, making it a sub-panel.
		 *
		 * @since 2.0.0
		 *
		 * @var string
		 *
		 * @access public
		 */
		public $panel;

		/**
		 * Panel type
		 *
		 * @access public
		 * @var    string The type of panel to create.
		 */
		public $type = 'bgtfw_panel';

		public $breadcrumb = '';


		/**
		 * Gather the parameters passed to client JavaScript via JSON.
		 *
		 * @since  2.0.0
		 *
		 * @return array The array to be exported to the client as JSON.
		 */
		public function json() {
			$array = wp_array_slice_assoc( (array) $this, array( 'id', 'description', 'priority', 'type', 'panel' ) );

			$array['title'] = html_entity_decode( $this->title, ENT_QUOTES, get_bloginfo( 'charset' ) );
			$array['content'] = $this->get_content();
			$array['active'] = $this->active();
			$array['instanceNumber'] = $this->instance_number;
			$array['breadcrumb'] = $this->get_breadcrumb();

			return $array;
		}

		/**
		 * Get the breadcrumb trails for the current panel.
		 *
		 * @since 2.0.0
		 *
		 * @return string $breadcrumb The breadcrumb trail displayed.
		 */
		public function get_breadcrumb() {
			if ( ! empty( $this->panel ) && ! empty( $this->manager->get_panel( $this->panel )->breadcrumb ) ) {
				$this->breadcrumb = rtrim( $this->manager->get_panel( $this->panel )->breadcrumb ) . ' &#9656; ' . $this->get_panel_link( $this->manager->get_panel( $this->panel )->id, $this->manager->get_panel( $this->panel )->title );
			} else {
				$this->breadcrumb = '<span class="dashicons dashicons-admin-home"></span>';
			}

			return $this->breadcrumb;
		}

		/**
		 * Generates the html link for the panel's breadcrumb.
		 *
		 * @since 2.0.0
		 *
		 * @param string $id         The panel's ID.
		 * @param string $title      The panel's title.
		 * @param bool   $section_id Section should supply it's ID if calling panel.
		 *
		 * @return string The panel's html link.
		 */
		public function get_panel_link( $id, $title, $section_id = '' ) {
			if ( ! empty( $section_id ) ) {
				$section_id = ' wp.customize.section( \'' . esc_js( $section_id ) . '\' ).collapse();';
			}

			return '<a href="#" title="' . esc_attr( $title ) . '" onclick="event.preventDefault();' . $section_id . ' wp.customize.panel( \'' . esc_js( $id ) . '\' ).expand();">' . esc_html( $title ) . '</a>';
		}

		/**
		 * An Underscore (JS) template for this panel's content (but not its container).
		 *
		 * Class variables for this panel class are available in the `data` JS object;
		 * export custom variables by overriding WP_Customize_Panel::json().
		 *
		 * @see WP_Customize_Panel::print_template()
		 *
		 * @since 4.3.0
		 */
		protected function content_template() {
			?>
			<li class="panel-meta customize-info accordion-section <# if ( ! data.description ) { #> cannot-expand<# } #>">
				<button class="customize-panel-back" tabindex="-1"><span class="screen-reader-text"><?php _e( 'Back', 'bgtfw' ); ?></span></button>
				<div class="accordion-section-title">
					<span class="preview-notice">
						{{{ data.breadcrumb }}}
						<strong class="panel-title">{{ data.title }}</strong>
					</span>
					<# if ( data.description ) { #>
						<button type="button" class="customize-help-toggle dashicons dashicons-editor-help" aria-expanded="false"><span class="screen-reader-text"><?php _e( 'Help', 'bgtfw' ); ?></span></button>
					<# } #>
				</div>
				<# if ( data.description ) { #>
					<div class="description customize-panel-description">
						{{{ data.description }}}
					</div>
				<# } #>

				<div class="customize-control-notifications-container"></div>
			</li>
			<?php
		}
	}
}

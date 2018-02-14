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

			if ( $this->panel ) {
				$array['customizeAction'] = sprintf( 'Customizing &#9656; %s', esc_html( $this->manager->get_panel( $this->panel )->title ) );
			} else {
				$array['customizeAction'] = 'Customizing';
			}

			return $array;
		}
	}
}

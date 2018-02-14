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

			return $array;
		}
	}
}

<?php
/**
 * Class: BoldGrid_Framework_Container
 *
 * The class responsible for getting container classes for elements.
 *
 * @since   2.0.0
 * @package Boldgrid_Framework
 * @author  BoldGrid <support@boldgrid.com>
 * @link    https://boldgrid.com
 */

/**
 * Container Classes.
 *
 * @since 2.0.0
 */
class Boldgrid_Framework_Container {

	/**
	 * Location container should be added to.
	 *
	 * @since 2.0.0
	 * @var string $location Location to add container to.
	 */
	public $location;

	/**
	 * The BoldGrid Theme Framework configurations.
	 *
	 * @since 2.0.0
	 * @var string $classes Classes to be added for element to become container.
	 */
	public $classes;

	/**
	 * Display the classes for the header element.
	 *
	 * @since 2.0.0
	 *
	 * @param string|array $location Location container classes should be added to.
	 */
	public function __construct( $location ) {
		$this->location = $location;
		$this->classes = $this->get_container_classes();
	}

	/**
	 * Get container classes from theme mods.
	 *
	 * @since 2.0.0
	 *
	 * @param  string $location  Location of the container.
	 *
	 * @return string $container Container classes.
	 */
	public function get_container_classes( $location = '' ) {
		$container = '';
		$location = empty( $location ) ? $this->location : $location;

		if ( 'header' === $location ) {
			$container = $this->get_header_container_classes();
		}

		return $container;
	}

	/**
	 * Get the header container classes from theme mods.
	 *
	 * @since 2.0.0
	 *
	 * @return string Container classes.
	 */
	public function get_header_container_classes() {
		return get_theme_mod( 'bgtfw_header_layout_position' ) === 'header-top' ? get_theme_mod( 'header_container' ) : '';
	}
}

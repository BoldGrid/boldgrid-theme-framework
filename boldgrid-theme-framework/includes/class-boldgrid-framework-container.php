<?php

Class Boldgrid_Framework_Container {

	public $location;
	public $classes;

	/**
	 * Display the classes for the header element.
	 *
	 * @since 2.8.0
	 *
	 * @param string|array $class One or more classes to add to the class list.
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

		if ( 'footer' === $location ) {
			$container = $this->get_footer_container_classes();
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

	/**
	 * Get the footer container classes from theme mods.
	 *
	 * @since 2.0.0
	 *
	 * @return string Container classes.
	 */
	public function get_footer_container_classes() {
		return get_theme_mod( 'boldgrid_enable_footer' ) === true ? get_theme_mod( 'footer_container' ) : '';
	}
}

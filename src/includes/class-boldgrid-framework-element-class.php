<?php

Class Boldgrid_Framework_Element_Class {

	public $element;
	public $class;
	public $classes;
	public $html;

	/**
	 * Display the classes for the header element.
	 *
	 * @since 2.8.0
	 *
	 * @param string|array $class One or more classes to add to the class list.
	 */
	public function __construct( $element = '', $class = '' ) {
		$this->element = $element;
		$this->class = $class;
		$this->classes = $this->set_classes( $this->element, $this->class );
		$this->html = $this->set_html();
	}

	/**
	 * Display the classes for the header element.
	 *
	 * @since 2.8.0
	 */
	public function set_html() {
		return 'class="' . join( ' ', $this->classes ) . '"';
	}

	/**
	 * Retrieves classes to add to element.
	 *
	 * This method also adds dynamic filters so BGTFW can manage element classes.
	 *
	 * @since 2.8.0
	 */
	public function set_classes() {
		$classes = [];

		if ( ! empty( $this->class ) ) {

			if ( ! is_array( $this->class ) ) {
				$this->class = preg_split( '#\s+#', $this->class );
			}

			$classes = array_merge( $classes, $this->class );
		} else {

			// Ensure that we always coerce class to being an array.
			$this->class = [];
		}

		$classes = array_map( 'esc_attr', $classes );
		$classes = apply_filters( "bgtfw_{$this->element}_classes", $classes, $this->class );

		return array_unique( $classes );
	}
}

<?php
/**
 * Class: Boldgrid_Framework_Element_Class
 *
 * This class contains methods used to add classes to elements.
 *
 * @since      1.0.0
 * @package    Boldgrid_Framework
 * @subpackage Boldgrid_Framework_Api
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

/**
 * Class: Boldgrid_Framework_Element_Class
 *
 * This class contains methods used to add classes to elements.
 *
 * @since 2.0.0
 */
class Boldgrid_Framework_Element_Class {

	/**
	 * Element to add classes to.
	 *
	 * @since 2.0.0
	 * @var string $element Element to add classes to.
	 */
	public $element;

	/**
	 * Class/classes to be added to location when called.
	 *
	 * @since 2.0.0
	 * @var string|array $class Class/classes to be added to element when called.
	 */
	public $class;

	/**
	 * Sets classes for element. Also contains elements that are
	 * added through the generated filters.
	 *
	 * @since 2.0.0
	 * @var array classes Classes to add to the element after filter applied.
	 */
	public $classes;

	/**
	 * Markup to be added to HTML element.
	 *
	 * @since 2.0.0
	 * @var string $html Markup to be added to HTML element.
	 */
	public $html;

	/**
	 * Display the classes for the header element.
	 *
	 * @since 2.0.0
	 *
	 * @param string       $element Element to add classes to.
	 * @param string|array $class   One or more classes to add to the class list.
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
	 * @since  2.0.0
	 *
	 * @return string Markup to be added to element.
	 */
	public function set_html() {
		return 'class="' . esc_attr( join( ' ', $this->classes ) ) . '"';
	}

	/**
	 * Retrieves classes to add to element.
	 *
	 * This method also adds dynamic filters so BGTFW can manage element classes.
	 *
	 * @since  2.0.0
	 *
	 * @return array $classes Array of classes to add to HTML element.
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

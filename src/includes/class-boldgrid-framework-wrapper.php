<?php
/**
 * BoldGrid Theme Wrapper.
 *
 * This is used to create a wrapper for theme templates,
 * so we have a single base.php template to work with.
 *
 * @package Boldgrid_Framework
 * @since 1.1
 */

/**
 * Boldgrid_Framework_Wrapper Class.
 *
 * Essentially adds another level to the WordPress template
 * hierarchy.
 *
 * @link http://scribu.net/wordpress/theme-wrappers.html
 * @since 1.1
 */
class Boldgrid_Framework_Wrapper {

	/**
	 * Stores the full path to the main template file
	 *
	 * @since 1.1
	 * @access public
	 * @var string $main_template Full path to main template file.
	 */
	public static $main_template;

	/**
	 * Basename of the template file.
	 *
	 * @since 1.1
	 * @access public
	 * @var $slug Basename of the template file.
	 */
	public $slug;

	/**
	 * Array of templates.
	 *
	 * @since 1.1
	 * @access public
	 * @var array $templates Array of templates.
	 */
	public $templates;

	/**
	 * Stores the basename of the template file. 'page' for 'page.php' etc.
	 *
	 * @since 1.1
	 * @access public
	 * @var string $base Basename of template file.
	 */
	public static $base;

	/**
	 * Constructor.
	 *
	 * Creates $templates array with base.php as the fallback template.  Check
	 * that we aren't using index.php as the base, so a more specific
	 * template can be added in front of $template array.
	 *
	 * @param string $template Template file to load in include_template filter.
	 * @since 1.1
	 */
	public function __construct( $template = 'base.php' ) {
		$this->slug      = sanitize_title( basename( $template, '.php' ) );
		$this->templates = array( $template );

		if ( self::$base ) {
			$str = substr( $template, 0, -4 );
			array_unshift( $this->templates, sprintf( $str . '-%s.php', self::$base ) );
		}
	}

	/**
	 * Set the string value for object.
	 *
	 * This applies filters to the final $templates array, before returning
	 * the full path to the most specific existing base template via
	 * locate_template()
	 *
	 * @since 1.1
	 */
	public function __toString() {
		$this->templates = apply_filters( 'boldgrid/wrap_' . $this->slug, $this->templates );
		$this->templates = apply_filters( 'bgtfw_wrapper_templates', $this->templates, self::$base, self::$main_template );

		return locate_template( $this->templates );
	}

	/**
	 * Wrap method.
	 *
	 * Saves the $main_template path and $base as static variables.  Uses the
	 * template_include filter in WordPress.
	 *
	 * @since 1.1
	 * @param string $main path of main template file to use.
	 * @return Boldgrid_Framework_Wrapper An instance of Boldgrid_Framework_Wrapper
	 */
	public static function wrap( $main ) {
		// Check for other filters returning null.
		if ( ! is_string( $main ) ) {
			return $main;
		}

		self::$main_template = $main;
		self::$base          = basename( self::$main_template, '.php' );

		// Check if index.php is the base.
		if ( 'index' === self::$base ) {
			self::$base = false;
		}

		return new Boldgrid_Framework_Wrapper();
	}

	/**
	 * Template path helper.
	 *
	 * Just exposing a helper function to load $main_template.
	 *
	 * @since 1.1
	 * @return string $main_template The main template to load.
	 */
	public static function boldgrid_template_path() {
		return self::$main_template;
	}
}

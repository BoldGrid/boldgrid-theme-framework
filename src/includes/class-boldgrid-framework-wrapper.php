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

		$is_si_pdf      = $this->is_si_pdf();
		$is_si_invoice  = $this->is_si_invoice();
		$is_si_estimate = $this->is_si_estimate();

		if ( $this->is_si_pdf() ) {
			$this->templates = array( 'si-pdf-base.php' );
		} elseif ( $this->is_si_invoice() ) {
			$this->templates = array( 'si-invoice-base.php' );
		} elseif ( $this->is_si_estimate() ) {
			$this->templates = array( 'si-estimate-base.php' );
		}
	}

	/**
	 * Is this page an SI invoice?
	 *
	 * @since 2.17.2
	 *
	 * @return boolean
	 */
	public function is_si_invoice() {
		if ( class_exists( 'SI_Invoice' ) ) {
			return SI_Invoice::is_invoice_query();
		} else {
			return false;
		}
	}

	/**
	 * Is this page an SI estimate?
	 *
	 * @since 2.17.2
	 *
	 * @return boolean
	 */
	public function is_si_estimate() {
		global $post;

		if ( ! empty( $post ) && class_exists( 'SI_Invoice' ) ) {
			return 'sa_estimate' === $post->post_type;
		} else {
			return false;
		}
	}

	/**
	 * Is this page an SI PDF?
	 *
	 * @since 2.17.2
	 *
	 * @return boolean
	 */
	public function is_si_pdf() {
		global $post;

		// If Sprout is not active, we won't be viewing a sprout pdf.
		if ( ! class_exists( 'SI_Invoice' ) ) {
			return false;
		}

		// When viewing the sprout invoice, these two variables are always set.
		if ( ! self::$base || ! isset( self::$main_template ) ) {
			return false;
		}

		// If template doesn't contain 'sprout-invoices' in it's path, it's not a sprout pdf.
		if ( false === strpos( self::$main_template, 'sprout-invoices' ) ) {
			return false;
		}

		// At this point, we know its a sprout page. If it's a pdf redirected page, we know it's a pdf.
		if ( isset( $_SERVER['REDIRECT_QUERY_STRING'] ) && 'pdf=1' === $_SERVER['REDIRECT_QUERY_STRING'] ) {
			return true;
		}

		// If the base name contians '-pdf', then it's a sprout pdf.
		if ( false !== strpos( self::$base, '-pdf' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Set the string value for object.
	 *
	 * This applies a filter to the final $templates array, before returning
	 * the full path to the most specific existing base template via
	 * locate_template()
	 *
	 * @since 1.1
	 */
	public function __toString() {
		$this->templates = apply_filters( 'boldgrid/wrap_' . $this->slug, $this->templates );
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

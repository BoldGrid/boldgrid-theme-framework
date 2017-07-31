<?php
/**
 * Class: BoldGrid_Framework_Schema
 *
 * This contains code that BoldGrid themes use to present
 * schema.org microdata in the markup.
 *
 * @since      1.0.0
 * @package    Boldgrid_Framework
 * @subpackage BoldGrid_Framework_Schema
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

// If this file is called directly, abort.
defined( 'WPINC' ) ? : die;

/**
 * Class: BoldGrid_Framework_Schema
 *
 * This contains code that BoldGrid themes use to present
 * schema.org microdata in the markup.
 *
 * @since      1.0.0
 */
class BoldGrid_Framework_Schema {

	/**
	 * The BoldGrid Theme Framework configurations.
	 *
	 * @since     1.0.0
	 * @access    protected
	 * @var       string     $configs       The BoldGrid Theme Framework configurations.
	 */
	protected $configs;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param     string $configs       The BoldGrid Theme Framework configurations.
	 * @since     1.0.0
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
	}

	/**
	 * BoldGrid_Schema::body( $seo );
	 *
	 * This will print the schema.org markup in the body tag if passed true.
	 * The default value passed back is 'WebPage' if it's not a specific type of page.
	 *
	 * @var      $seo                   accepts true or false
	 * @var      $boldgrid_schema       Displays schema.org markup with no item type
	 * @var      $item_type             Conditionally adds the item type to schema.org based on page.
	 *
	 * @link http://schema.org/Article
	 * @link http://schema.org/BlogPage
	 * @link http://schema.org/ProfilePage
	 * @link http://schema.org/SearchResultsPage
	 * @link http://schema.org/WebPage
	 *
	 * @since 1.0.0
	 */
	public static function body( $seo = null ) {

		if ( 'true' === $seo ) {

			$boldgrid_schema = 'http://schema.org/';

			switch ( true ) {

				case is_single( ) :  $item_type = 'Article';

					break;

				case is_home( )   :  $item_type = 'BlogPage';

					break;

				case is_author( ) :  $item_type = 'ProfilePage';

					break;

				case is_search( ) :  $item_type = 'SearchResultsPage';

					break;

				default : $item_type = 'WebPage';

					break;

			}

			print 'itemscope="itemscope" itemtype="' . esc_url( $boldgrid_schema . $item_type ) . '"';

		} elseif ( ! isset( $seo ) ) {
			return;
		} else {
			return;
		}

	}


	/**
	 * BoldGrid_Schema::header( $seo );
	 *
	 * This will print the schema.org markup for a WordPress Header if passed true.
	 * This can be turned off in a theme by passing false or leaving empty.
	 *
	 * @var      $seo
	 * @link     http://schema.org/WPHeader
	 * @since    1.0.0
	 */
	public static function header( $seo = null ) {

		if ( 'true' === $seo ) {

			print ( 'itemscope="itemscope" itemtype="http://schema.org/WPHeader"' );

		} elseif ( ! isset( $seo ) ) { return;
		} else { return; }

	}

	/**
	 * BoldGrid_Schema::footer( $seo );
	 *
	 * This will print the schema.org markup for a WordPress Footer if passed true.
	 * This can be turned off in a theme by passing false or leaving empty.
	 *
	 * @var      $seo
	 * @link     http://schema.org/WPFooter
	 * @since    1.0.0
	 */
	public static function footer( $seo = null ) {

		if ( 'true' === $seo ) {

			print ( 'itemscope="itemscope" itemtype="http://schema.org/WPFooter"' );

		} elseif ( ! isset( $seo ) ) { return;
		} else { return; }

	}

	/**
	 * BoldGrid_Schema::blog_post( $seo );
	 *
	 * This will print the schema.org markup for a WordPress Footer if passed true.
	 * This can be turned off in a theme by passing false or leaving empty.
	 *
	 * @var      $seo
	 * @link     http://schema.org/BlogPosting
	 * @since    1.0.0
	 */
	public static function blog_post( $seo = null ) {

		if ( 'true' === $seo ) {

			print ( 'itemscope="itemscope" itemtype="http://schema.org/BlogPosting"' );

		} elseif ( ! isset( $seo ) ) { return;
		} else { return; }

	}

	/**
	 * BoldGrid_Schema::itemprop( $name );
	 *
	 * This will print the schema.org markup for a WordPress Footer if passed true.
	 * This can be turned off in a theme by passing false or leaving empty.
	 *
	 * @param    string $name Name to use for itemprop.
	 * @link     http://schema.org/BlogPosting
	 * @since    1.0.0
	 */
	public static function itemprop( $name = null ) {

		if ( null !== $name ) {

			print ( 'itemprop="' . esc_attr( $name ) . '"' );

		} elseif ( ! isset( $name ) ) { return;
		} else { return; }

	}

	/**
	 * Removes hentry class from the array of post classes.
	 *
	 * We are targetting page templates for removal since they
	 * do not have the required formatting for hentry.
	 *
	 * @param  array $classes Classes for the post element.
	 * @return array
	 */
	public static function remove_hentry( $classes ) {
		if ( 'page' === get_post_type() ) {
			$classes = array_diff( $classes, array( 'hentry' ) );
		}

		return $classes;
	}
}

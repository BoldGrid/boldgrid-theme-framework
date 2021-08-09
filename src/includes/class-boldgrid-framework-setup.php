<?php
/**
 * Class: BoldGrid_Framework_Setup
 *
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 * @since      1.0.0
 * @package    Boldgrid_Framework
 * @subpackage BoldGrid_Framework_Setup
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

// If this file is called directly, abort.
defined( 'WPINC' ) ? : die;

/**
 * Class: BoldGrid_Framework_Setup
 *
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 * @since      1.0.0
 */
class BoldGrid_Framework_Setup {

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
	 * Provides an endpoint for the setup process of our BoldGrid based theme.
	 *
	 * @since 1.0.0
	 */
	public function boldgrid_setup() {
		$this->set_theme_width( );
		$this->load_text_domain( );
		$this->auto_feed_links( );
		$this->title_tag_support( );
		$this->html5_support( );
		$this->post_thumbs( );
		$this->post_formats( );
		$this->customizer_bg( );
		$this->jetpack_setup( );
		$this->woo_commerce_setup( );
		$this->header_image_setup( );
		$this->custom_logo_setup();
		$this->selective_refresh_widgets();
	}

	/**
	 * Selective Refresh Widgets
	 *
	 * Adds Selective Refreshing to the Widgets.
	 *
	 * @since 2.9.0
	 */
	public function selective_refresh_widgets() {
		add_theme_support( 'customize-selective-refresh-widgets' );
	}

	/**
	 * Custom functions that act independently of the theme templates.
	 *
	 * @TODO: REMOVE after integrating functionality
	 * These includes came from functions.php
	 * @since 1.0.0
	 */
	public function add_additional_setup() {
		require_once $this->configs['framework']['includes_dir'] . 'partials/template-tags.php';
	}

	/**
	 * Set the content width based on the theme's design and stylesheet.
	 *
	 * @since 1.0.0
	 */
	private function set_theme_width() {
		if ( ! isset( $content_width ) ) {
			$content_width = 640; /* pixels */
		}
	}

	/**
	 * Make theme available for translation.
	 *
	 * Translations can be filed in the /languages/ directory.
	 * Generally you would want to use find and replace to change BOLDGRID_THEME_NAME,
	 * but this is a PHP constant, which stores the value in your theme's functions.php
	 * file.
	 *
	 * @todo   will this actually work as intended?  more thorough testing should be
	 *		   done between active/staging types, and 3rd party plugin integration.
	 *
	 * @link   https://codex.wordpress.org/Function_Reference/load_textdomain
	 * @since  1.0.0
	 */
	private function load_text_domain() {
		load_theme_textdomain( $this->configs['textdomain'], get_template_directory( ) . '/languages' );
	}

	/**
	 * Enable support for automatic rss feed links in the <head>
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 *
	 * @since 1.0.0
	 */
	private function auto_feed_links() {
		add_theme_support( 'automatic-feed-links' );
	}

	/**
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 *
	 * @link   https://codex.wordpress.org/Function_Reference/add_theme_support#Title_Tag
	 *
	 * @since  1.0.0
	 */
	private function title_tag_support() {
		add_theme_support( 'title-tag' );
	}

	/**
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 *
	 * @link   https://codex.wordpress.org/Function_Reference/add_theme_support#HTML5
	 *
	 * @since  1.0.0
	 */
	private function html5_support() {
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
	}


	/**
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link   http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 *
	 * @since  1.0.0
	 */
	private function customizer_bg() {
		if ( $this->configs['customizer-options']['background']['enabled'] ) {
			add_theme_support( 'custom-background', apply_filters( 'boldgrid_custom_background_args', array(
				'default-image'          => $this->configs['customizer-options']['background']['defaults']['background_image'],
				'default-repeat'         => $this->configs['customizer-options']['background']['defaults']['background_repeat'],
				'default-attachment'     => $this->configs['customizer-options']['background']['defaults']['background_attachment'],
				'wp-head-callback'       => function ( $styles ) {

					// Disable background image styles, if using a pattern.
					if ( 'pattern' === get_theme_mod( 'boldgrid_background_type' ) ) {
						$styles = '';
					}

					return $styles;
				},
			) ) );
		}
	}

	/**
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link   http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 *
	 * @since  1.0.0
	 */
	private function post_thumbs() {
		add_theme_support( 'post-thumbnails' );
	}

	/**
	 * Enable support for Post Formats.
	 *
	 * @link    http://codex.wordpress.org/Post_Formats
	 *
	 * @since   1.0.0
	 */
	private function post_formats() {
		if ( false === empty( $this->configs['post_formats'] ) ) {
			add_theme_support( 'post-formats', $this->configs['post_formats'] );
		}
	}

	/**
	 * Add theme support for Jetpack's Infinite Scroll.
	 *
	 * @link    http://jetpack.me/support/infinite-scroll/
	 *
	 * @since   1.0.0
	 */
	private function jetpack_setup() {
		add_theme_support( 'infinite-scroll', array( 'container' => 'main', 'footer' => 'page' ) );
	}

	/**
	 * Add theme support for the wooCommerce plugin.
	 *
	 * @link     http://docs.woothemes.com/document/declare-woocommerce-support-in-third-party-theme/
	 *
	 * @since    1.0.0
	 */
	private function woo_commerce_setup() {
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}


	/**
	 * Add theme support for header images if requested
	 *
	 * @since    1.0.0
	 */
	public function header_image_setup() {
		if ( ! empty( $this->configs['customizer-options']['header-image']['enabled'] ) ) {
			register_default_headers( array(
				'default_image' => array(
				'url'   		=> $this->configs['customizer-options']['header-image']['defaults']['default-image'],
				'thumbnail_url' => $this->configs['customizer-options']['header-image']['defaults']['default-image'],
				),
				)
			);
			add_theme_support( 'custom-header', $this->configs['customizer-options']['header-image']['defaults'] );
		}
	}

	/**
	 * Add support for custom logo.
	 *
	 * @since 2.0.0
	 */
	public function custom_logo_setup() {
		add_theme_support(
			'custom-logo',
			apply_filters(
				'bgtfw_custom_logo_args',
				array(
					'height'      => 100,
					'width'       => 400,
					'flex-height' => true,
					'flex-width'  => true,
					'header-text' => array(
						'site-title',
						'site-description',
					),
				)
			)
		);
	}
}

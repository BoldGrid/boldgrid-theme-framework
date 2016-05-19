<?php
/**
 * Class: BoldGrid_Framework_Scripts
 *
 * This is used to enqueue scripts in one place.
 *
 * This pulls configuration, directories and version information from the framework configs.
 *
 * @since      1.0.0
 * @package    BoldGrid_Framework
 * @subpackage BoldGrid_Framework_Scripts
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

// If this file is called directly, abort.
defined( 'WPINC' ) ? : die;

/**
 * Class: BoldGrid_Framework_Scripts
 *
 * This is used to enqueue scripts in one place.
 *
 * This pulls configuration, directories and version information from the framework configs.
 *
 * @since      1.0.0
 */
class BoldGrid_Framework_Scripts {

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
	 * Enqueue the scripts for our BoldGrid Theme.
	 *
	 * @since 1.0.0
	 */
	public function boldgrid_enqueue_scripts() {
		// Minify if script debug is off.
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		/**
		 * Various Bootstrap Shims to adjust styling for WordPress Elements.
		 *
		 * @since 1.0.0
		 */
		wp_enqueue_script(
			'bootstrap-scripts',
			$this->configs['framework']['js_dir'] . 'boldgrid-bootstrap-shim' . $suffix . '.js',
			array( 'jquery' ),
			$this->configs['version'],
			true
		);

		/**
		 * Core Bootstrap.js file.  This is not necessary for all themes, if you don't need it feel free to remove it!
		 *
		 * @since 1.0.0
		 */
		wp_enqueue_script(
			'boldgrid-bootstrap-bootstrap',
			$this->configs['framework']['js_dir'] . 'bootstrap/bootstrap' . $suffix . '.js',
			array( 'jquery' ),
			'3.3.6',
			true
		);

		/**
		 * General Boldgrid scripts
		 *
		 * Used for small snippets of code that should always be applied
		 *
		 * @since 1.0.0
		 */
		wp_enqueue_script(
			'boldgrid-front-end-scripts',
			$this->configs['framework']['js_dir'] . 'front-end' . $suffix . '.js',
			array( 'jquery' ),
			$this->configs['version'],
			true
		);

		/**
		 * Hide/Show Author Box
		 */
		if ( is_single( ) || is_author( ) ) {
			wp_enqueue_script(
				'hide-author-box',
				$this->configs['framework']['js_dir'] . 'hide-author-box' . $suffix . '.js',
				array( 'jquery' ),
				$this->configs['version'],
				true
			);
		}
		/**
		 * Check to see if comments are open before enqueuing the WP core comment-reply js.
		 *
		 * @since 1.0.0
		 */
		if ( is_singular( ) && comments_open( ) && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		/**
		 * Add wow.js for scroll animation events if a theme requests it.
		 *
		 * @since 1.0.0
		 */
	 	if ( true === $this->configs['scripts']['wow-js'] ) {
			$handle = 'boldgrid-wow-js';
			wp_enqueue_script(
				$handle,
				$this->configs['framework']['js_dir'] . 'wow/wow' . $suffix . '.js',
				array( 'jquery' ),
				$this->configs['version']
			);
			$wp_scripts = wp_scripts();
			$wow_configs = $this->configs['scripts']['options']['wow-js'];
			$wp_scripts->add_data( $handle, 'data', sprintf( 'var _wowJsOptions = %s;', wp_json_encode( $wow_configs ) ) );
		}

		/**
		 * Add the sticky header nav script thing if a theme requests it and is not on a mobile device.
		 *
		 * @TODO  should collapsing for mobile be set as a config option?
		 * @since 1.0.0
		 */
		if ( ( true === $this->configs['scripts']['boldgrid-sticky-nav'] ) && ! wp_is_mobile() ) {
			wp_enqueue_script(
				'boldgrid-sticky-nav-js',
				$this->configs['framework']['js_dir'] . 'boldgrid-sticky-nav' . $suffix . '.js',
				array( 'jquery' ),
				$this->configs['version'],
				true
			);
		}

		/**
		 * Add offcanvas.js for offcanvas menus if a theme requests it.
		 *
		 * @since 1.0.0
		 */
		if ( true === $this->configs['scripts']['offcanvas-menu'] ) {
			wp_enqueue_script(
				'boldgrid-offcanvas-js',
				$this->configs['framework']['js_dir'] . 'offcanvas/offcanvas' . $suffix . '.js',
				array( 'jquery' ),
				$this->configs['version']
			);
		}

		/**
		 * Add slimscroll support if specified by configs.
		 *
		 * @since 1.0.0
		 */
		if ( true === $this->configs['scripts']['options']['nicescroll']['enabled'] ) {
			wp_enqueue_script(
				'boldgrid-nicescroll-js',
				$this->configs['framework']['js_dir'] . 'niceScroll/jquery.nicescroll.min.js',
				array( 'jquery' ),
				$this->configs['version']
			);

			$wp_scripts = wp_scripts();
			$nice_configs = $this->configs['scripts']['options']['nicescroll'];
			$wp_scripts->add_data( 'boldgrid-nicescroll-js', 'data', sprintf( 'var _niceScrollOptions = %s;', wp_json_encode( $nice_configs ) ) );
		}

		/**
		 * Add jQuery Goup Scroll To Top Plugin.
		 *
		 * @since 1.0.0
		 */
		if ( true === $this->configs['scripts']['options']['goup']['enabled'] ) {
			wp_enqueue_script(
				'boldgrid-goup-js',
				$this->configs['framework']['js_dir'] . 'goup/jquery.goup' . $suffix . '.js',
				array( 'jquery' ),
				$this->configs['version']
			);

			$wp_scripts = wp_scripts();
			$goup_configs = $this->configs['scripts']['options']['goup'];
			$wp_scripts->add_data( 'boldgrid-goup-js', 'data', sprintf( 'var _goupOptions = %s;', wp_json_encode( $goup_configs ) ) );
		}

		/**
		 * Enqueue theme specific javascript if the file exists.
		 *
		 * @since 1.1.5
		 */
		$file = '/js/theme.js';

		if ( file_exists( get_stylesheet_directory() . $file ) ) {
			wp_enqueue_script(
				'theme-js',
				get_stylesheet_directory_uri() . $file,
				array( 'jquery' ),
				$this->configs['version']
			);
		}
	}
}

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
			wp_enqueue_script(
				'boldgrid-wow-js',
				$this->configs['framework']['js_dir'] . 'wow/wow' . $suffix . '.js',
				array( 'jquery' ),
				$this->configs['version']
			);
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

	}
}

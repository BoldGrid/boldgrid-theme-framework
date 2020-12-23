<?php
/**
 * Class: Boldgrid_Framework_Wp_Fs
 *
 * Functions for interacting with WordPress Filesystem.
 *
 * @since      1.2.3
 * @package    Boldgrid_Framework
 * @subpackage Boldgrid_Framework_SCSS
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

use ScssPhp\ScssPhp\Compiler;

/**
 * Class: Boldgrid_Framework_Wp_Fs
 *
 * Functions for interacting with WordPress Filesystem.
 *
 * @since      1.2.3
 */
class Boldgrid_Framework_Wp_Fs {

	/**
	 * Initialize the WP_Filesystem.
	 *
	 * @since 1.2.3
	 * @global $wp_filesystem WordPress Filesystem global.
	 */
	public function init() {
		global $wp_filesystem;
		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';
			WP_Filesystem();
		}
	}

	/**
	 * Save Compiled SCSS.
	 *
	 * @since 1.2.3
	 *
	 * @param string $content Content to save.
	 * @param string $file File to write to.
	 */
	public function save( $content, $file ) {
		self::init();
		global $wp_filesystem;

		// Write output to CSS file.
		$chmod_file = ( 0644 & ~ umask() );
		if ( defined( 'FS_CHMOD_FILE' ) ) {
			$chmod_file = FS_CHMOD_FILE;
		}
		$wp_filesystem->put_contents( $file, $content, $chmod_file );
	}

	/**
	 * Get file content.
	 *
	 * @since 2.0.3
	 *
	 * @param string $file File to write to.
	 */
	public function get_contents( $file ) {
		self::init();
		global $wp_filesystem;

		return $wp_filesystem->get_contents( $file );
	}
}

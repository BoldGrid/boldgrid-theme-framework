<?php
/**
 * Plugin Name: BoldGrid Theme Framework
 * Plugin URI: http://www.boldgrid.com
 * Description: BoldGrid Theme Framework is a library that allows you to easily make BoldGrid themes. Please see our reference guide for more information: https://www.boldgrid.com/docs/configuration-file
 * Version: 1.3.3
 * Author: BoldGrid.com <wpb@boldgrid.com>
 * Author URI: http://www.boldgrid.com
 * Text Domain: bgtfw
 * Domain Path: /languages
 * License: GPLv2 or later
 */

/**
 * Load the BoldGrid Framework Into Our Themes.
 *
 * @since 1.0.0
 */
$theme_framework_path = get_template_directory() . '/inc/boldgrid-theme-framework';
if ( defined( 'BGTFW_PATH' ) ) {
	$theme_framework_path = ABSPATH . BGTFW_PATH;
}

$theme_framework_class = $theme_framework_path . '/includes/class-boldgrid-framework.php';
if ( file_exists( $theme_framework_class ) ) {
	/**
	 * Set up constants for our theme framework directory structure.
	 *
	 * @since 1.0.0
	 */
	require_once $theme_framework_class;
	$boldgrid_theme_framework = new Boldgrid_Framework();
	$boldgrid_theme_framework->run();
}
?>

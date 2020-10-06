<?php
/**
 * Plugin Name: BoldGrid Theme Framework
 * Plugin URI: https://www.boldgrid.com/docs/configuration-file
 * Description: BoldGrid Theme Framework is a library that allows you to easily make BoldGrid themes. Please see our reference guide for more information: https://www.boldgrid.com/docs/configuration-file
 * Version: 1.7.7
 * Author: BoldGrid.com <wpb@boldgrid.com>
 * Author URI: https://www.boldgrid.com/
 * Text Domain: bgtfw
 * Domain Path: /languages
 * License: GPLv2 or later
 *
 * @package Boldgrid_Theme_Framework
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

	$uri           = $_SERVER['REQUEST_URI'];
	$is_admin_ajax = false !== strpos( $uri, 'admin-ajax' );
	$is_editor     = false !== strpos( $uri, 'edit' );
	$is_admin_post = is_admin() && ( false !== strpos( $uri, 'post' ) );
	// Only Load theme if page is admin, customizer or editor
	if( false === is_admin() || is_customize_preview() || $is_admin_ajax || $is_editor || $is_admin_post ) {
		$boldgrid_theme_framework = new Boldgrid_Framework();
		$boldgrid_theme_framework->run();
	}
}
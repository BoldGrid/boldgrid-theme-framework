<?php
/**
 * Load the BoldGrid Framework Into Our Themes.
 *
 * @since 1.0.0
 */
$theme_framework = get_template_directory() . '/inc/boldgrid-theme-framework/includes/class-boldgrid-framework.php';
if ( file_exists( $theme_framework ) ) {
	/**
	 * Set up constants for our theme framework directory structure.
	 *
	 * @since 1.0.0
	 */
	require_once $theme_framework;
	$boldgrid_theme_framework = new Boldgrid_Framework();
	$boldgrid_theme_framework->run();
}
?>

<?php
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

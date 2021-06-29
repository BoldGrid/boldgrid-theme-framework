<?php
/**
 * The base configuration options for bgtfw.
 *
 * @package Boldgrid_Theme_Framework
 * @subpackage Boldgrid_Theme_Framework\Configs
 *
 * @since    1.1
 *
 * @return   array   An array of base configs used in bgtfw.
 */

$bgtfw_dir = realpath( plugin_dir_path( __FILE__ ) . '../..' );

$bgtfw_uri = get_template_directory_uri()
	. '/inc/boldgrid-theme-framework';

if ( defined( 'BGTFW_PATH' ) ) {
	$bgtfw_uri = get_site_url() . BGTFW_PATH;
}

$bgtfw_theme_data = wp_get_theme();
$bgtfw_parent = is_child_theme() ? $bgtfw_theme_data->template : $bgtfw_theme_data->stylesheet;

return array(

	// Temp configs rolling out to themes.
	'temp' => array(
		'attribution_links'    => false,
	),

	// Required From Theme - these are defaults.
	'theme_name' => $bgtfw_theme_data->stylesheet,
	'theme-parent-name' => $bgtfw_parent,
	'version' => $bgtfw_theme_data->version,
	'framework-version' => implode( '', get_file_data( $bgtfw_dir . '/boldgrid-theme-framework.php', array( 'Version' ), 'plugin' ) ),
	'theme_id' => null,
	'boldgrid-parent-theme' => false,
	'bootstrap' => false,

	// End Required.
	'textdomain' => $bgtfw_theme_data->get( 'TextDomain' ),
	'framework' => array(
		'asset_dir'       => $bgtfw_dir . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR,
		'includes_dir'    => $bgtfw_dir . '/includes/',
		'root_uri'        => $bgtfw_uri . '/',
		'webpack_server'  => 'http://localhost:4009/',
		'admin_asset_dir' => $bgtfw_uri . '/assets/',
		'js_dir'          => $bgtfw_uri . '/assets/js/',
		'css_dir'         => $bgtfw_uri . '/assets/css/',
		'inline_styles'   => false,
	),

	/**
	 * No Post Format Styles are required by default
	 * Theme authors can add post formats here. Eventually post formats will be required
	 * and can be added here
	 *
	 * @since 1.0.4
	 */
	'post_formats' => array(),

	/**
	 * Customizer Specific Configurations
	 *
	 * @since 1.0.0
	 */
	'customizer-options' => array(
		'edit_enabled'    => false,
		'site_logo'       => true,
		'header_panel'    => true,
		'header_controls' => array(
			'widgets'     => true,
			'custom_html' => true,
		),

		'footer_panel'    => true,
		'footer_controls' => array(
			'widgets'     => true,
			'custom_html' => true,
		),

		'advanced_panel' => true,
		'advanced_controls' => array(
			'css_editor' => true,
			'js_editor'  => true,
		),
		'effects_panel' => false,
	),
);

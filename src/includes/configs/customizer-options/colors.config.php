<?php
/**
 * Color Palette Configuration Options.
 *
 * @package Boldgrid_Theme_Framework
 * @subpackage Boldgrid_Theme_Framework\Configs
 *
 * @since    1.1
 *
 * @return   array   An array of color palette configs.
 */
global $boldgrid_theme_framework;
$bgtfw_configs = $boldgrid_theme_framework->get_configs();

return array(
	'enabled' => true,
	'defaults' => array(),
	'light_text' => '#ffffff',
	'dark_text' => '#4d4d4d',
	'settings' => array(
		// Directory that contains SCSS files to be compiled.
		'scss_directory' => array(
			'framework_dir' => $bgtfw_configs['framework']['asset_dir'] . 'scss/custom-color',
			'default' => '/inc/boldgrid-theme-framework-config/scss',
		),

		// After the helper compiles the css, where should the css be stored?
		'output_css_name' => $bgtfw_configs['framework']['config_directory']['template'] . '/css/color-palettes.css',

		// Should the output be minified?
		'minify_output' => true,
	),
);

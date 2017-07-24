<?php
/**
 * Button Configuration Options.
 *
 * @package Boldgrid_Theme_Framework
 * @subpackage Boldgrid_Theme_Framework\Configs
 *
 * @since    1.2.3
 *
 * @return   array   Array of button configs.
 */

return array(
	'enabled' => true,
	'css_file' => $this->configs['framework']['config_directory']['template'] . '/css/buttons.css',
	'css_uri' => $this->configs['framework']['config_directory']['uri'] . '/css/buttons.css',
	'variables' => array(
		'ubtn-namespace' => '.btn',
		'ubtn-glow-namespace' => '.glow',
		// 'ubtn-colors' => Boldgrid_Framework_Compile_Colors::get_button_colors(),
		'ubtn-glow-color' => '#2c9adb, light blue',
		// 'ubtn-shapes' =>,
		// 'ubtn-sizes' => ,
		// 'ubtn-bgcolor' => '$' . get_theme_mod( 'boldgrid_palette_class', 'palette-primary' ) . '_' . Boldgrid_Framework_Compile_Colors::get_button_default_color() . ';',
		// 'ubtn-height' => ,
		'ubtn-font-family' => 'inherit',
		// 'ubtn-font-color' => '$text-contrast-' . get_theme_mod( 'boldgrid_palette_class', 'palette-primary' ) . '_' . Boldgrid_Framework_Compile_Colors::get_button_default_color() . ';',
		'ubtn-font-weight' => 'inherit',
		'ubtn-font-size' => '1em',
	),
);

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
	'variables' => array(
		'ubtn-namespace' => '.btn',
		'ubtn-glow-namespace' => '.glow',
		'ubtn-colors' => Boldgrid_Framework_Compile_Colors::get_button_colors(),
		'ubtn-glow-color' => '#2c9adb, light blue',
		//'ubtn-shapes' =>,
		//'ubtn-sizes' => ,
		//'ubtn-bgcolor' => ,
		//'ubtn-height' => ,
		'ubtn-font-family' => 'inherit',
		//'ubtn-font-color' => ,
		'ubtn-font-weight' => 'inherit',
		'ubtn-font-size' => 'inherit',
		'button-primary-classes' => '.btn, .btn-3d, .btn-color-2, .btn-rounded',
		'button-secondary-classes' => '.btn, .btn-border, .btn-color-3, .btn-pill',
	),
);

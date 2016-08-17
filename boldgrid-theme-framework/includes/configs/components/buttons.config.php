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
		//'ubtn-colors' => ,
		'ubtn-glow-color' => '#2c9adb, light blue',
		//'ubtn-shapes' =>,
		//'ubtn-sizes' => ,
		//'ubtn-bgcolor' => ,
		//'ubtn-height' => ,
		'ubtn-font-family' => get_theme_mod( 'body_font_family', $this->configs['customizer-options']['typography']['defaults']['body_font_family'] ),
		//'ubtn-font-color' => ,
		//'ubtn-font-weight' => ,
		'ubtn-font-size' => get_theme_mod( 'body_font_family', $this->configs['customizer-options']['typography']['defaults']['body_font_family'] ),
		'button-primary-classes' => '.btn, .btn-3d, .btn-primary, .btn-rounded',
		'button-secondary-classes' => '.btn, .btn-border',
	),
);

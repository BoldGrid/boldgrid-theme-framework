<?php
/**
 * Background Configuration Options.
 *
 * @package Boldgrid_Theme_Framework
 * @subpackage Boldgrid_Theme_Framework\Configs
 *
 * @since    1.1
 *
 * @return   array   An array of background configs.
 */
global $boldgrid_theme_framework;
$configs = $boldgrid_theme_framework->get_configs();

return array(
	'enabled' => true,
	'defaults' => array(
		// Pattern or image?
		'boldgrid_background_type' => 'image',

		// Background Pattern Mods.
		'boldgrid_background_pattern' => '60-lines.png',
		'boldgrid_background_color' => '',

		// Background Image Mods.
		'boldgrid_background_image_size' => 'cover',
		'boldgrid_background_vertical_position' => '0',
		'boldgrid_background_horizontal_position' => '0',
		'background_image' => get_theme_mod( 'default_background_image',
				$this->configs['framework']['config_directory']['uri'] . '/images/background.jpg' ),
		'background_repeat' => 'no-repeat',
		'background_attachment' => 'fixed',
		'recommended_image_width' => 1920,
		'recommended_image_height' => 1080,
	),
);
?>

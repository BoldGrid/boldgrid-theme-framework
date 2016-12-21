<?php
/**
 * Header Image Configuration Options.
 *
 * @package Boldgrid_Theme_Framework
 * @subpackage Boldgrid_Theme_Framework\Configs
 *
 * @since    1.1
 *
 * @return   array   An array of typography configs.
 */

return array(
	'enabled' => false,
	'defaults' => array(
		'width'         => 600,
		'height'        => 600,
		'uploads'       => true,
		'default-image' => get_theme_mod( 'default_header_image', get_template_directory_uri() . '/images/header.png' ),
	),
);


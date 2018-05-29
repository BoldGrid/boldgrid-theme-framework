<?php
/**
 * The menu configuration options for bgtfw.
 *
 * @package Boldgrid_Theme_Framework
 * @subpackage Boldgrid_Theme_Framework\Configs
 *
 * @since    1.1
 *
 * @return   array   An array of menu configs used in bgtfw.
 */

return array(
	'action_prefix' => 'boldgrid_menu_',
	'footer_menus' => array(
		// When the footer is disabled, these menus will be removed
		'footer_center'
	),
	'locations' => array(
		'main' => 'Main Menu',
		'social' => 'Social Icons',
		'secondary' => 'Secondary Menu',
		'footer_center' => 'Footer Menu',
	),
	'prototype' => array(
		'main' => array(
			'theme_location' => 'main',
			'container' => false,
			'menu_id' => 'main-menu',
			'menu_class' => 'sm sm-clean main-menu',
		),
		'social' => array(
			'theme_location'  => 'social',
			'container'       => false,
			'menu_id'         => 'social-menu',
			'menu_class'      => 'sm sm-clean social-menu',
		),
		'secondary' => array(
			'theme_location'  => 'secondary',
			'container'       => false,
			'menu_id'         => 'secondary-menu',
			'menu_class'      => 'sm sm-clean secondary-menu',
		),
		'footer_center' => array(
			'theme_location'  => 'footer_center',
			'container'       => false,
			'menu_id'         => 'footer-center-menu',
			'menu_class'      => 'sm sm-clean footer-center-menu',
		),
	),
	'default-menus' => array(),
);

<?php
/**
 * Quick Start Items Configuration File.
 *
 * This configuration array is used to generate the Quick Start items in the.
 * Customizer Quick Start Guide.
 *
 * @package Boldgrid_Theme_Framework
 */

return array(
	'main'   => array(
		'nav'   => array(),
		'items' => array(
			array(
				'title' => 'Choose Your Colors',
				'child' => 'colors',
			),
			array(
				'title' => 'Design Your Header',
				'child' => 'header',
			),
			array(
				'title' => 'Customize Your Content Layouts',
				'child' => 'layouts',
			),
			array(
				'title' => 'Create Your Sidebars',
				'child' => 'sidebars',
			),
			array(
				'title' => 'Design Your Footer',
				'child' => 'footer',
			),
		),
	),
	'colors' => array(
		'nav'   => array( 'main' ),
		'items' => array(
			array(
				'title' => 'Choose Your Color Palette',
				'focus' => 'boldgrid-color-palette',
				'child' => 'color-palette',
			),
			array(
				'title' => 'Change Font Colors',
				'child' => 'font-colors',
			),
			array(
				'title' => 'Change Background Colors',
				'child' => 'bg-colors',
			),
		),
	),
	'color-palette' => array(
		'nav'   => array( 'main', 'colors' ),
		'items' => array(
			array(
				'title' => 'Choose Your Color Palette',
				'descr' => __( 'Crio comes with a color pallete generator, that can help you create a color pallete to be used throughout your site\'s design.', 'bgtfw' ),
			),
		),
	),
);

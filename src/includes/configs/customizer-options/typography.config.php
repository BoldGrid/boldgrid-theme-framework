<?php
/**
 * Typography Configuration Options.
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
	'controls' => array(
		'site_title'     => true,
		'headings' => true,
		'alternate_headings' => true,
		'main_text' => true,
		'navigation' => true,
	),
	'defaults' => array(
		'headings_font_size' => '18px',
		'headings_font_family' => 'Roboto',
		'headings_text_transform' => 'uppercase',
		'alternate_headings_font_size' => 14,
		'alternate_headings_font_family' => 'Oswald',
		'alternate_headings_text_transform' => 'uppercase',
		'body_font_size' => '18px',
		'body_font_family' => 'Roboto',
		'body_line_height' => '1.4',
		'navigation_font_size' => '18px',
		'navigation_text_transform' => 'uppercase',
		'navigation_font_family' => 'Roboto',
	),
	'selectors' => array(
		'h1, .h1' => array(
			'type' => 'headings',
			'round' => 'floor',
			'amount' => 2.6,
		),
		'h2, .h2' => array(
			'type' => 'headings',
			'round' => 'floor',
			'amount' => 2.15,
		),
		'h3, .h3' => array(
			'type' => 'headings',
			'round' => 'ceil',
			'amount' => 1.7,
		),
		'h4, .h4' => array(
			'type' => 'headings',
			'round' => 'ceil',
			'amount' => 1.25,
		),
		'h5, .h5' => array(
			'type' => 'headings',
			'round' => 'floor',
			'amount' => 1,
		),
		'h6, .h6' => array(
			'type' => 'headings',
			'round' => 'ceil',
			'amount' => 0.85,
		),
		// Enable this styling for Page Header Headings.
		'.bgc-heading.bgc-page-title' => array(
			'type' => 'headings',
			'round' => 'floor',
			'amount' => 1,
		),
	),
);

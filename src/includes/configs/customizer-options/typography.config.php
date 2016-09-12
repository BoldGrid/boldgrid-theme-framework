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
		'headings_font_size' => 14,
		'headings_font_family' => 'Oswald',
		'headings_text_transform' => 'uppercase',
		'alternate_headings_font_size' => 14,
		'alternate_headings_font_family' => 'Oswald',
		'alternate_headings_text_transform' => 'uppercase',
		'body_font_size' => 14,
		'body_font_family' => 'Open Sans',
		'body_line_height' => 136,
		'navigation_font_size' => 14,
		'navigation_text_transform' => 'uppercase',
		'navigation_font_family' => 'Oswald',
	),
	'selectors' => array(
		'h1:not( .site-title ):not( .alt-font ), .h1' => array(
			'type' => 'headings',
			'round' => 'floor',
			'amount' => 2.6,
		),
		'h2:not( .alt-font ), .h2' => array(
			'type' => 'headings',
			'round' => 'floor',
			'amount' => 2.15,
		),
		'h3:not( .alt-font ):not( .site-description ), .h3' => array(
			'type' => 'headings',
			'round' => 'ceil',
			'amount' => 1.7,
		),
		'h4:not( .alt-font ), .h4' => array(
			'type' => 'headings',
			'round' => 'ceil',
			'amount' => 1.25,
		),
		'h5:not( .alt-font ), .h5' => array(
			'type' => 'headings',
			'round' => 'floor',
			'amount' => 1,
		),
		'h6:not( .alt-font ), .h6' => array(
			'type' => 'headings',
			'round' => 'ceil',
			'amount' => 0.85,
		),
		'h1.alt-font, .h1.alt-font' => array(
			'type' => 'subheadings',
			'round' => 'floor',
			'amount' => 2.6,
		),
		'h2.alt-font, .h2.alt-font' => array(
			'type' => 'subheadings',
			'round' => 'floor',
			'amount' => 2.15,
		),
		'h3.alt-font, .h3.alt-font' => array(
			'type' => 'subheadings',
			'round' => 'ceil',
			'amount' => 1.7,
		),
		'h4.alt-font, .h4.alt-font' => array(
			'type' => 'subheadings',
			'round' => 'ceil',
			'amount' => 1.25,
		),
		'h5.alt-font, .h5.alt-font' => array(
			'type' => 'subheadings',
			'round' => 'floor',
			'amount' => 1,
		),
		'h6.alt-font, .h6.alt-font' => array(
			'type' => 'subheadings',
			'round' => 'ceil',
			'amount' => 0.85,
		),
	),
);
?>

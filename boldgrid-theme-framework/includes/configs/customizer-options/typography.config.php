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
);
?>

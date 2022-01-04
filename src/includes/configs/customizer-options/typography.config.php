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
		'.widget, .site-content, .attribution-theme-mods-wrapper, .gutenberg .edit-post-visual-editor, .mce-content-body, .template-header' => array(
			'type' => 'body',
		),
		'.palette-primary .button-primary:not(.menu-item)' => array(
			'type' => 'button_primary',
		),
		'.palette-primary .button-secondary:not(.menu-item)' => array(
			'type' => 'button_secondary',
		),
	),
	'responsive_font_controls' => array(
		'bgtfw_body_font_size' => array(
			'section'         => 'boldgrid_typography',
			'priority'        => 2,
			'output_selector' => '.widget, .site-content, .attribution-theme-mods-wrapper, .gutenberg .edit-post-visual-editor, .mce-content-body, .template-header',
		),
		'bgtfw_headings_responsive_font_size' => array(
			'section'         => 'boldgrid_typography',
			'priority'        => 5,
			'output_selector' => 'h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6, .bgc-heading.bgc-page-title',
		),
		'bgtfw_site_title_font_size' => array(
			'section'         => 'bgtfw_site_title',
			'priority'        => 21,
			'output_selector' => '.site-footer .site-title > a, .' . get_theme_mod( 'boldgrid_palette_class', 'palette-primary' ) . '.site-header .site-title > a, .' . get_theme_mod( 'boldgrid_palette_class', 'palette-primary' ) . ' .site-header .site-title > a,.' . get_theme_mod( 'boldgrid_palette_class', 'palette-primary' ) . ' .site-header .site-title > a:hover, .bgc-site-title, .bgc-site-title:hover',
		),
		'bgtfw_button_primary_font_size' => array(
			'section'         => 'boldgrid_typography',
			'priority'        => 11,
			'output_selector' => '.palette-primary .button-primary:not(.menu-item)',
		),
		'bgtfw_button_secondary_font_size' => array(
			'section'         => 'boldgrid_typography',
			'priority'        => 13,
			'output_selector' => '.palette-primary .button-secondary:not(.menu-item)',
		),
	),
);

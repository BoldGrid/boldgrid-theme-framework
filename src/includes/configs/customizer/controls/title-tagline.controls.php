<?php
/**
 * Customizer Controls Configs.
 *
 * @package Boldgrid_Theme_Framework
 * @subpackage Boldgrid_Theme_Framework\Configs\Customizer\Controls
 *
 * @since 2.0.0
 *
 * @return array Controls to create in the WordPress Customizer.
 */

return array(
	'bgtfw_site_title_color' => array(
		'type'        => 'bgtfw-palette-selector',
		'transport' => 'postMessage',
		'settings'    => 'bgtfw_site_title_color',
		'label' => esc_attr__( 'Color', 'bgtfw' ),
		'section'     => 'bgtfw_site_title',
		'priority' => 10,
		'default'     => '',
		'choices'     => array(
			'colors' => $bgtfw_formatted_palette,
			'size' => $bgtfw_palette->get_palette_size( $bgtfw_formatted_palette ),
		),
		'sanitize_callback' => array( $bgtfw_color_sanitize, 'sanitize_palette_selector' ),
	),
	'bgtfw_site_title_typography' => array(
		'type'        => 'typography',
		'transport'   => 'auto',
		'settings'    => 'bgtfw_site_title_typography',
		'label'       => esc_attr__( 'Typography', 'bgtfw' ),
		'section'     => 'bgtfw_site_title',
		'default'     => array(
			'font-family'    => 'Roboto',
			'variant'        => 'regular',
			'font-size'      => '42px',
			'line-height'    => '1.5',
			'letter-spacing' => '0',
			'subsets'        => array( 'latin-ext' ),
			'text-transform' => 'none',
			'text-align'     => 'left',
		),
		'priority'    => 20,
		'output'      => array(
			array(
				'element' => '.site-footer .site-title > a, .' . get_theme_mod( 'boldgrid_palette_class', 'palette-primary' ) . '.site-header .site-title > a, .' . get_theme_mod( 'boldgrid_palette_class', 'palette-primary' ) . ' .site-header .site-title > a,.' . get_theme_mod( 'boldgrid_palette_class', 'palette-primary' ) . ' .site-header .site-title > a:hover, .bgc-site-title, .bgc-site-title:hover',
			),
		),
	),
	'bgtfw_tagline_color' => array(
		'type'        => 'bgtfw-palette-selector',
		'transport'   => 'postMessage',
		'settings'    => 'bgtfw_tagline_color',
		'label'       => esc_attr__( 'Color', 'bgtfw' ),
		'section'     => 'bgtfw_tagline',
		'priority'    => 10,
		'default'     => '',
		'choices'     => array(
			'colors'  => $bgtfw_formatted_palette,
			'size'    => $bgtfw_palette->get_palette_size( $bgtfw_formatted_palette ),
		),
		'sanitize_callback' => array( $bgtfw_color_sanitize, 'sanitize_palette_selector' ),
	),
	'bgtfw_tagline_typography' => array(
		'type'        => 'typography',
		'transport'   => 'auto',
		'settings'    => 'bgtfw_tagline_typography',
		'label'       => esc_attr__( 'Typography', 'bgtfw' ),
		'section'     => 'bgtfw_tagline',
		'default'     => array(
			'font-family'    => 'Roboto',
			'variant'        => 'regular',
			'font-size'      => '42px',
			'line-height'    => '1.5',
			'letter-spacing' => '0',
			'subsets'        => array( 'latin-ext' ),
			'text-transform' => 'none',
			'text-align'     => 'left',
		),
		'priority'    => 20,
		'output'      => array(
			array(
				'element' => '.site-branding .site-description, .bgc-tagline',
			),
		),
	),
);

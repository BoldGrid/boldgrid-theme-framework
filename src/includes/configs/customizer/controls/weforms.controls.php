<?php
/**
 * Customizer Controls Configs.
 *
 * @package Boldgrid_Theme_Framework
 * @subpackage Boldgrid_Theme_Framework\Configs\Customizer\Controls
 *
 * @since 2.0.0
 *
 * @return array Controls to create in the WeForms Customizer.
 */

return array(
	'bgtfw_weforms_float_labels' => array(
		'type'        => 'switch',
		'transport'   => 'refresh',
		'settings'    => 'bgtfw_weforms_float_labels',
		'description' => __( 'Choose whether or not the labels should be floated within the input field', 'bgtfw' ),
		'label'       => esc_attr__( 'Float Labels', 'bgtfw' ),
		'section'     => 'bgtfw_weforms',
		'default'     => true,
		'priority'    => 1,
	),
	'bgtfw_weforms_label_color' => array(
		'type'              => 'bgtfw-palette-selector',
		'transport'         => 'postMessage',
		'settings'          => 'bgtfw_weforms_label_color',
		'label'             => esc_attr__( 'Label Font Color', 'bgtfw' ),
		'section'           => 'bgtfw_weforms',
		'priority'          => 1,
		'default'           => 'transparent',
		'choices'           => array(
			'colors' => $bgtfw_formatted_palette,
			'size'   => $bgtfw_palette->get_palette_size( $bgtfw_formatted_palette, true ),
			'transparent' => true,
		),
		'sanitize_callback' => array( $bgtfw_color_sanitize, 'sanitize_palette_selector' ),
	),
	'bgtfw_weforms_sublabel_color' => array(
		'type'              => 'bgtfw-palette-selector',
		'transport'         => 'postMessage',
		'settings'          => 'bgtfw_weforms_sublabel_color',
		'label'             => esc_attr__( 'Sub-label Font Color', 'bgtfw' ),
		'section'           => 'bgtfw_weforms',
		'priority'          => 1,
		'default'           => 'transparent',
		'choices'           => array(
			'colors' => $bgtfw_formatted_palette,
			'size'   => $bgtfw_palette->get_palette_size( $bgtfw_formatted_palette, true ),
			'transparent' => true,
		),
		'sanitize_callback' => array( $bgtfw_color_sanitize, 'sanitize_palette_selector' ),
	),
);

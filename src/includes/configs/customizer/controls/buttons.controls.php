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
	'bgtfw_primary_button_background' => array(
		'type'              => 'bgtfw-palette-selector',
		'transport'         => 'postMessage',
		'settings'          => 'bgtfw_primary_button_background',
		'label'             => esc_attr__( 'Primary Button Background Color', 'bgtfw' ),
		'description'       => esc_attr__( 'Choose a color from your palette to use.', 'bgtfw' ),
		'section'           => 'bgtfw_primary_button',
		'priority'          => 1,
		'default'           => 'color-1',
		'choices'           => array(
			'colors'      => $bgtfw_formatted_palette,
			'size'        => $bgtfw_palette->get_palette_size( $bgtfw_formatted_palette, true ),
			'transparent' => true,
		),
		'sanitize_callback' => array( $bgtfw_color_sanitize, 'sanitize_palette_selector' ),
		'edit_vars'         => array(
			array(
				'selector'    => '.button-primary',
				'label'       => __( 'Button Background Color', 'bgtfw' ),
				'description' => __( 'Change the color of the Primary Button Background', 'bgtfw' ),
			),
		),
		'output'            => array(
			array(
				'element'  => '.button-primary',
			),
		),
	),
	'bgtfw_primary_button_size' => array(
		'type'              => 'slider',
		'transport'         => 'postMessage',
		'settings'          => 'bgtfw_primary_button_size',
		'label'             => esc_attr__( 'Primary Button Size', 'bgtfw' ),
		'section'           => 'bgtfw_primary_button',
		'priority'          => 2,
		'default'           => '3',
		'choices'         => array(
			'min'  => '1',
			'max'  => '6',
			'step' => '1',
		),
		'sanitize_callback' => array( $bgtfw_color_sanitize, 'sanitize_palette_selector' ),
		'edit_vars'         => array(
			array(
				'selector'    => '.button-primary',
				'label'       => __( 'Button Size', 'bgtfw' ),
				'description' => __( 'Change the size of the Primary Buttons', 'bgtfw' ),
			),
		),
		'output'            => array(
			array(
				'element'  => '.button-primary',
			),
		),
	),
	'bgtfw_primary_button_shape' => array(
		'type'      => 'radio-buttonset',
		'transport' => 'postMessage',
		'settings'  => 'bgtfw_primary_button_shape',
		'label'     => esc_html__( 'Button Shape', 'bgtfw' ),
		'section'   => 'bgtfw_primary_button',
		'default'   => '',
		'priority'  => 10,
		'choices'   => array(
			''            => esc_html__( 'Normal', 'bgtfw' ),
			'btn-rounded' => esc_html__( 'Rounded', 'bgtfw' ),
			'btn-pill'    => esc_html__( 'Pill', 'bgtfw' ),
		),
	),
	'bgtfw_primary_button_raised' => array(
		'type'        => 'radio-buttonset',
		'transport'   => 'postMessage',
		'settings'    => 'bgtfw_primary_button_raised',
		'label'       => esc_html__( 'Button Raised', 'bgtfw' ),
		'section'     => 'bgtfw_primary_button',
		'default'     => '',
		'priority'    => 10,
		'choices'     => array(
			''            => esc_html__( 'Normal', 'bgtfw' ),
			'btn-raised' => esc_html__( 'Raised', 'bgtfw' ),
		),
	),
	'bgtfw_primary_button_text_shadow' => array(
		'type'        => 'radio-buttonset',
		'transport'   => 'postMessage',
		'settings'    => 'bgtfw_primary_button_text_shadow',
		'label'       => esc_html__( 'Text Shadow', 'kirki' ),
		'section'     => 'bgtfw_primary_button',
		'default'     => '',
		'priority'    => 10,
		'choices'     => array(
			''                => esc_html__( 'Disabled', 'kirki' ),
			'btn-longshadow'  => esc_html__( 'Enabled', 'kirki' ),
		),
	),
	'bgtfw_primary_button_effect' => array(
		'type'      => 'radio-buttonset',
		'transport' => 'postMessage',
		'settings'  => 'bgtfw_primary_button_effect',
		'label'     => esc_html__( 'Button Effect', 'kirki' ),
		'section'   => 'bgtfw_primary_button',
		'default'   => '',
		'priority'  => 10,
		'choices'   => array(
			''         => esc_html__( 'None', 'kirki' ),
			'btn-3d'   => esc_html__( '3D', 'kirki' ),
			'btn-glow' => esc_html__( 'Glow', 'kirki' ),
		),
	),
);
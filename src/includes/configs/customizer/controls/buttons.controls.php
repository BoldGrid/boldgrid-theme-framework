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
	// Primary Buttons.
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
				'label'       => __( 'Customize Primary Buttons', 'bgtfw' ),
				'description' => __( 'Change the style of the Primary Buttons', 'bgtfw' ),
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
		'priority'  => 3,
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
		'priority'    => 4,
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
		'priority'    => 5,
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
		'priority'  => 6,
		'choices'   => array(
			''         => esc_html__( 'None', 'kirki' ),
			'btn-3d'   => esc_html__( '3D', 'kirki' ),
			'btn-glow' => esc_html__( 'Glow', 'kirki' ),
		),
	),
	'bgtfw_primary_button_border' => array(
		'type'      => 'radio-buttonset',
		'transport' => 'postMessage',
		'settings'  => 'bgtfw_primary_button_border',
		'label'     => esc_html__( 'Button Border', 'kirki' ),
		'section'   => 'bgtfw_primary_button',
		'default'   => '',
		'priority'  => 7,
		'choices'   => array(
			''         => esc_html__( 'None', 'kirki' ),
			'btn-border btn-border-thin'   => esc_html__( 'Thin', 'kirki' ),
			'btn-border' => esc_html__( 'Medium', 'kirki' ),
			'btn-border btn-border-thick' => esc_html__( 'Thick', 'kirki' ),
		),
	),
	'bgtfw_primary_button_border_color' => array(
		'type'              => 'bgtfw-palette-selector',
		'transport'         => 'postMessage',
		'settings'          => 'bgtfw_primary_button_border_color',
		'label'             => esc_attr__( 'Button Border Color', 'bgtfw' ),
		'description'       => esc_attr__( 'Choose a color from your palette to use.', 'bgtfw' ),
		'section'           => 'bgtfw_primary_button',
		'priority'          => 8,
		'default'           => 'color-1',
		'choices'           => array(
			'colors'      => $bgtfw_formatted_palette,
			'size'        => $bgtfw_palette->get_palette_size( $bgtfw_formatted_palette, false ),
		),
		'sanitize_callback' => array( $bgtfw_color_sanitize, 'sanitize_palette_selector' ),
		'output'            => array(
			array(
				'element'  => '.button-primary',
			),
		),
	),
	'bgtfw_button_primary_typography'            => array(
		'type'      => 'typography',
		'transport' => 'auto',
		'settings'  => 'bgtfw_button_primary_typography',
		'label'     => esc_attr__( 'Primary Button Typography', 'bgtfw' ),
		'section'   => 'bgtfw_primary_button',
		'default'   => $bgtfw_typography->default_button_typography( $bgtfw_configs ),
		'priority'  => 10,
		'output'    => $bgtfw_typography->get_typography_output(
			$bgtfw_configs,
			'.palette-primary .button-primary:not(.menu-item)'
		),
		'edit_vars' => array(
			array(
				'selector'    => array(
					'.palette-primary .button-primary:not(.menu-item)',
				),
				'label'       => esc_attr__( 'Primary Button Typography', 'bgtfw' ),
				'description' => esc_attr__( 'Adjust the typography of your primary buttons', 'bgtfw' ),
			),
		),
	),
	// Secondary Buttons.
	'bgtfw_secondary_button_background' => array(
		'type'              => 'bgtfw-palette-selector',
		'transport'         => 'postMessage',
		'settings'          => 'bgtfw_secondary_button_background',
		'label'             => esc_attr__( 'Secondary Button Background Color', 'bgtfw' ),
		'description'       => esc_attr__( 'Choose a color from your palette to use.', 'bgtfw' ),
		'section'           => 'bgtfw_secondary_button',
		'priority'          => 1,
		'default'           => 'color-2',
		'choices'           => array(
			'colors'      => $bgtfw_formatted_palette,
			'size'        => $bgtfw_palette->get_palette_size( $bgtfw_formatted_palette, true ),
			'transparent' => true,
		),
		'sanitize_callback' => array( $bgtfw_color_sanitize, 'sanitize_palette_selector' ),
		'edit_vars'         => array(
			array(
				'selector'    => '.button-secondary',
				'label'       => __( 'Customize Secondary Buttons', 'bgtfw' ),
				'description' => __( 'Change the style of the Secondary Buttons', 'bgtfw' ),
			),
		),
		'output'            => array(
			array(
				'element'  => '.button-secondary',
			),
		),
	),
	'bgtfw_secondary_button_size' => array(
		'type'              => 'slider',
		'transport'         => 'postMessage',
		'settings'          => 'bgtfw_secondary_button_size',
		'label'             => esc_attr__( 'Secondary Button Size', 'bgtfw' ),
		'section'           => 'bgtfw_secondary_button',
		'priority'          => 2,
		'default'           => '3',
		'choices'           => array(
			'min'  => '1',
			'max'  => '6',
			'step' => '1',
		),
		'sanitize_callback' => array( $bgtfw_color_sanitize, 'sanitize_palette_selector' ),
		'output'            => array(
			array(
				'element'  => '.button-secondary',
			),
		),
	),
	'bgtfw_secondary_button_shape' => array(
		'type'      => 'radio-buttonset',
		'transport' => 'postMessage',
		'settings'  => 'bgtfw_secondary_button_shape',
		'label'     => esc_html__( 'Button Shape', 'bgtfw' ),
		'section'   => 'bgtfw_secondary_button',
		'default'   => '',
		'priority'  => 3,
		'choices'   => array(
			''            => esc_html__( 'Normal', 'bgtfw' ),
			'btn-rounded' => esc_html__( 'Rounded', 'bgtfw' ),
			'btn-pill'    => esc_html__( 'Pill', 'bgtfw' ),
		),
	),
	'bgtfw_secondary_button_raised' => array(
		'type'        => 'radio-buttonset',
		'transport'   => 'postMessage',
		'settings'    => 'bgtfw_secondary_button_raised',
		'label'       => esc_html__( 'Button Raised', 'bgtfw' ),
		'section'     => 'bgtfw_secondary_button',
		'default'     => '',
		'priority'    => 4,
		'choices'     => array(
			''            => esc_html__( 'Normal', 'bgtfw' ),
			'btn-raised' => esc_html__( 'Raised', 'bgtfw' ),
		),
	),
	'bgtfw_secondary_button_text_shadow' => array(
		'type'        => 'radio-buttonset',
		'transport'   => 'postMessage',
		'settings'    => 'bgtfw_secondary_button_text_shadow',
		'label'       => esc_html__( 'Text Shadow', 'kirki' ),
		'section'     => 'bgtfw_secondary_button',
		'default'     => '',
		'priority'    => 5,
		'choices'     => array(
			''                => esc_html__( 'Disabled', 'kirki' ),
			'btn-longshadow'  => esc_html__( 'Enabled', 'kirki' ),
		),
	),
	'bgtfw_secondary_button_effect' => array(
		'type'      => 'radio-buttonset',
		'transport' => 'postMessage',
		'settings'  => 'bgtfw_secondary_button_effect',
		'label'     => esc_html__( 'Button Effect', 'kirki' ),
		'section'   => 'bgtfw_secondary_button',
		'default'   => '',
		'priority'  => 6,
		'choices'   => array(
			''         => esc_html__( 'None', 'kirki' ),
			'btn-3d'   => esc_html__( '3D', 'kirki' ),
			'btn-glow' => esc_html__( 'Glow', 'kirki' ),
		),
	),
	'bgtfw_secondary_button_border' => array(
		'type'      => 'radio-buttonset',
		'transport' => 'postMessage',
		'settings'  => 'bgtfw_secondary_button_border',
		'label'     => esc_html__( 'Button Border', 'kirki' ),
		'section'   => 'bgtfw_secondary_button',
		'default'   => '',
		'priority'  => 7,
		'choices'   => array(
			''         => esc_html__( 'None', 'kirki' ),
			'btn-border btn-border-thin'   => esc_html__( 'Thin', 'kirki' ),
			'btn-border' => esc_html__( 'Medium', 'kirki' ),
			'btn-border btn-border-thick' => esc_html__( 'Thick', 'kirki' ),
		),
	),
	'bgtfw_secondary_button_border_color' => array(
		'type'              => 'bgtfw-palette-selector',
		'transport'         => 'postMessage',
		'settings'          => 'bgtfw_secondary_button_border_color',
		'label'             => esc_attr__( 'Button Border Color', 'bgtfw' ),
		'description'       => esc_attr__( 'Choose a color from your palette to use.', 'bgtfw' ),
		'section'           => 'bgtfw_secondary_button',
		'priority'          => 8,
		'default'           => 'color-1',
		'choices'           => array(
			'colors'      => $bgtfw_formatted_palette,
			'size'        => $bgtfw_palette->get_palette_size( $bgtfw_formatted_palette, true ),
			'transparent' => true,
		),
		'sanitize_callback' => array( $bgtfw_color_sanitize, 'sanitize_palette_selector' ),
		'output'            => array(
			array(
				'element'  => '.button-secondary',
			),
		),
	),
	'bgtfw_button_secondary_typography'            => array(
		'type'      => 'typography',
		'transport' => 'auto',
		'settings'  => 'bgtfw_button_secondary_typography',
		'label'     => esc_attr__( 'Secondary Button Typography', 'bgtfw' ),
		'section'   => 'bgtfw_secondary_button',
		'default'   => $bgtfw_typography->default_button_typography( $bgtfw_configs ),
		'priority'  => 10,
		'output'    => $bgtfw_typography->get_typography_output(
			$bgtfw_configs,
			'.palette-primary .button-secondary:not(.menu-item)'
		),
	),
);

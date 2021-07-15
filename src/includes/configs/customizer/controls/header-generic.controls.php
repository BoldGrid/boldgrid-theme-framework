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
	'bgtfw_header_margin' => array(
		'type'        => 'kirki-generic',
		'transport'   => 'postMessage',
		'section'     => 'boldgrid_header_margin_section',
		'settings'    => 'bgtfw_header_margin',
		'label'       => '',
		'default'     => '',
		'sanitize_callback' => array( 'Boldgrid_Framework_Customizer_Generic', 'sanitize' ),
		'choices' => array(
			'name' => 'boldgrid_controls',
			'type' => 'Margin',
			'settings' => array(
				'responsive' => Boldgrid_Framework_Customizer_Generic::$device_sizes,
				'control' => array(
					'selectors' => array( '.site-header' ),
					'sliders' => array(
						array( 'name' => 'top', 'label' => 'Top', 'cssProperty' => 'margin-top' ),
						array( 'name' => 'bottom', 'label' => 'Bottom', 'cssProperty' => 'margin-bottom' ),
					),
				),
			),
		),
	),
	'bgtfw_header_padding' => array(
		'type'        => 'kirki-generic',
		'transport'   => 'postMessage',
		'section'     => 'boldgrid_header_padding_section',
		'settings'    => 'bgtfw_header_padding',
		'label'       => '',
		'default'     => '',
		'sanitize_callback' => array( 'Boldgrid_Framework_Customizer_Generic', 'sanitize' ),
		'choices' => array(
			'name' => 'boldgrid_controls',
			'type' => 'Padding',
			'settings' => array(
				'responsive' => Boldgrid_Framework_Customizer_Generic::$device_sizes,
				'control' => array(
					'selectors' => array( '.site-header header' ),
				),
			),
		),
	),
	'bgtfw_header_border' => array(
		'type'        => 'kirki-generic',
		'transport'   => 'postMessage',
		'section'     => 'boldgrid_header_border_section',
		'settings'    => 'bgtfw_header_border',
		'label'       => '',
		'default'     => '',
		'sanitize_callback' => array( 'Boldgrid_Framework_Customizer_Generic', 'sanitize' ),
		'choices' => array(
			'name' => 'boldgrid_controls',
			'type' => 'Border',
			'settings' => array(
				'responsive' => Boldgrid_Framework_Customizer_Generic::$device_sizes,
				'control' => array(
					'selectors' => array( '.site-header header' ),
				),
			),
		),
	),
	'bgtfw_header_border_color' => array(
		'type'        => 'bgtfw-palette-selector',
		'transport'   => 'postMessage',
		'settings'    => 'bgtfw_header_border_color',
		'label'       => esc_attr__( 'Border Color', 'bgtfw' ),
		'section'     => 'boldgrid_header_border_section',
		'priority'    => 20,
		'default'     => 'color-1',
		'choices'     => array(
			'colors' => $bgtfw_formatted_palette,
			'size' => $bgtfw_palette->get_palette_size( $bgtfw_formatted_palette ),
		),
		'sanitize_callback' => array( $bgtfw_color_sanitize, 'sanitize_palette_selector' ),
	),
	'bgtfw_header_shadow' => array(
		'type'        => 'kirki-generic',
		'transport'   => 'postMessage',
		'section'     => 'boldgrid_header_shadow_section',
		'settings'    => 'bgtfw_header_shadow',
		'label'       => '',
		'default'     => '',
		'sanitize_callback' => array( 'Boldgrid_Framework_Customizer_Generic', 'sanitize' ),
		'choices' => array(
			'name' => 'boldgrid_controls',
			'type' => 'BoxShadow',
			'settings' => array(
				'responsive' => Boldgrid_Framework_Customizer_Generic::$device_sizes,
				'control' => array(
					'selectors' => array( '.site-header header' ),
				),
			),
		),
	),
	'bgtfw_header_radius' => array(
		'type'        => 'kirki-generic',
		'transport'   => 'postMessage',
		'section'     => 'boldgrid_header_radius_section',
		'settings'    => 'bgtfw_header_radius',
		'label'       => '',
		'default'     => '',
		'sanitize_callback' => array( 'Boldgrid_Framework_Customizer_Generic', 'sanitize' ),
		'choices' => array(
			'name' => 'boldgrid_controls',
			'type' => 'BorderRadius',
			'settings' => array(
				'responsive' => Boldgrid_Framework_Customizer_Generic::$device_sizes,
				'control' => array(
					'selectors' => array( '.site-header header', '.wp-custom-header' ),
				),
			),
		),
	),
);
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
	'boldgrid_background_type' => array(
		'type'      => 'radio-buttonset',
		'transport' => 'postMessage',
		'settings'  => 'boldgrid_background_type',
		'section'   => 'background_image',
		'default'   => 'image',
		'priority'  => 0,
		'choices'   => array(
			'image'   => '<span class="dashicons dashicons-format-image"></span>' . esc_html__( 'Image', 'bgtfw' ),
			'pattern' => '<span class="dashicons dashicons-art"></span>' . esc_html__( 'Pattern & Color', 'bgtfw' ),
		),
		'edit_vars' => array(
			array(
				'selector'    => array( '.page .site-content', '.post .site-content', '.blog .site-content' ),
				'label'       => __( 'Body Background', 'bgtfw' ),
				'description' => __( 'Change the background of your site', 'bgtfw' ),
			),
		),
	),
	'boldgrid_background_image_size' => array(
		'type'      => 'radio',
		'label'     => __( 'Background Image Size', 'bgtfw' ),
		'section'   => 'background_image',
		'settings'  => 'boldgrid_background_image_size',
		'transport' => 'refresh',
		'default'   => 'cover',
		'priority'  => 15,
		'choices'   => array(
			'cover'     => __( 'Cover Page', 'bgtfw' ),
			'contain'   => __( 'Scaled to Fit', 'bgtfw' ),
			'100% auto' => __( 'Full Width', 'bgtfw' ),
			'auto 100%' => __( 'Full Height', 'bgtfw' ),
			'inherit'   => __( 'Default', 'bgtfw' ),
			'auto'      => __( 'Do Not Resize', 'bgtfw' ),
		),
	),
	'boldgrid_background_color' => array(
		'type'        => 'bgtfw-palette-selector',
		'transport' => 'postMessage',
		'settings'    => 'boldgrid_background_color',
		'label' => esc_attr__( 'Color', 'bgtfw' ),
		'description' => esc_attr__( 'Choose a color from your palette to use.', 'bgtfw' ),
		'tooltip' => 'testing what a tool tip looks like',
		'section'     => 'background_image',
		'priority' => 2,
		'default'     => 'color-neutral',
		'choices'     => array(
			'colors' => $bgtfw_formatted_palette,
			'size' => $bgtfw_palette->get_palette_size( $bgtfw_formatted_palette ),
		),
		'sanitize_callback' => array( $bgtfw_color_sanitize, 'sanitize_palette_selector' ),
	),
	'bgtfw_background_overlay' => array(
		'type'        => 'switch',
		'settings'    => 'bgtfw_background_overlay',
		'transport'   => 'postMessage',
		'label'       => __( 'Image Overlay', 'bgtfw' ),
		'description' => esc_attr__( 'Add an overlay to give your text readability over an image.', 'bgtfw' ),
		'section'     => 'background_image',
		'default'     => false,
		'priority'    => 10,
		'choices'     => array(
			'on'  => esc_attr__( 'Enable', 'bgtfw' ),
			'off' => esc_attr__( 'Disable', 'bgtfw' ),
		),
	),
	'bgtfw_background_overlay_color' => array(
		'type'        => 'bgtfw-palette-selector',
		'transport'   => 'postMessage',
		'settings'    => 'bgtfw_background_overlay_color',
		'label'       => esc_attr__( 'Overlay Color', 'bgtfw' ),
		'section'     => 'background_image',
		'priority'    => 10,
		'default'     => 'color-1',
		'choices'     => array(
			'colors' => $bgtfw_formatted_palette,
			'size' => $bgtfw_palette->get_palette_size( $bgtfw_formatted_palette ),
		),
		'sanitize_callback' => array( $bgtfw_color_sanitize, 'sanitize_palette_selector' ),
	),
	'bgtfw_background_overlay_type' => array(
		'type'        => 'select',
		'transport'   => 'postMessage',
		'settings'    => 'bgtfw_background_overlay_type',
		'label'       => esc_attr__( 'Overlay Blend Mode', 'bgtfw' ),
		'section'     => 'background_image',
		'priority'    => 10,
		'default'     => 'overlay',
		'choices' => [
			'multiply' => __( 'Multiply', 'bgtfw' ),
			'screen' => __( 'Screen', 'bgtfw' ),
			'overlay' => __( 'Overlay', 'bgtfw' ),
			'darken' => __( 'Darken', 'bgtfw' ),
			'lighten' => __( 'Lighten', 'bgtfw' ),
			'color-dodge' => __( 'Color Dodge', 'bgtfw' ),
			'color-burn' => __( 'Color Burn', 'bgtfw' ),
			'hard-light' => __( 'Hard Light', 'bgtfw' ),
			'soft-light' => __( 'Soft Light', 'bgtfw' ),
			'difference' => __( 'Difference', 'bgtfw' ),
			'exclusion' => __( 'Exclusion', 'bgtfw' ),
			'hue' => __( 'Hue', 'bgtfw' ),
			'saturation' => __( 'Saturation', 'bgtfw' ),
			'color' => __( 'Color', 'bgtfw' ),
			'luminosity' => __( 'Luminosity', 'bgtfw' ),
		],
	),
	'bgtfw_background_overlay_alpha' => array(
		'type'        => 'slider',
		'transport'   => 'postMessage',
		'settings'    => 'bgtfw_background_overlay_alpha',
		'label'       => esc_attr__( 'Overlay Opacity', 'bgtfw' ),
		'section'     => 'background_image',
		'priority'    => 10,
		'default'     => '0.70',
		'choices'     => array(
			'min'  => '0',
			'max'  => '1',
			'step' => '.01',
		),
	),
);

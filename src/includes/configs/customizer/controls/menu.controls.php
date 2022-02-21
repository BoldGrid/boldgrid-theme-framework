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
	'bgtfw_menu_hamburger_main_toggle'                => array(
		'type'      => 'switch',
		'settings'  => 'bgtfw_menu_hamburger_main_toggle',
		'transport' => 'postMessage',
		'priority'  => 10,
		'label'     => esc_html__( 'Enable Hamburger Menu', 'bgtfw' ),
		'section'   => 'bgtfw_menu_hamburgers_main',
		'default'   => true,
	),
	'bgtfw_menu_hamburger_main_color'                 => array(
		'type'              => 'bgtfw-palette-selector',
		'transport'         => 'postMessage',
		'settings'          => 'bgtfw_menu_hamburger_main_color',
		'label'             => esc_attr__( 'Primary Color', 'bgtfw' ),
		'section'           => 'bgtfw_menu_hamburgers_main',
		'default'           => 'color-1',
		'priority'          => 11,
		'choices'           => array(
			'colors' => $bgtfw_formatted_palette,
			'size'   => $bgtfw_palette->get_palette_size( $bgtfw_formatted_palette ),
		),
		'sanitize_callback' => array( $bgtfw_color_sanitize, 'sanitize_palette_selector' ),
	),
	'bgtfw_menu_hamburger_display_main'               => array(
		'settings'  => 'bgtfw_menu_hamburger_display_main',
		'transport' => 'postMessage',
		'label'     => __( 'Hamburger Display', 'bgtfw' ),
		'type'      => 'multicheck',
		'default'   => array( 'ham-tablet', 'ham-phone' ),
		'section'   => 'bgtfw_menu_hamburgers_main',
		'priority'  => 12,
		'choices'   => array(
			'ham-large'   => esc_html__( 'Large', 'bgtfw' ),
			'ham-desktop' => esc_html__( 'Desktop', 'bgtfw' ),
			'ham-tablet'  => esc_html__( 'Tablet', 'bgtfw' ),
			'ham-phone'   => esc_html__( 'Phone', 'bgtfw' ),
		),
	),
	'bgtfw_menu_hamburger_main'                       => array(
		'settings'          => 'bgtfw_menu_hamburger_main',
		'transport'         => 'postMessage',
		'label'             => __( 'Hamburger Style', 'bgtfw' ),
		'type'              => 'bgtfw-menu-hamburgers',
		'default'           => 'hamburger--collapse',
		'section'           => 'bgtfw_menu_hamburgers_main',
		'sanitize_callback' => 'sanitize_html_class',
		'priority'          => 13,
	),

	/* Start: Main Menu Background Controls */
	'bgtfw_menu_background_main'                      => array(
		'type'              => 'bgtfw-palette-selector',
		'transport'         => 'postMessage',
		'settings'          => 'bgtfw_menu_background_main',
		'label'             => esc_attr__( 'Background Color', 'bgtfw' ),
		'section'           => 'bgtfw_menu_background_main',
		'priority'          => 1,
		'default'           => 'transparent',
		'choices'           => array(
			'colors'      => $bgtfw_formatted_palette,
			'size'        => $bgtfw_palette->get_palette_size( $bgtfw_formatted_palette, true ),
			'transparent' => true,
		),
		'sanitize_callback' => array( $bgtfw_color_sanitize, 'sanitize_palette_selector' ),
	),

	'bgtfw_menu_submenu_background_main'              => array(
		'type'              => 'bgtfw-palette-selector',
		'transport'         => 'postMessage',
		'settings'          => 'bgtfw_menu_submenu_background_main',
		'label'             => esc_attr__( 'Sub Menu Background Color', 'bgtfw' ),
		'section'           => 'bgtfw_menu_background_main',
		'priority'          => 2,
		'default'           => 'transparent',
		'choices'           => array(
			'colors'      => $bgtfw_formatted_palette,
			'size'        => $bgtfw_palette->get_palette_size( $bgtfw_formatted_palette, true ),
			'transparent' => true,
		),
		'sanitize_callback' => array( $bgtfw_color_sanitize, 'sanitize_palette_selector' ),
	),

	/* Start: Main Menu Spacing Controls */
	'bgtfw_menu_margin_main'                          => array(
		'type'              => 'kirki-generic',
		'transport'         => 'postMessage',
		'section'           => 'bgtfw_menu_margin_main',
		'settings'          => 'bgtfw_menu_margin_main',
		'label'             => '',
		'default'           => '',
		'sanitize_callback' => array( 'Boldgrid_Framework_Customizer_Generic', 'sanitize' ),
		'choices'           => array(
			'name'     => 'boldgrid_controls',
			'type'     => 'Margin',
			'settings' => array(
				'responsive' => Boldgrid_Framework_Customizer_Generic::$device_sizes,
				'control'    => array(
					'selectors' => array( '#main-menu' ),
					'sliders'   => array(
						array(
							'name'        => 'top',
							'label'       => 'Top',
							'cssProperty' => 'margin-top',
						),
						array(
							'name'        => 'right',
							'label'       => 'Right',
							'cssProperty' => 'margin-right',
						),
						array(
							'name'        => 'bottom',
							'label'       => 'Bottom',
							'cssProperty' => 'margin-bottom',
						),
						array(
							'name'        => 'left',
							'label'       => 'Left',
							'cssProperty' => 'margin-left',
						),
					),
				),
				'slider'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
					'em' => array(
						'min' => 0,
						'max' => 5,
					),
				),
			),
		),
	),
	'bgtfw_menu_padding_main'                         => array(
		'type'              => 'kirki-generic',
		'transport'         => 'postMessage',
		'section'           => 'bgtfw_menu_padding_main',
		'settings'          => 'bgtfw_menu_padding_main',
		'label'             => '',
		'default'           => '',
		'sanitize_callback' => array( 'Boldgrid_Framework_Customizer_Generic', 'sanitize' ),
		'choices'           => array(
			'name'     => 'boldgrid_controls',
			'type'     => 'Padding',
			'settings' => array(
				'responsive' => Boldgrid_Framework_Customizer_Generic::$device_sizes,
				'control'    => array(
					'selectors' => array( '#main-menu' ),
					'sliders'   => array(
						array(
							'name'        => 'top',
							'label'       => 'Top',
							'cssProperty' => 'padding-top',
						),
						array(
							'name'        => 'right',
							'label'       => 'Right',
							'cssProperty' => 'padding-right',
						),
						array(
							'name'        => 'bottom',
							'label'       => 'Bottom',
							'cssProperty' => 'padding-bottom',
						),
						array(
							'name'        => 'left',
							'label'       => 'Left',
							'cssProperty' => 'padding-left',
						),
					),
				),
				'slider'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
					'em' => array(
						'min' => 0,
						'max' => 5,
					),
				),
			),
		),
	),
	/* End: Main Menu Spacing Controls */

	'bgtfw_menu_visibility_main'                      => array(
		'type'              => 'kirki-generic',
		'transport'         => 'postMessage',
		'section'           => 'bgtfw_menu_visibility_main',
		'settings'          => 'bgtfw_menu_visibility_main',
		'label'             => '',
		'default'           => array(),
		'sanitize_callback' => array( 'Boldgrid_Framework_Customizer_Generic', 'sanitize' ),
		'choices'           => array(
			'name'     => 'boldgrid_controls',
			'type'     => 'DeviceVisibility',
			'settings' => array(
				'responsive' => Boldgrid_Framework_Customizer_Generic::$device_sizes,
				'control'    => array(
					'selectors' => array( '#main-menu' ),
				),
			),
		),
	),

	/* Start: Main Menu Border */
	'bgtfw_menu_border_main'                          => array(
		'type'              => 'kirki-generic',
		'transport'         => 'postMessage',
		'section'           => 'bgtfw_menu_border_main',
		'settings'          => 'bgtfw_menu_border_main',
		'label'             => '',
		'default'           => '',
		'sanitize_callback' => array( 'Boldgrid_Framework_Customizer_Generic', 'sanitize' ),
		'choices'           => array(
			'name'     => 'boldgrid_controls',
			'type'     => 'Border',
			'settings' => array(
				'responsive' => Boldgrid_Framework_Customizer_Generic::$device_sizes,
				'control'    => array(
					'selectors' => array( '#main-menu' ),
				),
			),
		),
	),
	'bgtfw_menu_border_color_main'                    => array(
		'type'              => 'bgtfw-palette-selector',
		'transport'         => 'postMessage',
		'settings'          => 'bgtfw_menu_border_color_main',
		'label'             => esc_attr__( 'Border Color', 'bgtfw' ),
		'section'           => 'bgtfw_menu_border_main',
		'default'           => 'color-3',
		'choices'           => array(
			'colors' => $bgtfw_formatted_palette,
			'size'   => $bgtfw_palette->get_palette_size( $bgtfw_formatted_palette ),
		),
		'sanitize_callback' => array( $bgtfw_color_sanitize, 'sanitize_palette_selector' ),
	),
	'bgtfw_menu_border_radius_main'                   => array(
		'type'              => 'kirki-generic',
		'transport'         => 'postMessage',
		'section'           => 'bgtfw_menu_border_main',
		'settings'          => 'bgtfw_menu_border_radius_main',
		'label'             => '',
		'default'           => '',
		'sanitize_callback' => array( 'Boldgrid_Framework_Customizer_Generic', 'sanitize' ),
		'choices'           => array(
			'name'     => 'boldgrid_controls',
			'type'     => 'BorderRadius',
			'settings' => array(
				'responsive' => Boldgrid_Framework_Customizer_Generic::$device_sizes,
				'control'    => array(
					'selectors' => array( '#main-menu' ),
				),
			),
		),
	),

	/* End: Main Menu Border */
	'bgtfw_menu_items_border_main'                    => array(
		'type'              => 'kirki-generic',
		'transport'         => 'postMessage',
		'section'           => 'bgtfw_menu_items_border_main',
		'settings'          => 'bgtfw_menu_items_border_main',
		'label'             => '',
		'default'           => '',
		'sanitize_callback' => array( 'Boldgrid_Framework_Customizer_Generic', 'sanitize' ),
		'choices'           => array(
			'name'     => 'boldgrid_controls',
			'type'     => 'Border',
			'settings' => array(
				'responsive' => Boldgrid_Framework_Customizer_Generic::$device_sizes,
				'control'    => array(
					'selectors' => array( '#main-menu > li:not(.current-menu-item)' ),
				),
			),
		),
	),
	'bgtfw_menu_items_border_color_main'              => array(
		'type'              => 'bgtfw-palette-selector',
		'transport'         => 'postMessage',
		'settings'          => 'bgtfw_menu_items_border_color_main',
		'label'             => esc_attr__( 'Primary Color', 'bgtfw' ),
		'section'           => 'bgtfw_menu_items_border_main',
		'default'           => 'color-3',
		'choices'           => array(
			'colors' => $bgtfw_formatted_palette,
			'size'   => $bgtfw_palette->get_palette_size( $bgtfw_formatted_palette ),
		),
		'sanitize_callback' => array( $bgtfw_color_sanitize, 'sanitize_palette_selector' ),
	),
	'bgtfw_menu_items_border_radius_main'             => array(
		'type'              => 'kirki-generic',
		'transport'         => 'postMessage',
		'section'           => 'bgtfw_menu_items_border_main',
		'settings'          => 'bgtfw_menu_items_border_radius_main',
		'label'             => '',
		'default'           => '',
		'sanitize_callback' => array( 'Boldgrid_Framework_Customizer_Generic', 'sanitize' ),
		'choices'           => array(
			'name'     => 'boldgrid_controls',
			'type'     => 'BorderRadius',
			'settings' => array(
				'responsive' => Boldgrid_Framework_Customizer_Generic::$device_sizes,
				'control'    => array(
					'selectors' => array( '#main-menu > li:not(.current-menu-item)' ),
				),
			),
		),
	),
	'bgtfw_menu_items_spacing_main'                   => array(
		'type'              => 'kirki-generic',
		'transport'         => 'postMessage',
		'section'           => 'bgtfw_menu_items_spacing_main',
		'settings'          => 'bgtfw_menu_items_spacing_main',
		'label'             => '',
		'default'           => '',
		'sanitize_callback' => array( 'Boldgrid_Framework_Customizer_Generic', 'sanitize' ),
		'choices'           => array(
			'name'     => 'boldgrid_controls',
			'type'     => 'Margin',
			'settings' => array(
				'responsive' => Boldgrid_Framework_Customizer_Generic::$device_sizes,
				'control'    => array(
					'selectors' => array( '#main-menu > li' ),
					'sliders'   => array(
						array(
							'name'        => 'top',
							'label'       => 'Top',
							'cssProperty' => 'margin-top',
						),
						array(
							'name'        => 'right',
							'label'       => 'Right',
							'cssProperty' => 'margin-right',
						),
						array(
							'name'        => 'bottom',
							'label'       => 'Bottom',
							'cssProperty' => 'margin-bottom',
						),
						array(
							'name'        => 'left',
							'label'       => 'Left',
							'cssProperty' => 'margin-left',
						),
					),
				),
			),
		),
	),
	'bgtfw_menu_items_hover_color_main'               => array(
		'type'              => 'bgtfw-palette-selector',
		'transport'         => 'postMessage',
		'settings'          => 'bgtfw_menu_items_hover_color_main',
		'label'             => esc_attr__( 'Primary Color', 'bgtfw' ),
		'section'           => 'bgtfw_menu_items_hover_item_main',
		'default'           => 'color-4',
		'choices'           => array(
			'colors' => $bgtfw_formatted_palette,
			'size'   => $bgtfw_palette->get_palette_size( $bgtfw_formatted_palette ),
		),
		'sanitize_callback' => array( $bgtfw_color_sanitize, 'sanitize_palette_selector' ),
		'active_callback'   => array(
			array(
				'setting'  => 'bgtfw_menu_items_hover_effect_main',
				'operator' => '!==',
				'value'    => '',
			),
		),
	),
	'bgtfw_menu_items_hover_link_color_main'          => array(
		'type'              => 'bgtfw-palette-selector',
		'transport'         => 'postMessage',
		'settings'          => 'bgtfw_menu_items_hover_link_color_main',
		'label'             => esc_attr__( 'Link Hover Color', 'bgtfw' ),
		'section'           => 'bgtfw_menu_items_hover_item_main',
		'default'           => 'transparent',
		'choices'           => array(
			'colors'      => $bgtfw_formatted_palette,
			'size'        => $bgtfw_palette->get_palette_size( $bgtfw_formatted_palette ),
			'transparent' => true,
		),
		'sanitize_callback' => array( $bgtfw_color_sanitize, 'sanitize_palette_selector' ),
		'active_callback'   => array(
			array(
				'setting'  => 'bgtfw_menu_items_hover_effect_main',
				'operator' => '===',
				'value'    => '',
			),
		),
	),
	'bgtfw_menu_items_hover_background_main'          => array(
		'type'              => 'bgtfw-palette-selector',
		'transport'         => 'postMessage',
		'settings'          => 'bgtfw_menu_items_hover_background_main',
		'label'             => esc_attr__( 'Secondary Color', 'bgtfw' ),
		'section'           => 'bgtfw_menu_items_hover_item_main',
		'default'           => 'color-3',
		'choices'           => array(
			'colors' => $bgtfw_formatted_palette,
			'size'   => $bgtfw_palette->get_palette_size( $bgtfw_formatted_palette ),
		),
		'sanitize_callback' => array( $bgtfw_color_sanitize, 'sanitize_palette_selector' ),
	),
	'bgtfw_menu_items_hover_effect_main'              => array(
		'type'              => 'select',
		'transport'         => 'postMessage',
		'settings'          => 'bgtfw_menu_items_hover_effect_main',
		'label'             => esc_attr__( 'Hover Effect', 'bgtfw' ),
		'section'           => 'bgtfw_menu_items_hover_item_main',
		'default'           => 'hvr-underline-reveal',
		'sanitize_callback' => 'esc_attr',
		'choices'           => array(

			/** No Effects */
			''          => esc_attr__( 'Link Text Color Change', 'bgtfw' ),

			/** Background Transitions */
			'optgroup1' => array(
				esc_attr__( 'Single Color Transitions', 'bgtfw' ),
				array(
					/**
					 * Currently this pulses to default color in RGBA. Color doesn't look
					 * like it gets extracted out since it's happening in a transition.
					 *
					 * Disabling this for now.
					 *
					 * 'hvr-back-pulse' => esc_attr__( 'Back Pulse', 'bgtfw' ),
					 */
					'hvr-fade'           => esc_attr__( 'Fade', 'bgtfw' ),
					'hvr-sweep-to-right' => esc_attr__( 'Sweep to Right', 'bgtfw' ),
					'hvr-sweep-to-left'  => esc_attr__( 'Sweep to Left', 'bgtfw' ),
				),
			),

			/** Two Color Background Transitions */
			'optgroup2' => array(
				esc_attr__( 'Two Color Transitions', 'bgtfw' ),
				array(
					'hvr-rectangle-in'           => esc_attr__( 'Rectangle In', 'bgtfw' ),
					'hvr-rectangle-out'          => esc_attr__( 'Rectangle Out', 'bgtfw' ),
					'hvr-shutter-in-horizontal'  => esc_attr__( 'Shutter In Horizontal', 'bgtfw' ),
					'hvr-shutter-out-horizontal' => esc_attr__( 'Shutter Out Horizontal', 'bgtfw' ),
				),
			),

			/** Border Effects */
			'optgroup3' => array(
				esc_attr__( 'Border Effects', 'bgtfw' ),
				array(
					'hvr-trim'        => esc_attr__( 'Trim', 'bgtfw' ),
					'hvr-ripple-in'   => esc_attr__( 'Ripple In', 'bgtfw' ),
					'hvr-outline-out' => esc_attr__( 'Outline Out', 'bgtfw' ),
				),
			),
			'optgroup4' => array(
				esc_attr__( 'Overline/Underline Effects', 'bgtfw' ),
				array(
					'hvr-underline-from-center' => esc_attr__( 'Underline From Center', 'bgtfw' ),
					'hvr-underline-reveal'      => esc_attr__( 'Underline Reveal', 'bgtfw' ),
					'hvr-overline-reveal'       => esc_attr__( 'Overline Reveal', 'bgtfw' ),
					'hvr-overline-from-center'  => esc_attr__( 'Overline From Center', 'bgtfw' ),
				),
			),
		),
	),

	'bgtfw_menu_items_link_color_main'                => array(
		'type'              => 'bgtfw-palette-selector',
		'transport'         => 'postMessage',
		'settings'          => 'bgtfw_menu_items_link_color_main',
		'label'             => esc_attr__( 'Link Color', 'bgtfw' ),
		'section'           => 'bgtfw_menu_items_link_color_main',
		'priority'          => 1,
		'default'           => 'color-1',
		'choices'           => array(
			'colors' => $bgtfw_formatted_palette,
			'size'   => $bgtfw_palette->get_palette_size( $bgtfw_formatted_palette ),
		),
		'sanitize_callback' => array( $bgtfw_color_sanitize, 'sanitize_palette_selector' ),
	),

	'bgtfw_menu_items_sub_link_color_main'            => array(
		'type'              => 'bgtfw-palette-selector',
		'transport'         => 'postMessage',
		'settings'          => 'bgtfw_menu_items_sub_link_color_main',
		'label'             => esc_attr__( 'Sub Menu Link Color', 'bgtfw' ),
		'section'           => 'bgtfw_menu_items_link_color_main',
		'priority'          => 2,
		'default'           => get_theme_mod( 'bgtfw_menu_items_link_color_main', 'color-1' ),
		'choices'           => array(
			'colors' => $bgtfw_formatted_palette,
			'size'   => $bgtfw_palette->get_palette_size( $bgtfw_formatted_palette ),
		),
		'sanitize_callback' => array( $bgtfw_color_sanitize, 'sanitize_palette_selector' ),
	),

	'bgtfw_menu_items_active_link_color_main'         => array(
		'type'              => 'bgtfw-palette-selector',
		'transport'         => 'postMessage',
		'settings'          => 'bgtfw_menu_items_active_link_color_main',
		'label'             => esc_attr__( 'Color', 'bgtfw' ),
		'section'           => 'bgtfw_menu_items_active_link_color_main',
		'priority'          => 1,
		'default'           => 'color-4',
		'choices'           => array(
			'colors' => $bgtfw_formatted_palette,
			'size'   => $bgtfw_palette->get_palette_size( $bgtfw_formatted_palette ),
		),
		'sanitize_callback' => array( $bgtfw_color_sanitize, 'sanitize_palette_selector' ),
	),

	'bgtfw_menu_items_sub_active_link_color_main'     => array(
		'type'              => 'bgtfw-palette-selector',
		'transport'         => 'postMessage',
		'settings'          => 'bgtfw_menu_items_sub_active_link_color_main',
		'label'             => esc_attr__( 'Sub Menu Color', 'bgtfw' ),
		'section'           => 'bgtfw_menu_items_active_link_color_main',
		'priority'          => 2,
		'default'           => get_theme_mod( 'bgtfw_menu_items_active_link_color_main', 'color-4' ),
		'choices'           => array(
			'colors' => $bgtfw_formatted_palette,
			'size'   => $bgtfw_palette->get_palette_size( $bgtfw_formatted_palette ),
		),
		'sanitize_callback' => array( $bgtfw_color_sanitize, 'sanitize_palette_selector' ),
	),

	'bgtfw_menu_items_active_link_background_main'    => array(
		'type'              => 'bgtfw-palette-selector',
		'transport'         => 'postMessage',
		'settings'          => 'bgtfw_menu_items_active_link_background_main',
		'label'             => esc_attr__( 'Color', 'bgtfw' ),
		'section'           => 'bgtfw_menu_items_active_link_background_main',
		'priority'          => 1,
		'default'           => 'transparent',
		'choices'           => array(
			'colors'      => $bgtfw_formatted_palette,
			'size'        => $bgtfw_palette->get_palette_size( $bgtfw_formatted_palette, true ),
			'transparent' => true,
		),
		'sanitize_callback' => array( $bgtfw_color_sanitize, 'sanitize_palette_selector' ),
	),

	'bgtfw_menu_items_active_link_border_main'        => array(
		'type'              => 'kirki-generic',
		'transport'         => 'postMessage',
		'section'           => 'bgtfw_menu_items_active_link_border_main',
		'settings'          => 'bgtfw_menu_items_active_link_border_main',
		'label'             => '',
		'default'           => '',
		'sanitize_callback' => array( 'Boldgrid_Framework_Customizer_Generic', 'sanitize' ),
		'choices'           => array(
			'name'     => 'boldgrid_controls',
			'type'     => 'Border',
			'settings' => array(
				'responsive' => Boldgrid_Framework_Customizer_Generic::$device_sizes,
				'control'    => array(
					'selectors' => array( '#main-menu > li.current-menu-item' ),
				),
			),
		),
	),
	'bgtfw_menu_items_active_link_border_color_main'  => array(
		'type'              => 'bgtfw-palette-selector',
		'transport'         => 'postMessage',
		'settings'          => 'bgtfw_menu_items_active_link_border_color_main',
		'label'             => esc_attr__( 'Primary Color', 'bgtfw' ),
		'section'           => 'bgtfw_menu_items_active_link_border_main',
		'default'           => 'color-3',
		'choices'           => array(
			'colors' => $bgtfw_formatted_palette,
			'size'   => $bgtfw_palette->get_palette_size( $bgtfw_formatted_palette ),
		),
		'sanitize_callback' => array( $bgtfw_color_sanitize, 'sanitize_palette_selector' ),
	),
	'bgtfw_menu_items_active_link_border_radius_main' => array(
		'type'              => 'kirki-generic',
		'transport'         => 'postMessage',
		'section'           => 'bgtfw_menu_items_active_link_border_main',
		'settings'          => 'bgtfw_menu_items_active_link_border_radius_main',
		'label'             => '',
		'default'           => '',
		'sanitize_callback' => array( 'Boldgrid_Framework_Customizer_Generic', 'sanitize' ),
		'choices'           => array(
			'name'     => 'boldgrid_controls',
			'type'     => 'BorderRadius',
			'settings' => array(
				'responsive' => Boldgrid_Framework_Customizer_Generic::$device_sizes,
				'control'    => array(
					'selectors' => array( '#main-menu > li.current-menu-item' ),
				),
			),
		),
	),

	/** Menu Typography */
	'bgtfw_menu_typography_main'                      => array(
		'type'      => 'typography',
		'transport' => 'auto',
		'settings'  => 'bgtfw_menu_typography_main',
		'label'     => esc_attr__( 'Typography', 'bgtfw' ),
		'section'   => 'bgtfw_menu_typography_main',
		'default'   => array(
			'font-family'    => 'Roboto',
			'variant'        => 'regular',
			'font-size'      => '18px',
			'line-height'    => '1.5',
			'letter-spacing' => '0',
			'subsets'        => array( 'latin-ext' ),
			'text-transform' => 'uppercase',
		),
		'priority'  => 20,
		'output'    => $bgtfw_typography->get_typography_output(
			$bgtfw_configs,
			'#main-menu li a, .mce-content-body .sm-clean'
		),
	),
);

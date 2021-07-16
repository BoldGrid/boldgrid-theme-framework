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
	'bgtfw_footer_layout' => [
		'settings' => 'bgtfw_footer_layout',
		'transport' => 'auto',
		'label' => __( 'Footer Layout', 'bgtfw' ),
		'type' => 'bgtfw-sortable-accordion',
		'default' => [
			[
				'container' => 'container',
				'items' => [
					[
						'type' => 'boldgrid_display_attribution_links',
						'key' => 'attribution',
						'align' => 'w',
					],
					[
						'type' => 'boldgrid_menu_social',
						'key' => 'menu',
						'align' => 'e',
					],
				],
			],
		],
		'items' => [
			'menu' => [
				'icon' => 'dashicons dashicons-menu',
				'title' => __( 'Menu', 'bgtfw' ),
				'controls' => [
					'menu-select' => [],
					'align' => [
						'default' => 'nw',
					],
				],
			],
			'branding' => [
				'icon' => 'dashicons dashicons-store',
				'title' => __( 'Branding', 'bgtfw' ),
				'controls' => [
					'align' => [
						'default' => 'nw',
					],
					'display' => [
						'default' => [
							[
								'selector' => '.custom-logo-link',
								'display' => 'show',
								'title' => __( 'Logo', 'bgtfw' ),
							],
							[
								'selector' => '.site-title',
								'display' => 'show',
								'title' => __( 'Title', 'bgtfw' ),
							],
							[
								'selector' => '.site-description',
								'display' => 'show',
								'title' => __( 'Tagline', 'bgtfw' ),
							],
						],
					],
				],
			],
			'sidebar' => [
				'icon' => 'dashicons dashicons-layout',
				'title' => __( 'Widget Area', 'bgtfw' ),
				'controls' => [
					'sidebar-edit' => [],
				],
			],
			'attribution' => [
				'icon' => 'dashicons dashicons-admin-links',
				'title' => __( 'Attribution Links', 'bgtfw' ),
				'controls' => [
					'attribution' => [],
					'align' => [
						'default' => 'w',
					],
				],
			],
		],
		'location' => 'footer',
		'section' => 'boldgrid_footer_panel',
		'partial_refresh' => [
			'bgtfw_footer_layout' => [
				'selector' => '.bgtfw-footer',
				'render_callback' => [ 'BoldGrid', 'dynamic_footer' ],
			],
		],
	],
	'bgtfw_footer_color' => array(
		'type'        => 'bgtfw-palette-selector',
		'transport' => 'postMessage',
		'settings'    => 'bgtfw_footer_color',
		'label' => esc_attr__( 'Background Color', 'bgtfw' ),
		'description' => esc_attr__( 'Choose a color from your palette to use.', 'bgtfw' ),
		'section'     => 'bgtfw_footer_colors',
		'priority' => 10,
		'default'     => '',
		'choices'     => array(
			'colors' => $bgtfw_formatted_palette,
			'size' => $bgtfw_palette->get_palette_size( $bgtfw_formatted_palette ),
		),
		'sanitize_callback' => array( $bgtfw_color_sanitize, 'sanitize_palette_selector' ),
		'edit_vars' => array(
			array(
				'selector'    => 'footer.site-footer',
				'label'       => esc_attr__( 'Footer Colors', 'bgtfw' ),
				'description' => esc_attr__( 'Change the color of the footer background and footer links', 'bgtfw' ),
			),
		),
	),
	'bgtfw_footer_links' => array(
		'type' => 'bgtfw-palette-selector',
		'transport' => 'postMessage',
		'settings' => 'bgtfw_footer_links',
		'label' => esc_attr__( 'Link Color', 'bgtfw' ),
		'section' => 'bgtfw_footer_colors',
		'priority' => 30,
		'default' => '',
		'choices' => array(
			'colors' => $bgtfw_formatted_palette,
			'size' => $bgtfw_palette->get_palette_size( $bgtfw_formatted_palette ),
		),
		'sanitize_callback' => array( $bgtfw_color_sanitize, 'sanitize_palette_selector' ),
	),
);

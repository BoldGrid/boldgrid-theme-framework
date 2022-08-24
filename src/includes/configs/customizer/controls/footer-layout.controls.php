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
	'bgtfw_footer_layout' => array(
		'settings'        => 'bgtfw_footer_layout',
		'transport'       => 'auto',
		'label'           => __( 'Footer Layout', 'bgtfw' ),
		'type'            => 'bgtfw-sortable-accordion',
		'default'         => array(
			array(
				'container' => 'container',
				'items'     => array(
					array(
						'type'  => 'boldgrid_display_attribution_links',
						'key'   => 'attribution',
						'align' => 'w',
					),
					array(
						'type'  => 'boldgrid_menu_social',
						'key'   => 'menu',
						'align' => 'e',
					),
				),
			),
		),
		'items'           => array(
			'menu'        => array(
				'icon'     => 'dashicons dashicons-menu',
				'title'    => __( 'Menu', 'bgtfw' ),
				'controls' => array(
					'menu-select' => array(),
					'align'       => array(
						'default' => 'nw',
					),
				),
			),
			'branding'    => array(
				'icon'     => 'dashicons dashicons-store',
				'title'    => __( 'Branding', 'bgtfw' ),
				'controls' => array(
					'align'   => array(
						'default' => 'nw',
					),
					'display' => array(
						'default' => array(
							array(
								'selector' => '.custom-logo-link',
								'display'  => 'show',
								'title'    => __( 'Logo', 'bgtfw' ),
							),
							array(
								'selector' => '.site-title',
								'display'  => 'show',
								'title'    => __( 'Title', 'bgtfw' ),
							),
							array(
								'selector' => '.site-description',
								'display'  => 'show',
								'title'    => __( 'Tagline', 'bgtfw' ),
							),
						),
					),
				),
			),
			'sidebar'     => array(
				'icon'     => 'dashicons dashicons-layout',
				'title'    => __( 'Widget Area', 'bgtfw' ),
				'controls' => array(
					'sidebar-edit' => array(),
				),
			),
			'attribution' => array(
				'icon'     => 'dashicons dashicons-admin-links',
				'title'    => __( 'Attribution Links', 'bgtfw' ),
				'controls' => array(
					'attribution' => array(),
					'align'       => array(
						'default' => 'w',
					),
				),
			),
		),
		'location'        => 'footer',
		'section'         => 'boldgrid_footer_panel',
		'partial_refresh' => array(
			'bgtfw_footer_layout' => array(
				'selector'        => '.bgtfw-footer',
				'render_callback' => array( 'BoldGrid', 'dynamic_footer' ),
			),
		),
		'edit_vars'   => array(
			array(
				'selector'    => 'footer.site-footer',
				'label'       => __( 'Footer Layout', 'bgtfw' ),
				'description' => __( 'Customize the layout of your site\'s footer', 'bgtfw' ),
			),
		),
	),
	'bgtfw_footer_color'  => array(
		'type'              => 'bgtfw-palette-selector',
		'transport'         => 'postMessage',
		'settings'          => 'bgtfw_footer_color',
		'label'             => esc_attr__( 'Background Color', 'bgtfw' ),
		'description'       => esc_attr__( 'Choose a color from your palette to use.', 'bgtfw' ),
		'section'           => 'bgtfw_footer_colors',
		'priority'          => 10,
		'default'           => '',
		'choices'           => array(
			'colors' => $bgtfw_formatted_palette,
			'size'   => $bgtfw_palette->get_palette_size( $bgtfw_formatted_palette ),
		),
		'sanitize_callback' => array( $bgtfw_color_sanitize, 'sanitize_palette_selector' ),
		'edit_vars'         => array(
			array(
				'selector'    => 'footer.site-footer',
				'label'       => esc_attr__( 'Footer Colors', 'bgtfw' ),
				'description' => esc_attr__( 'Change the color of the footer background and footer links', 'bgtfw' ),
			),
		),
	),
	'bgtfw_footer_links'  => array(
		'type'              => 'bgtfw-palette-selector',
		'transport'         => 'postMessage',
		'settings'          => 'bgtfw_footer_links',
		'label'             => esc_attr__( 'Link Color', 'bgtfw' ),
		'section'           => 'bgtfw_footer_colors',
		'priority'          => 30,
		'default'           => '',
		'choices'           => array(
			'colors' => $bgtfw_formatted_palette,
			'size'   => $bgtfw_palette->get_palette_size( $bgtfw_formatted_palette ),
		),
		'sanitize_callback' => array( $bgtfw_color_sanitize, 'sanitize_palette_selector' ),
	),
);

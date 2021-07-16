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
	'bgtfw_woocommerce_container' => array(
		'settings' => 'bgtfw_woocommerce_container',
		'transport'   => 'postMessage',
		'label'       => esc_html__( 'Container', 'bgtfw' ),
		'type'        => 'radio-buttonset',
		'priority'    => 35,
		'default'   => 'container',
		'choices'     => array(
			'container' => '<span class="icon-layout-container"></span>' . esc_attr__( 'Contained', 'bgtfw' ),
			'' => '<span class="icon-layout-full-screen"></span>' . esc_attr__( 'Full Width', 'bgtfw' ),
		),
		'section' => 'bgtfw_layout_woocommerce_container',
		'sanitize_callback' => function( $value, $settings ) {
			return 'container' === $value || 'full-width' === $value ? $value : '';
		},
		'js_vars' => array(
			array(
				'element' => '.woocommerce .site-content, .woocommerce-page .site-content',
				'function' => 'html',
				'attr' => 'class',
				'value_pattern' => 'site-content $',
			),
			array(
				'element' => '.woocommerce .main-wrapper, .woocommerce-page .main-wrapper',
				'function' => 'html',
				'attr' => 'class',
				'value_pattern' => 'main-wrapper $',
			),
			array(
				'element' => '.woocommerce-page .main > .container, .woocommerce-page .main > .full-width',
				'function' => 'html',
				'attr' => 'class',
				'value_pattern' => '$',
			),
		),
		'edit_vars' => array(
			array(
				'selector'    => '.woocommerce .site-content',
				'label'       => __( 'WooCommerce Page Layout', 'bgtfw' ),
				'description' => __( 'Choose between contained or full-width page layout for WooCommerce pages', 'bgtfw' ),
			),
		),
	),
	'bgtfw_woocommerce_products_per_page' => array(
		'type'              => 'kirki-generic',
		'settings'          => 'bgtfw_woocommerce_products_per_page',
		'label'             => __( 'Products Per Page', 'bgtfw' ),
		'description'       => __( 'How many products should be shown per page?', 'bgtfw' ),
		'section'           => 'woocommerce_product_catalog',
		'default'           => 10,
		'priority'          => 10,
		'sanitize_callback' => 'esc_attr',
		'choices'           => array(
			'type' => 'number',
		),
	),
);

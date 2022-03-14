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

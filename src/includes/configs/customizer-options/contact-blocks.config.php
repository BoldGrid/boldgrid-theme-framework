<?php
/**
 * Contact Blocks Configuration.
 *
 * @package Boldgrid_Theme_Framework
 * @subpackage Boldgrid_Theme_Framework\Configs
 *
 * @since    1.3.5
 *
 * @return   array   An array of contact block configs.
 */
return array(
	'enabled'  => false,
	'defaults' => array(
		array(
			'contact_block' => '© ' . date( 'Y' ) . ' ' . get_bloginfo( 'name' ),
		),
		array(
			'contact_block' => esc_attr( '202 Grid Blvd. Agloe, NY 12776' ),
		),
		array(
			'contact_block' => esc_attr( '777-765-4321' ),
		),
		array(
			'contact_block' => esc_attr( 'info@example.com' ),
		),
	),
);

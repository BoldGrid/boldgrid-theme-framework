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
	'custom_theme_js' => array(
		'type'        => 'code',
		'settings'    => 'custom_theme_js',
		'label'       => __( 'JS code', 'bgtfw' ),
		'help'        => __( 'This adds live JavaScript to your website.', 'bgtfw' ),
		'description' => __( 'Add custom javascript for this theme.', 'bgtfw' ),
		'section'     => 'custom_css',
		'default'     => "// jQuery('body');",
		'priority'    => 10,
		'choices'     => array(
			'language' => 'javascript',
			'theme'    => 'base16-dark',
			'height'   => 100,
		),
	),
	'bgtfw_scroll_to_top_display' => array(
		'type' => 'radio-buttonset',
		'transport' => 'postMessage',
		'settings' => 'bgtfw_scroll_to_top_display',
		'label' => esc_attr__( 'Display', 'bgtfw' ),
		'tooltip' => __( 'Toggle the display of the scroll to top button on your site.', 'bgtfw' ),
		'section' => 'bgtfw_scroll_to_top',
		'default' => 'show',
		'choices' => array(
			'show' => '<span class="dashicons dashicons-visibility"></span>' . __( 'Show', 'bgtfw' ),
			'hide' => '<span class="dashicons dashicons-hidden"></span>' . __( 'Hide', 'bgtfw' ),
		),
		'sanitize_callback' => function( $value, $settings ) {
			return in_array( $value, [ 'show', 'hide' ], true ) ? $value : $settings->default;
		},
		'edit_vars' => array(
			array(
				'selector'    => '.goup-container',
				'label'       => esc_attr__( 'Scroll To Top', 'bgtfw' ),
				'description' => esc_attr__( 'Enable / Disable the scroll to top button', 'bgtfw' ),
			),
		),
	),
	'boldgrid_contact_details_setting' => array(
		'type'        => 'repeater',
		'label'       => esc_attr__( 'Contact Details', 'bgtfw' ),
		'section'     => 'boldgrid_footer_panel',
		'priority'    => 10,
		'row_label' => array(
			'field' => 'contact_block',
			'type' => 'field',
			'value' => esc_attr__( 'Contact Block', 'bgtfw' ),
		),
		'settings'    => 'boldgrid_contact_details_setting',
		'default'     => array(
			array(
				'contact_block' => '&copy; ' . date( 'Y' ) . ' ' . get_bloginfo( 'name' ),
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
		'fields' => array(
			'contact_block' => array(
				'type'        => 'text',
				'label'       => esc_attr__( 'Text', 'bgtfw' ),
				'description' => esc_attr__( 'Enter the text to display in your contact details', 'bgtfw' ),
				'default'     => '',
			),
		),
	),
);
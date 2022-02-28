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
	'bgtfw_blog_page_container_max_width'  => array(
		'type'              => 'kirki-generic',
		'transport'         => 'postMessage',
		'section'           => 'bgtfw_pages_blog_blog_page_post_content',
		'settings'          => 'bgtfw_blog_page_container_max_width',
		'label'             => 'Container Max Width',
		'default'           => $bgtfw_generic->get_width_defaults( 'max-width' ),
		'priority'          => 37,
		'sanitize_callback' => array( 'Boldgrid_Framework_Customizer_Generic', 'sanitize' ),
		'activate_callback' => array(
			function() {
				return false;
			},
		),
		'choices'           => array(
			'name'     => 'boldgrid_controls',
			'type'     => 'ContainerWidth',
			'settings' => array(
				'responsive' => array(
					'tablet'  => 991,
					'desktop' => 1199,
				),
				'control'    => array(
					'selectors' => array(
						'body.blog .container-fluid',
						'body.archive .container-fluid',
						'body.blog .boldgrid-section > .full-width',
						'body.archive .boldgrid-section > .full-width',
					),
					'title'     => 'Container Max Width',
					'name'      => 'bgtfw_blog_page_container_max_width',
					'sliders'   => array(
						array(
							'name'        => 'maxWidth',
							'label'       => '',
							'cssProperty' => 'max-width',
						),
					),
					'units'     => array(
						'default' => '%',
						'enabled' => array( 'px', '%' ),
					),
				),
			),
		),
	),
	'bgtfw_blog_posts_container_max_width' => array(
		'type'              => 'kirki-generic',
		'transport'         => 'postMessage',
		'section'           => 'bgtfw_pages_blog_posts_container',
		'settings'          => 'bgtfw_blog_posts_container_max_width',
		'label'             => 'Container Max Width',
		'default'           => $bgtfw_generic->get_width_defaults( 'max-width' ),
		'priority'          => 40,
		'sanitize_callback' => array( 'Boldgrid_Framework_Customizer_Generic', 'sanitize' ),
		'activate_callback' => array(
			function() {
				return false;
			},
		),
		'choices'           => array(
			'name'     => 'boldgrid_controls',
			'type'     => 'ContainerWidth',
			'settings' => array(
				'responsive' => array(
					'tablet'  => 991,
					'desktop' => 1199,
				),
				'control'    => array(
					'selectors' => array( 'body.single .container-fluid', 'body.single .boldgrid-section > .full-width' ),
					'title'     => 'Container Max Width',
					'name'      => 'bgtfw_blog_posts_container_max_width',
					'sliders'   => array(
						array(
							'name'        => 'maxWidth',
							'label'       => '',
							'cssProperty' => 'max-width',
						),
					),
					'units'     => array(
						'default' => '%',
						'enabled' => array( 'px', '%' ),
					),
				),
			),
		),
	),
	'bgtfw_pages_container_max_width'      => array(
		'type'              => 'kirki-generic',
		'transport'         => 'postMessage',
		'section'           => 'bgtfw_layout_page_container',
		'settings'          => 'bgtfw_pages_container_max_width',
		'label'             => 'Container Max Width',
		'default'           => $bgtfw_generic->get_width_defaults( 'max-width' ),
		'sanitize_callback' => array( 'Boldgrid_Framework_Customizer_Generic', 'sanitize' ),
		'activate_callback' => array(
			function() {
				return false;
			},
		),
		'choices'           => array(
			'name'     => 'boldgrid_controls',
			'type'     => 'ContainerWidth',
			'settings' => array(
				'responsive' => array(
					'tablet'  => 991,
					'desktop' => 1199,
				),
				'control'    => array(
					'selectors' => array( 'body.page .container-fluid', 'body.page .boldgrid-section > .full-width' ),
					'title'     => 'Container Max Width',
					'name'      => 'bgtfw_pages_container_max_width',
					'sliders'   => array(
						array(
							'name'        => 'maxWidth',
							'label'       => '',
							'cssProperty' => 'max-width',
						),
					),
					'units'     => array(
						'default' => '%',
						'enabled' => array( 'px', '%' ),
					),
				),
			),
		),
	),
);

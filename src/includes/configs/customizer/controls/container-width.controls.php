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
	'bgtfw_pages_container'                => array(
		'settings'          => 'bgtfw_pages_container',
		'transport'         => 'refresh',
		'label'             => esc_html__( 'Container', 'bgtfw' ),
		'type'              => 'radio-buttonset',
		'priority'          => 35,
		'default'           => 'container',
		'choices'           => array(
			'container'      => '<span class="icon-layout-container"></span>' . esc_attr__( 'Contained', 'bgtfw' ),
			''               => '<span class="icon-layout-full-screen"></span>' . esc_attr__( 'Full Width', 'bgtfw' ),
			'max-full-width' => '<span class="icon-layout-max-full-width"></span>' . esc_attr__( 'Max Full Width', 'bgtfw' ),
		),
		'section'           => 'bgtfw_layout_page_container',
		'sanitize_callback' => function( $value, $settings ) {
			$sanitized_value = $settings->default;
			switch ( $value ) {
				case '':
					$sanitized_value = '';
					break;
				case 'container':
					$sanitized_value = 'container';
					break;
				case 'max-full-width':
					$sanitized_value = 'max-full-width';
					break;
				default:
					$sanitized_value =  $settings->default;
					break;
			}
			error_log( 'sanitized_value: ' . json_encode( $sanitized_value ) );
			return $sanitized_value;
		},
		'js_vars'           => array(
			array(
				'element'       => '.page .site-content',
				'function'      => 'html',
				'attr'          => 'class',
				'value_pattern' => 'site-content $',
			),
			array(
				'element'       => 'body.page',
				'function'      => 'html',
				'attr'          => 'data-container',
				'value_pattern' => '$',
			),
		),
		'edit_vars'         => array(
			array(
				'selector'    => '.page .site-content',
				'label'       => __( 'Page Layout', 'bgtfw' ),
				'description' => __( 'Choose between contained or full-width page layout', 'bgtfw' ),
			),
		),
	),
	'bgtfw_blog_page_container'            => array(
		'settings'          => 'bgtfw_blog_page_container',
		'transport'         => 'refresh',
		'label'             => esc_html__( 'Container', 'bgtfw' ),
		'type'              => 'radio-buttonset',
		'priority'          => 35,
		'default'           => 'container',
		'choices'           => array(
			'container' => '<span class="icon-layout-container"></span>' . esc_attr__( 'Contained', 'bgtfw' ),
			''          => '<span class="icon-layout-full-screen"></span>' . esc_attr__( 'Full Width', 'bgtfw' ),
		),
		'section'           => 'bgtfw_pages_blog_blog_page_post_content',
		'sanitize_callback' => function( $value, $settings ) {
			return 'container' === $value || '' === $value ? $value : $settings->default;
		},
	),
	'bgtfw_blog_posts_container'           => array(
		'settings'          => 'bgtfw_blog_posts_container',
		'transport'         => 'refresh',
		'label'             => esc_html__( 'Container', 'bgtfw' ),
		'tooltip'           => __( 'Choose if you would like your content wrapped in a container or cover the full width of the page.', 'bgtfw' ),
		'type'              => 'radio-buttonset',
		'priority'          => 40,
		'default'           => 'container',
		'choices'           => array(
			'container' => '<span class="icon-layout-container"></span>' . esc_attr__( 'Contained', 'bgtfw' ),
			''          => '<span class="icon-layout-full-screen"></span>' . esc_attr__( 'Full Width', 'bgtfw' ),
		),
		'section'           => 'bgtfw_pages_blog_posts_container',
		'sanitize_callback' => function( $value, $settings ) {
			return 'container' === $value || '' === $value ? $value : $settings->default;
		},
		'js_vars'           => array(
			array(
				'element'       => '.single-post .main-wrapper',
				'function'      => 'html',
				'attr'          => 'class',
				'value_pattern' => 'main-wrapper $',
			),
		),
	),
	'bgtfw_woocommerce_container'          => array(
		'settings'          => 'bgtfw_woocommerce_container',
		'transport'         => 'postMessage',
		'label'             => esc_html__( 'Container', 'bgtfw' ),
		'type'              => 'radio-buttonset',
		'priority'          => 35,
		'default'           => 'container',
		'choices'           => array(
			'container' => '<span class="icon-layout-container"></span>' . esc_attr__( 'Contained', 'bgtfw' ),
			''          => '<span class="icon-layout-full-screen"></span>' . esc_attr__( 'Full Width', 'bgtfw' ),
		),
		'section'           => 'bgtfw_layout_woocommerce_container',
		'sanitize_callback' => function( $value, $settings ) {
			return 'container' === $value || 'full-width' === $value ? $value : '';
		},
		'js_vars'           => array(
			array(
				'element'       => '.woocommerce .site-content, .woocommerce-page .site-content',
				'function'      => 'html',
				'attr'          => 'class',
				'value_pattern' => 'site-content $',
			),
			array(
				'element'       => '.woocommerce .main-wrapper, .woocommerce-page .main-wrapper',
				'function'      => 'html',
				'attr'          => 'class',
				'value_pattern' => 'main-wrapper $',
			),
			array(
				'element'       => '.woocommerce-page .main > .container, .woocommerce-page .main > .full-width',
				'function'      => 'html',
				'attr'          => 'class',
				'value_pattern' => '$',
			),
		),
		'edit_vars'         => array(
			array(
				'selector'    => '.woocommerce .site-content',
				'label'       => __( 'WooCommerce Page Layout', 'bgtfw' ),
				'description' => __( 'Choose between contained or full-width page layout for WooCommerce pages', 'bgtfw' ),
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
		'priority'          => 36,
		'sanitize_callback' => array( 'Boldgrid_Framework_Customizer_Generic', 'sanitize' ),
		'active_callback'   => array(
			array(
				'setting'  => 'bgtfw_pages_container',
				'operator' => '===',
				'value'    => 'max-full-width',
			),
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
);

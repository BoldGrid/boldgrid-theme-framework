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
	'bgtfw_pages_container'           => array(
		'settings'          => 'bgtfw_pages_container',
		'transport'         => 'refresh',
		'label'             => esc_html__( 'Container', 'bgtfw' ),
		'type'              => 'radio-buttonset',
		'priority'          => 35,
		'default'           => 'container',
		'choices'           => array(
			'container' => '<span class="icon-layout-container"></span>' . esc_attr__( 'Contained', 'bgtfw' ),
			''          => '<span class="icon-layout-full-screen"></span>' . esc_attr__( 'Full Width', 'bgtfw' ),
		),
		'section'           => 'bgtfw_layout_page_container',
		'sanitize_callback' => function( $value, $settings ) {
			return 'container' === $value || '' === $value ? $value : $settings->default;
		},
		'js_vars'           => array(
			array(
				'element'       => '.page .site-content',
				'function'      => 'html',
				'attr'          => 'class',
				'value_pattern' => 'site-content $',
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
	'bgtfw_pages_container_width'     => array(
		'type'              => 'kirki-generic',
		'transport'         => 'postMessage',
		'section'           => 'bgtfw_layout_page_container',
		'settings'          => 'bgtfw_pages_container_width',
		'label'             => '',
		'default'           => $bgtfw_generic->get_width_defaults( 'width' ),
		'sanitize_callback' => array( 'Boldgrid_Framework_Customizer_Generic', 'sanitize' ),
		'choices'           => array(
			'name'     => 'boldgrid_controls',
			'type'     => 'ContainerWidth',
			'settings' => array(
				'responsive' => array(
					'tablet'  => 991,
					'desktop' => 1199,
				),
				'control'    => array(
					'selectors' => array( 'body.page .container' ),
					'name'      => 'bgtfw_pages_container_width',
					'sliders'   => array(
						array(
							'name'        => 'width',
							'label'       => '',
							'cssProperty' => 'width',
						),
					),
					'units'     => array(
						'default' => 'px',
						'enabled' => array( 'px', '%' ),
					),
				),
			),
		),
	),
	'bgtfw_pages_container_max_width' => array(
		'type'              => 'kirki-generic',
		'transport'         => 'postMessage',
		'section'           => 'bgtfw_layout_page_container',
		'settings'          => 'bgtfw_pages_container_max_width',
		'label'             => 'Container Max Width',
		'default'           => $bgtfw_generic->get_width_defaults( 'max-width' ),
		'sanitize_callback' => array( 'Boldgrid_Framework_Customizer_Generic', 'sanitize' ),
		'choices'           => array(
			'name'     => 'boldgrid_controls',
			'type'     => 'ContainerWidth',
			'settings' => array(
				'responsive' => array(
					'tablet'  => 991,
					'desktop' => 1199,
				),
				'control'    => array(
					'selectors' => array( 'body.page .container' ),
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
	'bgtfw_pages_title_display'       => array(
		'type'              => 'radio-buttonset',
		'settings'          => 'bgtfw_pages_title_display',
		'label'             => esc_html__( 'Display', 'bgtfw' ),
		'tooltip'           => esc_html__( 'This is a global setting. Access the editor to toggle page titles for individual posts.', 'bgtfw' ),
		'section'           => 'bgtfw_layout_page_title',
		'default'           => 'show',
		'choices'           => array(
			'show' => '<span class="dashicons dashicons-visibility"></span>' . __( 'Show', 'bgtfw' ),
			'hide' => '<span class="dashicons dashicons-hidden"></span>' . __( 'Hide', 'bgtfw' ),
		),
		'sanitize_callback' => function( $value, $settings ) {
			return in_array( $value, array( 'show', 'hide' ), true ) ? $value : $settings->default;
		},
		'partial_refresh'   => array(
			'bgtfw_pages_title_display' => array(
				'selector'        => '.page .page .featured-imgage-header, .blog .page-header .featured-imgage-header, .archive .page-header .featured-imgage-header',
				'render_callback' => function() {
					if ( ! is_front_page() && is_home() ) {
						printf(
							'<p class="page-title %1$s"><a %2$s href="%3$s" rel="bookmark">%4$s</a></p>',
							esc_attr( get_theme_mod( 'bgtfw_global_title_size' ) ),
							BoldGrid::add_class( 'pages_title', array( 'link' ), false ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							esc_url( get_permalink( get_option( 'page_for_posts', true ) ) ),
							wp_kses_post( get_the_title( get_option( 'page_for_posts', true ) ) )
						);
					} elseif ( is_archive() || is_author() ) {
						$queried_obj_id = get_queried_object_id();
						$archive_url = is_author() ? get_author_posts_url( $queried_obj_id ) : get_term_link( $queried_obj_id );
						printf(
							'<p class="page-title %1$s"><a %2$s href="%3$s" rel="bookmark">%4$s</a></p>',
							esc_attr( get_theme_mod( 'bgtfw_global_title_size' ) ),
							BoldGrid::add_class( 'pages_title', array( 'link' ), false ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							esc_url( $archive_url ),
							wp_kses_post( get_the_archive_title() )
						);
						the_archive_description( '<div class="taxonomy-description">', '</div>' );
					} else {
						the_title( sprintf( '<p class="entry-title page-title ' . esc_attr( get_theme_mod( 'bgtfw_global_title_size' ) ) . '"><a ' . BoldGrid::add_class( 'pages_title', array( 'link' ), false ) . ' href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></p>' );
					}
					return;
				},
			),
		),
		'edit_vars'         => array(
			array(
				'selector'    => array( '.page .page-header', '.archive .page-header', '.blog .page-header' ),
				'label'       => __( 'Page Title Display', 'bgtfw' ),
				'description' => __( 'Choose whether or not to display the page title', 'bgtfw' ),
			),
		),
	),
	'bgtfw_layout_page'               => array(
		'type'              => 'radio',
		'settings'          => 'bgtfw_layout_page',
		'label'             => __( 'Sidebar', 'bgtfw' ),
		'section'           => 'bgtfw_layout_page_sidebar',
		'default'           => 'no-sidebar',
		'priority'          => 10,
		'choices'           => array(),
		'sanitize_callback' => 'esc_attr',
		'edit_vars'         => array(
			array(
				'selector'    => '.page .site-content',
				'label'       => __( 'Page Sidebar Layout', 'bgtfw' ),
				'description' => __( 'Choose the layout of the sidebar on your pages', 'bgtfw' ),
			),
		),
	),
);

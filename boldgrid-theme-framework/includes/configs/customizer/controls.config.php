<?php
/**
 * Customizer Controls Configs
 *
 * @package Boldgrid_Theme_Framework
 * @subpackage Boldgrid_Theme_Framework\Configs
 *
 * @since 2.0.0
 *
 * @return array Controls to create in the WordPress Customizer.
 */

global $boldgrid_theme_framework;
$configs = $boldgrid_theme_framework->get_configs();

// Check that get_page_templates() method is available in the customizer.
if ( ! function_exists( 'get_page_templates' ) ) {
	require_once ABSPATH . 'wp-admin/includes/theme.php';
}

$palette = new Boldgrid_Framework_Compile_Colors( $this->configs );
$active_palette = $palette->get_active_palette();
$formatted_palette = $palette->color_format( $active_palette );
$sanitize = new Boldgrid_Framework_Customizer_Color_Sanitize();

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
	'boldgrid_background_color' => array(
		'type'        => 'bgtfw-palette-selector',
		'transport' => 'postMessage',
		'settings'    => 'boldgrid_background_color',
		'label' => esc_attr__( 'Color', 'bgtfw' ),
		'description' => esc_attr__( 'Choose a color from your palette to use.', 'bgtfw' ),
		'tooltip' => 'testing what a tool tip looks like',
		'section'     => 'background_image',
		'priority' => 1,
		'default'     => '',
		'choices'     => array(
			'colors' => $formatted_palette,
			'size' => $palette->get_palette_size( $formatted_palette ),
		),
		'sanitize_callback' => array( $sanitize, 'sanitize_palette_selector' ),
	),
	'boldgrid_background_vertical_position' => array(
		'type' => 'slider',
		'settings' => 'boldgrid_background_vertical_position',
		'label' => __( 'Vertical Background Position', 'bgtfw' ),
		'section' => 'background_image',
		'transport' => 'postMessage',
		'default' => '0',
		'priority' => 16,
		'choices' => array(
			'min' => - 100,
			'max' => 100,
			'step' => 1,
		),
	),

	/*** Start Background Image Overlay ***/
	'bgtfw_background_overlay' => array(
		'type'        => 'switch',
		'settings'    => 'bgtfw_background_overlay',
		'transport'   => 'postMessage',
		'label'       => __( 'Image Overlay', 'bgtfw' ),
		'description' => esc_attr__( 'Add an overlay to give your text readability over an image.', 'bgtfw' ),
		'section'     => 'background_image',
		'default'     => false,
		'priority'    => 10,
		'choices'     => array(
			'on'  => esc_attr__( 'Enable', 'bgtfw' ),
			'off' => esc_attr__( 'Disable', 'bgtfw' ),
		),
	),
	'bgtfw_background_overlay_color' => array(
		'type'        => 'bgtfw-palette-selector',
		'transport'   => 'postMessage',
		'settings'    => 'bgtfw_background_overlay_color',
		'label'       => esc_attr__( 'Overlay Color', 'bgtfw' ),
		'section'     => 'background_image',
		'priority'    => 10,
		'default'     => 'color-1',
		'choices'     => array(
			'colors' => $formatted_palette,
			'size' => $palette->get_palette_size( $formatted_palette ),
		),
		'sanitize_callback' => array( $sanitize, 'sanitize_palette_selector' ),
	),
	'bgtfw_background_overlay_alpha' => array(
		'type'        => 'slider',
		'transport'   => 'postMessage',
		'settings'    => 'bgtfw_background_overlay_alpha',
		'label'       => esc_attr__( 'Overlay Opacity', 'bgtfw' ),
		'section'     => 'background_image',
		'priority'    => 10,
		'default'     => '0.70',
		'choices'     => array(
			'min'  => '0',
			'max'  => '1',
			'step' => '.01',
		),
	),
	/*** End Background Image Overlay ***/

	/*** Start Header Generic Controls ***/
	'bgtfw_header_margin' => array(
		'type'        => 'kirki-generic',
		'transport'   => 'postMessage',
		'section'     => 'boldgrid_header_margin_section',
		'settings'    => 'bgtfw_header_margin',
		'label'       => '',
		'default'     => '',
		'choices' => array(
			'name' => 'boldgrid_controls',
			'type' => 'Margin',
			'settings' => array(
				'responsive' => Boldgrid_Framework_Customizer_Generic::$device_sizes,
				'control' => array(
					'selectors' => array( '.site-header' ),
					'sliders' => array(
						array( 'name' => 'top', 'label' => 'Top', 'cssProperty' => 'margin-top' ),
						array( 'name' => 'bottom', 'label' => 'Bottom', 'cssProperty' => 'margin-bottom' ),
					),
				),
			),
		),
	),
	'bgtfw_header_padding' => array(
		'type'        => 'kirki-generic',
		'transport'   => 'postMessage',
		'section'     => 'boldgrid_header_padding_section',
		'settings'    => 'bgtfw_header_padding',
		'label'       => '',
		'default'     => '',
		'choices' => array(
			'name' => 'boldgrid_controls',
			'type' => 'Padding',
			'settings' => array(
				'responsive' => Boldgrid_Framework_Customizer_Generic::$device_sizes,
				'control' => array(
					'selectors' => array( '.site-header header' ),
				),
			),
		),
	),
	'bgtfw_header_border' => array(
		'type'        => 'kirki-generic',
		'transport'   => 'postMessage',
		'section'     => 'boldgrid_header_border_section',
		'settings'    => 'bgtfw_header_border',
		'label'       => '',
		'default'     => '',
		'choices' => array(
			'name' => 'boldgrid_controls',
			'type' => 'Border',
			'settings' => array(
				'responsive' => Boldgrid_Framework_Customizer_Generic::$device_sizes,
				'control' => array(
					'selectors' => array( '.site-header header' ),
				),
			),
		),
	),
	'bgtfw_header_border_color' => array(
		'type'        => 'bgtfw-palette-selector',
		'transport'   => 'postMessage',
		'settings'    => 'bgtfw_header_border_color',
		'label'       => esc_attr__( 'Border Color', 'bgtfw' ),
		'section'     => 'boldgrid_header_border_section',
		'priority'    => 20,
		'default'     => 'color-1',
		'choices'     => array(
			'colors' => $formatted_palette,
			'size' => $palette->get_palette_size( $formatted_palette ),
		),
		'sanitize_callback' => array( $sanitize, 'sanitize_palette_selector' ),
	),
	'bgtfw_header_shadow' => array(
		'type'        => 'kirki-generic',
		'transport'   => 'postMessage',
		'section'     => 'boldgrid_header_shadow_section',
		'settings'    => 'bgtfw_header_shadow',
		'label'       => '',
		'default'     => '',
		'choices' => array(
			'name' => 'boldgrid_controls',
			'type' => 'BoxShadow',
			'settings' => array(
				'responsive' => Boldgrid_Framework_Customizer_Generic::$device_sizes,
				'control' => array(
					'selectors' => array( '.site-header header' ),
				),
			),
		),
	),
	'bgtfw_header_radius' => array(
		'type'        => 'kirki-generic',
		'transport'   => 'postMessage',
		'section'     => 'boldgrid_header_radius_section',
		'settings'    => 'bgtfw_header_radius',
		'label'       => '',
		'default'     => '',
		'choices' => array(
			'name' => 'boldgrid_controls',
			'type' => 'BorderRadius',
			'settings' => array(
				'responsive' => Boldgrid_Framework_Customizer_Generic::$device_sizes,
				'control' => array(
					'selectors' => array( '.site-header header', '.wp-custom-header' ),
				),
			),
		),
	),
	/*** End Header Generic Controls ***/

	/*** Start Footer Generic Controls ***/
	'bgtfw_footer_margin' => array(
		'type'        => 'kirki-generic',
		'transport'   => 'postMessage',
		'section'     => 'boldgrid_footer_margin_section',
		'settings'    => 'bgtfw_footer_margin',
		'label'       => '',
		'default'     => '',
		'choices' => array(
			'name' => 'boldgrid_controls',
			'type' => 'Margin',
			'settings' => array(
				'responsive' => Boldgrid_Framework_Customizer_Generic::$device_sizes,
				'control' => array(
					'selectors' => array( '#colophon.site-footer' ),
					'sliders' => array(
						array( 'name' => 'top', 'label' => 'Top', 'cssProperty' => 'margin-top' ),
						array( 'name' => 'bottom', 'label' => 'Bottom', 'cssProperty' => 'margin-bottom' ),
					),
				),
			),
		),
	),
	'bgtfw_footer_padding' => array(
		'type'        => 'kirki-generic',
		'transport'   => 'postMessage',
		'section'     => 'boldgrid_footer_padding_section',
		'settings'    => 'bgtfw_footer_padding',
		'label'       => '',
		'default'     => '',
		'choices' => array(
			'name' => 'boldgrid_controls',
			'type' => 'Padding',
			'settings' => array(
				'responsive' => Boldgrid_Framework_Customizer_Generic::$device_sizes,
				'control' => array(
					'selectors' => array( '#colophon.site-footer' ),
				),
			),
		),
	),
	'bgtfw_footer_border' => array(
		'type'        => 'kirki-generic',
		'transport'   => 'postMessage',
		'section'     => 'boldgrid_footer_border_section',
		'settings'    => 'bgtfw_footer_border',
		'label'       => '',
		'default'     => '',
		'choices' => array(
			'name' => 'boldgrid_controls',
			'type' => 'Border',
			'settings' => array(
				'responsive' => Boldgrid_Framework_Customizer_Generic::$device_sizes,
				'control' => array(
					'selectors' => array( '#colophon.site-footer' ),
				),
			),
		),
	),
	'bgtfw_footer_border_color' => array(
		'type'        => 'bgtfw-palette-selector',
		'transport'   => 'postMessage',
		'settings'    => 'bgtfw_footer_border_color',
		'label'       => esc_attr__( 'Border Color', 'bgtfw' ),
		'section'     => 'boldgrid_footer_border_section',
		'priority'    => 20,
		'default'     => 'color-1',
		'choices'     => array(
			'colors' => $formatted_palette,
			'size' => $palette->get_palette_size( $formatted_palette ),
		),
		'sanitize_callback' => array( $sanitize, 'sanitize_palette_selector' ),
	),
	'bgtfw_footer_shadow' => array(
		'type'        => 'kirki-generic',
		'transport'   => 'postMessage',
		'section'     => 'boldgrid_footer_shadow_section',
		'settings'    => 'bgtfw_footer_shadow',
		'label'       => '',
		'default'     => '',
		'choices' => array(
			'name' => 'boldgrid_controls',
			'type' => 'BoxShadow',
			'settings' => array(
				'responsive' => Boldgrid_Framework_Customizer_Generic::$device_sizes,
				'control' => array(
					'selectors' => array( '#colophon.site-footer' ),
				),
			),
		),
	),
	'bgtfw_footer_radius' => array(
		'type'        => 'kirki-generic',
		'transport'   => 'postMessage',
		'section'     => 'boldgrid_footer_radius_section',
		'settings'    => 'bgtfw_footer_radius',
		'label'       => '',
		'default'     => '',
		'choices' => array(
			'name' => 'boldgrid_controls',
			'type' => 'BorderRadius',
			'settings' => array(
				'responsive' => Boldgrid_Framework_Customizer_Generic::$device_sizes,
				'control' => array(
					'selectors' => array( '#colophon.site-footer' ),
				),
			),
		),
	),
	/*** End Footer Generic Controls ***/

	'boldgrid_background_horizontal_position' => array(
		'type' => 'slider',
		'settings' => 'boldgrid_background_horizontal_position',
		'label' => __( 'Horizontal Background Position', 'bgtfw' ),
		'section' => 'background_image',
		'transport' => 'postMessage',
		'default' => '0',
		'priority' => 17,
		'choices' => array(
			'min' => -100,
			'max' => 100,
			'step' => 1,
		),
	),
	'bgtfw_layout_page' => array(
		'type'        => 'radio',
		'settings'    => 'bgtfw_layout_page',
		'label'       => __( 'Default Sidebar Display', 'bgtfw' ),
		'section'     => 'bgtfw_layout_page',
		'default'     => 'no-sidebar',
		'priority'    => 10,
		'choices'     => array(),
		'sanitize_callback' => 'esc_attr',
	),
	'bgtfw_pages_display_title' => array(
		'type' => 'switch',
		'settings' => 'bgtfw_pages_display_title',
		'transport' => 'auto',
		'label' => esc_html__( 'Page Title', 'bgtfw' ),
		'section' => 'bgtfw_layout_page',
		'priority' => 40,
		'default' => true,
		'partial_refresh' => array(
			'bgtfw_pages_display_title' => array(
				'selector' => '.page .page .featured-imgage-header',
				'render_callback' => function() {
					return get_theme_mod( 'bgtfw_pages_display_title' ) ? the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ) : '';
				},
			),
		),
	),
	'bgtfw_posts_display_title' => array(
		'type' => 'switch',
		'settings' => 'bgtfw_posts_display_title',
		'label' => esc_html__( 'Post Title', 'bgtfw' ),
		'section' => 'bgtfw_pages_blog_posts_layout',
		'priority' => 40,
		'default' => true,
		'partial_refresh' => array(
			'bgtfw_posts_display_title' => array(
				'selector' => '.single .post .featured-imgage-header',
				'render_callback' => function() {
					if ( get_theme_mod( 'bgtfw_posts_display_title' ) ) {
						the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' );
					}
					echo '<div class="entry-meta">';
					boldgrid_posted_on();
					echo '</div>';
					return;
				},
			),
		),
	),
	'bgtfw_headings_color' => array(
		'type'        => 'bgtfw-palette-selector',
		'transport'   => 'postMessage',
		'settings'    => 'bgtfw_headings_color',
		'label'       => esc_attr__( 'Color', 'bgtfw' ),
		'section'     => 'headings_typography',
		'priority'    => 10,
		'default'     => '',
		'choices'     => array(
			'colors'  => $formatted_palette,
			'size'    => $palette->get_palette_size( $formatted_palette ),
		),
		'sanitize_callback' => array( $sanitize, 'sanitize_palette_selector' ),
	),
	'bgtfw_headings_typography' => array(
		'type'     => 'typography',
		'settings'  => 'bgtfw_headings_typography',
		'transport'   => 'auto',
		'settings'    => 'bgtfw_headings_typography',
		'label'       => esc_attr__( 'Headings Typography', 'bgtfw' ),
		'section'     => 'headings_typography',
		'default'     => array(
			'font-family'    => 'Roboto',
			'variant'        => 'regular',
			'font-size'      => '14px',
			'line-height'    => '1.5',
			'letter-spacing' => '0',
			'subsets'        => array( 'latin-ext' ),
			'text-transform' => 'none',
		),
		'priority'    => 20,
		'output'      => array(
			array(
				'element'  => implode( ', ', array_keys( $this->configs['customizer-options']['typography']['selectors'] ) ),
			),
		),
	),
	'bgtfw_tagline_color' => array(
		'type'        => 'bgtfw-palette-selector',
		'transport'   => 'postMessage',
		'settings'    => 'bgtfw_tagline_color',
		'label'       => esc_attr__( 'Color', 'bgtfw' ),
		'section'     => 'bgtfw_tagline',
		'priority'    => 10,
		'default'     => '',
		'choices'     => array(
			'colors'  => $formatted_palette,
			'size'    => $palette->get_palette_size( $formatted_palette ),
		),
		'sanitize_callback' => array( $sanitize, 'sanitize_palette_selector' ),
	),
	'bgtfw_tagline_typography' => array(
		'type'        => 'typography',
		'transport'   => 'auto',
		'settings'    => 'bgtfw_tagline_typography',
		'label'       => esc_attr__( 'Typography', 'bgtfw' ),
		'section'     => 'bgtfw_tagline',
		'default'     => array(
			'font-family'    => 'Roboto',
			'variant'        => 'regular',
			'font-size'      => '42px',
			'line-height'    => '1.5',
			'letter-spacing' => '0',
			'subsets'        => array( 'latin-ext' ),
			'text-transform' => 'none',
			'text-align'     => 'left',
		),
		'priority'    => 20,
		'output'      => array(
			array(
				'element' => '.site-branding .site-description',
			),
		),
	),
	'bgtfw_fixed_header' => array(
		'type'        => 'switch',
		'transport'   => 'postMessage',
		'settings'    => 'bgtfw_fixed_header',
		'label'       => esc_attr__( 'Sticky Header', 'bgtfw' ),
		'section'     => 'bgtfw_header_layout',
		'default'     => false,
		'priority'    => 10,
	),
	'bgtfw_header_width' => array(
		'type'        => 'slider',
		'settings'    => 'bgtfw_header_width',
		'transport'   => 'auto',
		'label'       => esc_attr__( 'Header Width', 'bgtfw' ),
		'section'     => 'bgtfw_header_layout',
		'default'     => 400,
		'choices'     => array(
			'min'  => '0',
			'max'  => '600',
			'step' => '1',
		),
		'active_callback' => array(
			array(
				'setting'  => 'bgtfw_header_layout_position',
				'operator' => '!=',
				'value'    => 'header-top',
			),
		),
		'output' => array(
			array(
				'media_query' => '@media only screen and (min-width : 768px)',
				'element'  => '.flexbox .header-left .site-header, .flexbox .header-right .site-header',
				'property' => 'flex',
				'value_pattern' => '0 0 $px',
			),
			array(
				'media_query' => '@media only screen and (max-width : 968px)',
				'element'  => '.flexbox .header-left .site-content, .flexbox .header-right .site-content',
				'property' => 'flex',
				'value_pattern' => '1 0 calc(100% - $px)',
			),
			array(
				'media_query' => '@media only screen and (min-width : 768px)',
				'element'  => ' .flexbox .header-left.header-fixed .site-footer, .flexbox .header-right.header-fixed .site-footer',
				'property' => 'width',
				'value_pattern' => 'calc(100% - $px)',
			),
			array(
				'media_query' => '@media only screen and (min-width : 768px)',
				'element'  => '.flexbox .header-left .site-content, .flexbox .header-left.header-fixed .site-footer, .flexbox .header-right .site-content, .flexbox .header-right.header-fixed .site-footer',
				'property' => 'width',
				'value_pattern' => 'calc(100% - $px)',
			),
			array(
				'media_query' => '@media only screen and (min-width : 768px)',
				'element'  => '.flexbox .header-right.header-fixed .site-header, .flexbox .header-left.header-fixed .site-header, .header-right .wp-custom-header, .header-left .wp-custom-header, .header-right .site-header, .header-left .site-header, .header-left #masthead, .header-right #masthead',
				'property' => 'width',
				'value_pattern' => '$px',
			),
			array(
				'media_query' => '@media only screen and (min-width : 768px)',
				'element'  => '.header-left #navi-wrap, .header-right #navi-wrap',
				'property' => 'max-width',
				'value_pattern' => '$px',
			),
			array(
				'media_query' => '@media only screen and (min-width : 768px)',
				'element'  => '.flexbox .header-right.header-fixed .site-footer, .flexbox .header-right.header-fixed .site-content',
				'property' => 'margin-right',
				'value_pattern' => '$px',
			),
			array(
				'media_query' => '@media only screen and (min-width : 768px)',
				'element'  => '.flexbox .header-left.header-fixed .site-footer, .flexbox .header-left.header-fixed .site-content',
				'property' => 'margin-left',
				'value_pattern' => '$px',
			),
		),
	),
	'bgtfw_header_color' => array(
		'type'        => 'bgtfw-palette-selector',
		'transport' => 'postMessage',
		'settings'    => 'bgtfw_header_color',
		'label' => esc_attr__( 'Background Color', 'bgtfw' ),
		'section'     => 'bgtfw_header_colors',
		'priority' => 1,
		'default'     => '',
		'choices'     => array(
			'colors' => $formatted_palette,
			'size' => $palette->get_palette_size( $formatted_palette ),
		),
		'sanitize_callback' => array( $sanitize, 'sanitize_palette_selector' ),
	),
	'bgtfw_site_title_color' => array(
		'type'        => 'bgtfw-palette-selector',
		'transport' => 'postMessage',
		'settings'    => 'bgtfw_site_title_color',
		'label' => esc_attr__( 'Color', 'bgtfw' ),
		'section'     => 'bgtfw_site_title',
		'priority' => 10,
		'default'     => '',
		'choices'     => array(
			'colors' => $formatted_palette,
			'size' => $palette->get_palette_size( $formatted_palette ),
		),
		'sanitize_callback' => array( $sanitize, 'sanitize_palette_selector' ),
	),
	'bgtfw_site_title_typography' => array(
		'type'        => 'typography',
		'transport'   => 'auto',
		'settings'    => 'bgtfw_site_title_typography',
		'label'       => esc_attr__( 'Typography', 'bgtfw' ),
		'section'     => 'bgtfw_site_title',
		'default'     => array(
			'font-family'    => 'Roboto',
			'variant'        => 'regular',
			'font-size'      => '42px',
			'line-height'    => '1.5',
			'letter-spacing' => '0',
			'subsets'        => array( 'latin-ext' ),
			'text-transform' => 'none',
			'text-align'     => 'left',
		),
		'priority'    => 20,
		'output'      => array(
			array(
				'element' => '.' . get_theme_mod( 'boldgrid_palette_class', 'palette-primary' ) . ' .site-header .site-title > a,.' . get_theme_mod( 'boldgrid_palette_class', 'palette-primary' ) . ' .site-header .site-title > a:hover',
			),
		),
	),
	'bgtfw_body_typography' => array(
		'type'        => 'typography',
		'transport'   => 'auto',
		'settings'    => 'bgtfw_body_typography',
		'label'       => esc_attr__( 'Typography', 'bgtfw' ),
		'section'     => 'body_typography',
		'default'     => array(
			'font-family'    => 'Roboto',
			'variant'        => '100',
			'font-size'      => '18px',
			'line-height'    => '1.4',
			'letter-spacing' => '0',
			'subsets'        => array( 'latin-ext' ),
			'text-transform' => 'none',

		),
		'priority'    => 10,
		'output'      => array(
			array(
				'element' => '.site-header, .site-content, .site-footer, .gutenberg .edit-post-visual-editor, .mce-content-body',
			),
		),
	),
	'boldgrid_attribution_heading' => array(
		'type'        => 'custom',
		'settings'     => 'boldgrid_attribution_heading',
		'label'       => __( 'Attribution Control', 'bgtfw' ),
		'section'     => 'boldgrid_footer_panel',
		'default'     => '',
		'priority'    => 20,
	),
	'hide_boldgrid_attribution' => array(
		'type'        => 'checkbox',
		'settings'     => 'hide_boldgrid_attribution',
		'transport'   => 'refresh',
		'label'       => __( 'Hide BoldGrid Attribution', 'bgtfw' ),
		'section'     => 'boldgrid_footer_panel',
		'default'     => false,
		'priority'    => 30,
	),
	'hide_wordpress_attribution' => array(
		'type'        => 'checkbox',
		'settings'     => 'hide_wordpress_attribution',
		'transport'   => 'refresh',
		'label'       => __( 'Hide WordPress Attribution', 'bgtfw' ),
		'section'     => 'boldgrid_footer_panel',
		'default'     => false,
		'priority'    => 40,
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
		'fields' => array(
			'contact_block' => array(
				'type'        => 'text',
				'label'       => esc_attr__( 'Text', 'bgtfw' ),
				'description' => esc_attr__( 'Enter the text to display in your contact details', 'bgtfw' ),
				'default'     => '',
			),
		),
	),
	'boldgrid_enable_footer' => array(
		'type' => 'switch',
		'settings' => 'boldgrid_enable_footer',
		'label' => __( 'Enable Footer', 'bgtfw' ),
		'section' => 'boldgrid_footer_panel',
		'default' => true,
		'priority' => 5,
	),
	'boldgrid_footer_widget_help' => array(
		'type'        => 'custom',
		'settings'     => 'boldgrid_footer_widget_help',
		'section'     => 'bgtfw_footer_widgets',
		'default'     => '<a class="button button-primary open-widgets-section">' . __( 'Continue to Widgets Section', 'bgtfw' ) . '</a>',
		'priority'    => 10,
		'description' => __( 'You can add widgets to your footer from the widgets section.', 'bgtfw' ),
		'required' => array(
			array(
				'settings' => 'boldgrid_enable_footer',
				'operator' => '==',
				'value' => true,
			),
		),
	),
	'boldgrid_footer_widgets' => array(
		'label'       => __( 'Footer Widget Areas', 'bgtfw' ),
		'description' => __( 'Select the number of footer widget columns you wish to display.', 'bgtfw' ),
		'type'        => 'number',
		'settings'    => 'boldgrid_footer_widgets',
		'priority'    => 15,
		'default'     => 0,
		'transport'   => 'auto',
		'choices'     => array(
			'min'  => 0,
			'max'  => 4,
			'step' => 1,
		),
		'section'     => 'bgtfw_footer_widgets',
		'partial_refresh' => array(
			'boldgrid_footer_widgets' => array(
				'selector'        => '#footer-widget-area',
				'render_callback' => function() {
					$widget_area = new Boldgrid_Framework_Customizer_Widget_Areas();
					$widget_area->footer_html();
				},
				'container_inclusive' => true,
			),
		),
	),
	'bgtfw_header_widget_help' => array(
		'type'        => 'custom',
		'settings'     => 'bgtfw_header_widget_help',
		'section'     => 'bgtfw_header_widgets',
		'default'     => '<a class="button button-primary open-widgets-section">' . __( 'Continue to Widgets Section', 'bgtfw' ) . '</a>',
		'priority'    => 10,
		'description' => __( 'You can add widgets to your header from the widgets section.', 'bgtfw' ),
	),
	'boldgrid_header_widgets' => array(
		'label'       => __( 'Header Widget Areas', 'bgtfw' ),
		'description' => __( 'Select the number of header widget columns you wish to display.', 'bgtfw' ),
		'type'        => 'number',
		'settings'    => 'boldgrid_header_widgets',
		'priority'    => 80,
		'default'     => 0,
		'transport'   => 'auto',
		'choices'     => array(
			'min'  => 0,
			'max'  => 4,
			'step' => 1,
		),
		'section'     => 'bgtfw_header_widgets',
		'partial_refresh' => array(
			'boldgrid_header_widgets' => array(
				'selector'        => '#header-widget-area',
				'render_callback' => function() {
					$widget_area = new Boldgrid_Framework_Customizer_Widget_Areas();
					$widget_area->header_html();
				},
			),
		),
	),

	// Header overlay begin.
	'bgtfw_header_overlay' => array(
		'type'        => 'switch',
		'settings'    => 'bgtfw_header_overlay',
		'transport'   => 'postMessage',
		'label'       => __( 'Header Overlay', 'bgtfw' ),
		'description' => esc_attr__( 'Add an overlay to give your text readability over an image or video.', 'bgtfw' ),
		'section'     => 'header_image',
		'default'     => false,
		'priority'    => 20,
		'choices'     => array(
			'on'  => esc_attr__( 'Enable', 'bgtfw' ),
			'off' => esc_attr__( 'Disable', 'bgtfw' ),
		),
	),
	'bgtfw_header_overlay_color' => array(
		'type'        => 'bgtfw-palette-selector',
		'transport'   => 'postMessage',
		'settings'    => 'bgtfw_header_overlay_color',
		'label'       => esc_attr__( 'Overlay Color', 'bgtfw' ),
		'section'     => 'header_image',
		'priority'    => 25,
		'default'     => 'color-1',
		'choices'     => array(
			'colors' => $formatted_palette,
			'size' => $palette->get_palette_size( $formatted_palette ),
		),
		'sanitize_callback' => array( $sanitize, 'sanitize_palette_selector' ),
	),
	'bgtfw_header_overlay_alpha' => array(
		'type'        => 'slider',
		'transport'   => 'postMessage',
		'settings'    => 'bgtfw_header_overlay_alpha',
		'label'       => esc_attr__( 'Overlay Opacity', 'bgtfw' ),
		'section'     => 'header_image',
		'priority'    => 30,
		'default'     => '0.70',
		'choices'     => array(
			'min'  => '0',
			'max'  => '1',
			'step' => '.01',
		),
	),
	// Header overlay end.
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
			'colors' => $formatted_palette,
			'size' => $palette->get_palette_size( $formatted_palette ),
		),
		'sanitize_callback' => array( $sanitize, 'sanitize_palette_selector' ),
	),
	'bgtfw_footer_headings_color' => array(
		'type' => 'bgtfw-palette-selector',
		'transport' => 'postMessage',
		'settings' => 'bgtfw_footer_headings_color',
		'label' => esc_attr__( 'Headings Color', 'bgtfw' ),
		'section' => 'bgtfw_footer_colors',
		'priority' => 20,
		'default' => '',
		'choices' => array(
			'colors' => $formatted_palette,
			'size' => $palette->get_palette_size( $formatted_palette ),
		),
		'sanitize_callback' => array( $sanitize, 'sanitize_palette_selector' ),
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
			'colors' => $formatted_palette,
			'size' => $palette->get_palette_size( $formatted_palette ),
		),
		'sanitize_callback' => array( $sanitize, 'sanitize_palette_selector' ),
	),
	'bgtfw_pages_blog_blog_page_layout_columns' => array(
		'label'       => __( 'Columns', 'bgtfw' ),
		'description' => __( 'Select the number of columns you wish to display on your blog page.', 'bgtfw' ),
		'type'        => 'number',
		'settings'    => 'bgtfw_pages_blog_blog_page_layout_columns',
		'priority'    => 1,
		'default'     => 1,
		'transport'   => 'postMessage',
		'choices'     => array(
			'min'  => 1,
			'max'  => 6,
			'step' => 1,
		),
		'section'     => 'bgtfw_pages_blog_blog_page_layout',
		'sanitize_callback' => function( $value, $setting ) {
			return is_int( $value ) && 6 <= absint( $value ) ? absint( $value ) : $setting->default;
		},
	),
	'bgtfw_pages_blog_blog_page_layout_featimg' => array(
		'type'        => 'switch',
		'settings'    => 'bgtfw_pages_blog_blog_page_layout_featimg',
		'label'       => esc_attr__( 'Featured Images', 'bgtfw' ),
		'description' => __( 'Display the featured image for posts in the full post content.', 'bgtfw' ),
		'section'     => 'bgtfw_pages_blog_blog_page_layout',
		'default'     => false,
		'priority'    => 45,
	),
	'bgtfw_pages_blog_blog_page_layout_content' => array(
		'type'        => 'radio',
		'settings' => 'bgtfw_pages_blog_blog_page_layout_content',
		'transport' => 'refresh',
		'label'       => esc_html__( 'Post Content Display', 'bgtfw' ),
		'priority'    => 40,
		'default'   => 'excerpt',
		'choices'     => array(
			'excerpt' => esc_attr__( 'Post Excerpt', 'bgtfw' ),
			'content' => esc_attr__( 'Full Content', 'bgtfw' ),
		),
		'section' => 'bgtfw_pages_blog_blog_page_layout',
		'sanitize_callback' => function( $value, $settings ) {
			return 'excerpt' === $value || 'content' === $value ? $value : $settings->default;
		},
	),
	'bgtfw_pages_blog_posts_layout_layout' => array(
		'settings' => 'bgtfw_pages_blog_posts_layout_layout',
		'transport'   => 'postMessage',
		'label'       => esc_html__( 'Layout', 'bgtfw' ),
		'type'        => 'radio',
		'priority'    => 40,
		'default'   => 'container',
		'choices'     => array(
			'container' => esc_attr__( 'Contained', 'bgtfw' ),
			'container-fluid' => esc_attr__( 'Full Width', 'bgtfw' ),
		),
		'section' => 'bgtfw_pages_blog_posts_layout',
		'sanitize_callback' => function( $value, $settings ) {
			return 'container' === $value || 'container-fluid' === $value ? $value : $settings->default;
		},
	),
	'bgtfw_layout_blog' => array(
		'settings' => 'bgtfw_layout_blog',
		'label'       => esc_html__( 'Sidebar Display', 'bgtfw' ),
		'type'        => 'radio',
		'priority'    => 10,
		'default'   => 'no-sidebar',
		'choices'     => array(),
		'section'     => 'bgtfw_pages_blog_posts_sidebar',
		'sanitize_callback' => 'sanitize_html_class',
	),
	'bgtfw_blog_blog_page_settings' => array(
		'settings' => 'bgtfw_blog_blog_page_settings',
		'label'       => esc_html__( 'Homepage Sidebar Display', 'bgtfw' ),
		'type'        => 'radio',
		'priority'    => 10,
		'default'   => 'no-sidebar',
		'choices'     => array(),
		'section'     => 'bgtfw_blog_blog_page_settings',
		'sanitize_callback' => 'sanitize_html_class',
	),
	'bgtfw_blog_layout' => array(
		'settings' => 'bgtfw_blog_layout',
		'transport'   => 'postMessage',
		'label'       => esc_html__( 'Design', 'bgtfw' ),
		'type'        => 'radio',
		'priority'    => 40,
		'default'   => 'layout-1',
		'choices'     => array(
			'design-1' => esc_attr__( 'Design 1', 'bgtfw' ),
			'design-2' => esc_attr__( 'Design 2', 'bgtfw' ),
			'design-3' => esc_attr__( 'Design 3', 'bgtfw' ),
			'design-4' => esc_attr__( 'Design 4', 'bgtfw' ),
		),
		'section' => 'bgtfw_pages_blog_blog_page_layout',
		'sanitize_callback' => function( $value, $settings ) {
			return in_array( $value, [ 'design-1', 'design-2', 'design-3', 'design-4' ], true ) ? $value : $settings->default;
		},
	),
	'bgtfw_blog_blog_page_sidebar' => array(
		'settings' => 'bgtfw_blog_blog_page_sidebar',
		'label'       => esc_html__( 'Homepage Sidebar Display', 'bgtfw' ),
		'type'        => 'radio',
		'priority'    => 30,
		'default'   => 'no-sidebar',
		'choices'     => array(),
		'section'     => 'static_front_page',
		'active_callback' => function() {
			return get_option( 'show_on_front', 'posts' ) === 'posts' ? true : false;
		},
		'sanitize_callback' => 'sanitize_html_class',
	),
	'bgtfw_blog_blog_page_sidebar2' => array(
		'setting' => 'bgtfw_blog_blog_page_sidebar2',
		'settings'    => 'bgtfw_blog_blog_page_sidebar',
		'label'       => esc_html__( 'Sidebar Options', 'bgtfw' ),
		'type'        => 'radio',
		'priority'    => 10,
		'default'   => 'no-sidebar',
		'choices'     => array(),
		'section'     => 'bgtfw_blog_blog_page_panel_sidebar',
		'sanitize_callback' => 'sanitize_html_class',
	),
	'bgtfw_layout_blog_layout' => array(
		'settings' => 'bgtfw_layout_blog_layout',
		'transport'   => 'postMessage',
		'label'       => esc_html__( 'Homepage Blog Layout', 'bgtfw' ),
		'type'        => 'radio',
		'priority'    => 40,
		'default'   => 'layout-1',
		'choices'     => array(
			'layout-1' => esc_attr__( 'Layout 1', 'bgtfw' ),
			'layout-2' => esc_attr__( 'Layout 2', 'bgtfw' ),
			'layout-3' => esc_attr__( 'Layout 3', 'bgtfw' ),
			'layout-4' => esc_attr__( 'Layout 4', 'bgtfw' ),
			'layout-5' => esc_attr__( 'Layout 5', 'bgtfw' ),
			'layout-6' => esc_attr__( 'Layout 6', 'bgtfw' ),
		),
		'section' => 'static_front_page',
		'active_callback' => function() {
			return get_option( 'show_on_front', 'posts' ) === 'posts' ? true : false;
		},
		'sanitize_callback' => 'sanitize_html_class',
	),
	'bgtfw_layout_blog_layout' => array(
		'settings' => 'bgtfw_layout_blog_layout',
		'transport'   => 'postMessage',
		'label'       => esc_html__( 'Layout', 'bgtfw' ),
		'type'        => 'radio',
		'priority'    => 40,
		'default' => 'layout-1',
		'choices'     => array(
			'layout-1' => esc_attr__( 'Layout 1', 'bgtfw' ),
			'layout-2' => esc_attr__( 'Layout 2', 'bgtfw' ),
			'layout-3' => esc_attr__( 'Layout 3', 'bgtfw' ),
			'layout-4' => esc_attr__( 'Layout 4', 'bgtfw' ),
			'layout-5' => esc_attr__( 'Layout 5', 'bgtfw' ),
			'layout-6' => esc_attr__( 'Layout 6', 'bgtfw' ),
		),
		'section' => 'bgtfw_layout_blog',
		'sanitize_callback' => 'sanitize_html_class',
	),
	'bgtfw_header_top_layouts' => array(
		'settings' => 'bgtfw_header_top_layouts',
		'transport'   => 'postMessage',
		'label'       => esc_html__( 'Layout', 'bgtfw' ),
		'type'        => 'radio',
		'priority'    => 30,
		'default'   => 'layout-1',
		'choices'     => array(
			'layout-1' => esc_attr__( 'Layout 1', 'bgtfw' ),
			'layout-2' => esc_attr__( 'Layout 2', 'bgtfw' ),
			'layout-3' => esc_attr__( 'Layout 3', 'bgtfw' ),
			'layout-4' => esc_attr__( 'Layout 4', 'bgtfw' ),
			'layout-5' => esc_attr__( 'Layout 5', 'bgtfw' ),
			'layout-6' => esc_attr__( 'Layout 6', 'bgtfw' ),
		),
		'section'     => 'bgtfw_header_layout',
		'sanitize_callback' => 'sanitize_html_class',
	),
	'header_container' => array(
		'settings' => 'header_container',
		'transport'   => 'postMessage',
		'label'       => esc_html__( 'Header Container', 'bgtfw' ),
		'type'        => 'radio',
		'priority'    => 30,
		'default'   => '',
		'choices'     => array(
			'' => esc_attr__( 'Full Width', 'bgtfw' ),
			'container' => esc_attr__( 'Fixed Width', 'bgtfw' ),
		),
		'section'     => 'bgtfw_header_layout',
		'sanitize_callback' => function( $value, $settings ) {
			return '' === $value || 'container' === $value ? $value : $settings->default;
		},
	),
	'bgtfw_footer_layouts' => array(
		'settings' => 'bgtfw_footer_layouts',
		'transport'   => 'postMessage',
		'label'       => esc_html__( 'Layout', 'bgtfw' ),
		'type'        => 'radio',
		'priority'    => 10,
		'default'   => 'layout-1',
		'choices'     => array(
			'layout-1' => esc_attr__( 'Layout 1', 'bgtfw' ),
			'layout-2' => esc_attr__( 'Layout 2', 'bgtfw' ),
			'layout-3' => esc_attr__( 'Layout 3', 'bgtfw' ),
			'layout-4' => esc_attr__( 'Layout 4', 'bgtfw' ),
			'layout-5' => esc_attr__( 'Layout 5', 'bgtfw' ),
			'layout-6' => esc_attr__( 'Layout 6', 'bgtfw' ),
			'layout-7' => esc_attr__( 'Layout 7', 'bgtfw' ),
			'layout-8' => esc_attr__( 'Layout 8', 'bgtfw' ),
		),
		'section'     => 'boldgrid_footer_panel',
		'sanitize_callback' => 'sanitize_html_class',
	),
	'footer_container' => array(
		'settings' => 'footer_container',
		'transport'   => 'postMessage',
		'label'       => esc_html__( 'Footer Container', 'bgtfw' ),
		'type'        => 'radio',
		'priority'    => 10,
		'default'   => '',
		'choices'     => array(
			'' => esc_attr__( 'Full Width', 'bgtfw' ),
			'container' => esc_attr__( 'Fixed Width', 'bgtfw' ),
		),
		'section'     => 'boldgrid_footer_panel',
		'sanitize_callback' => function( $value, $settings ) {
			return '' === $value || 'container' === $value ? $value : $settings->default;
		},
	),
	'bgtfw_header_layout_position' => array(
		'settings' => 'bgtfw_header_layout_position',
		'transport' => 'postMessage',
		'label' => __( 'Header Position', 'bgtfw' ),
		'type' => 'radio',
		'priority' => 10,
		'default' => 'header-top',
		'choices' => array(
			'header-top' => esc_attr__( 'Header on Top', 'bgtfw' ),
			'header-left' => esc_attr__( 'Header on Left', 'bgtfw' ),
			'header-right' => esc_attr__( 'Header on Right', 'bgtfw' ),
		),
		'section' => 'bgtfw_header_layout',
		'sanitize_callback' => 'sanitize_html_class',
	),

	/*** Start: Dynamic Menu Controls ***/
	'bgtfw_menu_hamburger_main_toggle' => array(
		'type' => 'switch',
		'settings' => 'bgtfw_menu_hamburger_main_toggle',
		'transport' => 'postMessage',
		'label' => esc_html__( 'Enable Hamburger Menu', 'bgtfw' ),
		'section' => 'bgtfw_menu_hamburgers_main',
		'default' => true,
	),
	'bgtfw_menu_hamburger_main_color' => array(
		'type'        => 'bgtfw-palette-selector',
		'transport'   => 'postMessage',
		'settings'    => 'bgtfw_menu_hamburger_main_color',
		'label'       => esc_attr__( 'Primary Color', 'bgtfw' ),
		'section'     => 'bgtfw_menu_hamburgers_main',
		'default'     => 'color-1',
		'choices'     => array(
			'colors'  => $formatted_palette,
			'size'    => $palette->get_palette_size( $formatted_palette ),
		),
		'sanitize_callback' => array( $sanitize, 'sanitize_palette_selector' ),
	),
	'bgtfw_menu_hamburger_main' => array(
		'settings' => 'bgtfw_menu_hamburger_main',
		'transport' => 'postMessage',
		'label' => __( 'Hamburger Style', 'bgtfw' ),
		'type' => 'bgtfw-menu-hamburgers',
		'default' => 'hamburger--collapse',
		'section' => 'bgtfw_menu_hamburgers_main',
		'sanitize_callback' => 'sanitize_html_class',
	),

	/* Start: Main Menu Background Controls */
	'bgtfw_menu_background_main' => array(
		'type'            => 'bgtfw-palette-selector',
		'transport'       => 'postMessage',
		'settings'        => 'bgtfw_menu_background_main',
		'label'           => esc_attr__( 'Background Color', 'bgtfw' ),
		'section'         => 'bgtfw_menu_background_main',
		'default'         => 'transparent',
		'choices'         => array(
			'colors'      => $formatted_palette,
			'size'        => $palette->get_palette_size( $formatted_palette, true ),
			'transparent' => true,
		),
		'sanitize_callback' => array( $sanitize, 'sanitize_palette_selector' ),
	),

	/* Start: Main Menu Spacing Controls */
	'bgtfw_menu_margin_main' => array(
		'type'        => 'kirki-generic',
		'transport'   => 'postMessage',
		'section'     => 'bgtfw_menu_margin_main',
		'settings'    => 'bgtfw_menu_margin_main',
		'label'       => '',
		'default'     => '',
		'choices' => array(
			'name' => 'boldgrid_controls',
			'type' => 'Margin',
			'settings' => array(
				'responsive' => Boldgrid_Framework_Customizer_Generic::$device_sizes,
				'control' => array(
					'selectors' => array( '#main-menu' ),
					'sliders' => array(
						array( 'name' => 'top', 'label' => 'Top', 'cssProperty' => 'margin-top' ),
						array( 'name' => 'right', 'label' => 'Right', 'cssProperty' => 'margin-right' ),
						array( 'name' => 'bottom', 'label' => 'Bottom', 'cssProperty' => 'margin-bottom' ),
						array( 'name' => 'left', 'label' => 'Left', 'cssProperty' => 'margin-left' ),
					),
				),
				'slider' => array(
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
	'bgtfw_menu_padding_main' => array(
		'type'        => 'kirki-generic',
		'transport'   => 'postMessage',
		'section'     => 'bgtfw_menu_padding_main',
		'settings'    => 'bgtfw_menu_padding_main',
		'label'       => '',
		'default'     => '',
		'choices' => array(
			'name' => 'boldgrid_controls',
			'type' => 'Padding',
			'settings' => array(
				'responsive' => Boldgrid_Framework_Customizer_Generic::$device_sizes,
				'control' => array(
					'selectors' => array( '#main-menu' ),
					'sliders' => array(
						array( 'name' => 'top', 'label' => 'Top', 'cssProperty' => 'padding-top' ),
						array( 'name' => 'right', 'label' => 'Right', 'cssProperty' => 'padding-right' ),
						array( 'name' => 'bottom', 'label' => 'Bottom', 'cssProperty' => 'padding-bottom' ),
						array( 'name' => 'left', 'label' => 'Left', 'cssProperty' => 'padding-left' ),
					),
				),
				'slider' => array(
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

	'bgtfw_menu_visibility_main' => array(
		'type'        => 'kirki-generic',
		'transport'   => 'postMessage',
		'section'     => 'bgtfw_menu_visibility_main',
		'settings'    => 'bgtfw_menu_visibility_main',
		'label'       => '',
		'default'     => [],
		'choices' => array(
			'name' => 'boldgrid_controls',
			'type' => 'DeviceVisibility',
			'settings' => array(
				'responsive' => Boldgrid_Framework_Customizer_Generic::$device_sizes,
				'control' => array(
					'selectors' => array( '#main-menu' ),
				),
			),
		),
	),

	/* Start: Main Menu Border */
	'bgtfw_menu_border_main' => array(
		'type'        => 'kirki-generic',
		'transport'   => 'postMessage',
		'section'     => 'bgtfw_menu_border_main',
		'settings'    => 'bgtfw_menu_border_main',
		'label'       => '',
		'default'     => '',
		'choices' => array(
			'name' => 'boldgrid_controls',
			'type' => 'Border',
			'settings' => array(
				'responsive' => Boldgrid_Framework_Customizer_Generic::$device_sizes,
				'control' => array(
					'selectors' => array( '#main-menu' ),
				),
			),
		),
	),
	'bgtfw_menu_border_color_main' => array(
		'type'        => 'bgtfw-palette-selector',
		'transport'   => 'postMessage',
		'settings'    => 'bgtfw_menu_border_color_main',
		'label'       => esc_attr__( 'Border Color', 'bgtfw' ),
		'section'     => 'bgtfw_menu_border_main',
		'default'     => 'color-3',
		'choices'     => array(
			'colors'  => $formatted_palette,
			'size'    => $palette->get_palette_size( $formatted_palette ),
		),
		'sanitize_callback' => array( $sanitize, 'sanitize_palette_selector' ),
	),
	'bgtfw_menu_border_radius_main' => array(
		'type'        => 'kirki-generic',
		'transport'   => 'postMessage',
		'section'     => 'bgtfw_menu_border_main',
		'settings'    => 'bgtfw_menu_border_radius_main',
		'label'       => '',
		'default'     => '',
		'choices' => array(
			'name' => 'boldgrid_controls',
			'type' => 'BorderRadius',
			'settings' => array(
				'responsive' => Boldgrid_Framework_Customizer_Generic::$device_sizes,
				'control' => array(
					'selectors' => array( '#main-menu' ),
				),
			),
		),
	),

	/* End: Main Menu Border */
	'bgtfw_menu_items_border_main' => array(
		'type'        => 'kirki-generic',
		'transport'   => 'postMessage',
		'section'     => 'bgtfw_menu_items_border_main',
		'settings'    => 'bgtfw_menu_items_border_main',
		'label'       => '',
		'default'     => '',
		'choices' => array(
			'name' => 'boldgrid_controls',
			'type' => 'Border',
			'settings' => array(
				'responsive' => Boldgrid_Framework_Customizer_Generic::$device_sizes,
				'control' => array(
					'selectors' => array( '#main-menu > li:not(.current-menu-item)' ),
				),
			),
		),
	),
	'bgtfw_menu_items_border_color_main' => array(
		'type'        => 'bgtfw-palette-selector',
		'transport'   => 'postMessage',
		'settings'    => 'bgtfw_menu_items_border_color_main',
		'label'       => esc_attr__( 'Primary Color', 'bgtfw' ),
		'section'     => 'bgtfw_menu_items_border_main',
		'default'     => 'color-3',
		'choices'     => array(
			'colors'  => $formatted_palette,
			'size'    => $palette->get_palette_size( $formatted_palette ),
		),
		'sanitize_callback' => array( $sanitize, 'sanitize_palette_selector' ),
	),
	'bgtfw_menu_items_border_radius_main' => array(
		'type'        => 'kirki-generic',
		'transport'   => 'postMessage',
		'section'     => 'bgtfw_menu_items_border_main',
		'settings'    => 'bgtfw_menu_items_border_radius_main',
		'label'       => '',
		'default'     => '',
		'choices' => array(
			'name' => 'boldgrid_controls',
			'type' => 'BorderRadius',
			'settings' => array(
				'responsive' => Boldgrid_Framework_Customizer_Generic::$device_sizes,
				'control' => array(
					'selectors' => array( '#main-menu > li:not(.current-menu-item)' ),
				),
			),
		),
	),
	'bgtfw_menu_items_spacing_main' => array(
		'type'        => 'kirki-generic',
		'transport'   => 'postMessage',
		'section'     => 'bgtfw_menu_items_spacing_main',
		'settings'    => 'bgtfw_menu_items_spacing_main',
		'label'       => '',
		'default'     => '',
		'choices' => array(
			'name' => 'boldgrid_controls',
			'type' => 'Margin',
			'settings' => array(
				'responsive' => Boldgrid_Framework_Customizer_Generic::$device_sizes,
				'control' => array(
					'selectors' => array( '#main-menu > li' ),
					'sliders' => array(
						array( 'name' => 'top', 'label' => 'Top', 'cssProperty' => 'margin-top' ),
						array( 'name' => 'right', 'label' => 'Right', 'cssProperty' => 'margin-right' ),
						array( 'name' => 'bottom', 'label' => 'Bottom', 'cssProperty' => 'margin-bottom' ),
						array( 'name' => 'left', 'label' => 'Left', 'cssProperty' => 'margin-left' ),
					),
				),
			),
		),
	),
	'bgtfw_menu_items_hover_color_main' => array(
		'type'        => 'bgtfw-palette-selector',
		'transport'   => 'postMessage',
		'settings'    => 'bgtfw_menu_items_hover_color_main',
		'label'       => esc_attr__( 'Primary Color', 'bgtfw' ),
		'section'     => 'bgtfw_menu_items_hover_item_main',
		'default'     => 'color-4',
		'choices'     => array(
			'colors'  => $formatted_palette,
			'size'    => $palette->get_palette_size( $formatted_palette ),
		),
		'sanitize_callback' => array( $sanitize, 'sanitize_palette_selector' ),
	),
	'bgtfw_menu_items_hover_background_main' => array(
		'type'        => 'bgtfw-palette-selector',
		'transport'   => 'postMessage',
		'settings'    => 'bgtfw_menu_items_hover_background_main',
		'label'       => esc_attr__( 'Secondary Color', 'bgtfw' ),
		'section'     => 'bgtfw_menu_items_hover_item_main',
		'default'     => 'color-3',
		'choices'     => array(
			'colors'  => $formatted_palette,
			'size'    => $palette->get_palette_size( $formatted_palette ),
		),
		'sanitize_callback' => array( $sanitize, 'sanitize_palette_selector' ),
	),
	'bgtfw_menu_items_hover_effect_main' => array(
		'type'        => 'select',
		'transport'   => 'postMessage',
		'settings'    => 'bgtfw_menu_items_hover_effect_main',
		'label'       => esc_attr__( 'Hover Effect', 'bgtfw' ),
		'section'     => 'bgtfw_menu_items_hover_item_main',
		'default'     => 'hvr-underline-reveal',
		'sanitize_callback' => 'sanitize_html_class',
		'choices'     => array(

			/** No Effects */
			'' => esc_attr__( 'No Hover Effects', 'bgtfw' ),

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
					'hvr-fade' => esc_attr__( 'Fade', 'bgtfw' ),
					'hvr-sweep-to-right' => esc_attr__( 'Sweep to Right', 'bgtfw' ),
					'hvr-sweep-to-left' => esc_attr__( 'Sweep to Left', 'bgtfw' ),
					'hvr-sweep-to-bottom' => esc_attr__( 'Sweep to Bottom', 'bgtfw' ),
					'hvr-sweep-to-top' => esc_attr__( 'Sweep to Top', 'bgtfw' ),
					'hvr-bounce-to-right' => esc_attr__( 'Bounce to Right', 'bgtfw' ),
					'hvr-bounce-to-left' => esc_attr__( 'Bounce to Left', 'bgtfw' ),
					'hvr-bounce-to-bottom' => esc_attr__( 'Bounce to Bottom', 'bgtfw' ),
					'hvr-bounce-to-top' => esc_attr__( 'Bounce to Top', 'bgtfw' ),

				),
			),

			/** Two Color Background Transitions */
			'optgroup2' => array(
				esc_attr__( 'Two Color Transitions', 'bgtfw' ),
				array(
					'hvr-radial-in' => esc_attr__( 'Radial In', 'bgtfw' ),
					'hvr-radial-out' => esc_attr__( 'Radial Out', 'bgtfw' ),
					'hvr-rectangle-in' => esc_attr__( 'Rectangle In', 'bgtfw' ),
					'hvr-rectangle-out' => esc_attr__( 'Rectangle Out', 'bgtfw' ),
					'hvr-shutter-in-horizontal' => esc_attr__( 'Shutter In Horizontal', 'bgtfw' ),
					'hvr-shutter-in-vertical' => esc_attr__( 'Shutter In Vertical', 'bgtfw' ),
					'hvr-shutter-out-horizontal' => esc_attr__( 'Shutter Out Horizontal', 'bgtfw' ),
					'hvr-shutter-out-vertical' => esc_attr__( 'Shutter Out Vertical', 'bgtfw' ),
				),
			),

			/** Border Effects */
			'optgroup3' => array(
				esc_attr__( 'Border Effects', 'bgtfw' ),
				array(
					'hvr-trim' => esc_attr__( 'Trim', 'bgtfw' ),
					'hvr-ripple-out' => esc_attr__( 'Ripple Out', 'bgtfw' ),
					'hvr-ripple-in' => esc_attr__( 'Ripple In', 'bgtfw' ),
					'hvr-outline-out' => esc_attr__( 'Outline Out', 'bgtfw' ),
					'hvr-outline-in' => esc_attr__( 'Outline In', 'bgtfw' ),
				),
			),
			'optgroup4' => array(
				esc_attr__( 'Overline/Underline Effects', 'bgtfw' ),
				array(
					'hvr-underline-from-left' => esc_attr__( 'Underline From Left', 'bgtfw' ),
					'hvr-underline-from-center' => esc_attr__( 'Underline From Center', 'bgtfw' ),
					'hvr-underline-from-right' => esc_attr__( 'Underline From Right', 'bgtfw' ),
					'hvr-underline-reveal' => esc_attr__( 'Underline Reveal', 'bgtfw' ),
					'hvr-overline-reveal' => esc_attr__( 'Overline Reveal', 'bgtfw' ),
					'hvr-overline-from-left' => esc_attr__( 'Overline From Left', 'bgtfw' ),
					'hvr-overline-from-center' => esc_attr__( 'Overline From Center', 'bgtfw' ),
					'hvr-overline-from-right' => esc_attr__( 'Overline From Right', 'bgtfw' ),
				),
			),
		),
	),

	'bgtfw_menu_items_link_color_main' => array(
		'type'        => 'bgtfw-palette-selector',
		'transport' => 'postMessage',
		'settings'    => 'bgtfw_menu_items_link_color_main',
		'label' => esc_attr__( 'Link Color', 'bgtfw' ),
		'section'     => 'bgtfw_menu_items_link_color_main',
		'priority' => 1,
		'default'     => 'color-1',
		'choices'     => array(
			'colors' => $formatted_palette,
			'size' => $palette->get_palette_size( $formatted_palette ),
		),
		'sanitize_callback' => array( $sanitize, 'sanitize_palette_selector' ),
	),

	'bgtfw_menu_items_active_link_color_main' => array(
		'type'        => 'bgtfw-palette-selector',
		'transport' => 'postMessage',
		'settings'    => 'bgtfw_menu_items_active_link_color_main',
		'label' => esc_attr__( 'Color', 'bgtfw' ),
		'section'     => 'bgtfw_menu_items_active_link_color_main',
		'priority' => 1,
		'default'  => 'color-4',
		'choices'     => array(
			'colors' => $formatted_palette,
			'size' => $palette->get_palette_size( $formatted_palette ),
		),
		'sanitize_callback' => array( $sanitize, 'sanitize_palette_selector' ),
	),

	'bgtfw_menu_items_active_link_background_main' => array(
		'type'        => 'bgtfw-palette-selector',
		'transport' => 'postMessage',
		'settings'    => 'bgtfw_menu_items_active_link_background_main',
		'label' => esc_attr__( 'Color', 'bgtfw' ),
		'section'     => 'bgtfw_menu_items_active_link_background_main',
		'priority' => 1,
		'default'  => 'transparent',
		'choices'     => array(
			'colors' => $formatted_palette,
			'size' => $palette->get_palette_size( $formatted_palette, true ),
			'transparent' => true,
		),
		'sanitize_callback' => array( $sanitize, 'sanitize_palette_selector' ),
	),

	'bgtfw_menu_items_active_link_border_main' => array(
		'type'        => 'kirki-generic',
		'transport'   => 'postMessage',
		'section'     => 'bgtfw_menu_items_active_link_border_main',
		'settings'    => 'bgtfw_menu_items_active_link_border_main',
		'label'       => '',
		'default'     => '',
		'choices' => array(
			'name' => 'boldgrid_controls',
			'type' => 'Border',
			'settings' => array(
				'responsive' => Boldgrid_Framework_Customizer_Generic::$device_sizes,
				'control' => array(
					'selectors' => array( '#main-menu > li.current-menu-item' ),
				),
			),
		),
	),
	'bgtfw_menu_items_active_link_border_color_main' => array(
		'type'        => 'bgtfw-palette-selector',
		'transport'   => 'postMessage',
		'settings'    => 'bgtfw_menu_items_active_link_border_color_main',
		'label'       => esc_attr__( 'Primary Color', 'bgtfw' ),
		'section'     => 'bgtfw_menu_items_active_link_border_main',
		'default'     => 'color-3',
		'choices'     => array(
			'colors'  => $formatted_palette,
			'size'    => $palette->get_palette_size( $formatted_palette ),
		),
		'sanitize_callback' => array( $sanitize, 'sanitize_palette_selector' ),
	),
	'bgtfw_menu_items_active_link_border_radius_main' => array(
		'type'        => 'kirki-generic',
		'transport'   => 'postMessage',
		'section'     => 'bgtfw_menu_items_active_link_border_main',
		'settings'    => 'bgtfw_menu_items_active_link_border_radius_main',
		'label'       => '',
		'default'     => '',
		'choices' => array(
			'name' => 'boldgrid_controls',
			'type' => 'BorderRadius',
			'settings' => array(
				'responsive' => Boldgrid_Framework_Customizer_Generic::$device_sizes,
				'control' => array(
					'selectors' => array( '#main-menu > li.current-menu-item' ),
				),
			),
		),
	),

	/** Menu Typography */
	'bgtfw_menu_typography_main' => array(
		'type'     => 'typography',
		'transport'   => 'postMessage',
		'settings'    => 'bgtfw_menu_typography_main',
		'label'       => esc_attr__( 'Typography', 'bgtfw' ),
		'section'     => 'bgtfw_menu_typography_main',
		'default'     => array(
			'font-family'    => 'Roboto',
			'variant'        => 'regular',
			'font-size'      => '18px',
			'line-height'    => '1.5',
			'letter-spacing' => '0',
			'subsets'        => array( 'latin-ext' ),
			'text-transform' => 'uppercase',
		),
		'priority'    => 20,
		'output'      => array(
			array(
				'element'  => '#main-menu li a',
			),
		),
	),

	/*** End: Dynamic Menu Controls ***/
);

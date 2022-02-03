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
	'bgtfw_posts_tags_display'                  => array(
		'type'              => 'radio-buttonset',
		'transport'         => 'auto',
		'settings'          => 'bgtfw_posts_tags_display',
		'label'             => esc_attr__( 'Display', 'bgtfw' ),
		'tooltip'           => __( 'Toggle the display of your tags on the blog roll and archive pages.', 'bgtfw' ),
		'section'           => 'bgtfw_pages_blog_posts_tags_links',
		'default'           => 'block',
		'choices'           => array(
			'block' => '<span class="dashicons dashicons-visibility"></span>' . __( 'Show', 'bgtfw' ),
			'none'  => '<span class="dashicons dashicons-hidden"></span>' . __( 'Hide', 'bgtfw' ),
		),
		'sanitize_callback' => function( $value, $settings ) {
			return in_array( $value, array( 'block', 'none' ), true ) ? $value : $settings->default;
		},
		'output'            => array(
			array(
				'element'  => '.single .entry-footer .tags-links',
				'property' => 'display',
			),
		),
	),

	// Start: Posts Tags Links Color Controls.
	'bgtfw_posts_tags_link_color_display'       => array(
		'type'              => 'radio-buttonset',
		'transport'         => 'postMessage',
		'settings'          => 'bgtfw_posts_tags_link_color_display',
		'label'             => esc_attr__( 'Colors', 'bgtfw' ),
		'section'           => 'bgtfw_pages_blog_posts_tags_links',
		'default'           => 'inherit',
		'choices'           => array(
			'inherit' => '<span class="dashicons dashicons-admin-site"></span>' . __( 'Global Color', 'bgtfw' ),
			'custom'  => '<span class="dashicons dashicons-admin-customizer"></span>' . __( 'Custom', 'bgtfw' ),
		),
		'sanitize_callback' => function( $value, $settings ) {
			return in_array( $value, array( 'inherit', 'custom' ), true ) ? $value : $settings->default;
		},
		'active_callback'   => array(
			array(
				'setting'  => 'bgtfw_posts_tags_display',
				'operator' => '!==',
				'value'    => 'none',
			),
		),
	),
	'bgtfw_posts_tags_link_color'               => array(
		'type'              => 'bgtfw-palette-selector',
		'transport'         => 'postMessage',
		'settings'          => 'bgtfw_posts_tags_link_color',
		'label'             => esc_attr__( 'Link Color', 'bgtfw' ),
		'section'           => 'bgtfw_pages_blog_posts_tags_links',
		'default'           => 'color-1',
		'choices'           => array(
			'selectors' => array( '.single .entry-footer .tags-links a' ),
			'colors'    => $bgtfw_formatted_palette,
			'size'      => $bgtfw_palette->get_palette_size( $bgtfw_formatted_palette ),
		),
		'sanitize_callback' => array( $bgtfw_color_sanitize, 'sanitize_palette_selector' ),
		'active_callback'   => array(
			array(
				'setting'  => 'bgtfw_posts_tags_display',
				'operator' => '!==',
				'value'    => 'none',
			),
			array(
				'setting'  => 'bgtfw_posts_tags_link_color_display',
				'operator' => '!==',
				'value'    => 'inherit',
			),
		),
	),
	'bgtfw_posts_tags_link_decoration'          => array(
		'settings'          => 'bgtfw_posts_tags_link_decoration',
		'transport'         => 'postMessage',
		'label'             => esc_html__( 'Text Style', 'bgtfw' ),
		'type'              => 'radio-buttonset',
		'section'           => 'bgtfw_pages_blog_posts_tags_links',
		'default'           => 'none',
		'choices'           => array(
			'none'      => '<span class="dashicons dashicons-editor-textcolor"></span>' . __( 'Normal', 'bgtfw' ),
			'underline' => '<span class="dashicons dashicons-editor-underline"></span>' . __( 'Underline', 'bgtfw' ),
		),
		'sanitize_callback' => function( $value, $settings ) {
			return in_array( $value, array( 'none', 'underline' ), true ) ? $value : $settings->default;
		},
		'active_callback'   => array(
			array(
				'setting'  => 'bgtfw_posts_tags_display',
				'operator' => '!==',
				'value'    => 'none',
			),
			array(
				'setting'  => 'bgtfw_posts_tags_link_color_display',
				'operator' => '!==',
				'value'    => 'inherit',
			),
		),
	),
	'bgtfw_posts_tags_link_color_hover'         => array(
		'type'            => 'slider',
		'transport'       => 'postMessage',
		'settings'        => 'bgtfw_posts_tags_link_color_hover',
		'label'           => esc_attr__( 'Hover Color Brightness', 'bgtfw' ),
		'section'         => 'bgtfw_pages_blog_posts_tags_links',
		'default'         => -25,
		'choices'         => array(
			'min'  => '-25',
			'max'  => '25',
			'step' => '1',
		),
		'active_callback' => array(
			array(
				'setting'  => 'bgtfw_posts_tags_display',
				'operator' => '!==',
				'value'    => 'none',
			),
			array(
				'setting'  => 'bgtfw_posts_tags_link_color_display',
				'operator' => '!==',
				'value'    => 'inherit',
			),
		),
	),
	'bgtfw_posts_tags_decoration_hover'         => array(
		'settings'          => 'bgtfw_posts_tags_link_decoration_hover',
		'transport'         => 'postMessage',
		'label'             => esc_html__( 'Hover Text Style', 'bgtfw' ),
		'type'              => 'radio-buttonset',
		'section'           => 'bgtfw_pages_blog_posts_tags_links',
		'default'           => 'none',
		'choices'           => array(
			'none'      => '<span class="dashicons dashicons-editor-textcolor"></span>' . __( 'None', 'bgtfw' ),
			'underline' => '<span class="dashicons dashicons-editor-underline"></span>' . __( 'Underline', 'bgtfw' ),
		),
		'sanitize_callback' => function( $value, $settings ) {
			return in_array( $value, array( 'none', 'underline' ), true ) ? $value : $settings->default;
		},
		'active_callback'   => array(
			array(
				'setting'  => 'bgtfw_posts_tags_display',
				'operator' => '!==',
				'value'    => 'none',
			),
			array(
				'setting'  => 'bgtfw_posts_tags_link_color_display',
				'operator' => '!==',
				'value'    => 'inherit',
			),
		),
	),

	// Start Tag Icons.
	'bgtfw_posts_tags_icon_display'             => array(
		'type'              => 'radio-buttonset',
		'transport'         => 'auto',
		'settings'          => 'bgtfw_posts_tags_icon_display',
		'label'             => esc_attr__( 'Icon Display', 'bgtfw' ),
		'section'           => 'bgtfw_pages_blog_posts_tags_links',
		'default'           => 'inline-block',
		'choices'           => array(
			'inline-block' => '<span class="dashicons dashicons-visibility"></span>' . __( 'Show', 'bgtfw' ),
			'none'         => '<span class="dashicons dashicons-hidden"></span>' . __( 'Hide', 'bgtfw' ),
		),
		'sanitize_callback' => function( $value, $settings ) {
			return in_array( $value, array( 'inline-block', 'none' ), true ) ? $value : $settings->default;
		},
		'output'            => array(
			array(
				'element'  => '.single .entry-footer .tags-links .fa',
				'property' => 'display',
			),
		),
		'active_callback'   => array(
			array(
				'setting'  => 'bgtfw_posts_tags_display',
				'operator' => '!==',
				'value'    => 'none',
			),
		),
	),
	'bgtfw_posts_tag_icon'                      => array(
		'type'            => 'fontawesome',
		'transport'       => 'postMessage',
		'settings'        => 'bgtfw_posts_tag_icon',
		'label'           => esc_attr__( 'Single Tag Icon', 'bgtfw' ),
		'section'         => 'bgtfw_pages_blog_posts_tags_links',
		'default'         => 'hashtag',
		'js_vars'         => array(
			array(
				'element'       => '.single .tags-links.singular .fa',
				'function'      => 'html',
				'attr'          => 'class',
				'value_pattern' => 'fa fa-fw fa-$',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'bgtfw_posts_tags_display',
				'operator' => '!==',
				'value'    => 'none',
			),
			array(
				'setting'  => 'bgtfw_posts_tags_icon_display',
				'operator' => '!==',
				'value'    => 'none',
			),
		),
	),
	'bgtfw_posts_tags_icon'                     => array(
		'type'            => 'fontawesome',
		'transport'       => 'postMessage',
		'settings'        => 'bgtfw_posts_tags_icon',
		'label'           => esc_attr__( 'Multiple Tags Icon', 'bgtfw' ),
		'section'         => 'bgtfw_pages_blog_posts_tags_links',
		'default'         => 'hashtag',
		'js_vars'         => array(
			array(
				'element'       => '.single .tags-links.multiple .fa',
				'function'      => 'html',
				'attr'          => 'class',
				'value_pattern' => 'fa fa-fw fa-$',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'bgtfw_posts_tags_display',
				'operator' => '!==',
				'value'    => 'none',
			),
			array(
				'setting'  => 'bgtfw_posts_tags_icon_display',
				'operator' => '!==',
				'value'    => 'none',
			),
		),
	),

	'bgtfw_posts_cats_display'                  => array(
		'type'              => 'radio-buttonset',
		'transport'         => 'auto',
		'settings'          => 'bgtfw_posts_cats_display',
		'label'             => esc_attr__( 'Display', 'bgtfw' ),
		'section'           => 'bgtfw_pages_blog_posts_cat_links',
		'default'           => 'block',
		'choices'           => array(
			'block' => '<span class="dashicons dashicons-visibility"></span>' . __( 'Show', 'bgtfw' ),
			'none'  => '<span class="dashicons dashicons-hidden"></span>' . __( 'Hide', 'bgtfw' ),
		),
		'sanitize_callback' => function( $value, $settings ) {
			return in_array( $value, array( 'block', 'none' ), true ) ? $value : $settings->default;
		},
		'output'            => array(
			array(
				'element'  => '.single .entry-footer .cat-links',
				'property' => 'display',
			),
		),
	),

	// Start: Posts Category Links Color Controls.
	'bgtfw_posts_cats_link_color_display'       => array(
		'type'              => 'radio-buttonset',
		'transport'         => 'postMessage',
		'settings'          => 'bgtfw_posts_cats_link_color_display',
		'label'             => esc_attr__( 'Colors', 'bgtfw' ),
		'section'           => 'bgtfw_pages_blog_posts_cat_links',
		'default'           => 'inherit',
		'choices'           => array(
			'inherit' => '<span class="dashicons dashicons-admin-site"></span>' . __( 'Global Color', 'bgtfw' ),
			'custom'  => '<span class="dashicons dashicons-admin-customizer"></span>' . __( 'Custom', 'bgtfw' ),
		),
		'sanitize_callback' => function( $value, $settings ) {
			return in_array( $value, array( 'inherit', 'custom' ), true ) ? $value : $settings->default;
		},
		'active_callback'   => array(
			array(
				'setting'  => 'bgtfw_posts_cats_display',
				'operator' => '!==',
				'value'    => 'none',
			),
		),
	),
	'bgtfw_posts_cats_link_color'               => array(
		'type'              => 'bgtfw-palette-selector',
		'transport'         => 'postMessage',
		'settings'          => 'bgtfw_posts_cats_link_color',
		'label'             => esc_attr__( 'Link Color', 'bgtfw' ),
		'section'           => 'bgtfw_pages_blog_posts_cat_links',
		'default'           => 'color-1',
		'choices'           => array(
			'selectors' => array( '.single .entry-footer .cat-links a' ),
			'colors'    => $bgtfw_formatted_palette,
			'size'      => $bgtfw_palette->get_palette_size( $bgtfw_formatted_palette ),
		),
		'sanitize_callback' => array( $bgtfw_color_sanitize, 'sanitize_palette_selector' ),
		'active_callback'   => array(
			array(
				'setting'  => 'bgtfw_posts_cats_display',
				'operator' => '!==',
				'value'    => 'none',
			),
			array(
				'setting'  => 'bgtfw_posts_cats_link_color_display',
				'operator' => '!==',
				'value'    => 'inherit',
			),
		),
	),
	'bgtfw_posts_cats_link_decoration'          => array(
		'settings'          => 'bgtfw_posts_cats_link_decoration',
		'transport'         => 'postMessage',
		'label'             => esc_html__( 'Text Style', 'bgtfw' ),
		'type'              => 'radio-buttonset',
		'section'           => 'bgtfw_pages_blog_posts_cat_links',
		'default'           => 'none',
		'choices'           => array(
			'none'      => '<span class="dashicons dashicons-editor-textcolor"></span>' . __( 'Normal', 'bgtfw' ),
			'underline' => '<span class="dashicons dashicons-editor-underline"></span>' . __( 'Underline', 'bgtfw' ),
		),
		'sanitize_callback' => function( $value, $settings ) {
			return in_array( $value, array( 'none', 'underline' ), true ) ? $value : $settings->default;
		},
		'active_callback'   => array(
			array(
				'setting'  => 'bgtfw_posts_cats_display',
				'operator' => '!==',
				'value'    => 'none',
			),
			array(
				'setting'  => 'bgtfw_posts_cats_link_color_display',
				'operator' => '!==',
				'value'    => 'inherit',
			),
		),
	),
	'bgtfw_posts_cats_link_color_hover'         => array(
		'type'            => 'slider',
		'transport'       => 'postMessage',
		'settings'        => 'bgtfw_posts_cats_link_color_hover',
		'label'           => esc_attr__( 'Hover Color Brightness', 'bgtfw' ),
		'section'         => 'bgtfw_pages_blog_posts_cat_links',
		'default'         => -25,
		'choices'         => array(
			'min'  => '-25',
			'max'  => '25',
			'step' => '1',
		),
		'active_callback' => array(
			array(
				'setting'  => 'bgtfw_posts_cats_display',
				'operator' => '!==',
				'value'    => 'none',
			),
			array(
				'setting'  => 'bgtfw_posts_cats_link_color_display',
				'operator' => '!==',
				'value'    => 'inherit',
			),
		),
	),
	'bgtfw_posts_cats_decoration_hover'         => array(
		'settings'          => 'bgtfw_posts_cats_link_decoration_hover',
		'transport'         => 'postMessage',
		'label'             => esc_html__( 'Hover Text Style', 'bgtfw' ),
		'type'              => 'radio-buttonset',
		'section'           => 'bgtfw_pages_blog_posts_cat_links',
		'default'           => 'none',
		'choices'           => array(
			'none'      => '<span class="dashicons dashicons-editor-textcolor"></span>' . __( 'None', 'bgtfw' ),
			'underline' => '<span class="dashicons dashicons-editor-underline"></span>' . __( 'Underline', 'bgtfw' ),
		),
		'sanitize_callback' => function( $value, $settings ) {
			return in_array( $value, array( 'none', 'underline' ), true ) ? $value : $settings->default;
		},
		'active_callback'   => array(
			array(
				'setting'  => 'bgtfw_posts_cats_display',
				'operator' => '!==',
				'value'    => 'none',
			),
			array(
				'setting'  => 'bgtfw_posts_cats_link_color_display',
				'operator' => '!==',
				'value'    => 'inherit',
			),
		),
	),

	// Start: Category Icons.
	'bgtfw_posts_cats_icon_display'             => array(
		'type'              => 'radio-buttonset',
		'transport'         => 'auto',
		'settings'          => 'bgtfw_posts_cats_icon_display',
		'label'             => esc_attr__( 'Icon Display', 'bgtfw' ),
		'section'           => 'bgtfw_pages_blog_posts_cat_links',
		'default'           => 'inline-block',
		'choices'           => array(
			'inline-block' => '<span class="dashicons dashicons-visibility"></span>' . __( 'Show', 'bgtfw' ),
			'none'         => '<span class="dashicons dashicons-hidden"></span>' . __( 'Hide', 'bgtfw' ),
		),
		'sanitize_callback' => function( $value, $settings ) {
			return in_array( $value, array( 'inline-block', 'none' ), true ) ? $value : $settings->default;
		},
		'output'            => array(
			array(
				'element'  => '.single .entry-footer .cat-links .fa',
				'property' => 'display',
			),
		),
	),
	'bgtfw_posts_cat_icon'                      => array(
		'type'            => 'fontawesome',
		'transport'       => 'postMessage',
		'settings'        => 'bgtfw_posts_cat_icon',
		'label'           => esc_attr__( 'Single Category Icon', 'bgtfw' ),
		'section'         => 'bgtfw_pages_blog_posts_cat_links',
		'default'         => 'folder',
		'js_vars'         => array(
			array(
				'element'       => '.single .cat-links.singular .fa',
				'function'      => 'html',
				'attr'          => 'class',
				'value_pattern' => 'fa fa-fw fa-$',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'bgtfw_posts_cats_display',
				'operator' => '!==',
				'value'    => 'none',
			),
			array(
				'setting'  => 'bgtfw_posts_cats_icon_display',
				'operator' => '!==',
				'value'    => 'none',
			),
		),
	),
	'bgtfw_posts_cats_icon'                     => array(
		'type'            => 'fontawesome',
		'transport'       => 'postMessage',
		'settings'        => 'bgtfw_posts_cats_icon',
		'label'           => esc_attr__( 'Multiple Categories Icon', 'bgtfw' ),
		'section'         => 'bgtfw_pages_blog_posts_cat_links',
		'default'         => 'folder-open',
		'js_vars'         => array(
			array(
				'element'       => '.single .cat-links.multiple .fa',
				'function'      => 'html',
				'attr'          => 'class',
				'value_pattern' => 'fa fa-fw fa-$',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'bgtfw_posts_cats_display',
				'operator' => '!==',
				'value'    => 'none',
			),
			array(
				'setting'  => 'bgtfw_posts_cats_icon_display',
				'operator' => '!==',
				'value'    => 'none',
			),
		),
	),

	'bgtfw_posts_navigation_display'            => array(
		'type'              => 'radio-buttonset',
		'transport'         => 'auto',
		'settings'          => 'bgtfw_posts_navigation_display',
		'label'             => esc_attr__( 'Display', 'bgtfw' ),
		'section'           => 'bgtfw_pages_blog_posts_navigation_links',
		'default'           => 'flex',
		'choices'           => array(
			'flex' => '<span class="dashicons dashicons-visibility"></span>' . __( 'Show', 'bgtfw' ),
			'none' => '<span class="dashicons dashicons-hidden"></span>' . __( 'Hide', 'bgtfw' ),
		),
		'sanitize_callback' => function( $value, $settings ) {
			return in_array( $value, array( 'flex', 'none' ), true ) ? $value : $settings->default;
		},
		'output'            => array(
			array(
				'element'  => '.single .post-navigation',
				'property' => 'display',
			),
		),
		'edit_vars'         => array(
			array(
				'selector'    => '.single .post-navigation',
				'label'       => __( 'Post Navigation Links', 'bgtfw' ),
				'description' => __( 'Choose whether or not to display post navigation links', 'bgtfw' ),
			),
		),
	),

	// Start: Posts Navigation Link Color Controls.
	'bgtfw_posts_navigation_link_color_display' => array(
		'type'              => 'radio-buttonset',
		'transport'         => 'postMessage',
		'settings'          => 'bgtfw_posts_navigation_link_color_display',
		'label'             => esc_attr__( 'Colors', 'bgtfw' ),
		'section'           => 'bgtfw_pages_blog_posts_navigation_links',
		'default'           => 'inherit',
		'choices'           => array(
			'inherit' => '<span class="dashicons dashicons-admin-site"></span>' . __( 'Global Color', 'bgtfw' ),
			'custom'  => '<span class="dashicons dashicons-admin-customizer"></span>' . __( 'Custom', 'bgtfw' ),
		),
		'sanitize_callback' => function( $value, $settings ) {
			return in_array( $value, array( 'inherit', 'custom' ), true ) ? $value : $settings->default;
		},
		'active_callback'   => array(
			array(
				'setting'  => 'bgtfw_posts_navigation_display',
				'operator' => '!==',
				'value'    => 'none',
			),
		),
	),
	'bgtfw_posts_navigation_link_color'         => array(
		'type'              => 'bgtfw-palette-selector',
		'transport'         => 'postMessage',
		'settings'          => 'bgtfw_posts_navigation_link_color',
		'label'             => esc_attr__( 'Link Color', 'bgtfw' ),
		'section'           => 'bgtfw_pages_blog_posts_navigation_links',
		'default'           => 'color-1',
		'choices'           => array(
			'selectors' => array( '.single .post-navigation a' ),
			'colors'    => $bgtfw_formatted_palette,
			'size'      => $bgtfw_palette->get_palette_size( $bgtfw_formatted_palette ),
		),
		'sanitize_callback' => array( $bgtfw_color_sanitize, 'sanitize_palette_selector' ),
		'active_callback'   => array(
			array(
				'setting'  => 'bgtfw_posts_navigation_display',
				'operator' => '!==',
				'value'    => 'none',
			),
			array(
				'setting'  => 'bgtfw_posts_navigation_link_color_display',
				'operator' => '!==',
				'value'    => 'inherit',
			),
		),
	),
	'bgtfw_posts_navigation_link_decoration'    => array(
		'settings'          => 'bgtfw_posts_navigation_link_decoration',
		'transport'         => 'postMessage',
		'label'             => esc_html__( 'Text Style', 'bgtfw' ),
		'type'              => 'radio-buttonset',
		'section'           => 'bgtfw_pages_blog_posts_navigation_links',
		'default'           => 'none',
		'choices'           => array(
			'none'      => '<span class="dashicons dashicons-editor-textcolor"></span>' . __( 'Normal', 'bgtfw' ),
			'underline' => '<span class="dashicons dashicons-editor-underline"></span>' . __( 'Underline', 'bgtfw' ),
		),
		'sanitize_callback' => function( $value, $settings ) {
			return in_array( $value, array( 'none', 'underline' ), true ) ? $value : $settings->default;
		},
		'active_callback'   => array(
			array(
				'setting'  => 'bgtfw_posts_navigation_display',
				'operator' => '!==',
				'value'    => 'none',
			),
			array(
				'setting'  => 'bgtfw_posts_navigation_link_color_display',
				'operator' => '!==',
				'value'    => 'inherit',
			),
		),
	),
	'bgtfw_posts_navigation_link_color_hover'   => array(
		'type'            => 'slider',
		'transport'       => 'postMessage',
		'settings'        => 'bgtfw_posts_navigation_link_color_hover',
		'label'           => esc_attr__( 'Hover Color Brightness', 'bgtfw' ),
		'section'         => 'bgtfw_pages_blog_posts_navigation_links',
		'default'         => -25,
		'choices'         => array(
			'min'  => '-25',
			'max'  => '25',
			'step' => '1',
		),
		'active_callback' => array(
			array(
				'setting'  => 'bgtfw_posts_navigation_display',
				'operator' => '!==',
				'value'    => 'none',
			),
			array(
				'setting'  => 'bgtfw_posts_navigation_link_color_display',
				'operator' => '!==',
				'value'    => 'inherit',
			),
		),
	),
	'bgtfw_posts_navigation_decoration_hover'   => array(
		'settings'          => 'bgtfw_posts_navigation_link_decoration_hover',
		'transport'         => 'postMessage',
		'label'             => esc_html__( 'Hover Text Style', 'bgtfw' ),
		'type'              => 'radio-buttonset',
		'section'           => 'bgtfw_pages_blog_posts_navigation_links',
		'default'           => 'none',
		'choices'           => array(
			'none'      => '<span class="dashicons dashicons-editor-textcolor"></span>' . __( 'None', 'bgtfw' ),
			'underline' => '<span class="dashicons dashicons-editor-underline"></span>' . __( 'Underline', 'bgtfw' ),
		),
		'sanitize_callback' => function( $value, $settings ) {
			return in_array( $value, array( 'none', 'underline' ), true ) ? $value : $settings->default;
		},
		'active_callback'   => array(
			array(
				'setting'  => 'bgtfw_posts_navigation_display',
				'operator' => '!==',
				'value'    => 'none',
			),
			array(
				'setting'  => 'bgtfw_posts_navigation_link_color_display',
				'operator' => '!==',
				'value'    => 'inherit',
			),
		),
	),
	'bgtfw_blog_posts_container'                => array(
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
	'bgtfw_blog_posts_container_width'          => array(
		'type'              => 'kirki-generic',
		'transport'         => 'postMessage',
		'section'           => 'bgtfw_pages_blog_posts_container',
		'settings'          => 'bgtfw_blog_posts_container_width',
		'priority'          => 40,
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
					'selectors' => array( 'body.single .container' ),
					'name'      => 'bgtfw_blog_posts_container_width',
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
	'bgtfw_blog_posts_container_max_width'      => array(
		'type'              => 'kirki-generic',
		'transport'         => 'postMessage',
		'section'           => 'bgtfw_pages_blog_posts_container',
		'settings'          => 'bgtfw_blog_posts_container_max_width',
		'label'             => 'Container Max Width',
		'default'           => $bgtfw_generic->get_width_defaults( 'max-width' ),
		'priority'          => 40,
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
					'selectors' => array( 'body.single .container' ),
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
	'bgtfw_layout_blog'                         => array(
		'settings'          => 'bgtfw_layout_blog',
		'label'             => esc_html__( 'Sidebar Display', 'bgtfw' ),
		'type'              => 'radio',
		'priority'          => 10,
		'default'           => 'no-sidebar',
		'choices'           => array(),
		'section'           => 'bgtfw_pages_blog_posts_sidebar',
		'sanitize_callback' => 'sanitize_html_class',
	),
	'bgtfw_post_header_feat_image_display'      => array(
		'type'              => 'radio-buttonset',
		'transport'         => 'auto',
		'settings'          => 'bgtfw_post_header_feat_image_display',
		'label'             => esc_attr__( 'Display', 'bgtfw' ),
		'tooltip'           => __( 'Hide or show your featured image on your blog posts.', 'bgtfw' ),
		'section'           => 'bgtfw_pages_blog_posts_featured_images',
		'default'           => 'show',
		'choices'           => array(
			'show' => '<span class="dashicons dashicons-visibility"></span>' . __( 'Show', 'bgtfw' ),
			'hide' => '<span class="dashicons dashicons-hidden"></span>' . __( 'Hide', 'bgtfw' ),
		),
		'sanitize_callback' => function( $value, $settings ) {
			return in_array( $value, array( 'show', 'hide' ), true ) ? $value : $settings->default;
		},
	),
	'bgtfw_post_header_feat_image_position'     => array(
		'type'              => 'radio-buttonset',
		'transport'         => 'auto',
		'settings'          => 'bgtfw_post_header_feat_image_position',
		'label'             => esc_attr__( 'Position', 'bgtfw' ),
		'tooltip'           => __( 'Change where your featured image appears on your blog posts.', 'bgtfw' ),
		'section'           => 'bgtfw_pages_blog_posts_featured_images',
		'default'           => 'background',
		'choices'           => array(
			'background' => '<span class="dashicons dashicons-format-image"></span>' . __( 'Header Background', 'bgtfw' ),
			'below'      => '<span class="dashicons dashicons-arrow-down-alt"></span>' . __( 'Below Header', 'bgtfw' ),
		),
		'sanitize_callback' => function( $value, $settings ) {
			return in_array( $value, array( 'background', 'below' ), true ) ? $value : $settings->default;
		},
		'active_callback'   => array(
			array(
				'setting'  => 'bgtfw_post_header_feat_image_display',
				'operator' => '!==',
				'value'    => 'hide',
			),
		),
	),
	'bgtfw_post_header_feat_image_size'         => array(
		'type'              => 'radio-buttonset',
		'transport'         => 'auto',
		'settings'          => 'bgtfw_post_header_feat_image_size',
		'label'             => esc_attr__( 'Size', 'bgtfw' ),
		'tooltip'           => __( 'Change the size of your featured images. Due to container sizes, very large images may now show the full size when left or right aligned', 'bgtfw' ),
		'section'           => 'bgtfw_pages_blog_posts_featured_images',
		'default'           => 'medium',
		'choices'           => array(
			'thumbnail' => __( 'Thumbnail', 'bgtfw' ),
			'medium'    => __( 'Medium', 'bgtfw' ),
			'large'     => __( 'Large', 'bgtfw' ),
			'full'      => __( 'Full', 'bgtfw' ),
		),
		'sanitize_callback' => function( $value, $settings ) {
			return in_array( $value, array( 'thumbnail', 'medium', 'large', 'full' ), true ) ? $value : $settings->default;
		},
		'active_callback'   => array(
			array(
				'setting'  => 'bgtfw_post_header_feat_image_display',
				'operator' => '!==',
				'value'    => 'hide',
			),
			array(
				'setting'  => 'bgtfw_post_header_feat_image_position',
				'operator' => '!==',
				'value'    => 'background',
			),
		),
	),
	'bgtfw_post_header_feat_image_align'        => array(
		'type'              => 'radio-buttonset',
		'transport'         => 'auto',
		'settings'          => 'bgtfw_post_header_feat_image_align',
		'label'             => esc_attr__( 'Alignment', 'bgtfw' ),
		'tooltip'           => __( 'Change the alignment of your image.', 'bgtfw' ),
		'section'           => 'bgtfw_pages_blog_posts_featured_images',
		'default'           => 'alignleft',
		'choices'           => array(
			'alignnone'   => __( 'None', 'bgtfw' ),
			'alignleft'   => __( 'Left', 'bgtfw' ),
			'aligncenter' => __( 'Center', 'bgtfw' ),
			'alignright'  => __( 'Right', 'bgtfw' ),
		),
		'sanitize_callback' => function( $value, $settings ) {
			return in_array( $value, array( 'alignnone', 'alignleft', 'aligncenter', 'alignright' ), true ) ? $value : $settings->default;
		},
		'active_callback'   => array(
			array(
				'setting'  => 'bgtfw_post_header_feat_image_display',
				'operator' => '!==',
				'value'    => 'hide',
			),
			array(
				'setting'  => 'bgtfw_post_header_feat_image_position',
				'operator' => '!==',
				'value'    => 'background',
			),
		),
	),
);

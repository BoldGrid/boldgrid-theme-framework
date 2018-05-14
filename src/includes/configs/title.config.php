<?php
/**
 * Title configuration for bgtfw.
 *
 * @package Boldgrid_Theme_Framework
 * @subpackage Boldgrid_Theme_Framework\Configs
 *
 * @since 2.0.0
 *
 * @return array An array of title configs used in the bgtfw.
 */



return array(
	// Post meta data that determines if title should show on individual page.
	'hide' => 'boldgrid_hide_page_title',

	// Theme mod, determines if title should display on pages.
	'page' => 'bgtfw_pages_display_title',

	// Theme mod, determines if title should display on posts.
	'post' => 'bgtfw_posts_display_title',

	'default_page' => '0',
	'default_post' => '1',

	'customizer_controls' => array(
		array(
			'label' => esc_html__( 'Page Title', 'bgtfw' ),
			'section' => 'bgtfw_layout_page',
			'setting' => 'bgtfw_pages_display_title',
			'default' => '0',
		),
		array(
			'label' => esc_html__( 'Post Title', 'bgtfw' ),
			'section' => 'bgtfw_pages_blog_posts_layout',
			'setting' => 'bgtfw_posts_display_title',
			'default' => '0',
		),
	),

	// Editor meta box controls.
	'meta_box' => array(
		'post' => array(
			'show_post_text' => '',
			'hide_post_text' => '',
		),
		'page' => array(
			'show_post_text' => '',
			'hide_post_text' => __( 'recommended', 'bgtfw' ),
		),
	),
);

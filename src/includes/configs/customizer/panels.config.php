<?php
/**
 * Customizer Panels Configs
 *
 * @package Boldgrid_Theme_Framework
 * @subpackage Boldgrid_Theme_Framework\Configs
 *
 * @since 2.0.0
 *
 * @return array Panels to create in the WordPress Customizer.
 */

return array(
	'bgtfw_design_panel' => array(
		'title' => __( 'Design', 'bgtfw' ),
		'priority' => 1,
	),
	'boldgrid_typography' => array(
		'title' => __( 'Fonts', 'bgtfw' ),
		'description' => 'Manage your site typography settings.',
		'priority' => 90,
	),
	'bgtfw_header' => array(
		'title' => __( 'Header', 'bgtfw' ),
		'priority' => 1,
		'panel' => 'bgtfw_design_panel',
	),
	'bgtfw_footer' => array(
		'title' => __( 'Footer', 'bgtfw' ),
		'priority' => 2,
		'panel' => 'bgtfw_design_panel',
	),
	'bgtfw_blog_panel' => array(
		'title' => __( 'Blog', 'bgtfw' ),
		'priority' => 2,
		'panel' => 'bgtfw_design_panel',
	),
	'bgtfw_blog_blog_page_panel' => array(
		'title' => __( 'Blog Page', 'bgtfw' ),
		'panel' => 'bgtfw_blog_panel',
	),
	'bgtfw_pages_panel' => array(
		'title' => __( 'Pages', 'bgtfw' ),
		'priority' => 2,
		'panel' => 'bgtfw_design_panel',
	),
	'bgtfw_blog_posts_panel' => array(
		'title' => __( 'Posts', 'bgtfw' ),
		'panel' => 'bgtfw_blog_panel',
	),
	'bgtfw_menus_panel' => array(
		'title' => __( 'Menus', 'bgtfw' ),
		'priority' => 3,
		'panel' => 'bgtfw_design_panel',
	),
);

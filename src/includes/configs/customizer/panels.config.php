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
		'description' => '<div class="bgtfw-description"><p>' . esc_html__( 'Manage your site\'s typography settings.', 'bgtfw' ) . '</p><div class="help"><a href="https://www.boldgrid.com/support/boldgrid-crio/working-with-fonts-in-boldgrid-crio/" target="_blank"><span class="dashicons"></span>Help</a></div></div>',
		'priority' => 90,
	),
	'bgtfw_header' => array(
		'title' => __( 'Header', 'bgtfw' ),
		'priority' => 1,
		'panel' => 'bgtfw_design_panel',
	),
	'bgtfw_site_content' => array(
		'title' => __( 'Site Content', 'bgtfw' ),
		'priority' => 2,
		'panel' => 'bgtfw_design_panel',
	),
	'bgtfw_footer' => array(
		'title' => __( 'Footer', 'bgtfw' ),
		'priority' => 3,
		'panel' => 'bgtfw_design_panel',
	),
	'bgtfw_blog_panel' => array(
		'title' => __( 'Blog', 'bgtfw' ),
		'priority' => 5,
		'panel' => 'bgtfw_design_panel',
	),
	'bgtfw_blog_blog_page_panel' => array(
		'title' => __( 'Blog Page', 'bgtfw' ),
		'description' => '<div class="bgtfw-description"><p>' . esc_html__( 'Change the display of your site\'s blog page.', 'bgtfw' ) . '</p><div class="help"><a href="https://www.boldgrid.com/support/boldgrid-crio/customizing-your-blog-page-in-boldgrid-crio/" target="_blank"><span class="dashicons"></span>Help</a></div></div>',
		'panel' => 'bgtfw_blog_panel',
	),
	'bgtfw_blog_blog_page_design' => array(
		'title' => __( 'Design', 'bgtfw' ),
		'panel' => 'bgtfw_blog_blog_page_panel',
	),
	'bgtfw_pages_panel' => array(
		'title' => __( 'Pages', 'bgtfw' ),
		'description' => '<div class="bgtfw-description"><p>' . esc_html__( 'Change the display of your site\'s pages', 'bgtfw' ) . '</p><div class="help"><a href="https://www.boldgrid.com/support/boldgrid-crio/changing-the-page-layout-in-boldgrid-crio/" target="_blank"><span class="dashicons"></span>Help</a></div></div>',
		'priority' => 2,
		'panel' => 'bgtfw_design_panel',
	),
	'bgtfw_menus_panel' => array(
		'title' => __( 'Menus', 'bgtfw' ),
		'description' => '<div class="bgtfw-description"><p>' . esc_html__( 'Manage the display of menus on your site.', 'bgtfw' ) . '</p><div class="help"><a href="https://www.boldgrid.com/support/boldgrid-crio/working-with-menus-in-boldgrid-crio/" target="_blank"><span class="dashicons"></span>Help</a></div></div>',
		'priority' => 4,
		'panel' => 'bgtfw_design_panel',
	),
	'bgtfw_blog_posts_panel' => array(
		'title' => __( 'Posts', 'bgtfw' ),
		'description' => '<div class="bgtfw-description"><p>' . esc_html__( 'Change the display of single blog posts.', 'bgtfw' ) . '</p><div class="help"><a href="https://www.boldgrid.com/support/boldgrid-crio/customizing-your-blog-posts-in-boldgrid-crio/" target="_blank"><span class="dashicons"></span>Help</a></div></div>',
		'panel' => 'bgtfw_blog_panel',
	),
);

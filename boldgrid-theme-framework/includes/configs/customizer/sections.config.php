<?php
/**
 * Customizer Sections Configs
 *
 * @package Boldgrid_Theme_Framework
 * @subpackage Boldgrid_Theme_Framework\Configs
 *
 * @since 2.0.0
 *
 * @return array Sections to create in the WordPress Customizer.
 */

return array(
	'bgtfw_layout_blog' => array(
		'title' => __( 'Blog', 'bgtfw' ),
		'panel' => 'bgtfw_layout',
		'description' => __( 'This section controls the layout of pages and posts on your website.' ),
		'capability' => 'edit_theme_options',
	),
	'bgtfw_layout_page' => array(
		'title' => __( 'Pages', 'bgtfw' ),
		'panel' => 'bgtfw_design_panel',
		'description' => __( 'This section controls the global layout of pages on your website.' ),
		'capability' => 'edit_theme_options',
	),
	'boldgrid_footer_panel' => array(
		'title' => __( 'Layout', 'bgtfw' ),
		'panel' => 'bgtfw_footer',
		'description' => esc_html__( 'Change the layout of your site\'s footer.', 'bgtfw' ),
		'capability' => 'edit_theme_options',
	),
	'bgtfw_footer_widgets' => array(
		'title' => __( 'Widgets', 'bgtfw' ),
		'panel' => 'bgtfw_footer',
		'description' => __( "Adjust your footer's widget sections.", 'bgtfw' ),
		'capability' => 'edit_theme_options',
	),
	'bgtfw_header_widgets' => array(
		'title' => __( 'Widgets', 'bgtfw' ),
		'panel' => 'bgtfw_header',
		'description' => __( "Adjust your header's widget sections.", 'bgtfw' ),
		'capability' => 'edit_theme_options',
	),
	'bgtfw_footer_colors' => array(
		'title' => __( 'Colors', 'bgtfw' ),
		'panel' => 'bgtfw_footer',
		'description' => esc_attr__( 'Change the colors used in your custom footer.', 'bgtfw' ),
		'capability' => 'edit_theme_options',
	),
	'bgtfw_tagline' => array(
		'title' => __( 'Tagline', 'bgtfw' ),
		'panel' => 'bgtfw_header',
		'description' => __( "Change your site's tagline, and it's appearance.", 'bgtfw' ),
		'capability' => 'edit_theme_options',
	),
	'bgtfw_header_layout' => array(
		'title' => __( 'Layout', 'bgtfw' ),
		'panel' => 'bgtfw_header',
		'priority' => 10,
		'capability' => 'edit_theme_options',
	),
	'bgtfw_header_colors' => array(
		'title' => __( 'Colors', 'bgtfw' ),
		'panel' => 'bgtfw_header',
		'description' => esc_attr__( 'Change the colors used in your custom header.', 'bgtfw' ),
		'capability' => 'edit_theme_options',
	),
	'bgtfw_site_title' => array(
		'title' => esc_attr__( 'Site Title', 'bgtfw' ),
		'panel' => 'bgtfw_header',
		'description' => __( "Change your site title and it's appearance.", 'bgtfw' ),
		'capability' => 'edit_theme_options',
	),
	'navigation_typography' => array(
		'title' => __( 'Menus', 'bgtfw' ),
		'panel' => 'boldgrid_typography',
	),
	'headings_typography' => array(
		'title' => __( 'Headings', 'bgtfw' ),
		'panel' => 'boldgrid_typography',
	),
	'body_typography' => array(
		'title' => __( 'Main Text', 'bgtfw' ),
		'panel' => 'boldgrid_typography',
	),
	'bgtfw_pages_blog_blog_page_layout' => array(
		'title' => 'Layout',
		'panel' => 'bgtfw_blog_blog_page_panel',
		'priority' => 2,
	),
	'bgtfw_blog_blog_page_panel_sidebar' => array(
		'title' => __( 'Sidebar', 'bgtfw' ),
		'panel' => 'bgtfw_blog_blog_page_panel',
		'priority' => 4,
	),
	'bgtfw_pages_blog_posts_layout' => array(
		'title' => 'Layout',
		'panel' => 'bgtfw_blog_posts_panel',
		'priority' => 2,
	),
	'bgtfw_pages_blog_posts_sidebar' => array(
		'title' => __( 'Sidebar', 'bgtfw' ),
		'panel' => 'bgtfw_blog_posts_panel',
		'priority' => 4,
	),

	/** Start: Generic Header Controls **/
	'boldgrid_header_margin_section' => array(
		'title' => __( 'Margin', 'bgtfw' ),
		'panel' => 'bgtfw_header',
		'description' => esc_html__( 'Change the margin of your site\'s header.', 'bgtfw' ),
		'capability' => 'edit_theme_options',
		'priority' => 70,
	),
	'boldgrid_header_padding_section' => array(
		'title' => __( 'Padding', 'bgtfw' ),
		'panel' => 'bgtfw_header',
		'description' => esc_html__( 'Change the padding of your site\'s header.', 'bgtfw' ),
		'capability' => 'edit_theme_options',
		'priority' => 70,
	),
	'boldgrid_header_border_section' => array(
		'title' => __( 'Border', 'bgtfw' ),
		'panel' => 'bgtfw_header',
		'description' => esc_html__( 'Change the border of your site\'s header.', 'bgtfw' ),
		'capability' => 'edit_theme_options',
		'priority' => 70,
	),
	'boldgrid_header_shadow_section' => array(
		'title' => __( 'Box Shadow', 'bgtfw' ),
		'panel' => 'bgtfw_header',
		'description' => esc_html__( 'Change the box shadow of your site\'s header.', 'bgtfw' ),
		'capability' => 'edit_theme_options',
		'priority' => 70,
	),

	'boldgrid_header_radius_section' => array(
		'title' => __( 'Border Radius', 'bgtfw' ),
		'panel' => 'bgtfw_header',
		'description' => esc_html__( 'Change the border radius of your site\'s header.', 'bgtfw' ),
		'capability' => 'edit_theme_options',
		'priority' => 70,
	),
	/** End: Generic Header Controls **/

	/** Start: Generic Footer Controls **/
	'boldgrid_footer_margin_section' => array(
		'title' => __( 'Margin', 'bgtfw' ),
		'panel' => 'bgtfw_footer',
		'description' => esc_html__( 'Change the margin of your site\'s footer.', 'bgtfw' ),
		'capability' => 'edit_theme_options',
		'priority' => 70,
	),
	'boldgrid_footer_padding_section' => array(
		'title' => __( 'Padding', 'bgtfw' ),
		'panel' => 'bgtfw_footer',
		'description' => esc_html__( 'Change the padding of your site\'s footer.', 'bgtfw' ),
		'capability' => 'edit_theme_options',
		'priority' => 70,
	),
	'boldgrid_footer_border_section' => array(
		'title' => __( 'Border', 'bgtfw' ),
		'panel' => 'bgtfw_footer',
		'description' => esc_html__( 'Change the border of your site\'s footer.', 'bgtfw' ),
		'capability' => 'edit_theme_options',
		'priority' => 70,
	),
	'boldgrid_footer_shadow_section' => array(
		'title' => __( 'Box Shadow', 'bgtfw' ),
		'panel' => 'bgtfw_footer',
		'description' => esc_html__( 'Change the box shadow of your site\'s footer.', 'bgtfw' ),
		'capability' => 'edit_theme_options',
		'priority' => 70,
	),

	'boldgrid_footer_radius_section' => array(
		'title' => __( 'Border Radius', 'bgtfw' ),
		'panel' => 'bgtfw_footer',
		'description' => esc_html__( 'Change the border radius of your site\'s footer.', 'bgtfw' ),
		'capability' => 'edit_theme_options',
		'priority' => 70,
	),
	/** End: Generic Footer Controls **/

);

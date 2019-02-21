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
		'description' => __( 'This section controls the layout of pages and posts on your website.', 'bgtfw' ),
		'capability' => 'edit_theme_options',
	),
	'bgtfw_layout_page' => array(
		'title' => __( 'Pages', 'bgtfw' ),
		'panel' => 'bgtfw_design_panel',
		'description' => esc_html__( 'This section controls the global layout of pages on your website.', 'bgtfw' ),
		'capability' => 'edit_theme_options',
		'priority' => 1,
		'icon' => 'dashicons-admin-page',
	),
	'bgtfw_layout_page_title' => array(
		'title' => __( 'Title', 'bgtfw' ),
		'panel' => 'bgtfw_design_panel',
		'section' => 'bgtfw_layout_page',
		'description' => esc_html__( 'This section controls the appearance of titles on your pages.', 'bgtfw' ),
		'capability' => 'edit_theme_options',
		'icon' => 'icon-header-settings',
	),
	'bgtfw_layout_page_container' => array(
		'title' => __( 'Container', 'bgtfw' ),
		'panel' => 'bgtfw_design_panel',
		'section' => 'bgtfw_layout_page',
		'description' => esc_html__( 'This section controls the container for your pages.', 'bgtfw' ),
		'capability' => 'edit_theme_options',
		'icon' => 'icon-layout-container',
	),
	'bgtfw_layout_page_sidebar' => array(
		'title' => __( 'Sidebar', 'bgtfw' ),
		'panel' => 'bgtfw_design_panel',
		'section' => 'bgtfw_layout_page',
		'description' => esc_html__( 'This section controls the container for your pages.', 'bgtfw' ),
		'capability' => 'edit_theme_options',
		'icon' => 'icon-sidebar-settings',
	),
	'boldgrid_footer_panel' => array(
		'title' => __( 'Layout', 'bgtfw' ),
		'panel' => 'bgtfw_footer',
		'description' => '<div class="bgtfw-description"><p>' . esc_html__( 'Change the layout of your site\'s footer.', 'bgtfw' ) . '</p><div class="help"><a href="https://www.boldgrid.com/support/boldgrid-crio/customizing-the-footer-design-in-boldgrid-crio/" target="_blank"><span class="dashicons"></span>Help</a></div></div>',
		'capability' => 'edit_theme_options',
		'priority' => 1,
		'icon' => 'dashicons-schedule',
	),
	'bgtfw_footer_colors' => array(
		'title' => __( 'Colors', 'bgtfw' ),
		'panel' => 'bgtfw_footer',
		'description' => '<div class="bgtfw-description"><p>' . esc_attr__( 'Change the colors used in your custom footer.', 'bgtfw' ) . '</p><div class="help"><a href="https://www.boldgrid.com/support/boldgrid-crio/how-to-change-the-footer-colors-in-boldgrid-crio/" target="_blank"><span class="dashicons"></span>Help</a></div></div>',
		'capability' => 'edit_theme_options',
		'priority' => 2,
		'icon' => 'dashicons-art',
	),
	'bgtfw_footer_advanced' => array(
		'title' => __( 'Advanced', 'bgtfw' ),
		'description' => esc_html__( 'Advanced settings for the appearance of your site\'s footer.', 'bgtfw' ),
		'panel' => 'bgtfw_footer',
		'priority' => 70,
		'icon' => 'dashicons-admin-generic',
	),
	'bgtfw_header_layout' => array(
		'title' => __( 'Layout', 'bgtfw' ),
		'description' => '<div class="bgtfw-description"><p>' . esc_html__( 'Manage the layout of your site\'s header.', 'bgtfw' ) . '</p><div class="help"><a href="https://www.boldgrid.com/support/boldgrid-crio/customizing-the-header-design-in-boldgrid-crio/" target="_blank"><span class="dashicons"></span>' . esc_html__( 'Help', 'crio-premium' ) . '</a></div></div>',
		'panel' => 'bgtfw_header',
		'capability' => 'edit_theme_options',
		'priority' => 5,
		'notice' => [
			'dismissible' => false,
			'message' => esc_html__( 'Upgrade Crio to get additional display options for your header!', 'bgtfw' ),
			'type' => 'bgtfw-features',
			'templateId' => 'bgtfw-notification',
			'featureCount' => 1,
			'featureDescription' => esc_html__( '1 premium feature available!', 'bgtfw' ),
			'url' => esc_url( 'https://www.boldgrid.com/wordpress-themes/crio/header' ),
			'buttonText' => esc_html__( 'Learn More', 'bgtfw' ),
		],
		'icon' => 'dashicons-schedule',
	),
	'bgtfw_site_title' => array(
		'title' => esc_attr__( 'Site Title', 'bgtfw' ),
		'panel' => 'bgtfw_header',
		'description' => '<div class="bgtfw-description"><p>' . esc_html__( 'Change your site\'s title and it\'s appearance.', 'bgtfw' ) . '</p><div class="help"><a href="https://www.boldgrid.com/support/boldgrid-crio/working-with-your-site-title-logo-and-tagline-in-boldgrid-crio/" target="_blank"><span class="dashicons"></span>Help</a></div></div>',
		'capability' => 'edit_theme_options',
		'priority' => 6,
		'icon' => 'dashicons-flag',
	),
	'bgtfw_tagline' => array(
		'title' => __( 'Tagline', 'bgtfw' ),
		'panel' => 'bgtfw_header',
		'description' => '<div class="bgtfw-description"><p>' . esc_html__( 'Change your site\'s tagline, and it\'s appearance.', 'bgtfw' ) . '</p><div class="help"><a href="https://www.boldgrid.com/support/boldgrid-crio/working-with-your-site-title-logo-and-tagline-in-boldgrid-crio/" target="_blank"><span class="dashicons"></span>Help</a></div></div>',
		'capability' => 'edit_theme_options',
		'priority' => 8,
		'icon' => 'dashicons-tag',
	),
	'bgtfw_header_colors' => array(
		'title' => __( 'Colors', 'bgtfw' ),
		'panel' => 'bgtfw_header',
		'description' => '<div class="bgtfw-description"><p>' . esc_html__( 'Change the colors used in your custom header.', 'bgtfw' ) . '</p><div class="help"><a href="https://www.boldgrid.com/support/boldgrid-crio/how-to-change-the-header-background-in-boldgrid-crio/" target="_blank"><span class="dashicons"></span>Help</a></div></div>',
		'capability' => 'edit_theme_options',
		'priority' => 11,
		'icon' => 'dashicons-art',
	),
	'bgtfw_header_advanced' => array(
		'title' => __( 'Advanced', 'bgtfw' ),
		'description' => esc_html__( 'Advanced settings for the appearance of your site\'s header.', 'bgtfw' ),
		'panel' => 'bgtfw_header',
		'priority' => 70,
		'icon' => 'dashicons-admin-generic',
	),
	'navigation_typography' => array(
		'title' => __( 'Menus', 'bgtfw' ),
		'panel' => 'boldgrid_typography',
		'icon' => 'dashicons-menu',
	),
	'body_typography' => array(
		'title' => __( 'Main Text', 'bgtfw' ),
		'panel' => 'boldgrid_typography',
		'icon' => 'fa-paragraph',
	),
	'headings_typography' => array(
		'title' => __( 'Headings', 'bgtfw' ),
		'panel' => 'boldgrid_typography',
		'icon' => 'fa-header',
	),
	'bgtfw_global_page_titles' => array(
		'title' => __( 'Page Titles', 'bgtfw' ),
		'description' => '<div class="bgtfw-description"><p>' . esc_html__( 'Control the display of page titles displayed on your site.', 'bgtfw' ) . '</p><div class="help"><a href="https://www.boldgrid.com/support/boldgrid-crio/site-content-design-tools-in-boldgrid-crio/" target="_blank"><span class="dashicons"></span>Help</a></div></div>',
		'panel' => 'bgtfw_site_content',
		'icon' => 'icon-header-settings',
	),
	'bgtfw_body_link_design' => array(
		'title' => __( 'Links', 'bgtfw' ),
		'description' => '<div class="bgtfw-description"><p>' . esc_html__( 'Control the display of links used in your site\'s content.', 'bgtfw' ) . '</p><div class="help"><a href="https://www.boldgrid.com/support/boldgrid-crio/site-content-design-tools-in-boldgrid-crio/" target="_blank"><span class="dashicons"></span>Help</a></div></div>',
		'panel' => 'bgtfw_site_content',
		'icon' => 'dashicons-admin-links',
	),
	'bgtfw_blog_colors_section' => array(
		'title' => __( 'Colors', 'bgtfw' ),
		'panel' => 'bgtfw_blog_blog_page_panel',
		'section' => 'bgtfw_blog_blog_page_design',
		'description' => esc_attr__( 'Change the colors used on your blog post page.', 'bgtfw' ),
		'capability' => 'edit_theme_options',
		'priority' => 10,
	),
	'bgtfw_pages_blog_blog_page_layout' => array(
		'title' => 'Layout',
		'panel' => 'bgtfw_blog_blog_page_panel',
		'priority' => 1,
	),
	'bgtfw_pages_blog_blog_page_post_content' => array(
		'title' => 'Post List Settings',
		'panel' => 'bgtfw_blog_blog_page_panel',
		'icon' => 'dashicons-feedback',
	),
	'bgtfw_blog_blog_page_panel_sidebar' => array(
		'title' => __( 'Sidebar', 'bgtfw' ),
		'panel' => 'bgtfw_blog_blog_page_panel',
		'icon' => 'icon-sidebar-settings',
	),
	'bgtfw_blog_blog_page_colors' => array(
		'title' => __( 'Background Colors', 'bgtfw' ),
		'panel' => 'bgtfw_blog_blog_page_panel',
		'icon' => 'dashicons-art',
	),
	'bgtfw_pages_blog_blog_page_titles' => array(
		'title' => 'Titles',
		'panel' => 'bgtfw_blog_blog_page_panel',
		'icon' => 'icon-header-settings',
	),
	'bgtfw_pages_blog_blog_page_featured_images' => array(
		'title' => 'Featured Images',
		'panel' => 'bgtfw_blog_blog_page_panel',
		'icon' => 'dashicons-format-gallery',
	),
	'bgtfw_pages_blog_blog_page_links' => array(
		'title' => 'Links',
		'panel' => 'bgtfw_blog_blog_page_panel',
		'icon' => 'dashicons-admin-links',
	),
	'bgtfw_pages_blog_blog_page_advanced' => array(
		'title' => 'Advanced',
		'panel' => 'bgtfw_blog_blog_page_panel',
		'icon' => 'dashicons-admin-generic',
	),
	'bgtfw_pages_blog_blog_page_post_meta' => array(
		'title' => 'Post Meta',
		'panel' => 'bgtfw_blog_blog_page_panel',
		'section' => 'bgtfw_pages_blog_blog_page_links',
		'icon' => 'fa-id-card-o',
	),
	'bgtfw_pages_blog_blog_page_read_more' => array(
		'title' => 'Read More Links',
		'panel' => 'bgtfw_blog_blog_page_panel',
		'section' => 'bgtfw_pages_blog_blog_page_links',
		'icon' => 'fa-ellipsis-h',
	),
	'bgtfw_pages_blog_blog_page_tags_links' => array(
		'title' => 'Tag Links',
		'panel' => 'bgtfw_blog_blog_page_panel',
		'section' => 'bgtfw_pages_blog_blog_page_links',
		'icon' => 'dashicons-tag',
	),
	'bgtfw_pages_blog_blog_page_cat_links' => array(
		'title' => 'Category Links',
		'panel' => 'bgtfw_blog_blog_page_panel',
		'section' => 'bgtfw_pages_blog_blog_page_links',
		'icon' => 'dashicons-category',
	),
	'bgtfw_pages_blog_blog_page_comment_links' => array(
		'title' => 'Comment Links',
		'panel' => 'bgtfw_blog_blog_page_panel',
		'section' => 'bgtfw_pages_blog_blog_page_links',
		'icon' => 'fa-comments-o',
	),
	'bgtfw_pages_blog_posts_title' => array(
		'title' => __( 'Title', 'bgtfw' ),
		'panel' => 'bgtfw_blog_posts_panel',
		'icon' => 'icon-header-settings',
	),
	'bgtfw_pages_blog_posts_container' => array(
		'title' => __( 'Container', 'bgtfw' ),
		'panel' => 'bgtfw_blog_posts_panel',
		'icon' => 'icon-layout-container',
	),
	'bgtfw_pages_blog_posts_sidebar' => array(
		'title' => __( 'Sidebar', 'bgtfw' ),
		'panel' => 'bgtfw_blog_posts_panel',
		'icon' => 'icon-sidebar-settings',
	),
	'bgtfw_pages_blog_posts_links' => array(
		'title' => 'Links',
		'panel' => 'bgtfw_blog_posts_panel',
		'icon' => 'dashicons-admin-links',
	),
	'bgtfw_pages_blog_posts_meta' => array(
		'title' => __( 'Post Meta', 'bgtfw' ),
		'panel' => 'bgtfw_blog_posts_panel',
		'section' => 'bgtfw_pages_blog_posts_links',
		'icon' => 'fa-id-card-o',
	),
	'bgtfw_pages_blog_posts_tags_links' => array(
		'title' => 'Tag Links',
		'panel' => 'bgtfw_blog_posts_panel',
		'section' => 'bgtfw_pages_blog_posts_links',
		'icon' => 'dashicons-tag',
	),
	'bgtfw_pages_blog_posts_cat_links' => array(
		'title' => 'Category Links',
		'panel' => 'bgtfw_blog_posts_panel',
		'section' => 'bgtfw_pages_blog_posts_links',
		'icon' => 'dashicons-category',
	),
	'bgtfw_pages_blog_posts_navigation_links' => array(
		'title' => 'Post Navigation Links',
		'panel' => 'bgtfw_blog_posts_panel',
		'section' => 'bgtfw_pages_blog_posts_links',
		'icon' => 'dashicons-menu',
	),

	/*  Start: Generic Header Controls */
	'boldgrid_header_margin_section' => array(
		'title' => __( 'Margin', 'bgtfw' ),
		'panel' => 'bgtfw_header',
		'section' => 'bgtfw_header_advanced',
		'description' => '<div class="bgtfw-description"><p>' . esc_html__( 'Change the margin of your site\'s header.', 'bgtfw' ) . '</p><div class="help"><a href="https://www.boldgrid.com/support/boldgrid-crio/editing-header-and-footer-margins-in-boldgrid-crio/" target="_blank"><span class="dashicons"></span>Help</a></div></div>',
		'capability' => 'edit_theme_options',
		'priority' => 70,
	),
	'boldgrid_header_padding_section' => array(
		'title' => __( 'Padding', 'bgtfw' ),
		'panel' => 'bgtfw_header',
		'section' => 'bgtfw_header_advanced',
		'description' => '<div class="bgtfw-description"><p>' . esc_html__( 'Change the padding of your site\'s header.', 'bgtfw' ) . '</p><div class="help"><a href="https://www.boldgrid.com/support/boldgrid-crio/working-with-header-footer-padding/" target="_blank"><span class="dashicons"></span>Help</a></div></div>',
		'capability' => 'edit_theme_options',
		'priority' => 70,
	),
	'boldgrid_header_border_section' => array(
		'title' => __( 'Border', 'bgtfw' ),
		'panel' => 'bgtfw_header',
		'section' => 'bgtfw_header_advanced',
		'description' => '<div class="bgtfw-description"><p>' . esc_html__( 'Change the border of your site\'s header.', 'bgtfw' ) . '</p><div class="help"><a href="https://www.boldgrid.com/support/boldgrid-crio/customizing-borders-in-the-header-and-footer/" target="_blank"><span class="dashicons"></span>Help</a></div></div>',
		'capability' => 'edit_theme_options',
		'priority' => 70,
	),
	'boldgrid_header_shadow_section' => array(
		'title' => __( 'Box Shadow', 'bgtfw' ),
		'panel' => 'bgtfw_header',
		'section' => 'bgtfw_header_advanced',
		'description' => '<div class="bgtfw-description"><p>' . esc_html__( 'Change the box shadow of your site\'s header.', 'bgtfw' ) . '</p><div class="help"><a href="https://www.boldgrid.com/support/boldgrid-crio/how-to-add-a-box-shadow-to-the-boldgrid-crio-header-and-footer/" target="_blank"><span class="dashicons"></span>Help</a></div></div>',
		'capability' => 'edit_theme_options',
		'priority' => 70,
	),

	'boldgrid_header_radius_section' => array(
		'title' => __( 'Border Radius', 'bgtfw' ),
		'panel' => 'bgtfw_header',
		'section' => 'bgtfw_header_advanced',
		'description' => '<div class="bgtfw-description"><p>' . esc_html__( 'Change the border radius of your site\'s header.', 'bgtfw' ) . '</p><div class="help"><a href="https://www.boldgrid.com/support/boldgrid-crio/changing-the-header-and-footer-border-radius/" target="_blank"><span class="dashicons"></span>Help</a></div></div>',
		'capability' => 'edit_theme_options',
		'priority' => 70,
	),

	// End: Generic Header Controls
	// Start: Generic Footer Controls
	'boldgrid_footer_margin_section' => array(
		'title' => __( 'Margin', 'bgtfw' ),
		'panel' => 'bgtfw_footer',
		'section' => 'bgtfw_footer_advanced',
		'description' => '<div class="bgtfw-description"><p>' . esc_html__( 'Change the margin of your site\'s footer.', 'bgtfw' ) . '</p><div class="help"><a href="https://www.boldgrid.com/support/boldgrid-crio/editing-header-and-footer-margins-in-boldgrid-crio/" target="_blank"><span class="dashicons"></span>Help</a></div></div>',
		'capability' => 'edit_theme_options',
		'priority' => 70,
	),
	'boldgrid_footer_padding_section' => array(
		'title' => __( 'Padding', 'bgtfw' ),
		'panel' => 'bgtfw_footer',
		'section' => 'bgtfw_footer_advanced',
		'description' => '<div class="bgtfw-description"><p>' . esc_html__( 'Change the padding of your site\'s footer.', 'bgtfw' ) . '</p><div class="help"><a href="https://www.boldgrid.com/support/boldgrid-crio/working-with-header-footer-padding/" target="_blank"><span class="dashicons"></span>Help</a></div></div>',
		'capability' => 'edit_theme_options',
		'priority' => 70,
	),
	'boldgrid_footer_border_section' => array(
		'title' => __( 'Border', 'bgtfw' ),
		'panel' => 'bgtfw_footer',
		'section' => 'bgtfw_footer_advanced',
		'description' => '<div class="bgtfw-description"><p>' . esc_html__( 'Change the border of your site\'s footer.', 'bgtfw' ) . '</p><div class="help"><a href="https://www.boldgrid.com/support/boldgrid-crio/customizing-borders-in-the-header-and-footer/" target="_blank"><span class="dashicons"></span>Help</a></div></div>',
		'capability' => 'edit_theme_options',
		'priority' => 70,
	),
	'boldgrid_footer_shadow_section' => array(
		'title' => __( 'Box Shadow', 'bgtfw' ),
		'panel' => 'bgtfw_footer',
		'section' => 'bgtfw_footer_advanced',
		'description' => '<div class="bgtfw-description"><p>' . esc_html__( 'Change the box shadow of your site\'s footer.', 'bgtfw' ) . '</p><div class="help"><a href="https://www.boldgrid.com/support/boldgrid-crio/how-to-add-a-box-shadow-to-the-boldgrid-crio-header-and-footer/" target="_blank"><span class="dashicons"></span>Help</a></div></div>',
		'capability' => 'edit_theme_options',
		'priority' => 70,
	),

	'boldgrid_footer_radius_section' => array(
		'title' => __( 'Border Radius', 'bgtfw' ),
		'panel' => 'bgtfw_footer',
		'section' => 'bgtfw_footer_advanced',
		'description' => '<div class="bgtfw-description"><p>' . esc_html__( 'Change the border radius of your site\'s footer.', 'bgtfw' ) . '</p><div class="help"><a href="https://www.boldgrid.com/support/boldgrid-crio/changing-the-header-and-footer-border-radius/" target="_blank"><span class="dashicons"></span>Help</a></div></div>',
		'capability' => 'edit_theme_options',
		'priority' => 70,
	),

	// Start: Generic Blog Design Controls.
	'bgtfw_blog_margin_section' => array(
		'title' => __( 'Margin', 'bgtfw' ),
		'panel' => 'bgtfw_blog_blog_page_panel',
		'section' => 'bgtfw_pages_blog_blog_page_advanced',
		'description' => esc_html__( 'Change the margin of your blog posts.', 'bgtfw' ),
		'capability' => 'edit_theme_options',
	),
	'bgtfw_blog_padding_section' => array(
		'title' => __( 'Padding', 'bgtfw' ),
		'panel' => 'bgtfw_blog_blog_page_panel',
		'section' => 'bgtfw_pages_blog_blog_page_advanced',
		'description' => esc_html__( 'Change the padding of your blog posts.', 'bgtfw' ),
		'capability' => 'edit_theme_options',
	),
	'bgtfw_blog_border_section' => array(
		'title' => __( 'Border', 'bgtfw' ),
		'panel' => 'bgtfw_blog_blog_page_panel',
		'section' => 'bgtfw_pages_blog_blog_page_advanced',
		'description' => esc_html__( 'Change the border of your blog posts.', 'bgtfw' ),
		'capability' => 'edit_theme_options',
	),
	'bgtfw_blog_shadow_section' => array(
		'title' => __( 'Box Shadow', 'bgtfw' ),
		'panel' => 'bgtfw_blog_blog_page_panel',
		'section' => 'bgtfw_pages_blog_blog_page_advanced',
		'description' => esc_html__( 'Change the box shadow of your blog posts.', 'bgtfw' ),
		'capability' => 'edit_theme_options',
	),
	// End: Generic Blog Design Controls.
);

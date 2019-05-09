<?php
/**
 * Edit Button Configuration Options.
 *
 * @package Boldgrid_Theme_Framework
 * @subpackage Boldgrid_Theme_Framework\Configs
 *
 * @since    1.1.6
 *
 * @return   array   An array of edit button configs.
 */

return array(
	'enabled' => true,
	'defaultIcon' => 'dashicons-edit',
	// jQuery selectors to target our hamburger menus.
	'hamburgers' =>
		// @since 1.0.0
		'.navbar-toggle' .
		// @since 2.0.0
		', .main-menu-btn',
	'buttons' => array(

		/*
		 * An array of general edit buttons.
		 *
		 * @param icon sring          A css class that will be added to the button. The default edit
		 *                            button is the pencil icon, 'dashicons-edit'.
		 * @param isParentColumn bool @since 2.0.0 Whether or not the selector is the parent column.
		 *                            If it is, then the edit button will be aligned with the selector.
		 *                            Else, the edit button will be aligned with the element found by
		 *                            BOLDGRID.CustomizerEdit.parentColumn().
		 * @param parentColumn string @since 2.0.0 A container to align a button with. In previous versions,
		 *                            the container was found using BOLDGRID.CustomizerEdit.parentColumn().
		 *                            That container was generally the closest .col, .row, or .container.
		 *                            As of 2.0.0, the structure has changed slightly, and for some
		 *                            elements we need to define the parentColumn. If it's not defined,
		 *                            it will be determined with the parentColumn() function.
		 * @param requireText bool    Require that the item we are controling have text. For example,
		 *                            if we are controlling ".site-title a", we can require that that
		 *                            element has some sort of text within it, before adding a button
		 *                            for it.
		 * @param objectType string   @since 2.0.0 A customizer object type, such as "panel", "section",
		 *                            or "control". This param used in helping to determine action to
		 *                            take when edit button is clicked. For example, a control within
		 *                            a panel can be bounced to bring attention to it, but you don't
		 *                            want to bounce a section / panel.
		 * @param postType array      @since 2.0.0 An array of post types a setting should be displayed
		 *                            for. Optional.
		 */
		'general' => array(
			array(
				'control' => 'blogname',
				'selector' => '#masthead .site-title',
				'isParentColumn' => true,
				'requireText' => true,
				'title' => esc_attr__( 'Site Title', 'bgtfw' ),
			),
			array(
				'control' => 'custom_logo',
				'selector' => '#masthead .custom-logo-link',
				'isParentColumn' => true,
				'title' => esc_attr__( 'Logo', 'bgtfw' ),
			),
			array(
				'control' => 'boldgrid_contact_details_setting',
				'selector' => '.bgtfw.contact-block',
				'title' => esc_attr__( 'Contact Blocks', 'bgtfw' ),
			),
			array(
				'control' => 'bgtfw_footer_layout',
				'selector' => '.attribution-theme-mods',
				'title' => esc_attr__( 'Attribution', 'bgtfw' ),
			),

			// Entry header, contains title and meta data.
			array(
				'control' => 'bgtfw_global_page_titles',
				'selector' => '.page .entry-header, .single .entry-header, .blog .page-header, .archive .page-header',
				'postType' => array( 'page', 'post' ),
				'objectType' => 'section',
				'title' => esc_attr__( 'Title Design', 'bgtfw' ),
			),

			/*
			 * The next two entries are for page and post content. If the user clicks these edit buttons,
			 * they will be prompted to go to the editor.
			 */
			array(
				'control' => 'entry-content',
				'selector' => '.entry-content',
				'isParentColumn' => true,
				'title' => esc_attr__( 'Page Content', 'bgtfw' ),
				'postType' => array( 'page' ),
			),
			array(
				'control' => 'entry-content',
				'selector' => '.entry-content',
				'isParentColumn' => true,
				'title' => esc_attr__( 'Post Content', 'bgtfw' ),
				'postType' => array( 'post' ),
			),

			// Entry title, for pages.
			array(
				'control' => 'bgtfw_pages_title_display',
				'selector' => '.page .entry-title, .blog .page-title',
				'title' => esc_attr__( 'Page Title Display', 'bgtfw' ),
			),

			// Entry title, for single post.
			array(
				'control' => 'bgtfw_posts_title_display',
				'selector' => '.single .entry-title',
				'postType' => array( 'post' ),
				'title' => esc_attr__( 'Post Title Display', 'bgtfw' ),
			),

			// Post entry meta.
			array(
				'control' => 'bgtfw_pages_blog_posts_meta',
				'selector' => '.single .entry-meta',
				'postType' => array( 'post' ),
				'objectType' => 'section',
				'title' => esc_attr__( 'Post Meta', 'bgtfw' ),
			),

			// Article wrapper for posts.
			array(
				'control' => 'bgtfw_pages_blog_posts_container',
				'selector' => '.single article',
				'postType' => array( 'post' ),
				'objectType' => 'section',
				'title' => esc_attr__( 'Post Container', 'bgtfw' ),
			),

			// Article wrapper for pages.
			array(
				'control' => 'bgtfw_layout_page_container',
				'selector' => '.page article',
				'postType' => array( 'page' ),
				'objectType' => 'section',
				'title' => esc_attr__( 'Page Container', 'bgtfw' ),
			),

			// Post navigation links.
			array(
				'control' => 'bgtfw_pages_blog_posts_navigation_links',
				'selector' => '.post-navigation',
				'postType' => array( 'post' ),
				'objectType' => 'section',
				'title' => esc_attr__( 'Navigation Links', 'bgtfw' ),
			),

			// Post category links.
			array(
				'control' => 'bgtfw_pages_blog_posts_cat_links',
				'selector' => '.single .cat-links',
				'postType' => array( 'post' ),
				'objectType' => 'section',
				'title' => esc_attr__( 'Category Links', 'bgtfw' ),
			),

			// Post tag links.
			array(
				'control' => 'bgtfw_pages_blog_posts_tags_links',
				'selector' => '.single .tags-links',
				'postType' => array( 'post' ),
				'objectType' => 'section',
				'title' => esc_attr__( 'Tag Links', 'bgtfw' ),
			),

			// Posts page - individual post - post meta.
			array(
				'control' => 'bgtfw_pages_blog_blog_page_post_meta',
				'selector' => '.blog .main .entry-meta, .archive .main .entry-meta',
				'objectType' => 'section',
				'title' => esc_attr__( 'Post Meta', 'bgtfw' ),
			),

			// Posts page - individual post - read more.
			array(
				'control' => 'bgtfw_pages_blog_blog_page_read_more',
				'selector' => '.blog .main .read-more:first, .archive .main .read-more:first',
				'objectType' => 'section',
				'title' => esc_attr__( 'Read More', 'bgtfw' ),
			),

			// Posts page - individual post - tags.
			array(
				'control' => 'bgtfw_pages_blog_blog_page_tags_links',
				'selector' => '.blog .main .tags-links:first, .archive .main .tags-links:first',
				'objectType' => 'section',
				'title' => esc_attr__( 'Tag Links', 'bgtfw' ),
			),

			// Posts page - individual post - categories.
			array(
				'control' => 'bgtfw_pages_blog_blog_page_cat_links',
				'selector' => '.blog .main .cat-links:first, .archive .main .cat-links:first',
				'objectType' => 'section',
				'title' => esc_attr__( 'Category Links', 'bgtfw' ),
			),

			// Posts page - individual post - comments.
			array(
				'control' => 'bgtfw_pages_blog_blog_page_comment_links',
				'selector' => '.blog .main .comments-link:first, .archive .main .comments-link:first',
				'objectType' => 'section',
				'title' => esc_attr__( 'Comments', 'bgtfw' ),
			),

			// Posts page - individual post - title.
			array(
				'control' => 'bgtfw_pages_blog_blog_page_titles',
				'selector' => '.blog .main .entry-title:first, .archive .main .entry-title:first',
				'objectType' => 'section',
				'title' => esc_attr__( 'Post Title Design', 'bgtfw' ),
			),

			// Posts page - individual post - entry header.
			array(
				'control' => 'bgtfw_blog_header_background_color',
				'selector' => '.blog .main .entry-header:first, .archive .main .entry-header:first',
				'title' => esc_attr__( 'Background Color', 'bgtfw' ),
			),

			// Posts page - individual post - entry content.
			array(
				'control' => 'bgtfw_blog_post_background_color',
				'selector' => '.blog .main .entry-content, .archive .main .entry-content',
				'title' => esc_attr__( 'Background Color', 'bgtfw' ),
			),

			// Posts page - individual post - entry content.
			array(
				'control' => 'bgtfw_pages_blog_blog_page_advanced',
				'selector' => '.blog .main article, .archive .main article',
				'objectType' => 'section',
				'title' => esc_attr__( 'Advanced', 'bgtfw' ),
			),
			array(
				'control' => 'blogdescription',
				'selector' => '#masthead .site-description',
				'isParentColumn' => true,
				'title' => esc_attr__( 'Tagline', 'bgtfw' ),
			),
			array(
				'control' => 'bgtfw_header',
				'selector' => '#masthead',
				'isParentColumn' => true,
				'objectType' => 'panel',
				'title' => esc_attr__( 'Header', 'bgtfw' ),
			),
			array(
				'control' => 'bgtfw_footer',
				'selector' => '#colophon',
				'isParentColumn' => true,
				'objectType' => 'panel',
				'title' => esc_attr__( 'Footer', 'bgtfw' ),
			),
			array(
				'control' => 'bgtfw_blog_blog_page_panel',
				'selector' => '.blog .main, .archive .main',
				'parentColumn' => 'main',
				'objectType' => 'panel',
				'title' => esc_attr__( 'Blog Page', 'bgtfw' ),
			),

			// Scroll to top button.
			array(
				'control' => 'bgtfw_scroll_to_top_display',
				'selector' => '.goup-container',
				'isParentColumn' => true,
				'title' => esc_attr__( 'Scroll To Top', 'bgtfw' ),
			),
		),
	),
);

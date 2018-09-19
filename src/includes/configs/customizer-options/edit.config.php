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
				'selector' => '.site-title a',
				'parentColumn' => '.site-title',
				'requireText' => true,
			),
			array(
				'control' => 'custom_logo',
				'selector' => '.custom-logo-link',
				'isParentColumn' => true,
			),
			array(
				'control' => 'boldgrid_contact_details_setting',
				'selector' => '.bgtfw.contact-block',
			),
			array(
				'control' => 'hide_boldgrid_attribution',
				'selector' => '.attribution-theme-mods',
			),
			// Entry header, contains title and meta data.
			array(
				'control' => 'bgtfw_global_page_titles',
				'selector' => '.entry-header',
				'postType' => array( 'page', 'post' ),
				'objectType' => 'section',
			),
			array(
				'control' => 'entry-content',
				'selector' => '.entry-content',
				'isParentColumn' => true,
			),
			// Entry title, for pages.
			array(
				'control' => 'bgtfw_pages_title_display',
				'selector' => '.entry-title',
				'postType' => array( 'page' ),
			),
			// Entry title, for posts.
			array(
				'control' => 'bgtfw_posts_title_display',
				'selector' => '.entry-title',
				'postType' => array( 'post' ),
			),
			// Post entry meta.
			array(
				'control' => 'bgtfw_pages_blog_posts_meta',
				'selector' => '.entry-meta',
				'postType' => array( 'post' ),
				'objectType' => 'section',
			),
			// Article wrapper for posts.
			array(
				'control' => 'bgtfw_pages_blog_posts_container',
				'selector' => '.article-wrapper',
				'postType' => array( 'post' ),
				'objectType' => 'section',
			),
			// Post navigation links.
			array(
				'control' => 'bgtfw_pages_blog_posts_navigation_links',
				'selector' => '.post-navigation',
				'postType' => array( 'post' ),
				'objectType' => 'section',
			),
			// Post category links.
			array(
				'control' => 'bgtfw_pages_blog_posts_cat_links',
				'selector' => '.cat-links',
				'postType' => array( 'post' ),
				'objectType' => 'section',
			),
			// Post tag links.
			array(
				'control' => 'bgtfw_pages_blog_posts_tags_links',
				'selector' => '.tags-links',
				'postType' => array( 'post' ),
				'objectType' => 'section',
			),
			array(
				'control' => 'blogdescription',
				'selector' => '.site-description',
				'isParentColumn' => true,
			),
			array(
				'control' => 'bgtfw_header_layout',
				'selector' => '#masthead',
				'isParentColumn' => true,
				'objectType' => 'section',
			),
			array(
				'control' => 'boldgrid_footer_panel',
				'selector' => '#colophon',
				'isParentColumn' => true,
				'objectType' => 'section',
			),
			array(
				'control' => 'bgtfw_blog_blog_page_panel',
				'selector' => '.blog .main, .archive .main',
				'parentColumn' => 'main',
				'objectType' => 'panel',
			),
		),
	),
);

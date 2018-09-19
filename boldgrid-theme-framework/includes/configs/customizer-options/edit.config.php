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
			array(
				'control' => 'entry-content',
				'selector' => '.entry-content',
				'isParentColumn' => true,
			),
			array(
				'control' => 'entry-title',
				'selector' => '.entry-title',
				'postType' => array( 'page', 'post' ),
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

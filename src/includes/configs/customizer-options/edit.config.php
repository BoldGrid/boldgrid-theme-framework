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
	'buttons' => array(

		/*
		 * An array of general edit buttons.
		 *
		 * Settings:
		 * # icon - A css class that will be added to the button. The default edit button is the
		 *          pencil icon, 'dashicons-edit'.
		 */
		'general' => array(
			array(
				'control' => 'blogname',
				'selector' => '.site-title a',
				'icon' => 'dashicons-edit',
			),
			array(
				'control' => 'boldgrid_logo_setting',
				'selector' => '.logo-site-title',
				'icon' => 'dashicons-edit',
			),
			array(
				'control' => 'boldgrid_contact_details_setting',
				'selector' => '.bgtfw.contact-block',
				'icon' => 'dashicons-edit',
			),
			array(
				'control' => 'hide_boldgrid_attribution',
				'selector' => '.attribution-theme-mods',
				'icon' => 'dashicons-edit',
			),
			array(
				'control' => 'entry-content',
				'selector' => '.entry-content',
				'icon' => 'dashicons-edit',
			),
			array(
				'control' => 'entry-title',
				'selector' => '.entry-title',
				'icon' => 'dashicons-edit',
			),
			array(
				'control' => 'blogdescription',
				'selector' => '.site-description',
				'icon' => 'dashicons-edit',
			),
		),
	),
);

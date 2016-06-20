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
		 * By default, all icons will use the pencil dashicon. If adding a new icon below, ensure
		 * the icon if configured it edit.css (refer to the existing icons as example).
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
				'control' => 'boldgrid_enable_footer',
				'selector' => '.attribution',
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
		)
	)
);
?>

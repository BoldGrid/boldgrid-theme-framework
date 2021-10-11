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
	'page' => 'bgtfw_pages_title_display',

	// Theme mod, determines if title should display on posts.
	'post' => 'bgtfw_posts_title_display',

	'default_page' => '1',
	'default_post' => '1',
	'default_page_for_posts' => 'initial',

	// Editor meta box controls.
	'meta_box' => array(
		'post' => array(
			'global_post_text' => '',
			'show_post_text' => '',
			'hide_post_text' => '',
		),
		'page' => array(
			'global_post_text' => '',
			'show_post_text' => '',
			'hide_post_text' => '',
		),
	),
);

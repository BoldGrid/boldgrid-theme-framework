<?php
/**
 * If over writing these settings, make sure to add %s back into the string
 *
 * @package Boldgrid_Framework
 */

return array(
	'header' => '',
	'footer' => '',
	'navbar-search-form' => '',
	'site-title-tag' => 'h1',
	'site-title-classes' => 'site-title',
	'tagline' => '<h3 class="site-description %s">%s</h3>',
	'tagline-classes' => 'alt-font',
	'entry-header' => '',
	'entry-footer' => '',
	'post_navigation' => array(
		'style' => 'default',
		'paging_nav_classes' => array(
			'next' => 'nav-next',
			'previous' => 'nav-previous',
		),
		'post_nav_classes' => array(
			'next' => 'nav-next',
			'previous' => 'nav-previous',
		),
		'style_configs' => array(
			'buttons' => array(
				'paging_nav_classes' => 'button-primary',
				'post_nav_classes' => 'button-primary',
			),
		),
	),
	'pages' => array(
		'blog' => 'container',
		'global' => array(
			'header' => 'container',
			'footer' => 'container',
		),
		'default' => array(
			'header' => 'container',
			'entry-header' => 'container',
			'entry-content' => 'container',
			'entry-footer' => 'container',
			'footer' => 'container',
		),
		'page_home.php' => array(
			'header' => 'container',
			'entry-header' => 'container',
			'entry-content' => 'container',
			'entry-footer' => 'container',
			'footer' => 'container',
		),
	),
	'generic-location-rows' => array(
		'header' => array(
			array( '1' ),
			array( '2', '3', '4' ),
			array( '14', '15' ),
			array( '5' ),
			array( '8' ),
			array( '6', '7' ),
			array( '9', '10' ),
			array( '11' ),
		),
		'footer' => array(
			array( '2', '3', '4' ),
			array( '5' ),
			array( '6', '7' ),
			array( '8' ),
			array( '9', '10' ),
			array( '11' ),
		),
	),
	'sidebar' => array(
		'is_404',
		'is_search',
		'is_not_bgtfw_sidebar_layout',
	),
	'archives' => array(
		'posted-on' => array(
			'format' => 'date',
			'types' => array(
				'date' => 'Date',
				'timeago' => 'Human Readable',
			),
		),
	),
);

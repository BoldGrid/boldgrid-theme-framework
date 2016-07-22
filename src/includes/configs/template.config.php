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
	'tagline' => '<h3 class="site-description %s">%s</h3>',
	'tagline-classes' => 'alt-font',
	'call-to-action' => '<h2 id="slogan">%s</h2>',
	'call-to-action-button' => '<div class="call-to-action"><p class="p-button-primary"><a class="button-primary" href="%s">%s<i class="fa fa-angle-double-right"></i></a></p></div>',
	'contact-number' => '<div class="phone"><p><i class="fa fa-phone"></i> Call Today <span class="phone-number">%s</span></p></div>',
	'entry-header' => '',
	'entry-footer' => '',
	'pages' => array(
		'blog' => 'container',
		'global' => array(
			'header' => 'container',
			'call-to-action' => '',
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
		'is_front_page',
		'[default]is_page_template',
		'[page_home.php]is_page_template',
	),
);

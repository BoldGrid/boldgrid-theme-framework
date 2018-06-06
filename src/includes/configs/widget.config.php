<?php
/**
 * Widget Configuration Options.
 *
 * @package Boldgrid_Theme_Framework
 * @subpackage Boldgrid_Theme_Framework\Configs
 *
 * @since    1.1
 * @return   array   An array of widget configs.
 */

return array(

	// When the footer is disabled, these widgets will be removed.
	'footer_widgets' => array(),
	'widget_instances' => array(),
	'sidebars' => array(

		// Header Widgets.
		'header-1' => array(
			'name'          => __( 'Header Column 1', 'bgtfw' ),
			'id'            => 'header-1',
			'before_widget' => '<aside class="%2$s widget" id="%1$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		),
		'header-2' => array(
			'name'          => __( 'Header Column 2', 'bgtfw' ),
			'id'            => 'header-2',
			'before_widget' => '<aside class="%2$s widget" id="%1$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		),
		'header-3' => array(
			'name'          => __( 'Header Column 3', 'bgtfw' ),
			'id'            => 'header-3',
			'before_widget' => '<aside class="%2$s widget" id="%1$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		),
		'header-4' => array(
			'name'          => __( 'Header Column 4', 'bgtfw' ),
			'id'            => 'header-4',
			'before_widget' => '<aside class="%2$s widget" id="%1$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		),

		// Footer Widgets.
		'footer-1' => array(
			'name'          => __( 'Footer Column 1', 'bgtfw' ),
			'id'            => 'footer-1',
			'before_widget' => '<aside class="%2$s widget" id="%1$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		),
		'footer-2' => array(
			'name'          => __( 'Footer Column 2', 'bgtfw' ),
			'id'            => 'footer-2',
			'before_widget' => '<aside class="%2$s widget" id="%1$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		),
		'footer-3' => array(
			'name'          => __( 'Footer Column 3', 'bgtfw' ),
			'id'            => 'footer-3',
			'before_widget' => '<aside class="%2$s widget" id="%1$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		),
		'footer-4' => array(
			'name'          => __( 'Footer Column 4', 'bgtfw' ),
			'id'            => 'footer-4',
			'before_widget' => '<aside class="%2$s widget" id="%1$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		),

		// Primary Sidebar Widgets.
		'primary-sidebar' => array(
			'name'          => __( 'Primary Sidebar', 'bgtfw' ),
			'id'            => 'primary-sidebar',
			'description'   => '',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		),
	),
);

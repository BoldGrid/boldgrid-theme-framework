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

$year = date( 'Y' );
$blogname = get_bloginfo( 'name' );
$custom_widgets['footer-company-details'] = <<<HTML
	&copy; $year $blogname | 202 Grid Blvd. Agloe, NY 12776 | <span class="phone-number">777-765-4321</span> | <a href="mailto:">info@example.com</a>
HTML;

return array(
	'force_enable_bstw' => true,
	'force_disable_bstw' => false,
	'footer_widgets' => array(
		// When the footer is disabled, these widgets will be removed.
		'boldgrid-widget-3'
	),
	'widget_instances' => array(
		// This supports adding many widgets to 1 area.
		'boldgrid-widget-3' => array(
			// Specify name as key to assist with lookups.
			'footer-company-details' => array(
				'title' => 'Contact Info',
				'text' => $custom_widgets['footer-company-details'],
				'type' => 'visual',
				'filter' => 1,
				'label' => 'black-studio-tinymce',
			),
		),

	),
	'sidebars' => array(
		'boldgrid-widget-1' => array(
			'name'          => 'Widget 1',
			'id'            => 'boldgrid-widget-1',
			'before_widget' => '<aside class="%2$s widget well" id="%1$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h2 class="widget-title alt-font">',
			'after_title'   => '</h2>',
			'before_bg_widget' => '<aside class="%2$s widget" id="%1$s">',
			'after_bg_widget' => '</aside>',
		),
		'boldgrid-widget-2' => array(
			'name'          => 'Widget 2',
			'id'            => 'boldgrid-widget-2',
			'before_widget' => '<aside class="%2$s widget well" id="%1$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h2 class="widget-title alt-font">',
			'after_title'   => '</h2>',
			'before_bg_widget' => '<aside class="%2$s widget" id="%1$s">',
			'after_bg_widget' => '</aside>',
		),
		'boldgrid-widget-3' => array(
			'name'          => 'Footer Center',
			'id'            => 'boldgrid-widget-3',
			'before_widget' => '<aside class="%2$s widget well" id="%1$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h2 class="widget-title alt-font">',
			'after_title'   => '</h2>',
			'before_bg_widget' => '<aside class="%2$s widget" id="%1$s">',
			'after_bg_widget' => '</aside>',
		),
		// Header Widgets.
		'header-1' => array(
			'name'          => __( 'Header Column 1' ),
			'id'            => 'header-1',
			'before_widget' => '<aside class="widget well %2$s" id="%1$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h2 class="widget-title alt-font">',
			'after_title'   => '</h2>',
		),
		'header-2' => array(
			'name'          => __( 'Header Column 2' ),
			'id'            => 'header-2',
			'before_widget' => '<aside class="widget well %2$s" id="%1$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h2 class="widget-title alt-font">',
			'after_title'   => '</h2>',
		),
		'header-3' => array(
			'name'          => __( 'Header Column 3' ),
			'id'            => 'header-3',
			'before_widget' => '<aside class="widget well %2$s" id="%1$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h2 class="widget-title alt-font">',
			'after_title'   => '</h2>',
		),
		'header-4' => array(
			'name'          => __( 'Header Column 4' ),
			'id'            => 'header-4',
			'before_widget' => '<aside class="widget well %2$s" id="%1$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h2 class="widget-title alt-font">',
			'after_title'   => '</h2>',
		),
		// Footer Widgets.
		'footer-1' => array(
			'name'          => __( 'Footer Column 1' ),
			'id'            => 'footer-1',
			'before_widget' => '<aside class="%2$s widget well" id="%1$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h2 class="widget-title alt-font">',
			'after_title'   => '</h2>',
		),
		'footer-2' => array(
			'name'          => __( 'Footer Column 2' ),
			'id'            => 'footer-2',
			'before_widget' => '<aside class="%2$s widget well" id="%1$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h2 class="widget-title alt-font">',
			'after_title'   => '</h2>',
		),
		'footer-3' => array(
			'name'          => __( 'Footer Column 3' ),
			'id'            => 'footer-3',
			'before_widget' => '<aside class="%2$s widget well" id="%1$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h2 class="widget-title alt-font">',
			'after_title'   => '</h2>',
		),
		'footer-4' => array(
			'name'          => __( 'Footer Column 4' ),
			'id'            => 'footer-4',
			'before_widget' => '<aside class="%2$s widget well" id="%1$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h2 class="widget-title alt-font">',
			'after_title'   => '</h2>',
		),
		'sidebar-1' => array(
			'name'          => __( 'Sidebar #1', 'bgtfw' ),
			'id'            => 'sidebar-1',
			'description'   => '',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h2 class="widget-title alt-font">',
			'after_title'   => '</h2>',
		),
	),
);

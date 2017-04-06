<?php
/**
 * The menu configuration options for bgtfw.
 *
 * @package Boldgrid_Theme_Framework
 * @subpackage Boldgrid_Theme_Framework\Configs
 *
 * @since    1.1
 *
 * @return   array   An array of menu configs used in bgtfw.
 */

return array(
	'action_prefix' => 'boldgrid_menu_',
	'footer_menus' => array(
		// When the footer is disabled, these menus will be removed
		'footer_center'
	),
	'locations' => array(
		'primary' => 'Primary Menu',
		'social' => 'Left Below Primary Navigation',
		'secondary' => 'Right Above Primary Navigation',
		'tertiary' => 'Header Upper Right',
		'footer_center' => 'Footer Center',
	),
	'prototype' => array(
		'primary' => array(
			'theme_location'    => 'primary',
			'depth'             => 0,
			'container'         => 'div',
			'container_class'   => 'collapse navbar-collapse primary-menu',
			'container_id'      => 'primary-navbar',
			'menu_class'        => 'nav navbar-nav',
			'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
			'walker'            => new wp_bootstrap_navwalker(),
			'items_wrap'        => '<ul id="%1$s" class="%2$s">%3$s</ul>',
		),
		'social' => array(
			'theme_location'  => 'social',
			'container'       => 'div',
			'container_id'    => 'menu-social',
			'container_class' => 'social-menu menu-social no-collapse',
			'menu_id'         => 'menu-social-items',
			'menu_class'      => 'nav navbar-nav social-menu-items',
			'depth'           => 0,
			'walker'          => new wp_bootstrap_navwalker(),
			'items_wrap'      => '<nav class="navbar"><ul id="%1$s" class="%2$s" data-sm-skip="true">%3$s</ul></nav>',
			'fallback_cb'     => '',
		),
		'secondary' => array(
			'theme_location'   => 'secondary',
			'container'        => 'div',
			'container_id'     => 'secondary-menu',
			'container_class'  => 'secondary-menu no-collapse',
			'menu_id'          => 'secondary-menu-items',
			'menu_class'       => 'nav navbar-nav secondary-menu-items',
			'dropdown_flatten' => true,
			'depth'            => 0,
			'walker'           => new wp_bootstrap_navwalker(),
			'items_wrap'       => '<nav class="navbar"><ul id="%1$s" class="%2$s" data-sm-skip="true">%3$s</ul></nav>',
			'fallback_cb'      => '',
		),
		'tertiary' => array(
			'theme_location'   => 'tertiary',
			'container'        => 'div',
			'container_id'     => 'tertiary-menu',
			'container_class'  => 'tertiary-menu no-collapse',
			'menu_id'          => 'tertiary-menu-items',
			'menu_class'       => 'nav navbar-nav tertiary-menu-items',
			'dropdown_flatten' => true,
			'depth'            => 0,
			'walker'           => new wp_bootstrap_navwalker(),
			'items_wrap'       => '<nav class="navbar"><ul id="%1$s" class="%2$s" data-sm-skip="true">%3$s</ul></nav>',
			'fallback_cb'      => '',
		),
		'footer_center' => array(
			'theme_location'   => 'footer_center',
			'container'        => 'div',
			'container_id'     => 'footer_center-menu',
			'container_class'  => 'footer_center-menu no-collapse',
			'menu_id'          => 'footer_center-menu-items',
			'menu_class'       => 'nav navbar-nav footer_center-menu-items',
			'dropdown_flatten' => true,
			'depth'            => 0,
			'walker'           => new wp_bootstrap_navwalker(),
			'items_wrap'       => '<nav class="navbar"><ul id="%1$s" class="%2$s" data-sm-skip="true">%3$s</ul></nav>',
			'fallback_cb'      => '',
		),
	),
	'default-menus' => array(
		'social' => array(
			'label' => 'Social Media',
			'location' => 'social',
			'items' => array(
				array(
					'menu-item-title' => __( 'Facebook' ),
					'menu-item-classes' => 'facebook',
					'menu-item-url' => '//facebook.com',
					'menu-item-status' => 'publish',
					'menu-item-attr-title' => __( 'Facebook' ),
					'menu-item-target' => '_blank',
				),
				array(
					'menu-item-title' => __( 'Twitter' ),
					'menu-item-classes' => 'twitter',
					'menu-item-url' => '//twitter.com',
					'menu-item-status' => 'publish',
					'menu-item-attr-title' => __( 'Twitter' ),
					'menu-item-target' => '_blank',
				),
				array(
					'menu-item-title' => __( 'Google Plus' ),
					'menu-item-classes' => 'google',
					'menu-item-url' => '//plus.google.com',
					'menu-item-status' => 'publish',
					'menu-item-attr-title' => __( 'Google Plus' ),
					'menu-item-target' => '_blank',
				),
				array(
					'menu-item-title' => __( 'LinkedIn' ),
					'menu-item-classes' => 'linkedin',
					'menu-item-url' => '//linkedin.com',
					'menu-item-status' => 'publish',
					'menu-item-attr-title' => __( 'LinkedIn' ),
					'menu-item-target' => '_blank',
				),
				array(
					'menu-item-title' => __( 'Youtube' ),
					'menu-item-classes' => 'youtube',
					'menu-item-url' => '//youtube.com',
					'menu-item-status' => 'publish',
					'menu-item-attr-title' => __( 'Youtube' ),
					'menu-item-target' => '_blank',
				),
			),
		),
	),
);

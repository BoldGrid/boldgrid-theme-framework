<?php
/**
 * Plugin Name: BoldGrid Theme Framework
 * Plugin URI: https://www.boldgrid.com/docs/configuration-file
 * Description: BoldGrid Theme Framework is a library that allows you to easily make BoldGrid themes. Please see our reference guide for more information: https://www.boldgrid.com/docs/configuration-file
 * Version: 2.1.1
 * Author: BoldGrid.com <wpb@boldgrid.com>
 * Author URI: https://www.boldgrid.com/
 * Text Domain: bgtfw
 * Domain Path: /languages
 * License: GPL-3.0-or-later
 *
 * @package Boldgrid_Theme_Framework
 * @license GPL-3.0-or-later
 *
 * Copyright (C) 2019 BoldGrid
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 */

/**
 * Load the BoldGrid Framework Into Our Themes.
 *
 * @since 1.0.0
 */
$theme_framework_path = get_template_directory() . '/inc/boldgrid-theme-framework';
if ( defined( 'BGTFW_PATH' ) ) {
	$theme_framework_path = ABSPATH . BGTFW_PATH;
}

$theme_framework_class = $theme_framework_path . '/includes/class-boldgrid-framework.php';

if ( file_exists( $theme_framework_class ) ) {
	if ( version_compare( phpversion(), '5.4.0', '<' ) ) {
		require_once $theme_framework_path . '/includes/class-boldgrid-framework-version-requirements.php';
		$requirements = new Boldgrid_Framework_Version_Requirements();
		$requirements->add_hooks();
	} else {
		require_once $theme_framework_class;
		$boldgrid_theme_framework = new Boldgrid_Framework();
		$boldgrid_theme_framework->run();
	}
}

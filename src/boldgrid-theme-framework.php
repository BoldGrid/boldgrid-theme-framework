<?php
/**
 * Plugin Name: BoldGrid Theme Framework
 * Plugin URI: https://www.boldgrid.com/docs/configuration-file
 * Description: BoldGrid Theme Framework is a library that allows you to easily make BoldGrid themes. Please see our reference guide for more information: https://www.boldgrid.com/docs/configuration-file
 * Version: 2.18.1
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
$bgtfw_path = get_template_directory() . '/inc/boldgrid-theme-framework';
if ( defined( 'BGTFW_PATH' ) ) {
	$bgtfw_path = ABSPATH . BGTFW_PATH;
}

if ( ! defined( 'FONTAWESOME_DIR_PATH' ) ) {
	define( 'FONTAWESOME_DIR_PATH', WP_PLUGIN_DIR . '/font-awesome' );
}

$bgtfw_class = $bgtfw_path . '/includes/class-boldgrid-framework.php';

if ( file_exists( $bgtfw_class ) ) {
	if ( version_compare( phpversion(), '5.6.0', '<' ) ) {
		require_once $bgtfw_path . '/includes/class-boldgrid-framework-version-requirements.php';
		$bgtfw_requirements = new Boldgrid_Framework_Version_Requirements();
		$bgtfw_requirements->add_hooks();
	} else {
		require_once $bgtfw_class;
		$bgtfw_framework = new Boldgrid_Framework();
		$bgtfw_framework->run();
	}
}

<?php
/**
 * Customizer Starter Content Configs.
 *
 * configs['starter-content']               These configs make up the actual starter content, such as
 *                                          posts, widgets, etc. These ARE NOT those configs.
 * configs['customizer']['starter-content'] These configs are for setting up admin related Starter
 *                                          Content pages, such as "starter content suggest". These
 *                                          ARE those configs.
 *
 * @package Boldgrid_Theme_Framework
 * @subpackage Boldgrid_Theme_Framework\Configs
 *
 * @since    x.x.x
 *
 * @return   array   An array of typography configs.
 */

return array(
	// The "Return to dashboard" link given to users after successfully installing starter content.
	'return_to_dashboard' => admin_url(),
	// URL to the "Starter Content" page in the dashboard.
	'dashboard_url' => '',
);

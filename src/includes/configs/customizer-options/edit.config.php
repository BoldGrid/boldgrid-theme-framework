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
	'custom_logo' => array(
		'edit_vars' => array(
			array(
				'selector'    => '#masthead .custom-logo-link',
				'label'       => esc_html__( 'Change Logo', 'bgtfw' ),
				'description' => esc_html__( 'Upload or change your site logo.', 'bgtfw' ),
			)
		),
	),
);

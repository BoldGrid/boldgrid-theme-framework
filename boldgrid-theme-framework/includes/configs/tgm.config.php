<?php
return array(
	// Enable the plugin activation or not.
	'enabled' => true,
	/*
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	'plugins' => array(
		array(
			'name'      => 'BoldGrid Inspirations',
			'slug'      => 'boldgrid-inspirations',
			'source'    => 'https://repo.boldgrid.com/boldgrid-inspirations.zip',
			'required'  => false, // If false, the plugin is only 'recommended' instead of required.
		),
		array(
			'name'      => 'BoldGrid Editor',
			'slug'      => 'boldgrid-editor',
			'source'    => 'https://repo.boldgrid.com/boldgrid-editor.zip',
			'required'  => false, // If false, the plugin is only 'recommended' instead of required.
		),
	),

	/*
	 * Array of configuration settings. Amend each line as needed.
	 *
	 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
	 * strings available, please help us make TGMPA even better by giving us access to these translations or by
	 * sending in a pull-request with .po file(s) with the translations.
	 *
	 * Only uncomment the strings in the config array if you want to customize the strings.
	 */
	'configs' => array(
		'id'           => 'bgtfw',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'bgtfw-install-plugins', // Menu slug.
		'parent_slug'  => 'plugins.php',            // Parent menu slug.
		'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
		'strings' => array(
			'menu_title'   => __( 'Recommended Plugins', 'bgtfw' ),
			'page_title'   => __( 'Install Recommended Plugins', 'bgtfw' ),
		),
	),
);

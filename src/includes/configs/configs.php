<?php
$theme_framework_dir = get_template_directory()
	. DIRECTORY_SEPARATOR . 'inc'
	. DIRECTORY_SEPARATOR . 'boldgrid-theme-framework';

$theme_framework_uri = get_template_directory_uri()
	. '/inc/boldgrid-theme-framework';

return array(

	// temp configs rolling out to themes
	'temp' => array(
		'attribution_links'    => false,
	),

	// Required From Theme - these are defaults
	'theme_name' => 'boldgrid-theme',
	'version' => wp_get_theme()->Version,
	'theme_id' => null,
	'boldgrid-parent-theme' => false,
	'bootstrap' => false,

	// End Required
	'text_domain' => 'boldgrid-theme-framework',

	'font' => array(
		'translators' => 'on',
		'types' => array(
			'Roboto:300,400,500,700,900|Oswald'
		 ),
	),

	'framework' => array(
		'asset_dir'       => $theme_framework_dir . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR,
		'includes_dir'    => $theme_framework_dir . '/includes/',
		'black_studio'    => $theme_framework_uri . '/includes/black-studio-tinymce-widget/',
		'root_uri'        => $theme_framework_uri . '/',
		'admin_asset_dir' => $theme_framework_uri . '/assets/',
		'js_dir'          => $theme_framework_uri . '/assets/js/',
		'css_dir'         => $theme_framework_uri . '/assets/css/',
		'inline_styles'   => false,
	),

	/**
	 * Social Media Icons as Menu Items preferences.
	 *
	 * @since 1.0.0
	 */
	'social-icons' => array(

		// true, false
		'hide-text'   => true,

		// normal, large, 2x, 3x, 4x, 5x
		'size'        => '2x',

		// currently supports icon, icon-sign
		'type'        => 'icon',

	),

	/**
	 * Optional scripts a theme may wish to use.  Set to false by default unless theme requests them.
	 *
	 * @since 1.0.0
	 */
	'scripts' => array(
		'boldgrid-sticky-nav'     => false,
		'boldgrid-sticky-footer'  => false,
		'wow-js'                  => false,
		'animate-css'             => false,
	),

	/**
	 * No Post Format Styles are required by default
	 * Theme authors can add post formats here. Eventually post formats will be required
	 * and can be added here
	 *
	 * @since 1.0.4
	 */
	'post_formats' => array(),

	/**
	 * Customizer Specific Configurations
	 *
	 * @since 1.0.0
	 */
	'customizer-options' => array(
		'site_logo'      => true,
		'header_panel'    => true,
		'header_controls' => array(
			'widgets'     => true,
			'custom_html' => true,
		),

		'footer_panel'    => true,
		'footer_controls' => array(
			'widgets'     => true,
			'custom_html' => true,
		),

		'advanced_panel' => true,
		'advanced_controls' => array(
			'css_editor' => true,
			'js_editor'  => true,
		),
	),
);

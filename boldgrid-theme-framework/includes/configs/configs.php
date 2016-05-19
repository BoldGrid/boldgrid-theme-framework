<?php
$theme_framework_dir = realpath( plugin_dir_path ( __FILE__ ) . '../..' );

$theme_framework_uri = get_template_directory_uri()
	. '/inc/boldgrid-theme-framework';

if ( defined( 'BGTFW_PATH' ) ) {
	$theme_framework_uri = get_site_url() . BGTFW_PATH;
}

return array(

	// Temp configs rolling out to themes.
	'temp' => array(
		'attribution_links'    => false,
	),

	// Required From Theme - these are defaults.
	'theme_name' => 'boldgrid-theme',
	'theme-parent-name' => 'prime',
	'version' => wp_get_theme()->Version,
	'theme_id' => null,
	'boldgrid-parent-theme' => false,
	'bootstrap' => false,

	// End Required.
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
		'hide-text'   => true,
		'size'        => '2x',
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
		'offcanvas-menu'          => false,
		'options' => array(
			'wow-js' => array(
				'enabled'      => false,
				'boxClass'     => 'wow',
				'animateClass' => 'animated',
				'offset'       => 0,
				'mobile'       => true,
				'live'         => true,
			),
			'nicescroll' => array(
				'enabled'                    => false,
				'selector'                   => 'html', // Main Selector to apply scroll effect to.
				'cursorcolor'                => '#424242', // Change cursor color in hex.
				'cursoropacitymin'           => 0, // Change opacity when cursor is inactive (scrollabar "hidden" state), range from 1 to 0
				'cursoropacitymax'           => 1, // Change opacity when cursor is active (scrollabar "visible" state), range from 1 to 0
				'cursorwidth'                => '5px', // Cursor width in pixel (you can also write "5px")
				'cursorborder'               => '1px solid #fff', // CSS definition for cursor border
				'cursorborderradius'         => '5px', // Border radius in pixel for cursor
				'zindex'                     => 'auto', // Change z-index for scrollbar div
				'scrollspeed'                => 60, // Scrolling speed
				'mousescrollstep'            => 40, // Scrolling speed with mouse wheel (pixel)
				'touchbehavior'              => false, // Enable cursor-drag scrolling like touch devices in desktop computer
				'hwacceleration'             => true, // Use hardware accelerated scroll when supported
				'boxzoom'                    => false, // enable zoom for box content
				'dblclickzoom'               => true, // (only when boxzoom=true) zoom activated when double click on box
				'gesturezoom'                => true, // (only when boxzoom=true and with touch devices) zoom activated when pinch out/in on box
				'grabcursorenabled'          => true, // (only when touchbehavior=true) display "grab" icon
				'autohidemode'               => true, // how hide the scrollbar works.
				'background'                 => '', // change css for rail background
				'iframeautoresize'           => true, // autoresize iframe on load event
				'cursorminheight'            => 32, // set the minimum cursor height (pixel)
				'preservenativescrolling'    => true, // you can scroll native scrollable areas with mouse, bubbling mouse wheel event
				'railoffset'                 => false, // you can add offset top/left for rail position
				'bouncescroll'               => false, // Enable scroll bouncing at the end of content as mobile-like (Only hw accell).
				'spacebarenabled'            => true, // Enable page down scrolling when space bar has pressed.
				'railpadding'                => array( // Set padding for rail bar.
					'top'    => 0,
					'right'  => 0,
					'left'   => 0,
					'bottom' => 0,
				),
				'disableoutline'             => true, // For chrome browser, disable outline (orange highlight) when selecting a div with nicescroll.
				'horizrailenabled'           => true, // Nicescroll can manage horizontal scroll
				'railalign'                  => 'right', // Alignment of vertical rail
				'railvalign'                 => 'bottom', // Alignment of horizontal rail
				'enabletranslate3d'          => true, // Nicescroll can use css translate to scroll content
				'enablemousewheel'           => true, // Nicescroll can manage mouse wheel events
				'enablekeyboard'             => true, // Nicescroll can manage keyboard events
				'smoothscroll'               => true, // Scroll with ease movement
				'sensitiverail'              => true, // Click on rail make a scroll
				'enablemouselockapi'         => true, // Can use mouse caption lock API (same issue on object dragging)
				'cursorfixedheight'          => false, // Set fixed height for cursor in pixel
				'hidecursordelay'            => 400, // Set the delay in microseconds to fading out scrollbars
				'directionlockdeadzone'      => 6, // Dead zone in pixels for direction lock activation
				'nativeparentscrolling'      => true, // Detect bottom of content and let parent to scroll, as native scroll does
				'enablescrollonselection'    => true, // Enable auto-scrolling of content when selection text
				'cursordragspeed'            => 0.3, // Speed of selection when dragged with cursor
				'rtlmode'                    => 'auto', // Horizontal div scrolling starts at left side
				'cursordragontouch'          => false, // Drag cursor in touch / touchbehavior mode also
				'oneaxismousemode'           => 'auto', // It permits horizontal scrolling with mousewheel on horizontal only content, if false (vertical-only) mousewheel don't scroll horizontally, if value is auto detects two-axis mouse
				'scriptpath'                 => '', // Define custom path for boxmode icons ("" => same script path)
				'preventmultitouchscrolling' => true, // Prevent scrolling on multitouch events
				'disablemutationobserver'    => false, // Force MutationObserver disabled.
			),
		),
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

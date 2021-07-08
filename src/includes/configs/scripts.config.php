<?php
/**
 * Optional scripts a theme may wish to use.
 *
 * Set to false by default unless theme requests them.
 *
 * @package Boldgrid_Theme_Framework
 * @subpackage Boldgrid_Theme_Framework\Configs
 *
 * @since    1.1
 *
 * @return   array   An array of site title configs.
 */

return array(
	'boldgrid-sticky-nav'     => false,
	'boldgrid-sticky-footer'  => false,
	'wow-js'                  => true,
	'animate-css'             => false,
	'options' => array(
		'wow-js' => array(
			'enabled'      => false,
			'boxClass'     => 'wow',
			'animateClass' => 'animated',
			'offset'       => -200,
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
		'goup' => array(
			'enabled'          => true, // Enable jQuery Go Up Plugin ( Scroll To Top ).
			'location'         => 'right', // On which side the button will be shown ("left" or "right").
			'locationOffset'   => 30, // Pixels from the button is distant from the edge of the screen, based on set location..
			'bottomOffset'     => 30, // Pixels from the bottom edge of screen.
			'containerSize'    => 40, // The width and height of the button (minimum is 20).
			'containerRadius'  => '4px', // Let you transform a square in a circle.
			'containerClass'   => 'goup-container color1-background-color color-1-text-contrast', // The class name given to the button container.
			'arrowClass'       => 'goup-arrow', // The class name given to the arrow container
			'containerColor'   => '#000', // The color of the container (in hex format).
			'arrowColor'       => false, // The color of the container (in hex format).
			'trigger'          => 500, // After how many scrolled down pixels the button must be shown (bypassed by alwaysVisible).
			'entryAnimation'   => 'fade', // The animation of the show and hide events of the button ("slide" or "fade").
			'alwaysVisible'    => false, // Set to true if u want the button to be always visible (bypass trigger).
			'goupSpeed'        => 'slow', // The speed at which the user will be brought back to the top ("slow", "normal" or "fast").
			'hideUnderWidth'   => 500, // The threshold of window width under which the button is permanently hidden.
			'title'            => '', // A text to show on the button mouse hover.
			'titleAsText'      => false, // If true the hover title becomes a true text under the button.
			'titleAsTextClass' => 'goup-text', // The class name given to the title text.
			'zIndex'           => 1, // Set the z-index.
		),
	),
);

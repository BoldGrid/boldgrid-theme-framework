/**
 * Typography Live Preview JavaScript.
 * This file is responsible for displaying customizer output
 * in the previewer window.  Controls are managed from the
 * customizer.typography.controls.js
 */
( function( $ ) {
	
	"use strict";

	// Check each active nav menu location in customizer.	
	var $menus = parent.wp.customize.section( 'menu_locations' ).controls();

	// Loop through nav menus for live preview changes.
	$menus.forEach( function( id ) {

		// Set menu location font size's for live previews without refreshes
		wp.customize( 'navigation_' + id.themeLocation +'_font_size', function( value ) {
			value.bind( function( to ) {
				$( '.' + id.themeLocation.replace( /_/g, '-' ) + '-menu ul li a' ).css( 'font-size', to + 'px' );
			});
		});

		// Set menu location's text transform for live previews without refreshes
		wp.customize( 'navigation_' + id.themeLocation +'_text_transform', function( value ) {
			value.bind( function( to ) {
				$( '.' + id.themeLocation.replace( /_/g, '-' ) + '-menu ul li a' ).css( 'text-transform', to );
			});
		});

	});

	// Set font size on main body text live
	wp.customize( 'body_font_size', function( value ) {
		value.bind( function( to ) {
			$( 'p:not( .site-title )' ).css( 'font-size', to + 'px' );
		});
	});

	// Set font-size of headings live
	wp.customize( 'headings_font_size', function( value ) {
		value.bind( function( to ) {
			$( 'h1:not( .site-title, .alt-font )' )
				.css( 'font-size', Math.floor( to * 2.6 ) + 'px' );
			$( 'h2:not( .alt-font )' )
				.css( 'font-size', Math.floor( to * 2.15 ) + 'px' );
			$( 'h3:not( .alt-font, .site-description )' )
				.css( 'font-size', Math.ceil( to * 1.7 ) + 'px' );
			$( 'h4:not( .alt-font )' )
				.css( 'font-size', Math.ceil( to * 1.25 ) + 'px' );
			$( 'h5:not( .alt-font )' )
				.css( 'font-size', to + 'px' );
			$( 'h6:not( .alt-font )' )
				.css( 'font-size', Math.ceil( to * 0.85 ) + 'px' );
		});
	});

	// Set text-transform on headings live
	wp.customize( 'headings_text_transform', function( value ) {
		value.bind( function( to ) {
			$( ':header:not( .site-title, .alt-font, .site-description )' ).css( 'text-transform', to );
		});
	});

	// Set font-size of headings live
	wp.customize( 'alternate_headings_font_size', function( value ) {
		value.bind( function( to ) {
			$( 'h1.alt-font' )
				.css( 'font-size', Math.floor( to * 2.6 ) + 'px' );
			$( 'h2.alt-font' )
				.css( 'font-size', Math.floor( to * 2.15 ) + 'px' );
			$( 'h3.alt-font' )
				.css( 'font-size', Math.ceil( to * 1.7 ) + 'px' );
			$( 'h4.alt-font' )
				.css( 'font-size', Math.ceil( to * 1.25 ) + 'px' );
			$( 'h5.alt-font' )
				.css( 'font-size', to + 'px' );
			$( 'h6.alt-font' )
				.css( 'font-size', Math.ceil( to * 0.85 ) + 'px' );
		});
	});

	// Set text-transform on alternate headings live
	wp.customize( 'alternate_headings_text_transform', function( value ) {
		value.bind( function( to ) {
			$( ':header.alt-font' ).css( 'text-transform', to );
		});
	});
	
	// Set logo line height on site title text live
	wp.customize( 'body_line_height', function( value ) {
		value.bind( function( to ) {
			$( 'p' ).css( 'line-height', to + '%' );
		});
	});

	// Set navigation font size live
	wp.customize( 'navigation_text_transform', function( value ) {
		value.bind( function( to ) {
			$( '#site-navigation' ).css( 'text-transform', to );
		});
	});

	// Set font size on navigation live in customizer
	wp.customize( 'navigation_font_size', function( value ) {
		value.bind( function( to ) {
			$( '#site-navigation' ).css( 'font-size', to + 'px' );
		});
	});

	// Set font size on site title text live
	wp.customize( 'logo_font_size', function( value ) {
		value.bind( function( to ) {
			$( '.site-title' ).css( 'font-size', to + 'px' );
		});
	});

	// Set text-transform on site title text live
	wp.customize( 'logo_text_transform', function( value ) {
		value.bind( function( to ) {
			$( '.site-title' ).css( 'text-transform', to );
		});
	});

	// Set text-decoration on site title text live
	wp.customize( 'logo_text_decoration', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).css( 'text-decoration', to );
		});
	});

	// Set hover text-decoration on site title text live
	wp.customize( 'logo_text_decoration_hover', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).hover( function(  ) {
				$( this ).css( 'text-decoration', to );
			},
			function(  ){
				$( this ).css( 'text-decoration', parent.wp.customize( 'logo_text_decoration' ).get() );
			});
		});
	});

	// Set logo margin top on site title text live
	wp.customize( 'logo_margin_top', function( value ) {
		value.bind( function( to ) {
			$( '.site-title' ).css( 'margin-top', to + 'px' );
		});
	});

	// Set logo margin bottom on site title text live
	wp.customize( 'logo_margin_bottom', function( value ) {
		value.bind( function( to ) {
			$( '.site-title' ).css( 'margin-bottom', to + 'px' );
		});
	});

	// Set logo horizontal margin on site title text live
	wp.customize( 'logo_margin_left', function( value ) {
		value.bind( function( to ) {
			$( '.site-title' ).css( 'margin-left', to + 'px' );
		});
	});

	// Set logo line height on site title text live
	wp.customize( 'logo_line_height', function( value ) {
		value.bind( function( to ) {
			$( '.site-title' ).css( 'line-height', to + '%' );
		});
	});

	// Set logo letter spacing on site title text live
	wp.customize( 'logo_letter_spacing', function( value ) {
		value.bind( function( to ) {
			$( '.site-title' ).css( 'letter-spacing', to + 'px' );
		});
	});

})( jQuery );
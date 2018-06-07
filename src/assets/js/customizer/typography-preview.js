/**
 * Typography Live Preview JavaScript.
 * This file is responsible for displaying customizer output
 * in the previewer window.  Controls are managed from the
 * customizer.typography.controls.js
 */
( function( $ ) {

	'use strict';

	// Generate the live preview CSS for headings control.
	var bgtfw_headings_typography = function( to ) {
		var size, base, unit, validUnits, head, style, css = '';

		if ( _.isUndefined( to ) ) {
			to = wp.customize( 'bgtfw_headings_typography' )();
		}

		if ( _.isString( to ) ) {
			to = JSON.parse( to );
		}

		size = to['font-size'];
		base = size.replace( /[^0-9.]/gi, '' );
		unit = size.replace( /[^a-z]/gi, '' );

		validUnits = [ 'fr', 'rem', 'em', 'ex', '%', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'ch', 'vh', 'vw', 'vmin', 'vmax' ];

		// Check for valid units, default to pixels otherwise.
		if ( ( 'auto' !== unit && 'inherit' !== unit && 'initial' !== unit && -1 === $.inArray( unit, validUnits ) ) || _.isEmpty( unit ) ) {
			unit = 'px';
		}

		// Build CSS.
		_.each( _typographyOptions, function( selector, rule ) {
			var val;
			if ( 'headings' === selector.type ) {
				val = base * selector.amount;
				if ( 'ceil' === selector.round ) {
					val = Math.ceil( val );
				}
				if ( 'floor' === selector.round ) {
					val = Math.floor( val );
				}
				css += rule + '{font-size:' + val + unit + ';}';
			}
		} );

		// Set CSS in the innerHTML of stylesheet or create a new stylesheet to append to head.
		if ( !! document.getElementById( 'bgtfw-headings-typography' ) ) {
			document.getElementById( 'bgtfw-headings-typography' ).innerHTML = css;
		} else {
			head = document.head || document.getElementsByTagName( 'head' )[0],
			style = document.createElement( 'style' );
			style.type = 'text/css';
			style.id = 'bgtfw-headings-typography';

			if ( style.styleSheet ) {
				style.styleSheet.cssText = css;
			} else {
				style.appendChild( document.createTextNode( css ) );
			}

			head.appendChild( style );
		}

		// Check if kirki's post-message already applied inline CSS and move our CSS after for override.
		if ( !! document.getElementById( 'kirki-postmessage-bgtfw_headings_typography' ) ) {
			$( '#bgtfw-headings-typography' ).insertAfter( '#kirki-postmessage-bgtfw_headings_typography' );
		}
	};

	// Set font-size of headings live.
	wp.customize( 'bgtfw_headings_typography', function( value ) {
		value.bind( function( to ) {
			bgtfw_headings_typography( to );
		} );
	} );

	// Set font-size of headings when previewer is ready.
	wp.customize.bind( 'preview-ready', bgtfw_headings_typography );
} )( jQuery );

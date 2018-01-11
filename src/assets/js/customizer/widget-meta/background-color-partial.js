/* eslint max-nested-callbacks: [ "error", 4 ], consistent-this: [ "error", "partial" ] */

wp.customize.selectiveRefresh.partialConstructor.sidebar_meta_background_color = (function( api, $ ) {
	'use strict';

	return api.selectiveRefresh.Partial.extend( {

		/**
		 * Refresh.
		 *
		 * Override refresh behavior to apply changes with JS instead of doing
		 * a selective refresh request for PHP rendering (since unnecessary).
		 *
		 * @returns {jQuery.promise}
		 */
		refresh: function() {
			var partial = this, backgroundColorSetting;

			backgroundColorSetting = api( partial.params.primarySetting );
			_.each( partial.placements(), function( placement ) {
				var palette, modified, color;
				color = parent.net.brehaut.Color( backgroundColorSetting.get() ).toCSS();
				palette = BOLDGRID.Customizer.Util.getInitialPalettes();

				// Strip bgtfw traces of background colors from element.
				placement.container.parent( '.sidebar' );

				modified = palette.map( function( c ) {
					return parent.net.brehaut.Color( c ).toCSS();
				});

				if ( _( modified ).contains( color ) ) {
					placement.container.parent( '.sidebar' )
						.attr( 'style', 'padding: 0px 1em;' )
						.addClass( 'color' + Math.abs( _( modified ).indexOf( color ) + 1 ) + '-background-color' );
				} else {
					placement.container.parent( '.sidebar' ).removeClass( function( index, className ) {

						/**
						 * Matches classes starting with "color" followed by a single 0-9
						 * number, then "-background" (optionally matches background-color as well).
						 * Regexr: https://regexr.com/3ib0g
						 */
						return ( className.match( /(^|\s)color\d-background(?:-color)?/g ) || [] ).join( ' ' );
					} ).css( 'background-color', backgroundColorSetting.get() );
				}
			} );

			// Return resolved promise since no server-side selective refresh will be requested.
			return $.Deferred().resolve().promise();
		}
	} );
})( wp.customize, jQuery );

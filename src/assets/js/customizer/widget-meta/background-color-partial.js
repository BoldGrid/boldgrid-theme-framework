/* eslint max-nested-callbacks: [ "error", 4 ], consistent-this: [ "error", "partial" ] */

import { PaletteSelector } from '../color/palette-selector';

// eslint-disable-next-line camelcase
wp.customize.selectiveRefresh.partialConstructor.sidebar_meta_background_color = ( function( api, $ ) {
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
			var partial = this,
				backgroundColorSetting;

			backgroundColorSetting = api( partial.params.primarySetting );
			_.each( partial.placements(), function( placement ) {
				var colorClassPrefix = new PaletteSelector().getColorNumber( backgroundColorSetting.get() );

				placement.container.parent( '.sidebar' ).removeClass( function( index, css ) {
					return ( css.match( /(^|\s)color-?([\d]|neutral)-(background|text)\S+/g ) || [] ).join( ' ' );
				} );

				if ( ! ~colorClassPrefix.indexOf( 'neutral' ) ) {
					colorClassPrefix = colorClassPrefix.replace( '-', '' );
				}
				if ( ! colorClassPrefix ) {
					placement.container.parent( '.sidebar' ).addClass( 'transparent-background-color ' + 'transparent-text-default' );
				} else {
					placement.container.parent( '.sidebar' ).removeClass( 'transparent-background-color ' + 'transparent-text-default' );
					placement.container.parent( '.sidebar' ).addClass( colorClassPrefix + '-background-color ' + colorClassPrefix + '-text-default' );
				}
			} );

			// Return resolved promise since no server-side selective refresh will be requested.
			return $.Deferred().resolve().promise();
		}
	} );
} )( wp.customize, jQuery );

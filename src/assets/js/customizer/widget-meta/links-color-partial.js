/* eslint max-nested-callbacks: [ "error", 4 ], consistent-this: [ "error", "partial" ] */

import { PaletteSelector } from '../color/palette-selector';

// eslint-disable-next-line camelcase
wp.customize.selectiveRefresh.partialConstructor.sidebar_meta_links_color = ( function( api, $ ) {
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
			linksColorSetting;

			linksColorSetting = api( partial.params.primarySetting );

			_.each( partial.placements(), function( placement ) {
				var colorClass = new PaletteSelector().getColorNumber( linksColorSetting.get() );

				$( placement.partial.params.selector ).parent( '.sidebar' ).removeClass( function( index, css ) {
					return ( css.match( /(^|\s)color-?([\d]|neutral)-(link)\S+/g ) || [] ).join( ' ' );
				} ).addClass( colorClass + '-link-color' );
			} );

			// Return resolved promise since no server-side selective refresh will be requested.
			return $.Deferred().resolve().promise();
		}
	} );
} )( wp.customize, jQuery );

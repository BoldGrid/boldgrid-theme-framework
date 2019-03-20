/* eslint max-nested-callbacks: [ "error", 4 ], consistent-this: [ "error", "partial" ] */

import { Preview } from '../preview';
import { PaletteSelector } from '../color/palette-selector';

// eslint-disable-next-line camelcase
wp.customize.selectiveRefresh.partialConstructor.sidebar_meta_headings_color = ( function( api, $ ) {
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
				headingsColorSetting;

			headingsColorSetting = api( partial.params.primarySetting );

			_.each( partial.placements(), function( placement ) {
				var color = new PaletteSelector().getColor( headingsColorSetting.get() ),
					id = 'dynamic-sidebar-' + placement.partial.id.match( /\[(.*?)\]/ ).pop() + '-inline-css';

				new Preview().updateDynamicStyles( id, `
					${placement.partial.params.selector} {
						color: ${color};
					}
				` );
			} );

			// Return resolved promise since no server-side selective refresh will be requested.
			return $.Deferred().resolve().promise();
		}
	} );
} )( wp.customize, jQuery );

/* eslint max-nested-callbacks: [ "error", 4 ], consistent-this: [ "error", "partial" ] */

wp.customize.selectiveRefresh.partialConstructor.sidebar_meta_title = (function( api ) {
	'use strict';

	return api.selectiveRefresh.Partial.extend( {

		/**
		 * Refresh.
		 *
		 * @returns {jQuery.promise}
		 */
		refresh: function() {
			var partial = this, titleSetting;

			// Do instant low-fidelity preview before selective refresh responds with high-fidelity PHP-rendering.
			titleSetting = api( partial.params.primarySetting );
			_.each( partial.placements(), function( placement ) {
				placement.container.toggle( '' !== titleSetting.get() );
				placement.container.text( titleSetting.get() );
			} );

			// Request high-fidelity PHP-rendering from the server.
			return api.selectiveRefresh.Partial.prototype.refresh.call( partial );
		}
	} );
})( wp.customize );

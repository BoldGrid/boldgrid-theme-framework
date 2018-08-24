( function() {

	'use strict';

	/*
	 * Load starter content.
	 *
	 * If a request for starter content is passed in this manner:
	 * customize.php?starter_content=default
	 * ... When the customizer is ready, make a request to load that specific set of starter content.
	 */
	wp.customize.bind( 'ready', function () {
		var request,
			data,
			starterContent = /starter_content=([^&]+)/.exec( window.location.search );

		if( starterContent[1] ) {
			data = wp.customize.previewer.query();
			data.starter_content = starterContent[1];

			request = wp.ajax.post( 'load_starter_content', data );
			request.done( function() {
				wp.customize.previewer.refresh();
			} );
		}
	});
} )();

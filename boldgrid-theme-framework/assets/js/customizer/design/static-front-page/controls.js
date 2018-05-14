( function( api ) {
	api.section( 'static_front_page', function( section ) {
		var previousUrl, clearPreviousUrl, previewUrlValue;
		previewUrlValue = api.previewer.previewUrl;
		clearPreviousUrl = function() {
			previousUrl = null;
		};

		/**
		 * Handle expanding of static_front_page section.
		 *
		 * This was renamed to "Homepage" in bgtfw.  This will
		 * direct the user to their home page in the preview window
		 * when they open the section, and stores the previous URL
		 * to redirect them back to once they leave this section.
		 */
		section.expanded.bind( function( isExpanded ) {
			var url;
			if ( isExpanded ) {
				url = api.settings.url.home;
				previousUrl = previewUrlValue.get();
				previewUrlValue.set( url );
				previewUrlValue.bind( clearPreviousUrl );
			} else {
				previewUrlValue.unbind( clearPreviousUrl );
				if ( previousUrl ) {
					previewUrlValue.set( previousUrl );
				}
			}
		} );
	} );
} )( wp.customize );

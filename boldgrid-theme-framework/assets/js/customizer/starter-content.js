( function() {

	'use strict';
	
	var starterContent = /starter_content=([^&]+)/.exec( window.location.search ),
		isLoading = starterContent && starterContent[1],
		notificationComplete,
		notificationInstalling;
	
	// Configure our "Installing..." notification.
	notificationInstalling = new wp.customize.Notification(
		'loading_starter_content',
		{
			dismissible: false,
			message: bgtfwCustomizerStarterContent.notificationInstalling,
			type: 'warning'
		}
	);
	
	// Configure our "Installation complete!" notification.
	notificationComplete = new wp.customize.Notification(
		'starter_content_complete',
		{
			dismissible: true,
			message: bgtfwCustomizerStarterContent.notificationComplete,
			type: 'success'
		}
	);

	/*
	 * Load starter content.
	 *
	 * If a request for starter content is passed in this manner:
	 * customize.php?starter_content=default
	 * ... When the customizer is ready, make a request to load that specific set of starter content.
	 */
	wp.customize.bind( 'ready', function () {
		var request,
			data;

		if( isLoading ) {
			wp.customize.notifications.add( notificationInstalling );
			
			// Ajax call to install starter content.
			data = wp.customize.previewer.query();
			data.starter_content = starterContent[1];
			request = wp.ajax.post( 'load_starter_content', data );
			request.done( function() {
				wp.customize.previewer.refresh();
			} );
			
			// Adjust the notices after the preview is loaded.
			$( window ).one( 'boldgrid_customizer_refresh', function() {
				wp.customize.notifications.remove( 'loading_starter_content' );
				wp.customize.notifications.add( notificationComplete );
			} );
		}
	});
} )();

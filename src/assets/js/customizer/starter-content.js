( function() {

	'use strict';
	
	var api = wp.customize,
		i18n = bgtfwCustomizerStarterContent,
		notificationComplete,
		notificationInstalling;
	
	// Configure our "Installing..." notification.
	notificationInstalling = new api.Notification(
		'loading_starter_content',
		{
			dismissible: false,
			message: i18n.notificationInstalling,
			type: 'warning'
		}
	);
	
	// Configure our "Installation complete!" notification.
	notificationComplete = new api.Notification(
		'starter_content_complete',
		{
			dismissible: true,
			message: i18n.notificationComplete,
			type: 'success'
		}
	);

	/*
	 * Load starter content.
	 *
	 * If a request for starter content is passed in the $_POST (i18n.post), when the customizer is
	 * ready, make a request to load that specific set of starter content.
	 */
	api.bind( 'ready', function () {
		var request,
			data;

		if( i18n.post && i18n.post.starter_content ) {
			api.notifications.add( notificationInstalling );
			
			// Ajax call to install starter content.
			data = api.previewer.query();
			data.starter_content = i18n.post.starter_content;
			
			request = wp.ajax.post( 'load_starter_content', data );
			request.done( function() {
				api.previewer.refresh();
			} );
			
			// Adjust the notices after the preview is loaded.
			$( window ).one( 'boldgrid_customizer_refresh', function() {
				api.notifications.remove( 'loading_starter_content' );
				api.notifications.add( notificationComplete );
			} );
		}
	});
} )();

( function() {

	'use strict';
	
	var api = wp.customize,
		i18n = bgtfwCustomizerStarterContent,
		notificationComplete,
		notificationInstalling,
		notificationFail;
	
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
	
	// Configure our "Failed" notification.
	notificationFail = new api.Notification(
		'starter_content_fail',
		{
			dismissible: true,
			message: i18n.notificationFail,
			type: 'error'
		}
	);

	/*
	 * Load starter content.
	 *
	 * If a request for starter content is passed in the $_POST (i18n.post), when the customizer is
	 * ready, make a request to load that specific set of starter content.
	 */
	api.bind( 'ready', function () {
		if( i18n.install ) {
			api.notifications.add( notificationInstalling );
			
			// Adjust the notices after the preview is loaded.
			$( window ).one( 'boldgrid_customizer_refresh', function() {
				api.notifications.remove( 'loading_starter_content' );
				api.notifications.add( notificationComplete );
			} );
		}
	});
} )();

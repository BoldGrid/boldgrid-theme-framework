/**
 * We know that this event is triggered by wordpress but we can't get it to work
 * This is a temp solution.
 *
 * @param $
 */
(function( $ ) {

	var refresh_event = $.Event( 'boldgrid_customizer_refresh' );
	$window = $( window );

	$window.on( 'message', function( e ) {
		var event = e.originalEvent;

		// Ensure we have a string that's JSON.parse-able.
		if ( typeof event.data !== 'string' || event.data[ 0 ] !== '{' ) {
			return;
		}
		var message = JSON.parse( event.data );
		if ( message.id === 'synced' ) {
			$window.trigger( refresh_event, message );
			hide_nav_controls();
		}
	} );

	// Set menu location font size's for menu's that are assigned to locations.
	$( document ).on( 'customize-preview-menu-refreshed', function() {
		hide_nav_controls();
	});

	/**
	 * Hide any nav controls that aren't needed based on whether or not
	 * a user has a nav menu assigned to a nav location.
	 */
	var hide_nav_controls = function() {
		var $menus = wp.customize.section( 'menu_locations' ).controls();

		// Check all registered menu locations.
		_.each( $menus, function( id ) {
			var menuSelector;
			// Deactivate all controls initially.
			wp.customize.control( 'navigation_' + id.themeLocation + '_typography' ).deactivate({ duration: 0 });

			menuSelector = wp.customize.previewer.container.find( 'iframe' ).contents()
				.find( '.' + id.themeLocation.replace( /_/g, '-' ) + '-menu:not(:has( ul li.menu-social ) )' );

			// Menus found in the previewer, so show controls.
			if ( menuSelector.length ) {
				wp.customize.control( 'navigation_' + id.themeLocation + '_typography' ).activate({ duration: 0 });
			}
		});
	};

})( jQuery );

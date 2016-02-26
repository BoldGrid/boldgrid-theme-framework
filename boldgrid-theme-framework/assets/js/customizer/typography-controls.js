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
		// Ensure we have a string that's JSON.parse-able
		if ( typeof event.data !== 'string' || event.data[ 0 ] !== '{' ) {
			return;
		}
		var message = JSON.parse( event.data );
		if ( message.id == 'synced' ) {
			$window.trigger( refresh_event, message );
			hide_nav_controls();
			hide_alt_font_controls();
		} 
	} );

	// Set menu location font size's for menu's that are assigned to locations.
	$( document ).on( 'customize-preview-menu-refreshed', function( e ) {
		hide_nav_controls();
	});

	/**
	 * Hide any nav controls that aren't needed based on whether or not
	 * a user has a nav menu assigned to a nav location.
	 */
	var hide_nav_controls = function () {
		var $menus = wp.customize.section( 'menu_locations' ).controls();
		// Check all registered menu locations.
		$menus.forEach( function( id ) {
			// Deactivate all controls initially
			wp.customize.control( 'navigation_' + id.themeLocation +'_font_size' ).deactivate();
			wp.customize.control( 'navigation_' + id.themeLocation +'_font_family' ).deactivate();
			wp.customize.control( 'navigation_' + id.themeLocation +'_text_transform' ).deactivate();

			var $menu_selector = wp.customize.previewer.container.find( 'iframe' ).contents()
				.find( 'div.' + id.themeLocation.replace( /_/g, '-' ) + '-menu:not(:has( ul li.menu-social ) )' );
				
			// if menus aren't present in the preview, then hide controls.
			if ( $menu_selector.length ) {
				// hide relevant font size controls
				wp.customize.control( 'navigation_' + id.themeLocation +'_font_size' ).activate();
				// hide relevant font family controls
				wp.customize.control( 'navigation_' + id.themeLocation +'_font_family' ).activate();
				// hide relevant text transform controls
				wp.customize.control( 'navigation_' + id.themeLocation +'_text_transform' ).activate();
			}
		});
	};

	/**
	 * Hide the alternate headings panel if there's no alternate headings to configure.
	 */
	var hide_alt_font_controls = function () {
		$alt_font = wp.customize.previewer.container
			.find( 'iframe[title="Site Preview"]' ).last().contents().find( '.alt-font' );
		if ( ! $alt_font.length ) {
			wp.customize.control( 'alternate_headings_text_transform' ).deactivate();
			wp.customize.control( 'alternate_headings_font_size' ).deactivate();
			wp.customize.control( 'alternate_headings_font_family' ).deactivate();
		} else {
			wp.customize.control( 'alternate_headings_text_transform' ).activate();
			wp.customize.control( 'alternate_headings_font_size' ).activate();
			wp.customize.control( 'alternate_headings_font_family' ).activate();
		}
	};

})( jQuery );
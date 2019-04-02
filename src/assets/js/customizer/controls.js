/**
 * We know that this event is triggered by wordpress but we can't get it to work
 * This is a temp solution.
 *
 * @param $
 */
( function( $ ) {
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
			setup_customizer_diagram();
		}
	} );

	$( function() {
		$( document ).on( 'click', '.open-widgets-section', function() {
			wp.customize.panel( 'widgets' ).focus();
		});
		$( document ).on( 'click', '[data-focus-control]', function() {
			var control = $( this ).data( 'focus-control' );
			var customizer_control = wp.customize.control( control );
			if ( customizer_control ) {
				customizer_control.focus();
			}
		});
		$( document ).on( 'click', '[data-focus-section]', function() {
			var control = $( this ).data( 'focus-section' );
			var customizer_control = wp.customize.section( control );
			if ( customizer_control ) {
				customizer_control.focus();
			}
		});
	});

	var setup_customizer_diagram = function() {
		var help, overlay, highlight;
		help = $( '#accordion-section-boldgrid_customizer_help' );
		overlay = wp.customize.previewer.container.find( 'iframe' ).contents().find( '.overlay-help' );
		highlight = function() {
			overlay.fadeToggle();
			help.toggleClass( 'active' );
		};
		help.on( 'click', function() {
			highlight();
		});
	};
})( jQuery );

// Bind edit post links from previewer.
wp.customize.bind( 'ready', function() {
	wp.customize.previewer.bind( 'edit-post-link', function( editPostLink ) {
		window.location = editPostLink;
	} );
} );

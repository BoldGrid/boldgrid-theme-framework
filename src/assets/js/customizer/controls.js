/**
 * We know that this event is triggered by wordpress but we can't get it to work
 * This is a temp solution.
 *
 * @param $
 */
( function( $ ) {
	var refreshEvent = $.Event( 'boldgrid_customizer_refresh' );
	$window = $( window );

	$window.on( 'message', function( e ) {
		var message,
			event = e.originalEvent;

		// Ensure we have a string that's JSON.parse-able.
		if ( 'string' !== typeof event.data || '{' !== event.data[0] ) {
			return;
		}

		message = JSON.parse( event.data );
		if ( 'synced' === message.id ) {
			$window.trigger( refreshEvent, message );
		}
	} );

	$( function() {
		$( document ).on( 'click', '.open-widgets-section', function() {
			wp.customize.panel( 'widgets' ).focus();
		} );

		$( document ).on( 'click', '[data-focus-control]', function() {
			var control = $( this ).data( 'focus-control' );
			var customizerControl = wp.customize.control( control );
			if ( customizerControl ) {
				customizerControl.focus();
			}
		} );

		$( document ).on( 'click', '[data-focus-section]', function() {
			var control = $( this ).data( 'focus-section' );
			var customizerControl = wp.customize.section( control );
			if ( customizerControl ) {
				customizerControl.focus();
			}
		} );
	} );
} )( jQuery );

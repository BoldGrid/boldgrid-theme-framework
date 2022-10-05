/**
 * We know that this event is triggered by WordPress but we can't get it to work
 * This is a temp solution.
 *
 * @param $
 */
( function( $ ) {
	var refreshEvent   = $.Event( 'boldgrid_customizer_refresh' ),
		$body          = $( 'body' ),
		$themeControls = $( '#customize-theme-controls' ),
		$window        = $( window );

	$window.on( 'message', function( e ) {
		$window.trigger( refreshEvent,  e.originalEvent.data );
	} );

	// Prevent interaction with panels until Customizer fully loads.
	$body.addClass( 'pre-initial-refresh' );
	$themeControls.prop( 'title', BOLDGRID.CUSTOMIZER.data.loadingTitle );

	$( function() {

		// Customizer has fully loaded. Allow for interaction.
		$( window ).one( 'boldgrid_customizer_refresh', function() {
			$body.removeClass( 'pre-initial-refresh' );
			$themeControls.removeAttr( 'title' );
		});

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

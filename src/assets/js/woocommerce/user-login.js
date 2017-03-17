/**
 * This is handles the form switching for a current user login
 * vs a user who wants to register for a shop's account using
 * wooCommerce plugin.
 */
jQuery( document ).ready( function( $ ) {
	$( '.switch-user-form' ).on( 'click', function( e ) {
		e.preventDefault();
		$( this ).closest( '.panel' ).fadeOut( 1000, function() {
			$( this ).siblings( '.panel' )
				.css( 'display', 'flex' )
				.hide()
				.fadeIn( 1000 );
		});
		return false;
	});
});

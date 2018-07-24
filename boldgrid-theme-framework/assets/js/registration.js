/* global jQuery */

( function( $ ) {
	$( function() {
		$( '#toplevel_page_crio-welcome' )
			.removeClass( 'wp-not-current-submenu' )
			.addClass( 'wp-has-current-submenu' )
			.find( 'a' ).first().addClass( 'wp-has-current-submenu', 'wp-menu-open' );
		$( '#menu-settings' )
			.addClass( 'wp-not-current-submenu' )
			.removeClass( 'wp-has-current-submenu' )
			.find( 'a' ).first().removeClass( 'wp-has-current-submenu', 'wp-menu-open' );
	} );
} )( jQuery );

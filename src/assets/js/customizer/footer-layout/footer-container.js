( function( $ ) {

	/**
	 * Update classes for blog layouts.
	 */
	wp.customize( 'footer_container', function( value ) {
		value.bind( function( to ) {
			$( '#colophon' ).find( '.footer-content' ).removeClass( 'container' ).addClass( to );
		} );
	} );
} )( jQuery );

( function( $ ) {

	/**
	 * Update classes for blog layouts.
	 */
	wp.customize( 'footer_container', function( value ) {
		value.bind( function( to ) {
			$( '#colophon' ).removeClass( 'container' ).addClass( to );
		} );
	} );
} )( jQuery );

( function( $ ) {

	/**
	 * Update classes for blog layouts.
	 */
	wp.customize( 'header_container', function( value ) {
		value.bind( function( to ) {
			$( '#navi' ).removeClass( 'container' ).addClass( to );
		} );
	} );
} )( jQuery );

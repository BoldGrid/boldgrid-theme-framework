( function( $ ) {

	/**
	 * Update classes for blog layouts.
	 */
	wp.customize( 'bgtfw_blog_layout', function( value ) {

		// Bind value change.
		value.bind( function( to ) {
			$( '.post' )
				.removeClass( function( index, className ) {
					return ( className.match ( /(^|\s)design-\S+/g ) || [] ).join( ' ' );
				} ).addClass( to );
		} );
	} );
} )( jQuery );

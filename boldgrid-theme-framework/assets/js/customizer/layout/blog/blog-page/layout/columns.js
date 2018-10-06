( function( $ ) {
	/**
	 * Update classes for blog layouts.
	 */
	wp.customize( 'bgtfw_pages_blog_blog_page_layout_columns', function( value ) {

		// Bind value change.
		value.bind( function( to ) {
			$( 'body.blog, body.archive' )
				.removeClass( function( index, className ) {
					return ( className.match ( /(^|\s)col[1-6](?!\S)\s?/g ) || [] ).join( ' ' );
				} ).addClass( 'col' + to );
		} );
	} );
} )( jQuery );

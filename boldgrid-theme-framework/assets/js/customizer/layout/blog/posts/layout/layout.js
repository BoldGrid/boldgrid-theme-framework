( function( api ) {

	/**
	 * Update classes for blog post layouts (full vs. fixed).
	 *
	 * @since 2.0.0
	 */
	api( 'bgtfw_pages_blog_posts_layout_layout', function( value ) {
		value.bind( function( to ) {
			var container = document.getElementById( 'content' );
			container && container.classList.remove( 'container' );
			to && container.classList.add( to );
		} );
	} );
} )( wp.customize );

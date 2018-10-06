( function( api ) {

	/**
	 * Update classes for blog layouts.
	 */
	api( 'bgtfw_header_layout_position', function( value ) {
		value.bind( function( to ) {
			var containers = [ 'navi', 'secondary-menu' ];
			_.each( containers, function( container ) {
				container = document.getElementById( container );
				container && container.classList.remove( 'container' );
				if ( to === 'header-top' && ! _.isEmpty( api( 'header_container' )() ) ) {
					container && container.classList.add( api( 'header_container' )() );
				}
			} );
			BoldGrid.custom_header.calc();
		} );
	} );
} )( wp.customize );

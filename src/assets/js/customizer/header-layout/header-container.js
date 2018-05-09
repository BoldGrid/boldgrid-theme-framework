( function( api ) {

	/**
	 * Update classes for blog layouts.
	 */
	api( 'bgtfw_header_layout_position', function( value ) {
		value.bind( function( to ) {
			var containers = [ 'navi', 'secondary-menu' ];
			_.each( containers, function( container ) {
				container = document.getElementById( container );
				container.classList.remove( 'container' );
				if ( to === 'header-top' && ! _.isEmpty( api( 'header_container' )() ) ) {
					container.classList.add( api( 'header_container' )() );
				}
			} );
			document.body.classList.contains( 'header-fixed' ) ? BoldGrid.header_fixed.calc() : BoldGrid.custom_header.calc();
		} );
	} );

	/**
	 * Update classes for blog layouts.
	 */
	wp.customize( 'header_container', function( value ) {
		value.bind( function( to ) {
			var containers = [ 'navi', 'secondary-menu' ];
			_.each( containers, function( container ) {
				container = document.getElementById( container );
				container.classList.remove( 'container' );
				if ( ! _.isEmpty( api( 'header_container' )() ) ) {
					container.classList.add( to );
				}
			} );
			document.body.classList.contains( 'header-fixed' ) ? BoldGrid.header_fixed.calc() : BoldGrid.custom_header.calc();
		} );
	} );
} )( wp.customize );

export default {
	ready: function() {
		let control = this;

		control.container.find( '.bgtfw-hamburger-col, .tray, input' ).on( 'click touchend', function( e ) {
			let col = $( e.target ).find( '.bgtfw-hamburger-col' );
			if ( ! col.length ) {
				col = $( e.target ).closest( '.bgtfw-hamburger-col' );
			}
			control.container.find( '.bgtfw-hamburger-col' ).removeClass( 'hamburger-selected' );
			col.addClass( 'hamburger-selected' );
		} );

		control.container.find( '.bgtfw-hamburger-col' ).on( 'mouseover', function( e ) {
			$( e.target ).find( '.hamburger' ).addClass( 'is-active' );
		} );
		control.container.find( '.bgtfw-hamburger-col' ).on( 'mouseout', function( e ) {
			$( e.target ).find( '.hamburger' ).removeClass( 'is-active' );
		} );
	}
};

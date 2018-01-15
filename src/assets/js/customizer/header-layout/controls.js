wp.customize.bind( 'ready', function () {

	/**
	 * The active_callback method in PHP doesn't allow for controls to be
	 * rerendered.  The active_callback needs to be implemented in js to
	 * allow for the controls to be contextually updated.
	 *
	 * @since 2.0.0
	 */
	wp.customize.control( 'bgtfw_header_top_layouts', function( control ) {
		var setting = wp.customize( 'bgtfw_header_layout_position' );
		control.active.set( 'header-top' === setting.get() );
		setting.bind( function( value ) {
			control.active.set( 'header-top' === value );
		} );
	} );
} );

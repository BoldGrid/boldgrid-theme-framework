wp.customize.bind( 'ready', function () {

	/**
	 * The active_callback method in PHP doesn't allow for controls to be
	 * rerendered.  The active_callback needs to be implemented in js to
	 * allow for the controls to be contextually updated.
	 *
	 * @since 2.0.0
	 */
	wp.customize( 'bgtfw_header_layout_position', function( setting ) {
		$.each( ['bgtfw_header_top_layouts', 'header_container' ], function( index, controlId ) {
			var displayControl;

			displayControl = function( control ) {
				var display = function() {
					'header-top' === setting.get() ? control.container.show() : control.container.hide();
				};
				display();
				setting.bind( display );
			};

			wp.customize.control( controlId, displayControl );
		} );
	} );
} );

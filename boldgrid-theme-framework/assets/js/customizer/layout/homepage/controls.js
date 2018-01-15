wp.customize.bind( 'ready', function () {

	/**
	 * The active_callback method in PHP doesn't allow for controls to be
	 * rerendered.  The active_callback needs to be implemented in js to
	 * allow for the controls to be contextually updated.
	 *
	 * @since 2.0.0
	 */
	wp.customize( 'show_on_front', function( setting ) {
		var sidebarControl;

		sidebarControl = function( control ) {
			var display = function() {
				'posts' === setting.get() ? control.container.show() : control.container.hide();
			};
			display();
			setting.bind( display );
		};

		wp.customize.control( 'bgtfw_layout_homepage_sidebar', sidebarControl );
	} );
} );

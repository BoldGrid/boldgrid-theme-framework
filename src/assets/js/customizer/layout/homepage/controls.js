wp.customize.bind( 'ready', function () {

	/**
	 * The active_callback method in PHP continues to display the control
	 * until the previewer has fully refreshed.  This gives the control
	 * instant feedback for the user by conditionally hiding it as they
	 * make their selections.
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

		wp.customize.control( 'bgtfw_blog_blog_page_sidebar', sidebarControl );
	} );
} );

wp.customize.bind( 'ready', function () {

	/**
	 * The active_callback method in PHP doesn't allow for controls to be
	 * rerendered.  The active_callback needs to be implemented in js to
	 * allow for the controls to be contextually updated.
	 *
	 * @since 2.0.0
	 */
	wp.customize( 'bgtfw_pages_blog_blog_page_layout_content', function( setting ) {
		var sidebarControl;

		sidebarControl = function( control ) {
			var display = function() {
				'content' === setting.get() ? control.container.show() : control.container.hide();
			};
			display();
			setting.bind( display );
		};

		wp.customize.control( 'bgtfw_pages_blog_blog_page_layout_featimg', sidebarControl );
	} );
} );

jQuery( document ).ready( function(  ) {

	/**
	 * Gets hex colors instead of rgb/rgbas from selectors
	 * since our designers believe hex is supreme and our developers
	 * are just trying to keep things somewhat uniform. :)
	 *
	 * @param   colorval   Selector you want to get colour value from.
	 *
	 * @since 1.0.0
	 **/

	// comment reply link
	jQuery( '.comment-reply-link' )
		.addClass( 'btn button-primary color1-text-contrast' )
		.css( 'transition' , 'all .5s' );

	// The WordPress Default Widgets
	jQuery( '.widget_rss ul' ).addClass( 'media-list' );

	jQuery( '.widget_meta ul, .widget_recent_entries ul, .widget_archive ul, .widget_categories ul, .widget_nav_menu ul, .widget_pages ul' ).addClass( 'nav' );

	jQuery( '.widget_recent_comments ul#recentcomments' )
		.css({ 'list-style' : 'none', 'padding-left' : '0' });

	jQuery( '.widget_recent_comments ul#recentcomments li' ).css( 'padding', '5px 15px');

	jQuery( 'table#wp-calendar' ).addClass( 'table table-striped');

	jQuery( "select[name='archive-dropdown']" ).addClass( 'form-control' );

} );

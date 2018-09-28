jQuery( document ).ready( function( $ ) {

	// Comment reply link.
	$( '.comment-reply-link' )
		.addClass( 'btn button-primary color1-text-contrast' )
		.css( 'transition', 'all .5s' );

	// The WordPress Default Widgets.
	$( '.widget_rss ul' ).addClass( 'media-list' );

	$( '.widget_meta ul, .widget_recent_entries ul, .widget_archive ul, .widget_categories ul, .widget_nav_menu ul, .widget_pages ul' ).addClass( 'nav' );

	$( '.widget_recent_comments ul#recentcomments' )
		.css({ 'list-style': 'none', 'padding-left': '0' });

	$( '.widget_recent_comments ul#recentcomments li' ).css( 'padding', '5px 15px' );

	$( '.sidebar select, select[name="archive-dropdown"]' ).addClass( 'form-control' );
	$( '.sidebar .button' ).removeClass( 'button' ).addClass( 'btn button-primary' );

	$( '.woocommerce.widget .ui-slider' ).css( 'display', 'none' );
	$( window ).on( 'load', function() {
		$( '.woocommerce.widget .ui-slider' ).css( 'display', 'block' );
		$( '.woocommerce.widget .ui-slider' ).addClass( 'color1-background-color' ).children().addClass( 'color2-background-color' );
	});

	$( 'body' ).find( '.button' )
		.removeClass( 'button' )
		.addClass( 'btn button-primary' );
	$( 'body' ).find( '.button.alt' )
		.removeClass( 'button alt' )
		.addClass( 'btn button-secondary' );
});

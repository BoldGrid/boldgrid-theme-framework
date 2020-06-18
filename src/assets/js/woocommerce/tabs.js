jQuery( document ).ready( function( $ ) {
	$( 'body' ).on( 'click', '.wc-tabs li a, ul.tabs li a', function( e ) {
		var $tab, $tabs_wrapper, $tabs;
		e.preventDefault();
		$tab = $( this );
		$tabs_wrapper = $tab.closest( '.wc-tabs-wrapper, .woocommerce-tabs' );
		$tabs = $tabs_wrapper.find( '.wc-tabs, ul.tabs' );
		$tabs.find( 'li > a' ).removeClass( 'color1-background color-1-text-contrast' );
		$tab.closest( 'li > a' ).addClass( 'color1-background color-1-text-contrast' );
	});
});

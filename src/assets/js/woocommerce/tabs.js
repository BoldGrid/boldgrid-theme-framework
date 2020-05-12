jQuery( document ).ready( function( $ ) {
	$( 'body' ).on( 'click', '.wc-tabs li a, ul.tabs li a', function( e ) {
		var $tab, $tabs_wrapper, $tabs;
		e.preventDefault();
		$tab = $( this );
		$tabs_wrapper = $tab.closest( '.wc-tabs-wrapper, .woocommerce-tabs' );
		$tabs = $tabs_wrapper.find( '.wc-tabs, ul.tabs' );
		$tabs.find( 'li' ).removeClass( 'color1-background' );
		$tabs.find( 'li > a' ).removeClass( 'color-1-text-contrast' );
		$tabs_wrapper.find( '.wc-tab, .panel:not(.panel .panel)' ).hide();
		$tab.closest( 'li' ).addClass( 'color1-background' );
		$tab.closest( 'li > a' ).addClass( 'color-1-text-contrast' );
	});
});

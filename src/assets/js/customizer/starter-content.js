( function( $ ) {

	'use strict';

	wp.customize.bind( 'ready', function () {
		wp.customize.panel( 'themes', function( panel ) {
			panel.deferred.embedded.done( function() {
				var starterContentRow;
				panel.headContainer.addClass( 'has-starter-content' );
				starterContentRow = $( wp.template( 'customize-starter-content-actions' )() );
				panel.headContainer.append( starterContentRow );
				starterContentRow.on( 'click', 'button', function( event ) {
					var request, button = $( this );
					event.preventDefault();
					$( '.wp-full-overlay' ).addClass( 'customize-loading' ); // @todo This doesn't work.
					request = wp.ajax.post( 'customize_load_starter_content', wp.customize.previewer.query() );
					button.prop( 'disabled', true );
					request.done( function() {
						panel.loadThemePreview( wp.customize.settings.theme.stylesheet ).fail( function() {
							button.prop( 'disabled', false );
						} );
					} );
					request.fail( function() {
						button.prop( 'disabled', false );
					} );
				} );
			} );
		} );
	} );
} )( jQuery );

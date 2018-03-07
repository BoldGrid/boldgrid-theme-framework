( function( $ ) {

	'use strict';

	wp.customize.bind( 'ready', function () {
		wp.customize.panel( 'themes', function( panel ) {
			panel.deferred.embedded.done( function() {
				var starterContentRow;
				panel.headContainer.addClass( 'has-starter-content' );
				starterContentRow = $( wp.template( 'customize-starter-content-actions' )() );
				panel.headContainer.append( starterContentRow );

				if ( wp.customize( 'bgtfw_starter_content_loaded' )() === true ) {
					starterContentRow.find( 'button' ).addClass( 'loaded' );
					starterContentRow.find( 'button' ).prop( 'disabled', true );
					wp.customize( 'bgtfw_starter_content_loaded' ).set( false );
				}

				starterContentRow.on( 'click', 'button', function( event ) {
					var request, button = $( this );
					event.preventDefault();
					button.addClass( 'loading' );
					wp.customize( 'bgtfw_starter_content_loaded' ).set( true );
					request = wp.ajax.post( 'load_starter_content', wp.customize.previewer.query() );
					button.prop( 'disabled', true );
					request.done( function() {
						panel.loadThemePreview( wp.customize.settings.theme.stylesheet ).fail( function() {
							button.prop( 'disabled', false );
							button.removeClass( 'loading' );
						} );
					} );
					request.fail( function() {
						button.prop( 'disabled', false );
						button.removeClass( 'loading' );
					} );
				} );
			} );
		} );
	} );
} )( jQuery );

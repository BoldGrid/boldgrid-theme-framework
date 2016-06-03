jQuery( document ).ready( function(  ) {

	'use strict';

	var adjust_content = function(  ) {
		var in_customizer = false;

		if ( typeof wp !== 'undefined' ) {
			in_customizer =  typeof wp.customize !== 'undefined' ? true : false;
		}

		var header_height = jQuery( '.site-header' ).height(  );
		var screen_width = true === in_customizer ? jQuery( window ).width() + 16 : jQuery( window ).width();

		// desktop
		if ( screen_width > 768 ) {
			jQuery( '.site-content' ).css( 'padding-top', header_height + 'px' );

		// mobile
		} else {
			jQuery( '.site-content' ).css( 'padding-top', '0px' );
		}

		window.addEventListener( 'scroll', function( e ) {
			var distanceY = window.pageYOffset || document.documentElement.scrollTop,
				shrinkOn = 100,
				header = document.querySelector( 'header' );

			if ( distanceY > shrinkOn ) {
				jQuery( header ).addClass( 'smaller' );

			} else {
				if ( true === jQuery( header ).hasClass( 'smaller' ) ) {
					jQuery( header ).removeClass( 'smaller' );
				}
			}

		} );
	};

	adjust_content(  );

	jQuery( window ).resize( adjust_content );

} );

jQuery( document ).ready( function() {

	'use strict';

	var adjust_content = function() {
		var in_customizer = false,
			header_height = jQuery( '.site-header' ).height(),
			screen_width = true === in_customizer ? jQuery( window ).width() + 16 : jQuery( window ).width();

		if ( 'undefined' !== typeof wp ) {
			in_customizer = 'undefined' !== typeof wp.customize ? true : false;
		}

		// Desktop.
		if ( screen_width > 768 ) {
			jQuery( '.site-content' ).css( 'padding-top', header_height + 'px' );

		// Mobile.
		} else {
			jQuery( '.site-content' ).css( 'padding-top', '0px' );
		}

		window.addEventListener( 'scroll', function() {
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

	adjust_content();
	jQuery( window ).resize( adjust_content );
} );

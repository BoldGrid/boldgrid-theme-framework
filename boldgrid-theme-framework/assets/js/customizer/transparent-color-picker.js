/**
 * Thanks To:
 * http://pluto.kiwi.nz/2014/07/how-to-add-a-color-control-with-alphaopacity-to-the-wordpress-theme-customizer/
 */

jQuery( document ).ready( function( $ ) {
	Color.prototype.toString = function( remove_alpha ) {
		if ( remove_alpha === 'no-alpha' ) {
			return this.toCSS( 'rgba', '1' ).replace( /\s+/g, '' );
		}
		if ( this._alpha < 1 ) {
			return this.toCSS( 'rgba', this._alpha ).replace( /\s+/g, '' );
		}
		var hex = parseInt( this._color, 10 ).toString( 16 );
		if ( this.error ) {
			return '';
		}
		if ( hex.length < 6 ) {
			for ( var i = 6 - hex.length - 1; i >= 0; i-- ) {
				hex = '0' + hex;
			}
		}

		return '#' + hex;
	};

	$( '.pluto-color-control' ).each( function() {
		var $control = $( this ),
			value = $control.val().replace( /\s+/g, '' );

		// Manage Palettes.
		var palette;
		var palette_input = $control.attr( 'data-palette' );
		if ( palette_input === 'false' || palette_input === false ) {
			palette = false;
		} else if ( palette_input === 'true' || palette_input === true ) {
			palette = true;
		} else {
			palette = $control.attr( 'data-palette' ).split( ',' );
		}
		$control.wpColorPicker({ // Change some things with the color picker
			clear: function() {

			// TODO reset Alpha Slider to 100.
			 },
			change: function( event, ui ) {

				// Send ajax request to wp.customizer to enable Save & Publish button.
				var _new_value = $control.val();
				var key = $control.attr( 'data-customize-setting-link' );
				wp.customize( key, function( obj ) {
					obj.set( _new_value );
				});

				// Change the background color of our transparency container whenever a color is updated.
				var $transparency = $control.parents( '.wp-picker-container:first' ).find( '.transparency' );

				// We only want to show the color at 100% alpha.
				$transparency.css( 'backgroundColor', ui.color.toString( 'no-alpha' ) );
			},
			palettes: palette // Remove the color palettes
		});
		$( '<div class="pluto-alpha-container"><div class="slider-alpha"></div><div class="transparency"></div></div>' ).appendTo( $control.parents( '.wp-picker-container' ) );
		var $alpha_slider = $control.parents( '.wp-picker-container:first' ).find( '.slider-alpha' );

		// If in format RGBA - grab A channel value.
		var alpha_val;
		if ( value.match( /rgba\(\d+\,\d+\,\d+\,([^\)]+)\)/ ) ) {
			alpha_val = parseFloat( value.match( /rgba\(\d+\,\d+\,\d+\,([^\)]+)\)/ )[1] ) * 100;
			alpha_val = parseInt( alpha_val );
		} else {
			alpha_val = 100;
		}
		$alpha_slider.slider({
			slide: function( event, ui ) {
				$( this ).find( '.ui-slider-handle' ).text( ui.value ); // Show value on slider handle

				// send ajax request to wp.customizer to enable Save & Publish button.
				var _new_value = $control.val();
				var key = $control.attr( 'data-customize-setting-link' );
				wp.customize( key, function( obj ) {
					obj.set( _new_value );
				});
			},
			create: function() {
				var v = $( this ).slider( 'value' );
				$( this ).find( '.ui-slider-handle' ).text( v );
			},
			value: alpha_val,
			range: 'max',
			step: 1,
			min: 1,
			max: 100
		}); // Slider
		$alpha_slider.slider().on( 'slidechange', function( event, ui ) {
			var new_alpha_val = parseFloat( ui.value ),
				iris = $control.data( 'a8cIris' ),
				color_picker = $control.data( 'wpWpColorPicker' );
			iris._color._alpha = new_alpha_val / 100.0;
			$control.val( iris._color.toString() );
			color_picker.toggler.css({
				backgroundColor: $control.val()
			});

			// Fix relationship between alpha slider and the 'side slider not updating.
			var get_val = $control.val();
			$( $control ).wpColorPicker( 'color', get_val );
		});
	}); // Each
});

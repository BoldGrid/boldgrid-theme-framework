jQuery( document ).ready( function( $ ) {

	var qtyInput = function() {
		$( '.btn-number' ).click( function( e ) {
			var minValue, maxValue, fieldName, type, input, currentVal;

			e.preventDefault();

			fieldName = $( this ).attr( 'data-field' );
			type      = $( this ).attr( 'data-type' );
			input = $( 'input[name="' + fieldName + '"]' );
			currentVal = parseInt( input.val() );

			if ( ! isNaN( currentVal ) ) {
				if ( 'minus' === type ) {
					minValue = parseInt( input.attr( 'min' ) );
					if ( isNaN( minValue ) ) {
						minValue = 0;
					}
					if ( currentVal > minValue ) {
						input.val( currentVal - 1 ).change();
					}
					if ( parseInt( input.val() ) === minValue ) {
						$( this ).attr( 'disabled', true );
					}

				} else if ( 'plus' === type ) {
					maxValue = parseInt( input.attr( 'max' ) );
					if ( ! maxValue ) {
						maxValue = 9999999999999;
					}
					if ( currentVal < maxValue ) {
						input.val( currentVal + 1 ).change();
					}
					if ( parseInt( input.val() ) === maxValue ) {
						$( this ).attr( 'disabled', true );
					}

				}
			} else {
				input.val( 0 );
			}
		});

		$( '.input-number' ).focusin( function() {
			$( this ).data( 'oldValue', $( this ).val() );
		});

		$( '.input-number' ).change( function() {
			var name, valueCurrent, minValue, maxValue;

			minValue =  parseInt( $( this ).attr( 'min' ) );
			maxValue =  parseInt( $( this ).attr( 'max' ) );

			if ( isNaN( minValue ) ) {
				minValue = 0;
			}

			if ( ! maxValue ) {
				maxValue = 9999999999999;
			}

			valueCurrent = parseInt( $( this ).val() );

			name = $( this ).attr( 'name' );
			if ( valueCurrent >= minValue ) {
				$( '.btn-number[data-type="minus"][data-field="' + name + '"]' ).removeAttr( 'disabled' );
			} else {
				alert( 'Sorry, the minimum value was reached' );
				$( this ).val( $( this ).data( 'oldValue' ) );
			}
			if ( valueCurrent <= maxValue ) {
				$( '.btn-number[data-type="plus"][data-field="' + name + '"]' ).removeAttr( 'disabled' );
			} else {
				alert( 'Sorry, the maximum value was reached' );
				$( this ).val( $( this ).data( 'oldValue' ) );
			}
		});

		// Key Events.
		$( '.input-number' ).keydown( function( e ) {

			// Allow: backspace, delete, tab, escape, enter and .'s.
			if ( $.inArray( e.keyCode, [ 46, 8, 9, 27, 13, 190 ] ) !== -1 ||

				// Allow: Ctrl+A.
				( 65 === e.keyCode && true === e.ctrlKey ) ||

				// Allow: home, end, left, right.
				( e.keyCode >= 35 && e.keyCode <= 39 ) ) {

					// Let it happen, don't do anything.
					return;
			}

			// Ensure that it is a number and stop the keypress.
			if ( ( e.shiftKey || ( e.keyCode < 48 || e.keyCode > 57 ) ) && ( e.keyCode < 96 || e.keyCode > 105 ) ) {
				e.preventDefault();
			}
		});
	};
	qtyInput();

	/* Trigger when cart updates run. */
	$( 'body' ).on( 'updated_wc_div', qtyInput );
});

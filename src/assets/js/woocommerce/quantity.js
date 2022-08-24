jQuery( document ).ready( function( $ ) {

	var qtyInput = function() {
		$( '.btn-number' ).on( 'click', function( e ) {
			var minValue, maxValue, type, input, currentVal;

			e.preventDefault();

			type       = $( this ).attr( 'data-type' );
			input      = $( this ).closest( '.input-group' ).find( '.qty' );
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
		} );

		$( '.input-number' ).focusin( function() {
			$( this ).data( 'oldValue', $( this ).val() );
		} );

		$( '.input-number' ).on( 'change', function() {
			var valueCurrent, minValue, maxValue, changed;

			minValue =  parseInt( $( this ).attr( 'min' ) );
			maxValue =  parseInt( $( this ).attr( 'max' ) );

			if ( isNaN( minValue ) ) {
				minValue = 0;
			}

			if ( ! maxValue ) {
				maxValue = 9999999999999;
			}

			valueCurrent = parseInt( $( this ).val() );

			if ( valueCurrent === minValue ) {
				$( this ).siblings( '.input-group-btn' ).children( '.btn-minus' ).attr( 'disabled', true );
				changed = true;
			} else if ( valueCurrent > minValue ) {
				$( this ).siblings( '.input-group-btn' ).children( '.btn-minus' ).removeAttr( 'disabled' );
				changed = true;
			}

			if ( valueCurrent === maxValue ) {
				$( this ).siblings( '.input-group-btn' ).children( '.btn-plus' ).attr( 'disabled', true );
				changed = true;
			} else if ( valueCurrent < maxValue ) {
				$( this ).siblings( '.input-group-btn' ).children( '.btn-plus' ).removeAttr( 'disabled' );
				changed = true;
			}

			if ( true !== changed ) {
				$( this ).val( $( this ).data( 'oldValue' ) );
			}
		} );

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
		} );
	};

	qtyInput();

	$( '.input-number' ).trigger( 'change' );

	// Table wrap fix.
	$( '.woocommerce-cart .actions' ).wrapInner( '<div class="bgtfw-table-vertical-align"></div>' );

	/* Trigger when cart updates run. */
	$( 'body' ).on( 'updated_wc_div wc_fragments_refreshed', function() {
		qtyInput();
		$( '.input-number' ).trigger( 'change' );
	} );
} );

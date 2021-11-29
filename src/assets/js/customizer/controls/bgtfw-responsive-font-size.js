const api = wp.customize;

export default () => {
	function isJsonString( str ) {
		try {
			JSON.parse( str );
		} catch ( e ) {
			return false;
		}
		return true;
	}

	function validateFontSize( setting ) {
		setting.validate = function( value ) {
			var formatCode = 'invalid_format',
				jsonCode = 'invalid_json',
				jsonNotification,
				formatNotification,
				valueObject,
				matches,
				invalidDevices = [];

			if ( ! isJsonString( value ) ) {
				jsonCode = 'invalid_json';
				jsonNotification = new api.Notification( jsonCode, {
					message: 'Invalid JSON',
					type: 'error'
				} );
				setting.notifications.add( jsonCode, jsonNotification );
				return value;
			} else {
				setting.notifications.remove( jsonCode );
			}

			valueObject = JSON.parse( value );

			for ( let device in valueObject ) {
				matches = valueObject[ device ].match( /(\d+)(em|ex|%|px|cm|mm|in|pt|pc|rem)?/ );
				if ( matches && 3 === matches.length && ! _.isUndefined( matches[2] ) ) {
					continue;
				}

				invalidDevices.push( device );
			}

			if ( 0 !== invalidDevices.length ) {
				formatNotification = new api.Notification(
					formatCode,
					{
						message: 'Invalid ' + invalidDevices.join( ', ' ) + ' font size format',
						type: 'error'
					}
				);
				setting.notifications.remove( formatCode );
				setting.notifications.add( formatCode, formatNotification );
				return value;
			} else {
				setting.notifications.remove( formatCode );
			}
			return value;
		};
	}

	function deviceClick( $container, $thisLabel, $thisInputValue, controlId ) {
		$container.find( 'p.font-size-input' ).hide();
		$container.find( '.devices-wrapper input' ).prop( 'ckecked', false );
		$container.find( '#' + controlId + '-font-size-' + $thisInputValue ).parent().show();
		$thisLabel.siblings( 'input' ).prop( 'checked', true );

		// This triggers the change in the device shown in the preview, to match the one in the control.
		if ( 'phone' === $thisInputValue ) {
			$container.closest( 'body' ).find( 'button.preview-mobile' ).not( '.active' ).trigger( 'click' );
		} else {
			$container.closest( 'body' ).find( 'button.preview-' + $thisInputValue ).not( '.active' ).trigger( 'click' );
		}
	}

	function sectionExpand( $controlContainer ) {
		var $container         = $controlContainer,
			currentDevice      = $container.find( '.devices-wrapper input:checked' ).val(),
			$responsiveWrapper = $container.find( '.devices-wrapper input:checked' ).parents( '.boldgrid-responsive-font-size-wrapper' ),
			$prevControl       = $controlContainer.prev();

		$prevControl.css( {'margin-bottom': 0, 'border-bottom': 0 } );
		$prevControl.children( 'label' ).css( 'border-bottom', 0 );
		$prevControl.children( 'wrapper' ).css( 'border-bottom', 0 );

		$responsiveWrapper.find( 'p.font-size-input' ).hide();

		$responsiveWrapper.find( 'p.font-size-input.' + currentDevice ).show();

	}

	function fontSizeInput( $thisInput, value, controlId ) {
		var device  = $thisInput.attr( 'data-device' ),
			setting = api( controlId )();

		setting = isJsonString( setting ) ? JSON.parse( setting ) : {};

		if ( value && device ) {
			setting[ device ] = value;
			api( controlId )( JSON.stringify( setting ) );
		}
	}

	api.bind( 'ready', () => {
		api.control.each( function( control ) {
			var controlId       = control.id,
				sectionId       = control.section(),
				$container      = control.container,
				$deviceLabel    = $container.find( '.devices-wrapper label' ),
				$fontSizeInputs = $container.find( '.font-size-input' );

			if ( 'bgtfw-responsive-typography' === control.params.type ) {
				$deviceLabel.on( 'click', ( e ) => {
					var $thisLabel      = $( e.currentTarget ),
						$thisInputValue = $thisLabel.siblings( 'input' ).val();

					deviceClick( $container, $thisLabel, $thisInputValue, controlId );
				} );

				api.section( sectionId ).expanded.bind( () => {
					sectionExpand( $container );
				} );

				$fontSizeInputs.each( function() {
					var $thisInput = $( this );
					var debounceCb = _.debounce( () => {
						fontSizeInput( $thisInput, $( this ).val(), controlId );
					}, 1000 );
					$thisInput.on( 'input', debounceCb );
				} );

				wp.customize( controlId, validateFontSize );
			}
		} );
	} );
};

/**
 * File: src/assets/js/customizer/controls/bgtfw-responsive-font-size.js
 *
 * Responsive font size control for the Customizer.
 *
 * @package    Boldgrid_Theme_Framework
 * @subpackage Boldgrid_Theme_Framework/Customizer/Controls
 *
 * @since      2.11.0
 */

/**
 * WP_Customize_Manager instance.
 *
 * @type {Object}
 */
const api = wp.customize;

export default () => {

	/**
	 * Determines if a string is valid JSON.
	 *
	 * @param {string} str Alleged JSON string.
	 * @returns
	 */
	function isJsonString( str ) {
		try {
			JSON.parse( str );
		} catch ( e ) {
			return false;
		}
		return true;
	}

	/**
	 * Device Clicked.
	 *
	 * @param {object} $container      The container of the clicked device.
	 * @param {object} $thisLabel      The label of the clicked device.
	 * @param {string} $thisInputValue The value of the clicked device.
	 * @param {string} controlId       The control id.
	 */
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

	/**
	 * Handles events bound to section expanding.
	 *
	 * @param {object} $controlContainer The container of the control.
	 */
	function sectionExpand( $controlContainer ) {
		var $container         = $controlContainer,
			currentDevice      = $container.find( '.devices-wrapper input:checked' ).val(),
			$responsiveWrapper = $container.find( '.devices-wrapper input:checked' ).parents( '.boldgrid-responsive-font-size-wrapper' ),
			$prevControl       = $controlContainer.prev();

		$prevControl.css( { 'margin-bottom': 0, 'border-bottom': 0 } );
		$prevControl.children( 'label' ).css( 'border-bottom', 0 );
		$prevControl.children( 'wrapper' ).css( 'border-bottom', 0 );

		$responsiveWrapper.find( 'p.font-size-input' ).hide();

		$responsiveWrapper.find( 'p.font-size-input.' + currentDevice ).show();

	}

	/**
	 * Converts individual device sizes to a JSON string.
	 *
	 * @param {object} $thisInput The input that was changed.
	 * @param {string} value      The value of the input.
	 * @param {string} controlId  The control id.
	 */
	function fontSizeInput( $thisInput, value, controlId ) {
		var device  = $thisInput.attr( 'data-device' ),
			setting = api( controlId )();

		setting = isJsonString( setting ) ? JSON.parse( setting ) : {};

		if ( value && device ) {
			setting[ device ] = value;
			api( controlId )( JSON.stringify( setting ) );
		}
	}

	/**
	 * Binds events to the wp.customize 'ready' event.
	 */
	api.bind( 'ready', () => {

		/**
		 * Binds events to each control that
		 * has the 'bgtfw-responseive-typography' type.
		 */
		api.control.each( function( control ) {
			var controlId       = control.id,
				sectionId       = control.section(),
				$container      = control.container,
				$deviceLabel    = $container.find( '.devices-wrapper label' ),
				$fontSizeInputs = $container.find( '.font-size-input' );

			if ( 'bgtfw-responsive-typography' === control.params.type ) {

				// Device lable click event.
				$deviceLabel.on( 'click', ( e ) => {
					var $thisLabel      = $( e.currentTarget ),
						$thisInputValue = $thisLabel.siblings( 'input' ).val();

					deviceClick( $container, $thisLabel, $thisInputValue, controlId );
				} );

				// Section expand event.
				api.section( sectionId ).expanded.bind( () => {
					sectionExpand( $container );
				} );

				// Input change event.
				$fontSizeInputs.each( function() {
					var $thisInput = $( this );
					var debounceCb = _.debounce( () => {
						fontSizeInput( $thisInput, $( this ).val(), controlId );
					}, 1000 );
					$thisInput.on( 'input', debounceCb );
				} );
			}
		} );
	} );
};

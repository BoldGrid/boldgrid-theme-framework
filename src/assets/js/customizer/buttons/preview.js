const api      = wp.customize;
const colorLib = window.net.brehaut;

/**
 * File: src/assets/js/customizer/buttons/preview.js
 *
 * Preview utility for the buttons.
 *
 * @since 2.12.0
 * @package BGTFW/customizer
 * @author  BoldGrid
 */

/**
 * Class: Preview
 *
 * Preview utility for the buttons.
 *
 * @since 2.12.0
 */
export class Preview {
	constructor() {

		/**
		 * Array of button Types used to form control ids.
		 * Ex: bgtfw_primary_button_background.
		 */
		this.buttonTypes = [ 'primary', 'secondary' ];

		/**
		 * Array of button controls that all use the same method
		 * of generating preview classes in the following manner:
		 *
		 * Key:   control suffix.
		 * Value: array of classes to remove.
		 */
		this.generalButtonControls = {
			'raised': [ 'btn-raised' ],
			'text_shadow': [ 'btn-longshadow' ],
			'effect': [ 'btn-3d', 'btn-glow' ],
			'border': [ 'btn-border', 'btn-border-thin', 'btn-border-thick' ],
			'shape': [ 'btn-rounded', 'btn-pill' ]
		};
	}

	/**
	 * Get Hover Color.
	 *
	 * This class auto generates a ligher or darker
	 * hover color for the button's background color.
	 *
	 * @since 2.12.0
	 *
	 * @param {string} color color string.
	 *
	 * @returns {string} css formatted color string.
	 */
	_getHoverColor( color ) {
		var colorObj  = colorLib.Color( color ),
			lightness = colorObj.getLightness(),
			hoverColor;

		if ( 0.9 < lightness ) {
			hoverColor = colorObj.darkenByAmount( 0.075 ).toCSS();
		} else {
			hoverColor = colorObj.lightenByAmount( 0.075 ).toCSS();
		}

		return hoverColor;

	}

	/**
	 * Run these on init.
	 *
	 * @since 2.12.0
	 */
	bindEvents() {
		this.buttonTypes.forEach( ( buttonType ) => {
			this._bindGeneralButtons( buttonType );
			this._buttonSizes( buttonType );
			this._backgroundColorClasses( buttonType );
			this._borderColorClasses( buttonType );
		} );
	}

	/**
	 * Bind General Buttons.
	 *
	 * This method loops through the generalButtonControls in order to
	 * bind the controls to the preview.
	 *
	 * @since 2.12.0
	 *
	 * @param {string} buttonType Button type. ie: primary, secondary.
	 */
	_bindGeneralButtons( buttonType ) {
		var controlIdPrefix = 'bgtfw_' + buttonType + '_button_';
		for ( const buttonControl in this.generalButtonControls ) {
			this._bindButtonControl( buttonType, controlIdPrefix + buttonControl, this.generalButtonControls[ buttonControl ] );
		}
	}

	/**
	 * Bind Button Control
	 *
	 * After having looped through the button types and button controls,
	 * this method actually binds each individual control and generates the
	 * preview.
	 *
	 * @since 2.12.0
	 *
	 * @param {string} buttonType      Button type. ie: primary, secondary.
	 * @param {string} controlId       Control id.
	 * @param {array}  classesToRemove Classes to remove.
	 */
	_bindButtonControl( buttonType, controlId, classesToRemove ) {
		api( controlId, ( value ) => {
			value.bind( ( newValue ) => {
				$( '.button-' + buttonType ).removeClass( classesToRemove );
				if ( '' !== newValue ) {
					$( '.button-' + buttonType ).addClass( newValue );
				}
			} );
		} );
	}

	/**
	 * Bind Button Size controls.
	 *
	 * Since the button size control is a slider,
	 * it needs it's own method so that the classes to remove are
	 * translated from the slider value to the corresponding class.
	 *
	 * @since 2.12.0
	 */
	_buttonSizes( buttonType ) {
		var controlId = 'bgtfw_' + buttonType + '_button_size';

		api( controlId, ( value ) => {
			value.bind( ( newValue ) => {
				var sizeClasses = [
						'btn-tiny',
						'btn-small',
						'',
						'btn-large',
						'btn-jumbo',
						'btn-giant'
					],
					sizeClass = sizeClasses[ newValue - 1 ];
				$( '.button-' + buttonType ).removeClass( 'btn-tiny btn-small btn-large btn-jumbo btn-giant' );
				if ( '' !== sizeClass ) {
					$( '.button-' + buttonType ).addClass( sizeClass );
				}
			} );
		} );
	}

	/**
	 * Bind Background Color controls.
	 *
	 * Background color controls require the control
	 * values to be split to separate the color variable,
	 * and the css color value.
	 *
	 * @param {string} buttonType Button type. ie: primary, secondary.
	 *
	 * @since 2.12.0
	 */
	_backgroundColorClasses( buttonType ) {
		var controlId = 'bgtfw_' + buttonType + '_button_background';

		api( controlId, ( value ) => {
			value.bind( ( newValue ) => {
				var valueSplit = newValue.split( ':' ),
					colorClass;
				if ( 1 < valueSplit.length ) {
					colorClass = 'btn-' + valueSplit[0];
				} else if ( 'transparent' === newValue ) {
					colorClass = 'btn-transparent';
				}

				if ( 'btn-color-neutral' === colorClass ) {
					colorClass = 'btn-neutral-color';
				}

				$( '.button-' + buttonType ).removeClass( ( index, className ) => {
					return ( className.match( /(^|\s)btn-color\S+/g ) || [] ).join( ' ' );
				} );
				$( '.button-' + buttonType ).removeClass( 'btn-neutral-color btn-transparent' );
				if ( colorClass ) {
					$( '.button-' + buttonType ).addClass( colorClass );
				}
			} );
		} );
	}

	/**
	 * Bind Border Color controls.
	 *
	 * Border color controls require the control
	 * values to be split to separate the color variable,
	 * and the css color value.
	 *
	 * @param {string} buttonType Button type. ie: primary, secondary.
	 *
	 * @since 2.12.0
	 */
	_borderColorClasses( buttonType ) {
		var controlId = 'bgtfw_' + buttonType + '_button_border_color';

		api( controlId, ( value ) => {
			value.bind( ( newValue ) => {
				var valueSplit = newValue.split( ':' ),
					colorClass;
				if ( 1 < valueSplit.length ) {
					colorClass = 'btn-border-' + valueSplit[0];
				} else if ( 'transparent' === newValue ) {
					colorClass = 'btn-border-transparent';
				}

				if ( 'btn-border-color-neutral' === colorClass ) {
					colorClass = 'btn-border-neutral-color';
				}

				$( '.button-' + buttonType ).removeClass( ( index, className ) => {
					return ( className.match( /(^|\s)btn-border-color\S+/g ) || [] ).join( ' ' );
				} );
				$( '.button-' + buttonType ).removeClass( 'btn-border-neutral-color btn-border-transparent' );
				if ( colorClass ) {
					$( '.button-' + buttonType ).addClass( colorClass );
				}
			} );
		} );
	}
}

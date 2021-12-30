const api      = wp.customize;
const colorLib = window.net.brehaut;

import { Preview as PreviewUtility } from '../preview';

export class Preview {
	constructor() {
		this.preview = new PreviewUtility();
	}


	/**
	 * Run these on init.
	 *
	 * @since 2.0.0
	 */
	bindEvents() {
		this._buttonSizes();
		this._buttonShapes();
		this._buttonRaised();
		this._buttonEffect();
		this._buttonBorder();
		this._buttonTextShadow();
		this._backgroundColorClasses();
		this._borderColorClasses();
	}

	_getHoverColor( color ) {
		var colorObj = colorLib.Color( color ),
			lightness = colorObj.getLightness(),
			hoverColor;

		if ( 0.9 < lightness ) {
			hoverColor = colorObj.darkenByAmount( 0.075 ).toCSS();
		} else {
			hoverColor = colorObj.lightenByAmount( 0.075 ).toCSS();
		}

		return hoverColor;

	}

	_buttonRaised() {
		var controls = [ 'bgtfw_primary_button_raised', 'bgtfw_secondary_button_raised' ];

		controls.forEach( ( control ) => {
			api( control, ( value ) => {
				value.bind( ( newValue ) => {
					var buttonType = control.match( /bgtfw_([a-z]*)_/ )[1];

					$( '.button-' + buttonType ).removeClass( 'btn-raised' );
					if ( '' !== newValue ) {
						$( '.button-' + buttonType ).addClass( newValue );
					}
				} );
			} );
		} );
	}

	_buttonTextShadow() {
		var controls = [ 'bgtfw_primary_button_text_shadow', 'bgtfw_secondary_button_text_shadow' ];

		controls.forEach( ( control ) => {
			api( control, ( value ) => {
				value.bind( ( newValue ) => {
					var buttonType = control.match( /bgtfw_([a-z]*)_/ )[1];

					$( '.button-' + buttonType ).removeClass( 'btn-longshadow' );
					if ( '' !== newValue ) {
						$( '.button-' + buttonType ).addClass( newValue );
					}
				} );
			} );
		} );
	}

	_buttonEffect() {
		var controls = [ 'bgtfw_primary_button_effect', 'bgtfw_secondary_button_effect' ];

		controls.forEach( ( control ) => {
			api( control, ( value ) => {
				value.bind( ( newValue ) => {
					var buttonType = control.match( /bgtfw_([a-z]*)_/ )[1];

					$( '.button-' + buttonType ).removeClass( [ 'btn-3d', 'btn-glow' ] );
					if ( '' !== newValue ) {
						$( '.button-' + buttonType ).addClass( newValue );
					}
				} );
			} );
		} );
	}

	_buttonBorder() {
		var controls = [ 'bgtfw_primary_button_border', 'bgtfw_secondary_button_border' ];

		controls.forEach( ( control ) => {
			api( control, ( value ) => {
				value.bind( ( newValue ) => {
					var buttonType = control.match( /bgtfw_([a-z]*)_/ )[1];
					$( '.button-' + buttonType ).removeClass( [ 'btn-border', 'btn-border-thin', 'btn-border-thick' ] );
					if ( '' !== newValue ) {
						$( '.button-' + buttonType ).addClass( newValue );
					}
				} );
			} );
		} );
	}

	_buttonShapes() {
		var controls = [ 'bgtfw_primary_button_shape', 'bgtfw_secondary_button_shape' ];

		controls.forEach( ( control ) => {
			api( control, ( value ) => {
				value.bind( ( newValue ) => {
					var buttonType = control.match( /bgtfw_([a-z]*)_/ )[1];

					$( '.button-' + buttonType ).removeClass( 'btn-rounded btn-pill' );
					if ( '' !== newValue ) {
						$( '.button-' + buttonType ).addClass( newValue );
					}
				} );
			} );
		} );
	}

	_buttonSizes() {
		var controls = [ 'bgtfw_primary_button_size', 'bgtfw_secondary_button_size' ];

		controls.forEach( ( control ) => {
			api( control, ( value ) => {
				value.bind( ( newValue ) => {
					var sizeClass,
						sizeClasses = [
							'btn-tiny',
							'btn-small',
							'',
							'btn-large',
							'btn-jumbo',
							'btn-giant'
						],
						buttonType = control.match( /bgtfw_([a-z]*)_/ )[1];

					sizeClass = sizeClasses[ newValue - 1 ];

					$( '.button-' + buttonType ).removeClass( 'btn-tiny btn-small btn-large btn-jumbo btn-giant' );
					if ( '' !== sizeClass ) {
						$( '.button-' + buttonType ).addClass( sizeClass );
					}
				} );
			} );
		} );
	}

	_backgroundColorClasses() {
		var controls = [ 'bgtfw_primary_button_background', 'bgtfw_secondary_button_background' ];

		controls.forEach( ( control ) => {
			api( control, ( value ) => {
				value.bind( ( newValue ) => {
					var valueSplit = newValue.split( ':' ),
						buttonType = control.match( /bgtfw_([a-z]*)_/ )[1],
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
		} );
	}
	_borderColorClasses() {
		var controls = [ 'bgtfw_primary_button_border_color', 'bgtfw_secondary_button_border_color' ];

		controls.forEach( ( control ) => {
			api( control, ( value ) => {
				value.bind( ( newValue ) => {
					var valueSplit = newValue.split( ':' ),
						buttonType = control.match( /bgtfw_([a-z]*)_/ )[1],
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
		} );
	}
}

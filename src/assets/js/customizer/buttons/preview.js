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
		//this._backgroundColors();
		this._buttonSizes();
		this._buttonShapes();
		this._buttonRaised();
		this._buttonEffect();
		this._buttonTextShadow();
		this._backgroundColorClasses();
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
		var controls = [ 'bgtfw_primary_button_raised' ];

		controls.forEach( ( control ) => {
			api( control, ( value ) => {
				value.bind( ( newValue ) => {
					console.log( newValue );

					$( '.button-primary' ).removeClass( 'btn-raised' );
					if ( '' !== newValue ) {
						$( '.button-primary' ).addClass( newValue );
					}
				} );
			} );
		} );
	}

	_buttonTextShadow() {
		var controls = [ 'bgtfw_primary_button_text_shadow' ];

		controls.forEach( ( control ) => {
			api( control, ( value ) => {
				value.bind( ( newValue ) => {
					console.log( newValue );

					$( '.button-primary' ).removeClass( 'btn-longshadow' );
					if ( '' !== newValue ) {
						$( '.button-primary' ).addClass( newValue );
					}
				} );
			} );
		} );
	}

	_buttonEffect() {
		var controls = [ 'bgtfw_primary_button_effect' ];

		controls.forEach( ( control ) => {
			api( control, ( value ) => {
				value.bind( ( newValue ) => {
					console.log( {
						'button-primary': $( '.button-primary' ),
						'newValue': newValue
					} );

					$( '.button-primary' ).removeClass( [ 'btn-3d', 'btn-glow' ] );
					if ( '' !== newValue ) {
						$( '.button-primary' ).addClass( newValue );
					}
				} );
			} );
		} );
	}

	_buttonShapes() {
		var controls = [ 'bgtfw_primary_button_shape' ];

		controls.forEach( ( control ) => {
			api( control, ( value ) => {
				value.bind( ( newValue ) => {
					console.log( newValue );

					$( '.button-primary' ).removeClass( 'btn-rounded btn-pill' );
					if ( '' !== newValue ) {
						$( '.button-primary' ).addClass( newValue );
					}
				} );
			} );
		} );
	}

	_buttonSizes() {
		var controls = [ 'bgtfw_primary_button_size' ];

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
						];

					sizeClass = sizeClasses[ newValue - 1 ];


					console.log( sizeClass );

					$( '.button-primary' ).removeClass( 'btn-tiny btn-small btn-large btn-jumbo btn-giant' );
					if ( '' !== sizeClass ) {
						$( '.button-primary' ).addClass( sizeClass );
					}
				} );
			} );
		} );
	}

	_backgroundColorClasses() {
		var controls = [ 'bgtfw_primary_button_background' ];

		controls.forEach( ( control ) => {
			api( control, ( value ) => {
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

					console.log( colorClass );

					$( '.button-primary' ).removeClass( ( index, className ) => {
						return ( className.match( /(^|\s)btn-color\S+/g ) || [] ).join( ' ' );
					} );
					$( '.button-primary' ).removeClass( 'btn-neutral-color btn-transparent' );
					if ( colorClass ) {
						$( '.button-primary' ).addClass( colorClass );
					}
				} );
			} );
		} );
	}

	_backgroundColors() {
		var controls = [ 'bgtfw_primary_button_background' ];

		controls.forEach( ( control ) => {
			api( control, ( value ) => {
				value.bind( ( newValue ) => {
					var valueSplit = newValue.split( ':' ),
						color,
						colorClass,
						css,
						hoverColor;
					if ( 1 < valueSplit.length ) {
						color = valueSplit[1];
						colorClass = valueSplit[0];
					} else if ( 'transparent' === newValue ) {
						color = 'rgba(0,0,0,0)';
					} else {
						color = '';
					}

					hoverColor = this._getHoverColor( color );

					console.log( hoverColor );


					if ( colorClass ) {
						css  = '.palette-primary .button-primary{' +
							'background-color: var(--' + colorClass + ')!important;' +
							'color: var(--' + colorClass + '-text-contrast)!important;' +
							'}' +
							'.palette-primary .button-primary:hover{' +
							'background-color: ' + hoverColor + '!important;' +
							'color: var(--' + colorClass + '-text-contrast)!important;' +
							'}';
						this.preview.updateDynamicStyles( control.id, css );
					} else {
						css = '.palette-primary .button-primary{' +
							'background-color: ' + color + '!important;' +
							'}' +
							'.palette-primary .button-primary:hover{' +
							'background-color: ' + hoverColor + '!important;' +
							'}';
						this.preview.updateDynamicStyles( control.id, css );
					}

					// this.preview.updateDynamicStyles( newValue );
				} );
			} );
		} );
	}
}
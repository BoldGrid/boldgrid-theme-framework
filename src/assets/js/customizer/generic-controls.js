import { BorderRadius } from '@boldgrid/controls/src/controls/border-radius';
import { Margin } from '@boldgrid/controls/src/controls/margin';
import { Padding } from '@boldgrid/controls/src/controls/padding';

import { BoxShadow } from '@boldgrid/controls/src/controls/box-shadow';
import { Border } from '@boldgrid/controls/src/controls/border';
import { MultiSlider } from '@boldgrid/controls/src/controls/multi-slider';
import '../../scss/customizer/controls/_generic.scss';

var $ = jQuery;

export class GenericControls {

	constructor() {
		this.classes = {
			BorderRadius,
			BoxShadow,
			MultiSlider,
			Border,
			Padding,
			Margin
		};
	}

	/**
	 * Initialize all generic controls.
	 *
	 * @since 2.0.0
	 */
	init() {
		$( () => this._bindConfigs() );
		return this;
	}

	/**
	 * Setup the control.
	 *
	 * @since 2.0.0
	 *
	 * @param  {object} wpControl WordPress control instance.
	 */
	_bindConfigs() {
		wp.customize.control.each( ( wpControl ) => {
			if ( wpControl.params.choices && 'boldgrid_controls' === wpControl.params.choices.name ) {
				this._setupControl( wpControl );
			}
		} );
	}


	/**
	 * Setup the control.
	 *
	 * @since 2.0.0
	 *
	 * @param  {object} wpControl WordPress control instance.
	 */
	_setupControl( wpControl ) {
		let bgControl = new this.classes[ wpControl.params.choices.type ]( wpControl.params.choices.settings || {} );

		this._bindRender( wpControl, bgControl );
		this._bindChangeEvent( wpControl, bgControl );
	}

	/**
	 * Setup the rendering capability. When the control is embeded update the DOM
	 * with our control.
	 *
	 * @since 2.0.0
	 *
	 * @param  {object} wpControl WordPress control instance.
	 * @param  {object} bgControl BoldGrid control instance.
	 */
	_bindRender( wpControl, bgControl ) {
		wpControl.deferred.embedded.done( () => {
			setTimeout( () => {
				const $el = $( wpControl.selector ),
					$input = $el.find( 'input' );

				$input.hide();
				$input.after( bgControl.render() );
			} );
		} );
	}

	/**
	 * When the value of the bg control changes, update the wp customizer values.
	 *
	 * @since 2.0.0
	 *
	 * @param  {object} wpControl WordPress control instance.
	 * @param  {object} bgControl BoldGrid control instance.
	 */
	_bindChangeEvent( wpControl, bgControl ) {
		bgControl.events.on( 'change', ( settings ) => {
			wpControl.setting.set( settings );
		} );
	}
}

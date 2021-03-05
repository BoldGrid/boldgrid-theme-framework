import { PaletteSelector } from '../color/palette-selector';
import { Border as BaseBorder } from '@boldgrid/controls/src/controls/border';

export class Border extends BaseBorder {

	/**
	 * Setup the color control.
	 *
	 * @since 2.0.0
	 *
	 * @param {object} options   Options for the parent control.
	 * @param {object} wpControl A WordPress Control.
	 */
	constructor( options, wpControl ) {
		super( options );

		this.paletteSelector = new PaletteSelector();
		this.wpControl = wpControl;
		this.colorControl = wp.customize.control( wpControl.id + '_color' );
	}

	/**
	 * Override the render method, to bind events for the border color.
	 *
	 * @since 2.0.0
	 *
	 * @return {jQuery} jQuery Control.
	 */
	render() {
		let $control = super.render();

		// If an associated color control is found bind events for it.
		if ( this.colorControl ) {
			this.toggleVisibility();
			this._bindBorderColor();
			this._bindParentChange();
			this._bindCustomizerRefresh();
		}

		return $control;
	}

	/**
	 * Update the visibility of the color control.
	 *
	 * @since 2.0.0
	 *
	 * @return {boolean}         Was the control state changed.
	 */
	toggleVisibility() {
		return ! this.settings.media || ! this.settings.media[ this.getSelectedDevice() ].type ?
			this.colorControl.deactivate( { duration: 0 } ) : this.colorControl.activate( { duration: 0 } );
	}

	/**
	 * Override the getting settings call to append the border color.
	 *
	 * @since 2.0.0
	 *
	 * @return {object} Settings.
	 */
	getCssRule( settings ) {
		let rules = super.getCssRule( settings );

		if ( this.colorControl && this.colorControl.setting() ) {
			rules += 'border-color:' + this.paletteSelector.getColor( this.colorControl.setting() ) + ';';
		}

		return rules;
	}

	/**
	 * Given an object of settings, change the inputs.
	 *
	 * @since 1.0.0
	 *
	 * @param  {object} settings Settings for control.
	 */
	applySettings( settings ) {
		super.applySettings( settings );

		if ( this.colorControl ) {
			this.colorControl.setting( settings.color || null );
		}
	}

	/**
	 * Get the current settings.
	 *
	 * @since 1.0.0
	 *
	 * @return {object} Settings for a control.
	 */
	getSettings() {
		let settings = super.getSettings();

		if ( this.colorControl ) {
			settings.color = this.colorControl.setting();
		}

		return settings;
	}


	/**
	 * When the customizer is refreshed update the visibility of the color control.
	 *
	 * @since 2.0.0
	 */
	_bindCustomizerRefresh() {
		$( window ).on( 'boldgrid_customizer_refresh', () => {
			this.toggleVisibility();
		} );
	}

	/**
	 * When the color changes, trigger a change of the width.
	 *
	 * @since 2.0.0
	 */
	_bindBorderColor() {
		this.colorControl.setting.bind( () => this._triggerChangeEvent() );
	}

	/**
	 * When the border style/width changes add color.
	 *
	 * @since 2.0.0
	 */
	_bindParentChange() {
		this.events.on( 'change', () => this.toggleVisibility() );
	}
}

import { BorderRadius } from '@boldgrid/controls/src/controls/border-radius';
import { Margin } from '@boldgrid/controls/src/controls/margin';
import { ContainerWidth } from '@boldgrid/controls/src/controls/container-width';
import { ColWidth } from '@boldgrid/controls/src/controls/col-width';
import { Padding } from '@boldgrid/controls/src/controls/padding';
import { BoxShadow } from '@boldgrid/controls/src/controls/box-shadow';
import { Control as DeviceVisibility } from '@boldgrid/controls/src/controls/device-visibility';
import { Border } from './border';
import { DevicePreview } from './device-preview';
import { MultiSlider } from '@boldgrid/controls/src/controls/multi-slider';
import '../../../scss/customizer/controls/_generic.scss';

export class Control {

	constructor() {
		this.classes = {
			BorderRadius,
			DeviceVisibility,
			BoxShadow,
			MultiSlider,
			Border,
			Padding,
			Margin,
			ContainerWidth,
			ColWidth
		};
	}

	/**
	 * Initialize all generic controls.
	 *
	 * @since 2.0.0
	 */
	init() {
		$( () => {
			this.devicePreview = new DevicePreview();
			this._bindConfigs();
		} );

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
		let bgControl,
			controlSettings = wpControl.params.choices.settings || {};

		this._setDefaults( wpControl, controlSettings );
		this._setSavedValues( wpControl, controlSettings );
		bgControl = new this.classes[ wpControl.params.choices.type ]( controlSettings, wpControl );

		this._bindRender( wpControl, bgControl );
		this._bindChangeEvent( wpControl, bgControl );
	}

	/**
	 * Pass the defaults value from params into the setting option of the control.
	 *
	 * @since 2.0.0
	 *
	 * @param  {object} wpControl       WordPress control.
	 * @param  {object} controlSettings Current Control settings.
	 */
	_setDefaults( wpControl, controlSettings ) {
		const defaults = wpControl.params.default;
		if ( defaults ) {

			// The method for passing in defaults needs to be standardized.
			if ( 'DeviceVisibility' === wpControl.params.choices.type ) {
				controlSettings.control.setting = defaults;
			} else {
				controlSettings.defaults = defaults;
			}
		}
	}

	/**
	 * Get the default values defined by the theme mod.
	 *
	 * @since 2.0.0
	 *
	 * @param  {object} wpControl       WordPress control.
	 * @param  {object} controlSettings Current Control settings.
	 */
	_setSavedValues( wpControl, controlSettings ) {
		let saved = wpControl.setting.get() || false;

		if ( _.isObject( saved ) && saved.media ) {
			controlSettings.saved = saved;

			try {
				controlSettings.saved.media = JSON.parse( controlSettings.saved.media ) || {};
			} catch ( e ) {
				controlSettings.saved.media = {};
			}
		}
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
					$input = $el.find( 'label' );

				$input.after( bgControl.render() );

				// This dummy input removes orginal handlers, and serves as a honeypot for DOM queries.
				$input.replaceWith( $( '<input type="text">' ).hide() );

				// Setup the delete event after render for optimization.
				this._bindDeleteEvent( wpControl, bgControl );

				this.devicePreview.setupControl( bgControl );
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
		let throttled = _.throttle( ( settings ) => {
			let controlSettings = { ...settings };

			controlSettings.media = JSON.stringify( controlSettings.media );

			wpControl.setting.set( controlSettings );
		}, 50 );

		bgControl.events.on( 'change', throttled );
	}

	/**
	 * When the user deletes their settings, unset the WP control setting.
	 *
	 * @since 2.0.0
	 *
	 * @param  {object} wpControl WordPress control instance.
	 * @param  {object} bgControl BoldGrid control instance.
	 */
	_bindDeleteEvent( wpControl, bgControl ) {
		bgControl.events.on( 'deleteSettings', () => wpControl.setting.set( null ) );
	}
}

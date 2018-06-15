const $ = jQuery;

export class DevicePreview {

	constructor() {
		this.$devices = $( '.devices' );
	}

	/**
	 * Setup any device related events.
	 *
	 * @since 2.0.0
	 * @param  {object} control BoldGrid Controls instance.
	 */
	setupControl( control ) {
		this._setupDeviceChange( control );
	}

	/**
	 * When the user changes a device to edit, match the device in the preview.
	 *
	 * @since  2.0.0
	 * @param  {object} control BoldGrid Controls instance.
	 */
	_setupDeviceChange( control ) {
		control.events.on( 'deviceChange', ( device ) => {
			let changeTo = 'desktop',
				$button;

			if ( 'tablet' === device ) {
				changeTo = device;
			} else if ( 'phone' === device ) {
				changeTo = 'mobile';
			}

			$button = this.$devices.find( `[data-device="${changeTo}"]` );

			if ( $button.length ) {
				$button.click();
			}
		} );
	}
}

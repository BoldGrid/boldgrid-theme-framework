export class Required {

	constructor() {
		this.bound = {};
		this.options = window.BOLDGRID.CUSTOMIZER.data.customizerOptions;
	}

	/**
	 * Bind DOM load events..
	 *
	 * @since 2.0.0
	 */
	init() {

		// This hook runs on refresh and on load.
		$( () => $( window ).on( 'boldgrid_customizer_refresh', () => this._setup() ) );
	}

	/**
	 * Loops through all the configs and updates visibility.
	 *
	 * @since 2.0.0
	 */
	_setup() {
		for ( const [ switchControl ] of Object.entries( this.options.required ) ) {
			if ( ! _.isUndefined( wp.customize( switchControl ) ) ) {
				this._toggle( switchControl );

				// Bind event only once per control.
				if ( ! this.bound[ switchControl ] ) {
					this.bound[ switchControl ] = true;
					wp.customize( switchControl ).bind( () => this._toggle( switchControl ) );
				}
			}
		}
	}

	/**
	 * Toggle the visibility of the control.
	 *
	 * @since 2.0.0
	 *
	 * @param  {string} switchControl Switch Control Name.
	 */
	_toggle( switchControl ) {
		for ( let name of this.options.required[ switchControl ] ) {
			if ( ! _.isUndefined( wp.customize.control( name ) ) ) {
				let action = wp.customize( switchControl )() ? 'activate' : 'deactivate';
				wp.customize.control( name )[ action ]();
			}
		}
	}
}

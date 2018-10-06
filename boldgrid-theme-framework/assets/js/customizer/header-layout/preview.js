/* global BoldGrid:false */
const api = wp.customize;

export class Preview {

	/**
	 * Run these on init.
	 *
	 * @since 2.0.0
	 */
	bindEvents() {
		this._setupResize();
	}

	/**
	 * When certain controls are updated we should trigger a resize to allow
	 * all layout calculations to reapply.
	 *
	 * This method is debounced to 1 second.
	 *
	 * @since 2.0.0
	 */
	_setupResize() {
		let resize = _.debounce( () => BoldGrid.custom_header.calc(), 500 );
		api( 'bgtfw_header_padding', 'bgtfw_header_border', 'bgtfw_header_margin', ( ...args ) => {
			args.map( ( control ) => control.bind( resize ) );
		} );
	}
}

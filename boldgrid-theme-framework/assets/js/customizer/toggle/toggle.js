/* esversion: 6 */
const api = wp.customize;

/**
 * This class is responsible for managing the expand and collapse
 * triggers for panels and sections in the WordPress customizer.
 *
 * @since 2.0.0
 */
export class Toggle {

	/**
	 * Constructor
	 *
	 * @since 2.0.0
	 *
	 * @param {String} id ID of the control to bind to.
	 * @param {String} fn Do something with updated value.
	 */
	constructor( id = null, fn = null ) {
		Object.assign( this, { id, fn } );
		$( () => this._onLoad() );
	}

	/**
	 * Initialize type.
	 *
	 * @since 2.0.0
	 */
	_bindControl() {
		api( this.id, ( value ) => value.bind( ( to ) => 'function' === typeof this.fn ? this.fn.call( this, to ) : this.bound( to ) ) );
	}

	/**
	 * Onload event.
	 *
	 * @since 2.0.0
	 */
	_onLoad() {
		this._bindControl();
	}

	/**
	 * Noop
	 *
	 * @since 2.0.0
	 */
	bound() {}
}

export default Toggle;

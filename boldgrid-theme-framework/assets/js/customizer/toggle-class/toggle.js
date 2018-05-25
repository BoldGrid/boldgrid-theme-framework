/* esversion: 6 */
const api = wp.customize;
const $ = jQuery;

/**
 * This class is responsible for managing the expand and collapse
 * triggers for panels and sections in the WordPress customizer.
 *
 * @since 2.0.0
 */
export class ToggleClass {

	/**
	 * Constructor
	 *
	 * @since 2.0.0
	 *
	 * @param {String} id ID of the control to bind to.
	 * @param {String} element EL element to toggle classes on.
	 * @param {String|Function} remove Classes or method to remove when control is updated.
	 */
	constructor( id = null, element = null, remove = null, cb = () => {} ) {
		Object.assign( this, { id, element, remove, cb } );
		$( () => this._onLoad() );
	}

	/**
	 * Initialize type.
	 *
	 * @since 2.0.0
	 */
	_bindToggle() {
		api( this.id, ( value ) => value.bind( ( to ) => this.toggle( to ) ) );
	}

	/**
	 * Handle the toggle of classes on an element.
	 *
	 * @2.0.0
	 *
	 * @param {Mixed} to New value control is updating to.
	 */
	toggle( to ) {
		$( this.element ).removeClass( this.remove ).addClass( to ) && this.cb();
	}

	/**
	 * Onload event.
	 *
	 * @since 2.0.0
	 */
	_onLoad() {
		this._bindToggle();
	}
}

export default ToggleClass;

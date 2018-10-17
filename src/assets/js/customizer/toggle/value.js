/* esversion: 6 */
import Base from './toggle';

/**
 * This class is responsible for managing the expand and collapse
 * triggers for panels and sections in the WordPress customizer.
 *
 * @since 2.0.0
 */
export class ToggleValue extends Base {

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
		super( ...arguments );
		Object.assign( this, { id, element, remove, cb } );
	}

	/**
	 * Handle the toggle of classes on an element.
	 *
	 * @2.0.0
	 *
	 * @param {Mixed} to New value control is updating to.
	 */
	bound( to ) {
		$( this.element ).removeClass( this.remove ).addClass( to ) && this.cb();
	}
}

export default ToggleValue;

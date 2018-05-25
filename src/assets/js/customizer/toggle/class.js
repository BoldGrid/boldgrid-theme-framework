/* esversion: 6 */
import Toggle from './toggle';
import ToggleValue from './value';

const api = wp.customize;
const $ = jQuery;

/**
 * This class is responsible for managing the expand and collapse
 * triggers for panels and sections in the WordPress customizer.
 *
 * @since 2.0.0
 */
export class ToggleClass extends ToggleValue {

	/**
	 * Handle the toggle of classes on an element.
	 *
	 * @2.0.0
	 *
	 * @param {Mixed} to New value control is updating to.
	 */
	bound( to ) {
		to ? $( this.element ).addClass( this.remove ) : $( this.element ).removeClass( this.remove ) && this.cb();
	}
}

export default ToggleClass;

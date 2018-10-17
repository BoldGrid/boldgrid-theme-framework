/* esversion: 6 */
import ToggleValue from './value';

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
		$( this.element ).toggleClass( this.remove, to ) && this.cb();
	}
}

export default ToggleClass;

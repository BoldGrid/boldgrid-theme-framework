/* esversion: 6 */
import Expand from './expand';

export class ExpandPanel extends Expand {

	constructor( { type = 'panel' } = {} ) {
		super( ...arguments );
		this.type = type;
	}
}

export default ExpandPanel;

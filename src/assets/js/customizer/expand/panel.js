/* esversion: 6 */
import Expand from './expand';

export class ExpandPanel extends Expand {

	constructor( { type = 'panel', previousRedirect = false } = {} ) {
		super( ...arguments );
		this.type = type;
		this.previousRedirect = previousRedirect;
	}
}

export default ExpandPanel;

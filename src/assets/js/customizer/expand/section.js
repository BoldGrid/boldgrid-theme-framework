/* esversion: 6 */
import Expand from './expand';

export class ExpandSection extends Expand {

	constructor( { type = 'section', previousRedirect = false } = {} ) {
		super( ...arguments );
		this.type = type;
		this.previousRedirect = previousRedirect;
	}
}

export default ExpandSection;

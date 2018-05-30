/* esversion: 6 */
import Expand from './expand';

export class ExpandSection extends Expand {

	constructor( { type = 'section' } = {} ) {
		super( ...arguments );
		this.type = type;
	}
}

export default ExpandSection;

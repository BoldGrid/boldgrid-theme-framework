/* esversion: 6 */
import Expand from './expand';

const api = wp.customize;

export class ExpandSection extends Expand {

	constructor( { type = 'section' } = {} ) {
		super( ...arguments );
		this.type = type;
	}
}

export default ExpandSection;

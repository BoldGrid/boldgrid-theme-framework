/* esversion: 6 */
import Expand from '../expand';

const api = wp.customize;

export class PanelExpand extends Expand {

	constructor( { type = 'panel' } = {} ) {
		super( ...arguments );
		this.type = type;
	}
}

export default PanelExpand;

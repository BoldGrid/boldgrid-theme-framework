/* esversion: 6 */
const api = wp.customize;

export class Expand {

	constructor( { type = null, typeId = null, url = null } = {} ) {
		$( () => _.extend( this, ...arguments, { previousUrl: null, preview: api.previewer.previewUrl } ) && this._onLoad() );
		console.log( this );
	}

	/**
	 * Bind Sidebar Count Controls.
	 *
	 * @since 2.0.0
	 *
	 * @param {string} mod Theme Mod ID
	 */
	_type() {
		api[ this.type ]( this.typeId, ( type ) => this._bindType( type ) );
	}

	/**
	 * Bind Sidebar Count Controls.
	 *
	 * @since 2.0.0
	 *
	 * @param {string} mod Theme Mod ID
	 */
	_bindType( type ) {
		type.expanded.bind( ( isExpanded ) => this._bindExpanded( isExpanded ) );
	}

	/**
	 * Bind Sidebar Count Controls.
	 *
	 * @since 2.0.0
	 *
	 * @param {string} mod Theme Mod ID
	 */
	_bindExpanded( isExpanded ) {
		let clear = () => this.previousUrl = null;
		isExpanded ? this.expanded( clear ) : this.collapsed( clear );
	}

	/**
	 * Bind Sidebar Count Controls.
	 *
	 * @since 2.0.0
	 *
	 * @param {Function} clear Reference method to bind.
	 */
	expanded( clear ) {
		this.previousUrl = this.preview.get();
		this.preview.set( this.url );
		this.preview.bind( clear );
	}

	/**
	 * Bind Sidebar Count Controls.
	 *
	 * @since 2.0.0
	 *
	 * @param {string} clear Reference method to unbind.
	 */
	collapsed( clear ) {
		this.preview.unbind( clear );
		this.previousUrl && this.preview.set( this.previousUrl );
	}

	_onLoad() {
		this._type();
	}
}

export default Expand;

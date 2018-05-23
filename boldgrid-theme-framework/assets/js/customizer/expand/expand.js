/* esversion: 6 */
const api = wp.customize;
const clear = () => this.previousUrl = null;

/**
 * This class is responsible for managing the expand and collapse
 * triggers for panels and sections in the WordPress customizer.
 *
 * @since 2.0.0
 */
export class Expand {

	/**
	 * Constructor
	 *
	 * @since 2.0.0
	 *
	 * @param {type}   string Either 'panel' or 'section'.
	 * @param {typeId} string ID of the panel or section.
	 * @param {url}    string URL to direct previewer to.
	 */
	constructor( { type = null, typeId = null, url = null } = {} ) {
		$( () => _.extend( this, ...arguments, { previousUrl: null, preview: api.previewer.previewUrl } ) && this._onLoad() );
	}

	/**
	 * Initialize type.
	 *
	 * @since 2.0.0
	 */
	_type() {
		api[ this.type ]( this.typeId, ( type ) => this._bindType( type ) );
	}

	/**
	 * Bind type
	 *
	 * @since 2.0.0
	 *
	 * @param {Object} type A panel or section object.
	 */
	_bindType( type ) {
		type.expanded.bind( ( isExpanded ) => this._bindExpanded( isExpanded ) );
	}

	/**
	 * Bind isExpanded
	 *
	 * @since 2.0.0
	 *
	 * @param {bool} isExpanded Triggers expanded or collapsed.
	 */
	_bindExpanded( isExpanded ) {
		isExpanded ? this.expanded( clear ) : this.collapsed( clear );
	}

	/**
	 * Expanded function.
	 *
	 * Binds the previous URL and sets previewer to new URL.
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
	 * Collapsed function.
	 *
	 * Directs user back to previousURL they were in before
	 * expanding, and then unbinds the previous URL binding.
	 *
	 * @since 2.0.0
	 *
	 * @param {Function} clear Reference method to unbind.
	 */
	collapsed( clear ) {
		this.preview.unbind( clear );
		this.previousUrl && this.preview.set( this.previousUrl );
	}

	/**
	 * Onload event.
	 *
	 * @since 2.0.0
	 */
	_onLoad() {
		this._type();
	}
}

export default Expand;

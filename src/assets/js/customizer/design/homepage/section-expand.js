/* esversion: 6 */
import ExpandSection from '../../expand/section';

const api = wp.customize;

/**
 * This class is responsible for setting the URL to go to, and
 * the sections's ID when the Homepage section is expanded.
 *
 * @since 2.0.0
 */
export class HomepageSectionExpand extends ExpandSection {

	/**
	 * Constructor
	 *
	 * @since 2.0.0
	 *
	 * @param {String} typeId ID of the section to add expand and collapse bindings.
	 * @param {String} url    URL the user should be directed to on expanded state.
	 */
	constructor( { typeId = 'static_front_page' } = {} ) {
		super( ...arguments );
		this.typeId = typeId;
		$( () => this.setUrl() );
		_.extend( this, ...arguments );
	}

	/**
	 * Set the URL the previewer should go to on the expanded state.
	 *
	 * This URL is provided in core wp.customize for the homepage.
	 *
	 * @return {String} this.url The URL for the previewer.
	 */
	setUrl() {
		return this.url = api.settings.url.home;
	}
}

export default HomepageSectionExpand;

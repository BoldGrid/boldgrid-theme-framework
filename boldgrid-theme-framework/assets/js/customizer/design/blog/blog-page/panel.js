/* esversion: 6 */
import PanelExpand from '../../../panel/expand';

const api = wp.customize;

export class BlogPagePanel extends PanelExpand {

	/**
	 * Constructor
	 *
	 * @since 2.0.0
	 *
	 * @param {String} typeId ID of the panel to add expand and collapse bindings.
	 * @param {String} url    URL the user should be directed to on expanded state.
	 */
	constructor( { typeId = 'bgtfw_blog_blog_page_panel', url = null } = {} ) {
		super( ...arguments );
		this.typeId = typeId;
		$( () => this.setUrl() );
		_.extend( this, ...arguments );
	}

	/**
	 * Set the URL the previewer should go to on the expanded state.
	 *
	 * This URL is based on the show_on_front and home URL settings.
	 *
	 * @return {String} this.url The URL for the previewer.
	 */
	setUrl() {
		return this.url = 'page' === api( 'show_on_front' )() ? api.settings.url.home + '?page_id=' + api( 'page_for_posts' )() : api.settings.url.home;
	}
}

export default BlogPagePanel;

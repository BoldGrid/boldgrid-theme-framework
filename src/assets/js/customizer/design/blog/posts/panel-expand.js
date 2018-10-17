/* esversion: 6 */
import ExpandPanel from '../../../expand/panel';

const api = wp.customize;

/**
 * This class is responsible for setting the URL to go to, and
 * the panel's ID when the Posts panel is expanded.
 *
 * @since 2.0.0
 */
export class BlogPostsPanelExpand extends ExpandPanel {

	/**
	 * Constructor
	 *
	 * @since 2.0.0
	 *
	 * @param {String} typeId ID of the panel to add expand and collapse bindings.
	 */
	constructor( { typeId = 'bgtfw_blog_posts_panel' } = {} ) {
		super( ...arguments );
		this.typeId = typeId;
		$( () => this.setUrl() );
		_.extend( this, ...arguments );
	}

	/**
	 * Set the URL the previewer should go to on the expanded state.
	 *
	 * This URL is retrieved from a query to the db for the most
	 * recent post that is published.
	 *
	 * @return {String} this.url The URL for the previewer.
	 */
	setUrl() {
		return this.url = api.settings.url.home + '?p=' + window.BOLDGRID.CUSTOMIZER.data.design.blog.posts.mostRecentPost;
	}
}

export default BlogPostsPanelExpand;

/* esversion: 6 */
import ExpandPanel from '../../../expand/panel';

const api = wp.customize;

/**
 * This class is responsible for setting the URL to go to, and
 * the panel's ID when the Blog Page panel is expanded.
 *
 * @since 2.0.0
 */
export class BlogPagePanelExpand extends ExpandPanel {

	/**
	 * Constructor
	 *
	 * @since 2.0.0
	 *
	 * @param {String} typeId ID of the panel to add expand and collapse bindings.
	 * @param {String} url    URL the user should be directed to on expanded state.
	 */
	constructor( { typeId = 'bgtfw_blog_blog_page_panel' } = {} ) {
		super( ...arguments );
		this.typeId = typeId;
		$( () => this.setUrl() && this._bindControls() );
		_.extend( this, ...arguments );
	}

	/**
	 * Bind control settings.
	 *
	 * These settings modify the URL that could be used to direct
	 * the user's previewer to.
	 *
	 * @since 2.0.0
	 */
	_bindControls() {
		const controls = [ 'show_on_front', 'page_on_front', 'page_for_posts' ];
		_.each( controls, ( control ) => {
			api( control, value => value.bind( () => this.setUrl() ) );
		} );
	}

	/**
	 * Set the URL the previewer should go to on the expanded state.
	 *
	 * This URL is based on the show_on_front and home URL settings.
	 *
	 * @return {String} this.url The URL for the previewer.
	 */
	setUrl() {
		let showOnFront = api( 'show_on_front' )();
		let pageOnFrontId = parseInt( api( 'page_on_front' )(), 10 );
		let pageId = parseInt( api( 'page_for_posts' )(), 10 );
		if ( 'page' === showOnFront ) {
			if ( 0 <= pageOnFrontId ) {
				this.url = api.settings.url.home;
			}
			if ( 0 <= pageId ) {
				this.url = api.settings.url.home + '?page_id=' + pageId;
			}
		} else {
			this.url = api.settings.url.home;
		}

		return this.url;
	}
}

export default BlogPagePanelExpand;

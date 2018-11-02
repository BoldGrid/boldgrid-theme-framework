/* esversion: 6 */
const api = wp.customize;

export class WidgetSectionUpdate {

	constructor() {
		this.classControls = [ 'bgtfw_header_layout', 'bgtfw_footer_layout' ];
	}

	/**
	 * Class initialized.
	 *
	 * @since 2.0.0
	 *
	 * @return {Preview} Class instance.
	 */
	init() {
		$( () => this._onLoad() );

		return this;
	}

	/**
	 * On load.
	 *
	 * @since 2.0.0
	 */
	_onLoad() {
		_.each( this.classControls, ( control ) => this._bindSidebarControl( control ) );
	}

	/**
	 * Bind Sidebar Count Controls.
	 *
	 * @since 2.0.0
	 *
	 * @param {string} mod Theme Mod ID
	 */
	_bindSidebarControl( mod ) {
		api( mod, ( value ) => value.bind( () => this.toggleSections( mod ) ) );
	}

	/**
	 * Toggle the active sections in the Widgets panel.
	 *
	 * @since 2.0.0
	 *
	 * @param {String} type Theme Mod ID for sidebar area counts.
	 */
	toggleSections( type ) {
		let sectionType = type.includes( 'header' ) ? 'header' : 'footer';
		let activeSections = this.getActiveSections( sectionType );
		this.deactivateSections( activeSections );
		this.activateSections( type );
	}

	/**
	 * Activate sections.
	 *
	 * @since 2.0.0
	 *
	 * @param {Array} sections Array of section objects to activate.
	 */
	activateSections( type ) {
		let sectionType = type.includes( 'header' ) ? 'header' : 'footer';
		type = api( type )();
		if ( ! _.isEmpty( type ) ) {
			for ( let i = 1; i <= type; i++ ) {
				api.section( 'sidebar-widgets-' + sectionType + '-' + i ).activate();
			}
		}
	}

	/**
	 * Deactivate sections.
	 *
	 * @since 2.0.0
	 *
	 * @param {Array} sections Array of section objects to deactivate.
	 */
	deactivateSections( sections ) {
		_.each( sections, function( section ) {
			api.section( section.id ).deactivate();
		} );
	}

	/**
	 * Get the active sections in widgets panel.
	 *
	 * @since 2.0.0
	 *
	 * @param  {String} type Type of sidebar area to find.
	 * @return {Array}       Array of active sidebar section objects.
	 */
	getActiveSections( type ) {
		return _.filter( wp.customize.panel( 'widgets' ).sections(), ( section ) => {
			return section.active() && section.id.includes( type );
		} );
	}
}

export default WidgetSectionUpdate;

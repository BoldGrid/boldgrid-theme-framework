/* esversion: 6 */
const api = wp.customize;

/**
 * This class is responsible for collapsing the hamburger controls
 * that rely on the hamburger menu being enabled for menu locations.
 *
 * @since 2.0.0
 */
export class HamburgerControlToggle {

	/**
	 * Constructor
	 *
	 * @since 2.0.0
	 *
	 * @param {type}   string Either 'panel' or 'section'.
	 * @param {typeId} string ID of the panel or section.
	 * @param {url}    string URL to direct previewer to.
	 */
	constructor() {
		api.bind( 'ready', () => this._onLoad() );
	}

	/**
	 * Bind the hamburger toggles.
	 *
	 * @since 2.0.0
	 */
	_bindHamburgerToggle() {
		let locations = Object.keys( this.menus );
		let toggleIds = locations.map( ( location ) => `bgtfw_menu_hamburger_${location}_toggle` );
		api( ...toggleIds, ( ...controls ) => {
			controls.map( ( setting ) => {
				let section = api.control( setting.id ).section();
				let dependants = api.section( section ).controls();
				dependants.map( ( dependant ) => {
					if ( dependant.id !== setting.id ) {
						wp.customize.control( dependant.id, ( control ) => {
							var display = function() {
								setting.get() ? control.container.show() : control.container.hide();
							};
							display();
							setting.bind( display );
						} );
					}
				} );
			} );
		} );
	}

	/**
	 * Onload event.
	 *
	 * @since 2.0.0
	 */
	_onLoad() {
		this.menus = window._wpCustomizeNavMenusSettings.locationSlugMappedToName;
		this._bindHamburgerToggle();
	}
}

export default HamburgerControlToggle;

/* esversion: 6 */
const api = wp.customize;

/**
 * This class is responsible for collapsing the hamburger controls
 * that rely on the hamburger menu being enabled for menu locations.
 *
 * @since 2.0.0
 */
export class HoverBackgroundToggle {

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
	_bindHoverBackgroundToggle() {
		let locations = Object.keys( this.menus );
		let toggleIds = locations.map( ( location ) => `bgtfw_menu_items_hover_effect_${location}` );
		api( ...toggleIds, ( ...controls ) => {
			controls.map( ( setting ) => {
				let section = api.control( setting.id ).section();
				let dependants = api.section( section ).controls();
				dependants.map( dependant => {
					if ( dependant.id.includes( '_hover_color' ) ) {
						api.control( dependant.id, ( control ) => {
							let display = () => {
								let choices = wp.customize.control( 'bgtfw_menu_items_hover_effect_main' ).params.choices;
								let effectControlId = dependant.id.replace( 'color', 'effect' );
								let currentChoice = api.control( effectControlId ).setting.get();

								// Displays "Primary Color" control for all categories except "Border Effects" (optgroup3), which uses the secondary color.
								_.isUndefined( choices.optgroup3[1][ currentChoice ] ) ? control.container.show() : control.container.hide();
							};
							display();
							setting.bind( display );
						} );
					}
					if ( dependant.id.includes( '_hover_background' ) ) {
						api.control( dependant.id, ( control ) => {
							let display = () => {
								let choices = wp.customize.control( 'bgtfw_menu_items_hover_effect_main' ).params.choices;
								let effectControlId = dependant.id.replace( 'background', 'effect' );
								let currentChoice = api.control( effectControlId ).setting.get();

								// Displays "Secondary Color" control for "Border Effects" (optgroup3) and "Two Color Transitions" (optgroup2) option groups.
								_.isUndefined( choices.optgroup2[1][ currentChoice ] ) && _.isUndefined( choices.optgroup3[1][ currentChoice ] ) ? control.container.hide() : control.container.show();
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
		var menus  = window._wpCustomizeNavMenusSettings.locationSlugMappedToName;
		this.menus = {};
		Object.keys( menus ).forEach( ( menu ) => {
			if ( api.panel( `bgtfw_menu_location_${menu}` ) ) {
				this.menus[ menu ] = menus[ menu ];
			}
		} );
		this._bindHoverBackgroundToggle();
	}
}

export default HoverBackgroundToggle;

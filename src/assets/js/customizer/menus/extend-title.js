/* global _wpCustomizeNavMenusSettings:false */
const api = wp.customize;

/**
 * This class is responsible for managing the expand and collapse
 * triggers for panels and sections in the WordPress customizer.
 *
 * @since 2.0.0
 */
export class SectionExtendTitle {

	/**
	 * Constructor
	 *
	 * @since 2.0.0
	 */
	constructor() {
		$( () => this._onLoad() );
	}

	/**
	 * Bind the menu panel section titles when they are expanded.
	 *
	 * @since 2.0.0
	 */
	_bindMenuPanels() {
		for ( const location of Object.keys( this.menus ) ) {

			// Do not try to bind panels that do not exist.
			if ( ! api.panel( `bgtfw_menu_location_${location}` ) ) {
				continue;
			}
			api.panel( `bgtfw_menu_location_${location}` ).expanded.bind( () => this.updateTitle( location ) );
			api( `bgtfw_menu_hamburger_${location}`, ( value ) => value.bind( () => this.updateTitle( location ) ) );
			api( `bgtfw_menu_hamburger_${location}_toggle`, ( value ) => value.bind( () => this.updateTitle( location ) ) );
		}
	}

	/**
	 * Handles updating the section titles.
	 *
	 * @since 2.0.0
	 */
	updateTitle( location ) {
		let title = api.section( `bgtfw_menu_hamburgers_${location}` ).container.find( 'h3' );
		let span = '<span class="section-title-extended">%s</span>';
		let text = 'Currently Disabled';
		title.find( '.section-title-extended' ).remove();

		if ( api( `bgtfw_menu_hamburger_${location}_toggle` )() ) {
			let control = api.control( `bgtfw_menu_hamburger_${location}` );
			let choices = _.invert( control.params.choices );
			text = 'Currently Using: ' + choices[ control.setting.get() ];
		}

		title.append( span.replace( '%s', text ) );
	}

	/**
	 * Onload event.
	 *
	 * @since 2.0.0
	 */
	_onLoad() {
		this.menus = _wpCustomizeNavMenusSettings.locationSlugMappedToName;
		this._bindMenuPanels();
	}
}

export default SectionExtendTitle;

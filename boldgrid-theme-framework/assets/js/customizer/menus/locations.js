/* esversion: 6 */
const api = wp.customize;

/**
 * This class is responsible for collapsing the hamburger controls
 * that rely on the hamburger menu being enabled for menu locations.
 *
 * @since 2.0.0
 */
export class Locations {

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
	_bindSection() {
		api.section( 'menu_locations', function( section ) {
			section.expanded.bind( function( isExpanded ) {
				if ( isExpanded ) {
					_.each( $( '[id^="customize-control-nav_menu_locations-"]' ), function( menuLocation ) {
						var location, html, $this;
						$this = $( menuLocation );
						if ( ! $this.find( '.edit-menu-design' ).length ) {
							location = $this.find( '.create-menu' ).data( 'locationId' );
							html = `<a href="#" onClick="event.preventDefault(); wp.customize.panel( 'bgtfw_menus_panel' ).expand({ allowMultiple: true }); wp.customize.panel( 'bgtfw_menu_location_${location}' ).expand();" type="button" class="button-link edit-menu-design" aria-label="Edit Menu Design">Edit Menu Design</a>`;
							$( html ).insertAfter( $this.find( '.edit-menu' ) );
						}
					} );
				}
			} );
		} );
	}

	/**
	 * Onload event.
	 *
	 * @since 2.0.0
	 */
	_onLoad() {
		this._bindSection();
	}
}

export default Locations;

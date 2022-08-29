/* global _wpCustomizeNavMenusSettings:false */
const api = wp.customize;

export default {
	ready() {
		api.controlConstructor.nav_menu_location.__super__.ready.apply( this, arguments );
		api.bind( 'ready', () => {

			// Controls that update menu locations dynamically.
			let dynamicControls = [
				'bgtfw_fixed_header',
				'bgtfw_header_layout_position',
				'bgtfw_sticky_header_layout',
				'bgtfw_sticky_header_layout_custom',
				'bgtfw_header_preset',
				'bgtfw_sticky_header_preset',
				'bgtfw_header_layout',
				'bgtfw_header_layout_custom',
				'bgtfw_footer_layout'
			];

			// Update menu location controls displayed throughout the various nav menu sections/panels.
			let customMenus = [];
			Object.keys( _wpCustomizeNavMenusSettings.locationSlugMappedToName ).forEach( menu => {
				let isActive = false;

				// Only mark as active if there is a panel for it.
				if ( /.*_\d+/.test( menu ) && api.panel( `bgtfw_menu_location_${menu}` ) ) {
					isActive = true;
					customMenus.push( menu );
				}
				api.controlConstructor.nav_menu_location.prototype.updateMenuLocations( menu, isActive );
			} );
			let menus = api.control( 'bgtfw_header_layout_custom' ).getConnectedMenus()
				.map( menu => menu.replace( 'boldgrid_menu_', '' ) );
			menus = menus.concat( customMenus );
			api.controlConstructor.nav_menu_location.prototype.updateSectionDescription( menus );

			// Bind the menu locations when these controls change the active menu locations used.
			dynamicControls.forEach( ctrl => {
				api( ctrl, setting => {
					this.toggleUsedLocations();
					setting.bind( () => this.toggleUsedLocations() );
				} );
			} );

			// Bind the 'New Menu' section expansion to show / hide locations on this section.
			api.section( 'add_menu' ).expanded.bind( () => this.toggleUsedLocations() );

			api.panel( 'nav_menus' ).expanded.bind( () => this.toggleUsedLocations() );

			// Bind to section add to listen for newly created menu sections being added dynamically.
			api.section.bind( 'add', function( section ) {
				if ( section && section.params && section.params.type && 'nav_menu' === section.params.type ) {
					api.control( section.id + '[locations]', function( control ) {
						control.deferred.embedded.done( function() {

							// Collect the dynamic controls connected menus.
							let menus = api.control( 'bgtfw_header_layout_custom' ).getConnectedMenus()
								.map( menu => menu.replace( 'boldgrid_menu_', '' ) );

							// Update section descriptions with the correct location counts.
							api.controlConstructor.nav_menu_location.prototype.updateSectionDescription( menus );

							// Update menu location controls displayed throughout the various nav menu sections/panels.
							Object.keys( _wpCustomizeNavMenusSettings.locationSlugMappedToName ).forEach( menu => {
								let isActive = menus.includes( menu );
								if ( /.*_\d+/.test( menu ) ) {
									isActive = true;
								}

								// If there is no panel for this location, mark inactive.
								if ( ! api.panel( `bgtfw_menu_location_${menu}` ) ) {
									isActive = false;
								}
								api.controlConstructor.nav_menu_location.prototype.updateMenuLocations( menu, isActive );
							} );
						} );
					} );
				}
			} );
		} );
	},

	toggleUsedLocations() {

		// Split string like: nav_menu_locations[main] ==> main.
		let locationId = this.id.slice( 19, -1 );

		// Collect the dynamic controls connected menus.
		let menus = api.control( 'bgtfw_header_layout_custom' ).getConnectedMenus()
			.map( menu => menu.replace( 'boldgrid_menu_', 'nav_menu_locations[' ) + ']' );

		// This checks if the control matches any of our connected menus set in dynamic controls.
		let isActive;

		if ( /.*_\d+/.test( this.id ) ) {
			isActive = function() {
				return true;
			};
		} else {
			isActive = () => menus.includes( this.id ) ? true : false;
		}
		let panel = api.panel( `bgtfw_menu_location_${ locationId }` );

		// If there is no panel for this location quit.
		if ( ! panel ) {
			return;
		}

		// Force the active panel state to read JS state set with isActive() and ignore server response.
		panel.active.validate = isActive;

		// Update "Design" menu panels based on active state declared in the isActive() call.
		panel.active.set( isActive() );

		isActive() ? panel.activate() : panel.deactivate();

		// Force the active state to ignore whatever is sent from server and use JS active state we declared.
		this.active.validate = isActive;

		// Set the initial active state for control.
		this.active.set( isActive() );

		let customMenus = [];
		Object.keys( _wpCustomizeNavMenusSettings.locationSlugMappedToName ).forEach( menu => {
			if ( /.*_\d+/.test( menu ) ) {
				customMenus.push( menu );
			}
		} );
		menus = menus.concat( customMenus );

		// Update section descriptions counts.
		this.updateSectionDescription( menus );

		// Update menu location checkboxes.
		// @todo: set active state for these instead of just hide/show.
		this.updateMenuLocations( locationId, isActive() );
	},

	/**
	 * Updates the menu_locations section's description.
	 *
	 * The section description contains the current registered nav_menu_locations
	 * which need to be updated with the new menu location count to make sense.
	 *
	 * @since 2.1.0
	 */
	updateSectionDescription( locations ) {

		// Ensure that theme configs didn't dupe locations.
		locations = _.uniq( locations ).length;

		let section = api.section( 'menu_locations' ),
			oldDesc = $( section.params.description )[0].textContent,
			newDesc = oldDesc.replace( /\d+/g, locations );

		section.params.description = section.params.description.replace( oldDesc, newDesc );
		section.container.find( '.description p:first-child, .customize-section-title-menu_locations-description' )
			.each( ( index, desc ) => $( desc ).text( desc.textContent.replace( /\d+/g, locations ) ) );
	},

	/**
	 * Update available Menu Locations.
	 *
	 * This hides/shows all menu location controls once the menu locations
	 * have been updated.  The controls are the checkboxes used throughout
	 * the menus interfaces for menu locations, add new menu, editing menu etc.
	 *
	 * @since 2.1.0
	 */
	updateMenuLocations( id, active ) {
		if ( active ) {
			$( `[data-location-id="${ id }"]` ).closest( 'li' ).show();
		} else {
			$( `[data-location-id="${ id }"]` ).closest( 'li' ).hide();
		}
	}
};

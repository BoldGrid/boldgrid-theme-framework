( function( api ) {

	// Extends the boldgrid-customizer-help section.
	api.sectionConstructor['boldgrid-customizer-help'] = api.Section.extend( {

		// No events for this type of section.
		attachEvents: function () {},

		// Always make the section active.
		isContextuallyActive: function () {
			return true;
		}
	} );

} )( wp.customize );

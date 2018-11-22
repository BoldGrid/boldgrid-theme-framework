
const api = wp.customize;

export default function() {
	var sections, panels;

	// Reflow sections.
	sections = [];

	api.section.each( section => {
		if ( 'bgtfw_section' !== section.params.type || 'undefined' === typeof section.params.section ) {
			return;
		}

		sections.push( section );
	} );

	sections.sort( api.utils.prioritySort ).reverse();

	$.each( sections, ( i, section ) => {
		var parentContainer = $( '#sub-accordion-section-' + section.params.section );
		parentContainer.children( '.section-meta' ).after( section.headContainer );
	} );

	// Reflow panels.
	panels = [];

	api.panel.each( panel => {
		if ( 'bgtfw_panel' !== panel.params.type || 'undefined' === typeof panel.params.panel ) {
			return;
		}

		panels.push( panel );
	} );

	panels.sort( api.utils.prioritySort ).reverse();

	$.each( panels, ( i, panel ) => {
		var parentContainer = $( '#sub-accordion-panel-' + panel.params.panel );
		parentContainer.children( '.panel-meta' ).after( panel.headContainer );
	} );

	// Handle home icon click.
	$( '.customize-action > .dashicons-admin-home, .preview-notice > .dashicons-admin-home' ).on( 'click', function( event ) {
		var baseId,
			el = event.delegateTarget,
			links = $( $( el ).siblings( 'a' ) ).get().reverse();

		_.each( links, function( link ) {
			if ( _.isFunction( link.onclick ) ) {
				link.onclick.call( link, event );
			}
		} );

		// Detect if whatever is currently open is a section or panel.
		if ( $( '.control-panel-bgtfw_panel.current-panel' ).length ) {
			baseId = $( '.control-panel-bgtfw_panel.current-panel' );
			baseId = baseId.attr( 'id' ).replace( 'sub-accordion-panel-', '' );
			if ( api.panel( baseId ) ) {
				api.panel( baseId ).collapse();
			}
		} else if ( $( '.control-section-bgtfw_section.open' ).length ) {
			baseId = $( '.control-section-bgtfw_section.open' );
			baseId = baseId.attr( 'id' ).replace( 'sub-accordion-section-', '' );
			if ( api.section( baseId ) ) {
				api.section( baseId ).collapse();
			}
		}
	} );
}

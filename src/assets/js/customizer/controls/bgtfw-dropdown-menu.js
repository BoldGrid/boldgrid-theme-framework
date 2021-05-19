const api = wp.customize;

export default () => {
	api.bind( 'ready', () => {
		var bindAutoFocus = ( control ) => {
			var container = control.container;

			container.find( '.bgtfw-inline-af-link' ).each( ( _, afInlineLink ) => {
				var focusType = afInlineLink.dataset.focustype,
					focusId   = afInlineLink.dataset.focusid,
					classList = afInlineLink.classList;

				container.find( afInlineLink ).on( 'click', ( e ) => {
					e.preventDefault();
					if ( classList.contains( 'custom-preset' ) ) {
						let presetContainer   = wp.customize.control( 'bgtfw_header_preset' ).container;
						let customPresetLabel = presetContainer.find( '.bgtfw_header_presetcustom' );

						customPresetLabel.trigger( 'click' );
					} else {
						api[ focusType ]( focusId ).focus();
					}
				} );
			} );

			container.find( '.bgtfw-additional-control' ).each( ( _, autoFocusLi ) => {
				var focusType = autoFocusLi.dataset.focusType,
					focusId   = autoFocusLi.dataset.focusId;
				container.find( autoFocusLi ).find( 'span' ).on( 'click', () => {
					api[ focusType ]( focusId ).focus();
				} );
			} );
		};

		var bindDropdownArrow = ( control ) => {
			var container = control.container;

			container.find( '.bgtfw-dropdown-menu-header' ).on( 'click', function() {
				container.find( '.bgtfw-dropdown-menu-header' ).toggleClass( 'collapsed' );
			} );
		};

		api.control.each( ( control ) => {
			if ( control.params.type && 'bgtfw-dropdown-menu' === control.params.type ) {
				control.priority( -1 );
				bindDropdownArrow( control );
				bindAutoFocus( control );
				api.section( control.section() ).expanded.bind( ( isExpanded ) => {
					if ( ! isExpanded ) {
						control.container.find( '.bgtfw-dropdown-menu-header' ).addClass( 'collapsed' );
					}
				} );
			}
		} );
	} );
};

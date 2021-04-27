/* globals BOLDGRID */
const api = wp.customize;
window.BOLDGRID.CUSTOMIZER.dropdownMenus = window.BOLDGRID.CUSTOMIZER.dropdownMenus ? window.BOLDGRID.CUSTOMIZER.dropdownMenus : [];
export default () => {
	api.bind( 'ready', () => {
		var bindAutoFocus = ( control ) => {
			var container = control.container;

			container.find( '.bgtfw-additional-control' ).each( ( _, autoFocusLi ) => {
				var focusType = autoFocusLi.dataset.focusType,
					focusId   = autoFocusLi.dataset.focusId;
					container.find( autoFocusLi ).find( 'span' ).on( 'click', function() {
						BOLDGRID.CUSTOMIZER.dropdownMenus.push( control.section() );
						api[ focusType ]( focusId ).focus();
					} );

					// container.find( '.bgtfw-dropdown-menu-return' ).on( 'click', function() {
					// 	BOLDGRID.CUSTOMIZER.dropdownMenus.pop();
					// 	api.section( focusId ).focus( this.dataset.section );
					// } );
			} );
		};

		var bindDropdownArrow = ( control ) => {
			var container = control.container;

			container.find( '.dashicons' ).on( 'click', function() {
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

					// } else if ( 0 !== BOLDGRID.CUSTOMIZER.dropdownMenus.length ) {
					// 	let goBackSections = BOLDGRID.CUSTOMIZER.dropdownMenus;
					// 	control.container.find( '.bgtfw-dropdown-menu-return' ).removeClass( 'hidden' );
					// 	control.container.find( '.bgtfw-dropdown-menu-return' ).data( 'section', goBackSections[ goBackSections.length - 1 ] );
					// }
				} );
			}
		} );
	} );
};
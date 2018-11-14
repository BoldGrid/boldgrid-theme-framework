
const api = wp.customize;

export default {
	init() {
		api.bind( 'ready', () => this.ready() );
	},

	ready() {
		this.setupTabs();
		$( '.bgtfw-tab' ).on( 'click', e => this._handleTabClicks( e ) );
		api( 'bgtfw_fixed_header', 'bgtfw_header_layout_position', ( ...args ) => this._bindControls( ...args ) );
	},

	setupTabs() {
		if ( false === api( 'bgtfw_fixed_header' )() || 'header-top' !== api( 'bgtfw_header_layout_position' )() ) {
			$( '.bgtfw-tab[data-tab$="sticky_header_layout"]' ).hide();
			api.control( 'bgtfw_sticky_header_layout' ).deactivate();
		}

		this.hideTab();
	},

	hideTab() {
		document.querySelectorAll( '.bgtfw-tab:not(.selected)' ).forEach( tab => {
			$( tab.dataset.tab ).hide();
		} );
	},

	_handleTabClicks( e ) {
		if ( ! e.currentTarget.classList.contains( 'selected' ) ) {
			document.querySelectorAll( '.bgtfw-tab' ).forEach( item => {
				item.classList.remove( 'selected' );
				$( item.dataset.tab ).hide();
			} );

			e.currentTarget.classList.add( 'selected' );
			$( e.currentTarget.dataset.tab ).show();
		}
	},

	_bindControls( ...args ) {

		// Bind sticky header and header position controls to sticky header controls in dynamic layout.
		args.map( control => {
			control.bind( () => {
				const tab = $( '.bgtfw-tab[data-tab$="sticky_header_layout"]' );

				if ( true === api( 'bgtfw_fixed_header' )() && 'header-top' === api( 'bgtfw_header_layout_position' )() ) {
					tab.show();
					api.control( 'bgtfw_sticky_header_layout' ).activate();
					this.hideTab();
				} else {
					if ( tab.hasClass( 'selected' ) ) {
						$( '.bgtfw-tab:not(.selected)' ).trigger( 'click' );
					}
					tab.hide();
					api.control( 'bgtfw_sticky_header_layout' ).deactivate();
				}
			} );
		} );
	}
};

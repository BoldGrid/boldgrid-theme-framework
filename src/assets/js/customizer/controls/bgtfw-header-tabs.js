
const api = wp.customize;

export default {

	/**
	 * Initialize BGTFW header tab control.
	 *
	 * @since 2.1.0
	 */
	init() {
		api.bind( 'ready', () => this.ready() );
	},

	/**
	 * Tasks to run when customizer has entered ready state.
	 *
	 * @since 2.1.0
	 */
	ready() {
		this.setupTabs();
		$( '.bgtfw-tab' ).on( 'click', e => this._handleTabClicks( e ) );
		api( 'bgtfw_fixed_header', 'bgtfw_header_layout_position', ( ...args ) => this._bindControls( ...args ) );
	},

	/**
	 * Setup up initial state of tabs.
	 *
	 * @since 2.1.0
	 */
	setupTabs() {
		if ( ( _.isFunction( api( 'bgtfw_fixed_header' ) ) && false === api( 'bgtfw_fixed_header' )() ) || 'header-top' !== api( 'bgtfw_header_layout_position' )() ) {
			$( '.bgtfw-tab[data-tab$="sticky_header_layout"]' ).hide();
		}

		this.hideTab();
	},

	/**
	 * Hide non-select tabs.
	 *
	 * @since 2.1.0
	 */
	hideTab() {
		document.querySelectorAll( '.bgtfw-tab:not(.selected)' ).forEach( tab => {
			$( tab.dataset.tab ).hide();
		} );
	},

	/**
	 * Event handler for click on tabs.
	 *
	 * @since 2.1.0
	 *
	 * @param {ClickEvent} e
	 */
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

	/**
	 * Processes to run when bound control settings are changed.
	 *
	 * @since 2.1.0
	 *
	 * @param { wp.customize.Setting }
	 */
	_bindControls( ...args ) {

		// Bind sticky header and header position controls to sticky header controls in dynamic layout.
		args.map( control => {
			control.bind( () => {
				const tab = $( '.bgtfw-tab[data-tab$="sticky_header_layout"]' );

				if ( ( _.isFunction( api( 'bgtfw_fixed_header' ) ) && true === api( 'bgtfw_fixed_header' )() ) && 'header-top' === api( 'bgtfw_header_layout_position' )() ) {
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

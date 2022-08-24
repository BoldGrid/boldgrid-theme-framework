import camelCase from 'lodash.camelcase';
import kebabCase from 'lodash.kebabcase';
import startCase from 'lodash.startcase';
import lowerCase from 'lodash.lowercase';

const api = wp.customize;

export default {

	/**
	 * Handles the control's initialization.
	 *
	 * @since 2.0.3
	 */
	ready() {
		this.uids = [];
		this.setSelectQueue();
		this.setSortable();
		this.addSortables();
		this.addTypes();
		this.addRepeaterControls();
		this.updateTitles();
		this.updateAddTypes();
		this.addEvents();
		this.addUids();
	},

	/**
	 * Sets select queue for connected controls.
	 *
	 * @since 2.0.3
	 */
	setSelectQueue() {
		return this.selectQueue = 0;
	},

	/**
	 * Sets a reference to the sortable selector to the parent sortable to use.
	 *
	 * @since 2.0.3
	 */
	setSortable() {
		return this.sortable = this.container.find( `#sortable-${ this.id }` );
	},

	/**
	 * Initializes sortables.
	 *
	 * @since 2.0.3
	 */
	addSortables() {
		this.addPrimarySortables();
		this.addRepeaterSortables();
	},

	/**
	 * Adds primary container sortables.
	 *
	 * @since 2.0.3
	 */
	addPrimarySortables() {
		this.sortable.accordion( {
			header: '> .sortable-wrapper > .sortable-title',
			heightStyle: 'content',
			collapsible: true
		} ).sortable( {
			update: this.updateValues.bind( this )
		} ).disableSelection();
	},

	/**
	 * Adds repeater container sortables.
	 *
	 * @since 2.0.3
	 */
	addRepeaterSortables() {
		this.container.find( '.connected-sortable' ).accordion( {
			header: '.repeater-handle',
			heightStyle: 'content',
			collapsible: true
		} ).sortable( {
			update: this.updateValues.bind( this )
		} ).disableSelection();
	},

	/**
	 * Adds the additional event handlers used in the sortable control.
	 *
	 * @since 2.0.3
	 */
	addEvents() {
		api.bind( 'ready', () => {
			$( api.OuterSection.prototype.containerParent ).on( 'bgtfw-menu-dropdown-select', _.after( this.getConnectedMenus().length, this.updateConnectedSelects( true ) ) );
			this.sortable
				.on( 'click', '.bgtfw-sortable:not(.disabled)', e => this._addItem( e ) )
				.on( 'click', '.bgtfw-container-control > .bgtfw-sortable-control:not(.selected), .repeater-control.align .direction:not(.selected)', e => this._select( e ) )
				.on( 'click', '.dashicons-trash:not(.disabled)', e => this._deleteItem( e ) )
				.on( 'change', '.repeater-control.menu-select', e => this._updateMenuSelect( e ) )
				.on( 'click', '.repeater-control.align .direction:not(.selected)', e => this._updateAlignment( e ) )
				.on( 'click', '.bgtfw-container-control > .bgtfw-sortable-control:not(.selected)', () => this._updateContainer() )
				.on( 'change', '.repeater-control.attribution .attribution-link', e => this._updateAttribution( e ) )
				.on( 'change', '.display-control', e => this._updateDisplay( e ) );

			$( `#sortable-${ this.id }-add-section` ).on( 'click', ( e ) => this.addSection( e ) );

			// Bind sticky header and header position controls to sticky header controls in dynamic layout.
			api( 'bgtfw_fixed_header', 'bgtfw_header_layout_position', 'custom_logo', ( ...args ) => {
				args.map( ( control ) => {
					control.bind( () => this._toggleSticky() );
				} );
			} );

			api.previewer.bind( 'ready', () => {
				this._toggleSticky();
				this.sortable.on( 'click', '.repeater-control.sticky .bgtfw-sortable-control:not(.selected)', ( e ) => this._updateSelector( e ) );
			} );

			api( 'custom_logo', value => value.bind( to => this._toggleLogo( to ) ) );
			this.disableAttributionTrash();
		} );
	},

	/**
	 * Disables the Trash Icon for Attribution LInks.
	 *
	 * @since 2.9.2
	 */
	disableAttributionTrash() {
		var footerContainer        = api.control( 'bgtfw_footer_layout' ).container,
			footerSortableWrappers = footerContainer.find( '.sortable-wrapper' );

		footerSortableWrappers.find( '.dashicons-trash' ).removeClass( 'disabled' );

		footerSortableWrappers.each( ( _, wrapper ) => {
			var $attributionsLinks   = footerContainer.find( wrapper ).find( '.dashicons-admin-links' ),
				$attributionsHandles = $attributionsLinks.parents( '.sortable-title' ),
				$repeaterHandles     = $( wrapper ).children( '.sortable-title' );
			if ( 0 !== $attributionsLinks.length ) {
				$attributionsHandles.find( '.dashicons-trash' ).addClass( 'disabled' );
				$repeaterHandles.find( '.dashicons-trash' ).addClass( 'disabled' );
			}
		} );
	},

	_toggleLogo( to ) {
		this.container[0].querySelectorAll( '[data-selector=".custom-logo"]' ).forEach( control => {
			control.classList.toggle( 'hidden', ( _.isEmpty( to ) && ! _.isNumber( to ) ) );
		} );
	},

	/**
	 * Adds Unique IDs for each item.
	 *
	 * @since 2.0.3
	 */
	addUids() {
		this.container[0].querySelectorAll( '.repeater.ui-sortable-handle' ).forEach( repeater => {
			if ( _.isEmpty( repeater.dataset ) || _.isUndefined( repeater.dataset.uid ) ) {
				let uid = _.uniqueId( this.params.location.charAt( 0 ).toString() );
				while ( this.uids.includes( uid ) ) {
					uid = _.uniqueId( this.params.location.charAt( 0 ).toString() );
				}
				this.uids.push( uid );
				repeater.dataset.uid = uid;
			}
		} );
	},

	/**
	 * Header items' display event handler.
	 *
	 * @since 2.0.3
	 */
	_updateDisplay( e ) {
		let el = e.currentTarget;

		// Set display state based on :checked.
		el.dataset.display = el.checked ? 'show' : 'hide';

		// Update repeater.
		let repeater = $( el ).closest( '.repeater' )[0],
			data = {
				display: el.dataset.display,
				selector: el.dataset.selector
			},
			displayData = JSON.parse( decodeURIComponent( repeater.dataset.display ) ),
			index = _.findIndex( displayData, { selector: data.selector } );

		displayData[ index ] = _.extend( _.findWhere( displayData, { selector: data.selector } ), data );
		repeater.dataset.display = encodeURIComponent( JSON.stringify( displayData ) );
		this.updateValues();
	},

	/**
	 * Gets dynamic item selectors.
	 *
	 * @since 2.0.3
	 */
	getItemSelector( data ) {
		let selector = data.selector;
		if ( 'menu' === data.key ) {
			selector = this.getMenuSelector( data.type );
		}

		if ( 'sidebar' === data.key ) {
			selector = this.getSidebarSelector( data.type );
		}

		return selector;
	},

	/**
	 * Toggle sticky header control display.
	 *
	 * @since 2.0.3
	 */
	_toggleSticky() {
		if ( 'sticky-header' === this.params.location && _.isFunction( api( 'bgtfw_fixed_header' ) ) ) {
			if ( 'header-top' === api( 'bgtfw_header_layout_position' )() && true === api( 'bgtfw_fixed_header' )() ) {
				api.control( 'bgtfw_fixed_header' ).container.find( '.customize-control-description' ).show();
				this.active.set( true );
			} else {
				api.control( 'bgtfw_fixed_header' ).container.find( '.customize-control-description' ).hide();
				this.active.set( false );
			}
		}
	},

	/**
	 * Add new section to sortable event handler.
	 *
	 * @since 2.0.3
	 */
	addSection( e ) {
		e.preventDefault();
		let instances = this.container.find( '.connected-sortable' );
		instances.sortable( 'destroy' );
		instances.accordion( 'destroy' );
		let items = this.sortable.find( '.sortable-wrapper' );
		let newItem;
		if ( items.length ) {
			newItem = items.last().clone( true );
			newItem.attr( 'id', `sortable-${ items.length }-wrapper` );
			newItem.find( '.connected-sortable' ).attr( 'id', `sortable-${ this.id }-${ items.length }` );
			newItem.find( '.connected-sortable li' ).remove();
			newItem.appendTo( this.sortable );
		} else {
			newItem = `
			<div id="sortable-0-wrapper" class="sortable-wrapper ui-sortable-handle">
				<span class="sortable-title ui-accordion-header ui-state-default ui-accordion-header-active ui-state-active ui-corner-top ui-accordion-icons" role="tab" id="ui-id-17" aria-controls="ui-id-18" aria-selected="true" aria-expanded="true" tabindex="0"><span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-s"></span><span class="title"><em>Empty Section</em></span><span class="dashicons dashicons-trash"></span></span>
				<div class="sortable-accordion-content ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom ui-accordion-content-active" id="ui-id-18" aria-labelledby="ui-id-17" role="tabpanel" aria-hidden="false" style="display: block;">
					<div class="sortable-section-controls">
						<div class="bgtfw-container-control">
							<div class="bgtfw-sortable-control container selected" data-container="container">
								<span class="bgtfw-icon icon-layout-container"></span>
								<span>Container</span>
							</div>
							<div class="bgtfw-sortable-control full-screen" data-container="full-width">
								<span class="bgtfw-icon icon-layout-full-screen"></span>
								<span>Full Width</span>
							</div>
						</div>
					</div>
					<ul id="sortable-${ this.id }-0" class="connected-sortable ui-accordion ui-widget ui-helper-reset ui-sortable" role="tablist">
					</ul>
					<div class="sortable-actions"></div>
				</div>
			</div>`;
			document.getElementById( `sortable-${ this.id }` ).innerHTML = newItem;
			this.addTypes();
		}

		this.addRepeaterSortables();
		this.refreshItems();
		this.updateValues();
	},

	/**
	 * Repeater's delete item event handler.
	 *
	 * @since 2.0.3
	 */
	_deleteItem( e ) {
		e.preventDefault();
		let handle = $( e.currentTarget ).closest( '.ui-sortable-handle' ),
			flagUpdate = 0;

		// Main sortable is deleted.
		if ( _.isEmpty( handle[0].dataset ) ) {
			handle[0].querySelectorAll( '.repeater.ui-sortable-handle' ).forEach( repeater => {
				if ( 'menu' === repeater.dataset.key ) {
					flagUpdate++;
				}
			} );
		} else {
			if ( 'menu' === handle[0].dataset.key ) {
				flagUpdate++;
			}
		}

		handle.remove();
		this.updateValues();
		if ( 0 !== flagUpdate ) {
			this.updateConnectedSelects();
		}
	},

	/**
	 * Menu item type's dropdown event handler.
	 *
	 * @since 2.0.3
	 */
	_updateMenuSelect( e ) {
		let el = e.currentTarget,
			repeater = $( el ).closest( '.repeater' )[0];

		// Update repeater's dataset.
		repeater.dataset.type = el.value;
		this.updateValues();

		// Update the menu select controls' disabled items.
		this.updateConnectedSelects();
	},

	/**
	 * Update menu select controls.
	 *
	 * @since 2.0.3
	 */
	updateConnectedSelects( queued ) {
		let unqueued = _.isUndefined( queued ),
			containers = [],
			instance,
			menus;

		if ( ! unqueued ) {
			this.getLastConnected().selectQueue += 1;
		}

		if ( unqueued || this.getLastConnected().selectQueue >= this.getConnectedControls().length ) {
			menus = this.getConnectedMenus();
			_.each( this.getConnectedControls(), control => {
				instance = this.id === control.id ? this : api.control( control.id );
				if ( ! _.isUndefined( instance ) ) {
					containers.push( instance.selector );
				}
			} );

			containers = containers.map( container => `${ api.OuterSection.prototype.containerParent } ${ container } .repeater-control.menu-select option` ).join( ', ' );
			document.querySelectorAll( containers ).forEach( option => {
				let repeater = $( option ).closest( '.repeater' )[0].dataset.type;
				option.disabled = menus.includes( option.value ) && repeater !== option.value ? true : false;
			} );

			this.selectQueue = 0;
		}
	},

	/**
	 * Alignment controls' event handler.
	 *
	 * @since 2.0.3
	 */
	_updateAlignment( e ) {
		let el = e.currentTarget,
			repeater = $( el ).closest( '.repeater' )[0],
			newVal = el.className.replace( /direction|selected|\s/g, '' );

		repeater.dataset.align = newVal;
		this.updateValues();
	},

	/**
	 * Container controls' event handler.
	 *
	 * @since 2.0.3
	 */
	_updateContainer() {
		this.updateValues();
	},

	/**
	 * Check if string is JSON encoded.
	 *
	 * @since 2.0.3
	 */
	isJSON( str ) {
		try {
			let obj = JSON.parse( decodeURIComponent( str ) );
			if ( obj && _.isObject( obj ) ) {
				return true;
			}
		} catch ( err ) {
			return false;
		}
		return false;
	},

	/**
	 * Adds any of the needed controls for the repeaters.
	 *
	 * @since 2.0.3
	 */
	addRepeaterControls( cancelled = false ) {
		let repeaters = this.sortable.find( '.repeater' ),
			addedSelect = _.after( this.getUsedMenuActions().length, () => $( api.OuterSection.prototype.containerParent ).trigger( 'bgtfw-menu-dropdown-select' ) );

		_.each( repeaters, repeater => {
			let repeaterControls = repeater.querySelector( '.repeater-accordion-content' ),

				controls = this.params.items[ repeater.dataset.key ].controls || {},
				setting;

			_.each( controls, ( control, key ) => {
				if ( ! repeater.querySelector( `.repeater-control.${ kebabCase( key ) }` ) ) {
					setting = repeater.dataset[ key ] || control.default || repeater.dataset.type;
					setting = this.isJSON( setting ) ? JSON.parse( decodeURIComponent( setting ) ) : setting;
					repeaterControls.innerHTML += this[ camelCase( `Get ${ key } Markup` ) ]( setting );
					if ( 'menu-select' === key ) {
						cancelled ? this.updateConnectedSelects() : addedSelect;
					}
				}
			} );
		} );
	},

	/**
	 * Get sticky header display control.
	 *
	 * @since 2.0.3
	 */
	getDisplayMarkup( setting ) {
		let markup = `
		<div class="repeater-control display">
			<div class="repeater-control-title">Display</div>
			<div class="repeater-control-nested">
				<div class="control-wrapper">
					<ul>`;

				_.each( setting, control => {

					// Create unique IDs ex: 'sticky-header-title-1'.
					let classes = 'display-control',
						id = _.uniqueId( `${ this.params.location }-${ lowerCase( control.title ) }-` ),
						checked = 'hide' === control.display ? '' : 'checked',
						logo = api( 'custom_logo' )();
					if ( '.custom-logo' === control.selector && ( _.isEmpty( logo ) && ! _.isNumber( logo ) ) ) {
						classes += ' hidden';
					}

					markup += `
						<li>
							<input id="${ id }" class="${ classes }" type="checkbox" data-display="${ control.display }" data-selector="${ control.selector }" ${ checked }>
							<label for="${ id }">${ control.title }</label>
						</li>`;
				} );
		markup += `</ul>
				</div>
			</div>
		</div>`;

		return markup;
	},

	/**
	 * Attribution controls' event handler.
	 *
	 * @since 2.0.3
	 */
	_updateAttribution( e ) {
		api( e.currentTarget.dataset.attribution ).set( e.currentTarget.checked );
	},

	/**
	 * Gets the attribution control's markup.
	 *
	 * @since 2.0.3
	 */
	getAttributionMarkup() {
		let markup = '<div class="repeater-control attribution"><ul>';

		_.each( this.getAttributionSettings(), setting => {
			let id = _.uniqueId( `${ setting.id }_` ),
				checked = api( setting.id )() ? 'checked' : '';

			markup += `<li>
				<input id="${ id }" class="attribution-link" type="checkbox" data-attribution="${ setting.id }" ${ checked }>
				<label for="${ id }">${ startCase( setting.id ).replace( 'Wordpress', 'WordPress' ).replace( 'Boldgrid', 'BoldGrid' ) }</label>
			</li>`;
		} );

		markup += '</ul></div>';

		return markup;
	},

	/**
	 * Gets the alignment control's markup.
	 *
	 * @since 2.0.3
	 */
	getAlignMarkup( align ) {
		let markup = `
		<div class="repeater-control align">
			<div class="align-wrapper">
				<div class="repeater-control-title">Alignment</div>
				<div class="direction nw">
					<span class="dashicons dashicons-arrow-up"></span>
				</div>
				<div class="direction n">
					<span class="dashicons dashicons-arrow-up"></span>
				</div>
				<div class="direction ne">
					<span class="dashicons dashicons-arrow-up"></span>
				</div>
				<div class="direction w">
					<span class="dashicons dashicons-arrow-left"></span>
				</div>
				<div class="direction c">
					<span class="dashicons dashicons-marker"></span>
				</div>
				<div class="direction e">
					<span class="dashicons dashicons-arrow-right"></span>
				</div>
				<div class="direction sw">
					<span class="dashicons dashicons-arrow-down"></span>
				</div>
				<div class="direction s">
					<span class="dashicons dashicons-arrow-down"></span>
				</div>
				<div class="direction se">
					<span class="dashicons dashicons-arrow-down"></span>
				</div>
			</div>
		</div>`;

		markup = markup.replace( `"direction ${ align }"`, `"direction ${ align } selected"` );

		return markup;
	},

	/**
	 * Gets the sidebar edit button markup.
	 *
	 * @since 2.0.3
	 */
	getSidebarEditMarkup( type ) {
		let id = type.replace( 'bgtfw_sidebar_', '' );
		return `<div class="repeater-control sidebar-edit">
			<p class="repeater-control-description">Edit sidebar widgets and appearance in "Widgets" section.</p>
			<a class="button-primary" href="#" onclick="event.preventDefault(); wp.customize.section( 'sidebar-widgets-${ id }' ).focus();"><i class="dashicons dashicons-edit"></i>Edit Sidebar</a>
		</div>`;
	},

	/**
	 * Gets the menu item type's dropdown menu.
	 *
	 * @since 2.0.3
	 */
	getMenuSelectMarkup( type ) {
		let attr,
			markup = '<select class="repeater-control menu-select">';

		_.each( window._wpCustomizeNavMenusSettings.locationSlugMappedToName, ( name, location ) => {
			if ( 'sticky-header' !== this.params.location && location.includes( 'sticky' ) ) {
				return;
			}
			if ( 'sticky-header' === this.params.location && ! location.includes( 'sticky' ) ) {
				return;
			}
			attr = `boldgrid_menu_${ location }` === type ? 'selected' : '';
			markup += `<option value="boldgrid_menu_${ location }"${ attr }>${ name }</option>`;
		} );

		markup += '</select>';

		return markup;
	},

	/**
	 * Container control event handler.
	 *
	 * @since 2.0.3
	 */
	updateValues() {
		let values = this.getValues();
		this.setting.set( values );
		this.updateTitles();
		this.updateAddTypes();
		this.disableAttributionTrash();
	},

	/**
	 * Retrieves user's currently set values for control.
	 *
	 * @since 2.0.3
	 */
	getValues() {
		let values = [];

		_.each( this.container.find( '.connected-sortable' ), ( sortable, key ) => {
			let	sortableValue = $( sortable ).find( '.repeater' ).map( ( key, el ) => {
				return Object.assign( {}, _.mapObject( el.dataset, data => this.isJSON( data ) ? JSON.parse( decodeURIComponent( data ) ) : data ) );
			} ).toArray();

			values[ key ] = {
				container: this.getContainer( sortable ),
				items: sortableValue
			};
		} );

		return values;
	},

	/**
	 * Gets the selected value for the container control.
	 *
	 * @since 2.0.3
	 */
	getContainer( sortable ) {
		return sortable.previousElementSibling.querySelector( '.selected' ).dataset.container;
	},

	/**
	 * Select event handler for controls.
	 *
	 * @since 2.0.3
	 */
	_select( e ) {
		let selector = $( e.currentTarget );
		selector.siblings().removeClass( 'selected' );
		selector.addClass( 'selected' );
	},

	/**
	 * Add Item event handler.
	 *
	 * @since 2.0.3
	 */
	_addItem( e ) {
		let el = e.currentTarget,
			dataset = el.dataset,
			container = el.parentElement.previousElementSibling;

		container.innerHTML += this.getMarkup( dataset );

		// Apply control defaults to repeater for newly added items.
		_.each( this.params.items[ el.dataset.key ].controls, ( control, key ) => {
			if ( ! _.isUndefined( control.default ) ) {
				if ( ! _.isString( control.default ) ) {
					control.default = encodeURIComponent( JSON.stringify( control.default ) );
				}
				container.lastChild.dataset[ key ] = control.default;
			}
		} );

		// Add uid for new items.
		if ( _.isUndefined( container.lastChild.dataset.uid ) ) {
			container.lastChild.dataset.uid = _.uniqueId( this.params.location.charAt( 0 ).toString() );
		}

		this.addRepeaterControls( true );
		this.refreshItems();
		this.updateValues();
	},

	/**
	 * Updates section titles with titles of repeaters contained inside.
	 *
	 * @since 2.0.3
	 */
	updateTitles() {
		_.each( this.sortable.sortable( 'instance' ).items, ( sortable ) => {
			let el = sortable.item[0],
				titleContainer = el.querySelector( '.title' ),
				title = [];

			el.querySelectorAll( '.repeater-title' ).forEach( titles => title.push( titles.textContent ) );
			title = title.join ( '<span class="title-divider">&#9679;</span>' );
			if ( _.isEmpty( title ) ) {
				titleContainer.classList.add( 'title-empty' );
				title = '<em>Empty Section</em>';
			}
			titleContainer.classList.remove( 'title-empty' );
			titleContainer.innerHTML = title;
		} );
	},

	/**
	 * Refresh container active ui elements.
	 *
	 * @since 2.0.3
	 *
	 * @param {Array} types Type of ui elements to refresh.
	 */
	refreshItems( types = [ 'sortable', 'accordion' ] ) {
		_.each( types, type => {
			this.sortable[ type ]( 'refresh' );
			this.container.find( '.connected-sortable' )[ type ]( 'refresh' );
		} );
	},

	/**
	 * Refresh sortables.
	 *
	 * @since 2.0.3
	 */
	refreshSortables() {
		this.sortable.sortable( 'refresh' );
		this.container.find( '.connected-sortable' ).sortable( 'refresh' );
	},

	/**
	 * Refresh accordions.
	 *
	 * @since 2.0.3
	 */
	refreshAccordions() {
		this.sortable.accordion( 'refresh' );
		this.container.find( '.connected-sortable' ).accordion( 'refresh' );
	},

	/**
	 * Get repeater markup.
	 *
	 * @since 2.0.3
	 */
	getMarkup( dataset ) {
		let attributes = Object.entries( dataset ).map( data => {
			if ( ! _.isString( data[1] ) ) {
				data[1] = encodeURIComponent( JSON.stringify( data[1] ) );
			}

			return `data-${ data[0] }="${ data[1] }"`;
		} ).join( ' ' );
		return `<li class="repeater" ${ attributes }>
			<div class="repeater-input">
				<div class="repeater-handle">
					<div class="sortable-title">
						<span class="repeater-title"><i class="${ this.params.items[ dataset.key ].icon }"></i>${ this.params.items[ dataset.key ].title }</span><span class="dashicons dashicons-trash"></span>
					</div>
				</div>
				<div class="repeater-accordion-content-wrapper">
					<div class="repeater-accordion-content"></div>
				</div>
			</div>
		</li>`;
	},

	/**
	 * Update add item buttons.
	 *
	 * @since 2.0.3
	 */
	updateAddTypes() {
		this.sortable[0].querySelectorAll( '.bgtfw-sortable' ).forEach( button => {
			let type = this.getItem( button.dataset.key );
			if ( type ) {
				button.classList.remove( 'disabled' );
				button.dataset.type = this.getItem( button.dataset.key );
			} else {
				button.classList.add( 'disabled' );
			}
		} );
	},

	/**
	 * Handles adding the Add Item buttons to sortables.
	 *
	 * @since 2.0.3
	 */
	addTypes() {
		this.container[0].querySelectorAll( '.sortable-actions' ).forEach( sortableAction => {
			_.each( this.params.items, ( item, key ) => {
				sortableAction.innerHTML += this.getAddTypeMarkup( item, key );
			} );
		} );
	},

	/**
	 * Gets menu item selector for toggling display.
	 *
	 * @since 2.0.3
	 */
	getMenuSelector( action ) {
		return '#' + action.replace( 'boldgrid_menu_', '' );
	},

	/**
	 * Gets sidebar item selector for toggling display.
	 *
	 * @since 2.0.3
	 */
	getSidebarSelector( action ) {
		return '#' + action.replace( 'bgtfw_sidebar_', '' );
	},

	/**
	 * Gets markup for add item buttons.
	 *
	 * @since 2.0.3
	 */
	getAddTypeMarkup( item, key ) {
		return `<span class="bgtfw-sortable ${ key }" data-key="${ key }" aria-label="Add ${ item.title }"><i class="fa fa-plus"></i>${ item.title }</span>`;
	},

	/**
	 * Gets all current items added to location.
	 *
	 * @since 2.0.3
	 */
	getCurrentItems() {
		return _.map( _.flatten( _.map( this.setting.get(), data => _.values( data.items ) ) ), values => values.type );
	},

	/**
	 * Gets all registered sidebar locations.
	 *
	 * @since 2.0.3
	 */
	getAllSidebarLocations() {
		return _.pluck( window._wpCustomizeWidgetsSettings.registeredSidebars, 'id' );
	},

	/**
	 * Gets all sidebar action names.
	 *
	 * @since 2.0.3
	 */
	getAllSidebarActions() {
		return this.getAllSidebarLocations().map( item => `bgtfw_sidebar_${ item }` );
	},

	/**
	 * Gets all sidebar action names filtered by location.
	 *
	 * @since 2.0.3
	 */
	getFilteredSidebarActions() {
		return _.filter( this.getAllSidebarActions(), sidebar => sidebar.includes( this.params.location ) );
	},

	/**
	 * Gets all currently used sidebar actions.
	 *
	 * @since 2.0.3
	 */
	getUsedSidebarActions() {
		return _.filter( this.getCurrentItems(), actions => actions.includes( 'bgtfw_sidebar' ) );
	},

	/**
	 * Gets all unused sidebar actions filtered by location.
	 *
	 * @since 2.0.3
	 */
	getAvailableSidebars() {
		return _.difference( this.getFilteredSidebarActions(), this.getUsedSidebarActions() );
	},

	/**
	 * Gets the next available sidebar item that can be added.
	 *
	 * @since 2.0.3
	 */
	getNextAvailableSidebar() {
		return _.first( this.getAvailableSidebars() );
	},

	/**
	 * Gets the next available attribution item that can be added.
	 *
	 * Note: Using previous settings for attribution control for
	 * backwards compat w/ inspirations plugin.  Currently this will
	 * only allow for one attribution control to be added.
	 *
	 * @since 2.0.3
	 */
	getNextAvailableAttribution() {
		return this.getCurrentItems().includes( 'boldgrid_display_attribution_links' ) ? null : 'boldgrid_display_attribution_links';
	},

	/**
	 * Gets all menu action names that can be added.
	 *
	 * @since 2.0.3
	 */
	getAllMenuActions() {
		return Object.keys( window._wpCustomizeNavMenusSettings.locationSlugMappedToName ).map( item => `boldgrid_menu_${item}` );
	},

	/**
	 * Gets all used menu actions.
	 *
	 * @since 2.0.3
	 */
	getUsedMenuActions() {
		return _.filter( this.getCurrentItems(), actions => actions.includes( 'boldgrid_menu' ) );
	},

	/**
	 * Gets all unused menu actions.
	 *
	 * @since 2.0.3
	 */
	getAvailableMenus() {
		var allMenus       = this.getAllMenuActions(),
			connectedMenus = this.getConnectedMenus(),
			availableMenus = _.difference( allMenus, connectedMenus );

		return availableMenus;
	},

	/**
	 * Gets the next available menu item that can be added.
	 *
	 * @since 2.0.3
	 */
	getNextAvailableMenu() {
		return _.first( this.getAvailableMenus() );
	},

	/**
	 * Get item to be added to sortable based on type.
	 *
	 * @since 2.0.3
	 */
	getItem( type ) {
		switch ( type ) {
			case 'menu':
				type = this.getNextAvailableMenu();
				break;
			case 'sidebar':
				type = this.getNextAvailableSidebar();
				break;
			case 'branding':
				type = 'boldgrid_site_identity';
				break;
			case 'attribution':
				type = this.getNextAvailableAttribution();
				break;
			default:
				break;
		}

		return type;
	},

	/**
	 * Get registered attribution link settings.
	 *
	 * @since 2.0.3
	 */
	getAttributionSettings() {
		return _.filter( _.mapObject( window._wpCustomizeSettings.settings, ( setting, key ) => _.extend( setting, { id: key } ) ), setting => setting.id.includes( 'hide_' ) && setting.id.includes( '_attribution' ) );
	},

	/**
	 * Get connected control instances.
	 *
	 * @since 2.0.3
	 */
	getConnectedControls() {
		var controls = window._wpCustomizeSettings.controls;

		/*
		 * The bgtfw_sticky_header_layout and bgtfw_header_layout control values
		 * are used when using the preset layouts, but the '*_header_layout_advanced'
		 * is used when using a custom layout. Therefore, when using a custom layout,
		 * we need to omit these controls from the list of connected controls or else
		 * the menus will not work properly.
		*/

		controls = _.filter( controls, ( control ) => {
			if ( 'bgtfw_sticky_header_layout' === control.id ) {
				return false;
			}
			if ( 'bgtfw_header_layout' === control.id ) {
				return false;
			}

			return true;
		} );

		return _.filter( controls, { type: this.params.type } );
	},

	/**
	 * Gets all connected items based on control type.
	 *
	 * @since 2.0.3
	 */
	getConnectedItems() {
		return _.flatten( [].concat( _.map( this.getConnectedControls(), control => this.id === control.id ? this.getCurrentItems() : _.isUndefined( api.control( control.id ) ) ? [] : api.control( control.id ).getCurrentItems() ) ) );
	},

	/**
	 * Gets all connected menus used in controls.
	 *
	 * @since 2.0.3
	 */
	getConnectedMenus() {
		let menus = _.filter( this.getConnectedItems(), item => item.includes( 'boldgrid_menu' ) );

		if ( _.isFunction( api( 'bgtfw_fixed_header' ) ) ) {
			if ( false === api( 'bgtfw_fixed_header' )() || 'header-top' !== api( 'bgtfw_header_layout_position' )() ) {
				menus = menus.filter( menu => ! menu.includes( 'sticky' ) );
			}
		}

		return menus;
	},

	/**
	 * Retrieves the last found connected control or this control if none used.
	 *
	 * @since 2.0.3
	 */
	getLastConnected() {
		let last = _.last( this.getConnectedControls() ),
			id = last.id;
		return _.isUndefined( api.control( id ) ) ? this : api.control( id );
	}
};

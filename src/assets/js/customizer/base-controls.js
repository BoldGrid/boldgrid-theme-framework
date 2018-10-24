/* eslint-disable */
import WidgetSectionUpdate from './widget/section-update';
import BlogPagePanelExpand from './design/blog/blog-page/panel-expand.js';
import BlogPostsPanelExpand from './design/blog/posts/panel-expand.js';
import HomepageSectionExpand from './design/homepage/section-expand.js';
import { Control as GenericControls } from './generic/control.js';
import { Required } from './required.js';
import SectionExtendTitle from './menus/extend-title';
import HamburgerControlToggle from './menus/hamburger-control-toggle';
import HoverBackgroundToggle from './menus/hover-background-toggle';
import { Locations as MenuLocations } from './menus/locations';
import { Devices } from './devices';

let devices = new Devices();
devices.init();

( function( $ ) {
	var api, _panelEmbed, _panelIsContextuallyActive, _panelAttachEvents, _sectionEmbed, _sectionIsContextuallyActive, _sectionAttachEvents;

	api = wp.customize;
	new Required().init();
	new WidgetSectionUpdate().init();
	new BlogPagePanelExpand();
	new BlogPostsPanelExpand();
	new HomepageSectionExpand();
	new SectionExtendTitle();
	new GenericControls().init();
	new HamburgerControlToggle();
	new HoverBackgroundToggle();
	new MenuLocations();

	api.bind( 'pane-contents-reflowed', function() {
		var sections, panels;

		// Reflow sections.
		sections = [];

		api.section.each( function( section ) {
			if ( 'bgtfw_section' !== section.params.type || 'undefined' === typeof section.params.section ) {
				return;
			}

			sections.push( section );
		} );

		sections.sort( api.utils.prioritySort ).reverse();

		$.each( sections, function( i, section ) {
			var parentContainer = $( '#sub-accordion-section-' + section.params.section );
			parentContainer.children( '.section-meta' ).after( section.headContainer );
		} );

		// Reflow panels.
		panels = [];

		api.panel.each( function( panel ) {
			if ( 'bgtfw_panel' !== panel.params.type || 'undefined' === typeof panel.params.panel ) {
				return;
			}

			panels.push( panel );
		} );

		panels.sort( api.utils.prioritySort ).reverse();

		$.each( panels, function( i, panel ) {
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
				if ( wp.customize.panel( baseId ) ) {
					wp.customize.panel( baseId ).collapse();
				}
			} else if ( $( '.control-section-bgtfw_section.open' ).length ) {
				baseId = $( '.control-section-bgtfw_section.open' );
				baseId = baseId.attr( 'id' ).replace( 'sub-accordion-section-', '' );
				if ( wp.customize.section( baseId ) ) {
					wp.customize.section( baseId ).collapse();
				}
			}
		} );
	} );


	// Extend Panel.
	_panelEmbed = wp.customize.Panel.prototype.embed;
	_panelIsContextuallyActive = wp.customize.Panel.prototype.isContextuallyActive;
	_panelAttachEvents = wp.customize.Panel.prototype.attachEvents;

	wp.customize.Panel = wp.customize.Panel.extend( {
		attachEvents: function() {
			var panel;

			if ( 'bgtfw_panel' !== this.params.type || 'undefined' === typeof this.params.panel ) {
				_panelAttachEvents.call( this );
				return;
			}

			_panelAttachEvents.call( this );

			panel = this;

			panel.expanded.bind( function( expanded ) {
				var parent = api.panel( panel.params.panel );
				expanded ? parent.contentContainer.addClass( 'current-panel-parent' ) : parent.contentContainer.removeClass( 'current-panel-parent' );
			} );

			panel.container.find( '.customize-panel-back' )
				.off( 'click keydown' )
				.on( 'click keydown', function( event ) {
					if ( api.utils.isKeydownButNotEnterEvent( event ) ) {
						return;
					}

					// Keep this AFTER the key filter above
					event.preventDefault();

					if ( panel.expanded() ) {
						api.panel( panel.params.panel ).expand();
					}
				} );
		},
		embed: function() {
			var panel, parentContainer;

			if ( 'bgtfw_panel' !== this.params.type || 'undefined' === typeof this.params.panel ) {
				_panelEmbed.call( this );

				return;
			}

			_panelEmbed.call( this );

			panel = this;
			parentContainer = $( '#sub-accordion-panel-' + this.params.panel );

			parentContainer.append( panel.headContainer );
		},
		isContextuallyActive: function() {
			var panel, children, activeCount;

			if ( 'bgtfw_panel' !== this.params.type ) {
				return _panelIsContextuallyActive.call( this );
			}

			panel = this;
			children = this._children( 'panel', 'section' );

			api.panel.each( function( child ) {
				if ( ! child.params.panel || ( child.params.panel !== panel.id ) ) {
					return;
				}

				children.push( child );
			} );

			children.sort( api.utils.prioritySort );

			activeCount = 0;

			_( children ).each( function( child ) {
				if ( child.active() && child.isContextuallyActive() ) {
					activeCount += 1;
				}
			} );

			return ( 0 !== activeCount );
		},

		/**
		 * Collapse all child sections.
		 *
		 * @since 2.0.0
		 */
		collapseChildren: function() {
			var children = this._children( 'panel', 'section' );

			_( children ).each( function( child ) {
				if ( child.expanded() ) {
					child.collapse();
				}
			} );
		},

		/**
		 * Wrapper function for the focus() method.
		 *
		 * Because of nested panels, the focus() method does not always work. If you're in a nested
		 * section, it won't focus on the parent panel correctly.
		 *
		 * @since 2.0.0
		 */
		bgtfwFocus: function() {
			var panel = this;

			if ( panel.expanded() ) {
				panel.collapseChildren();
			} else {
				panel.focus();
			}
		}
	} );

	/**
	 * Override the render font selector method to display font images.
	 */
	wp.customize.controlConstructor['kirki-typography'] = wp.customize.controlConstructor['kirki-typography'].extend( {
		renderFontSelector: function() {

			// Call parent method.
			wp.customize.controlConstructor['kirki-typography']
				.__super__
				.renderFontSelector
				.apply( this, arguments );

			// Selecting the instance will add the needed attributes, don't ask why.
			$( this.selector + ' .font-family select' ).selectWoo();
		}
	} );

	wp.customize.controlConstructor['bgtfw-menu-hamburgers'] = wp.customize.Control.extend( {
		ready: function() {
			var control = this;

			control.container.find( '.bgtfw-hamburger-col, .tray, input' ).on( 'click touchend', function( e ) {
				var col = $( e.target ).find( '.bgtfw-hamburger-col' );
				if ( ! col.length ) {
					col = $( e.target ).closest( '.bgtfw-hamburger-col' );
				}
				control.container.find( '.bgtfw-hamburger-col' ).removeClass( 'hamburger-selected' );
				col.addClass( 'hamburger-selected' );
			} );

			control.container.find( '.bgtfw-hamburger-col' ).on( 'mouseover', function( e ) {
				$( e.target ).find( '.hamburger' ).addClass( 'is-active' );
			} );
			control.container.find( '.bgtfw-hamburger-col' ).on( 'mouseout', function( e ) {
				$( e.target ).find( '.hamburger' ).removeClass( 'is-active' );
			} );
		}
	} );

	wp.customize.controlConstructor['bgtfw-sortable-accordion'] = wp.customize.Control.extend( {
		ready: function() {
			var control = this;

			control.container[0].querySelectorAll( '.sortable-actions' ).forEach( sortableAction => {
				_.each( control.params.items, item => sortableAction.innerHTML += control.addTypes.call( this, item ) );
			} );

			// Make our Repeater fields sortable
			control.container.find( '#sortable-' + control.id ).accordion( {
				header: '> .sortable-wrapper > .sortable-title',
				heightStyle: 'content',
				collapsible: true
			} ).sortable( {
				update: this.updateValues.bind( this )
			} ).disableSelection();
			control.container.find( '.connected-sortable' ).sortable( {
				update: this.updateValues.bind( this )
			} ).disableSelection();

			this.addRepeaterControls();
			this.updateTitles();
			control.addEvents.call( this );
		},

		addEvents: function() {
			this.container.find( '.bgtfw-sortable' ).on( 'click', ( e ) => this.addItem( e ) );
			this.container.find( '.bgtfw-container-control > .bgtfw-sortable-control' ).on( 'click', ( e ) => this.selectContainer( e ) );
			this.container.find( '.dashicons-trash' ).on( 'click', ( e ) => this.deleteItem( e ) );
		},

		deleteItem: function( e ) {
			$( e.currentTarget ).closest( '.repeater' ).remove();
			this.updateValues();
		},

		updateMenuSelect: function( e ) {
			let oldVal = e.currentTarget.dataset.value,
				newVal = e.target.value;

			// Update disabled selects.
			document.querySelectorAll( `${ this.selector } option[value=${oldVal}]` ).forEach( ( option ) => option.disabled = false );
			document.querySelectorAll( `${ this.selector } option[value=${newVal}]` ).forEach( ( option ) => option.disabled = true );
			e.target[ e.target.options.selectedIndex ].disabled = false;
			e.currentTarget.dataset.value = newVal;
			this.updateValues();
		},

		addRepeaterControls: function() {
			let repeaters = $( '.repeater' );

			_.each( repeaters, ( repeater ) => {
				let selectControl,
					type = repeater.dataset.value;

				// Menu Items.
				if ( -1 !== type.indexOf( 'menu' ) ) {

					if ( ! repeater.querySelector( '.repeater-menu-select' ) ) {
						repeater.querySelector( '.repeater-accordion-content' ).innerHTML += this.getMenuSelect( type );
						selectControl = repeater.querySelector( '.repeater-menu-select' );

						$( repeater ).on( 'change', e => this.updateMenuSelect( e ) );

						if ( 'menu' !== type ) {
							selectControl.value = type;
						} else {

							// Newly added menu item.
							selectControl.selectedIndex = 0;
							repeater.dataset.value = selectControl.value;
						}
					}
				}
			} );
		},

		getMenuSelect: function( type ) {
			let disabled,
				markup = '<select class="repeater-menu-select">',
				currentItems = _.map( _.flatten( _.map( this.setting.get(), data => _.values( data.items ) ) ), values => values.type );

			_.each( window._wpCustomizeNavMenusSettings.locationSlugMappedToName, ( name, location ) => {
				disabled = -1 !== currentItems.indexOf( `boldgrid_menu_${location}` ) && `boldgrid_menu_${location}` !== type ? ' disabled' : '';
				markup += `<option value="boldgrid_menu_${location}"${disabled}>${name}</option>`;
			} );

			markup += '</select>';

			return markup;
		},

		updateValues: function() {
			let	sortableValue,
			values = [];
			_.each( this.container.find( '.connected-sortable' ), ( sortable, key ) => {
				sortableValue = $( sortable ).find( '.repeater' ).map( function() {
					return { type: this.dataset.value };
				} ).toArray();
				values[ key ] = {
					container: this.getContainer( sortable ),
					items: sortableValue
				};
			} );

			wp.customize( this.id ).set( values );
			this.updateTitles();
		},

		getContainer: function( sortable ) {
			return sortable.previousElementSibling.querySelector( '.selected' ).dataset.container;
		},

		selectContainer: function( e ) {
			let selector = $( e.currentTarget );

			if ( ! selector.hasClass( 'selected' ) ) {
				selector.siblings().removeClass( 'selected' );
				selector.addClass( 'selected' );
				this.updateValues();
			}
		},

		addItem: function( e ) {
			let type = e.currentTarget.dataset.type;
			e.currentTarget.parentElement.previousElementSibling.innerHTML += this.getMarkup( type );
			this.addRepeaterControls();
			this.refreshSortables();
			this.updateValues();
		},

		updateTitles: function() {
			_.each( $( '.sortable-wrapper' ), ( sortable ) => {
				var title = [];
				sortable.querySelectorAll( '.repeater-title' ).forEach( titles => title.push( titles.textContent ) );
				sortable.firstElementChild.innerHTML = title.join ( '<span class="title-divider">&#9679;</span>' );
			} );
		},

		refreshSortables: function() {
			this.container.find( '#sortable-' + this.id ).sortable( 'refresh' );
			this.container.find( '.connected-sortable' ).sortable( 'refresh' );
		},

		getMarkup: function( type ) {
			let key = -1 !== type.indexOf( 'menu' ) ? 'menu' : type;
			return `<li class="repeater" data-value="${ type }">
				<div class="repeater-input">
					<div class="repeater-handle">
						<span class="repeater-title"><i class="${ this.params.items[ key ].icon }"></i>${ this.params.items[ key ].title }</span><span class="dashicons dashicons-trash"></span>
					</div>
					<div class="repeater-accordion-content"></div>
				</div>
			</li>`;
		},

		addTypes: function( item ) {
			let attribute = item.title.toLowerCase(),
				type = attribute === 'branding' ? 'boldgrid_site_identity' : attribute;

			return '<span class="bgtfw-sortable ' + attribute + '" data-type="' +  type + '" aria-label="Add ' + item.title + '"><i class="fa fa-plus"></i>' + item.title + '</span>';
		}
	} );

	// Extend Section.
	_sectionEmbed = wp.customize.Section.prototype.embed;
	_sectionIsContextuallyActive = wp.customize.Section.prototype.isContextuallyActive;
	_sectionAttachEvents = wp.customize.Section.prototype.attachEvents;

	wp.customize.Section = wp.customize.Section.extend( {
		attachEvents: function() {
			var section;

			if ( 'bgtfw_section' !== this.params.type || 'undefined' === typeof this.params.section ) {
				_sectionAttachEvents.call( this );
				return;
			}

			_sectionAttachEvents.call( this );

			section = this;

			section.expanded.bind( function( expanded ) {
				var parent = api.section( section.params.section );

				expanded ? parent.contentContainer.addClass( 'current-section-parent' ) : parent.contentContainer.removeClass( 'current-section-parent' );
			} );

			section.container.find( '.customize-section-back' )
				.off( 'click keydown' )
				.on( 'click keydown', function( event ) {
					if ( api.utils.isKeydownButNotEnterEvent( event ) ) {
						return;
					}

					event.preventDefault(); // Keep this AFTER the key filter above

					if ( section.expanded() ) {
						api.section( section.params.section ).expand();
					}
				} );
		},

		embed: function() {
			var section, parentContainer;

			if ( 'bgtfw_section' !== this.params.type || 'undefined' === typeof this.params.section ) {
				_sectionEmbed.call( this );

				return;
			}

			_sectionEmbed.call( this );

			section = this;
			parentContainer = $( '#sub-accordion-section-' + this.params.section );

			parentContainer.append( section.headContainer );
		},

		isContextuallyActive: function() {
			var section, children, activeCount;

			if ( 'bgtfw_section' !== this.params.type ) {
				return _sectionIsContextuallyActive.call( this );
			}

			section = this;
			children = this._children( 'section', 'control' );

			api.section.each( function( child ) {
				if ( ! child.params.section || child.params.section !== section.id ) {
					return;
				}

				children.push( child );
			} );

			children.sort( api.utils.prioritySort );

			activeCount = 0;

			_( children ).each( function( child ) {
				if ( ( 'undefined' !== typeof child.isContextuallyActive ) && ( child.active() && child.isContextuallyActive() ) ) {
					activeCount += 1;
				} else if ( child.active() ) {
					activeCount += 1;
				}
			} );

			return ( 0 !== activeCount );
		}
	} );

} )( jQuery );

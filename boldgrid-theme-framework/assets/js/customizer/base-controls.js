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

			_.each( control.params.items, function( item ) {
				$( '.sortable-actions' ).append( control.addTypes.call( this, item ) );
			} );

			control.addEvents.call( this );

			// Make our Repeater fields sortable
			control.container.find( '#sortable-' + control.id ).accordion( {
				header: '> .sortable-wrapper > .sortable-title',
				heightStyle: 'content',
				collapsible: true
			} ).sortable( {
				handle: '> .sortable-wrapper > .sortable-title',
				update: this.updateValues.bind( this )
			} ).disableSelection();
			control.container.find( '.connected-sortable' ).accordion( {
				header: '> .repeater > .repeater-input > .repeater-title',
				heightStyle: 'content',
				collapsible: true
			} ).sortable( {
				handle: '> .repeater > .repeater-input > .repeater-title',
				update: this.updateValues.bind( this )
			} ).disableSelection();

			control.container.find( '.resizable-columns > .resizable' ).resizable( {
				handles: 'e',
				create: function() {

					// Hide last column handle.
					$( '.resizable-columns .resizable:last-child .ui-resizable-handle' ).hide();
				},
				resize: function( e, ui ) {
					var m,
						originalClass,
						originalNextClass,
						thiscol,
						container,
						cellPercentWidth,
						nextCol,
						colnum,
						originalColnum,
						originalNextColnum,
						getClosest,
						gridSystem,
						bsClass;

					bsClass = 'col-sm-1 col-sm-2 col-sm-3 col-sm-4 col-sm-5 col-sm-6 col-sm-7 col-sm-8 col-sm-9 col-sm-10 col-sm-11 col-sm-12';

					gridSystem = [
						{
							grid: 8.33333333,
							col: 1
						}, {
							grid: 16.66666667,
							col: 2
						}, {
							grid: 25,
							col: 3
						}, {
							grid: 33.33333333,
							col: 4
						}, {
							grid: 41.66666667,
							col: 5
						}, {
							grid: 50,
							col: 6
						}, {
							grid: 58.33333333,
							col: 7
						}, {
							grid: 66.66666667,
							col: 8
						}, {
							grid: 75,
							col: 9
						}, {
							grid: 83.33333333,
							col: 10
						}, {
							grid: 91.66666667,
							col: 11
						}, {
							grid: 100,
							col: 12
						}, {
							grid: 10000,
							col: 10000
						}
					];

					getClosest = function( arr, value ) {
						var i,
							diff,
							closest,
							mindiff = null;

						for ( i = 0; i < arr.length; ++i ) {
								diff = Math.abs( arr[ i ].grid - value );

							if ( null === mindiff || diff < mindiff ) {

								// first value or trend decreasing
								closest = i;
								mindiff = diff;
							} else {
								return arr[ closest ].col; // col number
							}
						}

						return null;
					};

					m = ui.element[0].className.match( /\bcol-(?:md|xs|sm|lg)-[1-9][0-2]?\b/g );
					originalClass = m ? m[0] : '';
					m = ui.element.next()[0].className.match( /\bcol-(?:md|xs|sm|lg)-[1-9][0-2]?\b/g );
					originalNextClass = m ? m[0] : '';
					thiscol = $( this );
					container = thiscol.parent();
					cellPercentWidth = 100 * ui.originalElement.outerWidth() / container.innerWidth();
					ui.originalElement.css( 'width', cellPercentWidth + '%' );
					nextCol = ui.originalElement.next();
					colnum = getClosest( gridSystem, cellPercentWidth );

					originalColnum = parseInt( originalClass.match( /\d+/g ).map( Number ) );
					originalNextColnum = parseInt( originalNextClass.match( /\d+/g ).map( Number ) );

					thiscol.removeClass( bsClass ).addClass( 'col-sm-' + colnum );

					thiscol.css( 'width', '' );
					if ( originalColnum < parseInt( colnum ) ) {
						thiscol.find( 'h3' ).text( colnum );
						thiscol.next().find( 'h3' ).text( Math.abs( originalNextColnum - 1 ) );
						nextCol.removeClass( bsClass ).addClass( 'col-sm-' + Math.abs( originalNextColnum - 1 ) );
					} else if ( originalColnum > parseInt( colnum ) ) {
						thiscol.find( 'h3' ).text( colnum );
						thiscol.next().find( 'h3' ).text( Math.abs( originalNextColnum + 1 ) );
						nextCol.removeClass( bsClass ).addClass( 'col-sm-' + Math.abs( originalNextColnum + 1 ) );
					}
				}
			} );
		},

		addEvents: function() {
			$( '.bgtfw-sortable' ).on( 'click', ( e ) => this.addItem( e ) );
			$( '.bgtfw-container-control > .bgtfw-sortable-control' ).on( 'click', ( e ) => this.selectContainer( e ) );
		},

		updateValues: function() {
			let	sortableValue,
			values = [];
			_.each( this.container.find( '.connected-sortable' ), ( sortable, key ) => {
				sortableValue = $( sortable ).find( '.repeater' ).map( function() {
					return { type: $( this ).data( 'value' ) };
				} ).toArray();
				values[ key ] = {
					container: this.getContainer( sortable ),
					items: sortableValue
				};
			} );

			wp.customize( this.id ).set( values );
		},

		getContainer: function( sortable ) {
			return $( sortable ).prev().find( '.bgtfw-sortable-control.selected' ).data( 'container' );
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
			let selector = $( e.currentTarget ),
				type = selector.data( 'type' ),
				markup = this.getMarkup( type );

			selector.parent().prev().append( markup );
			this.refreshSortables();
			this.updateValues();
		},

		refreshSortables: function() {
			this.container.find( '#sortable-' + this.id ).sortable( 'refresh' );
			this.container.find( '.connected-sortable' ).sortable( 'refresh' );
		},

		getMarkup: function( type ) {
			return `<li class="repeater" data-value="${ type }">
				<div class="repeater-input">
					${ type }
				</div>
			</li>`;
		},

		addTypes: function( item ) {
			let attribute = item.toLowerCase();
			return '<span class="bgtfw-sortable ' + attribute + '" data-type="' +  attribute + '" aria-label="Add ' + item + '"><i class="fa fa-plus"></i>' + item + '</span>';
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

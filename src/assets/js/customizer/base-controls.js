import WidgetSectionUpdate from './widget/section-update';
import BlogPagePanelExpand from './design/blog/blog-page/panel-expand.js';
import BlogPostsPanelExpand from './design/blog/posts/panel-expand.js';
import HomepageSectionExpand from './design/homepage/section-expand.js';
import { Control as GenericControls } from './generic/control.js';

( function( $ ) {
	var api, _panelEmbed, _panelIsContextuallyActive, _panelAttachEvents, _sectionEmbed, _sectionIsContextuallyActive, _sectionAttachEvents;

	api = wp.customize;
	let widgetSectionUpdate = new WidgetSectionUpdate().init();
	let blogPanel = new BlogPagePanelExpand();
	let blogPostsPanel = new BlogPostsPanelExpand();
	let homepageSection = new HomepageSectionExpand();
	let genericControls = new GenericControls().init();

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

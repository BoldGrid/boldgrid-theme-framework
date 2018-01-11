/* exported CustomizeWidgetSidebarMetaControls */

var CustomizeWidgetSidebarMetaControls = (function( $ ) {
	'use strict';

	var component = {
		data: {
			l10n: {
				color_label: '',
				background_color_label: ''
			}
		}
	};

	/**
	 * Initialize component.
	 *
	 * @param {wp.customize.Values} api  - The wp.customize object.
	 * @param {object}              data - Data.
	 * @returns {void}
	 */
	component.init = function init( api, data ) {
		component.api = api;
		_.extend( component.data, data );
		component.api.bind( 'ready', component.ready );
	};

	/**
	 * Ready.
	 *
	 * @returns {void}
	 */
	component.ready = function ready() {
		component.api.section.each( component.extendSection );
		component.api.section.bind( 'add', component.extendSection );
	};

	/**
	 * Extend a given sidebar section with the
	 *
	 * @param {wp.customize.Section} section Section.
	 * @returns {boolean} Whether the section was extended (whether it was for a sidebar).
	 */
	component.extendSection = function extendSection( section ) {
		if ( ! section.extended( component.api.Widgets.SidebarSection ) ) {
			return false;
		}

		section.metaControlsContainer = $( '<ul class="customize-widget-sidebar-meta-controls"></ul>' );
		section.contentContainer.find( '.customize-section-title' ).after( section.metaControlsContainer );

		component.api.apply( component.api, [
			'sidebar_meta[' + section.params.sidebarId + '][title]',
			'sidebar_meta[' + section.params.sidebarId + '][background_color]'
		] ).done( function() {
			component.addControls( section );
		} );
		return true;
	};

	/**
	 * Extend a given sidebar section with the
	 *
	 * @param {wp.customize.Widgets.SidebarSection} section Section.
	 * @returns {boolean} Whether the section was extended (whether it was for a sidebar).
	 */
	component.addControls = function addControls( section ) {

		// @todo Let the priority define the ordering.
		component.addTitleControl( section );
		component.addBackgroundColorControl( section );
	};

	/**
	 * Add title control.
	 *
	 * @param {wp.customize.Widgets.SidebarSection} section Section.
	 * @returns {wp.customize.control} The added control.
	 */
	component.addTitleControl = function addTitleControl( section ) {
		var control, customizeId, setting;

		customizeId = 'sidebar_meta[' + section.params.sidebarId + '][title]';
		setting = component.api( customizeId );
		control = new component.api.Control( customizeId, {
			params: {
				section: null,
				label: component.data.l10n.title_label,
				active: true,
				type: 'widget-sidebar-meta-title', // Needed for template. See \Customize_Widget_Sidebar_Meta_Controls\customize_controls_print_footer_scripts().
				settings: {
					'default': setting.id
				},
				content: '<li class="customize-control"></li>' // This should not be needed in WordPress 4.8.
			}
		} );
		section.metaControlsContainer.append( control.container );

		// These should not be needed in the future (as of #38077). They are needed currently because section is null.
		control.renderContent();
		control.deferred.embedded.resolve();

		// The following will be unnecessary as of #37964 and #30738
		control.titleElement = new component.api.Element( control.container.find( 'input' ) );
		control.titleElement.set( setting.get() );
		control.titleElement.sync( setting );
	};

	/**
	 * Add color control.
	 *
	 * @param {wp.customize.Widgets.SidebarSection} section Section.
	 * @returns {wp.customize.ColorControl} The added control.
	 */
	component.addBackgroundColorControl = function addColorControl ( section ) {
		var control, customizeId;

		customizeId = 'sidebar_meta[' + section.params.sidebarId + '][background_color]';
		control = new component.api.ColorControl( customizeId, {
			params: {
				section: null,
				label: component.data.l10n.background_color_label,
				active: true,
				type: 'color', // Needed for template. Shouldn't be needed in the future.
				settings: {
					'default': customizeId
				},
				content: '<li class="customize-control"></li>' // This should not be needed in WordPress 4.8.
			}
		} );

		section.metaControlsContainer.append( control.container );

		// These should not be needed in the future (as of #38077). They are needed currently because section is null.
		control.renderContent();
		control.deferred.embedded.resolve();
	};

	return component;

})( jQuery );

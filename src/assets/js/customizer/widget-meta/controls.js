/* exported CustomizeWidgetSidebarMetaControls */

var CustomizeWidgetSidebarMetaControls = ( function( $ ) {
	'use strict';

	var component = {
		data: {
			l10n: {
				color_label: '',
				background_color_label: '',
				headings_color_label: '',
				links_color_label: ''
			},
			choices: {
				colors: [],
				size: ''
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
		var controls;

		if ( ! section.extended( component.api.Widgets.SidebarSection ) ) {
			return false;
		}

		section.metaControlsContainer = $( '<ul class="customize-widget-sidebar-meta-controls"></ul>' );
		section.contentContainer.find( '.customize-section-title' ).after( section.metaControlsContainer );
		controls = [
			'sidebar_meta[' + section.params.sidebarId + '][title]',
			'sidebar_meta[' + section.params.sidebarId + '][background_color]',
			'sidebar_meta[' + section.params.sidebarId + '][headings_color]',
			'sidebar_meta[' + section.params.sidebarId + '][links_color]'
		];
		component.api.apply( component.api, controls ).done( function() {
			var obj = {};
			section.sidebarMeta = section.sidebarMeta || {};

			section.sidebarMeta.controls = controls.reduce( function( o, key ) {
				obj[ key ] = {};
				return Object.assign( o, obj );
			}, {} );

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
		component.addTitleControl( section );
		component.addBackgroundColorControl( section );
		component.addHeadingsColorControl( section );
		component.addLinksColorControl( section );
	};

	/**
	 * Add title control.
	 *
	 * @param {wp.customize.Widgets.SidebarSection} section Section.
	 * @returns {wp.customize.control} The added control.
	 */
	component.addTitleControl = function addTitleControl( section ) {
		var control, customizeId, setting, params, obj = {};

		customizeId = 'sidebar_meta[' + section.params.sidebarId + '][title]';
		setting = component.api( customizeId );
		params = {
			params: {
				section: null,
				label: component.data.l10n.title_label,
				active: true,
				type: 'widget-sidebar-meta-title',
				settings: {
					'default': setting.id
				},
				content: '<li class="customize-control"></li>'
			}
		};

		control = new component.api.Control( customizeId, params );

		obj[ customizeId ] = params;
		_.extend( section.sidebarMeta.controls, obj );

		section.metaControlsContainer.append( control.container );

		control.renderContent();
		control.deferred.embedded.resolve();

		control.titleElement = new component.api.Element( control.container.find( 'input' ) );
		control.titleElement.set( setting.get() );
		control.titleElement.sync( setting );
	};

	/**
	 * Add color control.
	 *
	 * @param {wp.customize.Widgets.SidebarSection} section Section.
	 * @returns {wp.customize.control} The added control.
	 */
	component.addBackgroundColorControl = function addColorControl( section ) {
		var control, customizeId, setting, params, obj = {};

		customizeId = 'sidebar_meta[' + section.params.sidebarId + '][background_color]';
		setting = component.api( customizeId );

		params = {
			params: {
				section: null,
				label: component.data.l10n.background_color_label,
				type: 'bgtfw-palette-selector', // Needed for template. Shouldn't be needed in the future.
				settings: {
					'default': setting.id
				},
				id: customizeId,
				link: 'data-customize-setting-link="' + customizeId + '"',
				choices: component.data.backgroundChoices
			}
		};

		// Create dynamic instance of color palette control.
		control = new component.api.Control( customizeId, params );

		obj[ customizeId ] = params;
		_.extend( section.sidebarMeta.controls, obj );

		section.metaControlsContainer.append( control.container );

		// These should not be needed in the future (as of #38077). They are needed currently because section is null.
		control.renderContent();
		control.deferred.embedded.resolve();

		// Create the link between theinput and settings and sync.
		// https://wordpress.stackexchange.com/questions/280561/customizer-instantiating-settings-and-controls-via-javascript
		control.inputElement = new component.api.Element( control.container.find( 'input' ) );
		control.inputElement.set( setting.get() );
		control.inputElement.sync( setting );
	};

	/**
	 * Add color control.
	 *
	 * @param {wp.customize.Widgets.SidebarSection} section Section.
	 * @returns {wp.customize.control} The added control.
	 */
	component.addHeadingsColorControl = function addColorControl ( section ) {
		var control, customizeId, setting, params, obj = {};

		customizeId = 'sidebar_meta[' + section.params.sidebarId + '][headings_color]';
		setting = component.api( customizeId );

		params = {
			params: {
				section: null,
				label: component.data.l10n.headings_color_label,
				type: 'bgtfw-palette-selector', // Needed for template. Shouldn't be needed in the future.
				settings: {
					'default': setting.id
				},
				id: customizeId,
				link: 'data-customize-setting-link="' + customizeId + '"',
				choices: component.data.choices
			}
		};

		// Create dynamic instance of color palette control.
		control = new component.api.Control( customizeId, params );

		obj[ customizeId ] = params;
		_.extend( section.sidebarMeta.controls, obj );

		section.metaControlsContainer.append( control.container );

		// These should not be needed in the future (as of #38077). They are needed currently because section is null.
		control.renderContent();
		control.deferred.embedded.resolve();

		// Create the link between theinput and settings and sync.
		// https://wordpress.stackexchange.com/questions/280561/customizer-instantiating-settings-and-controls-via-javascript
		control.inputElement = new component.api.Element( control.container.find( 'input' ) );
		control.inputElement.set( setting.get() );
		control.inputElement.sync( setting );
	};

		/**
	 * Add color control.
	 *
	 * @param {wp.customize.Widgets.SidebarSection} section Section.
	 * @returns {wp.customize.control} The added control.
	 */
	component.addLinksColorControl = function addColorControl ( section ) {
		var control, customizeId, setting, params, obj = {};

		customizeId = 'sidebar_meta[' + section.params.sidebarId + '][links_color]';
		setting = component.api( customizeId );

		params = {
			params: {
				section: null,
				label: component.data.l10n.links_color_label,
				type: 'bgtfw-palette-selector', // Needed for template. Shouldn't be needed in the future.
				settings: {
					'default': setting.id
				},
				id: customizeId,
				link: 'data-customize-setting-link="' + customizeId + '"',
				choices: component.data.choices
			}
		};

		// Create dynamic instance of color palette control.
		control = new component.api.Control( customizeId, params );
		obj[ customizeId ] = params;
		_.extend( section.sidebarMeta.controls, obj );

		section.metaControlsContainer.append( control.container );

		// These should not be needed in the future (as of #38077). They are needed currently because section is null.
		control.renderContent();
		control.deferred.embedded.resolve();

		// Create the link between theinput and settings and sync.
		// https://wordpress.stackexchange.com/questions/280561/customizer-instantiating-settings-and-controls-via-javascript
		control.inputElement = new component.api.Element( control.container.find( 'input' ) );
		control.inputElement.set( setting.get() );
		control.inputElement.sync( setting );
	};

	return component;

})( jQuery );

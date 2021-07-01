var BOLDGRID = BOLDGRID || {};
BOLDGRID.CUSTOMIZER = BOLDGRID.CUSTOMIZER || {};
BOLDGRID.CUSTOMIZER.Search = BOLDGRID.CUSTOMIZER.Search || {};

'use strict';

/**
 * Customizer Admin JS
 *
 * @since  1.0.0
 * @package Customizer_Search
 */

( function( $ ) {

	/**
	 * Selector for the search field
	 * @type {String}
	 */
	var searchInputSelector = '#customizer-search-input';

	/**
	 * innerHTML of all the customizer panels.
	 * @type {String}
	 */
	var customizerPanels = '';

	/**
	 * Handles logic for the customizer search interface.
	 *
	 * @class BOLDGRID.CUSTOMIZER.Search
	 * @since 2.0.0
	 */
	BOLDGRID.CUSTOMIZER.Search = {
		controls: [],
		/**
		 * Initializes the customizer search interface.
		 *
		 * @since 1.0.0
		 */
		_init: function _init() {
			this._bind();

			// Maps the existing controls.
			BOLDGRID.CUSTOMIZER.Search.controls = $.map( _wpCustomizeSettings.controls, function( control ) {
				$.map( _wpCustomizeSettings.sections, function( section ) {
					if ( control.section === section.id ) {
						$.map( _wpCustomizeSettings.panels, function( panel ) {
							if ( '' === section.panel ) {
								control.panelName = section.title;
							}
							if ( section.panel === panel.id ) {
								control.sectionName = section.title;
								control.panel = section.panel;
								control.panelName = panel.title;
							}
						} );
					}
				} );

				return [control];
			} );

			// Adds the dymanic sidebar controls we've created.
			$.map( _wpCustomizeSettings.sections, function( section ) {
				var sidebar, panel;
				if ( 'sidebar' === section.type ) {
					sidebar = wp.customize.section( section.id );
					panel = wp.customize.panel( sidebar.params.panel );
					_.each( sidebar.controls(), function( v ) {
						var c;
						c = v.params;
						c.label = c.label.replace( /:/g, '' );
						c.sectionName = sidebar.params.title;
						c.section = sidebar.params.id;
						c.panel = panel.params.id;
						c.panelName = panel.params.title;
						BOLDGRID.CUSTOMIZER.Search.controls.push( c );
					} );
				}
			} );

			customizerPanels = document.getElementById( 'customize-theme-controls' );

			customizePanelsParent = $( '#customize-theme-controls' );
			customizePanelsParent.after( '<div id="search-results"></div>' );

			$( document ).on( 'keyup', searchInputSelector, function( event ) {
				event.preventDefault();
				$this = $( searchInputSelector );
				string = $this.val();

				if ( string.length > 0 ) {
					BOLDGRID.CUSTOMIZER.Search.displayMatches( string, BOLDGRID.CUSTOMIZER.Search.controls );
				} else {
					BOLDGRID.CUSTOMIZER.Search._clearSearch();
				}
			} );

			$( document ).on( 'click', '.clear-search', function() {
				BOLDGRID.CUSTOMIZER.Search._clearSearch();
			} );

			$( document ).on( 'click', '.customize-search-toggle', function() {
				BOLDGRID.CUSTOMIZER.Search._toggleSearchForm();
			} );

			$( document ).on( 'click', '.customize-controls-preview-toggle', function() {
				BOLDGRID.CUSTOMIZER.Search._closeSearchForm();
			} );
		},

		/**
		 * Expands the section of a search result so user goes to control.
		 *
		 * @since  2.0.0
		 */
		expandSection: function expandSection() {
			var sectionName = this.getAttribute( 'data-section' );
			var section = wp.customize.section( sectionName );
			BOLDGRID.CUSTOMIZER.Search._clearSearch();
			BOLDGRID.CUSTOMIZER.Search._closeSearchForm();
			section.expand();
		},

		/**
		 * Displays the matches and settings trail in customizer.
		 *
		 * @since  2.0.0
		 */
		displayMatches: function displayMatches( stringToMatch, controls ) {
			var matchArray, searchSettings;

			matchArray = BOLDGRID.CUSTOMIZER.Search.findMatches( stringToMatch, controls );

			if ( 0 === matchArray.length ) {
				return; // Return if empty results.
			}

			html = matchArray.map( function( index ) {
				var settingTrail, label, regex;
				if ( '' === index.label ) {
					return; // Return if empty results.
				}

				settingTrail = index.panelName;
				if ( '' !== index.sectionName ) {
					settingTrail = settingTrail + ' \u25B8 ' + index.sectionName;
				}

				regex = new RegExp( stringToMatch, 'gi' );
				label = index.label.replace(
					regex,
					'<span class="hl">$&</span>'
				);
				settingTrail = settingTrail.replace(
					regex,
					'<span class="hl">$&</span>'
				);

				return ( '<li id="accordion-section-' + index.section + '" class="accordion-section control-section control-section-default customizer-search-results" aria-owns="sub-accordion-section-' + index.section + '" data-section="' + index.section + '">' +
					'<h3 class="accordion-section-title" tabindex="0">' + label +
							'<span class="screen-reader-text">Press return or enter to open this section</span>' +
						'</h3>' +
						'<span class="search-setting-path">' + settingTrail + '</i></span>' +
					'</li>'
				);
			} ).join( '' );

			customizerPanels.classList.add( 'search-not-found' );
			document.getElementById( 'search-results' ).innerHTML =
				'<ul id="customizer-search-results">' + html + '</ul>';

			searchSettings = document.querySelectorAll(
				'#search-results .accordion-section'
			);
			searchSettings.forEach( function( setting ) {
				return setting.addEventListener(
					'click',
					BOLDGRID.CUSTOMIZER.Search.expandSection
				);
			} );
		},

		/**
		 * Finds the matches for a search query.
		 *
		 * @since  2.0.0
		 */
		findMatches: function findMatches( stringToMatch, controls ) {
			return controls.filter( function( control ) {
				var regex;

				if ( control.panelName == null ) {
					control.panelName = '';
				}

				if ( control.sectionName == null ) {
					control.sectionName = '';
				}

				// Search for the stringToMatch from control label, Panel Name, Section Name.
				regex = new RegExp( stringToMatch, 'gi' );

				return (
					control.label.match( regex ) ||
					control.panelName.match( regex ) ||
					control.sectionName.match( regex )
				);
			} );
		},

		/**
		 * Binds admin customize events.
		 *
		 * @since 2.0.0
		 */
		_bind: function _bind() {
			wp.customize.previewer.targetWindow.bind(
				$.proxy( this._renderTemplate, this )
			);
		},

		/**
		 * Adds the templates to the customizer.
		 *
		 * @since 2.0.0
		 */
		_renderTemplate: function _renderTemplate() {
			var template;

			template = wp.template( 'search-button' );
			if ( $( '#customize-header-actions .customize-search-toggle' ).length === 0 ) {
				$( '#customize-header-actions' ).append( template() );
			}

			template = wp.template( 'search-form' );
			if ( $( '#accordion-section-customizer-search' ).length === 0 ) {
				$( '#customize-header-actions' ).after( template() );
			}
		},

		/**
		 * Toggles the seach form.
		 *
		 * @since  2.0.0
		 */
		_toggleSearchForm: function _toggleSearchForm() {
			if ( $( '#accordion-section-customizer-search' ).hasClass( 'open' ) ) {
				BOLDGRID.CUSTOMIZER.Search._closeSearchForm();
			} else {
				BOLDGRID.CUSTOMIZER.Search._openSearchForm();
			}

			$( searchInputSelector ).focus();
		},

		/**
		 * Closes the search form.
		 *
		 * @since  2.0.0
		 */
		_closeSearchForm: function _closeSearchForm() {
			var searchHeight, noticeHeight, visibility;
			noticeHeight = $( '#customize-notifications-area' ).outerHeight();
			searchHeight = $( '#accordion-section-customizer-search' ).outerHeight();
			visibility = $( '#customize-notifications-area:hidden' ).length ? 0 : 1;

			$( '#accordion-section-customizer-search' ).removeClass( 'open' ).slideUp( 'fast' );
			$( '#customize-header-actions .customize-search-toggle' ).removeClass( 'open' );
			$( '.wp-full-overlay-sidebar-content' ).animate( { top: 45 + noticeHeight + visibility + 'px' }, 'fast' );
			$( '#customize-notifications-area' ).animate( { top: $( '#customize-header-actions' ).outerHeight() + 'px' }, 'fast' );
			BOLDGRID.CUSTOMIZER.Search._clearSearch();
		},

		/**
		 * Opens the search form.
		 *
		 * @since  2.0.0
		 */
		_openSearchForm: function _openSearchForm() {
			var searchHeight, noticeHeight, visibility;
			noticeHeight = $( '#customize-notifications-area' ).height();
			searchHeight = $( '#accordion-section-customizer-search' ).height();
			visibility = $( '#customize-notifications-area:hidden' ).length ? 0 : 1;

			$( '.customize-panel-description' ).removeClass( 'open' ).slideUp( 'fast' );
			$( '#customize-header-actions .customize-search-toggle' ).addClass( 'open' );
			$( '#accordion-section-customizer-search' ).addClass( 'open' ).slideDown( 'fast' );
			$( '#customize-notifications-area' ).animate( { top: $( '#customize-header-actions' ).height() + searchHeight + 2 + 'px' }, 'fast' );
			$( '.wp-full-overlay-sidebar-content' ).animate( { top: 81 + noticeHeight + visibility + 'px' }, 'fast' );
		},

		/**
		 * Clear search input and display all the options.
		 *
		 * @since  2.0.0
		 */
		_clearSearch: function _clearSearch() {
			var panels = document.getElementById( 'customize-theme-controls' );
			panels.classList.remove( 'search-not-found' );
			document.getElementById( 'search-results' ).innerHTML = '';
			document.getElementById( 'customizer-search-input' ).value = '';
			$( searchInputSelector ).focus();
		}
	};

	// Initialize.
	$( function() {
		BOLDGRID.CUSTOMIZER.Search._init();
	} );

} )( jQuery );

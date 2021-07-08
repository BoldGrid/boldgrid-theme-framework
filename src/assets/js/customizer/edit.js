/**
 * This file adds the js necessary to add Edit buttons within the Customizer preview.
 *
 * @summary Add edit buttons to customizer.
 *
 * @since 1.1.6
 * @requires jquery-ui-dialog
 */

/* global _,jQuery */

var BOLDGRID = BOLDGRID || {};
BOLDGRID.CustomizerEdit = BOLDGRID.CustomizerEdit || {};

( function( $ ) {

	'use strict';

	var self, api;

	api = parent.wp.customize;

	/**
	 * Add edit buttons to customizer.
	 *
	 * @since 1.1.6
	 */
	BOLDGRID.CustomizerEdit = {


		buttonParams: window.boldgridFrameworkCustomizerEdit || {},

		/**
		 * Keep track of a button's collision set.
		 *
		 * @since 1.1.6
		 * @access public
		 * @property int
		 */
		buttonCollisionSet: 1,

		/**
		 * An interval set to place the target-highlight.
		 *
		 * @since 1.1.6
		 * @access public
		 * @property function
		 */
		targetHighlightTop: true,

		/**
		 * Is the user scrolling?
		 *
		 * @since 1.1.6
		 * @access public
		 * @property bool
		 */
		userIsScrolling: false,

		/**
		 * The height of an edit button.
		 *
		 * @since 1.1.6
		 * @access public
		 * @property int
		 */
		buttonHeight: 0,
		buttonWidth: 0,

		/**
		 * Default z-index of our edit buttons, as defined in edit.css.
		 *
		 * @since 1.1.6
		 * @access public
		 * @property int
		 */
		defaultZindex: 200,

		init: function() {
			api.previewer.bind( 'ready', self._onLoad );
		},

		_onLoad: function() {
			self.destroy();
			self.start();
			wp.customize.selectiveRefresh.bind( 'partial-content-rendered', function() {
				self.destroy();
				self.start();
			} );
		},

		destroy: function() {
			$( '.bgtfw-multi-edit-button' ).remove();
			$( '.bgtfw-edit-button' ).remove();
		},

		/**
		 * @summary Init the buttons.
		 *
		 * @since 1.1.6
		 */
		start: function() {
			self.$targetHighlight = $( '#target-highlight' );

			self.addButtons();
		},

		/**
		 * @summary Add all edit buttons to the DOM.
		 *
		 * @since 1.1.6
		 */
		addButtons: function() {
			_( self.buttonParams.params ).each( function( controls, selector ) {
				if ( 1 === Object.keys( controls ).length ) {
					let controlId = Object.keys( controls )[0];
					let buttonPosition = self.determineButtonPosition( selector );
					self.addSingleButton( selector, controlId, controls[ controlId ], buttonPosition );
				} else {
					let buttonPosition = self.determineButtonPosition( selector );
					self.addMultiButtons( selector, controls, buttonPosition );
				}
			} );
			self.addMenuButtons();
		},

		determineButtonPosition: function( selector ) {
			var locationHeight = $( selector ).height(),
				locationWidth  = $( selector ).outerWidth(),
				locationOffset = $( selector ).offset(),
				documentHeight = $( document ).height(),
				position       = { hor: 'right', vert: 'bottom' };

				if ( locationOffset.left < locationWidth ) {
					position.hor = 'left';
				}

				if ( locationHeight > documentHeight - ( locationHeight + locationOffset.top ) ) {
					position.vert = 'top';
				}

				return position;
		},

		addMenuButtons: function() {
			var menus = $( '.bgtfw-menu-wrap' );
			_( menus ).each( function( menu ) {
				var themeLocation = menu.id.split( '-wrap' )[0],
					menuId,
					menuLocationName,
					controls = {},
					buttonPosition;

				_( api.section( 'menu_locations' ).controls() ).each( function( menuLocation ) {
					if ( menuLocation.themeLocation === themeLocation ) {
						menuId = menuLocation.setting();
						menuLocationName = menuLocation.params.label;
					}
				} );

				if ( ! menuId ) {
					controls[ 'nav_menu_locations[' + themeLocation + ']' ] = {type: 'control', label: 'Assign Menu', description: 'Assign or Create a menu for this location' };
					controls[ 'bgtfw_menu_location_' + themeLocation ] = {type: 'panel', label: 'Customize ' + menuLocationName, description: 'Customize the styling of this menu' };
					buttonPosition = self.determineButtonPosition( 'div#' + themeLocation + '-menu' );
					self.addMultiButtons( 'div#' + themeLocation + '-menu', controls, buttonPosition );
				} else {
					let menuSelector = 'ul#' + themeLocation + '-menu';
					controls[ 'nav_menu[' + menuId + ']' ] = {type: 'section', label: 'Add Menu Items', description: 'Add or remove items to this menu' };
					controls[ 'bgtfw_menu_location_' + themeLocation ] = {type: 'panel', label: 'Customize ' + menuLocationName, description: 'Customize the styling of this menu' };
					buttonPosition = self.determineButtonPosition( menuSelector );
					self.addMultiButtons( menuSelector, controls, buttonPosition );
				}


			} );
		},

		addMultiButtons: function( selector, controls, buttonPosition ) {
			if ( 'static' === $( selector ).css( 'position' ) ) {
				$( selector ).css( 'position', 'relative' );
			}

			$( selector ).addClass( 'bgtfw-has-edit multi-edit-button' );
			$( selector ).addClass( buttonPosition.vert + '-button ' + buttonPosition.hor + '-button' );
			$( selector ).append( '<div class="bgtfw-multi-edit-button"><div></div></div>' );
			$( selector ).append( '<div class="bgtfw-multi-edit-border-box"></div>' );
			_( controls ).each( function( control, controlId ) {
				$( selector ).find( '.bgtfw-multi-edit-button' ).find( 'div' ).append( `
					<p class="bgtfw-edit-item" data-focus-type="${control.type}" data-focus-id="${controlId}">
						<span class="edit-label">${control.label}</span> - ${control.description}</span>
					<p>
				` );
			} );

			$( selector ).find( '.bgtfw-edit-item' ).on( 'click', function() {
				var control = $( this ).data( 'focus-id' ),
					type    = $( this ).data( 'focus-type' );
				api[ type ]( control ).focus();
			} );
		},

		addSingleButton: function( selector, controlId, control, buttonPosition ) {
			if ( 'static' === $( selector ).css( 'position' ) ) {
				$( selector ).css( 'position', 'relative' );
			}
			$( selector ).addClass( 'bgtfw-has-edit single-edit-button' );
			$( selector ).addClass( buttonPosition.vert + '-button ' + buttonPosition.hor + '-button' );
			$( selector ).append( '<div class="bgtfw-edit-button" data-focus-type="' + control.type + '" data-focus-id="' + controlId + '" title="' + control.label + '"><div>' );
			$( selector ).append( '<div class="bgtfw-edit-border-box"></div>' );
			$( selector ).find( '.bgtfw-edit-button' ).on( 'click', function() {
				if ( 'A' === $( selector ).prop( 'nodeName' ) ) {
					$( selector ).on( 'click', function( e ) {
						e.preventDefault();
						e.stopPropagation();
					} );
				}
				api[control.type]( controlId ).focus();
			} );
		}
	};

	self = BOLDGRID.CustomizerEdit;

} )( jQuery );

BOLDGRID.CustomizerEdit.init();
parent.window.BOLDGRID.CustomizerEdit = BOLDGRID.CustomizerEdit;

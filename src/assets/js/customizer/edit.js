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

	var self, bg, $body, api;

	bg = BOLDGRID;
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
			self._onReady();
			self._onLoad();
		},

		_onReady: function() {
			$( document ).on( 'ready', self.adjustEmptyMenus );
		},

		_onLoad: function() {
			$( window ).on( 'load', function() {
				$body = $( 'body' );

				self.start();
			} );
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
					self.addSingleButton( selector, controlId, controls[ controlId ] );
				} else {
					self.addMultiButtons( selector, controls );
				}
			} );
			self.addMenuButtons();
		},

		addMenuButtons: function() {
			var menus = $( 'ul.sm' );
			_( menus ).each( function( menu ) {
				var themeLocation = menu.id.split( '-' )[0],
					menuId,
					menuLocationName,
					controls = {};
				_( api.section( 'menu_locations' ).controls() ).each( function( menuLocation ) {
					if ( themeLocation === menuLocation.themeLocation ) {
						menuId = menuLocation.setting();
						menuLocationName = menuLocation.params.label;
					}
				} );

				if ( ! menuId ) {
					return;
				}

				controls[ 'nav_menu[' + menuId + ']' ] = {type: 'section', label: 'Add Menu Items', description: 'Add or remove items to this menu' };
				controls[ 'bgtfw_menu_location_' + themeLocation ] = {type: 'panel', label: 'Customize ' + menuLocationName, description: 'Customize the styling of this menu' };

				self.addMultiButtons( 'ul#' + menu.id, controls );
			} );
		},

		addMultiButtons: function( selector, controls ) {
			if ( 'static' === $( selector ).css( 'position' ) ) {
				$( selector ).css( 'position', 'relative' );
			}
			console.log( controls );
			$( selector ).addClass( 'bgtfw-has-edit multi-edit-button' );
			$( selector ).append( '<div class="bgtfw-multi-edit-button"><div></div></div>' );
			$( selector ).append( '<div class="bgtfw-multi-edit-border-box"></div>' );
			_( controls ).each( function( control, controlId ) {
				console.log( $( selector ).find( '.bgtfw-multi-edit-button' ).find( 'ul' ) );
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

		addSingleButton: function( selector, controlId, control ) {
			if ( 'static' === $( selector ).css( 'position' ) ) {
				$( selector ).css( 'position', 'relative' );
			}
			$( selector ).addClass( 'bgtfw-has-edit single-edit-button' );
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

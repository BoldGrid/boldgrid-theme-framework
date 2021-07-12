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
		 * @property array
		 */
		buttonCollisionSet: {},

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
			self.buttonCollisionSet = {};
			$( '.bgtfw-multi-edit-button' ).remove();
			$( '.bgtfw-edit-border-box' ).remove();
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
				$( selector ).each( function() {
					var text = $( this ).clone().children().remove().end().text();
					if ( 0 === $( this ).height() ||
						0 === $( this ).outerWidth() ||
						( $( this ).is( 'h1, h2, h3, h4, h5, h6, p' ) && 0 === text.length ) ) {
							$( this ).addClass( 'no-edit-button' );
					}
				} );
				if ( 1 === Object.keys( controls ).length ) {
					let controlId = Object.keys( controls )[0];
					let buttonPosition = self.determineButtonPosition( selector );
					if ( 'bgtfw_body_link_color' === controlId && $( selector ).is( '.button-primary, .button-secondary' ) ||
						'bgtfw_body_link_color' === controlId && $( selector ).parent().is( '.page-title' ) ||
						'bgtfw_body_link_color' === controlId && $( selector ).parent().is( '.tags-links' ) ||
						'bgtfw_body_link_color' === controlId && $( selector ).parent().is( '.cat-links' )) {
						return;
					}
					self.addSingleButton( selector, controlId, controls[ controlId ], buttonPosition );
				} else {
					let buttonPosition = self.determineButtonPosition( selector );
					self.addMultiButtons( selector, controls, buttonPosition );
				}
			} );
			self.addMenuButtons();

			self.addWidgetButtons();

			_.defer( self.fixCollisions );
		},

		fixCollisions: function() {
			var $editButtons = $( '.bgtfw-multi-edit-button, .bgtfw-edit-button' );

			$editButtons.each( function() {
				if ( 'static' === $( this ).parent().css( 'position' ) ) {
					$( this ).parent().css( 'position', 'relative' );
				}
			} );

			$editButtons.each( function() {
				var buttonOffset = $( this ).offset(),
					offsetKey    = Math.floor( buttonOffset.left ) + ',' + Math.floor( buttonOffset.top );

					if ( self.buttonCollisionSet.hasOwnProperty( offsetKey ) ) {
						self.buttonCollisionSet[ offsetKey ].push( $( this ) );
					} else {
						self.buttonCollisionSet[ offsetKey ] = [ $( this ) ];
					}
			} );

			for ( const offset in self.buttonCollisionSet ) {
				if ( 1 === self.buttonCollisionSet[ offset ].length ) {
					continue;
				}

				self.buttonCollisionSet[ offset ].forEach( self.fixButtonCollision );
			}

			$editButtons.each( function() {
				if ( $( this ).is( '.bgtfw-multi-edit-button' ) ) {
					self.fixMultiCollisions( $( this ).children( 'div' ) );
				}
			} );
		},

		fixMultiCollisions: function( menuSelector ) {
			var multiBoxHeight   = $( menuSelector ).height(),
				multiBoxOffset   = $( menuSelector ).parent().offset(),
				docHeight        = $( document ).height(),
				isTop            = $( menuSelector ).closest( '.bgtfw-has-edit' ).is( '.top-button' ) ? true : false,
				css              = {};

			if ( ! multiBoxOffset ) {
				return;
			}

			if ( ( multiBoxHeight + multiBoxOffset.top + 30 ) >= docHeight ||
				0 >= (  multiBoxOffset.top + 30 - multiBoxHeight ) ) {
					css.top    = isTop ? 'unset' : '30px';
					css.bottom = isTop ? '30px' : 'unset';
					$( menuSelector ).css( css );
			}
		},

		fixButtonCollision: function( buttonSelector, index ) {
			var $buttonParent   = $( buttonSelector ).parent( '.bgtfw-has-edit' ),
				isLeft          = $buttonParent.is( '.left-button' ) ? true : false,
				cssProp         = isLeft ? 'left' : 'right',
				horAdjustment;

				if ( 0 === index ) {
					return;
				}

				horAdjustment = isLeft ? 30 * index : ( 30 * -1 * index ) - 30;

				$( buttonSelector ).css( cssProp, horAdjustment + 'px' );
		},

		determineButtonPosition: function( selector ) {
			var locationHeight = $( selector ).height(),
				locationWidth  = $( selector ).outerWidth(),
				locationOffset = $( selector ).offset(),
				documentHeight = $( document ).height(),
				position       = { hor: 'right', vert: 'bottom' };

				if ( locationOffset && locationOffset.left < locationWidth ) {
					position.hor = 'left';
				}

				if ( locationOffset && locationHeight > documentHeight - ( locationHeight + locationOffset.top ) ) {
					position.vert = 'top';
				}

				return position;
		},

		addWidgetButtons: function() {
			var widgets = $( 'aside.sidebar' );

			_( widgets ).each( function( widget ) {
				var widgetId      = widget.id,
					sectionId     = 'sidebar-widgets-' + widgetId,
					buttonPosition = self.determineButtonPosition( '#' + widgetId ),
					control    = {
						'type': 'section',
						'label': 'Widgets'
					};

					self.addSingleButton( '#' + widgetId, sectionId, control, buttonPosition );
			} );
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

			$( selector ).not( '.no-edit-button' ).addClass( 'bgtfw-has-edit multi-edit-button' );
			$( selector ).not( '.no-edit-button' ).addClass( buttonPosition.vert + '-button ' + buttonPosition.hor + '-button' );
			$( selector ).not( '.no-edit-button' ).append( '<div class="bgtfw-multi-edit-button" title="Click for edit options"><div></div></div>' );
			$( selector ).not( '.no-edit-button' ).append( '<div class="bgtfw-edit-border-box"></div>' );
			_( controls ).each( function( control, controlId ) {
				$( selector ).not( '.no-edit-button' ).find( '.bgtfw-multi-edit-button' ).find( 'div' ).append( `
					<p class="bgtfw-edit-item" data-focus-type="${control.type}" data-focus-id="${controlId}">
						<span class="edit-label">${control.label}</span>${control.description}
					</p>
				` );
			} );

			$( selector ).find( '.bgtfw-multi-edit-button' ).on( 'click', function() {
				$( this ).toggleClass( 'expanded' );
				$( '.bgtfw-multi-edit-button' ).not( this ).removeClass( 'expanded' );
			} );

			$( selector ).find( '.bgtfw-edit-item' ).on( 'click', function() {
				var control = $( this ).data( 'focus-id' ),
					type    = $( this ).data( 'focus-type' );
				api[ type ]( control ).focus();
			} );
		},

		addSingleButton: function( selector, controlId, control, buttonPosition ) {
			$( selector ).not( '.no-edit-button' ).addClass( 'bgtfw-has-edit single-edit-button' );
			$( selector ).not( '.no-edit-button' ).addClass( buttonPosition.vert + '-button ' + buttonPosition.hor + '-button' );
			$( selector ).not( '.no-edit-button' ).append( '<div class="bgtfw-edit-button" data-focus-type="' + control.type + '" data-focus-id="' + controlId + '" title="' + control.label + '"><div>' );
			$( selector ).not( '.no-edit-button' ).append( '<div class="bgtfw-edit-border-box"></div>' );
			$( selector ).not( '.no-edit-button' ).find( '.bgtfw-edit-button' ).on( 'click', function() {
				if ( 'A' === $( selector ).not( '.no-edit-button' ).prop( 'nodeName' ) ) {
					$( selector ).not( '.no-edit-button' ).on( 'click', function( e ) {
						e.preventDefault();
						e.stopPropagation();
					} );
				}
				$( '.bgtfw-multi-edit-button' ).removeClass( 'expanded' );
				api[control.type]( controlId ).focus();
			} );
		}
	};

	self = BOLDGRID.CustomizerEdit;

} )( jQuery );

BOLDGRID.CustomizerEdit.init();
parent.window.BOLDGRID.CustomizerEdit = BOLDGRID.CustomizerEdit;

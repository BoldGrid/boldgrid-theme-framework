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

const { __ } = wp.i18n;

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
			self.buttonCollisionSet = [];
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

			$( window ).on( 'scroll', function() {
				var scrollPos = $( window ).scrollTop();
				if ( 0 >= scrollPos ) {
					$( '#masthead-sticky' ).css( 'display', 'none' );
				} else if ( api( 'bgtfw_fixed_header' )() ) {
					$( '#masthead-sticky' ).css( 'display', 'block' );
				}
			} );
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
						( $( this ).is( 'h1, h2, h3, h4, h5, h6, p' ) && 0 === text.length && ! $( this ).is( '.site-title' ) && ! $( this ).is( '.entry-title' ) ) ) {
							$( this ).addClass( 'no-edit-button' );
					}
				} );
				if ( 1 === Object.keys( controls ).length ) {
					let controlId = Object.keys( controls )[0];
					let buttonPosition = self.determineButtonPosition( selector );
					if ( 'bgtfw_body_link_color' === controlId && $( selector ).is( '.button-primary, .button-secondary' ) ||
						'bgtfw_body_link_color' === controlId && $( selector ).parent().is( '.page-title, .entry-title, .tags-links, .cat-links ' ) ||
						'bgtfw_body_link_color' === controlId && $( selector ).parent().is( '.author, .posted-on, .nav-previous, .nav-next' ) ) {
						return;
					}

					if ( 'bgtfw_body_typography' === controlId && $( selector ).is( '.entry-title' ) ) {
						return;
					}

					if ( 'bgtfw_headings_typography' === controlId && $( selector ).is( '.site-description, .site-title' ) ) {
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

			self.addExternalButtons();

			_.defer( self.fixStaticPostioning );

			_.defer( self.fixCollisions );

			_.defer( self.fixZindex );
		},

		/**
		 * Fixes Menu Z-index.
		 *
		 * Find all .sm elements and decrease the z-index for each one.
		 */
		fixZindex: function() {
			var defaultZindex = 301;
			$( '.sm' ).each( function() {
				defaultZindex = defaultZindex - 1;
				$( this ).css( 'z-index', defaultZindex );
			} );
		},

		fixStaticPostioning: function() {
			var $editButtons = $( '.bgtfw-multi-edit-button, .bgtfw-edit-button' );

			$editButtons.each( function() {
				if ( 'static' === $( this ).parent().css( 'position' ) ) {
					$( this ).parent().css( 'position', 'relative' );
				}
			} );
		},

		/**
		 * @summary Sort buttons asc based upon location to the top of the document.
		 *
		 * @since 1.1.6
		 *
		 * @link http://stackoverflow.com/questions/1129216/sort-array-of-objects-by-string-property-value-in-javascript
		 *
		 * @param object a A jQuery object.
		 * @param object b A jQuery object.
		 * @return int
		 */
		sortButtonsAsc: function( $a, $b ) {
			var aTop = $a.offset().top,
				bTop = $b.offset().top,
				$parentA,
				$parentB,
				parentATop,
				parentBTop;

			if ( aTop === bTop ) {
				$parentA = $( $a.parent() );
				parentATop = $parentA.offset().top;

				aTop = parentATop;

				$parentB = $( $b.parent() );
				parentBTop = $parentB.offset().top;

				bTop = parentBTop;
			}

			if ( aTop < bTop ) {
				return -1;
			} else if ( aTop > bTop ) {
				return 1;
			} else {
				return 0;
			}
		},

		/**
		 * @summary Sort buttons desc based upon location to the top of the document.
		 *
		 * @since 1.1.6
		 *
		 * @param object a A jQuery object.
		 * @param object b A jQuery object.
		 * @return int
		 */
		sortButtonsDesc: function( a, b ) {
			var aTop = a.offset().top,
				bTop = b.offset().top;

			if ( bTop < aTop ) {
				return -1;
			} else if ( bTop > aTop ) {
				return 1;
			} else {
				return 0;
			}
		},

		assignCollisionSet: function( $buttonA, $buttonB ) {

			// The button towards the bottom will be moved lower. Figure out which button is higher.
			var aTop = Math.ceil( $buttonA.offset().top ),
				bTop = Math.ceil( $buttonB.offset().top ),
				$lowerButton = ( aTop > bTop ? $buttonA : $buttonB ),
				$higherButton = ( $buttonA.is( $lowerButton ) ? $buttonB : $buttonA ),
				collisionSet = $higherButton.attr( 'data-collision-set' );

			$lowerButton.offset( {
				'top': Math.ceil( $higherButton.offset().top ) + 30,
				'left': $lowerButton.offset().left
			} );

			if ( 'undefined' === typeof collisionSet ) {
				collisionSet = self.buttonCollisionSet;
				self.buttonCollisionSet++;
			}

			$lowerButton.attr( 'data-collision-set', collisionSet );
			$higherButton.attr( 'data-collision-set', collisionSet );
		},

		fixCollisions: function() {
			var editButtons = [];

			self.buttonCollisionSet = 1;

			$( '.bgtfw-multi-edit-button:visible, .bgtfw-edit-button:visible' ).each( function() {
				editButtons.push( $( this ) );
			} );

			editButtons.sort( self.sortButtonsAsc );

			$.each( editButtons, function( index, buttonA ) {
				var $buttonA = $( buttonA );

				// If this is not the last button.
				if ( index < ( editButtons.length - 1 ) ) {
					$.each( editButtons, function( indexB, buttonB ) {
						var $buttonB = $( buttonB );

						if ( $buttonA.is( $buttonB ) ) {
							return;
						}

						if ( self.determineButtonCollision( $buttonA, $buttonB ) ) {
							self.assignCollisionSet( $buttonA, $buttonB );
						}
					} );
				}
			} );
		},

		fixMultiCollisions: function( menuSelector ) {
			var multiBoxHeight   = $( menuSelector ).height(),
				multiBoxOffset   = $( menuSelector ).parent().offset(),
				docHeight        = $( '#colophon.site-footer' ).offset().top + $( '#colophon.site-footer' ).outerHeight( true ),
				scrollTop        = $( window ).scrollTop(),
				css              = {};

			if ( ! multiBoxOffset ) {
				return;
			}

			if ( 0 >= ( ( multiBoxOffset.top - scrollTop - 30 ) - multiBoxHeight ) ) {
				css.top    = '30px';
				css.bottom = 'unset';
				$( menuSelector ).css( css );
			}

			if ( ( multiBoxHeight + multiBoxOffset.top + 30 ) >= docHeight ) {
				css.top    = 'unset';
				css.bottom = '30px';
				$( menuSelector ).css( css );
			}
		},

		adjustButtonPosition: function( $button, collisionIndex ) {
			var $buttonParent = $button.parent( '.bgtfw-has-edit' ),
				isLeft        = $buttonParent.is( '.left-button' ) ? true : false,
				topPos        = $button.offset().top,
				leftPos       = Math.floor( $button.offset().left ),
				adjustment    = 30 * collisionIndex,
				newPos        = isLeft ? leftPos + adjustment : leftPos - adjustment;

			$button.offset( { top: topPos, left: newPos } );
		},

		determineButtonCollision: function( $test, $collision ) {
			var testRect      = $test.get( 0 ).getBoundingClientRect(),
				collisionRect = $collision.get( 0 ).getBoundingClientRect(),
				overlap       = ! ( testRect.right < collisionRect.left ||
					testRect.left > collisionRect.right ||
					testRect.bottom < collisionRect.top ||
					testRect.top > collisionRect.bottom
				);

			return overlap;
		},

		determineButtonPosition: function( selector ) {
			var locationHeight = $( selector ).height(),
				locationOffset = $( selector ).offset(),
				documentHeight = $( document ).height(),
				position       = { hor: 'right', vert: 'bottom' };

				if ( locationOffset && 30 > locationOffset.left ) {
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
					buttonPosition = self.determineButtonPosition( '#' + widgetId );

				// Add widget area edit button.
				self.addSingleButton(
					'#' + widgetId,
					sectionId,
					{
						'type': 'section',
						'label': __( 'Widget Area', 'bgtfw' )
					},
					buttonPosition
				);
			} );
		},

		addExternalButtons: function() {
			var pageSelector  = '.site-content .entry-content',
				pageButtonPos = self.determineButtonPosition( pageSelector ),
				pageHeaderSelector = '#masthead.template-header',
				pageHeaderButtonPos = self.determineButtonPosition( pageHeaderSelector ),
				templateId;

			self.addSingleButton(
				pageSelector,
				'editPostLink',
				{
					type: 'external',
					label: 'Edit Page / Post Content',
					description: 'Edit the Content of this Page / Post',
					dialogSelector: '#entry-content'
				},
				pageButtonPos
			);

			if ( 0 !== $( pageHeaderSelector ).length ) {
				let classList = $( pageHeaderSelector ).get( 0 ).classList;
				const re = /template-(\d+)/;

				classList.forEach( function( className ) {
					let match = re.exec( className );
					if ( match ) {
						templateId = match[ 1 ];
						self.buttonParams.pageHeaderLink = self.buttonParams.editPostLink.replace(
							/post=\d+/,
							'post=' + templateId
						);
					}
				} );

				self.addSingleButton(
					pageHeaderSelector,
					'pageHeaderLink',
					{
						type: 'external',
						label: 'Edit Custom Page Header',
						description: 'Edit the Custom Page Header',
						dialogSelector: '#custom-page-header'
					},
					pageHeaderButtonPos
				);
			}
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

			$( selector ).find( '.bgtfw-multi-edit-button' ).off( 'click' ).on( 'click', function( e ) {
				e.preventDefault();
				e.stopPropagation();
				$( this ).toggleClass( 'expanded' );
				$( this ).parent().toggleClass( 'expanded' );
				$( '.bgtfw-multi-edit-button' ).not( this ).removeClass( 'expanded' );
				$( '.bgtfw-multi-edit-button, .bgtfw-edit-button' ).not( this ).toggleClass( 'hidden' );
				if ( $( this ).parent().hasClass( 'expanded' ) ) {
					self.fixMultiCollisions( $( this ).children( 'div' ) );
				}
			} );

			$( selector ).find( '.bgtfw-edit-item' ).off( 'click' ).on( 'click', function( e ) {
				var controlId   = $( this ).data( 'focus-id' ),
					controlType = $( this ).data( 'focus-type' );
					e.preventDefault();
					e.stopPropagation();
				api[ controlType ]( controlId ).focus( { completeCallback: function() {
					if ( 'control' === controlType ) {
						api.control( controlId ).container.fadeTo( '400', '0.1', function() {
							$( this ).fadeTo( '400', '1' );
						} );
					}
				} } );
			} );
		},

		addSingleButton: function( selector, controlId, control, buttonPosition ) {
			$( selector ).not( '.no-edit-button' ).addClass( 'bgtfw-has-edit single-edit-button' );
			$( selector ).not( '.no-edit-button' ).addClass( buttonPosition.vert + '-button ' + buttonPosition.hor + '-button' );
			$( selector ).not( '.no-edit-button' ).append( '<div class="bgtfw-edit-button" data-focus-type="' + control.type + '" data-focus-id="' + controlId + '" title="' + control.label + '"><div>' );
			$( selector ).not( '.no-edit-button' ).append( '<div class="bgtfw-edit-border-box"></div>' );

			$( selector ).not( '.no-edit-button' ).find( '.bgtfw-edit-button' ).off( 'click' ).on( 'click', function( e ) {
				var controlType = this.dataset.focusType,
					controlId   = this.dataset.focusId;
				if ( 'A' === $( selector ).not( '.no-edit-button' ).prop( 'nodeName' ) ) {
					$( selector ).not( '.no-edit-button' ).on( 'click', function( e ) {
						e.preventDefault();
						e.stopPropagation();
					} );
				}

				e.preventDefault();
				e.stopPropagation();

				if ( 'external' === controlType ) {
					self.showExternalPrompt( controlId, control.dialogSelector );

				} else {
					api[ this.dataset.focusType ]( this.dataset.focusId ).focus( { completeCallback: function() {
						if ( 'control' === controlType ) {
							api.control( controlId ).container.fadeTo( '700', '0.1', function() {
								$( this ).fadeTo( '700', '1' );
							} );
						}
					} } );
				}
			} );
		},
		showExternalPrompt: function( controlId, selector ) {
			var dialogSettings = {
				width: 400,
				resizable: false,
				modal: true,
				classes: {
					'ui-dialog': 'bgtfw-edit-dialog'
				}
			},
			goThereNow = self.buttonParams.goThereNow,
			editPostLink = self.buttonParams[ controlId ];

			/*
			* When clicking on the the page content, the user will be prompted to
			* visit the page editor to edit those items. They will see an option to "Go there now",
			* which brings the user to the page editor. They will also see an option to cancel,
			* which closes the editor. In order to use the appropriate language for "Cancel" and
			* "Go there now", we need to set those language variables as keys. This must be done
			* below rather than in the standard var delaration above.
			*/
			dialogSettings.buttons = {};

			// When "Go there now" is clicked, navigate to the editor for this page.
			dialogSettings.buttons[goThereNow] = function() {
				wp.customize.preview.send( 'edit-post-link', editPostLink );
			};

			wp.customize.preview.bind( 'active', dialogSettings.buttons[goThereNow] );

			dialogSettings.buttons.cancel = function() {
				$( this ).dialog( 'close' );
			};

			$( selector ).dialog( dialogSettings );
		}
	};

	self = BOLDGRID.CustomizerEdit;

} )( jQuery );

BOLDGRID.CustomizerEdit.init();
parent.window.BOLDGRID.CustomizerEdit = BOLDGRID.CustomizerEdit;

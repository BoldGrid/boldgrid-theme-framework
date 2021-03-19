/**
 * This file adds the js necessary to add Edit buttons within the Customizer preview.
 *
 * @summary Add edit buttons to customizer.
 *
 * @since 1.1.6
 * @requires jquery-ui-dialog
 */

/* global _,jQuery,wp, */

var BOLDGRID = BOLDGRID || {};
BOLDGRID.CustomizerEdit = BOLDGRID.CustomizerEdit || {};

( function ( $ ) {

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


		i18n: window.boldgridFrameworkCustomizerEdit || {},

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
			$( document ).ready( self.adjustEmptyMenus );
		},

		_onLoad: function() {
			$( window ).on( 'load', function() {
				$body = $( 'body' );

				self.start();
				self._customizer();

				/*
				* Sometimes, animations on the page can cause buttons to become misaligned. After
				* the buttons have been initialized (immediately above), wait 3 seconds and realign
				* them once more.
				*/
				setTimeout( function() {
					self.placeButtons();
				}, 3000 );
			} );
		},

		/**
		 * Events fired in the WordPress Customizer.
		 *
		 * @memberOf BOLDGRID.CustomizerEdit
		 */
		_customizer: function() {
			var sm;

			sm = $( '#main-menu' );

			if ( sm.length ) {

				// Bind event handling for sub menu hiding and showing.
				sm.on( 'animationend webkitAnimationEnd oanimationend MSAnimationEnd', 'ul', function( e ) {
						BOLDGRID.CustomizerEdit.placeButtons();
						e.stopPropagation();
				});
			}

			/*
			 * As you change your tagline (and other elements), content on the page shifts. When that
			 * content shifts, update the placement of the buttons.
			 */
			wp.customize.preview.bind( 'setting', function() {
				clearTimeout( $.data( this, 'previewBind' ) );
				$.data( this, 'previewBind', setTimeout( function() {
					BOLDGRID.CustomizerEdit.placeButtons();
				}, 400 ) );
			} );

			// When menu partials are refreshed, we need to ensure we update the new container.
			wp.customize.selectiveRefresh.bind( 'partial-content-rendered', function() {
				/* jshint ignore:start */
				BOLDGRID.CustomizerEdit.destroy();
				BOLDGRID.CustomizerEdit.start();
				/* jshint ignore:end */
			} );

			// Listen for sections that might have menus refreshed.
			$( document ).on( 'customize-preview-menu-refreshed', function() {
				/* jshint ignore:start */
				BOLDGRID.CustomizerEdit.destroy();
				BOLDGRID.CustomizerEdit.start();
				/* jshint ignore:end */
			} );

			// Fixed side headers need to readjust edit buttons when header is scrolled.
			$( '.header-fixed .site-header' ).on( 'scroll', _.debounce( function() {
				BOLDGRID.CustomizerEdit.placeButtons();
			}, 400 ) );
		},

		/**
		 * @summary Init the buttons.
		 *
		 * @since 1.1.6
		 */
		start: function() {
			self.$targetHighlight = $( '#target-highlight' );

			self.addButtons();

			// After we have placed all our buttons, take note of the button's outerHeight.
			self.buttonHeight = $( 'button[data-control]:visible' ).first().outerHeight();
			self.buttonWidth = $( 'button[data-control]:visible' ).first().outerWidth();

			self.placeButtons();

			// When the window is resized, wait 0.4 seconds and readjust the placement of our buttons.
			$( window ).resize(function() {
				clearTimeout( $.data( this, 'resizeTimer' ) );

				$.data( this, 'resizeTimer', setTimeout( function() {
					self.placeButtons();
				}, 400 ) );
			});

			/*
			* Navbars can sometimes become collapsed in a hamburger menu and button placement will
			* need adjusting. After a navbar toggle is clicked, wait 0.4 seconds and adjust buttons.
			*/
			$( self.i18n.config.hamburgers ).click( function() {
				setTimeout( self.placeButtons, 400 );
			});

			/*
			* As you scroll down the page, highlighted buttons may disappear.
			*
			* As an example, if you are hovered over the page content and scroll down, the edit button
			* will no longer be visible. In this case, we will fix the button to the top of the page
			* so it's easily accessible.
			*/
			$( window ).scroll( function() {
				self.userIsScrolling = true;

				clearTimeout( $.data( this, 'scrollTimer' ) );

				$.data( this, 'scrollTimer', setTimeout( function() {
					self.userIsScrolling = false;
					self.windowScroll();
				}, 100 ) );
			});
		},

		/**
		 * @summary Add all edit buttons to the DOM.
		 *
		 * @since 1.1.6
		 */
		addButtons: function() {
			var	menus = _.isFunction( api.section ) ? api.section( 'menu_locations' ).controls() : [],
				menuId,
				$emptyMenu = $( '.empty-menu' ),
				widgetAreas;

			// Add our general buttons.
			_( self.i18n.config.buttons.general ).each( function( button ) {

				// Some buttons are only displayed for certain post types.
				if ( button.postType && ! button.postType.includes( self.i18n.postType ) ) {
					return;
				}

				// Ensure the element exists before adding a button for it.
				if ( 0 !== $( button.selector ).length ) {
					self.addButton( button );
				}
			} );

			// Widget locations.
			widgetAreas = self.getSections( 'sidebar-widgets-' );
			_( widgetAreas ).each( function( widgetArea ) {
				var button, selectorId, widgetData = widgetArea.idSplit[1];

				selectorId = ~ widgetData.indexOf( 'header' ) ? '#masthead ' : '';
				selectorId += '#' + widgetData + '.sidebar';

				button = {
					control : widgetArea.id,
					selector : selectorId,
					objectType: 'section',
					isParentColumn: true,
					title : self.i18n.widgetArea
				};

				self.addButton( button );
			} );

			// Menu locations.
			_( menus ).each( function( menu ) {
				var button, selectorId;

				// Define the menuId. It will be used as the selector for our call to addButton.
				menuId = self.getMenuId( menu );
				if ( ! menuId ) {
					return;
				}

				selectorId = $( '#masthead' ).find( '#' + menuId ).length ? '#masthead ' : '';
				selectorId += '#' + menuId;

				// All menu edit buttons will be aligned with the menu itself.
				$( selectorId ).attr( 'data-parent-column', selectorId );

				button = {
					type : 'nav_menu',
					control : menu.setting._value,
					selector : selectorId,
					title: self.i18n.menu
				};

				self.addButton( button );
			} );

			// Empty menu locations.
			_( $emptyMenu ).each(
				function( menu ) {
					var button = {
						type : null,
						control : 'new_menu_name',
						selector : '#' + $( menu ).attr( 'id' ),
						icon : 'dashicons-plus'
					};

					self.addButton( button );
				}
			);
		},

		/**
		 * Ensure empty navbars don't take up space.
		 *
		 * @summary Printing a menu within a nav tag is one approach that several BoldGrid Themes take to print
		 * a navigation menu.
		 *
		 * When taking this approach, an empty menu area should be empty, but it's not. This is because
		 * the nav tag has a navbar class with a min-height of 50px.
		 *
		 * To ensure the empty navbar does not affect the display of the page, override it's min-height
		 * value by adding an 'empty-navbar' class.
		 *
		 * For all empty menus, we will find their parent nav container and add .empty-navbar.
		 *
		 * @since 1.1.10
		 */
		adjustEmptyMenus: function() {
			$( '.empty-menu' ).parent( '.navbar' ).addClass( 'empty-navbar' );
		},

		/**
		 * @summary Handle the click of each edit button.
		 *
		 * @since 1.1.6
		 *
		 * @param object $button A jQuery object.
		 */
		buttonClick: function( $button ) {
			var dataControl = $button.attr( 'data-control' ),
				cancel = parent.window._wpCustomizeControlsL10n.cancel,
				dialogSettings = {
					width: 400,
					resizable: false,
					modal: true
				},
				goThereNow = self.i18n.goThereNow,
				$parent = $( $button.attr( 'data-selector' ) ),
				$collapseSidebar = $( '.collapse-sidebar', parent.document ),
				$previewToggleControls = $( '.customize-controls-preview-toggle .controls', parent.document ),
				$overlay = $( '.wp-full-overlay', parent.document ),
				dataType = $button.attr( 'data-object-type' ),
				navMenuLocation;


			/*
			* When clicking on the page title or the page content, the user will be prompted to
			* visit the page editor to edit those items. They will see an option to "Go there now",
			* which brings the user to the page editor. They will also see an option to cancel,
			* which closes the editor. In order to use the appropriate language for "Cancel" and
			* "Go there now", we need to set those language variables as keys. This must be done
			* below rather than in the standard var delaration above.
			*/
			dialogSettings.buttons = {};

			// When "Go there now" is clicked, navigate to the editor for this page.
			dialogSettings.buttons[goThereNow] = function() {
				wp.customize.preview.send( 'edit-post-link', self.i18n.editPostLink );
			};

			wp.customize.preview.bind( 'active', dialogSettings.buttons[goThereNow] );

			// When "cancel" is clicked, close the dialog.
			dialogSettings.buttons[cancel] = function() {
				$( this ).dialog( 'close' );
			};

			// If the Customizer sidebar is collapsed, expand it.
			if ( 'false' === $collapseSidebar.attr( 'aria-expanded' ) ) {
				$collapseSidebar.click();
			}

			/*
			* If we are in mobile / zoomed in to where only the customizer panel or the preview shows,
			* show the customizer panel.
			*/
			if ( $previewToggleControls.is( ':visible' ) ) {
				$overlay.toggleClass( 'preview-only' );
			}
			// Page title.
			if ( 'entry-title' === dataControl ) {
				if( 'page' === self.i18n.postType ) {
					dataControl = 'bgtfw_pages_title_display';
				} else if ( 'post' === self.i18n.postType ) {
					dataControl = 'bgtfw_posts_title_display';
				}
			}

			// Page content.
			if ( 'entry-content' === dataControl ) {
				$( '#' + dataControl ).dialog( dialogSettings );
				return;

			// Sections.
			} else if ( 'section' === dataType ) {
				api[ dataType ]( dataControl ).focus();
				return;

			// Panels.
			} else if ( 'panel' === dataType ) {
				api[ dataType ]( dataControl ).bgtfwFocus();
				return;

			// Empty menu locations.
			} else if ( 'new_menu_name' === dataControl ) {
				navMenuLocation = $parent.attr( 'data-theme-location' );
				api.control( 'nav_menu_locations[' + navMenuLocation + ']' ).focus();

			// Custom menu locations.
			} else if ( 'nav_menu[0]' === dataControl ) {
				let locationId = $button.attr( 'data-selector' ).match(/\s?#(\S+-\d{3})-menu/)[1];
				locationId = locationId.replace( /(\S+)-(\d{3})/, '$1_$2' );
				api.panel( 'bgtfw_menu_location_' + locationId ).focus();

			// Default.
			} else {
				api.control( dataControl ).focus();
			}

			/*
			* After we have opened the correct pane, bounce the control element to indicate for the
			* user exactly where they can modify the selected item.
			*
			* The timeout allows 0.5 seconds for the pane to actually open and become ready.
			*/
			setTimeout( function() {
				var focused = $( ':focus', parent.document ), initialTransition;

				if ( 'hide_boldgrid_attribution' === dataControl ) {
					// Alternatively read control with regex /^(hide_)+\w*(_attribution)+$/m
					focused = $( 'ul#sub-accordion-section-boldgrid_footer_panel > li[id^="customize-control-hide_"][id$="_attribution"]', parent.document );
				}
				/*jshint eqeqeq:false */
				/*jshint -W041 */
				if ( 'hide_boldgrid_attribution' === dataControl && false == api( 'boldgrid_enable_footer' )() ) {
					/*jshint eqeqeq:true */
					/*jshint +W041 */
					// Alternatively read control with regex /^(hide_)+\w*(_attribution)+$/m
					focused = $( api.control( 'boldgrid_enable_footer' ).selector, parent.document );
				}

				if ( 0 === dataControl.lastIndexOf( 'nav_menu', 0 ) ) {
					focused = $( '.customize-control-nav_menu_name', parent.document );
				}

				// Kirki switches cannot be bounced, target the li instead.
				if ( focused.closest( 'div' ).hasClass( 'switch' ) ) {
					focused = focused.closest( 'li' );
				}

				/*
				* Elements with a transition do not bounce correctly. Below, take note of the initial
				* transition effect. We'll remove the transition, and restore the initial after the
				* element has been bounced.
				*/
				initialTransition = focused.css( 'transition' );

				focused.css( {
					'min-height': focused.outerHeight(),
					'min-width': focused.outerWidth(),
					'transition': 'all 0s'
				} )
				.effect( 'bounce', {
					times: 3,
					distance: 10
				}, 'slow', function() {
					$( this ).css( 'transition', initialTransition );
				} );
			}, 750 );
		},

		/**
		 * @summary Action binded to a button's mouse enter event.
		 *
		 * @since 1.1.6
		 *
		 * @param object $button An edit button.
		 * @param object $parent The element an edit button is assigned to.
		 * @param object The div.col a parent belongs to.
		 */
		buttonMouseEnter: function( $button, $parent, $parentsContainer ) {
			var top = $parent.offset().top,
				containerOffset = $parentsContainer.offset(),
				highlightHeight = $parent.outerHeight( ),
				count = 0,
				withTitleWidth;

			/*
			* Sometimes the $parent itself does not have a height, but its descendants do. Find the
			* tallest descendant and use that height for the hover effect.
			*/
			if ( 0 === highlightHeight ) {
				$parent.find( '*' ).each( function() {
					var childHeight = $( this ).outerHeight( );

					if ( childHeight > highlightHeight ) {
						highlightHeight = childHeight;
					}
				});
			}

			if ( '1' === $button.attr( 'data-fixed-ancestor' ) ) {
				self.$targetHighlight.css( 'position', 'fixed' );
				top = $parent.offset().top - $( window ).scrollTop();
			} else {
				self.$targetHighlight.css( 'position', 'absolute' );
			}

			self.$targetHighlight

				// Stop any existing animations.
				.stop( true )

				// The highlight should be as wide as the col.
				.css( 'width', $parentsContainer.outerWidth() )

				// The highlight should be as tall as the parent element.
				.css( 'height', highlightHeight )

				// The highlight should be aligned top the same as the parent element.
				.css( 'top', top )

				/*
				 * Sometimes an edit button's z-index is changed dynamically so that the button remains
				 * atop of an element (such as a sticky header). In those cases, the $targetHighlight
				 * needs to have the button's z-index as well, otherwise it could fall under the element
				 * (sticky header) and not be seen.
				 */
				.css( 'z-index', $button.css( 'z-index' ) )

				// The highlight should be aligned left with the col.
				.css( 'left', containerOffset.left )
				.css( 'visibility', 'visible' );

			/*
			* An empty widget area may be animating back to 0px. In this case, the current $parent may
			* move position on the page wile the previous animation is rendering.
			*/

			self.targetHighlightTop = setInterval( function() {
				count += 10;

				// Calculate the appropriate top.
				if ( '1' === $button.attr( 'data-fixed-ancestor' ) ) {
					top = $parent.offset().top - $( window ).scrollTop();
				} else {
					top = $parent.offset().top;
				}

				self.$targetHighlight.css( 'top', top  );

				if ( count >= 400 ) {
					clearInterval( self.targetHighlightTop );
				}
			}, 10 );

			// If this button is for adding a new menu, add contextual help.
			if ( $button.hasClass( 'new' ) ) {
				withTitleWidth = parseInt( $button.attr( 'data-with-title-width' ) );

				$button
					.stop( true )
					.html( ' ' + $button.attr( 'data-title' ) )
					.animate({
						'max-width': withTitleWidth + 'px',
						left: self.right( $button ) - withTitleWidth
					}, 400 );
			}

			if ( self.isParentEmpty( $parent ) ) {
				$parent.stop( true ).animate({ height: self.buttonHeight }, 400 );

				self.$targetHighlight
					.stop( true )
					.css( 'visibility', 'visible' )
					.animate({ height: self.buttonHeight }, 400 );
			}
		},

		/**
		 * @summary Action binded to a button's mouse out event.
		 *
		 * @since 1.1.6
		 *
		 * @param object $button An edit button.
		 * @param object $parent The element an edit button is assigned to.
		 */
		buttonMouseLeave: function( $button, $parent ) {

			// If this button is for adding a new menu, remove contextual help on mouse out.
			if ( $button.hasClass( 'new' ) ) {
				$button
					.stop( true )
					.animate({
						'max-width': 30,
						left: $button.attr( 'data-left' )
					}, 400, function() {
						$button.html( '' );
					} );
			}

			clearInterval( self.targetHighlightTop );

			if ( self.isParentEmpty( $parent ) ) {
				$parent.stop( true ).animate( { height: '0px' }, 500 );

				// Avoid animating height from 2px to 0px for half a second.
				if ( self.$targetHighlight.height() <= 2 ) {
					self.$targetHighlight.css( 'visibility', 'hidden' );
				} else {
					self.$targetHighlight.stop( true ).animate({ height:'0px' }, 400, function() {
						self.$targetHighlight.css( 'visibility', 'hidden' );
					});
				}
			} else {
				self.$targetHighlight.css( 'visibility', 'hidden' );
			}
		},

		/**
		 * @summary Determine if two elements collide.
		 *
		 * @since 1.1.6
		 *
		 * @param object $div1 The first element.
		 * @param object $div2 The second element.
		 * @return bool collides Does elem 1 collide with elem 2?
		 */
		collide: function( $element1, $element2 ) {
			var collides = true,
				x1 = parseInt( $element1.attr( 'data-left' ) ),
				x2 = parseInt( $element2.attr( 'data-left' ) ),
				y1 = parseInt( $element1.attr( 'data-top' ) ),
				y2 = parseInt( $element2.attr( 'data-top' ) ),
				h1 = $element1.outerHeight( true ),
				h2 = $element2.outerHeight( true ),
				w1 = $element1.outerWidth( true ),
				w2 = $element2.outerWidth( true ),
				b1 = y1 + h1,
				r1 = x1 + w1,
				b2 = y2 + h2,
				r2 = x2 + w2;

			if ( b1 < y2 || y1 > b2 || r1 < x2 || x1 > r2 ) {
				collides = false;
			}

			return collides;
		},

		/**
		 * @summary Find all elements that collide.
		 *
		 * @since 1.1.6
		 */
		findCollision: function() {
			var buttons = [],
				/*
				* There are inconsistencies when getting the height of the document. Assume that our
				* .site-footer is the last element on the page and use it to calculate the height of
				* the document.
				*/
				$footer = $( '.site-footer' ).last(),
				initialWindowHeight = $footer.offset().top + $footer.outerHeight( true );

			// Reset the collision set back to 1.
			self.buttonCollisionSet = 1;

			// Add all visible edit buttons to the buttons array.
			$( 'button[data-control]:visible' ).each( function() {
				buttons.push( $( this ) );
			});

			// Sort the buttons from top to bottom.
			buttons.sort( self.sortButtonsAsc );

			// Loop through all the buttons, find and fix collsions.
			$.each( buttons, function( index, buttonA ) {
				var $buttonA = $( buttonA );

				// If this is not the last button.
				if ( index < ( buttons.length - 1 ) ) {
					$.each( buttons, function( indexB, buttonB ) {
						var $buttonB = $( buttonB );

						if ( $buttonA.is( $buttonB ) ) {
							return;
						}

						if ( self.collide( $buttonA, $buttonB ) ) {
							self.fixCollision( $buttonA, $buttonB );
						}
					} );
				}
			});

			// Sort the buttons from bottom to top.
			buttons.sort( self.sortButtonsDesc );

			// Prevent any button towards the buttom from extending the document's height.
			_( buttons ).each( function( button ) {
					var topAdjustment, collisionSet, $button = $( button ),
						bottom = parseInt( $button.attr( 'data-top' ) ) + $button.outerHeight( true );

					if ( bottom > initialWindowHeight ) {
						topAdjustment = bottom - initialWindowHeight;
						collisionSet = $button.attr( 'data-collision-set' );

						if ( ! _.isUndefined( collisionSet ) ) {
							$.each( $( '[data-collision-set=' + collisionSet + ']' ), function() {
								var $buttonInSet = $( this ),
									newTop = parseInt( $buttonInSet.attr( 'data-top' ) - topAdjustment );

								$buttonInSet.attr( 'data-top', newTop );
							} );
						}
					}
			});
		},

		/**
		 * @summary Adjust button placement for those 'fixed' that shouldn't be.
		 *
		 * Due to page scrolling, we may find that a button remains fixed when it shouldn't. This
		 * usually happens when the user scrolls up too fast.
		 *
		 * @since 1.1.6
		 */
		fixButtonPlacement: function() {
			var selector = '[data-control][style*="position: fixed"][data-fixed-ancestor="0"]:not(.highlight-button)';

			$( selector ).each( function() {
				var $button = $( this );

				/*
				* Before resetting the button's placement on the page, adjust its position so that the
				* placeButton call below has a smooth transition.
				*/
				$button.css( 'position', 'absolute' ).css( 'top', $( window ).scrollTop() );

				self.placeButton( $button );
				self.findCollision();
			});
		},

		/**
		 * @summary Adjust button positions if they collide.
		 *
		 * @since 1.1.6
		 *
		 * @param object $buttonA The first button in the set.
		 * @param object $buttonB The second button in the set.
		 */
		fixCollision: function( $buttonA, $buttonB ) {

			// The button towards the bottom will be moved lower. Figure out which button is higher.
			var aTop = parseInt( $buttonA.attr( 'data-top' ) ),
				bTop = parseInt( $buttonB.attr( 'data-top' ) ),
				$lowerButton = ( aTop > bTop ? $buttonA : $buttonB ),
				$higherButton = ( $buttonA.is( $lowerButton ) ? $buttonB : $buttonA ),
				collisionSet = $higherButton.attr( 'data-collision-set' );

			$lowerButton.attr( 'data-top', parseInt( $higherButton.attr( 'data-top' ) ) + self.buttonHeight );

			if ( 'undefined' === typeof collisionSet ) {
				collisionSet = self.buttonCollisionSet;
				self.buttonCollisionSet++;
			}

			$lowerButton.attr( 'data-collision-set', collisionSet );
			$higherButton.attr( 'data-collision-set', collisionSet );
		},

		/**
		 * @summary Get a jQuery collection of $element's parents that have a fixed position.
		 *
		 * @since 1.1.6
		 *
		 * @return object $fixedAncestors A jQuery collection.
		 */
		getFixedAncestors: function( $element ) {
			var $fixedAncestors = true === $element.data( 'is-parent-column' ) && 'fixed' === $element.css( 'position' ) ? $element : $element.parents().filter( function() {
				return 'fixed' === $( this ).css( 'position' );
			} );

			return $fixedAncestors;
		},

		/**
		 * @summary Find the id of a menu.
		 *
		 * Pass in a menu location control (Customizer > Menus > View All Locations) and we will find
		 * the id of the element on the page that contains that menu.
		 *
		 * @param object menu A menu location control, found by calling within the Customizer:
		 *                    parent.wp.customize.section( 'menu_locations' ).controls()
		 */
		getMenuId: function( menu ) {
			var data, id;
			data = $( '[data-customize-partial-placement-context*="' + menu.themeLocation.replace( '_', '-' ) + '-menu-location"]' ).data();
			if ( data ) {
				if ( ! _.isEmpty( data.customizePartialPlacementContext.container_id ) ) {
					id = data.customizePartialPlacementContext.container_id;
				} else if ( ! _.isEmpty( data.customizePartialPlacementContext.menu_id ) ) {
					id = data.customizePartialPlacementContext.menu_id;
				} else {
					id = null;
				}
			}

			return id;
		},

		/**
		 * @summary Get array of sections whose id begings with beginning.
		 *
		 * @since 2.0.0
		 *
		 * @param  string beginning
		 * @return array
		 */
		getSections: function( beginning ) {
			var sections = [];

			api.section.each( function ( section ) {
				if( section.id.startsWith( beginning ) ) {
					section.idSplit = section.id.split( beginning );
					sections.push( section );
				}
			} );

			return sections;
		},

		/**
		 * @summary Determine if the top of an element is in view.
		 *
		 * @since 1.1.6
		 *
		 * @param object $element A jQuery object.
		 * @return bool
		 */
		inView: function( $element ) {
			var $window = $( window ),
				docViewTop = $window.scrollTop(),
				docViewBottom = docViewTop + $window.height(),
				elemTop = $element.offset().top,
				elemBottom = elemTop + $element.height();

			return ( elemTop <= docViewBottom && elemBottom >= docViewTop );
		},

		/**
		 * @summary Determine if the parent element is an empty nav area.
		 *
		 * @since 1.1.6
		 *
		 * @param object $parent a jQuery element.
		 * @return bool
		 */
		isParentEmpty: function( $parent ) {
			return ( $parent.hasClass( 'empty-menu' ) );
		},

		/**
		 * @summary Get the parent '.col' of an element.
		 *
		 * Sometimes an element is not within a '.col'. In that case, return the closest '.row'.
		 *
		 * @since 1.1.6
		 *
		 * @param object $element A jQuery object.
		 * @return object An element's parent '.col'.
		 */
		parentColumn: function( $element ) {
			var $col = null,
				found = false,
				selectors = [
					'div[class*=col-]',
					'div[class^=row]',
					'div[class^=container]',
					'div',
					'p'
				],
				// Parent column values, see edit.config.php.
				isParentColumn = $element.attr( 'data-is-parent-column' ),
				parentColumn = $element.attr( 'data-parent-column' );

			// If we've defined the element itself to be the parent column, return it now.
			if( isParentColumn ) {
				return $element;
			}

			// If we've defined a selector for parent column, set as highest priority within selectors.
			if( parentColumn ) {
				selectors.unshift( parentColumn );
			}

			_( selectors ).each( function( selector ) {
				if ( false === found ) {
					$col = $element.closest( selector );

					if ( $col.length > 0 ) {
						found = true;
					}
				}
			});

			return $col;
		},

		/**
		 * @summary Adjust the location of a button on the page.
		 *
		 * @since 1.1.6
		 *
		 * @param object $button An edit button.
		 */
		placeButton: function( $button ) {
			var $parent = $( $button.attr( 'data-selector' ) ),
				parentOffset = $parent.offset(),
				$parentsContainer = self.parentColumn( $parent ),
				moves = parseInt( $button.attr( 'data-moves' ) ),
				duration = ( 0 === moves ? 0 : 400 ),
				$fixedAncestors = self.getFixedAncestors( $parent ),
				dataFixedAncestor = ( $fixedAncestors.length > 0 ? '1' : '0' ),
				position = 'absolute',
				buttonLeft = self.right( $parentsContainer ),
				bodyWidth = $body.outerWidth( true ),
				zIndex,
				top;

			/*
			* The button's data-fixed-ancestor has already been set. Because this function is ran
			* on window resize AND the 'has fixed ancestors' status may change on a window resize,
			* reset the data attribute now.
			*/
			$button.attr( 'data-fixed-ancestor', dataFixedAncestor );

			/*
			* Fix z-index issues.
			*
			* This generally only happens when dealing with 'fixed' ancestors. Sometimes the button's
			* z-index places it below the fixed ancestor, which results in being unable to click the button.
			*
			* IF we have fixed ancestors AND they have a z-index set,
			* THEN update the button's z-index to be one higher.
			* ELSE we may have previously altered the z-index, reset it.
			*/
			if ( $fixedAncestors.length ) {
				zIndex = parseInt( $fixedAncestors.last().css( 'z-index' ) );

				/*
				* The edit button must remain atop the #target-highlight, and its default z-index is
				* setup to do this. Do not adjust the button's z-index if it will be make it lower than
				* its default z-index.
				*/
				if ( Number.isInteger( zIndex ) && zIndex > self.defaultZindex ) {
					$button.css( 'z-index', zIndex + 1 );
				}
			} else {
				$button.css( 'z-index', '' );
			}

			/*
			* Based on the parent's visibility and whether we're showing this button for the first
			* time, determine the appropriate fade effect for the button.
			*/
			if ( $parent.is( '.hidden, .invisible, .screen-reader-text' ) || ! $parent.is( ':visible' ) ) {

				/*
				* This may be the first time we're showing the button, but we don't actually want
				* it to show. For example, a button for a menu that's collapsed in a hamburer
				* show not be show. In this case, hide the button immediately, otherwise fade out.
				*
				*/
				if ( 0 === moves ) {
					$button.hide();
				} else {
					$button.fadeOut();
				}

				return;
			} else {
				$button.fadeIn();
			}

			// Don't allow buttons to go off the screen.
			if ( buttonLeft + self.buttonWidth > bodyWidth ) {
				buttonLeft = bodyWidth - self.buttonWidth;
			}

			/*
			* If we're working with a button with a fixed ancestor, adjust our top and position
			* attributes.
			*/
			if ( '1' === $button.attr( 'data-fixed-ancestor' ) ) {
				position = 'fixed';
				top = parentOffset.top - $( window ).scrollTop();
			} else {
				top = parentOffset.top;
			}

			moves++;

			$button
				.attr( {
					'data-last-animation': 'placeButton',
					'data-moves': moves,
					'data-top': top,
					'data-left': buttonLeft,
					'data-duration': duration
				} )
				.css( 'position', position )
				.removeAttr( 'data-collision-set' );
		},

		/**
		 * @summary Adjust the location of all edit buttons on the page.
		 *
		 * This also will remove buttons found if the selectors don't appear, which
		 * is the case with rendering sidebar areas as partials.
		 *
		 * @since 1.1.6
		 */
		placeButtons: function( ) {
			$( 'button[data-control]' ).each( function() {
				var selector = $( this ).data( 'selector' );
				$( selector ).length ? self.placeButton( $( this ) ) : self.removeButton( $( this ) );
			});

			self.findCollision();
			self.positionByData();
		},

		/**
		 * @summary Position buttons on a page based upon their data-left and data-top attributes.
		 *
		 * @since 1.1.6
		 */
		positionByData: function() {
			$( 'button[data-control]' ).each( function() {
				var $button = $( this );

				// If the button is fixed and is highlighted, don't touch it.
				if ( $button.hasClass( 'highlight-button' ) && 'fixed' === $button.css( 'position' ) ) {
					return;
				}

				// If button is for an empty nav area and is currently being animated, don't touch it.
				if ( $button.hasClass( 'new' ) && $button.is( ':animated' ) ) {
					return;
				}

				$button
					.attr( 'data-last-animation', 'positionByData' )
					.animate({
						top: parseInt( $button.attr( 'data-top' ) ),
						left: parseInt( $button.attr( 'data-left' ) )
						}, parseInt( $button.attr( 'data-duration' ) ) );
			});
		},

		/**
		 * @summary Calculate the 'right' of an element.
		 *
		 * jQuery's offset returns an element's top and left, but not right.
		 *
		 * @since 1.1.2
		 *
		 * @param object $element A jQuery object.
		 * @param bool includeMargin A bool to determine if outerWidth calculation should include margin.
		 * @return string The calculated 'right' of an element.
		 */
		right: function( $element, includeMargin ) {

			// If includeMargin is undefined, set it to false by default.
			includeMargin = ! _.isUndefined( includeMargin ) ? includeMargin : false;

			/*
			 * Sometimes there will be multiple elements in the $element object.
			 * Normally this would return the results of the first element. However,
			 * sometimes that first element is hidden, in which case we don't want the results for
			 * that element, but the next element in line. Therefore we need to skip hidden elements
			 * when there the length of $elements is not 1.
			 */
			if ( 1 === $element.length ) {
				return $element.offset().left + $element.outerWidth( includeMargin );
			} else {
				let left = 0;
				$element.each( function() {
					if ( $( this ).is( ':visible' ) ) {
						left = $( this ).offset().left + $( this ).outerWidth( includeMargin );
						return false;
					}
				} );
				return left;
			}
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
				$parentA = $( $a.attr( 'data-selector' ) );
				parentATop = $parentA.offset().top;

				if ( self.isParentEmpty( $parentA ) ) {
					parentATop -= 1;
				}

				aTop = parentATop;

				$parentB = $( $b.attr( 'data-selector' ) );
				parentBTop = $parentB.offset().top;

				if ( self.isParentEmpty( $parentB ) ) {
					parentBTop -= 1;
				}

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

		/**
		 * @summary Determine if the top of an element is in view.
		 *
		 * @since 1.1.6
		 *
		 * @param object $element A jQuery element.
		 * @return bool
		 */
		topInView: function( $element ) {
			var $window = $( window ),
				docViewTop = $window.scrollTop(),
				docViewBottom = docViewTop + $window.height(),
				elemTop = $element.offset().top;

			/*
			* There are cases in which an element's top is never in view. For example, at certain
			* zooms, BoldGrid-Pavilion's site title (at the top of the page) will have a negative top.
			*
			* In those cases, if we're at the top of the page, return true. Otherwise, run standard
			* calculation to determine if the element's top is in view.
			*/
			if ( 0 === docViewTop && elemTop < 0 ) {
				return true;
			} else {
				return ( elemTop >= docViewTop && elemTop <= docViewBottom );
			}
		},

		/**
		 * Handle the Scroll to Top's edit button.
		 *
		 * The button conditionally hides/shows itself based on settings passed,
		 * so we want to attach the edit button's visibility to mimic that of the
		 * scroll to top button's.  This allows them to hide/show in sync with one
		 * another.
		 *
		 * @since 2.1.4
		 */
		scrollToTopButton: function() {

			// Handles Scroll To Top Edit Button conditionally hiding/showing itself.
			var isAnimating = false, btn = $( '[data-control="bgtfw_scroll_to_top_display"]' );

			if ( btn.length ) {

				// Window offset from top is less than scroll to top's trigger point, so hide.
				if ( window.pageYOffset < _goupOptions.trigger ) {
					if ( 'none' !== btn.css( 'display' ) && false === isAnimating ) {
						isAnimating = true;
						btn[ _goupOptions.entryAnimation + 'Out' ]( function() {
							isAnimating = false;
						} );
					}
				} else {

					// Check if btn has determined it's placement.
					if ( ! btn.data( 'top' ) ) {
						self.placeButton( $( '[data-control="bgtfw_scroll_to_top_display"]' ) );
					}

					// Place with CSS if not already placed properly.
					if ( btn.css( 'top' ) !== btn.data( 'top' ) ) {
						btn.css( 'top', btn.data( 'top' ) );
					}

					// show the button.
					if ( 'hide' !== btn.css( 'display' ) && false === isAnimating ) {
						isAnimating = true;
						btn[ _goupOptions.entryAnimation + 'In' ]( function() {
							isAnimating = false;
						} );
					}
				}
			}
		},

		/**
		 * @summary Adjust a button's position based on whether or not the top of its parent is in view.
		 *
		 * @since 1.1.6
		 */
		windowScroll: function() {
			var $button, $parent, buttonIsFixed,
				selector = '[data-control][style*="position: fixed"][data-fixed-ancestor="0"]:not(.highlight-button)';

			self.scrollToTopButton();

			/*
			* Adjust the position of fixed buttons that are NOT highlighted and need to be snapped back
			* into place.
			*/
			$( selector ).each( function() {
				var $button = $( this ),
					$parent = $( $button.attr( 'data-selector' ) );

				/*
				 * IF the parent's top is in view, move the button DOWN to it.
				 *
				 * ==================================
				 * ==                  (BTN-FIXED) ==
				 * ==                      ▼       ==
				 * ==                      ▼       ==
				 * ==  --------------- (BTN-ABS)   ==
				 * ==  - PARENT      -             ==
				 * ==  -             -             ==
				 * ==  -             -             ==
				 * ==  -             -             ==
				 * ==  ---------------             ==
				 * ==================================
				 *
				 * ELSE, move the button UP to it.
				 *
				 *     --------------- (BTN-ABS)
				 *     - PARENT      -      ^
				 *     -             -      ^
				 * =========================^========
				 * ==  -             - (BTN-FIXED) ==
				 * ==  -             -             ==
				 * ==  -             -             ==
				 * ==  ---------------             ==
				 * ==                              ==
				 * ==                              ==
				 * ==       M                      ==
				 * ==                              ==
				 * ==                              ==
				 * ==================================
				 */

				$button
					.css( 'position', 'absolute' )
					.css( 'top', $( window ).scrollTop() );

				if ( self.topInView( $parent ) ) {
					self.placeButton( $button );
					self.findCollision();
					self.positionByData();
				} else {
					/*
					* Before resetting the button's placement on the page, adjust its position so that the
					* placeButton call below has a smooth transition.
					*
					* We will animate it twice. The first animation is to slowly move it off the screen.
					* The next animation is to place it correctly.
					*/
					$button
						.attr( 'data-last-animation', 'animation-c' )
						.animate({
							top: '-=' + self.buttonHeight
						}, 400, function() {
							self.placeButton( $button );
							self.findCollision();
							self.positionByData();
						});
				}
			});

			// Get the highlighted edit button.
			$button = $( '.highlight-button[data-control][data-fixed-ancestor="0"]' );

			if ( 1 === $button.length ) {
				$parent = $( $button.attr( 'data-selector' ) );

				// Check if the button has fixed positioning.
				buttonIsFixed = ( 'fixed' === $button.css( 'position' ) );

				/*
				 * If the button is fixed and its parent's top is in view, put the button at absolute
				 * positioning and place it.
				 *
				 *           BEFORE SCROLL                        AFTER SCROLL
				 *
				 *     ---------------
				 *     - PARENT      -
				 *     -             -
				 * ================================     ====================================
				 * ==  -          *(BTN-FIXED)*  ==     ==                 *(BTN-FIXED)*  ==
				 * ==  -             -           ==     ==                    v           ==
				 * ==  -             -           ==     ==                    v           ==
				 * ==  -             -           ==     ==  --------------- (BTN-ABS)     ==
				 * ==  -             -           ==     ==  - PARENT      -               ==
				 * ==  -     M       -           ==     ==  -      M      -               ==
				 * ==  -             -           ==     ==  -             -               ==
				 * ==  ---------------           ==     ==  -             -               ==
				 * ==                            ==     ==  ---------------               ==
				 * ================================     ====================================
				 */
				if ( self.topInView( $parent ) && buttonIsFixed ) {
					$button
						.attr( 'data-last-animation', 'animation-b' )
						.css( 'position', 'absolute' )
						.css( 'top', $( window ).scrollTop() );

					self.placeButton( $button );
					self.findCollision();
					self.positionByData();

					return;
				}

				/*
				 * If we have a highlighted button but the button has gone out of view, fix it to the top
				 * of the page.
				 *
				 *           BEFORE SCROLL                        AFTER SCROLL
				 *
				 *                                           --------------- *(BTN-ABS)*
				 *                                           - PARENT      -      v
				 *                                           -             -      v
				 * ==================================    =========================v==========
				 * ==                              ==    ==  -             - *(BTN-FIXED)* ==
				 * ==  --------------- *(BTN-ABS)* ==    ==  -             -               ==
				 * ==  - PARENT      -             ==    ==  -             -               ==
				 * ==  -             -             ==    ==  -        M    -               ==
				 * ==  -             -             ==    ==  -             -               ==
				 * ==  -             -             ==    ==  ---------------               ==
				 * ==  -             -             ==    ==                                ==
				 * ==  -             -             ==    ==                                ==
				 * ==  ---------------             ==    ==                                ==
				 * ==================================    ====================================
				 */
				if ( ! self.topInView( $parent ) && ! buttonIsFixed ) {
					$button
						.stop( true )
						.attr( 'data-last-animation', 'animation-a' )
						.css( 'position', 'fixed' )
						.css( 'top', -1 * $button.outerHeight() )
						.animate({
							top: '0px'
						}, 400 );

					return;
				}

				if ( ! self.inView( $parent ) && buttonIsFixed ) {
					$button
					.animate({
						top: '-=' + self.buttonHeight
					}, 400, function() {
						self.placeButton( $button );
						self.findCollision();
						self.positionByData();
					});

					return;
				}
			}
		},

		/**
		 * @summary Add an edit button to the DOM.
		 *
		 * @since 1.1.6
		 *
		 * @param array config
		 */
		addButton: function( config ) {
			var icon, $parentsContainer,
				type = config.type ? config.type : null,
				$button = $( '<button></button>' ),
				// The element we are controlling, such as '.site-title a' or '.entry-content'.
				$parent = $( config.selector ),
				dataControl = ( null === type ? config.control : type + '[' + config.control + ']' ),
				$fixedAncestors = self.getFixedAncestors( $parent ),
				dataFixedAncestor = ( $fixedAncestors.length > 0 ? '1' : '0' ),
				isEmptyNav = $parent.hasClass( 'empty-menu' );

			// If the button already exists, abort.
			if ( 0 !== $body.find( '[data-selector="' + config.selector + '"]' ).length ) {
				return;
			}

			// If we require text but the element doesn't have any, abort.
			if( config.requireText && '' === $parent.text().replace( /\s/g, '' ) ) {
				return;
			}

			// Allow for custom icons per button. By default, each edit buttion will be a pencil icon.
			icon = _.isUndefined( config.icon ) ? self.i18n.config.defaultIcon : config.icon;
			$button.addClass( icon );

			// Before setting the parentsContainer, set a few attributes that will help.
			if( config.isParentColumn ) {
				$parent.attr( 'data-is-parent-column', true );
			}
			if( config.parentColumn ) {
				$parent.attr( 'data-parent-column', config.parentColumn );
			}
			$parentsContainer = self.parentColumn( $parent );

			// If button is for empty nav area, add 'new' class to use plus icon  instead of pencil.
			if ( isEmptyNav ) {
				$button
					.addClass( 'new' )
					.attr( 'data-title', self.i18n.menu );
			}

			$button
				.attr( {
					'data-control': dataControl,
					'data-selector': config.selector,
					'data-moves': 0,
					'data-fixed-ancestor': dataFixedAncestor
				} );

			// If this button has a title, add it.
			if( config.title ) {
				$button.prop( 'title', config.title );
			}

			if( config.objectType ) {
				$button.attr( 'data-object-type', config.objectType );
			}

			$body.append( $button );

			/*
			* If a button has contextual help, like "New Menu", we need to calculate the width of
			* those buttons when the help text is added.
			*
			* Essentially, we'll add the text, measure the width, then remove the text.
			*/
			if ( isEmptyNav ) {
				$button

					// Allow the button to expand to full width.
					.css( 'max-width', '100%' )

					// Add the help text.
					.html( ' ' + $button.attr( 'data-title' ) )

					// Save the width to 'data-with-title-width'.
					.attr( 'data-with-title-width', $button.outerWidth( true ) )

					// Reset the max width.
					.css( 'max-width', '' )

					// Remove the help text.
					.html( '' );
			}

			// Bind actions to the button's hover.
			$button.hover(

				// Mouse in.
				function() {
					self.buttonMouseEnter( $button, $parent, $parentsContainer );
				},

				// Mouse out.
				function() {
					self.buttonMouseLeave( $button, $parent );
				}
			);

			// Bind actions to the button's click.
			$button.on( 'click', function() {
				self.buttonClick( $button );
			});

			// Bind actions the parent's hover.
			$parent.hover(

				// Mouse in.
				function() {
					$button.addClass( 'highlight-button' );

					/*
					* We just hovered over a parent. If its top is not in view, fix the button to the
					* top of the page.
					*
					* If the user is scrolling, skip this action because the on scroll method will
					* handle the button placement.
					*/
					if ( ! self.topInView( $parent ) && 'fixed' !== $button.css( 'position' ) && false === self.userIsScrolling ) {
						$button
							.attr( 'data-last-animation', 'ancar' )
							.css( 'position', 'fixed' )
							.css( 'top', -1 * $button.outerHeight() )
							.animate({
								top: '0px'
							}, 400 );
					}
				},

				// Mouse out.
				function() {
					$button.removeClass( 'highlight-button' );
				} );
		},
		removeButton: function( el ) {
			el.remove();
		},
		destroy: function() {
			$( 'button[data-control]' ).remove();
		}
	};

	self = BOLDGRID.CustomizerEdit;
} )( jQuery );

BOLDGRID.CustomizerEdit.init();

/**
 * This file adds the js necessary to add Edit buttons within the Customizer preview.
 *
 * @summary Add edit buttons to customizer.
 *
 * @since 1.1.6
 * @requires jquery-ui-dialog
 */

var BOLDGRID = BOLDGRID || {};

/**
 * Add edit buttons to customizer.
 *
 * @since 1.1.6
 */
BOLDGRID.Customizer_Edit = function( $ ) {

	'use strict';

	var self = this, api = parent.wp.customize;

	self.i18n = window.boldgridFrameworkCustomizerEdit || {};

	/**
	 * Keep track of a button's collision set.
	 *
	 * @since 1.1.6
	 * @access public
	 * @property int
	 */
	self.buttonCollisionSet = 1;

	/**
	 * An interval set to place the target-highlight.
	 *
	 * @since 1.1.6
	 * @access public
	 * @property function
	 */
	self.targetHighlightTop = true;

	/**
	 * Is the user scrolling?
	 *
	 * @since 1.1.6
	 * @access public
	 * @property bool
	 */
	self.userIsScrolling = false;

	/**
	 * The height of an edit button.
	 *
	 * @since 1.1.6
	 * @access public
	 * @property int
	 */
	self.buttonHeight = 0;
	self.buttonWidth = 0;

	/**
	 * Default z-index of our edit buttons, as defined in edit.css.
	 *
	 * @since 1.1.6
	 * @access public
	 * @property int
	 */
	self.defaultZindex = 210;

	/**
	 * @summary Add all edit buttons to the DOM.
	 *
	 * @since 1.1.6
	 */
	this.addButtons = function() {
		var	menus = _.isFunction( api.section ) ? api.section( 'menu_locations' ).controls() : [],
			menuId,
			$emptyMenu = $( '.empty-menu' ),
			$emptyWidgetAreas = $( '[data-empty-area="true"]' );

		// Add our general buttons.
		_( self.i18n.buttons.general ).each( function( button ) {

			// Ensure the element exists before adding a button for it.
			if ( 1 === $( button.selector ).length ) {
				self.addButton( null, button.control, button.selector, button.icon );
			}
		} );

		// Widgets.
		$( 'aside.widget' ).each( function() {
			var widgetId = $( this ).attr( 'id' );

			self.addButton( 'sidebar', widgetId, '#' + widgetId, 'dashicons-edit' );
		} );

		// Black Studio TinyMCE.
		$( 'aside[id^="black-studio-tinymce-"]' ).each( function() {
			var $widget = $( this ),
				widgetId = $widget.attr( 'id' ),
				blackStudioId = widgetId.replace( 'black-studio-tinymce-', '' ).trim();

			self.addButton( 'widget_black-studio-tinymce', blackStudioId, '#' + widgetId, 'dashicons-edit' );
		} );

		// Menus.
		_( menus ).each(
			function( menu ) {

				// Define the menuId. It will be used as the selector for our call to addButton.
				menuId = $( '.' + menu.themeLocation.replace( /_/g, '-' ) + '-menu-location' )
					.find( 'ul' ).first().attr( 'id' );

				// If we don't have a menuId, continue.
				if ( _.isUndefined( menuId ) ) {
					return;
				}

				self.addButton( 'nav_menu', menu.setting._value, '#' + menuId, 'dashicons-edit' );
			} );

		// Empty menu locations.
		_( $emptyMenu ).each(
			function( menu ) {
				self.addButton( null, 'new_menu_name', '#' + $( menu ).attr( 'id' ), 'dashicons-plus' );
			} );

		// Empty widget areas.
		_( $emptyWidgetAreas ).each( function( widgetArea ) {
			var dataWidgetArea, widgetAreaId, selector;

			$( widgetArea ).append( '<div class="empty-area"></div>' );
			dataWidgetArea = $( widgetArea ).attr( 'data-widget-area' );
			widgetAreaId = dataWidgetArea.replace( 'accordion-section-sidebar-widgets-', '' );

			/*
			 * This is a nested data selector inside of another data selector,
			 * so we have to use single quotes for the property.
			 */
			selector = '[data-widget-area=\'' + dataWidgetArea + '\']';

			self.addButton( 'sidebars_widgets', widgetAreaId, selector, 'dashicons-plus' );
		} );
	};

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
	this.adjustEmptyMenus = function() {
		$( '.empty-menu' ).parent( '.navbar' ).addClass( 'empty-navbar' );
	};

	/**
	 * @summary Handle the click of each edit button.
	 *
	 * @since 1.1.6
	 *
	 * @param object $button A jQuery object.
	 */
	this.buttonClick = function( $button ) {
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
			navMenuLocation, control;

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

		// Page title or page content.
		if ( 'entry-content' === dataControl || 'entry-title' === dataControl ) {
			$( '#' + dataControl ).dialog( dialogSettings );
			return;

		// Empty widget locations.
		} else if ( 0 === dataControl.lastIndexOf( 'sidebars_widgets', 0 ) ) {
			api.control( dataControl ).focus();

		// Widgets.
		} else if ( 0 === dataControl.lastIndexOf( 'sidebar', 0 ) ) {
			control = dataControl.match( /\[(.*?)\]/ );
			api.Widgets.focusWidgetFormControl( control[ 1 ] );

			/*
			 * Because Black Studio TinyMCE opens another pane, there is no need to bounce anything
			 * in an effort to get the user's attention.
			 */
			if ( control[ 1 ].startsWith( 'black-studio-tinymce-' ) ) {
				return;
			}

		// Empty menu locations.
		} else if ( 'new_menu_name' === dataControl ) {
			navMenuLocation = $parent.attr( 'data-theme-location' );
			api.control( 'nav_menu_locations[' + navMenuLocation + ']' ).focus();

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

			if ( dataControl.startsWith( 'sidebar[' ) ) {
				focused = focused.closest( '.widget' );
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
		}, 500 );
	};

	/**
	 * @summary Action binded to a button's mouse enter event.
	 *
	 * @since 1.1.6
	 *
	 * @param object $button An edit button.
	 * @param object $parent The element an edit button is assigned to.
	 * @param object The div.col a parent belongs to.
	 */
	this.buttonMouseEnter = function( $button, $parent, $parentsContainer ) {
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

		// If this button is for adding a new menu / widget, add contextual help.
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
	};

	/**
	 * @summary Action binded to a button's mouse out event.
	 *
	 * @since 1.1.6
	 *
	 * @param object $button An edit button.
	 * @param object $parent The element an edit button is assigned to.
	 */
	this.buttonMouseLeave = function( $button, $parent ) {

		// If this button is for adding a new menu / widget, remove contextual help on mouse out.
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
	};

	/**
	 * @summary Determine if two elements collide.
	 *
	 * @since 1.1.6
	 *
	 * @param object $div1 The first element.
	 * @param object $div2 The second element.
	 * @return bool collides Does elem 1 collide with elem 2?
	 */
	this.collide = function( $element1, $element2 ) {
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
	};

	/**
	 * @summary Find all elements that collide.
	 *
	 * @since 1.1.6
	 */
	this.findCollision = function() {
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
	};

	/**
	 * @summary Adjust button placement for those 'fixed' that shouldn't be.
	 *
	 * Due to page scrolling, we may find that a button remains fixed when it shouldn't. This
	 * usually happens when the user scrolls up too fast.
	 *
	 * @since 1.1.6
	 */
	this.fixButtonPlacement = function() {
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
	};

	/**
	 * @summary Adjust button positions if they collide.
	 *
	 * @since 1.1.6
	 *
	 * @param object $buttonA The first button in the set.
	 * @param object $buttonB The second button in the set.
	 */
	this.fixCollision = function( $buttonA, $buttonB ) {

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
	};

	/**
	 * @summary Get a jQuery collection of $element's parents that have a fixed position.
	 *
	 * @since 1.1.6
	 *
	 * @return object $fixedAncestors A jQuery collection.
	 */
	this.getFixedAncestors = function( $element ) {
		var $fixedAncestors = $element.parents().filter( function() {
			return 'fixed' === $( this ).css( 'position' );
		});

		return $fixedAncestors;
	};

	/**
	 * @summary Init the buttons.
	 *
	 * @since 1.1.6
	 */
	this.init = function() {
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
		$( '.navbar-toggle' ).click( function() {
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

		/*
		 * As you change your tagline (and other elements), content on the page shifts. When that
		 * content shifts, update the placement of the buttons.
		 */
		wp.customize.preview.bind( 'setting', function() {
			clearTimeout( $.data( this, 'previewBind' ) );

			$.data( this, 'previewBind', setTimeout( function() {
				self.placeButtons();
			}, 400 ) );
		} );
	};

	/**
	 * @summary Determine if the top of an element is in view.
	 *
	 * @since 1.1.6
	 *
	 * @param object $element A jQuery object.
	 * @return bool
	 */
	this.inView = function( $element ) {
		var $window = $( window ),
			docViewTop = $window.scrollTop(),
			docViewBottom = docViewTop + $window.height(),
			elemTop = $element.offset().top,
			elemBottom = elemTop + $element.height();

		return ( elemTop <= docViewBottom && elemBottom >= docViewTop );
	};

	/**
	 * @summary Is the parent element an empty widget / nav area.
	 *
	 * @since 1.1.6
	 *
	 * @param object $parent a jQuery element.
	 * @return bool
	 */
	this.isParentEmpty = function( $parent ) {
		return ( $parent.hasClass( 'empty-menu' ) || $parent.attr( 'data-empty-area' ) );
	};

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
	this.parentColumn = function( $element ) {
		var $col = null,
			found = false,
			selectors = [
				'div[class*=col-]',
				'div[class^=row]',
				'div[class^=container]',
				'div',
				'p'
			];

		_( selectors ).each( function( selector ) {
			if ( false === found ) {
				$col = $element.closest( selector );

				if ( $col.length > 0 ) {
					found = true;
				}
			}
		});

		return $col;
	};

	/**
	 * @summary Adjust the location of a button on the page.
	 *
	 * @since 1.1.6
	 *
	 * @param object $button An edit button.
	 */
	this.placeButton = function( $button ) {
		var $parent = $( $button.attr( 'data-selector' ) ),
			parentOffset = $parent.offset(),
			$parentsContainer = self.parentColumn( $parent ),
			moves = parseInt( $button.attr( 'data-moves' ) ),
			duration = ( 0 === moves ? 0 : 400 ),
			$fixedAncestors = self.getFixedAncestors( $parent ),
			dataFixedAncestor = ( $fixedAncestors.length > 0 ? '1' : '0' ),
			position = 'absolute',
			buttonLeft = self.right( $parentsContainer ),
			bodyWidth = $( 'body' ).outerWidth( true ),
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
		if ( $parent.hasClass( 'hidden' ) || $parent.hasClass( 'invisible' ) || ! $parent.is( ':visible' ) ) {

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
	};

	/**
	 * @summary Adjust the location of all edit buttons on the page.
	 *
	 * @since 1.1.6
	 */
	this.placeButtons = function( ) {
		$( 'button[data-control]' ).each( function() {
			self.placeButton( $( this ) );
		});

		self.findCollision();

		self.positionByData();
	};

	/**
	 * @summary Position buttons on a page based upon their data-left and data-top attributes.
	 *
	 * @since 1.1.6
	 */
	self.positionByData = function() {
		$( 'button[data-control]' ).each( function() {
			var $button = $( this );

			// If the button is fixed and is highlighted, don't touch it.
			if ( $button.hasClass( 'highlight-button' ) && 'fixed' === $button.css( 'position' ) ) {
				return;
			}

			/*
			 * If the button is for an empty nav / widget area and is currently being animated,
			 * don't touch it.
			 */
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
	};

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
	this.right = function( $element, includeMargin ) {

		// If includeMargin is undefined, set it to false by default.
		includeMargin = ! _.isUndefined( includeMargin ) ? includeMargin : false;

		return $element.offset().left + $element.outerWidth( includeMargin );
	};

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
	this.sortButtonsAsc = function( $a, $b ) {
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
	};

	/**
	 * @summary Sort buttons desc based upon location to the top of the document.
	 *
	 * @since 1.1.6
	 *
	 * @param object a A jQuery object.
	 * @param object b A jQuery object.
	 * @return int
	 */
	this.sortButtonsDesc = function( a, b ) {
		var aTop = a.offset().top,
			bTop = b.offset().top;

		if ( bTop < aTop ) {
			return -1;
		} else if ( bTop > aTop ) {
			return 1;
		} else {
			return 0;
		}
	};

	/**
	 * @summary Determine if the top of an element is in view.
	 *
	 * @since 1.1.6
	 *
	 * @param object $element A jQuery element.
	 * @return bool
	 */
	this.topInView = function( $element ) {
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
	};

	/**
	 * @summary Adjust a button's position based on whether or not the top of its parent is in view.
	 *
	 * @since 1.1.6
	 */
	this.windowScroll = function() {
		var $button, $parent, buttonIsFixed,
			selector = '[data-control][style*="position: fixed"][data-fixed-ancestor="0"]:not(.highlight-button)';

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
			 *     - PARENT      -      ▲
			 *     -             -      ▲
			 * =========================▲========
			 * ==  -             - (BTN-FIXED) ==
			 * ==  -             -             ==
			 * ==  -             -             ==
			 * ==  ---------------             ==
			 * ==                              ==
			 * ==                              ==
			 * ==       ☝                      ==
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
			 * If the button is fixed and its parent's top is in view, put the button at absolute positioning
			 * and place it.
			 *
			 *           BEFORE SCROLL                        AFTER SCROLL
			 *
			 *     ---------------
			 *     - PARENT      -
			 *     -             -
			 * ================================     ====================================
			 * ==  -          ☀(BTN-FIXED)☀  ==     ==                ☀(BTN-FIXED)☀  ==
			 * ==  -             -           ==     ==                    ▼          ==
			 * ==  -             -           ==     ==                    ▼          ==
			 * ==  -             -           ==     ==  --------------- (BTN-ABS)    ==
			 * ==  -             -           ==     ==  - PARENT      -              ==
			 * ==  -     ☝       -           ==     ==  -      ☝      -              ==
			 * ==  -             -           ==     ==  -             -              ==
			 * ==  ---------------           ==     ==  -             -              ==
			 * ==                            ==     ==  ---------------              ==
			 * ================================     ===================================
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
			 *                                           --------------- ☀(BTN-ABS)☀
			 *                                           - PARENT      -      ▼
			 *                                           -             -      ▼
			 * ==================================    =========================▼==========
			 * ==                              ==    ==  -             - ☀(BTN-FIXED)☀ ==
			 * ==  --------------- ☀(BTN-ABS)☀ ==    ==  -             -               ==
			 * ==  - PARENT      -             ==    ==  -             -               ==
			 * ==  -             -             ==    ==  -        ☝    -               ==
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
	};

	/**
	 * @summary Add an edit button to the DOM.
	 *
	 * @since 1.1.6
	 *
	 * @param string type The type of element this button controls.
	 * @param string id The id of an element to control.
	 * @param string selector A selector that points to an element this button controls.
	 * @param string icon The icon for the button, added as a class.
	 */
	this.addButton = function( type, id, selector, icon ) {
		var $button = $( '<button></button>' ),
			$parent = $( selector ),
			$parentsContainer = self.parentColumn( $parent ),
			dataControl = ( null === type ? id : type + '[' + id + ']' ),
			$fixedAncestors = self.getFixedAncestors( $parent ),
			dataFixedAncestor = ( $fixedAncestors.length > 0 ? '1' : '0' ),
			isEmptyWidget = $parent.attr( 'data-empty-area' ),
			isEmptyNav = $parent.hasClass( 'empty-menu' );

		// If the button already exists, abort.
		if ( 0 !== $( 'body' ).find( '[data-selector="' + selector + '"]' ).length ) {
			return;
		}

		// Allow for custom icons per button. By default, each edit buttion will be a pencil icon.
		icon = _.isUndefined( icon ) ? 'dashicons-edit' : icon;
		$button.addClass( icon );

		/*
		 * If this button is for an empty widget area or an empty nav area, add a 'new' class to use
		 * a plus sign instead of a pencil icon.
		 */
		if ( isEmptyNav ) {
			$button
				.addClass( 'new' )
				.attr( 'data-title', self.i18n.menu );
		}

		if ( isEmptyWidget ) {
			$button
				.addClass( 'new' )
				.attr( 'data-title', self.i18n.widget );
		}

		$button
			.attr( {
				'data-control': dataControl,
				'data-selector': selector,
				'data-moves': 0,
				'data-fixed-ancestor': dataFixedAncestor
			} );

		$( 'body' ).append( $button );

		/*
		 * If a button has contextual help, like "New Widget" or "New Menu", we need to calculate
		 * the width of those buttons when the help text is added.
		 *
		 * Essentially, we'll add the text, measure the width, then remove the text.
		 */
		if ( isEmptyNav || isEmptyWidget ) {
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
	};

	$( function() {
		self.adjustEmptyMenus();
	} );

	// After the window has loaded, initialize the edit buttons.
	$( window ).on( 'load', function() {
		self.init();

		/*
		 * Sometimes, animations on the page can cause buttons to become misaligned. After
		 * the buttons have been initialized (immediately above), wait 3 seconds and realign
		 * them once more.
		 */
		setTimeout( function() {
			self.placeButtons();
		}, 3000 );
	});
};

new BOLDGRID.Customizer_Edit( jQuery );

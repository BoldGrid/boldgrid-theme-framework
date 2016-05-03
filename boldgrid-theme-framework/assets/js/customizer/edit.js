/**
 * This file adds the js necessary to add Edit buttons within the Customizer preview.
 *
 * @summary Add edit buttons to customizer.
 *
 * @since 1.1.2
 * @requires jquery-ui-dialog
 */

var BOLDGRID = BOLDGRID || {};

/**
 * Add edit buttons to customizer.
 *
 * @since 1.1.2
 */
BOLDGRID.Customizer_Edit = function( $ ) {
	var self = this, api = parent.wp.customize;

	$( function() {
		// After the page has fully load, init the buttons 0.5 seconds later.
		setTimeout( self.init, 500 );
	} );

	/**
	 * @summary Add all edit buttons to the DOM.
	 *
	 * @since 1.1.2
	 */
	this.addButtons = function() {
		var	settings = {
		    	'blogname' : '.site-title a',
		    	'boldgrid_logo_setting' : '.logo-site-title',
		    	'boldgrid_enable_footer' : '.attribution',
		    	'entry-content' : '.entry-content',
		    	'entry-title' : '.entry-title',
		    	'blogdescription' : '.site-description', },
		    keys = _.keys( settings ),
		    menus = api.section( 'menu_locations' ).controls(),
			menuId,
			$emptyMenu = $( '.empty-menu' ),
			$emptyWidgetAreas = $( '[data-empty-area="\'true\'"]' );

		// General Settings.
		_( keys ).each( function( key ) {
			self.addButton( null, key, settings[ key ] );
		} );

		// Widgets.
		$( 'aside.widget' ).each( function() {
			var widgetId = $( this ).attr( 'id' );

			self.addButton( 'sidebar', widgetId, '#' + widgetId );
		} );

		// Black Studio TinyMCE.
		$( 'aside[id^="black-studio-tinymce-"' ).each( function() {
			var $widget = $( this ),
				widgetId = $widget.attr( 'id' ),
				blackStudioId = widgetId.replace( 'black-studio-tinymce-', '' ).trim();

			self.addButton( 'widget_black-studio-tinymce', blackStudioId, '#' + widgetId );
		} );

		// Menus.
		_( menus ).each(
		    function( menu ) {
			    menuId = $( '.' + menu.themeLocation.replace( /_/g, '-' ) + '-menu' )
			        .find( 'ul' ).first().attr( 'id' );

			    // If we don't have a menuId, continue.
			    if( menuId === undefined ) {
			    	return;
			    }

			    self.addButton( 'nav_menu', menu.setting._value, '#' + menuId );
		    } );

		// Empty menu locations.
		_( $emptyMenu ).each(
			function( menu ) {
				self.addButton( null, 'new_menu_name', '#' + $( menu ).attr( 'id' ) );
			} );

		// Empty widget areas.
		_( $emptyWidgetAreas ).each(
			function( widgetArea ) {
				// This is temporary.
				$( widgetArea ).append('<p style="color:#fff;background-color:blue;">Add a widget</p>');

				var dataWidgetArea = $( widgetArea ).attr( 'data-widget-area' );
				var widgetAreaId = dataWidgetArea.replace( 'accordion-section-sidebar-widgets-' , '' );
				var selector = "[data-widget-area='" + dataWidgetArea + "']";

				self.addButton( 'sidebars_widgets', widgetAreaId, selector );
			} );
	};

	/**
	 * @summary Handle the click of each edit button.
	 *
	 * @since 1.1.2
	 */
	this.buttonClick = function( $button ) {
		var dataControl = $button.attr( 'data-control' ),
		cancel = parent.window._wpCustomizeControlsL10n.cancel,
		dialogSettings = {
			width : 400,
			resizable : false,
			modal : true,
		},
		goThereNow = boldgridFrameworkCustomizerEdit.goThereNow;

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
	        parent.window.location = boldgridFrameworkCustomizerEdit.editPostLink;
	    };

	    // When "cancel" is clicked, close the dialog.
	    dialogSettings.buttons[cancel] = function() {
	        $( this ).dialog( 'close' );
	    };

		/*
		 * Take action based upon which edit button is clicked.
		 *
		 * If the user is clicked on the page content or page title, open the dialog described
		 * above. Otherwise, use api to open the appropriate pane in the Customizer controls.
		 */
		if ( 'entry-content' === dataControl || 'entry-title' === dataControl ) {
			$( '#' + dataControl ).dialog( dialogSettings );
			return;
		} else if ( 0 === dataControl.lastIndexOf( 'sidebars_widgets', 0 ) ) {
			api.control( dataControl ).focus();
		} else if ( 0 === dataControl.lastIndexOf( 'sidebar', 0 ) ) {
			var control = dataControl.match( /\[(.*?)\]/ );
			api.Widgets.focusWidgetFormControl( control[ 1 ] );
		} else if ( 'new_menu_name' === dataControl ) {
			// Open the "Menus" panel.
			api.panel( 'nav_menus' ).expand();
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

			if ( 'boldgrid_enable_footer' === dataControl ) {
				focused = $( api.control( dataControl ).selector, parent.document );
			}

			if ( 0 === dataControl.lastIndexOf( 'nav_menu', 0 ) ) {
				focused = $( '.customize-control-nav_menu_name', parent.document );
			}

			if( dataControl.startsWith( 'sidebar[' ) ) {
				focused = focused.closest( '.widget' );
			}

			if ( 'new_menu_name' === dataControl ) {
				focused = $( '.add-menu-toggle', parent.document );
			}

			/*
			 * Elements with a transition do not bounce correctly. Below, take note of the initial
			 * transition effect. We'll remove the transition, and restore the initial after the
			 * element has been bounced.
			 */
			initialTransition = focused.css( 'transition' );

			focused.css( {
			    'min-height' : focused.outerHeight(),
			    'min-width' : focused.outerWidth(),
			    'transition' : 'all 0s'
				} )
			.effect( 'bounce', {
				times : 3,
				distance : 10
				}, 'slow', function() {
					$( this ).css( 'transition', initialTransition );
			} );
		}, 500 );
	};

	/**
	 * @summary Action binded to a button's mouse enter event.
	 *
	 * @since 1.1.2
	 *
	 * @param object $button An edit button.
	 * @param object $parent The element an edit button is assigned to.
	 * @param object The div.col a parent belongs to.
	 */
	this.buttonMouseEnter = function( $button, $parent, $parentsContainer ) {
		var top = $parent.offset().top,
			containerOffset = $parentsContainer.offset(),
			highlightHeight = $parent.outerHeight( );

		/*
		 * Sometimes the $parent itself does not have a height, but its descendants do. Find the
		 * tallest descendant and use that height for the hover effect.
		 */
		if( 0 === highlightHeight ) {
			$parent.find('*').each( function() {
				var childHeight = $( this ).outerHeight( );

				if( childHeight > highlightHeight ) {
					highlightHeight = childHeight;
				}
			});
		}

		if( '1' === $button.attr( 'data-fixed-ancestor' ) ) {
			self.$targetHighlight.css( 'position', 'fixed' );
			top = $parent.offset().top - $( window ).scrollTop();
		} else {
			self.$targetHighlight.css( 'position', 'absolute' );
		}

		self.$targetHighlight
			// The highlight should be as wide as the col.
			.css( 'width', $parentsContainer.outerWidth() )
			// The highlight should be as tall as the parent element.
			.css( 'height', highlightHeight )
			// The highlight should be aligned top the same as the parent element.
			.css( 'top', top )
			// The highlight should be aligned left with the col.
			.css( 'left', containerOffset.left )
			.css( 'visibility', 'visible' );
	};

	/**
	 * @summary Action binded to a button's mouse out event.
	 *
	 * @since 1.1.2
	 */
	this.buttonMouseLeave = function( $parentsContainer ) {
		self.$targetHighlight.css( 'visibility', 'hidden' );
	};

	/**
	 * @summary Adjust button placement for those 'fixed' that shouldn't be.
	 *
	 * Due to page scrolling, we may find that a button remains fixed when it shouldn't. This
	 * usually happens when the user scrolls up too fast.
	 *
	 * @since 1.1.2
	 */
	this.fixButtonPlacement = function() {
		var selector = '[data-control][style*="position: fixed"][data-fixed-ancestor="0"]:not(.highlight-button)';

		$( selector ).each( function() {
			var $button = $( this ),
				$parent = $( $button.attr( 'data-selector' ) ),
				dataControl = $button.attr( 'data-control' );

			/*
			 * Before resetting the button's placement on the page, adjust its position so that the
			 * placeButton call below has a smooth transition.
			 */
			$button.css( 'position', 'absolute' ).css( 'top', $( window ).scrollTop() );

			self.placeButton( $button );
		});
	};

	/**
	 * @summary Get a jQuery collection of $element's parents that have a fixed position.
	 *
	 * @since 1.1.2
	 *
	 * @return object $fixedAncestors A jQuery collection.
	 */
	this.getFixedAncestors = function( $element ) {
		var $fixedAncestors = $element.parents().filter( function() {
	    	return $(this).css( "position" ) === 'fixed';
		});

		return $fixedAncestors;
	};

	/**
	 * @summary Init the buttons.
	 *
	 * @since 1.1.2
	 */
	this.init = function() {
		self.$targetHighlight = $( '#target-highlight' );

		self.addButtons();
		self.placeButtons();

		/*
		 * The footer button always gives us trouble. This usually is due to content on the page
		 * rendering / animating after the page has loaded. Wait 1 second, and then fix the
		 * placement of only the footer button.
		 */
		setTimeout( function() {
			self.placeButton( $('button[data-control=boldgrid_enable_footer]') );
		}, 1000 );

		// When the window is resized, wait 0.4 seconds and readjust the placement of our buttons.
		$( window ).resize(function() {
		    clearTimeout( $.data(this, 'resizeTimer' ) );

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
		$( window ).scroll(function() {
		    clearTimeout( $.data( this, 'scrollTimer' ) );

		    $.data( this, 'scrollTimer', setTimeout( function() {
		    	self.windowScroll();
		    	self.fixButtonPlacement();
		    }, 100 ) );
		});

		/*
		 * As you change your tagline (and other elements), content on the page shifts. When that
		 * content shifts, update the placement of the buttons.
		 */
		wp.customize.preview.bind( 'setting', function( args ) {
			clearTimeout( $.data( this, 'previewBind' ) );

		    $.data( this, 'previewBind', setTimeout( function() {
		    	self.placeButtons();
		    }, 400 ) );
		} );
	};

	/**
	 * @summary Get the parent '.col' of an element.
	 *
	 * Sometimes an element is not within a '.col'. In that case, return the closest '.row'.
	 *
	 * @since 1.1.2
	 */
	this.parentColumn = function( $element ) {
		var $col = null,
			found = false,
			selectors = [
		            'div[class*=col-]',
		            'div[class^=row]',
		            'div[class^=container]',
		            'div',
		            'p',
		    ];

		_( selectors ).each( function( selector ) {
			if( false === found ) {
				$col = $element.closest( selector );

				if( $col.length > 0 ) {
					found = true;
				}
			}
		});

		return $col;
	};

	/**
	 * @summary Adjust the location of a button on the page.
	 *
	 * @since 1.1.2
	 *
	 * @param object $button An edit button.
	 */
	this.placeButton = function ( $button ) {
		var $parent = $( $button.attr( 'data-selector' ) ),
		parentOffset = $parent.offset(),
		$parentsContainer = self.parentColumn( $parent ),
		moves = parseInt( $button.attr( 'data-moves' ) ),
		duration = ( moves === 0 ? 0 : 400 ),
		buttonLeft,
		$fixedAncestors = self.getFixedAncestors( $parent ),
		dataFixedAncestor = ( $fixedAncestors.length > 0 ? '1' : '0' );

		/*
		 * The button's data-fixed-ancestor has already been set. Because this function is ran
		 * on window resize AND the 'has fixed ancestors' status may change on a window resize,
		 * reset the data attribute now.
		 */
		$button.attr( 'data-fixed-ancestor', dataFixedAncestor );

		/*
		 * Based on the parent's visibility and whether we're showing this button for the first
		 * time, determine the appropriate fade effect for the button.
		 */
		if ( $parent.hasClass( 'hidden' ) || ! $parent.is( ':visible' ) ) {

			/*
			 * This may be the first time we're showing the button, but we don't actually want
			 * it to show. For example, a button for a menu that's collapsed in a hamburer
			 * show not be show. In this case, hide the button immediately, otherwise fade out.
			 *
			 */
			if( moves === 0 ) {
				$button.hide();
			} else {
				$button.fadeOut();
			}

			return;
		} else {
			$button.fadeIn();
		}

		buttonLeft = self.right( $parentsContainer );

		// Don't allow buttons to go off the screen.
		if( self.right( $parentsContainer ) + $button.outerWidth( true ) > $('body').outerWidth(true) ) {
			buttonLeft = $('body').outerWidth( true ) - $button.outerWidth( true );
		}

		var position = 'absolute';
		var top = parentOffset.top;

		if( '1' === $button.attr( 'data-fixed-ancestor' ) ) {
			position = 'fixed';
			top = parentOffset.top - $( window ).scrollTop();
		}

		moves++;

		$button
			.attr( 'data-moves', moves )
			.css( 'position', position )
			.animate( {
				top: top,
				left: buttonLeft
			}, duration );
	};

	/**
	 * @summary Adjust the location of all edit buttons on the page.
	 *
	 * @since 1.1.2
	 */
	this.placeButtons = function( ) {
		$( 'button[data-control]' ).each( function() {
			$button = $( this );
			self.placeButton( $button );
		});
	};

	/**
	 * @summary Calculate the 'right' of an element.
	 *
	 * jQuery's offset returns an element's top and left, but not right.
	 *
	 * @since 1.1.2
	 *
	 * @return string The calculated 'right' of an element.
	 */
	this.right = function( $element ) {
		return $element.offset().left + $element.outerWidth();
	};

	/**
	 * @summary Determine if the top of an element is in view.
	 *
	 * @since 1.1.2
	 *
	 * return bool
	 */
	this.topInView = function( $element ) {
	    var $window = $(window),
	    	docViewTop = $window.scrollTop(),
	    	docViewBottom = docViewTop + $window.height(),
	    	elemTop = $element.offset().top,
	    	elemBottom = elemTop + $element.height();

	    /*
	     * There are cases in which an element's top is never in view. For example, at certain
	     * zooms, BoldGrid-Pavilion's site title (at the top of the page) will have a negative top.
	     *
	     * In those cases, if we're at the top of the page, return true. Otherwise, run standard
	     * calculation to determine if the element's top is in view.
	     */
	    if( 0 === docViewTop && elemTop < 0 ) {
	    	return true;
	    } else {
	    	return ( elemTop >= docViewTop && elemTop <= docViewBottom );
	    }
	};

	/**
	 * @summary Adjust a button's position based on whether or not the top of its parent is in view.
	 *
	 * @since 1.1.2
	 */
	this.windowScroll = function() {
		var $button = $( '.highlight-button' ), $parent;

		// If we don't have a highlighted button, abort.
		if( 1 !== $button.length ) {
			return;
		}

		// If this button has a fixed element, then we'ved 'fixed' the button. No need to move the
		// button.
		if( '1' === $button.attr( 'data-fixed-ancestor' ) ) {
			return;
		}

		$parent = $( $button.attr( 'data-selector' ) );

		/*
		 * If the top of the parent IS NOT in view and the button has the standard 'absolute'
		 * positioning, fix the button to the top of the page.
		 *
		 * If the top of the parent IS in view and the button has the non-standard 'fixed'
		 * positioning, set it back to the standard 'absolute' positioning.
		 */
		if( ! self.topInView( $parent ) ) {
			if( 'absolute' === $button.css( 'position' ) ) {
				$button
					.css( 'position', 'fixed' )
					.css( 'top', -1 * $button.outerHeight() )
					.animate({
						top: '0px'
					}, 400);
			}
		} else {
			if( 'fixed' === $button.css( 'position' ) ) {
				$button.css( 'position', 'absolute' )
					.css( 'top', $( window ).scrollTop() )
					.animate({
						top: $parent.offset().top
					}, 400 );
			}
		}
	};

	/**
	 * @summary Add an edit button to the DOM.
	 *
	 * @since 1.1.2
	 */
	this.addButton = function( type, id, selector ) {
		var $button = $( '<button></button>' ),
			$parent = $( selector ),
			$parentsContainer = self.parentColumn( $parent ),
			dataControl = ( null === type ? id : type + '[' + id + ']' ),
			$fixedAncestors = self.getFixedAncestors( $parent ),
			dataFixedAncestor = ( $fixedAncestors.length > 0 ? '1' : '0' );

		// If the button already exists, abort.
		if( 0 !== $( 'body' ).find( '[data-selector="' + selector + '"]' ).length ) {
			return;
		}

		$button
			.attr( 'data-control', dataControl )
			.attr( 'data-selector', selector )
			.attr( 'data-moves', 0 )
			.attr( 'data-fixed-ancestor', dataFixedAncestor);

		$('body').append( $button );

		// Bind actions to the button's hover.
		$button.hover( function() {
			self.buttonMouseEnter( $button, $parent, $parentsContainer );
			}, function() {
				self.buttonMouseLeave( $parentsContainer );
			} );

		// Bind actions to the button's click.
		$button.on( 'click', function() {
			self.buttonClick( $button );
		});

		// Bind actions the parent's hover.
		$parent.hover( function() {
			$button.addClass( 'highlight-button' );
			self.windowScroll();
			}, function() {
				$button.removeClass( 'highlight-button' );
			} );
	};
};

new BOLDGRID.Customizer_Edit( jQuery );
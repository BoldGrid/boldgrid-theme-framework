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
	 * @summary All all edit buttons to the DOM.
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
			menuId;

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
	};

	/**
	 * @summary Handle the click of each edit button.
	 *
	 * @since 1.1.2
	 */
	this.bindEdit = function() {
		$( '[data-control]' ).on( 'click', function() {
			var dataControl = $( this ).attr( 'data-control' ),
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
			if ( 'entry-content' == dataControl || 'entry-title' == dataControl ) {
				$( '#' + dataControl ).dialog( dialogSettings );
				return;
			} else if ( 0 === dataControl.lastIndexOf( 'sidebar', 0 ) ) {
				var control = dataControl.match( /\[(.*?)\]/ );
				api.Widgets.focusWidgetFormControl( control[ 1 ] );
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
				var focused = $( ':focus', parent.document );

				if ( 'boldgrid_enable_footer' === dataControl ) {
					focused = $( api.control( dataControl ).selector, parent.document );
				}

				if ( 0 === dataControl.lastIndexOf( 'nav_menu', 0 ) ) {
					focused = $( '.customize-control-nav_menu_name', parent.document );
				}

				if( dataControl.startsWith( 'sidebar[' ) ) {
					focused = focused.closest( '.widget' );
				}

				focused.css( {
				    'min-height' : focused.outerHeight(),
				    'min-width' : focused.outerWidth(),
				} ).effect( 'bounce', {
				    times : 3,
				    distance : 10
				}, 'slow' );
			}, 500 );
		} );
	};

	/**
	 * @summary Control the behavior of a hovered button.
	 *
	 * @since 1.1.2
	 */
	this.buttonHover = function( $button, $parent, $parentsContainer ) {
		var parentOffset = $parent.offset(),
			containerOffset = $parentsContainer.offset(),
			$parentHighlight = $( '#target-highlight' ),
			highlightHeight = $parent.outerHeight( );

		/*
		 * Sometimes the $parent itself does not have a height, but its decendents do. Find the
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

		// Actions to take when this edit button is hovered.
		$button.hover( function() {
			$parentHighlight
				// The highlight should be as wide as the col.
				.css( 'width', $parentsContainer.outerWidth() )
				// The highlight should be as tall as the parent element.
				.css( 'height', highlightHeight )
				// The highlight should be aligned top the same as the parent element.
				.css( 'top', parentOffset.top )
				// The highlight should be aligned left with the col.
				.css( 'left', containerOffset.left );
		}, function() {
			$parentHighlight
				.css( 'width', '0px' )
				.css( 'height', '0px' );
		});

		// Actions to take when the parent element is hovered.
		$parent.hover( function() {
			$button.addClass( 'highlight-button' );

			self.windowScroll();
		}, function() {
			$button.removeClass( 'highlight-button' );
		} );
	};

	/**
	 * @summary After all buttons are initially loaded, fade them out.
	 *
	 * @since 1.1.2
	 */
	this.fadeOut = function() {
		var $buttons = $( '[data-control]' );

		$buttons
			.css( 'opacity', '0' )
			.animate( {
				opacity : 1
			}, 1000, function() {
				$buttons.animate( {
					opacity : 0.5
					}, 500, function() {
						$buttons.css( 'opacity', '' );
					});
			});

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
		var selector = '[data-control][style*="position: fixed"]:not(.highlight-button)';

		$( selector ).each( function() {
			var $button = $( this ),
				$parent = $( $button.attr( 'data-selector' ) ),
				dataControl = $button.attr( 'data-control' );

			/*
			 * Before resetting the button's placement on the page, adjust its position so that the
			 * placeButtons call below has a smooth transition.
			 */
			$button.css( 'position', 'absolute' ).css( 'top', $( window ).scrollTop() );

			self.placeButtons( '[data-control="' + dataControl + '"]' );
		});
	};

	/**
	 * @summary Init the buttons.
	 *
	 * @since 1.1.2
	 */
	this.init = function() {
		self.addButtons();
		self.placeButtons();
		self.bindEdit();
		self.fadeOut();

		// When the window is resized, wait 0.4 seconds and readust the placement of our buttons.
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
	 * @summary Add an edit button to the page for the given selector.
	 *
	 * @since 1.1.2
	 *
	 * @param string selector A jquery selector used to find the button to place on the page.
	 */
	this.placeButtons = function( selector ) {
		selector = ( selector === undefined ? '[data-control]' : selector );

		$( selector ).each( function() {
			var $button = $( this ),
				$parent = $( $button.attr( 'data-selector' ) ),
				parentOffset = $parent.offset(),
				$parentsContainer = self.parentColumn( $parent ),
				moves = parseInt( $button.attr( 'data-moves' ) ),
				duration = ( moves === 0 ? 0 : 400 ),
				buttonLeft;

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

			moves++;

			$button
				.attr( 'data-moves', moves )
				.css( 'position', 'absolute' )
				.animate( {
					top: parentOffset.top,
					left: buttonLeft
				}, duration );

			/*
			 * On window resize, the previsouly hover functionality does not work as expected
			 * because it is tied to previous positions (those before the browser resize).
			 * Remove previous hover states and add them fresh.
			 */
			$button.unbind( 'mouseenter mouseleave' );
			self.buttonHover( $button, $parent, $parentsContainer );
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
	     * zooms, BoldGrid-Pavilion's site title (at the top of th page) will have a negitive top.
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
			dataControl = ( null === type ? id : type + '[' + id + ']' );

		// If the button already exists, abort.
		if( 0 !== $( 'body' ).find( '[data-selector="' + selector + '"]' ).length ) {
			return;
		}

		$button
			.attr( 'data-control', dataControl )
			.attr( 'data-selector', selector )
			.attr( 'data-moves', 0 );

		$('body').append( $button );

		self.buttonHover( $button, $parent, $parentsContainer );
	};
};

new BOLDGRID.Customizer_Edit( jQuery );
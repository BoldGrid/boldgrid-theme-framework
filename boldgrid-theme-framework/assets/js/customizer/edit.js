/**
 * This file adds the js necessary to add Edit buttons within the Customizer
 * preview.
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
		setTimeout( self.init, 500 );
	} );

	/**
	 * @summary Add edit buttons.
	 *
	 * @since 1.1.2
	 */
	this.addButtons = function() {
		// General Settings.
		var settings = {
		    'blogname' : '.site-title a',
		    'boldgrid_logo_size' : '.logo-site-title',
		    'boldgrid_enable_footer' : '.attribution',
		    'entry-content' : '.entry-content',
		    'entry-title' : '.entry-title',
		    'blogdescription' : '.site-description',
		};

		var keys = _.keys( settings );

		_( keys ).each( function( key ) {
			self.addButton( null, key, settings[ key ] );
		} );

		// Widgets.
		$( 'aside.widget' ).each( function() {
			var widget = $( this ),
			widgetId = widget.attr( 'id' );

			self.addButton( 'sidebar', widgetId, '#' + widgetId );
		} );

		// Black Studio TinyMCE.
		$( 'aside[id^="black-studio-tinymce-"' ).each( function() {
			var widget = $( this ),
				widgetId;

			widgetId = widget.attr( 'id' ).replace( 'black-studio-tinymce-', '' ).trim();
			self.addButton( 'widget_black-studio-tinymce', widgetId, '#' + widget.attr( 'id' ) );
		} );

		// Menus.
		var menuId;

		settings = api.section( 'menu_locations' ).controls();

		_( settings ).each(
		    function( menu ) {
			    menuId = $( '.' + menu.themeLocation.replace( /_/g, '-' ) + '-menu' )
			        .find( 'ul' ).first().attr( 'id' );
			    self.addButton( 'nav_menu', menu.setting._value, '#' + menuId );
		    } );
	};

	/**
	 *
	 */
	this.bindEdit = function() {
		$( '[data-control]' ).on( 'click', function() {
			var dataControl = $( this ).attr( 'data-control' ),
				cancel = boldgridFrameworkCustomizerEdit.cancel,
				dialogSettings = {
					width : 400,
					resizable : false,
					modal : true,},
				goThereNow = boldgridFrameworkCustomizerEdit.goThereNow;

			/*
			 * In order to use the cancel and goThereNow variables as keys, we
			 * are defining them below instead of in the above declaration for
			 * dialogSettings.
			 */
			dialogSettings.buttons = {};
			dialogSettings.buttons[goThereNow] = function() {
		        parent.window.location = boldgridFrameworkCustomizerEdit.editPostLink;
	        };
	        dialogSettings.buttons[cancel] = function() {
		        $( this ).dialog( 'close' );
	        };

			/*
			 * If the user is trying to edit the page title or content, advise
			 * them they need to go to the page n post editor.
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

			setTimeout( function() {
				var focused = $( ':focus', parent.document );

				if ( 'boldgrid_enable_footer' === dataControl ) {
					focused = $( api.control( dataControl ).selector, parent.document );
				}

				if ( 0 === dataControl.lastIndexOf( 'nav_menu', 0 ) ) {
					focused = $( '.customize-control-nav_menu_name', parent.document );
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
	 *
	 */
	this.buttonHover = function( $button, $parent, $parentsContainer ) {
		var parentOffset = $parent.offset(),
			containerOffset,
			$parentHighlight = $( '#target-highlight' ),
			highlightHeight = $parent.outerHeight( );

		containerOffset = $parentsContainer.offset();

		// Sometimes the $parent itself does not have a height, but its decendents do.
		// Find the tallest descendant and use that height for the hover effect.
		if( 0 === highlightHeight ) {
			$parent.find('*').each( function() {
				var $child = $( this ),
					childHeight = $child.outerHeight( );

				if( childHeight > highlightHeight ) {
					highlightHeight = childHeight;
				}
			})
		}

		$button.hover( function() {
			$parentHighlight
				// The highlight should we as wide as the col.
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

		$parent.hover( function() {
			$button.addClass( 'highlight-button' );
		}, function() {
			$button.removeClass( 'highlight-button' );
		} );
	}

	/**
	 *
	 */
	this.fadeOut = function() {
		$( '[data-control]' ).css( 'opacity', '1' ).animate( {
			opacity : 0.5
		}, 2000, function() {
			$( '[data-control]' ).css( 'opacity', '' );
		} );
	};

	/**
	 *
	 */
	this.init = function() {
		self.addButtons();
		self.placeButtons();
		self.bindEdit();
		self.fadeOut();

		//$( window ).resize( self.placeButtons );

		$(window).resize(function() {
		    clearTimeout($.data(this, 'resizeTimer'));
		    $.data(this, 'resizeTimer', setTimeout(function() {
		    	console.log('firing');
		    	self.placeButtons();
		    }, 400));
		});

		$( '.navbar-toggle' ).click( function() {
			setTimeout( self.placeButtons, 400 );
		});
	};

	/**
	 *
	 */
	this.parentColumn = function( $element ) {
		$parentsContainer = $element.closest( 'div[class*=col-]' );

		// Some elements are not contained in a col, but instead in a row.
		if( 0 === $parentsContainer.length ) {
			$parentsContainer = $element.closest( 'div[class=row]' );
		}

		return $parentsContainer;
	}

	/**
	 *
	 */
	this.placeButtons = function() {
		$( '[data-control]' ).each( function() {
			var $button = $( this ),
				$parent = $( $button.attr( 'data-selector' ) ),
				parentOffset = $parent.offset(),
				$parentsContainer = $parent.closest( 'div[class*=col-]' );

			// If the $parent is not visible / hidden, like wedge's site-description, abort.
			if ( $parent.hasClass( 'hidden' ) || ! $parent.is( ':visible' ) ) {
				$button.fadeOut();
				return;
			} else {
				$button.fadeIn();
			}

			// Some elements are not contained in a col, but instead in a row.
			if( 0 === $parentsContainer.length ) {
				$parentsContainer = $parent.closest( 'div[class=row]' );

				if( 0 === $parentsContainer.length ) {
					console.log(' ERROR PARENT COL OR ROW NOT ROUND');
				}
			}

			var buttonLeft = self.right( $parentsContainer );

			// Don't allow buttons to go off the screen.
			if( self.right( $parentsContainer ) + $button.outerWidth( true ) > $('body').outerWidth(true) ) {
				buttonLeft = $('body').outerWidth(true) - $button.outerWidth( true );
			}

			var moves = parseInt( $button.attr( 'data-moves' ) );

			var duration = ( moves === 0 ? 0 : 400 );

			moves++;

			$button.attr( 'data-moves', moves );

			$button.animate({
				top: parentOffset.top,
				left: buttonLeft
			}, duration );



			// On window resize, the previsouly hover functionality does not work as expected
			// because it is tied to previous positions (those before the browser resize). Remove
			// previous hover states and add them fresh.
			$button.unbind( 'mouseenter mouseleave' );
			self.buttonHover( $button, $parent, $parentsContainer );
		});
	}

	/**
	 *
	 */
	this.right = function( $element ) {
		var offset = $element.offset();

		return offset.left + $element.outerWidth();
	}

	/**
	 *
	 */
	this.addButton = function( type, id, selector ) {
		var $button = $( '<button></button>' ),
			$parent = $( selector ),
			$parentsContainer = self.parentColumn( $parent );

		// If the selector is not visible / hidden, like wedge's site-description, abort.
		if ( $parent.hasClass( 'hidden' ) || ! $parent.is( ':visible' ) ) {
			// return;
		}

		if ( null === type ) {
			$button.attr( 'data-control', id );
		} else {
			$button.attr( 'data-control', type + '[' + id + ']' );
		}

		$button.attr( 'data-selector', selector );

		$button.attr( 'data-moves', 0 );

		$('body').append($button);

		self.buttonHover( $button, $parent, $parentsContainer );
	};
}

new BOLDGRID.Customizer_Edit( jQuery );

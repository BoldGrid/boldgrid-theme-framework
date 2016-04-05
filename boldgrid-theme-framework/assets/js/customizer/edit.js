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
	var self = this;

	$( function() {
		self.initEdit();
	} );

	/**
	 * @summary Add edit buttons.
	 *
	 * @since 1.1.2
	 */
	this.addButtons = function() {
		// General Settings.
		var settings = {
		    'blogname' : '.site-title',
		    'blogdescription' : '.site-description',
		    'boldgrid_logo_size' : '.logo-site-title',
		    'boldgrid_enable_footer' : '.site-footer',
		    'entry-content' : '.entry-content',
		    'entry-title' : '.entry-title',
		    'blogdescription' : '.site-description',
		};

		for ( var key in settings ) {
			self.addButton( null, key, $( settings[ key ] ) );
		}

		// Widgets.
		$( 'aside[class^="widget_"' ).each( function() {
			var widget = $( this ), widgetId, widgetType;

			widgetType = widget.attr( 'class' ).split( ' ' )[ 0 ];

			widgetId = widget.attr( 'id' );
			widgetId = widgetId.substring( widgetId.lastIndexOf( '-' ) + 1 );

			self.addButton( widgetType, widgetId, widget );
		} );

		// Black Studio TinyMCE.
		$( 'aside[id^="black-studio-tinymce-"' ).each( function() {
			var widget = $( this ), widgetId;

			widgetId = widget.attr( 'id' ).replace( 'black-studio-tinymce-', '' ).trim();

			self.addButton( 'widget_black-studio-tinymce', widgetId, widget );
		} );

		// Menus.
		var id, location, $menu, settings = parent.wp.customize.settings.settings;
		for ( var key in settings ) {
			// If this is not a nav menu location, abort.
			if ( !key.startsWith( 'nav_menu_locations[' ) ) {
				continue;
			}

			// If there is not a menu id assigned, abort.
			if ( 0 === settings[ key ].value ) {
				continue;
			}

			id = settings[ key ].value;
			location = key.substring( key.lastIndexOf( '[' ) + 1, key.lastIndexOf( ']' ) );

			$menu = $( '#menu-' + location );
			if ( !$menu.is( 'ul' ) ) {
				$menu = $menu.find( 'ul' ).first();
			}

			self.addButton( 'nav_menu', id, $menu );
		}
	}

	/**
	 *
	 */
	this.bindEdit = function() {
		$( '[data-control]' )
		    .on(
		        'click',
		        function() {
			        var dataControl = $( this ).attr( 'data-control' );

			        /*
					 * If the user is trying to edit the page title or content,
					 * advise them they need to go to the page n post editor.
					 */
			        if ( 'entry-content' == dataControl || 'entry-title' == dataControl ) {
				        $( '#' + dataControl )
				            .dialog(
				                {
				                    width : 400,
				                    resizable : false,
				                    modal : true,
				                    buttons : {
				                        'Go there now' : function() {
					                        parent.window.location = boldgridFrameworkCustomizerEdit.editPostLink;
				                        },
				                        Cancel : function() {
					                        $( this ).dialog( "close" );
				                        }
				                    }
				                } );
				        return;
			        }
			        ;

			        parent.wp.customize.control( dataControl ).focus();

			        setTimeout( function() {
				        var focused;

				        /*
						 * After the user clicks edit, we bounce the focused
						 * element to bring it to the user's attention.
						 * Generally, the focus should be on an input element,
						 * such as the site title. Sometimes though, the wrong
						 * element is focused. Determine which item should be
						 * bounced.
						 */
				        switch ( dataControl ) {
					        case 'boldgrid_enable_footer':
						        focused = $( '#customize-control-boldgrid_enable_footer',
						            parent.document );
						        break;
					        default:
						        focused = $( ':focus', parent.document );
				        }

				        if ( dataControl.startsWith( 'nav_menu[' ) ) {
					        focused = $( '.customize-control-nav_menu_name', parent.document );
				        }

				        /*
						 * Bounce the element in the customizer to bring it to
						 * the user's attention. There's an issue with jquery-ui
						 * effects changing padding / size of an element. Fix
						 * this by setting a min width and height.
						 */
				        focused.css( 'min-height', focused.outerHeight() );
				        focused.css( 'min-width', focused.outerWidth() );
				        focused.effect( "bounce", {
					        times : 3
				        }, "slow" );

			        }, 500 );
		        } );

		// In order for the edit button to be positioned correctly with absolute
		// positioning, its parent needs to have relative positioning.
		// $( '[data-control]' ).parent().addClass( 'relative' );

		// // When a button is hovered, highlight its parent.
		// $( '[data-control]' ).each( function() {
		// var $button = $( this ), $parent = $button.parent();
		//
		// $button.hover( function() {
		// $parent.addClass( 'edit-highlight' )
		// }, function() {
		// $parent.removeClass( 'edit-highlight' )
		// } );
		//
		// // When a parent is hovered, highlight it's edit button.
		// $parent.hover( function() {
		// $( this ).find( '> [data-control]' ).addClass( 'highlight-button' );
		// }, function() {
		// $( this ).find( '> [data-control]' ).removeClass( 'highlight-button'
		// );
		// } );
		// } );
	}

	/**
	 *
	 */
	this.fadeOut = function() {
		$( '[data-control]' ).css( 'opacity', '1' ).animate( {
			opacity : 0.5
		}, 2000, function() {
			$( '[data-control]' ).css( 'opacity', '' )
		} );
	}

	/**
	 *
	 */
	this.initEdit = function() {
		self.addButtons();
		self.bindEdit();
		self.fadeOut();
	}

	/**
	 *
	 */
	this.addButton = function( type, id, parent ) {
		// If the target exists but is hidden, like wedge's site-description,
		// abort.
		if ( parent.hasClass( 'hidden' ) ) {
			return;
		}

		var $button, $buttonContainer, $overlay;

		$button = $( '<button></button>' );

		$buttonContainer = $( '<div class="edit-button"></div>' );

		$overlay = $( '<div class="parent-overlay"></div>' );

		// $overlay.css('margin-right', $col.css('margin-right'));
		// $buttonContainer.css('margin-right', parent.css('padding-right'));

		$overlay.css( 'height', parent.outerHeight() );
		$overlay.css( 'margin-left', parent.outerWidth() );

		if ( 0 === parent.outerHeight() ) {
			parent.find( 'div,p' ).each( function() {
				var height = $( this ).height();
				if ( height > 0 ) {
					$overlay.css( 'height', $( this ).height() );
					return false;
				}
			} );
		}
		;

		// $overlay.zIndex( parent.zIndex() - 1 );

		// $overlay = $( '<div class="parent-overlay" style="height:' +
		// parent.outerHeight()
		// + 'px;"></div>' );

		if ( null === type ) {
			$button.attr( 'data-control', id );
		} else {
			$button.attr( 'data-control', type + '[' + id + ']' );
		}

		$buttonContainer.append( $button );
		$buttonContainer.append( $overlay );

		// $overlay.css( 'margin', parent.css( 'margin' ) );

		// button = '<div class="edit-button">' + button + overlay + '</div>';

		parent.before( $buttonContainer );

		// Get the closest column.
		var $col = parent.closest( 'div[class*=col-]' );
		var colOffset = $col.offset();

		if( undefined === colOffset ) {
			console.log('here no col');
			$col = parent.closest( 'div[class*=row]' );
			colOffset = $col.offset();
		}


		var containerOffset = $buttonContainer.offset();

		// Make sure our buttonContainer is flush with the right side of the
		// column.
		if ( undefined !== colOffset ) {
			var bodyWidth = $('body').width();

			var colLeft = colOffset.left;
			var colWidth = $col.outerWidth(true);

			var colRight = bodyWidth - (colLeft + colWidth);
			//console.log('colRight = ' + colRight);
			//console.log($col);

			var conLeft = containerOffset.left;
			var conWidth = $buttonContainer.outerWidth(true);
			var conRight = bodyWidth - (conLeft + conWidth);

			//console.log('conRight = ' + conRight);
			//console.log($buttonContainer);

			$buttonContainer.css('margin-right', (colRight - conRight) );





			//$buttonContainer.css('margin-right', '-=' + (rightPadding));
		}

		parent.hover( function() {
			$button.addClass( 'highlight-button' );
		}, function() {
			$button.removeClass( 'highlight-button' );
		} );

		$overlay.hover( function() {
			$button.addClass( 'highlight-button' );
		}, function() {
			$button.removeClass( 'highlight-button' );
		} );

		$button.hover( function() {
			$overlay.css( 'margin-left', '0px' );
		}, function() {
			$overlay.css( 'margin-left', parent.outerWidth() );
		} )
	}
};

new BOLDGRID.Customizer_Edit( jQuery );
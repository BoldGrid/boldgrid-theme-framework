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
	var self = this,
		api  = parent.wp.customize;

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
			'boldgrid_enable_footer' : '.site-footer .attribution',
			'entry-content' : '.entry-content',
			'entry-title' : '.entry-title',
			'blogdescription' : '.site-description',
		};

		var keys = _.keys( settings );

		_( keys ).each( function( key ) {
			self.addButton( null, key, $( settings[ key ] ) );
		} );


		// Widgets.
		$( 'aside.widget' ).each( function() {
			var widget = $( this ),
				widgetId = widget.attr( 'id' );
			self.addButton( 'sidebar', widgetId, widget );
		} );

		// Black Studio TinyMCE.
		$( 'aside[id^="black-studio-tinymce-"' ).each( function() {
			var widget = $( this ), widgetId;

			widgetId = widget.attr( 'id' ).replace( 'black-studio-tinymce-', '' ).trim();

			self.addButton( 'widget_black-studio-tinymce', widgetId, widget );
		} );


		// Menus.
		var $selector, settings = api.section( 'menu_locations' ).controls();
	
		_( settings ).each( function( menu ) {
			$selector = $( '.' + menu.themeLocation.replace( /_/g, '-' ) + '-menu' ).find( 'ul' ).first();
			self.addButton( 'nav_menu', menu.setting._value, $selector );
		} );
	};


	/**
	 *
	 */
	this.bindEdit = function() {
		$( '[data-control]' ).on( 'click', function() {
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
									$( this ).dialog( 'close' );
								}
							}
						} );
				return;
			} else if ( 0 === dataControl.lastIndexOf( 'sidebar', 0 ) ) {
				var control = dataControl.match(/\[(.*?)\]/);
				api.Widgets.focusWidgetFormControl( control[1] );
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

				focused
					.css({
						'min-height' : focused.outerHeight(),
						'min-width'  : focused.outerWidth(),
					})
					.effect( 'bounce', {
						times : 3,
						distance: 10
					}, 'slow' );

			}, 500 );
		} );

	};

	/**
	 *
	 */
	this.fadeOut = function() {
		$( '[data-control]' ).css( 'opacity', '1' ).animate( {
			opacity : 0.5
		}, 2000, function() {
			$( '[data-control]' ).css( 'opacity', '' )
		} );
	};

	/**
	 *
	 */
	this.initEdit = function() {
		self.addButtons();
		self.bindEdit();
		self.fadeOut();
	};

	/**
	 *
	 */
	this.addButton = function( type, id, parent ) {
		// If the target exists but is hidden, like wedge's site-description,
		// abort.
		if ( parent.hasClass( 'hidden' ) ) {
			return;
		};

		if( ! parent.is(':visible') ) {
			return;
		};

		var $button, $buttonContainer;

		$button = $( '<button></button>' );
//		if( parent.css('margin-top') != parent.css('margin-bottom') ) {
//			$button.css('top', parent.css('margin-top'));
//		}




		$buttonContainer = $( '<div class="edit-button"></div>' );

		// The phone number on pavilion has no height. Add height to it so we can highlight it.
		if ( 0 === parent.outerHeight() ) {
			var maxHeight = 0;
			parent.find( 'div,p,a,span' ).each( function() {
				var height = $( this ).height();
				if ( height > 0 && height > maxHeight ) {
					maxHeight = height;
				}
			} );

			parent.height(maxHeight);
		}
		;


		if ( null === type ) {
			$button.attr( 'data-control', id );
		} else {
			$button.attr( 'data-control', type + '[' + id + ']' );
		}

		$buttonContainer.append( $button );

		parent.before( $buttonContainer );

//		if( $button.height() > parent.outerHeight(true) ) {
//			$button.css('top', '-=' + (parent.outerHeight(true) / 2 ));
//		}

		// Get the closest column.
		var $col = parent.closest( 'div[class*=col-]' );
		var colOffset = $col.offset();

		if( undefined === colOffset ) {
			console.log('here no col');
			console.log(parent);
			$col = parent.closest( 'div[class*=row]' );
			console.log($col);
			colOffset = $col.offset();
			console.log(colOffset);
		}


		var containerOffset = $buttonContainer.offset();

		// Make sure our buttonContainer is flush with the right side of the
		// column.
		if ( undefined !== colOffset ) {
			var bodyWidth = $('body').width();

			var colLeft = colOffset.left;
			var colWidth = $col.outerWidth();

			var colRight = bodyWidth - (colLeft + colWidth);
			//console.log('colRight = ' + colRight);
			//console.log($col);

			var conLeft = containerOffset.left;
			var conWidth = $buttonContainer.outerWidth();
			var conRight = bodyWidth - (conLeft + conWidth);

			//console.log('conRight = ' + conRight);
			//console.log($buttonContainer);

			var adjustment = (colRight - conRight);
//			if( adjustment > 0 ) {
//				adjustment = adjustment * -1;
//			}

			$buttonContainer.css('margin-right', adjustment );

			$buttonContainer.css('margin-left', (colLeft - conLeft) );

			//$buttonContainer.css('margin-right', '-=' + (rightPadding));
		}


		parent.hover( function() {
			$button.addClass( 'highlight-button' );
		}, function() {
			$button.removeClass( 'highlight-button' );
		} );

//		$overlay.hover( function() {
//			$button.addClass( 'highlight-button' );
//		}, function() {
//			$button.removeClass( 'highlight-button' );
//		} );

		$button.hover( function() {
			// $overlay.css( 'margin-left', '0px' );
			parent.addClass('highlight-parent');
		}, function() {
			// $overlay.css( 'margin-left', parent.outerWidth() );
			parent.removeClass('highlight-parent');
		} )
	}
};

new BOLDGRID.Customizer_Edit( jQuery );

var IMHWPB = IMHWPB || {};

/**
 * Add BoldGrid Inspirations Customizer Features.
 *
 * @since 1.1
 */
IMHWPB.Customizer = function( $ ) {
	var self = this;

	$( function() {
		self.initEdit();
	} );

	/**
	 *
	 */
	this.addButtons = function() {
		// General settings.
		var settings = new Array();
		settings = {
		    'blogname' : '.site-title',
		    'blogdescription' : '.site-description',
		    'boldgrid_logo_size' : '.logo-site-title',
		    'boldgrid_enable_footer' : '.site-footer',
		    'show_on_front' : '.entry-content',
		    'blogdescription' : '.entry-title',
		};

		for ( var key in settings ) {
			$( settings[ key ] ).prepend( '<button data-control="' + key + '">EDIT</button>' );

			$( settings[ key ] ).hover( function() {
				$( this ).toggleClass( 'show-edit' );
			} );
		}

		// Widgets.
		$( 'aside[class^="widget_"' ).each( function() {
			var widget = $( this );
			var firstClass = widget.attr( 'class' ).split( ' ' )[ 0 ];
			var spliteId = widget.attr( 'id' ).split( '-' );
			var id = spliteId[ spliteId.length - 1 ];
			var dataControl = firstClass + '[' + id + ']';
			widget.prepend( '<button data-control="' + dataControl + '">EDIT</button>' );
			widget.hover( function() {
				$( this ).toggleClass( 'show-edit' );
			} );
		} );

		// Black Studio TinyMCE.
		$( 'aside[id^="black-studio-tinymce-"' ).each( function() {
			var widget = $( this );
			var id = widget.attr( 'id' ).replace( 'black-studio-tinymce-', '' ).trim();
			var dataControl = 'widget_black-studio-tinymce' + '[' + id + ']';
			widget.prepend( '<button data-control="' + dataControl + '">EDIT</button>' );
			widget.hover( function() {
				$( this ).addClass( 'show-edit' );
			}, function() {
				$( this ).removeClass( 'show-edit' );
			} );
		} );

		// Menus.
		$( 'li[id^="accordion-section-nav_menu"].assigned-to-menu-location', parent.document )
		    .each(
		        function() {
			        var menu = $( this );
			        // Create the widget control id.
			        var id = menu.attr( 'id' ).match( /\[(\d+)\]/ );
			        if ( id ) {
				        id = id[ 1 ];
				        var dataControl = 'nav_menu[' + id + ']';
				        var positionHtml = menu.find( '.menu-in-location' ).html();
				        var position = positionHtml.split( ':' )[ 1 ].replace( ')', '' ).trim();
				        var previewMenu = $( '#menu-' + position );
				        previewMenu.prepend( '<button data-control="' + dataControl
				            + '">EDIT</button>' );
				        previewMenu.hover( function() {
					        $( this ).toggleClass( 'show-edit' );
				        } );
			        }
		        } );
	}

	/**
	 *
	 */
	this.bindEdit = function() {
		$( '[data-control]' )
		    .on(
		        'click',
		        function() {
			        var dataControl = jQuery( this ).attr( 'data-control' );

			        if ( 'show_on_front' === dataControl ) {
				        $( "#dialog" )
				            .dialog(
				                {
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

			        parent.wp.customize.control( dataControl ).focus();

			        setTimeout( function() {
				        var focused;

				        // After the user clicks edit, we bounce the focused
						// element
				        // to bring it to the user's attention. Generally, the
						// focus
				        // should be on an input element, such as the site
						// title.
				        // Sometimes though, the wrong element is focused.
						// Determine
				        // which item should be bounced.
				        switch ( dataControl ) {
					        case 'boldgrid_enable_footer':
						        focused = $( '#customize-control-boldgrid_enable_footer',
						            parent.document );
						        break;
					        case 'show_on_front':
						        focused = $( '#customize-control-show_on_front', parent.document );
						        break;
					        default:
						        focused = $( ':focus', parent.document );
				        }

				        if ( dataControl.startsWith( 'nav_menu[' ) ) {
					        focused = $( '.customize-control-nav_menu_name', parent.document );
				        }

				        focused.css( 'position', 'relative' );

				        focused.animate( {
					        top : -30
				        }, 100 );
				        focused.animate( {
					        top : 0
				        }, 100 );
				        focused.animate( {
					        top : -10
				        }, 100 );
				        focused.animate( {
					        top : 0
				        }, 100 );
				        focused.animate( {
					        top : -5
				        }, 30 );
				        focused.animate( {
					        top : 0
				        }, 30 );
			        }, 500 );
		        } );

		// In order for the edit button to be positioned correctly with absolute
		// positioning, its parent needs to have relative positioning.
		$( '[data-control]' ).parent().addClass( 'relative' );

		// When a button is hovered, highlight its parent.
		$( '[data-control]' ).hover( function() {
			var parent = $( this ).parent();
			parent.addClass( 'edit-highlight' )
		}, function() {
			var parent = $( this ).parent();
			parent.removeClass( 'edit-highlight' );
		} );
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
};

new IMHWPB.Customizer( jQuery );
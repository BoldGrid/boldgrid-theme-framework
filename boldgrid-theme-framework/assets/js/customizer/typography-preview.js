/**
 * Typography Live Preview JavaScript.
 * This file is responsible for displaying customizer output
 * in the previewer window.  Controls are managed from the
 * customizer.typography.controls.js
 */
( function( $ ) {

	'use strict';

	// Check each active nav menu location in customizer.
	var $menus = [],
		$window = $( window ),
		shadowControls = [
		    'logo_shadow_color',
		    'logo_shadow_blur',
		    'logo_shadow_vertical',
		    'logo_shadow_horizontal',
		    'logo_shadow_switch'
		];

	$menus = parent.wp.customize.section ? parent.wp.customize.section( 'menu_locations' ).controls() : [];

	// Loop through nav menus for live preview changes.
	_.each( $menus, function( id ) {

		// Set menu location font size's for live previews without refreshes.
		wp.customize( 'navigation_' + id.themeLocation + '_font_size', function( value ) {
			value.bind( function( to ) {
				$( '.' + id.themeLocation.replace( /_/g, '-' ) + '-menu ul li a' ).css( 'font-size', to + 'px' );
			});
		});

		// Set menu location's text transform for live previews without refreshes.
		wp.customize( 'navigation_' + id.themeLocation + '_text_transform', function( value ) {
			value.bind( function( to ) {
				$( '.' + id.themeLocation.replace( /_/g, '-' ) + '-menu ul li a' ).css( 'text-transform', to );
			});
		});
	});



	// Set font size on main body text live.
	wp.customize( 'body_font_size', function( value ) {
		value.bind( function( to ) {
			$( 'p:not( .site-title ), .site-content, .site-footer' ).css( 'font-size', to + 'px' );
			$( 'blockquote, .mod-blockquote' ).css( 'font-size', to * 1.25 + 'px' );
		});
	});

	// Set font-size of headings live.
	wp.customize( 'bgtfw_headings_typography', function( value ) {
		value.bind( function( to ) {
			var size, base, unit, validUnits, head, style, css = '';
			if ( _.isString( to ) ) {
				to = JSON.parse( to );
			}
			size = to['font-size'];
			base = size.replace( /[^0-9]./gi, '' );
			unit = size.replace( /[^a-z]/gi, '' );

			validUnits = [ 'fr', 'rem', 'em', 'ex', '%', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'ch', 'vh', 'vw', 'vmin', 'vmax' ];

			// Check for valid units.
			if ( ( 'auto' !== unit && 'inherit' !== unit && 'initial' !== unit && -1 === $.inArray( unit, validUnits ) ) || _.isEmpty( unit ) ) {
				unit = 'px';
			}

			// Build CSS.
			_.each( _typographyOptions, function( selector, rule ) {
				var val;
				if ( 'headings' === selector.type ) {
					val = base * selector.amount;
					if ( 'ceil' === selector.round ) {
						val = Math.ceil( val );
					}
					if ( 'floor' === selector.round ) {
						val = Math.floor( val );
					}
					css += rule + '{font-size:' + val + unit + ';}';
				}
			});

			// Set CSS in the innerHTML of stylesheet or create a new stylesheet to append to head.
			if ( !! document.getElementById( 'bgtfw-headings-typography' ) ) {
				document.getElementById( 'bgtfw-headings-typography' ).innerHTML = css;
			} else {
				head = document.head || document.getElementsByTagName( 'head' )[0],
				style = document.createElement( 'style' );
				style.type = 'text/css';
				style.id = 'bgtfw-headings-typography';

				if ( style.styleSheet ) {
					style.styleSheet.cssText = css;
				} else {
					style.appendChild( document.createTextNode( css ) );
				}

				head.appendChild( style );
			}

			// Check if kirki's post-message already applied inline CSS and move our CSS after for override.
			if ( !! document.getElementById( 'kirki-postmessage-bgtfw_headings_typography' ) ) {
				$( '#bgtfw-headings-typography' ).insertAfter( '#kirki-postmessage-bgtfw_headings_typography' );
			}
		});
	});

	// Set logo line height on site title text live.
	wp.customize( 'body_line_height', function( value ) {
		value.bind( function( to ) {
			$( 'p:not( .site-title ), .site-content, .site-footer' ).css( 'line-height', to + '%' );
		});
	});

	// Set navigation font size live.
	wp.customize( 'navigation_text_transform', function( value ) {
		value.bind( function( to ) {
			$( '#site-navigation' ).css( 'text-transform', to );
		});
	});

	// Set font size on navigation live in customizer.
	wp.customize( 'navigation_font_size', function( value ) {
		value.bind( function( to ) {
			$( '#site-navigation' ).css( 'font-size', to + 'px' );
		});
	});


	if ( typeof _bgtfwTypography !== 'undefined' ) {
		$( function() {
			wp.customize.preview.bind( 'setting', function( args ) {
				if ( 'boldgrid_color_palette' !== args[0] ) {
					$window.trigger( 'resize' );
				}
			} );
			updateShadowControls();
		} );

		// Set font size on site title text live.
		wp.customize( 'logo_font_size', function( value ) {
			value.bind( function( to ) {
				$( '.site-title' ).css( 'font-size', to + 'px' );
			});
		});

		// Set text-transform on site title text live.
		wp.customize( 'logo_text_transform', function( value ) {
			value.bind( function( to ) {
				$( '.site-title' ).css( 'text-transform', to );
			});
		});

		// Set text-decoration on site title text live.
		wp.customize( 'logo_text_decoration', function( value ) {
			value.bind( function( to ) {
				$( '.site-title a' ).css( 'text-decoration', to );
			});
		});

		// Set hover text-decoration on site title text live.
		wp.customize( 'logo_text_decoration_hover', function( value ) {
			value.bind( function( to ) {
				$( '.site-title a' ).hover( function(  ) {
					$( this ).css( 'text-decoration', to );
				},
				function(  ) {
					$( this ).css( 'text-decoration', parent.wp.customize( 'logo_text_decoration' ).get() );
				});
			});
		});

		// Set logo margin top on site title text live.
		wp.customize( 'logo_margin_top', function( value ) {
			value.bind( function( to ) {
				$( '.site-title' ).css( 'margin-top', to + 'px' );
			});
		});

		// Set logo margin bottom on site title text live.
		wp.customize( 'logo_margin_bottom', function( value ) {
			value.bind( function( to ) {
				$( '.site-description' ).css( 'margin-bottom', to + 'px' );
			});
		});

		// Set logo horizontal margin on site title text live.
		wp.customize( 'logo_margin_left', function( value ) {
			value.bind( function( to ) {
				$( '.site-title, .site-description' ).css( 'margin-left', to + 'px' );
			});
		});

		// Set logo line height on site title text live.
		wp.customize( 'logo_line_height', function( value ) {
			value.bind( function( to ) {
				$( '.site-title' ).css( 'line-height', to + '%' );
			});
		});

		// Set logo letter spacing on site title text live.
		wp.customize( 'logo_letter_spacing', function( value ) {
			value.bind( function( to ) {
				$( '.site-title' ).css( 'letter-spacing', to + 'px' );
			});
		});

		// Set shadow contols.
		var updateShadowControls = function() {
			var logoShadowColor = wp.customize( 'logo_shadow_color' )(),
				logoShadowBlur = wp.customize( 'logo_shadow_blur' )() + 'px ',
				logoShadowVertical = wp.customize( 'logo_shadow_vertical' )() + 'px ',
				logoShadowHorizontal = wp.customize( 'logo_shadow_horizontal' )() + 'px ',
				logoShadowSwitch = wp.customize( 'logo_shadow_switch' )(),
				cssString = 'none';

			/*jshint eqeqeq:false */
			if ( '0' != logoShadowSwitch ) {
				cssString =
					logoShadowHorizontal +
					logoShadowVertical +
					logoShadowBlur +
					logoShadowColor;
			}

			$( '.site-title' ).css( 'text-shadow', cssString );
		};

		// Bind the change of shadow controls.
		$.each( shadowControls, function() {
			wp.customize( this, function( value ) {
				value.bind( function() {
					updateShadowControls();
				});
			});
		} );
	}

})( jQuery );

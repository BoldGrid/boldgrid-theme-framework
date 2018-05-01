var BOLDGRID = BOLDGRID || {};
BOLDGRID.Customizer = BOLDGRID.Customizer || {};
BOLDGRID.Customizer.Util = BOLDGRID.Customizer.Util || {};

/**
 * Check if the string is valid JSON by the use of regular expressions.
 * This security method is called internally.
 *
 * Examples:
 *
 *  bgtfwIsJSON( 'something' );
 *      // -> false
 *
 *  bgtfwIsJSON( "\"something\"");
 *      // -> true
 *
 *  bgtfwIsJSON( "{ foo: 2 }");
 *      // -> false
 *
 *  bgtfwIsJSON( "{ \"foo\": 2 }" );
 *      // -> true
 *
 * @param {string} string String to test for valid JSON syntax.
 *
 * @return {Boolean} True if string contains valid JSON, false on failure.
 */
BOLDGRID.Customizer.Util.bgtfwIsJSON = function( string ) {

	// Check if string is a string, and not an empty one.
	if ( ! _.isString( string ) || /^\s*$/.test( string ) ) {
		return false;
	}

	// Validate that the string is valid format for being JSON.
	string = string.replace( /\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g, '@' );
	string = string.replace( /"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']' );
	string = string.replace( /(?:^|:|,)(?:\s*\[)+/g, '' );

	return ( /^[\],:{}\s]*$/ ).test( string );
};

/**
 * Parse JSON safely.
 *
 * Ensure that the param passed is valid JSON, and attempt
 * to return the object back.  This returns false in the event
 * that it can't be parsed or isn't valid JSON.
 *
 * @param {string} string String to parse JSON from.
 *
 * @return {mixed} Returns object if JSON was parsed, or false.
 */
BOLDGRID.Customizer.Util.bgtfwParseJSON = function( string ) {
	if ( BOLDGRID.Customizer.Util.bgtfwIsJSON( string ) ) {
		try {
			var data = JSON.parse( string );
			if ( data && _.isObject( data ) ) {
				return data;
			}
		} catch( error ) {
			// console.warn( 'An error retrieving the active color palette occured!', error );
			return false;
		}
	}
	return false;
};

BOLDGRID.Customizer.Util.getInitialPalettes = function( option ) {
	var palette, colors, activePalette;

	// Default value is read from the customizer API if new data is not passed.
	if ( _.isUndefined( option ) ) {
		option = wp.customize( 'boldgrid_color_palette' )();
	}

	// Parse the JSON data.
	palette = BOLDGRID.Customizer.Util.bgtfwParseJSON( option );

	// Ensure it's formatted correctly and get the active palette's colors as an array.
	if ( palette && ! _.isUndefined( palette.state ) && ! _.isUndefined( palette.state.palettes ) ) {

		if ( ! _.isUndefined( palette.state['active-palette'] ) ) {

			activePalette = palette.state['active-palette'];

			if ( ! _.isUndefined( palette.state.palettes[ activePalette ] ) ) {
				activePalette = palette.state.palettes[ activePalette ];

				if ( ! _.isUndefined( activePalette.colors ) ) {
					colors = activePalette.colors;

					// Check if theme included a neutral color, and add it to palette.
					if ( ! _.isUndefined( activePalette['neutral-color'] ) ) {
						colors.push( activePalette['neutral-color'] );
					}

					// Update the wpColorPicker options for initialization.
					if ( colors.length ) {
						return colors;
					}
				}
			}
		}
	}

	// Unable to get the active palette set.
	return false;
};

/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */
( function( $ ) {
	var $body, $custom_styles;

	$body = $( 'body' );
	$custom_styles = $( '#boldgrid-override-styles' );

	$( function() {

		init_values();
		attribution_links();
		setup_post_edit_link();

		$( '.site-description' ).addClass( _typographyClasses );

		// When menu partials are refreshed, we need to ensure we update the new container.
		$( document ).on( 'customize-preview-menu-refreshed', function( e, params ) {
			if ( ! _.isUndefined( BoldGrid ) ) {
				if ( 'main' === params.wpNavMenuArgs.theme_location ) {
					if ( ! _.isUndefined( BoldGrid.standard_menu_enabled ) ) {

						// Initialize SmartMenu on the updated container and params.
						BoldGrid.standard_menu_enabled.init( params.newContainer );
					}
				}
			}
		} );

	} );

	// Site title and description.
	wp.customize( 'blogname', function( value ) {

		// If logo isn't set then bind site-title for live update, otherwise let .site-title update with logo live.
		if ( parent.wp.customize( 'boldgrid_logo_setting' ) && ! parent.wp.customize( 'boldgrid_logo_setting' ).get() ) {
			value.bind( function( to ) {
				$( '.site-title a' ).text( to );
				bgtfw_calculate_layouts();
			} );
		}
	} );

	/**
	 * There's a better way to do this, but I dunno what it is.  This
	 * works for now.  This will just trigger the preview pane to reload
	 * the same page after a user changes their header videos.
	 */
	$.each( ['external_header_video', 'header_video' ], function( index, settingId ) {
		wp.customize( settingId, function( value ) {
			value.bind( function() {
				wp.customize.preview.send( 'url', window.location.href );
			} );
		} );
	} );

	/**
	 * Recalculate layouts on font changes.
	 *
	 * @since 2.0.0
	 */
	$.each( ['bgtfw_site_title_typography', 'bgtfw_tagline_typography' ], function( index, settingId ) {
		wp.customize( settingId, function( value ) {
			value.bind( function( to ) {
				$.each( ['font-size', 'line-height', 'font-family', 'font-weight' ], function( index, control ) {
					if ( value[ control ] !== to[ control ] ) {
						bgtfw_calculate_layouts();
					}
				} );
			} );
		} );
	} );

	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			if ( to ) {
				$( '.site-description' ).text( to ).removeClass( 'invisible' );
			} else {
				$( '.site-description' ).text( '' ).addClass( 'invisible' );
			}
			bgtfw_calculate_layouts();
		} );
	} );

	/**
	 * Force recalculation of layouts helper.
	 *
	 * @since 2.0.0
	 */
	var bgtfw_calculate_layouts = function() {
		if ( !! wp.customize( 'bgtfw_fixed_header' ) ) {
			BoldGrid.header_fixed.calc();
		} else {
			BoldGrid.custom_header.calc();
		}
		$( window ).trigger( 'resize' );
	};

	/**
	 * Update classes for header position layouts.
	 */
	wp.customize( 'bgtfw_header_layout_position', function( value ) {

		// Bind value change.
		value.bind( function( to ) {
			var headerWidth;

			// Add CSS to elements.
			$( 'body' ).removeClass( 'header-top header-left header-right' ).addClass( to );

			if ( to === 'header-left' || to === 'header-right' ) {
				headerWidth = wp.customize( 'bgtfw_header_width' )();
				parent.kirkiSetSettingValue.set( 'bgtfw_header_width', headerWidth );
			}

			// Trigger resize to recalculate header positioning when options are switched.
			bgtfw_calculate_layouts();
		} );
	} );

	/**
	 * Add controls for fixed/not fixed header.
	 */
	wp.customize( 'bgtfw_fixed_header', function( value ) {
		// Bind value change.
		value.bind( function( to ) {
			var body = $( 'body' );
			to ? body.addClass( 'header-fixed' ) : body.removeClass( 'header-fixed' );
			bgtfw_calculate_layouts();
		} );
	} );

	/**
	 * Header layout control.
	 */
	wp.customize( 'bgtfw_header_top_layouts', function( value ) {
		// Bind value change.
		value.bind( function( to ) {
			$( '#masthead' )
				.removeClass( function( index, className ) {
					return ( className.match ( /(^|\s)layout-\S+/g ) || [] ).join( ' ' );
				} ).addClass( to );
				bgtfw_calculate_layouts();
		} );
	} );

	wp.customize( 'bgtfw_header_width', function( value ) {

		// Bind value change.
		value.bind( function() {
			bgtfw_calculate_layouts();
		} );
	} );

	/**
	 * Footer layout control.
	 */
	wp.customize( 'bgtfw_footer_layouts', function( value ) {

		// Bind value change.
		value.bind( function( to ) {
			$( '#colophon' )
				.removeClass( function( index, className ) {
					return ( className.match ( /(^|\s)layout-\S+/g ) || [] ).join( ' ' );
				} ).addClass( to );
		} );
	} );

	/**
	 * Update any of the color control's palettes with correct palette from color picker.
	 */
	wp.customize( 'boldgrid_color_palette', function( value ) {
		value.bind( function( to ) {
			var palettes, colors;

			palettes = parent.$( '.colors-wrapper' );
			colors = BOLDGRID.Customizer.Util.getInitialPalettes( to );

			// Update any palettes on open colorpicker instances.
			if ( colors ) {
				_( palettes ).each( function( palette ) {
					var swatches = $( palette ).find( 'input' );
					_( swatches ).each( function( swatch, index ) {
						var currentVal, newVal, link;
						currentVal = $( swatch ).val();
						newVal = currentVal.substring( 0, currentVal.indexOf( ':' ) + 1 ) + colors[ index ];
						$( swatch ).val( newVal );
						$( swatch ).next().find( '.color-palette-color' ).css( 'background', colors[ index ] ).text( colors[ index ] );

						// Update setting link for control.
						if ( $( swatch ).is( ':checked' ) ) {
							link = $( swatch ).data( 'customize-setting-link' );
							if ( ! _.isUndefined( link ) ) {
								parent.wp.customize( link ).set( newVal );
							}
						}
					} );
				} );
			}
		} );
	} );

	wp.customize( 'custom_theme_css', function( value ) {
		value.bind( function( to ) {
			$( '#boldgrid-custom-css' ).html( to );
		} );
	} );

	/**
	 * Set vertical position of image
	 */
	wp.customize( 'boldgrid_background_vertical_position', function( value ) {
		value.bind( set_background_vertical_position );
	} );

	/**
	 * Set horizontal position of image
	 */
	wp.customize( 'boldgrid_background_horizontal_position', function( value ) {
		value.bind( set_background_horizontal_position );
	} );

	/**
	 * Add a background pattern
	 */
	wp.customize( 'boldgrid_background_pattern', function( value ) {
		value.bind( update_color_and_patterns );
	} );

	/**
	 * Set body background and remove image
	 */
	wp.customize( 'boldgrid_background_color', function( value ) {
		value.bind( update_color_and_patterns );
	} );

	wp.customize( 'bgtfw_header_color', function( value ) {
		value.bind( function() {
			colorOutput( 'bgtfw_header_color', '#masthead' );
		} );
	} );

	wp.customize( 'bgtfw_header_headings_color', function( value ) {
		value.bind( function( to ) {
			headingsColorOutput( to, '#masthead > :not(.bgtfw-widget-row)' );
		} );
	} );

	wp.customize( 'bgtfw_footer_headings_color', function( value ) {
		value.bind( function( to ) {
			headingsColorOutput( to, '.site-footer :not(.bgtfw-widget-row)' );
		} );
	} );

	wp.customize( 'bgtfw_footer_color', function( value ) {
		value.bind( function() {
			colorOutput( 'bgtfw_footer_color', '#colophon' );
		} );
	} );

	/**
	 * When updating background type reset all saved values
	 */
	wp.customize( 'boldgrid_background_type', function( value ) {
		value.bind( background_type_update );
	} );

	wp.customize( 'background_attachment', function( value ) {
		value.bind( background_attachment_update );
	} );

	wp.customize( 'background_image', function( value ) {
		value.bind( background_image_update );
	} );

	wp.customize( 'background_repeat', function( value ) {
		value.bind( background_repeat_update );
	} );

	/**
	 * When updating background type reset all saved values
	 */
	wp.customize( 'boldgrid_background_image_size', function( value ) {
		value.bind( background_size_update );
	} );

	/**
	 * Allow the user to click the post edit link in the customizer and go to the editor
	 */
	var setup_post_edit_link = function() {
		$( '.post-edit-link' ).on( 'click', function() {
			parent.location = $( this ).attr( 'href' );
		});
	};

	var background_type_update = function( to ) {

			if ( to === 'pattern' ) {
				update_color_and_patterns();
			} else {
				$body.css( {
					'background-image': '',
					'background-size': '',
					'background-repeat': '',
					'background-attachment': ''
				} );

				init_values();
			}

			// Remove these styles that should only overwrite on the front end.
			$custom_styles.remove();
	};

	var background_size_update = function( to ) {
		$body.css( 'background-size', to );
	};

	var background_attachment_update = function( to ) {
		if ( to === 'parallax' ) {
			$body.addClass( 'boldgrid-customizer-parallax-effect' );
			$body.css( 'background-attachment', 'fixed' );

			$body.css( {
				'background-position': '0px 0px',
				'background-attachment': 'fixed'
			} );

			$body.data( 'stellar-background-ratio', '0.2' );
			$body.stellar();

			if ( $body.data( 'plugin_stellar' ) ) {
				$body.data( 'plugin_stellar' ).init();
			}
		} else {
			var plugin_stellar_data = $( 'body' ).data( 'plugin_stellar' );
			if ( plugin_stellar_data ) {
				plugin_stellar_data.destroy();
			}

			background_size_update( wp.customize( 'boldgrid_background_image_size' )() );

			$body.css( {
				'background-attachment': to
			});
			set_background_vertical_position();
			set_background_horizontal_position();
			background_repeat_update();

			$body.removeClass( 'boldgrid-customizer-parallax-effect' );
		}
	};

	/**
	 * Set the theme background_vertical_position on the preview frame
	 */
	var set_background_vertical_position = function() {
		var to = wp.customize( 'boldgrid_background_vertical_position' )();
		var cur_background_pos = $body.css( 'background-position' );
		var background_pos = cur_background_pos.split( ' ' )[0] + ' ' + ( to ) * 5 + 'px';
		$body.css( 'background-position', background_pos );
	};

	/**
	 * Set the theme background_horizontal_position on the preview frame
	 */
	var set_background_horizontal_position = function() {
		var to = wp.customize( 'boldgrid_background_horizontal_position' )();
		var cur_background_pos = $body.css( 'background-position' );
		var background_pos =  ( to ) * 5 + 'px' + ' ' +  cur_background_pos.split( ' ' )[1];
		$body.css( 'background-position', background_pos );
	};

	var headingsColorOutput = function( to, section ) {
		var color, selectors = [];

		if ( ! to || to === 'none' ) {
			return;
		}

		if ( _.isUndefined( section ) ) {
			section = 'body';
		}

		color = to.split( ':' ).pop();

		_.each( _typographyOptions, function( value, key ) {
			if ( value.type = 'headings' ) {
				selectors.push( section + ' ' + key );
			}
		} );

		selectors = selectors.join( ', ' );

		$( selectors ).css( 'color', color );
	};

	var colorOutput = function( themeMod, selector ) {
		var colorClassPrefix;
		themeMod = parent.wp.customize( themeMod )();

		if ( ! themeMod || themeMod === 'none' ) {
			themeMod = '';
		}

		colorClassPrefix = themeMod.split( ':' ).shift();

		$( selector ).removeClass( function ( index, css ) {
			return ( css.match( /(^|\s)color-?([\d]|neutral)\-(background|text)\S+/g ) || [] ).join( ' ' );
		} );

		if ( ! ~ colorClassPrefix.indexOf( 'neutral' ) ) {
			colorClassPrefix = colorClassPrefix.replace( '-', '' );
		}

		$( selector ).addClass( colorClassPrefix + '-background-color ' + colorClassPrefix + '-text-default' );
	};

	/**
	 * Set the theme update_color_and_patterns on the preview frame
	 */
	var update_color_and_patterns = function() {
		var backgroundPattern;

		backgroundPattern = wp.customize( 'boldgrid_background_pattern' )();

		if ( ! backgroundPattern ) {
			backgroundPattern = 'none';
		}

		$custom_styles.remove();

		$body.css( {
			'background-image': backgroundPattern,
			'background-size': 'auto',
			'background-repeat': 'repeat',
			'background-attachment': 'scroll'
		});

		colorOutput( 'boldgrid_background_color', 'body' );
	};

	var background_image_update = function( to ) {
		if ( ! to ) {
			to = '';
		} else {
			to = 'url(' + to + ')';
		}

		$body.css( {
			'background-image': to
		});
	};

	var background_repeat_update = function( ) {
		$body.css( {
			'background-repeat': wp.customize( 'background_repeat' )()
		});
	};

	var init_values = function() {
		$( '#custom-background-css' ).remove();
		var bg_attach = wp.customize( 'background_attachment' )(),
			bg_img_size = wp.customize( 'boldgrid_background_image_size' )(),
			bg_type = wp.customize( 'boldgrid_background_type' )();

		update_color_and_patterns();
		if ( bg_type !== 'pattern' ) {
			background_attachment_update( bg_attach );
			background_size_update( bg_img_size );
			var bg_image = wp.customize( 'background_image' )();
			background_image_update( bg_image );
			if ( bg_attach !== 'parallax' ) {
				set_background_vertical_position();
				set_background_horizontal_position();
				background_repeat_update();
			}
		}

	};

	var attribution_links = function() {
		var controls;
		wp.customize.bind( 'ready', _.defer( function() {
			if ( _.isFunction( parent.wp.customize.section ) ) {
				controls = parent.wp.customize.section( 'boldgrid_footer_panel' ).controls();
				_( controls ).each( function( control ) {
					var selector, regex = new RegExp( /^(hide_)+\w*(_attribution)+$/, 'm' );
					if ( regex.test( control.id ) ) {
						if ( !! parseInt( wp.customize( control.id )() ) ) {
							selector = '.' + control.id.replace( 'hide_', '' ).replace( /_/g, '-' ) + '-link';
							$( selector ).addClass( 'hidden' );
						}
					}
				} );
			}
			attributionSeparators();
		} ) );
	};

	/**
	 * Responsible for adjusting the separators in live preview for attribution footer links.
	 */
	var attributionSeparators = function() {
		$( '.attribution-theme-mods > .link' )
			.removeClass( 'no-separator' )
			.filter( ':visible' )
			.last()
			.addClass( 'no-separator' );
	};

	if ( _.isFunction( parent.wp.customize.section ) ) {
		var attributionControls = parent.wp.customize.section( 'boldgrid_footer_panel' ).controls();
		_( attributionControls ).each( function( control ) {
			var selector, regex = new RegExp( /^(hide_)+\w*(_attribution)+$/, 'm' );
			if ( regex.test( control.id ) ) {
				wp.customize( control.id, function( value ) {
					selector = '.' + control.id.replace( /hide_/, '' ).replace( /_/g, '-' ) + '-link';
					value.bind( function( to ) {
						if ( ! to ) {
							$( selector ).removeClass( 'hidden' );
						} else {
							$( selector ).addClass( 'hidden' );
						}
						attributionSeparators();
					});
				});
			}
		});
	}
} )( jQuery );

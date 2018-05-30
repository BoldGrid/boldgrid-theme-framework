import ColorPreview from './color/preview';
import { Preview as GenericPreview } from './generic/preview.js';
import Toggle from './toggle/toggle';
import ToggleValue from './toggle/value';
import ToggleClass from './toggle/class';

const api = wp.customize;
const controlApi = parent.wp.customize;

var BOLDGRID = BOLDGRID || {};
BOLDGRID.Customizer = BOLDGRID.Customizer || {};
BOLDGRID.Customizer.Util = BOLDGRID.Customizer.Util || {};
BOLDGRID.Customizer.Widgets = BOLDGRID.Customizer.Widgets || {};

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
	string = string.replace( /\\(?:["\\/bfnrt]|u[0-9a-fA-F]{4})/g, '@' );
	string = string.replace( /"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+-]?\d+)?/g, ']' );
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
	var data;

	if ( BOLDGRID.Customizer.Util.bgtfwIsJSON( string ) ) {
		try {
			data = JSON.parse( string );
			if ( data && _.isObject( data ) ) {
				return data;
			}
		} catch ( error ) {

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
		option = api( 'boldgrid_color_palette' )();
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
	var $body, $customStyles, colorPreview;

	$body = $( 'body' );
	$customStyles = $( '#boldgrid-override-styles' );

	colorPreview = new ColorPreview().init();
	new GenericPreview().bindEvents();

	$( function() {
		var updateColorAndPatterns,
			headingsColorOutput,
			setupPostEditLink,
			backgroundTypeUpdate,
			backgroundSizeUpdate,
			backgroundAttachmentUpdate,
			setBackgroundVerticalPosition,
			setBackgroundHorizontalPosition,
			backgroundImageUpdate,
			backgroundRepeatUpdate,
			initValues,
			attributionLinks,
			attributionSeparators,
			attributionControls;

		let calc = window.BoldGrid.custom_header.calc;

		/**
		 * Allow the user to click the post edit link in the customizer and go to the editor
		 */
		setupPostEditLink = function() {
			$( '.post-edit-link' ).on( 'click', function() {
				parent.location = $( this ).attr( 'href' );
			} );
		};

		backgroundTypeUpdate = function( to ) {

				if ( 'pattern' === to ) {
					updateColorAndPatterns();
				} else {
					$body.css( {
						'background-image': '',
						'background-size': '',
						'background-repeat': 'no-repeat',
						'background-attachment': ''
					} );

					initValues();
				}

				// Remove these styles that should only overwrite on the front end.
				$customStyles.remove();
		};

		backgroundSizeUpdate = function( to ) {
			$body.css( 'background-size', to );
		};

		backgroundAttachmentUpdate = function( to ) {
			var pluginStellarData;

			if ( 'parallax' === to ) {
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
				pluginStellarData = $( 'body' ).data( 'plugin_stellar' );
				if ( pluginStellarData ) {
					pluginStellarData.destroy();
				}

				backgroundSizeUpdate( api( 'boldgrid_background_image_size' )() );

				$body.css( {
					'background-attachment': to
				} );
				setBackgroundVerticalPosition();
				setBackgroundHorizontalPosition();
				backgroundRepeatUpdate();

				$body.removeClass( 'boldgrid-customizer-parallax-effect' );
			}
		};

		/**
		 * Set the theme background_vertical_position on the preview frame
		 */
		setBackgroundVerticalPosition = function() {
			var to, curBackgroundPos, backgroundPos;
			to = api( 'boldgrid_background_vertical_position' )();
			curBackgroundPos = $body.css( 'background-position' );
			backgroundPos = curBackgroundPos.split( ' ' )[0] + ' ' + ( to ) * 5 + 'px';
			$body.css( 'background-position', backgroundPos );
		};


		/**
		 * Set the theme background_horizontal_position on the preview frame
		 */
		setBackgroundHorizontalPosition = function() {
			var to, curBackgroundPos, backgroundPos;

			to = api( 'boldgrid_background_horizontal_position' )();
			curBackgroundPos = $body.css( 'background-position' );
			backgroundPos = ( to ) * 5 + 'px' + ' ' + curBackgroundPos.split( ' ' )[1];
			$body.css( 'background-position', backgroundPos );
		};

		/**
		 * Handles front-end headings color changes in previewer.
		 *
		 * This will add classes for the headings color changes to elements during a live preview.
		 * The headings makes use of the global _typographyOptions which contains the selectors
		 * defined in the PHP configurations for bgtfw.
		 *
		 * @since 2.0.0
		 *
		 * @param string to       Theme mod value of headings color.
		 * @param string selector CSS selector to apply classes to.
		 */
		headingsColorOutput = function( themeMod, section ) {
			var selectors = [];

			_.each( window._typographyOptions, function( value, key ) {
				if ( 'headings' === value.type ) {
					selectors.push( section + ' ' + key );
				}
			} );

			selectors = selectors.join( ', ' );

			colorPreview.outputColor( themeMod, $( selectors ).not( '.site-description' ), [ 'color' ] );
		};

		/**
		 * Set the theme updateColorAndPatterns on the preview frame
		 */
		updateColorAndPatterns = function() {
			var backgroundPattern;

			backgroundPattern = api( 'boldgrid_background_pattern' )();

			if ( ! backgroundPattern ) {
				backgroundPattern = 'none';
			}

			$customStyles.remove();

			$body.css( {
				'background-image': backgroundPattern,
				'background-size': 'auto',
				'background-repeat': 'repeat',
				'background-attachment': 'scroll'
			} );

			colorPreview.outputColor( 'boldgrid_background_color', 'body', [
				'background-color', 'text-default'
			] );
		};

		backgroundImageUpdate = function( to ) {
			if ( ! to ) {
				to = '';
			} else {
				to = 'url(' + to + ')';
			}

			$body.css( {
				'background-image': to
			} );
		};

		backgroundRepeatUpdate = function( ) {
			$body.css( {
				'background-repeat': api( 'background_repeat' )()
			} );
		};

		initValues = function() {
			var bgAttach, bgImgSize, bgType, bgImage;

			$( '#custom-background-css' ).remove();

			bgType = api( 'boldgrid_background_type' )();

			if ( 'pattern' === bgType ) {
				updateColorAndPatterns();
			}

			if ( 'pattern' !== bgType ) {
				bgAttach = api( 'background_attachment' )();
				bgImgSize = api( 'boldgrid_background_image_size' )();
				backgroundAttachmentUpdate( bgAttach );
				backgroundSizeUpdate( bgImgSize );
				bgImage = api( 'background_image' )();
				backgroundImageUpdate( bgImage );
				if ( 'parallax' !== bgAttach ) {
					setBackgroundVerticalPosition();
					setBackgroundHorizontalPosition();
					backgroundRepeatUpdate();
				}
			}

		};

		attributionLinks = function() {
			var controls;
			api.bind( 'ready', _.defer( function() {
				if ( _.isFunction(  controlApi.section ) ) {
					controls = controlApi.section( 'boldgrid_footer_panel' ).controls();
					_( controls ).each( function( control ) {
						var selector,
							regex = new RegExp( /^(hide_)+\w*(_attribution)+$/, 'm' );

						if ( regex.test( control.id ) ) {
							if ( parseInt( api( control.id )() ) ) {
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
		attributionSeparators = function() {
			$( '.attribution-theme-mods > .link' )
				.removeClass( 'no-separator' )
				.filter( ':visible' )
				.last()
				.addClass( 'no-separator' );
		};

		if ( _.isFunction( controlApi.section ) ) {
			attributionControls = controlApi.section( 'boldgrid_footer_panel' ).controls();
			_( attributionControls ).each( function( control ) {
				var selector,
					regex = new RegExp( /^(hide_)+\w*(_attribution)+$/, 'm' );

				if ( regex.test( control.id ) ) {
					api( control.id, function( value ) {
						selector = '.' + control.id.replace( /hide_/, '' ).replace( /_/g, '-' ) + '-link';
						value.bind( function( to ) {
							$( selector ).toggleClass( 'hidden', to );
							attributionSeparators();
						} );
					} );
				}
			} );
		}

		initValues();
		attributionLinks();
		setupPostEditLink();

		$( '.site-description' ).addClass( window._typographyClasses );

		/**
		 * There's a better way to do this, but I dunno what it is.  This
		 * works for now.  This will just trigger the preview pane to reload
		 * the same page after a user changes their header videos.
		 */
		api( 'external_header_video', 'header_video', function( ...args ) {
			args.map( ( control ) => control.bind( () => api.preview.send( 'url', window.location.href ) ) );
		} );

		/**
		 * Recalculate layouts on font changes.
		 *
		 * @since 2.0.0
		 */
		api( 'bgtfw_site_title_typography', 'bgtfw_tagline_typography', ( ...args ) => {
			args.map( ( control ) => {
				let settings = [ 'font-size', 'line-height', 'font-family', 'font-weight' ];
				let update = ( to ) => {
					_.each( settings, _.once( ( setting ) => control[ setting ] === to[ setting ] || calc() ) );
				};
				control.bind( _.throttle( update, 250 ) );
			} );
		} );

		/**
		 * Update any of the color control's palettes with correct palette from color picker.
		 */
		api( 'boldgrid_color_palette', function( value ) {
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
									controlApi( link ).set( newVal );
								}
							}
						} );
					} );
				}
			} );
		} );

		/* Header Background Color */
		api( 'bgtfw_header_color', function( value ) {
			value.bind( function( to ) {
				var style, head, css, color, alpha;

				colorPreview.outputColor( 'bgtfw_header_color', '#masthead, #navi', [ 'background-color', 'text-default' ] );

				color = to.split( ':' ).pop();

				alpha = parent.net.brehaut.Color( color );

				css = '.header-left #main-menu, .header-right #main-menu { background-color: ' + alpha.setAlpha( 0.7 ).toString() + '; }';
				css += '@media (min-width: 768px) {';
				css += '.sm-clean ul, .sm-clean ul a, .sm-clean ul a:hover, .sm-clean ul a:focus, .sm-clean ul a:active, .sm-clean ul a.highlighted, .sm-clean span.scroll-up, .sm-clean span.scroll-down, .sm-clean span.scroll-up:hover, .sm-clean span.scroll-down:hover { background-color:' + alpha.setAlpha( 0.4 ).toString() + ';}';
				css += '.sm-clean ul { border: 1px solid ' + alpha.setAlpha( 0.4 ).toString() + ';}';
				css += '.sm-clean > li > ul:before, .sm-clean > li > ul:after { border-color: transparent transparent ' + alpha.setAlpha( 0.4 ).toString() + ' transparent;}';
				css += '}';

				// Set CSS in the innerHTML of stylesheet or create a new stylesheet to append to head.
				if ( document.getElementById( 'bgtfw-menu-colors' ) ) {
					document.getElementById( 'bgtfw-menu-colors' ).innerHTML = css;
				} else {
					head = document.head || document.getElementsByTagName( 'head' )[0],
					style = document.createElement( 'style' );
					style.type = 'text/css';
					style.id = 'bgtfw-menu-colors';

					if ( style.styleSheet ) {
						style.styleSheet.cssText = css;
					} else {
						style.appendChild( document.createTextNode( css ) );
					}

					head.appendChild( style );
				}
			} );
		} );

		new ToggleValue( 'header_container', '#navi, #secondary-menu', 'container', calc );
		new ToggleClass( 'bgtfw_fixed_header', 'body', 'header-fixed', calc );

		let layoutFn = ( index, className ) => {
			return ( className.match ( /(^|\s)layout-\S+/g ) || [] ).join( ' ' );
		};

		new ToggleValue( 'bgtfw_footer_layouts', '#colophon', layoutFn );
		new ToggleValue( 'bgtfw_header_top_layouts', '#masthead', layoutFn, calc );

		new Toggle( 'bgtfw_header_width', calc );
		new Toggle( 'bgtfw_header_headings_color', () => headingsColorOutput( 'bgtfw_header_headings_color', '#navi-wrap > :not(.bgtfw-widget-row)' ) );
		new Toggle( 'bgtfw_footer_headings_color', () => headingsColorOutput( 'bgtfw_footer_headings_color', '.site-footer :not(.bgtfw-widget-row)' ) );
		new Toggle( 'blogname', ( to ) => $( '.site-title a' ).text( to ) && calc() );
		new Toggle( 'blogdescription', ( to ) => {
			$( '.site-description' ).text( to ).toggleClass( 'invisible', ! to ) && calc();
		} );

		new Toggle( 'boldgrid_background_vertical_position', setBackgroundVerticalPosition );
		new Toggle( 'boldgrid_background_horizontal_position', setBackgroundHorizontalPosition );
		new Toggle( 'boldgrid_background_pattern', updateColorAndPatterns );
		new Toggle( 'boldgrid_background_color', updateColorAndPatterns );

		new Toggle( 'boldgrid_background_type', backgroundTypeUpdate );
		new Toggle( 'background_attachment', backgroundAttachmentUpdate );
		new Toggle( 'background_image', backgroundImageUpdate );
		new Toggle( 'background_repeat', backgroundRepeatUpdate );
		new Toggle( 'boldgrid_background_image_size', backgroundSizeUpdate );

		let setHeaderPosition = ( to ) => {
			if ( 'header-top' !== to ) {
				parent.kirkiSetSettingValue.set( 'bgtfw_header_width', api( 'bgtfw_header_width' )() );
			}
			calc();
		};

		new ToggleValue( 'bgtfw_header_layout_position', 'body', 'header-top header-left header-right', setHeaderPosition );

		$( document ).on( 'customize-preview-menu-refreshed', function( event, menu ) {
			$.each( menu.newContainer.closest( '[data-is-parent-column]' ), function() {
				BOLDGRID.Customizer.Widgets.updatePartial( $( this ) );
				if ( 'secondary-menu' === menu.container_id && '' !== api( 'bgtfw_header_container' )() ) {
					document.getElementById( menu.container_id ).classList.add( api( 'bgtfw_header_container' )() );
				}
			} );
		} );

		// Reinitialize widgets when our sidebar areas are re-rendered.
		api.selectiveRefresh.bind( 'partial-content-rendered', function( placement ) {

			// Only update when the dynamic widget sidebars are rerendered.
			if ( 'boldgrid_header_widgets' === placement.partial.id || 'boldgrid_footer_widgets' === placement.partial.id ) {
				BOLDGRID.Customizer.Widgets.updatePartial( placement.container );
			}

			calc();
		} );

		BOLDGRID.Customizer.Widgets.updatePartial = function( selector ) {

			// Comment reply link.
			selector.find( '.comment-reply-link' )
				.addClass( 'btn button-primary color1-text-contrast' )
				.css( 'transition', 'all .5s' );

			// The WordPress Default Widgets.
			selector.find( '.widget_rss ul' ).addClass( 'media-list' );
			selector.find( '.widget_meta ul, .widget_recent_entries ul, .widget_archive ul, .widget_categories ul, .widget_nav_menu ul, .widget_pages ul' ).addClass( 'nav' );
			selector.find( '.widget_recent_comments ul#recentcomments' )
				.css( { 'list-style': 'none', 'padding-left': '0' } );
			selector.find( '.widget_recent_comments ul#recentcomments li' ).css( 'padding', '5px 15px' );
			selector.find( '.sidebar select, select[name="archive-dropdown"]' ).addClass( 'form-control' );
			selector.find( '.sidebar .button' ).removeClass( 'button' ).addClass( 'btn button-primary' );

			// WooCommerce Widgets.
			selector.find( '.woocommerce.widget .ui-slider' ).css( 'display', 'none' );
			selector.find( '.woocommerce.widget .ui-slider' ).css( 'display', 'block' );
			selector.find( '.woocommerce.widget .ui-slider' ).addClass( 'color1-background-color' ).children().addClass( 'color2-background-color' );

			// Buttons.
			selector.find( '.button' )
				.removeClass( 'button' )
				.addClass( 'btn button-primary' );
			selector.find( '.button.alt' )
				.removeClass( 'button alt' )
				.addClass( 'btn button-secondary' );
		};

		// When menu partials are refreshed, we need to ensure we update the new container.
		$( document ).on( 'customize-preview-menu-refreshed', function( e, params ) {
			if ( ! _.isUndefined( window.BoldGrid ) ) {
				if ( 'main' === params.wpNavMenuArgs.theme_location ) {
					if ( ! _.isUndefined( window.BoldGrid.standard_menu_enabled ) ) {

						// Initialize SmartMenu on the updated container and params.
						window.BoldGrid.standard_menu_enabled.init( params.newContainer );
					}
				}
			}
		} );
	} );
} )( jQuery );

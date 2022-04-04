/* global _wpCustomizePreviewNavMenusExports:false, _wpCustomizeSettings:false, BoldGrid:true, BOLDGRID:true */
import ColorPreview from './color/preview';
import { Preview as GenericPreview } from './generic/preview.js';
import { Preview as ResponsiveFontSizes } from './responsive-font-sizes/preview.js';
import { Preview as HeaderPreview } from './header-layout/preview.js';
import HeaderLayout from './header-layout/header-layout.js';
import { Preview as BackgroundPreview } from './background/preview.js';
import { LinkPreview } from './typography/link-preview.js';
import Toggle from './toggle/toggle';
import ToggleValue from './toggle/value';
import './widget-meta';
import { Preview as TypographyPreview } from './typography/preview';
import { Preview as ButtonsPreview } from './buttons/preview';
import { Preview as MenuPreview } from './menus/preview';
import { Preview as ContainerWidthPreview } from './max-width-containers/preview';

const api = wp.customize;
const controlApi = parent.wp.customize;

api.selectiveRefresh.bind( 'partial-content-rendered', placement => {
	let controls = [ 'bgtfw_header_layout', 'bgtfw_header_preset', 'bgtfw_sticky_header_preset', 'bgtfw_header_layout_custom', 'bgtfw_sticky_header_layout_custom', 'bgtfw_sticky_header_layout', 'bgtfw_footer_layout' ];

	if ( controls.includes( placement.partial.id ) ) {
		let css = [];

		controls.map( control => {

			if ( ! _.isUndefined( api( control ) ) ) {

				let uid = control.includes( 'header' ) ? control.includes( 'sticky_header' ) ? 's' : 'h' : 'f';

				_.each( api( control )(), ( sections, key ) => {
					uid += key;
					if ( ! _.isUndefined( sections.items ) ) {
						_.each( sections.items, ( item, k ) => {

							if ( _.isEmpty( item.uid ) ) {
								uid += k;
							} else {
								uid = item.uid;
							}

							if ( ! _.isUndefined( item.display ) ) {
								_.each( item.display, display => {
									if ( 'hide' === display.display ) {
										css.push( `.${ uid } ${ display.selector }` );
									}
								} );
							}
						} );
					}
				} );

			}

		} );

		css = _.isEmpty( css ) ? '' : `${ css.join( ', ' ) } { display: none !important; }`;

		// Ensure partial refresh styles are updated for fixed header.
		if (
			'bgtfw_header_layout' === placement.partial.id &&
			( _.isUndefined( api( 'bgtfw_fixed_header' ) ) || false === api( 'bgtfw_fixed_header' )() )
		) {
			css += '#boldgrid-sticky-wrap .bgtfw-sticky-header { display: none !important; }';
		}
		$( '#sticky-header-display-inline-css' ).html( css );
	}
} );

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


BOLDGRID.Customizer.Util.getAllUrlParams = function( url ) {

	// Get query string from url (optional) or window.
	let queryString = url ? url.split( '?' )[1] : window.location.search.slice( 1 );

	// We'll store the parameters here.
	const obj = {};

	// If query string exists...
	if ( queryString ) {

		// Stuff after # is not part of query string, so get rid of it.
		queryString = queryString.split( '#' )[0];

		// Split our query string into its component parts.
		let arr = queryString.split( '&' );

		for ( let i = 0; i < arr.length; i++ ) {

			// Separate the keys and the values.
			let a = arr[i].split( '=' );

			// In case params look like: list[]=thing1&list[]=thing2.
			let paramNum = undefined;
			let paramName = a[0].replace( /\[\d*\]/, function( v ) {
				paramNum = v.slice( 1, -1 );
				return '';
			} );

			// Set parameter value (use 'true' if empty).
			let paramValue = 'undefined' === typeof( a[1] ) ? true : a[1];

			// Keep casing consistent.
			paramName = paramName.toLowerCase();
			paramValue = paramValue.toLowerCase();

			// If parameter name already exists..
			if ( obj[ paramName ] ) {

				// Convert value to array (if still string).
				if ( 'string' === typeof obj[ paramName ] ) {
					obj[ paramName ] = [ obj[ paramName ] ];
				}

				// If no array index number specified...
				if ( 'undefined' === typeof paramNum ) {

					// Put the value on the end of the array.
					obj[ paramName ].push( paramValue );

				// If array index number specified...
				} else {

					// Put the value at that index number.
					obj[ paramName ][ paramNum ] = paramValue;
				}

			// If param name doesn't exist yet, set it.
			} else {
				obj[ paramName ] = paramValue;
			}
		}
	}

	return obj;
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

BOLDGRID.Customizer.Util.getTextContrast = function( color ) {
	const brightness = ( color ) => {
		color = window.net.brehaut.Color( color );
		return ( ( color.getRed() * 0.299 ) + ( color.getGreen() * 0.587 ) + ( color.getBlue() * 0.114 ) ) * 100;
	};
	let lightText = brightness( BOLDGRID.CUSTOMIZER.data.customizerOptions.colors.light_text );
	let darkText = brightness( BOLDGRID.CUSTOMIZER.data.customizerOptions.colors.dark_text );
	color = brightness( color );

	return Math.abs( color - lightText ) > Math.abs( color - darkText ) ? 'var(--light-text)' : 'var(--dark-text)';
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
	let colorPreview = new ColorPreview().init(),
		backgroundPreview = new BackgroundPreview().init(),
		headerLayout = new HeaderLayout();

	headerLayout.init();

	new ResponsiveFontSizes().bindEvents();
	new GenericPreview().bindEvents();
	new HeaderPreview().bindEvents();
	new TypographyPreview().bindEvents();
	new LinkPreview().bindEvents();
	new ButtonsPreview().bindEvents();
	new MenuPreview().bindEvents();
	new ContainerWidthPreview().bindEvents();

	$( function() {
		var headingsColorOutput,
			setupPostEditLink,
			backgroundTypeUpdate,
			backgroundSizeUpdate,
			backgroundAttachmentUpdate,
			backgroundRepeatUpdate,
			attributionLinks,
			attributionSeparators,
			attributionControls;

		let calc = BoldGrid.custom_header.calc;

		/**
		 * Allow the user to click the post edit link in the customizer and go to the editor
		 */
		setupPostEditLink = function() {
			$( '.post-edit-link' ).on( 'click', function() {
				parent.location = $( this ).attr( 'href' );
			} );
		};

		backgroundTypeUpdate = function( to ) {

			// Remove BGTFW color classes.
			let regex = new RegExp( '(color-?([\\d]|neutral)|transparent)-(background-color|text-default)(\\s+|$)', 'g' );
			document.body.className = document.body.className.replace( regex, '' );

			// Remove these styles that should only overwrite on the front end.
			[ 'bgtfw-background-overlay', 'bgtfw-background-inline-css', 'custom-background-css', 'boldgrid-override-styles-inline-css' ].forEach( id => {
				let el = document.getElementById( id );
				el && el.parentNode.removeChild( el );
			} );

			// Reset Styles.
			Object.assign( document.body.style, {
				backgroundImage: '',
				backgroundSize: 'auto',
				backgroundRepeat: 'repeat',
				backgroundAttachment: 'scroll',
				backgroundBlendMode: 'none',
				backgroundColor: null
			} );

			'pattern' === to ? updatePatternTab() : updateImageTab();
		};

		const updateImageTab = () => {
			var bgAttach, bgImgSize;

			bgAttach = api( 'background_attachment' )();
			backgroundPreview.setImage();
			backgroundAttachmentUpdate( bgAttach );

			if ( 'parallax' !== bgAttach ) {
				bgImgSize = api( 'boldgrid_background_image_size' )();
				backgroundSizeUpdate( bgImgSize );
				backgroundRepeatUpdate();
			}
		};

		/**
		 * Set the theme updateColorAndPatterns on the preview frame
		 */
		const updatePatternTab = () => {
			if ( 'pattern' === api( 'boldgrid_background_type' )() ) {
				let backgroundPattern = api( 'boldgrid_background_pattern' )();
				if ( ! backgroundPattern ) {
					backgroundPattern = 'none';
				}

				backgroundAttachmentUpdate();

				Object.assign( document.body.style, {
					backgroundImage: backgroundPattern,
					backgroundSize: 'auto',
					backgroundRepeat: 'repeat',
					backgroundAttachment: 'scroll',
					backgroundBlendMode: 'none',
					backgroundColor: null
				} );

				colorPreview.outputColor( 'boldgrid_background_color', 'body', [
					'background-color', 'text-default'
				] );
			}
		};

		backgroundAttachmentUpdate = function( to ) {
			if ( 'parallax' === to ) {
				document.body.classList.add( 'boldgrid-customizer-parallax-effect' );
				BoldGrid.boldgrid_customizer_parallax.init();
			} else {
				if ( BoldGrid.boldgrid_customizer_parallax.jarallax ) {
					BoldGrid.boldgrid_customizer_parallax.jarallax.jarallax( document.body, 'destroy' );
				}
				backgroundSizeUpdate( api( 'boldgrid_background_image_size' )() );
				document.body.style.backgroundAttachment = to;
				backgroundRepeatUpdate();
				document.body.classList.remove( 'boldgrid-customizer-parallax-effect' );
			}
		};

		backgroundRepeatUpdate = function() {
			document.body.style.backgroundRepeat = api( 'background_repeat' )();
		};

		backgroundSizeUpdate = function( to ) {
			document.body.style.backgroundSize = to;
		};

		/**
		 * Handles front-end headings color changes in previewer.
		 *
		 * This will add classes for the headings color changes to elements during a live preview.
		 * The headings makes use of the global:
		 * BOLDGRID.CUSTOMIZER.data.customizerOptions.typography.selectors,
		 * which contains the selectors defined in the PHP configurations for bgtfw.
		 *
		 * @since 2.0.0
		 *
		 * @param string to       Theme mod value of headings color.
		 * @param string selector CSS selector to apply classes to.
		 */
		headingsColorOutput = function( themeMod, section ) {
			var selectors = [];

			_.each( BOLDGRID.CUSTOMIZER.data.customizerOptions.typography.selectors, function( value, key ) {
				if ( 'headings' === value.type ) {
					selectors.push( section + ' ' + key );
				}
			} );

			selectors = selectors.join( ', ' );

			colorPreview.outputColor( themeMod, $( selectors ).not( '.site-description' ), [ 'color' ] );
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


		attributionLinks();
		setupPostEditLink();

		$( '.site-description' ).addClass( window._typographyClasses );

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

		api( 'bgtfw_site_title_display', value => value.bind( to => {
			$( '.site-title' ).toggleClass( 'screen-reader-text', 'show' !== to );
		} ) );

		api( 'bgtfw_tagline_display', value => value.bind( to => {
			$( '.site-description' ).toggleClass( 'screen-reader-text', 'show' !== to );
		} ) );

		api( 'boldgrid_color_palette', function( value ) {
			value.bind( function( to ) {
				var colors,
					neutral;

				colors = BOLDGRID.Customizer.Util.getInitialPalettes( to );
				if ( colors ) {
					neutral = colors.pop();

					document.documentElement.style.setProperty( '--color-neutral', neutral );
					document.documentElement.style.setProperty( '--color-neutral-text-contrast', BOLDGRID.Customizer.Util.getTextContrast( neutral ) );
					_( colors ).each( function( color, index ) {
						document.documentElement.style.setProperty( '--color-' + Math.abs( index + 1 ), color );
						document.documentElement.style.setProperty( '--color-' + Math.abs( index + 1 ) + '-text-contrast', BOLDGRID.Customizer.Util.getTextContrast( color ) );
					} );
				}
			} );
		} );

		/**
		 * Toggle Scroll To Top Arrows.
		 */
		api( 'bgtfw_scroll_to_top_display', value => value.bind( to => {
			if ( 'hide' !== to ) {

				// Initialize goup.
				BoldGrid.goup_enabled.init();

				// Check configs for scroll to top edit button settings.
				let btn = _.findWhere( BOLDGRID.CustomizerEdit.i18n.config.buttons.general, { control: 'bgtfw_scroll_to_top_display' } );

				// If the setting is found then add the edit button if it's not already in the DOM.
				if ( ! _.isUndefined( btn ) && ! $( '[data-control="bgtfw_scroll_to_top_display"]' ).length ) {
					BOLDGRID.CustomizerEdit.addButton( btn );
				}
			} else {
				BoldGrid.goup_enabled.destroy(); // Destroy scroll to top instance.
			}
		} ) );

		/**
		 * Update any of the color control's palettes with correct palette from color picker.
		 */
		api( 'boldgrid_color_palette', function( value ) {
			value.bind( function( to ) {
				var palettes, colors;

				palettes = parent.jQuery( '.colors-wrapper' );
				colors = BOLDGRID.Customizer.Util.getInitialPalettes( to );

				// Update any palettes on open colorpicker instances.
				if ( colors ) {
					_( palettes ).each( function( palette ) {
						var swatches = $( palette ).find( 'input' );
						_( swatches ).each( function( swatch, index ) {
							var currentVal, newVal, link;
							currentVal = $( swatch ).val();
							if ( 'transparent' !== currentVal ) {
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
							}
						} );
					} );
				}
			} );
		} );

		/* Header Background Color */
		api( 'bgtfw_header_color', function( value ) {
			value.bind( function() {
				colorPreview.outputColor( 'bgtfw_header_color', '#masthead, #navi', [ 'background-color', 'text-default' ] );
			} );
		} );

		new ToggleValue( 'bgtfw_blog_page_container', '.blog .site-content, .archive .site-content', 'container', calc );

		api( 'bgtfw_header_layout_tabs', function() {
			$( controlApi.control( 'bgtfw_header_layout_tabs' ).container ).find( '.bgtfw-tab' ).on( 'click', function() {
				if ( '#customize-control-bgtfw_sticky_header_layout' === this.dataset.tab ) {
					controlApi.control( 'bgtfw_header_layout_col_width' ).deactivate();
				} else {
					controlApi.control( 'bgtfw_header_layout_col_width' ).activate();
				}
			} );
		} );
		api( 'bgtfw_fixed_header', value => value.bind( to => {
			document.body.className = document.body.className.replace( 'header-slide-in', '' );
			document.body.className = document.body.className.replace( 'header-fixed', '' );
			if ( to ) {
				if ( 'header-top' === api( 'bgtfw_header_layout_position' )() ) {
					if ( -1 === document.body.className.indexOf( 'header-slide-in' ) ) {
						document.body.className += ' ' + 'header-slide-in';
					}
					$( '.bgtfw-sticky-header' ).attr( 'style', 'display: block !important' );
				} else {
					if ( -1 === document.body.className.indexOf( 'header-fixed' ) ) {
						document.body.className += ' ' + 'header-fixed';
					}
				}
			} else {
				$( '.bgtfw-sticky-header' ).attr( 'style', 'display: none !important' );
			}

			// Initialize header_slide_in if body class exists.
			if ( document.body.classList.contains( 'header-slide-in' ) ) {
				BoldGrid.header_slide_in.init();
			}

			calc();
		} ) );

		let layoutFn = ( index, className ) => {
			return ( className.match ( /(^|\s)layout-\S+/g ) || [] ).join( ' ' );
		};

		new ToggleValue( 'bgtfw_footer_layouts', '#colophon', layoutFn );

		// Setup hamburger menus.
		let hamburgerFn = ( index, className ) => {
			return ( className.match ( /(^|\s)hamburger--\S+/g ) || [] ).join( ' ' );
		};

		let hoverFn = ( index, className ) => {
			return ( className.match ( /(^|\s)hvr-\S+/g ) || [] ).join( ' ' );
		};

		/**
		 * Customizer always marks home as the current-menu-item.  This will check the query
		 * params of the link in the menu, and compare it to the currently previewed URL and
		 * remove the .current-menu-item classes from links that don't match.  I assume this
		 * should be fixed in core at some point, or we have done something incorrect somewhere,
		 * but for the time being this works.
		 *
		 * @since 2.0.0
		 */
		let setupCurrentMenuItems = menuId => {
			var currents;

			if ( 1 === menuId.length ) {
				currents = $( menuId ).children( '.current-menu-item' );
			} else {
				currents = $( `#${menuId} > .current-menu-item` );
			}

			// Check if it's a parent menu item before removing things.
			if ( currents.length && ! currents.hasClass( 'current-menu-parent' ) ) {
				let links = currents.find( 'a' );
				let regex = new RegExp( '(color-?([\\d]|neutral)|transparent)-.?[^\\s]+', 'g' );

				_.each( links, link => {
					let current = BOLDGRID.Customizer.Util.getAllUrlParams( _wpCustomizeSettings.url.self );
					let href = BOLDGRID.Customizer.Util.getAllUrlParams( $( link ).attr( 'href' ) );
					! _.isMatch( href, current ) && $( link )
						.parent( 'li' )
						.removeClass( 'current-menu-item' )
						.removeClass( ( index, css ) => {
							return ( css.match( regex ) || [] ).join( ' ' );
						} );
				} );
			}
		};

		/**
		 * Get the menu color being used for link color contrast.
		 *
		 * @since 2.0.0
		 *
		 * @param {String} location Menu location.
		 *
		 * @return {String} Name of theme mod to use.
		 */
		let menuContrastColor = ( location, menuId ) => {
			let type = `bgtfw_menu_background_${location}`;
			let isTransparent = api( type )().includes( 'transparent' );
			if ( isTransparent ) {
				type = 'header';

				if ( BOLDGRID.CUSTOMIZER.data.menu.footerMenus.includes( location ) ) {
					type = 'footer';
				}

				type = `bgtfw_${type}_color`;
			}

			new ColorPreview().outputColor( type, `#${menuId}`, [ 'background-color' ], isTransparent );
		};

		// Setup menu controls.
		let menuLocations = Object.values( _wpCustomizePreviewNavMenusExports.navMenuInstanceArgs );
		menuLocations.push( {
			'theme_location': 'social',
			'menu_id': 'social-menu'
		} );
		menuLocations.push( {
			'theme_location': 'sticky-social',
			'menu_id': 'sticky-social-menu'
		} );
		for ( const props of menuLocations ) {
			if ( props.theme_location ) {

				// Setup current menu items.
				setupCurrentMenuItems( props.menu_id );

				// Setup partials.
				new ToggleValue( `bgtfw_menu_hamburger_${props.theme_location}_toggle`, `#${props.menu_id}`, () => {
					let id = `nav_menu_instance[${props.args_hmac}]`;
					if ( wp.customize.selectiveRefresh.partial( id ) ) {

						// before triggering a refresh destroy the instance.
						$( `#${props.menu_id}` ).smartmenus( 'destroy' );
						wp.customize.selectiveRefresh.partial( id ).refresh();
					}
				} );

				// Setup hamburger menus.
				new ToggleValue( `bgtfw_menu_hamburger_${props.theme_location}`, `#${props.menu_id}-hamburger`, hamburgerFn );

				// Setup hamburger menu enable/disable.
				new Toggle( `bgtfw_menu_hamburger_${props.theme_location}_toggle`, ( to ) => {

					// If disabled, ensure the hamburger is forced open, and active state is set.
					let menu = document.getElementById( `${props.menu_id}-state` );
					let hamburger = document.getElementById( `${props.menu_id}-hamburger` );
					if ( ! to ) {
						if ( ! menu.checked ) {
							$( menu ).click();
							hamburger.classList.remove( 'is-active' );
						}
					}

					if ( to ) {
						if ( menu.checked ) {
							$( menu ).click();
							hamburger.classList.remove( 'is-active' );
						}
					}

					// Toggle hidden classes for display.
					$( `#${props.menu_id}-hamburger, #${props.menu_id}-state` ).toggleClass( 'hidden', ! to ) && calc();
				} );

				// Bind menu items hover effects.
				new ToggleValue( `bgtfw_menu_items_hover_effect_${props.theme_location}`, `#${props.menu_id} > li:not(.current-menu-item)`, hoverFn );

				// Bind menu background contrast for link colors.
				api( `bgtfw_menu_background_${props.theme_location}`, 'bgtfw_header_color', 'bgtfw_footer_color', ( ...args ) => {
					args.map( control => control.bind( () => menuContrastColor( props.theme_location, props.menu_id ) ) );
				} );
			}

		}

		// Listen for widget layout changes.
		[ 'bgtfw_header_layout', 'bgtfw_header_layout_custom', 'bgtfw_sticky_header_layout', 'bgtfw_sticky_header_layout_custom', 'bgtfw_footer_layout' ].forEach( control => {
			api( control, value => {
				value.bind( () => {
					api.preview.send( 'bgtfw-widget-section-update', control );
				} );
			} );
		} );

		new Toggle( 'bgtfw_header_width', calc );
		new Toggle( 'bgtfw_footer_headings_color', () => headingsColorOutput( 'bgtfw_footer_headings_color', '.site-footer :not(.bgtfw-widget-row)' ) );
		new Toggle( 'blogname', ( to ) => $( '.site-title a' ).text( to ) && calc() );
		new Toggle( 'blogdescription', ( to ) => {
			$( '.site-description' ).text( to ).toggleClass( 'invisible', ! to ) && calc();
		} );

		// Toggle page title wrapper containers.
		let toggleTitleFn = ( condition, classes ) => {
			let body = $( 'body' );
			if ( ! body.hasClass( 'page-header-hidden' ) && ! body.hasClass( 'page-header-shown' ) ) {
				for ( let className of classes ) {
					if ( body.hasClass( className ) ) {
						body.toggleClass( 'customizer-page-header-hidden', condition );
						break;
					}
				}
			}
		};

		api( 'bgtfw_pages_title_display', value => value.bind( to => {
			let condition = 'hide' === to;
			toggleTitleFn( condition, [ 'page', 'blog', 'archive' ] );
		} ) );

		api( 'bgtfw_posts_title_display', value => value.bind( to => {
			let condition = 'hide' === to && 'none' === api( 'bgtfw_posts_meta_display' )();
			toggleTitleFn( condition, [ 'single', 'post-template-default' ] );
		} ) );

		api( 'bgtfw_posts_meta_display', value => value.bind( to => {
			let condition = 'none' === to && 'hide' === api( 'bgtfw_posts_title_display' )();
			toggleTitleFn( condition, [ 'single', 'post-template-default' ] );
		} ) );

		new Toggle( 'boldgrid_background_pattern', updatePatternTab );
		new Toggle( 'boldgrid_background_color', updatePatternTab );

		new Toggle( 'boldgrid_background_type', backgroundTypeUpdate );
		new Toggle( 'background_attachment', backgroundAttachmentUpdate );
		new Toggle( 'background_repeat', backgroundRepeatUpdate );
		new Toggle( 'boldgrid_background_image_size', backgroundSizeUpdate );

		let setHeaderPosition = () => {
			calc();
		};

		new ToggleValue( 'bgtfw_header_layout_position', 'body', 'header-top header-left header-right', setHeaderPosition );

		$( document ).on( 'customize-preview-menu-refreshed', function( event, menu ) {
			$.each( menu.newContainer.closest( '[data-is-parent-column]' ), function() {
				BOLDGRID.Customizer.Widgets.updatePartial( $( this ) );
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

		/**
		 * Before displaying the partal content for nav menu items on the page we want to
		 * destroy the existing instance of our menus tied to the partial because when the
		 * partial is rendered, we will re-initialize the individual menu, and would no
		 * longer have reference back to the initial instance referenced.
		 */
		api.selectiveRefresh.bind( 'render-partials-response', function( placement ) {

			// Destroy menu instances.
			if ( ! _.isUndefined( placement.nav_menu_instance_args ) ) {
				_.each( placement.nav_menu_instance_args, ( instance ) => $( `#${instance.menu_id}` ).smartmenus( 'destroy' ) );
			}
		} );

		/**
		 * When menu partials are refreshed, we need to ensure that we update the new
		 * container, and initialize a new instance of our menus for proper funcitonality.
		 *
		 * The delay for setupCurrentMenuItems() is important because after
		 * customize-preview-menu-refreshed is trigged _wpCustomizeSettings reflects a
		 * different page ID, so we wait the full duration of the selectiveRefresh event
		 * before attempting to correct the menu classes.
		 */
		$( document ).on( 'customize-preview-menu-refreshed', function( e, params ) {
			if ( ! _.isUndefined( BoldGrid ) ) {
				if ( ! _.isUndefined( BoldGrid.standard_menu_enabled ) ) {

					// Reset current-menu-item classes in customizer.
					_.delay( () => setupCurrentMenuItems( params.newContainer ), _wpCustomizeSettings.timeouts.selectiveRefresh );

					// Initialize SmartMenu on the updated container and params.
					if ( ! _.isUndefined( params.wpNavArgs.theme_location ) && ! _.isEmpty( params.wpNavArgs.theme_location ) ) {
						BoldGrid.standard_menu_enabled.init( params.newContainer );
					}
				}
			}
		} );
	} );
} )( jQuery );

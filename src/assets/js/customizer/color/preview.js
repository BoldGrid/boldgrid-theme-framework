/* global _wpCustomizePreviewNavMenusExports:false, BOLDGRID:false */
import PaletteSelector from './palette-selector';
import { Preview as PreviewUtility } from '../preview';

export class Preview  {

	constructor() {
		this.previewUtility = new PreviewUtility();

		this.classControls = [
			{
				name: 'bgtfw_footer_color',
				selector: '#colophon:not(.template-footer), .footer-content:not(.template-footer)',
				properties: [ 'background-color', 'text-default' ]
			},
			{
				name: 'bgtfw_tagline_color',
				selector: '.site-description',
				properties: [ 'color' ]
			},
			{
				name: 'bgtfw_site_title_color',
				selector: '.site-title',
				properties: [ 'color' ]
			},
			{
				name: 'bgtfw_blog_post_background_color',
				selector: '.palette-primary.archive .post, .palette-primary.blog .post',
				properties: [ 'background-color', 'text-default' ]
			},
			{
				name: 'bgtfw_blog_post_header_title_color',
				selector: '.archive .post .entry-title .link, .blog .post .entry-title .link',
				properties: [ 'color', 'color-hover' ]
			},
			{
				name: 'bgtfw_blog_header_background_color',
				selector: '.palette-primary.archive .post .entry-header, .palette-primary.blog .post .entry-header',
				properties: [ 'background-color', 'text-default' ]
			},
			{
				name: 'bgtfw_global_title_background_color',
				selector: '.page-header',
				properties: [ 'background-color', 'text-default' ]
			},
			{
				name: 'bgtfw_global_title_color',
				selector: '.page-header .entry-title .link, .page-header .page-title .link',
				properties: [ 'color', 'color-hover' ]
			},
			{
				name: 'bgtfw_footer_links',
				selector: '.footer-content:not(.template-footer)',
				properties: [ 'link-color' ]
			}
		];
	}

	/**
	 * Class initialized.
	 *
	 * @since 2.0.0
	 *
	 * @return {Preview} Class instance.
	 */
	init() {
		$( () => this._onLoad() );

		return this;
	}

	/**
	 * Handles front-end color changes in previewer.
	 *
	 * This will add classes for color changes to elements during a live preview.
	 *
	 * @since 2.0.0
	 *
	 * @param string themeMod Theme mod to use for retrieving a background color.
	 * @param string selector CSS selector to apply classes to.
	 * @param array  list of properties to add.
	 */
	outputColor( themeMod, selector, properties, isTransparent ) {
		let colorClassPrefix,
			$selector = $( selector ),
			regex = new RegExp( '(color-?([\\d]|neutral)|transparent)-(' + properties.join( '|' ) + ')(\\s+|$)', 'g' );

		themeMod = parent.wp.customize( themeMod )();

		if ( ! themeMod || 'none' === themeMod ) {
			themeMod = '';
		}

		$selector.removeClass( ( index, css ) => {
			return ( css.match( regex ) || [] ).join( ' ' );
		} );

		// Get class prefix.
		colorClassPrefix = new PaletteSelector().getColorNumber( themeMod );

		// Add all classes.
		$selector.addClass( _.map( properties, ( property ) => {
			let prefix = colorClassPrefix;

			// If neutral or link-color, do not remove color hyphen.
			if ( -1 === colorClassPrefix.indexOf( 'neutral' ) && -1 === property.indexOf( 'link-color' ) ) {
				prefix = colorClassPrefix.replace( '-', '' );
			}

			return prefix + '-' + property;
		} ).join( ' ' ) );

		if ( isTransparent ) {
			$selector.addClass( _.map( properties, ( property ) => {
				let prefix = colorClassPrefix;

				// If neutral or link-color, do not remove color hyphen.
				if ( -1 === colorClassPrefix.indexOf( 'neutral' ) && -1 === property.indexOf( 'link-color' ) ) {
					prefix = colorClassPrefix.replace( '-', '' );
				}

				return prefix + '-' + property + ' transparent-' + property;
			} ).join( ' ' ) );
		}
	}

	/**
	 * Get the css used for headin colors.
	 *
	 * @since 2.0.0
	 *
	 * @param  {string} to Them mod value.
	 * @return {string}    CSS for headings.
	 */
	getHeadingCSS( to ) {
		const color = new PaletteSelector().getColor( to );

		let css = '',
			headingSelectors = [];

		if ( color ) {
			headingSelectors = this.getHeadingColorSelectors().join( ', ' );

			css = `
				${ headingSelectors } {
					color: ${ color };
				}
			`;
		}

		return css;
	}

	/**
	 * Get the css used for weForms label colors.
	 *
	 * @since 2.15.0
	 *
	 * @param  {string} to Them mod value.
	 *
	 * @return {string}    CSS for weForms.
	 */
	getWeformsLabelCss( to ) {
		const color    = new PaletteSelector().getColor( to );
		var labelColor = color;

		let css                   = '',
			hasSemicolon          = color ? color.indexOf( ';' ) : -1,
			weformsLabelSelectors = [];

		if ( color ) {
			labelColor            = -1 !== hasSemicolon ? color.split( ';' )[0] : color;
			weformsLabelSelectors = this.getWeformsLabelColorSelectors().join( ', ' );

			css = `
				${ weformsLabelSelectors } {
					color: ${ labelColor } !important;
				}
			`;
		}

		return css;
	}

	/**
	 * Get the css used for weForms sub-label colors.
	 *
	 * @since 2.15.0
	 *
	 * @param  {string} to Them mod value.
	 *
	 * @return {string}    CSS for weForms.
	 */
	getWeformsSubLabelCss( to ) {
		const color    = new PaletteSelector().getColor( to );
		var subLabelColor = color;

		let css = '',
			hasSemicolon = color ? color.indexOf( ';' ) : -1,
			weformsSubLabelSelectors = [];

		if ( color ) {
			subLabelColor            = -1 !== hasSemicolon ? color.split( ';' )[0] : color;
			weformsSubLabelSelectors = this.getWeformsSubLabelColorSelectors().join( ', ' );

			css = `
				${ weformsSubLabelSelectors } {
					color: ${ subLabelColor } !important;
				}
			`;
		}

		return css;
	}

	/**
	 * Get a list of heading selectors from the global.
	 *
	 * @since 2.0.0
	 *
	 * @return {array} list of selectors.
	 */
	getHeadingColorSelectors() {
		const selectors = [];

		_.each( BOLDGRID.CUSTOMIZER.data.customizerOptions.typography.selectors, function( value, key ) {
			if ( 'headings' === value.type ) {
				selectors.push( key );
			}
		} );

		return selectors;
	}

	/**
	 * Get a list of weforms label selectors from the global.
	 *
	 * @since 2.15.0
	 *
	 * @return {array} list of selectors.
	 */
	getWeformsLabelColorSelectors() {
		const selectors = [];

		_.each( BOLDGRID.CUSTOMIZER.data.customizerOptions.typography.selectors, function( value, key ) {
			if ( 'weformsLabel' === value.type ) {
				selectors.push( key );
			}
		} );

		return selectors;
	}

	/**
	 * Get a list of weforms sub-label selectors from the global.
	 *
	 * @since 2.15.0
	 *
	 * @return {array} list of selectors.
	 */
	getWeformsSubLabelColorSelectors() {
		const selectors = [];

		_.each( BOLDGRID.CUSTOMIZER.data.customizerOptions.typography.selectors, function( value, key ) {
			if ( 'weformsSubLabel' === value.type ) {
				selectors.push( key );
			}
		} );

		return selectors;
	}

	/**
	 * Use the theme mod saved for a heading color to set the heading colors.
	 *
	 * @since 2.0.0
	 */
	setHeadingColors() {
		const css = this.getHeadingCSS( wp.customize( 'bgtfw_headings_color' )() );
		this.previewUtility.updateDynamicStyles( 'bgtfw_headings_color', css );
	}

	/**
	 * Use the theme mod saved for a weForms label color
	 * to set the weForms label colors.
	 *
	 * @since 2.0.0
	 */
	setWeformsColors() {
		const css = this.getWeformsLabelCss( wp.customize( 'bgtfw_weforms_label_color' )() );
		this.previewUtility.updateDynamicStyles( 'bgtfw_weforms_label_color', css );
	}

	/**
	 * Use the theme mod saved for a weForms sub-label color
	 * to set the weForms sub-label colors.
	 *
	 * @since 2.0.0
	 */
	setWeformsSubColors() {
		const css = this.getWeformsSubLabelCss( wp.customize( 'bgtfw_weforms_sublabel_color' )() );
		this.previewUtility.updateDynamicStyles( 'bgtfw_weforms_sublabel_color', css );
	}


	/**
	 * Get Hamburger menu CSS.
	 *
	 * @param {String} to Thememod's color value.
	 * @param {String} menuId Menu ID for nav menu instance.
	 *
	 * @return {String} css The CSS to add.
	 */
	getHamburgerCSS( to, menuId ) {
		const color = new PaletteSelector().getColor( to );
		let css = `
		.${menuId}-btn .hamburger-inner,
		.${menuId}-btn .hamburger-inner::before,
		.${menuId}-btn .hamburger-inner::after {
			background-color: ${color};
		}`;

		return css;
	}

	/**
	 * Get active link color CSS.
	 *
	 * @param {String} to Thememod's color value.
	 * @param {String} menuId Menu ID for nav menu instance.
	 *
	 * @return {String} css The CSS to add.
	 */
	getActiveLinkColor( to, menuId ) {
		const color = new PaletteSelector().getColor( to );
		let css = `
		#${menuId} .current-menu-item > a:not( .btn ),
		#${menuId} .current-menu-ancestor > a:not( .btn ),
		#${menuId} .current-menu-parent > a:not( .btn ) {
			color: ${color};
		}`;

		return css;
	}

	/**
	 * Get Submenu Active Link Color
	 *
	 * @param {string} to The thememod's color value.
	 * @param {string} menuId Menu ID for nav menu instance.
	 * @returns {string} css The CSS to add.
	 */
	getSubActiveLinkColor( to, menuId ) {
		const color = new PaletteSelector().getColor( to );
		let css = `
		#${menuId} ul.sub-menu > .current-menu-item > a:not( .btn ),
		#${menuId} ul.sub-menu > .current-menu-ancestor > a:not( .btn ),
		#${menuId} ul.sub-menu > .current-menu-parent > a:not( .btn ) {
			color: ${color};
		}`;

		return css;
	}

	/**
	 * Get hover link color CSS.
	 *
	 * @param {String} to Thememod's color value.
	 * @param {String} menuId Menu ID for nav menu instance.
	 *
	 * @return {String} css The CSS to add.
	 */
	getHoverLinkColor( to, menuId ) {
		const color = new PaletteSelector().getColor( to );
		let css = `
		#${menuId} .hvr-none:not( .current-menu-item ):not( .button ) > a:hover,
		#${menuId} .hvr-none:not( .current-menu-ancestor ):not( .button ) > a:hover,
		#${menuId} .hvr-none:not( .current-menu-parent ):not( .button ) > a:hover {
			color: ${color};
		}`;

		return css;
	}

	/**
	 * Get supplementary menu color CSS.
	 *
	 * @param {String} location Menu location for nav menu instance.
	 *
	 * @return {String} css The CSS to add.
	 */
	getMenuColorsCSS( location ) {
		let type     = `bgtfw_menu_background_${location}`;
		let subtype  = `bgtfw_menu_submenu_background_${location}`;
		let inFooter = false;

		if ( wp.customize( type )().includes( 'transparent' ) || _.isUndefined( wp.customize( type )() ) ) {
			type = 'header';

			if ( BOLDGRID.CUSTOMIZER.data.menu.footerMenus.includes( location ) ) {
				type = 'footer';
				inFooter = true;
			}

			type = `bgtfw_${type}_color`;
		}

		if ( wp.customize( subtype )().includes( 'transparent' ) || _.isUndefined( wp.customize( subtype )() ) ) {
			subtype = 'header';

			if ( BOLDGRID.CUSTOMIZER.data.menu.footerMenus.includes( location ) ) {
				subtype = 'footer';
				inFooter = true;
			}

			subtype = `bgtfw_${subtype}_color`;
		}

		let paletteSelector  = new PaletteSelector();
		let color            = wp.customize( type )();
		let subcolor         = wp.customize( subtype )();
		let subcolorVariable = paletteSelector.getColor( subcolor );

		color    = paletteSelector.getColor( color, true );
		subcolor = paletteSelector.getColor( subcolor, true );

		let alpha = parent.net.brehaut.Color( color );
		let css   = '';

		location = location.replace( /_/g, '-' );

		if ( ! inFooter ) {
			css += `.header-left #main-menu, .header-right #main-menu { background-color: ${alpha}; }`;
		}

		css += '@media (min-width: 768px) {';
		css += `#${location}-menu.sm-clean ul.sub-menu:not(.custom-sub-menu) {background-color: ${subcolorVariable};}`;
		css += `#${location}-menu.sm-clean ul.sub-menu:not(.custom-sub-menu) li.menu-item:not(.custom-sub-menu) > a,
			#${location}-menu.sm-clean ul.sub-menu:not(.custom-sub-menu) li.menu-item:not(.custom-sub-menu) > a:hover,
			#${location}-menu.sm-clean ul.sub-menu:not(.custom-sub-menu) li.menu-item:not(.custom-sub-menu) > a:focus,
			#${location}-menu.sm-clean ul.sub-menu:not(.custom-sub-menu) li.menu-item:not(.custom-sub-menu) > a:active,
			#${location}-menu.sm-clean ul.sub-menu:not(.custom-sub-menu) li.menu-item:not(.custom-sub-menu) > a.highlighted,
			#${location}-menu.sm-clean span.scroll-up,
			#${location}-menu.sm-clean span.scroll-down,
			#${location}-menu.sm-clean span.scroll-up:hover,
			#${location}-menu.sm-clean span.scroll-down:hover {
				background-color: ${subcolorVariable};
			}`;
		css += `#${location}-menu.sm-clean ul.sub-menu:not(.custom-sub-menu) {
			border: 1px solid ${subcolorVariable};
			}`;
		css += `#${location}-menu.sm-clean > li.menu-item:not( .custom-sub-menu ) > ul.sub-menu:not(.custom-sub-menu):before,
			#${location}-menu.sm-clean > li.menu-item:not( .custom-sub-menu ) > ul.sub-menu:not(.custom-sub-menu):after {
				border-color: transparent transparent ${subcolorVariable} transparent;
			}
			#${location}-menu.sm-clean > li.menu-item:not( .custom-sub-menu ) > ul.sub-menu.pointer-bottom:not(.custom-sub-menu):after {
				border-color: ${subcolorVariable} transparent transparent transparent;
			}`;
		css += '}';

		return css;
	}

	/**
	 * Set supplementary menu colors.
	 *
	 * @since 2.0.0
	 *
	 * @param {String} location Menu location for nav menu instance.
	 */
	setMenuColors( location ) {
		let css = this.getMenuColorsCSS( location );
		this.previewUtility.updateDynamicStyles( `menu-colors-${location}-inline-css`, css );
	}

	/**
	 *
	 * @param {String} to Thememod's color value.
	 * @param {String} menuId Menu ID for nav menu instance.
	 */
	getHoverCSS( location ) {

		const color = new PaletteSelector().getColor( wp.customize( `bgtfw_menu_items_hover_color_${location}` )() );
		const backgroundColor = new PaletteSelector().getColor( wp.customize( `bgtfw_menu_items_hover_background_${location}` )() );
		let css = BOLDGRID.CUSTOMIZER.data.hoverColors;

		location = location.replace( /_/g, '-' );

		css = css.replace( /%1\$s/g, `#${location}-menu` );
		css = css.replace( /%2\$s/g, backgroundColor );
		css = css.replace( /%3\$s/g, color );
		css = css.replace( /%4\$s/g, backgroundColor );

		return css;
	}

	/**
	 * Set the hamburger colors for menus.
	 *
	 * @since 2.0.0
	 * @param {Object} props Properties assigned for a nav menu instance.
	 */
	setHamburgerColors( location, menuId ) {
		let to = wp.customize( `bgtfw_menu_hamburger_${location}_color` )();
		let css = this.getHamburgerCSS( to, menuId );
		this.previewUtility.updateDynamicStyles( `bgtfw_menu_hamburger_${location}_color`, css );
	}

	/**
	 * Set the hover colors for menu items.
	 *
	 * @since 2.0.0
	 *
	 * @param {String} location Set hover colors for menu items.
	 */
	setHoverColors( location ) {
		let css = this.getHoverCSS( location );
		this.previewUtility.updateDynamicStyles( `hover-${location}-inline-css`, css );
	}

	/**
	 * Set Active Sub menu link color.
	 *
	 * @param {string} location menu location
	 * @param {string} menuId menu id
	 */
	setActiveSubLinkColor( location, menuId ) {
		let subMod = `bgtfw_menu_items_sub_active_link_color_${location}`;
		let subTo = wp.customize( subMod )();
		let subCss = this.getSubActiveLinkColor( subTo, menuId );

		this.previewUtility.updateDynamicStyles( `active-link-sub-color-${location}-inline-css`, subCss );
	}

	/**
	 * Set active menu item link colors.
	 *
	 * @since 2.0.0
	 *
	 * @param {String} location Location of nav menu instance.
	 * @param {String} menuId   Unique ID for nav menu instance.
	 */
	setActiveLinkColor( location, menuId ) {
		let mod = `bgtfw_menu_items_active_link_color_${location}`;
		let to = wp.customize( mod )();
		let css = this.getActiveLinkColor( to, menuId );

		this.previewUtility.updateDynamicStyles( `active-link-color-${location}-inline-css`, css );
	}

	/**
	 * Set active menu item link colors.
	 *
	 * @since 2.0.0
	 *
	 * @param {String} location Location of nav menu instance.
	 * @param {String} menuId   Unique ID for nav menu instance.
	 */
	setHoverLinkColor( location, menuId ) {
		let mod = `bgtfw_menu_items_hover_link_color_${location}`;
		let to = wp.customize( mod )();
		let css = this.getHoverLinkColor( to, menuId );
		this.previewUtility.updateDynamicStyles( `hover-link-color-${location}-inline-css`, css );
	}

	/**
	 * Set dynamic menu color configs.
	 *
	 * @since 2.0.0
	 *
	 * @param {String} location Location of nav menu instance.
	 * @param {String} menuId   Unique ID for nav menu instance.
	 */
	setMenuConfigs( location, menuId ) {
		this.classControls.push(
			{
				name: `bgtfw_menu_border_color_${location}`,
				selector: `#${menuId}`,
				properties: [ 'border-color' ]
			},
			{
				name: `bgtfw_menu_items_border_color_${location}`,
				selector: `#${menuId} > li.menu-item`,
				properties: [ 'border-color' ]
			},
			{
				name: `bgtfw_menu_background_${location}`,
				selector: `#${menuId}`,
				properties: [ 'background-color' ]
			},
			{
				name: `bgtfw_menu_submenu_background_${location}`,
				selector: `#${menuId} ul.sub-menu:not(.custom-sub-menu)`,
				properties: [ 'background-color' ]
			},
			{
				name: `bgtfw_menu_items_link_color_${location}`,
				selector: `#${menuId}`,
				properties: [ 'link-color' ]
			},
			{
				name: `bgtfw_menu_items_sub_link_color_${location}`,
				selector: `#${menuId}`,
				properties: [ 'sub-link-color' ]
			},
			{
				name: `bgtfw_menu_items_active_link_background_${location}`,
				selector: `#${menuId} > li.current-menu-item`,
				properties: [ 'background-color' ]
			},
			{
				name: `bgtfw_menu_items_active_link_border_color_${location}`,
				selector: `#${menuId} > li.current-menu-item`,
				properties: [ 'border-color' ]
			}
		);
	}

	/**
	 * Set the overlay colors.
	 *
	 * @since 2.0.0
	 */
	setHeaderOverlay() {
		const selector = new PaletteSelector(),
			color = selector.getColor( wp.customize( 'bgtfw_header_overlay_color' )(), true ),
			alpha = wp.customize( 'bgtfw_header_overlay_alpha' )(),
			brehautColor = parent.net.brehaut.Color( color ),
			rgba = brehautColor.setAlpha( alpha ).toString();

		let styles = '#wp-custom-header::after{ display: none; }';
		if ( wp.customize( 'bgtfw_header_overlay' )() ) {
			styles = `
				#wp-custom-header::after {
					background-color: ${rgba} !important;
				}
			`;
		}

		this.previewUtility.updateDynamicStyles( 'bgtfw-header-overlay-inline-css', styles );
	}

	/**
	 * Set .entry-header colors.
	 *
	 * @since 2.0.0
	 */
	setPageTitles( themeMod, selectors ) {
		const selector = new PaletteSelector(),
		color = selector.getColor( wp.customize( themeMod )(), true ),
		brehautColor = parent.net.brehaut.Color( color ),
		updatedColor = brehautColor.setAlpha( 0.7 ).toString();

		let parse = ( string ) => {
			var token = /((?:[^"']|".*?"|'.*?')*?)([(,)]|$)/g;
			return ( function recurse() {
				var result;
				for ( let array = [];; ) {
					result = token.exec( string );
					if ( '(' === result[2] ) {
						array.push( result[1].trim() + '(' + recurse().join( ', ' ) + ')' );
						result = token.exec( string );
					} else {
						array.push( result[1].trim() );
					}
					if ( ',' !== result[2] ) {
						return array;
					} else if ( ',' === result[2] && '%' === result[1][ result[1].length - 1 ] ) {
						array[ array.length - 1 ] += result[1];
					}
				}
			} )( );
		};

		let colorRegex = /rgba\(\s?([0-9]+),\s?([0-9]+),\s?([0-9]+),\s?([0-9|.]+)\s?\)/g;
		let headers = $( selectors );

		_.each( headers, function( header ) {
			header = $( header );

			let background = header.css( 'background-image' );
			let updatedBackground = false;

			// Parse background-image properties into an array.
			let props = parse( background );

			// Check for linear-gradient being applied.
			let linearGradient = props.filter( prop => prop.includes( 'linear-gradient' ) );

			// Replace the new color in gradient.
			if ( 0 < linearGradient.length && colorRegex.test( linearGradient[0] ) ) {
				let newLinearGradient = linearGradient[0].replace( /\s+/g, '' );
				newLinearGradient = newLinearGradient.replace( colorRegex, updatedColor );
				updatedBackground = background.replace( linearGradient[0], newLinearGradient );
			} else {
				let hasImage = props.filter( prop => prop.includes( 'url(' ) );

				// If a url is specified, then construct a new linear-gradient if one wasn't found.
				if ( hasImage ) {
					updatedBackground = 'linear-gradient(' + updatedColor + ', ' + updatedColor + '), ' + background;
				}
			}

			// Update the background-image property.
			if ( updatedBackground ) {
				header.css( 'background-image', updatedBackground );
			}
		} );
	}

	/**
	 * Loop over registered nav menu instances and their arguments
	 * to handle the CSS color controls for hamburgers on each.
	 *
	 * @since 2.0.0
	 */
	menus() {
		var menuLocations = Object.values( _wpCustomizePreviewNavMenusExports.navMenuInstanceArgs );
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

				// Set menu border colors.
				this.setMenuConfigs( props.theme_location, props.menu_id );

				// Set Defaults.
				this.setHamburgerColors( props.theme_location, props.menu_id );

				// Set Defaults.
				this.setHoverColors( props.theme_location );

				// Set active link colors.
				this.setActiveLinkColor( props.theme_location, props.menu_id );

				// Set active link colors.
				this.setActiveSubLinkColor( props.theme_location, props.menu_id );

				// Set supplementary menu CSS.
				this.setMenuColors( props.theme_location );

				// Setup event handlers.
				this._bindHoverColors( props.theme_location );

				// Setup event handlers.
				this._bindMenuColors( props.theme_location );

				// Setup active link color even handlers.
				this._bindActiveLinkColors( props.theme_location, props.menu_id );

				// Setup hover link color even handlers.
				this._bindHoverLinkColors( props.theme_location, props.menu_id );

				// Setup event handlers.
				this._bindHamburgerColors( props.theme_location, props.menu_id );
			}
		}
	}

	/**
	 * Events to run when the Dom loads.
	 *
	 * @since 2.0.0
	 */
	_onLoad() {

		// Set Defaults.
		this.setHeadingColors();
		this.menus();

		// Setup event handlers.
		this._bindConfiguredControls();
		this._bindHeadingColor();
		this._bindWeformsColor();
		this._bindWeformsSubColor();
		this._bindHeaderOverlay();
		this._bindEntryHeader();
		this._bindGlobalPageTitles();
	}

	/**
	 * Bind the event of the .entry-headers changing colors.
	 *
	 * @since 2.0.0
	 */
	_bindEntryHeader() {
		wp.customize( 'bgtfw_blog_header_background_color', ( ...args ) => {
			args.map( ( control ) => control.bind( () => this.setPageTitles( 'bgtfw_blog_header_background_color', '.has-post-thumbnail .entry-header:not(.page-header)' ) ) );
		} );
	}

	/**
	 * Bind the event of the .entry-headers changing colors.
	 *
	 * @since 2.0.0
	 */
	_bindGlobalPageTitles() {
		wp.customize( 'bgtfw_global_title_background_color', ( ...args ) => {
			args.map( ( control ) => control.bind( () => this.setPageTitles( 'bgtfw_global_title_background_color', '.page-header.has-featured-image-header' ) ) );
		} );
	}

	/**
	 * Bind the event of the overlay changing colors.
	 *
	 * @since 2.0.0
	 */
	_bindHeaderOverlay() {
		wp.customize( 'bgtfw_header_overlay_alpha', 'bgtfw_header_overlay', 'bgtfw_header_overlay_color', ( ...args ) => {
			args.map( ( control ) => control.bind( () => this.setHeaderOverlay() ) );
		} );
	}

	/**
	 * Bind the event of the overlay changing colors.
	 *
	 * @since 2.0.0
	 */
	_bindMenuColors( location ) {
		wp.customize( `bgtfw_menu_background_${location}`, `bgtfw_menu_submenu_background_${location}`, 'bgtfw_header_color', 'bgtfw_footer_color', ( ...args ) => {
			args.map( ( control ) => control.bind( () => this.setMenuColors( location ) ) );
		} );
	}

	/**
	 * Bind all color class controls given through class property.
	 *
	 * @since 2.0.0
	 */
	_bindConfiguredControls() {
		for ( const control of this.classControls ) {
			wp.customize( control.name, ( value ) => {
				value.bind( () => this.outputColor( control.name, control.selector, control.properties ) );
			} );
		}
	}

	/**
	 * Bind the change events for heading colors changing.
	 *
	 * @since 2.0.0
	 */
	_bindHeadingColor() {
		wp.customize( 'bgtfw_headings_color', ( value ) => {
			value.bind( () => this.setHeadingColors() );
		} );
	}

	/**
	 * Bind the change events for weforms label colors changing.
	 *
	 * @since 2.15.0
	 */
	_bindWeformsColor() {
		wp.customize( 'bgtfw_weforms_label_color', ( value ) => {
			value.bind( () => this.setWeformsColors() );
		} );
	}

	/**
	 * Bind the change events for weForms sub-label colors changing.
	 *
	 * @since 2.15.0
	 */
	_bindWeformsSubColor() {
		wp.customize( 'bgtfw_weforms_sublabel_color', ( value ) => {
			value.bind( () => this.setWeformsSubColors() );
		} );
	}

	/**
	 * Bind the change events for hamburger colors
	 *
	 * @since 2.0.0
	 */
	_bindHamburgerColors( location, menuId ) {
		wp.customize( `bgtfw_menu_hamburger_${location}_color`, ( value ) => {
			value.bind( () => this.setHamburgerColors( location, menuId ) );
		} );
	}

	/**
	 * Bind the change events for hamburger colors
	 *
	 * @since 2.0.0
	 */
	_bindHoverColors( location ) {
		wp.customize( `bgtfw_menu_items_hover_color_${location}`, ( value ) => {
			value.bind( () => this.setHoverColors( location ) );
		} );
		wp.customize( `bgtfw_menu_items_hover_background_${location}`, ( value ) => {
			value.bind( () => this.setHoverColors( location ) );
		} );
	}

	/**
	 * Bind active link color settings
	 *
	 * @since 2.0.0
	 */
	_bindActiveLinkColors( location, menuId ) {
		wp.customize( `bgtfw_menu_items_active_link_color_${location}`, ( value ) => {
			value.bind( () => this.setActiveLinkColor( location, menuId ) );
		} );

		wp.customize( `bgtfw_menu_items_sub_active_link_color_${location}`, ( value ) => {
			value.bind( () => this.setActiveSubLinkColor( location, menuId ) );
		} );
	}

	/**
	 * Bind hover link color settings
	 *
	 * @since 2.0.0
	 */
	_bindHoverLinkColors( location, menuId ) {
		wp.customize( `bgtfw_menu_items_hover_link_color_${location}`, ( value ) => {
			value.bind( () => this.setHoverLinkColor( location, menuId ) );
		} );
	}
}

export default Preview;

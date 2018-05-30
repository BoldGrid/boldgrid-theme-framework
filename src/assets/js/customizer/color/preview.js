import PaletteSelector from './palette-selector';
import { Preview as PreviewUtility } from '../preview';

const $ = jQuery;

export class Preview  {

	constructor() {
		this.previewUtility = new PreviewUtility();

		this.classControls = [
			{
				name: 'bgtfw_header_links',
				selector: '#navi-wrap',
				properties: [ 'link-color' ]
			},
			{
				name: 'bgtfw_footer_color',
				selector: '#colophon, .footer-content',
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
				name: 'bgtfw_footer_links',
				selector: '.footer-content',
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
	outputColor( themeMod, selector, properties ) {
		let colorClassPrefix,
			$selector = $( selector ),
			regex = new RegExp( 'color-?([\\d]|neutral)-(' + properties.join( '|' ) + ')(\\s+|$)', 'g' );

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
	 * Get a list of heading selectors from the global.
	 *
	 * @since 2.0.0
	 *
	 * @return {array} list of selectors.
	 */
	getHeadingColorSelectors() {
		const selectors = [];

		_.each( window._typographyOptions, function( value, key ) {
			if ( 'headings' === value.type ) {
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
	 *
	 * @param {String} to Thememod's color value.
	 * @param {String} menuId Menu ID for nav menu instance.
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
	 * Set the overlay colors.
	 *
	 * @since 2.0.0
	 */
	setHeaderOverlay() {
		const selector = new PaletteSelector(),
			color = selector.getColor( wp.customize( 'bgtfw_header_overlay_color' )() ),
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
	 * Loop over registered nav menu instances and their arguments
	 * to handle the CSS color controls for hamburgers on each.
	 *
	 * @since 2.0.0
	 */
	hamburgers() {
		for ( const props of Object.entries( window._wpCustomizePreviewNavMenusExports.navMenuInstanceArgs ) ) {

			// Set Defaults.
			this.setHamburgerColors( props.theme_location, props.menu_id );

			// Setup event handlers.
			this._bindHamburgerColors( props.theme_location, props.menu_id );
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
		this.hamburgers();

		// Setup event handlers.
		this._bindConfiguredControls();
		this._bindHeadingColor();
		this._bindHeaderOverlay();
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
	 * Bind the change events for hamburger colors
	 *
	 * @since 2.0.0
	 */
	_bindHamburgerColors( location, menuId ) {
		wp.customize( `bgtfw_menu_hamburger_${location}_color`, ( value ) => {
			value.bind( () => this.setHamburgerColors( location, menuId ) );
		} );
	}

}

export default Preview;

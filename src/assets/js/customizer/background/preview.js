/* global BoldGrid:true */

import PaletteSelector from '../color/palette-selector';
import ColorPreview from '../color/preview';
import { Preview as PreviewUtility } from '../preview';


const api = wp.customize;

/**
 * Setup all events for previewing background changes.
 *
 * @since 2.0.0
 *
 * @type {Preview}
 */
export class Preview {

	/**
	 * Instatiate the class.
	 *
	 * @since 2.0.0
	 */
	constructor() {
		this.selector = new PaletteSelector();
		this.colorPreview = new ColorPreview().init();
	}

	/**
	 * Initialize this class, binding all events.
	 *
	 * @since 2.0.0
	 *
	 * @return {Preview} Class Instance.
	 */
	init() {
		$( () => this._onLoad() );

		return this;
	}

	/**
	* Set the overlay colors.
	*
	* @since 2.0.0
	*/
	setImage() {
		if ( 'image' === api( 'boldgrid_background_type' )() ) {
			const backgroundImage = api( 'background_image' )();

			if ( backgroundImage ) {
				if ( 'parallax' === api( 'background_attachment' )() ) {
					if ( ! document.querySelector( 'body > [id^="jarallax-container"]' ) ) {
						if ( ! BoldGrid.hooks.didAction( 'bgtfwParallaxReady' ) ) {
							if ( ! _.isUndefined( BoldGrid.hooks.actions.bgtfwParallaxReady ) && ! BoldGrid.hooks.actions.bgtfwParallaxReady.handlers ) {
								BoldGrid.hooks.addAction( 'bgtfwParallaxReady', 'bgtfwBackgroundPreview', () => this.setImage() );
							}
						} else {
							BoldGrid.hooks.removeAction( 'bgtfwParallaxReady', 'bgtfwBackgroundPreview' );
						}
					}
				}
				document.querySelectorAll( 'body, body > [id^="jarallax-container"] > div' ).forEach( el => {
					el.style.backgroundImage = `url("${backgroundImage}")`;
				} );

				let util = new PreviewUtility();
				util.updateDynamicStyles( 'bgtfw-background-inline-css', api( 'bgtfw_background_overlay' )() ? this._getOverlayCss() : '' );
			}
		}
	}

	/**
	 * Set the body color classes.
	 *
	 * @since 2.0.0
	 */
	setBodyClasses() {
		if ( 'image' === api( 'boldgrid_background_type' )() ) {
			if ( api( 'background_image' )() && api( 'bgtfw_background_overlay' )() ) {
				this.colorPreview.outputColor( 'bgtfw_background_overlay_color', 'body', [ 'text-default' ] );
			}
		}
	}

	/**
	 * Process to run when the page loads.
	 *
	 * @since 2.0.0
	 */
	_onLoad() {
		this._setupImageChange();
	}

	/**
	 * Get the css needed to display an overlay.
	 *
	 * @since 2.0.0
	 *
	 * @return {string}                 CSS for background image.
	 */
	_getOverlayCss() {
		const color = this.selector.getColor( api( 'bgtfw_background_overlay_color' )(), true ),
			alpha = api( 'bgtfw_background_overlay_alpha' )(),
			brehautColor = parent.net.brehaut.Color( color ),
			rgba = brehautColor.setAlpha( alpha ).toString(),
			blendMode = api( 'bgtfw_background_overlay_type' )(),
			selector = 'body.custom-background, body.custom-background > [id^="jarallax-container"] > div';

		return `@supports(background-blend-mode:${blendMode}) { ${selector} { background-color: ${rgba} !important; background-blend-mode: ${blendMode}; } }` +
			`@supports not (background-blend-mode: ${blendMode}) { ${selector} { background-color: ${brehautColor.toString()} !important; opacity: ${alpha}; } }`;
	}

	/**
	 * When the background image changes or any related properties, update the image.
	 *
	 * @since 2.0.0
	 */
	_setupImageChange() {
		api(
			'bgtfw_background_overlay_alpha',
			'bgtfw_background_overlay',
			'bgtfw_background_overlay_color',
			'bgtfw_background_overlay_type',
			'background_image',
			( ...args ) => {
				args.map( control =>
					control.bind( () => {
						this.setImage();
						this.setBodyClasses();
					} )
				);
			}
		);
	}
}

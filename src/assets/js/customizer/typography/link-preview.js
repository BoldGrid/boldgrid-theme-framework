import { Preview as PreviewUtility } from '../preview';
import PaletteSelector from '../color/palette-selector';

const api = wp.customize;
const $ = jQuery;
const colorLib = window.net.brehaut;

export class LinkPreview {

	/**
	 * Instantiate other classes to be used.
	 */
	constructor() {
		this.previewUtility = new PreviewUtility();
		this.paletteSelector = new PaletteSelector();
		this.prefixes = [
			'bgtfw_body',
			'bgtfw_posts_date',
			'bgtfw_posts_byline'
		];
		this.selectors = {};
	}

	/**
	 * When the user changes any of the options in the set, update the css.
	 *
	 * @since 2.0.0
	 */
	bindEvents() {
		$( () => {
			let prefixes = this.prefixes;
			for ( let prefix of prefixes ) {
				this.selectors[ prefix ] = this.getSelectors( prefix );
				api(
					`${prefix}_link_color_display`,
					`${prefix}_link_color`,
					`${prefix}_link_color_hover`,
					`${prefix}_link_decoration`,
					`${prefix}_link_decoration_hover`,
					( ...args ) => {
						args.map( control => {
							control.bind( () => this.updateStyles( prefix ) );
						} );
					}
				);
			}
		} );
	}

	/**
	 * Get the selectors for links.
	 *
	 * @since 2.0.0
	 *
	 * @return {array} Selectors to use.
	 */
	getSelectors( prefix ) {
		let selectors = [];
		if ( parent.wp.customize.control && parent.wp.customize.control( `${prefix}_link_color` ) ) {
			selectors = parent.wp.customize.control( `${prefix}_link_color` ).params.choices.selectors;
		}

		return selectors;
	}

	/**
	 * Update the styles for the content.
	 *
	 * @since 2.0.0
	 */
	updateStyles( prefix ) {
		let css = '';
		if ( ! api( `${prefix}_link_color_display` ) || 'inherit' !== api( `${prefix}_link_color_display` )() ) {
			let linkColor = this._getColor( `${prefix}_link_color` ),
				linkColorHover = api( `${prefix}_link_color_hover` )() || 0,
				decoration = this._getDecoration( `${prefix}_link_decoration` ),
				decorationHover = this._getDecoration( `${prefix}_link_decoration_hover` ),
				excludes = '',
				selectors = this.selectors[ prefix ],
				shiftedColorVal;

			linkColorHover = parseInt( linkColorHover, 10 ) / 100;
			shiftedColorVal = colorLib.Color( linkColor ).lightenByAmount( linkColorHover ).toCSS();

			for ( let selector of selectors ) {
				selector = selector + excludes;
				css += `
					${selector} {
						color: ${linkColor};
						text-decoration: ${decoration};
					}
					${selector}:hover,
					${selector}:focus {
						color: ${shiftedColorVal};
						text-decoration: ${decorationHover};
					}
				`;
			}
		}

		let inlineName = prefix.replace( /_/g, '-' );
		this.previewUtility.updateDynamicStyles( `${inlineName}-link-inline-css`, css );
	}

	/**
	 * Get the setting text decoration value.
	 *
	 * @since 2.0.0
	 *
	 * @param  {string} setting Setting Name.
	 * @return {string}         CSS value.
	 */
	_getDecoration( setting ) {
		return api( setting )();
	}

	/**
	 * Get the color from a setting.
	 *
	 * @since 2.0.0
	 *
	 * @param  {string} setting Setting Name.
	 * @return {string}         Saved Color.
	 */
	_getColor( setting ) {
		let color = api( setting )() || '';
		return this.paletteSelector.getColor( color );
	}

}

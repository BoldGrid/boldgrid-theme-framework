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

		this.selectors = [];
	}

	/**
	 * When the user changes any of the options in the set, update the css.
	 *
	 * @since 2.0.0
	 */
	bindEvents() {
		$( () => this.selectors = this.getSelectors() );

		api(
			'bgtfw_body_link_color',
			'bgtfw_body_link_color_hover',
			'bgtfw_body_link_decoration',
			'bgtfw_body_link_decoration_hover',
			( ...args ) => {
				args.map( control => {
					control.bind( () => this.updateStyles() );
				} );
			}
		);
	}

	/**
	 * Get the selectors for links.
	 *
	 * @since 2.0.0
	 *
	 * @return {array} Selectors to use.
	 */
	getSelectors() {
		let selectors = [ '#content a' ];
		if ( parent.wp.customize.control && parent.wp.customize.control( 'bgtfw_body_link_color' ) ) {
			selectors = parent.wp.customize.control( 'bgtfw_body_link_color' ).params.choices.selectors;
		}

		return selectors;
	}

	/**
	 * Update the styles for the content.
	 *
	 * @since 2.0.0
	 */
	updateStyles() {
		let linkColor = this._getColor( 'bgtfw_body_link_color' ),
			linkColorHover = api( 'bgtfw_body_link_color_hover' )() || 0,
			decoration = this._getDecoration( 'bgtfw_body_link_decoration' ),
			decorationHover = this._getDecoration( 'bgtfw_body_link_decoration_hover' ),
			excludes = ':not(.btn):not(.button-primary):not(.button-secondary)',
			selectors = this.selectors,
			shiftedColorVal,
			css = '';

		linkColorHover = parseInt( linkColorHover, 10 ) / 100;
		shiftedColorVal = colorLib.Color( linkColor ).lightenByAmount( linkColorHover ).toCSS();

		for ( let selector of selectors ) {
			selector = selector + excludes;
			css += `
				${selector} {
					color: ${linkColor};
					text-decoration: ${decoration};
				}
				${selector}:hover {
					color: ${shiftedColorVal};
					text-decoration: ${decorationHover};
				}
			`;
		}

		this.previewUtility.updateDynamicStyles( 'bgftw-body-link-inline-css', css );
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
		return api( setting )() ? 'underline' : 'none';
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

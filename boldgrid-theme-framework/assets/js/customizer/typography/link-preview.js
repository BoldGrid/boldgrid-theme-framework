import { Preview as PreviewUtility } from '../preview';
import PaletteSelector from '../color/palette-selector';

const api = wp.customize;

export class LinkPreview {

	/**
	 * Instantiate other classes to be used.
	 */
	constructor() {
		this.previewUtility = new PreviewUtility();
		this.paletteSelector = new PaletteSelector();
	}

	/**
	 * When the user changes any of the options in the set, update the css.
	 *
	 * @since 2.0.0
	 */
	bindEvents() {
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
	 * Update the styles for the content.
	 *
	 * @since 2.0.0
	 */
	updateStyles() {
		let linkColor = this._getColor( 'bgtfw_body_link_color' ),
			linkColorHover = this._getColor( 'bgtfw_body_link_color_hover' ),
			decoration = this._getDecoration( 'bgtfw_body_link_decoration' ),
			decorationHover = this._getDecoration( 'bgtfw_body_link_decoration_hover' ),
			excludes = ':not(.btn):not(.button-primary):not(.button-secondary)',
			selectors = [ '#content a' ],
			css = '';

		for ( let selector of selectors ) {
			selector = selector + excludes;
			css += `
				${selector} {
					color: ${linkColor};
					text-decoration: ${decoration};
				}
				${selector}:hover {
					color: ${linkColorHover};
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

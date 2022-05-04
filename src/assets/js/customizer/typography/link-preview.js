import { Preview as PreviewUtility } from '../preview';
import PaletteSelector from '../color/palette-selector';

const api = wp.customize;
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
			'bgtfw_posts_byline',
			'bgtfw_posts_tags',
			'bgtfw_posts_cats',
			'bgtfw_posts_navigation',
			'bgtfw_blog_post_header_byline',
			'bgtfw_blog_post_header_date',
			'bgtfw_blog_post_cats',
			'bgtfw_blog_post_tags',
			'bgtfw_blog_post_comments',
			'bgtfw_blog_post_readmore'
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

				let controls = [
					`${prefix}_link_color`,
					`${prefix}_link_color_hover`,
					`${prefix}_link_decoration`,
					`${prefix}_link_decoration_hover`
				];

				// Body links don't have a _link_color_display option.
				if ( 'bgtfw_body' !== prefix ) {
					controls.push( `${prefix}_link_color_display` );
				}

				api( ...controls, ( ...args ) => {
					args.map( control => control.bind( () => this.updateStyles( prefix ) ) );
				} );
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
		if ( false === _.isFunction( api( `${prefix}_link_color_display` ) ) || 'inherit' !== api( `${prefix}_link_color_display` )() ) {
			let linkColor = this._getColor( `${prefix}_link_color`, true ),
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

			/*
			 * This was added in 2.12.0 to make sure that the booter links can be
			 * controlled by the Site Content Link typography controls.
			 */
			if ( 'bgtfw_body' === prefix ) {
				let footerLinkColor      = this._getColor( 'bgtfw_footer_links', true ),
					footerLinkColorHover = api( `${prefix}_link_color_hover` )() || 0,
					footerShiftedColorVal;

				footerLinkColorHover  = parseInt( footerLinkColorHover, 10 ) / 100,
				footerShiftedColorVal = colorLib.Color( footerLinkColor ).lightenByAmount( footerLinkColorHover ).toCSS();

				css += `
				#colophon .bgtfw-footer.footer-content > a:not( .btn ),
				#colophon .bgtfw-footer.footer-content *:not( .menu-item ) > a:not( .btn ) {
					text-decoration: ${decoration};
				}
				#colophon .bgtfw-footer.footer-content > a:not( .btn ):hover,
				#colophon .bgtfw-footer.footer-content > a:not( .btn ):focus,
				#colophon .bgtfw-footer.footer-content *:not( .menu-item ) > a:not( .btn ):hover,
				#colophon .bgtfw-footer.footer-content *:not( .menu-item ) > a:not( .btn ):focus {
						color: ${footerShiftedColorVal};
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
	 * @param  {string} setting  Setting Name.
	 * @param  {bool}   variable Whether to return variable or true color
	 * @return {string}          Saved Color.
	 */
	_getColor( setting, variable = false ) {
		let color = api( setting )() || '';
		return this.paletteSelector.getColor( color, variable );
	}

}

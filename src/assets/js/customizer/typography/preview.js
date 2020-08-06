/* global BOLDGRID:false, global kirkiPostMessageFields*/
const api = wp.customize;

/**
 * This class is responsible for managing the typograpy
 * live preview in the WordPress customizer.
 *
 * @since 2.0.0
 */
export class Preview {

	/**
	 * Initialize class and bind events.
	 *
	 * @since 2.0.0
	 */
	bindEvents() {
		$( () => this._bindTypography() );
	}

	/**
	 * Handle the toggle of classes on an element.
	 *
	 * @since 2.0.0
	 * @param {Mixed} to New value control is updating to.
	 * @return {String} css CSS to add to page.
	 */
	getCSS( to ) {
		if ( _.isUndefined( to ) ) {
			to = api( 'bgtfw_headings_typography' )();
		}

		// JSON returned sometimes.
		if ( _.isString( to ) ) {
			to = JSON.parse( to );
		}

		let base = api( 'bgtfw_headings_font_size' )();
		let unit = 'px';

		// Build CSS.
		let css = '';

		_.each( BOLDGRID.CUSTOMIZER.data.customizerOptions.typography.selectors, function( selector, rule ) {
			var val;
			if ( 'headings' === selector.type ) {
				val = base * selector.amount;
				if ( 'ceil' === selector.round ) {
					val = Math.ceil( val );
				}
				if ( 'floor' === selector.round ) {
					val = Math.floor( val );
				}
				css += rule + '{font-size:' + val + unit + ';}';
			}
		} );

		return css;
	}

	/**
	 * Set CSS in the innerHTML of stylesheet or create a new stylesheet to
	 * append to head.
	 *
	 * @since 2.0.0
	 */
	addStyle( css ) {
		if ( document.getElementById( 'bgtfw-headings-typography' ) ) {
			document.getElementById( 'bgtfw-headings-typography' ).innerHTML = css;
		} else {
			let head = document.head || document.getElementsByTagName( 'head' )[0];
			let style = document.createElement( 'style' );

			style.type = 'text/css';
			style.id = 'bgtfw-headings-typography';

			if ( style.styleSheet ) {
				style.styleSheet.cssText = css;
			} else {
				style.appendChild( document.createTextNode( css ) );
			}

			head.appendChild( style );
		}

		// Check if kirki's post-message already applied inline CSS and move our CSS after for override.
		if ( document.getElementById( 'kirki-postmessage-bgtfw_headings_typography' ) ) {
			$( '#bgtfw-headings-typography' ).insertAfter( '#kirki-postmessage-bgtfw_headings_typography' );
		}
	}

	/**
	 * Bind Typography Controls.
	 *
	 * @since 2.0.0
	 */
	_bindTypography() {
		var typographyControls = [
			'bgtfw_body_typography',
			'bgtfw_headings_typography',
			'bgtfw_tagline_typography',
			'bgtfw_site_title_typography',
			'bgtfw_menu_typography_main'
		];
		this.addStyle( this.getCSS( api( 'bgtfw_headings_typography' )() ) );

		typographyControls.forEach( control => this.addTypographyOverride( control ) );

		api( 'bgtfw_headings_typography', value => value.bind( to => this.addStyle( this.getCSS( to ) ) ) );
		api( 'bgtfw_headings_font_size', value => value.bind( to => this.addStyle( this.getCSS( to ) ) ) );
	}

	/**
	 * Override Typography styles in customizer.
	 *
	 * @since 2.2.2
	 */
	addTypographyOverride( control ) {
		api( control, function( value ) {
			value.bind( function( css ) {
				var cssSelectors,
					cssText,
					adjustedCss;
				if ( _.isString( css ) ) {
					adjustedCss = JSON.parse( css );
				} else {
					return;
				}

				if ( /\s/.test( adjustedCss['font-family'] ) ) {
					adjustedCss['font-family'] = '"' + adjustedCss['font-family'] + '"';
				}
				kirkiPostMessageFields.forEach( function( field ) {
					if ( ! cssSelectors && control === field.id && field.output[0].element ) {
						cssSelectors = field.output[0].element;
					}
				} );

				cssText    = cssSelectors + ' {font-family: ' + adjustedCss['font-family'] + ';}';
				let head   = document.head || document.getElementsByTagName( 'head' )[0];
				let style  = document.createElement( 'style' );
				style.type = 'text/css';
				style.id   = control + '-font-families';

				if ( style.styleSheet ) {
					style.styleSheet.cssText = cssText;
				} else {
					style.appendChild( document.createTextNode( cssText ) );
				}

				head.appendChild( style );
			} );
		} );
	}
}

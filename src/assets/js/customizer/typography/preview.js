/* global BOLDGRID:false, global kirkiPostMessageFields*/
const api = wp.customize;
const controlApi = parent.wp.customize;

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
	 * @param {String} controlId Control ID.
	 * @return {String} css CSS to add to page.
	 */
	getCSS( to, controlId = 'bgtfw_headings_typography' ) {
		var variant,
			fontWeight,
			fontStyle,
			base,
			unit,
			controlType;
		if ( _.isUndefined( to ) ) {
			to = api( controlId )();
		}

		controlType = controlId.replace( 'bgtfw_', '' );
		controlType = controlType.replace( '_typography', '' );

		// JSON returned sometimes.
		try {
			to = JSON.parse( to );
		} catch ( err ) {

			// Do nothing on error.
		}

		// Handle variant of font sizes.
		variant    = to.variant;
		fontWeight = variant ? parseInt( variant ) : 'initial';
		fontStyle  = variant ? variant.replace( fontWeight, '' ) : 'initial';

		// Build CSS.
		let css = '';

		// If this is for headings, we must determine the base font size.
		if ( 'bgtfw_headings_typography' === controlId ) {
			base = parseInt( api( 'bgtfw_headings_font_size' )() );
			unit = api( 'bgtfw_headings_font_size' )().replace( base, '' );
			unit = unit ? unit : 'px';
			let matches = unit.match( /(em|ex|%|px|cm|mm|in|pt|pc|rem)/ );
			unit = matches ? matches[0] : 'px';
		}

		_.each( BOLDGRID.CUSTOMIZER.data.customizerOptions.typography.selectors, function( selector, rule ) {
			var val;

			// If this is for headings, we must calculate the appropriate 'h' value.
			if ( 'headings' === selector.type && 'bgtfw_headings_typography' === controlId ) {
				val = base * selector.amount;
				if ( 'ceil' === selector.round ) {
					val = Math.ceil( val );
				}
				if ( 'floor' === selector.round ) {
					val = Math.floor( val );
				}

				// Adds css for font variants.
				if ( fontWeight && fontStyle ) {
					css += rule + '{font-size:' + val + unit + ';';
					css += 'font-style:' + fontStyle + ';';
					css += 'font-weight:' + fontWeight + ';}';
				} else if ( fontWeight ) {
					css += rule + '{font-size:' + val + unit + ';';
					css += 'font-weight:' + fontWeight + ';}';
				}
				css += rule + '{font-size:' + val + unit + ';}';

			// Selector lists are pulled from the customizer options matched to controlType.
			} else if ( controlType === selector.type ) {

				// Adds css for font variants.
				if ( fontWeight && fontStyle ) {
					css += rule + '{';
					css += 'font-style:' + fontStyle + ';';
					css += 'font-weight:' + fontWeight + ';}';
				} else if ( fontWeight ) {
					css += rule + '{';
					css += 'font-weight:' + fontWeight + ';}';
				} else if ( fontStyle ) {
					css += rule + '{';
					css += 'font-style:' + fontStyle + ';}';
				}
			}
		} );

		// Menus are different, because the selectors are dynamic.
		if ( controlType.includes( 'menu' ) ) {
			let rule = controlType.replace( /_/g, '-' );
				rule = rule.replace( 'menu-', '' );
				rule = '#' + rule + '-menu li a';
			if ( fontWeight && fontStyle ) {
				css += rule + '{';
				css += 'font-style:' + fontStyle + ';';
				css += 'font-weight:' + fontWeight + ';}';
			} else if ( fontWeight ) {
				css += rule + '{';
				css += 'font-weight:' + fontWeight + ';}';
			} else if ( fontStyle ) {
				css += rule + '{';
				css += 'font-style:' + fontStyle + ';}';
			}
		}

		return css;
	}

	/**
	 * Set CSS in the innerHTML of stylesheet or create a new stylesheet to
	 * append to head.
	 *
	 * @since 2.0.0
	 */
	addStyle( css, controlId = 'bgtfw_headings_typography' ) {
		var styleId = controlId.replace( /_/g, '-' );
		if ( document.getElementById( styleId ) ) {
			document.getElementById( styleId ).innerHTML = css;
		} else {
			let head = document.head || document.getElementsByTagName( 'head' )[0];
			let style = document.createElement( 'style' );

			style.type = 'text/css';
			style.id = styleId;

			if ( style.styleSheet ) {
				style.styleSheet.cssText = css;
			} else {
				style.appendChild( document.createTextNode( css ) );
			}

			head.appendChild( style );
		}

		// Check if kirki's post-message already applied inline CSS and move our CSS after for override.
		if ( document.getElementById( 'kirki-postmessage-' + controlId ) ) {
			$( '#' + styleId ).insertAfter( '#kirki-postmessage-' + controlId );
		}
	}

	/**
	 * Bind Typography Controls.
	 *
	 * As of 2.11.0, this control is used for ALL typography controls,
	 * not just bgtfw_headings_typography and bgtfw_headings_font_size.
	 * Therefore, we need to bind to all controls and use appropriate logic
	 * to determine which control is being used.
	 *
	 * @since 2.0.0
	 */
	_bindTypography() {
		var typographyControls = [];

		controlApi.control.each( control => {
			if ( 'kirki-typography' === control.params.type ) {
				typographyControls.push( control.id );
			}
		} );

		typographyControls.forEach( controlId => this.addTypographyOverride( controlId ) );

		typographyControls.forEach( controlId => {
			api( controlId, value => value.bind( to => this.addStyle( this.getCSS( to, controlId ), controlId ) ) );
		} );

		api( 'bgtfw_headings_font_size', value => value.bind( to => this.addStyle( this.getCSS( to ) ) ) );
	}

	/**
	 * Override Typography styles in customizer.
	 *
	 * As of 2.11.0, this control is used for ALL typography controls.
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

				// Adds quotes to font-family if there is a space in it.
				if ( /\s/.test( adjustedCss['font-family'] ) ) {
					adjustedCss['font-family'] = '"' + adjustedCss['font-family'] + '"';
				}

				// Defines cssSelectors based on the control's output field.
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

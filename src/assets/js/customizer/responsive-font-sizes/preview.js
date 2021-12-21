/* global BOLDGRID */

/**
 * File: src/assets/js/customizer/bgtfw-responsive-font-sizes/preview.js
 *
 * Handles the previewing of responsive font sizes in the WP Customizer.
 *
 * @package Boldgrid_Theme_Framework
 * @subpackage Boldgrid_Theme_Framework/Customizer/Preview
 *
 * @since 2.11.0
 */

import { Preview as PreviewUtility } from '../preview';

/**
 * Class: Preview
 *
 * Extends functionality of PreviewUtility to the Responsive Font Controls.
 *
 * @since 2.11.0
 */
export class Preview {

	/**
	 * Constructor
	 *
	 * Initialize the Preview class, and PreviewUtility class.
	 *
	 * @since 2.11.0
	 */
	constructor() {
		this.preview = new PreviewUtility();
	}

	/**
	 * Bind Events.
	 *
	 * Loop through all controls, and bind control events for
	 * responsive typography controls.
	 *
	 * @since 2.11.0
	 */
	bindEvents() {
		if ( parent.wp.customize.control ) {
			parent.wp.customize.control.each( wpControl => {
				if ( 'bgtfw-responsive-typography' === wpControl.params.type ) {
					this.bindControl( wpControl );
				}
			} );
		}
	}

	/**
	 * Bind a single WordPress control's change event.
	 *
	 * @since 2.11.0
	 *
	 * @param  {object} wpControl WordPress control instance.
	 */
	bindControl( wpControl ) {
		var controlId = wpControl.id;
		wp.customize( controlId, ( control ) => {
			control.bind( ( value ) => {
				var selectors;

				// Headings use the default typography selectors.
				if ( 'bgtfw_headings_responsive_font_size' === controlId ) {
					selectors = Object.keys( BOLDGRID.CUSTOMIZER.data.customizerOptions.typography.selectors );
					selectors = selectors.join( ', ' );

				// All other controls use the specified responsive_font_controls selectors.
				} else {
					selectors = BOLDGRID.CUSTOMIZER.data.customizerOptions.typography.responsive_font_controls[ controlId ].output_selector;
				}
				$( selectors ).css( 'opacity', '0.3' );

				/**
				 * Ajax call to obtain the CSS for the control's values.
				 *
				 * @see {wp_ajax_responsive_font_sizes}                                             Ajax action definition.
				 * @see {Boldgrid_Framework_Customizer_Typography::wp_ajax_responsive_font_sizes()} Ajax handler.
				 */
				$.ajax(
					wp.ajax.settings.url,
					{
						type: 'POST',
						context: this,
						data: {
							controlId: controlId,
							action: 'responsive_font_sizes',
							responsiveFontSizesNonce: wp.customize.settings.nonce.bgtfw_responsive_font_sizes,
							wpCustomize: 'on',
							responsiveFontSizes: JSON.parse( value )
						}
					}
				).done(
					( response ) => {
						this.preview.updateDynamicStyles(
							wpControl.id + '-bgcontrol-inline-css',
							response.data.css
						);
						$( selectors ).css( 'opacity', '1' );
					}
				);
			} );
		} );
	}
}

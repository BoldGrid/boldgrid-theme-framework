/* global BOLDGRID */
import { Preview as PreviewUtility } from '../preview';

export class Preview {
	constructor() {
		this.preview = new PreviewUtility();
	}

	/**
	 * Loop through all controls that are generic controls, and bind the change event.
	 *
	 * @since 2.0.0
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
	 * Bind a single WordPress controls change event.
	 *
	 * @since 2.0.0
	 *
	 * @param  {object} wpControl WordPress control instance.
	 */
	bindControl( wpControl ) {
		var controlId = wpControl.id;
		wp.customize( controlId, ( control ) => {
			control.bind( ( value ) => {
				var selectors;
				if ( 'bgtfw_headings_responsive_font_size' === controlId ) {
					selectors = Object.keys( BOLDGRID.CUSTOMIZER.data.customizerOptions.typography.selectors );
					selectors = selectors.join( ', ' );
				} else {
					selectors = BOLDGRID.CUSTOMIZER.data.customizerOptions.typography.responsive_font_controls[ controlId ].output_selector;
				}
				$( selectors ).css( 'opacity', '0.3' );
				$.ajax(
					wp.ajax.settings.url,
					{
						type: 'POST',
						context: this,
						data: {
							controlId: controlId,
							action: 'responsive_heading_sizes',
							responsiveHeadingSizesNonce: wp.customize.settings.nonce.bgtfw_responsive_heading_sizes,
							wpCustomize: 'on',
							responsiveHeadingSizes: JSON.parse( value )
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

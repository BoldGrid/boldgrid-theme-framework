/**
 * File: src/assets/js/customizer/max-width-containers/preview.js
 *
 * Handles the previewing of responsive font sizes in the WP Customizer.
 *
 * @package Boldgrid_Theme_Framework
 * @subpackage Boldgrid_Theme_Framework/Customizer/Preview
 *
 * @since 2.14.0
 */

 import { Preview as PreviewUtility } from '../preview';

 /**
  * Class: Preview
  *
  * Extends functionality of PreviewUtility to the Responsive Font Controls.
  *
  * @since 2.14.0
  */
 export class Preview {

	/**
	 * Constructor
	 *
	 * Initialize the Preview class, and PreviewUtility class.
	 *
	 * @since 2.14.0
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
	 * @since 2.14.0
	 */
	bindEvents() {
		if ( parent.wp.customize.control ) {
			parent.wp.customize.control.each( wpControl => {
				if ( wpControl.id.includes( 'container_max_width' ) ) {
					this.bindControl( wpControl );
				}
			} );
		}
	}

	/**
	 * Adjusts the css for full widht rows based on the max-width value.
	 *
	 * @param {string} value value of control
	 */
	adjustFullWidthRows( value ) {
		var deviceSizes = {},
			colClasses  = {},
			colSizes = {
				'1': 0.0833333,
				'2': 0.166667,
				'3': 0.25,
				'4': 0.3333333,
				'5': 0.4166667,
				'6': 0.5,
				'7': 0.5833333,
				'8': 0.6666667,
				'9': 0.75,
				'10': 0.8333333,
				'11': 0.9166667,
				'12': 1
			},
			css;

		value = JSON.parse( value.media );

		for ( const device in value ) {
			let maxWidth = value[ device ].values.maxWidth;
			switch ( device ) {
				case 'large':
					deviceSizes.lg = maxWidth;
					break;
				case 'desktop':
					deviceSizes.md = maxWidth;
					break;
				case 'tablet':
					deviceSizes.sm = maxWidth;
					break;
			}
		}

		for ( const colSize in colSizes ) {
			for ( const device in deviceSizes ) {
				let maxWidth = deviceSizes[device] * colSizes[ colSize ];
				colClasses[device] = 'undefined' === typeof colClasses[device] ? {} : colClasses[device];
				colClasses[device][ 'fwr-' + device + '-' + colSize ] = [ colSizes[ colSize ], maxWidth, colSize ];
			}
		}

		css = '';
		if ( colClasses.lg ) {
			css += '@media only screen and (min-width: 1200px) {';
			for ( const colClass in colClasses.lg ) {
				css += `body[data-container="max-full-width"] .boldgrid-section>.container-fluid .row.full-width-row .fwr-container .fwr-left-container.${colClass},
				body[data-container="max-full-width"] .boldgrid-section >.full-width .row.full-width-row .fwr-container .fwr-left-container.${colClass},
				body[data-container="max-full-width"] .boldgrid-section >.container-fluid .row.full-width-row > .fwr-container .fwr-right-container.${colClass},
				body[data-container="max-full-width"] .boldgrid-section >.full-width .row.full-width-row > .fwr-container .fwr-right-container.${colClass} {
					width: calc(calc( ${colClasses.lg[ colClass ][1]}px * ${colClasses.lg[ colClass ][0]} ) + calc( calc( 100% - ${colClasses.lg[ colClass ][1]}px ) / 2 ));
					min-width: calc(100vw * ${colClasses.lg[ colClass ][0]});
				}`;
			}
			css += '}';
		}
		if ( colClasses.md ) {
			css += '@media only screen and (max-width: 1199px) and (min-width: 992px) {';
			for ( const colClass in colClasses.md ) {
				css += `body[data-container="max-full-width"] .boldgrid-section>.container-fluid .row.full-width-row .fwr-container .fwr-left-container.${colClass},
				body[data-container="max-full-width"] .boldgrid-section >.full-width .row.full-width-row .fwr-container .fwr-left-container.${colClass},
				body[data-container="max-full-width"] .boldgrid-section >.container-fluid .row.full-width-row > .fwr-container .fwr-right-container.${colClass},
				body[data-container="max-full-width"] .boldgrid-section >.full-width .row.full-width-row > .fwr-container .fwr-right-container.${colClass} {
					width: calc(calc( ${colClasses.md[ colClass ][1]}px * ${colClasses.md[ colClass ][0]} ) + calc( calc( 100% - ${colClasses.md[ colClass ][1]}px ) / 2 ));
					min-width: calc(100vw * ${colClasses.md[ colClass ][0]});
				}`;
			}
		}
		$( '#bgtfw-full-width-row-inline-css' ).remove();
		$( 'head' ).append( `<style id="bgtfw-full-width-row-inline-css">${css}</style>` );
	}

	/**
	 * Bind a single WordPress control's change event.
	 *
	 * @since 2.14.0
	 *
	 * @param  {object} wpControl WordPress control instance.
	 */
	bindControl( wpControl ) {
		var controlId = wpControl.id;
		wp.customize( controlId, ( control ) => {
			control.bind( ( value ) => {
				var attrs = [ 'data-mw-base', 'data-mw-large', 'data-mw-desktop', 'data-mw-tablet' ];

				attrs.forEach( attr => {
					$( 'body' ).removeAttr( attr );
				} );

				this.adjustFullWidthRows( value );
			} );
		} );
	}
}

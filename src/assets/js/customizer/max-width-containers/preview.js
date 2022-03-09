/**
 * File: src/assets/js/customizer/max-width-containers/preview.js
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
				if ( wpControl.id.includes( 'container_max_width' ) ) {
					this.bindControl( wpControl );
				}
			} );
		}
	}

	adjustFullWidthRows( value ) {
		var $rows       = $( 'body[data-container="max-full-width"]' ).find( '.row.full-width-row' ),
			$cols       = $( $rows ).find( '.row.full-width-rows div[class^="col-"]' ),
			$fwrLeft    = $( $rows ).find( '.fwr-left' ),
			$fwrRight   = $( $rows ).find( '.fwr-right' ),
			deviceSizes = {},
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
				colClasses[ 'col-' + device + '-' + colSize ] = [ colSizes[ colSize ], maxWidth ];
			}
		}

		$cols.each( function() {
			var isFirst = $( this ).is( ':first-of-type' ),
				isLast  = $( this ).is( ':last-of-type' );

			if ( isFirst ) {
				$( this ).css( 'padding-right', '0' );
			}
			if ( isLast ) {
				$( this ).css( 'padding-left', '0' );
			}
		} );

		css = '';
		for ( const colClass in colClasses ) {
			css += `body[data-container="max-full-width"] .boldgrid-section>.container-fluid .row.full-width-row > div.${colClass} .fwr-left,
			body[data-container="max-full-width"] .boldgrid-section >.full-width .row.full-width-row > div.${colClass} .fwr-left,
			body[data-container="max-full-width"] .boldgrid-section >.container-fluid .row.full-width-row > div.${colClass} .fwr-right,
			body[data-container="max-full-width"] .boldgrid-section >.full-width .row.full-width-row > div.${colClass} .fwr-right {
				width: ${colClasses[ colClass ][1]}px;
				max-width: calc(( 100vw * ${colClasses[ colClass ][0]} ) - 5px );
			}
			body[data-container="max-full-width"] .boldgrid-section >.container-fluid .row.full-width-row > div.${colClass}:not( :first-of-type ):not( :last-of-type ),
			body[data-container="max-full-width"] .boldgrid-section >.full-width .row.full-width-row > div.${colClass}:not( :first-of-type ):not( :last-of-type ){
				width: ${colClasses[ colClass ][1]}px;
				max-width: calc(( 100vw * ${colClasses[ colClass ][0]} ) - 5px );
			}`;
		}

		$( '#bgtfw-full-width-row-inline-css' ).remove();
		$( 'head' ).append( `<style id="bgtfw-full-width-row-css">${css}</style>` );

		$fwrRight.css(
			{
				'float': 'left',
				'padding-right': '20px'
			}
		);
		$fwrLeft.css(
			{
				'float': 'right',
				'padding-left': '20px'
			}
		);

		console.log( {
			method: 'ContainerWidth.adjustFullWidthRows',
			'$row': $rows,
			'$cols': $cols,
			'$fwrLeft': $fwrLeft,
			'$fwrRight': $fwrRight,
			'value': value,
			'colClasses': colClasses,
			'css': css
		} );
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
				var attrs         = [ 'data-mw-base', 'data-mw-large', 'data-mw-desktop', 'data-mw-tablet' ],
					$fullWidthRows = $( '.container-fluid row.full-width-row, .full-width .row.full-width-row' );

				console.log( {
					method: 'ContainerWidth.bindControl',
					'fullWidthRows': $fullWidthRows
				} );

				attrs.forEach( attr => {
					$( 'body' ).removeAttr( attr );
				} );

				this.adjustFullWidthRows( value );
			} );
		} );
	}
}

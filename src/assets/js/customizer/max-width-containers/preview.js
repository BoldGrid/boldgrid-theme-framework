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
			control.bind( () => {
				var attrs = [ 'data-mw-base', 'data-mw-large', 'data-mw-destop', 'data-mw-tablet' ];
				attrs.forEach( attr => {
					$( 'body' ).removeAttr( attr );
				} );
			} );
		} );
	}
}

/**
 * File: src/assets/js/customizer/menus/preview.js
 *
 * Handles the previewing of responsive font sizes in the WP Customizer.
 *
 * @package    Boldgrid_Theme_Framework
 * @subpackage Boldgrid_Theme_Framework/Customizer/Preview
 *
 * @since 2.12.0
 */

 import { Preview as PreviewUtility } from '../preview';

 /**
  * Class: Preview
  *
  * Extends functionality of PreviewUtility to the Responsive Font Controls.
  *
  * @since 2.12.0
  */
 export class Preview {

	/**
	  * Constructor
	  *
	  * Initialize the Preview class, and PreviewUtility class.
	  *
	  * @since 2.12.0
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
	 * @since 2.12.0
	 */
	bindEvents() {
		if ( parent.wp.customize.control ) {
			parent.wp.customize.control.each( wpControl => {
				if ( wpControl.id.includes( 'bgtfw_menu_hamburger_display' ) ) {
					this.bindControl( wpControl );
				}
			} );
		}
	}

	/**
	 * Bind Control.
	 *
	 * Bind the control events for the hamburger menu controls.
	 *
	 * @since 2.12.0
	 *
	 * @param {object} wpControl WP_Customize_Control object.
	 */
	bindControl( wpControl ) {
		var controlId = wpControl.id;
		wp.customize( controlId, ( control ) => {
			control.bind( ( value ) => {
				var menuId         = '#' + controlId.replace( 'bgtfw_menu_hamburger_display_', '' ) + '-menu',
					displayClasses = [ 'ham-large', 'ham-desktop', 'ham-phone', 'ham-tablet' ],
					$menu;

				menuId = menuId.replace( /_(\d{3})/, '-$1' ),
				$menu  = $( menuId );
				$menu.parent().removeClass( displayClasses );
				$menu.parent().addClass( value );
			} );
		} );
	}
}

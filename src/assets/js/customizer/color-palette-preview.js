var BOLDGRID = BOLDGRID || {};
BOLDGRID.COLOR_PALETTE = BOLDGRID.COLOR_PALETTE || {};
BOLDGRID.COLOR_PALETTE.Preview = BOLDGRID.COLOR_PALETTE.Preview || {};

/**
 * Create a preview of palette
 * @param $
 */
( function( $ ) {
	'use strict';

	var self = BOLDGRID.COLOR_PALETTE.Preview;

	// OnLoad.
	$( function() {

		// When the page loads for the first time, this method wont be called.

		// This section of code is executed when the user changes pages in the customizer.
		if ( parent.BOLDGRID && parent.BOLDGRID.COLOR_PALETTE.Modify && parent.BOLDGRID.COLOR_PALETTE.Modify.text_area_val ) {
			self.update_css( parent.BOLDGRID.COLOR_PALETTE.Modify.text_area_val );
		}
	});

	/**
	 * Main responsibility of this file
	 * The to parameter, is a json string that is inside of a textarea in the parent window
	 * along with the compiled css file that is stored in memory in the parent
	 * This function attaches a new css file to the DOM
	 */
	self.update_css = function( to ) {
		var style, data, classes, modify;

		if ( ! to ) {
			return;
		}

		data = JSON.parse( to );
		modify = parent.BOLDGRID.COLOR_PALETTE.Modify;
		classes = _.isArray( modify.body_classes ) ? modify.body_classes.join( ' ' ) : '';

		// Update body class.
		$( 'body:not(.' + data.state['active-palette'] + ')' ).removeClass( classes ).addClass( data.state['active-palette'] );

		// Update styles.
		style = document.getElementById( 'boldgrid-color-palettes-inline-css' );
		style.innerHTML = modify.compiled_css;
	};

	/**
	 * Everytime the user changes the following setting, Update the css.
	 */
	wp.customize( 'boldgrid_color_palette', function( value ) {
		value.bind( self.update_css );

		// Update css field on updates.
		value.bind( function() {
			parent.BOLDGRID.COLOR_PALETTE.Modify.$compiled_css_control
				.val( parent.BOLDGRID.COLOR_PALETTE.Modify.compiled_css )
				.change();
		} );
	} );

	wp.customize( 'boldgrid_compiled_css', function( value ) {
		value.bind( function() {

			// Update native select element colors.
			BoldGrid.common.forms( true );
		} );
	} );

})( jQuery );

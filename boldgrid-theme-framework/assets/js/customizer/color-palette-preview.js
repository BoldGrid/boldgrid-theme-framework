var BOLDGRID = BOLDGRID || {};
BOLDGRID.COLOR_PALETTE = BOLDGRID.COLOR_PALETTE || {};
BOLDGRID.COLOR_PALETTE.Preview = BOLDGRID.COLOR_PALETTE.Preview || {};

/**
 * Create a preview of palette
 * @param $
 */
(function( $ ) {
	'use strict';
	var self = BOLDGRID.COLOR_PALETTE.Preview;

	self.$new_style = null;
	
	//OnLoad
	$(function () {
		//When the page loads for the first time, this method wont be called
		//This section of code is executed when the user changes pages in the customizer
		if (  parent.BOLDGRID && parent.BOLDGRID.COLOR_PALETTE.Modify.text_area_val ) {
			self.update_css(parent.BOLDGRID.COLOR_PALETTE.Modify.text_area_val);
		}
	});
	
	/**
	 * Main responsibility of this file
	 * The to parameter, is a json string that is inside of a textarea in the parent window
	 * along with the compiled css file that is stored in memory in the parent
	 * This function attaches a new css file to the DOM 
	 */
	self.update_css = function( to ) {
		if ( ! to ) {
			return;
		}
		
		var new_palette_data = JSON.parse(to);
		var $body = $('body');

		//Create a string of body classes to remove
		//TODO: Do this once, not everytime
		var body_classes = parent.BOLDGRID.COLOR_PALETTE.Modify.body_classes;
		var body_classes_string = '';
		if ( body_classes ) {
			$.each(body_classes, function () {
				body_classes_string += this + " ";
			});
		}

		//Remove all existing palette classes
		$body.removeClass( body_classes_string )
			 .addClass(new_palette_data.state['active-palette'])
			 .data('current-body-class', new_palette_data.state['active-palette']);
		
		//New blank stylesheet
		var style = document.createElement('style');
		style.type = 'text/css';
		style.innerHTML = parent.BOLDGRID.COLOR_PALETTE.Modify.compiled_css;
		
		//Find the matching stylesheet
		var regex = new RegExp( parent.BOLDGRIDSass.output_css_filename , 'i' );
		var enqueue_found = false;
		$('head link[href]').each( function() {
			if ( $(this).attr('href') && $(this).attr('href') .match( regex ) ) {
				enqueue_found = true;
				
				if (self.$new_style) {
					self.$new_style.remove();
				}
				
				self.$new_style = $(style);
				self.$new_style.insertAfter($(this));
			}
		});
		
		//This generally happens if color palettes.css was not found
		if ( false === enqueue_found ) {
			if ( self.$new_style ) {
				self.$new_style.remove();
			}
			
			self.$new_style = $( style );
			$( 'head' ).append( self.$new_style );
		}
	};
	
	/**
	 * Everytime the user changes the following setting, Update the css.
	 */
	wp.customize( 'boldgrid_color_palette', function( value ) {
		value.bind( self.update_css );
	} );

})( jQuery );
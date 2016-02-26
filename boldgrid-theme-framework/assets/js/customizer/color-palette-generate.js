var BOLDGRID = BOLDGRID || {};
BOLDGRID.COLOR_PALETTE = BOLDGRID.COLOR_PALETTE || {};
BOLDGRID.COLOR_PALETTE.Generate = BOLDGRID.COLOR_PALETTE.Generate || {};

/**
 * Generate Palettes for a user based on a partial palette.
 * @param $
 */
(function( $ ) {

	'use strict';

	var self = BOLDGRID.COLOR_PALETTE.Generate;
	
	self.palette_collection = BOLDGRIDColorPalettes.palettes;
	
	/**
	 * Methods used to generate palettes based on only 1 color.
	 * These methods are included in color.js
	 */
	var color_scheme_methods = [
		'fiveToneAScheme',
		'fiveToneBScheme',
		'fiveToneCScheme',
		'fiveToneDScheme',
		'fiveToneEScheme',
		'neutralScheme', //Listed multiple times to increase probability of occurrence
		'neutralScheme',
		'neutralScheme',
		'analogousScheme', //Listed multiple times to increase probability of occurrence
		'analogousScheme',
		'analogousScheme',
	];

	/**
	 * Methods used to generate palettes based on only 1 color.
	 * These methods are included in this file.
	 */
	var internal_palettes = [
		'monochromatic',					 
		'intesity_and_hue',					 
		'complementaryScheme',					 
		'splitComplementaryScheme',					 
		'splitComplementaryCWScheme',					 
		'triadicScheme',					 
		'tetradicScheme',					 
	];

	/**
	 * Methods used to complete a partial palette
	 */
	var fill_palette_actions = [
		'compliment',			   
		'blend',			   
		'copy'		   
	];

	/**
	 * Methods used to randomize a palette
	 */
	var methods = [
		'saturateByAmount',			   
		'lightenByAmount',			   
		'shiftHue'			   
	];
	
	/**
	 * Get a random color
	 * Not Used ATM
	 */
	self.get_random_color = function () {
		var letters = '0123456789ABCDEF'.split('');
		var color = '#';
		for (var i = 0; i < 6; i++ ) {
			color += letters[Math.floor(Math.random() * 16)];
		}
		return color;
	};
	
	/**
	 * Calls generate palette X times.
	 */
	self.generate_palette_collection = function ( partial_palette, count ) {
		if (!count) {
			count = 5;
		}
		
		var palettes = [];
		for (var i = 0; i < count; i++) {
			var new_palette = self.generate_palette( partial_palette );
			if ( typeof new_palette == 'object' && new_palette.length ) {
				palettes.push ( new_palette );
			}
		}
		
		return palettes;
	};
	
	/**
	 * Generate a single palette based on a partial list of colors in a palette.
	 */
	self.generate_palette = function ( partial_palette ) {
		var new_palette = [];
		var bool_empty_palette = self.is_palette_empty( partial_palette );
		
		if ( bool_empty_palette ) {
			new_palette = self.get_palette_from_static_list( partial_palette );
		} else {
			var colors_partial_palette = self.partial_palette_into_colors_palette( partial_palette );
			if ( colors_partial_palette.unchanged_keys.length > 1) {
				var filled_palette = self.generate_palette_from_partial( colors_partial_palette.palette );
				new_palette = self.randomize_palette( filled_palette, colors_partial_palette.unchanged_keys );
			} else {
				
				var random = (Math.floor(Math.random() * 3) + 1);
				if ( random == 1 ){
					var internal_method = internal_palettes[Math.floor(Math.random()*internal_palettes.length)];
					new_palette = self.color_palettes[internal_method](colors_partial_palette.palette[colors_partial_palette.unchanged_keys[0]]);
					
				} else if ( random == 2 ) {
					var colors_method = color_scheme_methods[Math.floor(Math.random()*color_scheme_methods.length)];
					new_palette = colors_partial_palette.palette[colors_partial_palette.unchanged_keys[0]][colors_method]();
					
				} else {
					var degrees = self.random_array(4, 5);
					degrees.unshift(0);
					new_palette = colors_partial_palette.palette[colors_partial_palette.unchanged_keys[0]].schemeFromDegrees( degrees );
				}
				
				new_palette = self.randomize_palette(new_palette,[0]);
				new_palette = self.format_palette_to_unchanged( new_palette, colors_partial_palette.unchanged_keys[0] );
				new_palette = self.truncate_generated_palette( new_palette, colors_partial_palette.palette );
			}
		}
		
		return new_palette;
	};
	
	/**
	 * If the user requests a 3 color palette and we generate a 5 color palette. trim the 
	 * last 2 color in a palette
	 */
	self.truncate_generated_palette = function ( generated_palette, given_partial_palette ) {
		var truncated_palette = [];
		for( var i = 0; i < given_partial_palette.length; i++ ) {
			truncated_palette.push( generated_palette[i] );
		}
		
		return truncated_palette;
	};
	
	/***
	 * Place the single color in the correct placement
	 */
	self.format_palette_to_unchanged = function ( new_palette, needed_key ) {
		var selected_color = new_palette[0];
		var formated_palette = {};
		formated_palette[needed_key] = selected_color;
		formated_palette[0] = new_palette[needed_key];

		$.each( new_palette, function (key) {
			if ( key === 0 ) {
				return;
			}			

			if ( key !== needed_key ) {
				formated_palette[key] = ( this );
			}
		});
		
		formated_palette = $.map( formated_palette, function( value, index ) {
		    return [value];
		});
		
		return formated_palette;
	};
	
	/**
	 * Take a partial palette and convert the color values from css to Color objects
	 */
	self.partial_palette_into_colors_palette = function ( partial_palette ) {
		var color_palette = [];
		var unchanged_keys = [];
		$.each( partial_palette, function ( key ) {
			if ( this ) {
				color_palette.push( net.brehaut.Color( this ) );
				unchanged_keys.push( key );
			} else {
				color_palette.push( null );
			}
		} );
		
		return {
			'palette' : color_palette,
			'unchanged_keys' : unchanged_keys
		};
	};
	
	/**
	 * Get a palette from the color lovers list of palettes or otherwise
	 */
	self.get_palette_from_static_list = function ( partial_palette ) {
		var new_palette = [];
		
		//Try up to 2 times to find a palette
		$.each( [1,2], function () {
			var found_palette = self.palette_collection [ Math.floor( Math.random() * self.palette_collection.length) ];
			if ( found_palette.length >= partial_palette.length ) {
				if ( found_palette.length > partial_palette.length ) {
					for (var i = 0; i < partial_palette.length; i++) {
						new_palette.push ( found_palette[i] );
					}
				} else if ( found_palette.length === partial_palette.length ) {
					new_palette = found_palette;
				}
			}
			
			if ( new_palette.length ) {
				//Break out of the loop if found
				return false;
			}
		} );
		
		return new_palette;
	};
	
	/**
	 * Check if an array has no color definitions.
	 */
	self.is_palette_empty = function ( partial_palette ) {
		var empty_palette = true;
		$.each ( partial_palette, function ( key ) {
			if ( this ) {
				empty_palette = false;
				return false;
			}
		} );
		
		return empty_palette;
	};
	
	/**
	 * Get a random shade of grey
	 */
	self.get_grey = function () {
		var Color = net.brehaut.Color( '#FFFFFF' );
		return Color.setBlue(0).setRed(0).setGreen(0).setLightness(Math.random()).toCSS();
	};

	/**
	 * Covert an array of color objects to an array of css color definitions
	 */
	self.color_array_to_css = function ( colors ) {
		var css_colors = [];
		$.each( colors, function ( key ) {
			if ( key < 5 ) {
				css_colors.push( this.toCSS() );
			}
		} );
		return css_colors;
	};
	
	
	/**
	 * This function is used to create the rest of a palette if more than 1 color is given
	 */
	self.generate_palette_from_partial = function ( colors ) {
		var actual_colors = [];
		$.each(colors, function ( key, this_value ) {
			if ( this_value ) {
				actual_colors.push( this_value );
			}
		});
		
		var palette_colors = [];
		$.each( colors, function (key, this_value ) {
			if (!this_value) {
				var action = fill_palette_actions[Math.floor( Math.random()*fill_palette_actions.length )];
				var palette_color = actual_colors[Math.floor( Math.random()*actual_colors.length )];
				var palette_color2 = actual_colors[Math.floor( Math.random()*actual_colors.length )];
				var new_color;
				if ( action == 'compliment' ) {
					new_color = palette_color.shiftHue( 180 );
				} else if (  action == 'blend' ) {
					new_color = palette_color.blend( palette_color2, 0.5 );
				} else {
					new_color = palette_color;
				}
				palette_colors.push( new_color );
			} else {
				palette_colors.push( this_value );
			}
		});
		
		return palette_colors;
	};

	/**
	 * Take a palette palette and shaift the values slighly in order to provide the 
	 * appearence of a completely new palette
	 * This function essentially makes a palette lighter/darker, saturates and hue shifts
	 */
	self.randomize_palette = function ( palette, unchanged_keys ) {
		var palette_colors = [];
		$.each(palette, function ( key ) {

			if (key >= 5) {
				return false;
			}
			
			if ( unchanged_keys.indexOf( key ) != '-1' ) {
				palette_colors.push( this.toCSS() );
				return;
			}
			
			var color = this;
			for ( var i = 0; i < 2; i++ ) {
				var method = methods[Math.floor( Math.random()*methods.length )];
				var value;
				if ( method == 'shiftHue' ) {
					value = ( Math.floor( Math.random() * 45 ) + 1 ) - 23;
				} else {
					value = ( Math.floor( Math.random() * 20) + 1 ) / 100;
				}
				
				color = color[method]( value );
				if ( i == 1 ) {
					palette_colors.push( color.toCSS() );
				}
			}
		});

		return palette_colors;
	};
	
	/**
	 * Create an array with random variance values
	 */
	self.random_array = function ( size, variance_scale ) {
		var degrees = [];
		
		var range = 45 * variance_scale;
		for ( var i = 0; i < size; i++ ) {
			degrees.push( ( Math.floor( Math.random() * range ) + 1 ) - ( range / 2 ) );
		}
		return degrees;
	};
	
	/**
	 * These color palettes are used when the user only provides 1 color
	 * and would like to generate a palette
	 * These definitions come from the color.js file and include expansion of colors to 5
	 */
	self.color_palettes = {
			
		monochromatic : function ( color ) {
			var palette_colors = [];
			palette_colors.push( color );
			palette_colors.push( color.lightenByAmount( 0.3 ) );
			palette_colors.push( color.darkenByAmount( 0.1 ) );
			palette_colors.push( color.saturateByAmount( 0.5 ) );
			palette_colors.push( color.lightenByAmount( 0.2 ) );
			return palette_colors;
		},
		//Tims Palette
		intesity_and_hue : function ( color ) {
			var palette_colors = [];
			palette_colors.push( color );
	
			palette_colors.push( color.shiftHue( 20 ).lightenByAmount( 0.15 ) );
			palette_colors.push( color.shiftHue( -20 ).darkenByAmount( 0.20 ) );
			palette_colors.push( color.shiftHue( -33 ).darkenByAmount( 0.25 ) );
			palette_colors.push( color.shiftHue( 10 ).lightenByAmount( 0.05 ) );
			
			return palette_colors;
		},

		complementaryScheme : function ( color ) {
			var palette_colors = [];
			palette_colors.push( color );
			palette_colors.push( color.shiftHue( 180 ) );
			palette_colors.push( color.shiftHue( 180 ).lightenByAmount( 0.25 ) );
			palette_colors.push( color.darkenByAmount( 0.25 ) );
			palette_colors.push( color.lightenByAmount( 0.25 ) );
			
			return palette_colors;   	
		},

		splitComplementaryScheme : function ( color ) {
			var palette_colors = [];
			palette_colors.push( color );
			palette_colors.push( color.shiftHue( 150 ) );
			palette_colors.push( color.shiftHue( 320 ) );
			palette_colors.push( color.shiftHue( 320 ).darkenByAmount( 0.25 ) );
			palette_colors.push( color.lightenByAmount( 0.25 ) );
			
			return palette_colors;
		},

		splitComplementaryCWScheme : function ( color ) {
			var palette_colors = [];
			palette_colors.push( color );
			palette_colors.push( color.shiftHue( 60 ) );
			palette_colors.push( color.shiftHue( 210 ) );
			palette_colors.push( color.darkenByAmount( 0.1 ) );
			palette_colors.push( color.shiftHue( 60 ).darkenByAmount( 0.15 ) );
			
			return palette_colors;
		},

		triadicScheme : function ( color ) {
			var palette_colors = [];
			palette_colors.push( color );
			palette_colors.push( color.shiftHue( 60 ) );
			palette_colors.push( color.shiftHue( 240 ) );
			palette_colors.push( color.shiftHue( 60 ).lightenByAmount( 0.2 ) );
			palette_colors.push( color.lightenByAmount( 0.15 ) );
			
			return palette_colors;
		},

		tetradicScheme : function ( color ) {
			var palette_colors = [];
			palette_colors.push( color );
			palette_colors.push( color.shiftHue( 90 ) );
			palette_colors.push( color.shiftHue( 180 ) );
			palette_colors.push( color.shiftHue( 270 ) );
			palette_colors.push( color.saturateByAmount( -0.25 ) );
			
			return palette_colors;
		}
	};
})( jQuery );

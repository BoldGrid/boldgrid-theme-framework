var BOLDGRID = BOLDGRID || {};
BOLDGRID.COLOR_PALETTE = BOLDGRID.COLOR_PALETTE || {};
BOLDGRID.COLOR_PALETTE.Modify = BOLDGRID.COLOR_PALETTE.Modify || {};

/**
 * Handles the UI of a color palette control
 * @param $
 */
( function( $ ) {

	'use strict';

	var $document = $( document );
	var $window = $( window );
	var color_palette = BOLDGRID.COLOR_PALETTE.Modify;
	var self = color_palette;
	
	color_palette.palette_generator = BOLDGRID.COLOR_PALETTE.Generate;
	color_palette.generated_color_palettes = 5;
	color_palette.state = null;
	color_palette.active_body_class = '';
	color_palette.first_update = true;
	
	var last_refresh_time = null;
	
	var default_neutrals =  ['#232323', '#FFFFFF', '#FF5F5F', '#FFDBB8', '#FFFFB2' , '#bad6b1', '#99acbf', '#cdb5e2'];

	/**
	 * AutoLoad Function
	 */
	$( function() {
		self.$palette_control_wrapper = $( '#customize-control-boldgrid-color-palette' );
		self.$color_picker_input = self.$palette_control_wrapper.find( '.pluto-color-control' );
		self.$palette_option_field = self.$palette_control_wrapper.find( '.palette-option-field' );
		self.generated_palettes_container = self.$palette_control_wrapper.find( '.generated-palettes-container' );
		self.$accoridon_section_colors = $( '#accordion-section-colors' );
		self.$neutral_color_selection = self.$palette_control_wrapper.find( '.boldgrid-neutral-color' );
		
		//Create icon set variable
		color_palette.duplicate_modification_icons();

		//Bind the actions of the user clicking on one of the icons that removes or adds palettes
		color_palette.bind_palette_duplicate_remove();
		color_palette.bind_palette_activation();
		color_palette.bind_color_activation();
		color_palette.setup_color_picker();
		color_palette.setup_close_color_picker();
		color_palette.bind_generate_palette_action();
		color_palette.setup_palette_generation();
		color_palette.bind_help_section_visibility();
		color_palette.bind_help_link();
		
		//Hide Advanced Options
		color_palette.setup_advanced_options();
		
		//Action that occurs when color palette is compiled
		color_palette.bind_compile_done();
		
		color_palette.fetch_acceptable_palette_formats();

		//Wait 100ms before running this function because it expects WP color picker to be set up
		setTimeout( color_palette.wp_picker_post_init, 100 );
		
		color_palette.set_neutral_color_text();
		
	} );
	
	/**
	 * Try to show and hide the boldgrid color palette pointer depending on if the color the palette
	 * selector is visible
	 */
	color_palette.bind_help_section_visibility = function () {
		$('.accordion-section').on('click', function () {
			var $bg_help = $('.boldgrid-color-palette-help');
			if ( self.$accoridon_section_colors.hasClass('open') && !$bg_help.is(':visible') ) {
				$bg_help.show();
			} else if ( false === self.$accoridon_section_colors.hasClass('open')) {
				$bg_help.hide();
			}
		});
	};
	
	/**
	 * Close COlor Picker
	 */
	color_palette.setup_close_color_picker = function () {
		self.$close_palette_creator = $("<button type='button' class='button close-color-picker'>Done</button>");
		
		$('.boldgrid-color-palette-wrapper input[type=text].wp-color-picker')
			.after(self.$close_palette_creator);
		
		self.$close_palette_creator.on('click', function () {
			$('body').click();
		});
	};
	
	/**
	 * Bind the handlers for palette generation
	 */
	color_palette.setup_palette_generation = function () {
		
		self.$magic_button = $('.palette-generator-button');
		self.$magic_button.on('click', function (e) {
			self.$magic_button
				.closest('.boldgrid-color-palette-wrapper')  
				.addClass('palette-generate-mode');
		});
		
		self.$palette_control_wrapper.on('click',
			'.palette-generate-mode .current-palette-wrapper .color-lock', function (e) {
			e.stopPropagation();

			var $this = $(this);
			
			self.$palette_control_wrapper.find('.boldgrid-active-palette li').eq(
				$this.data('count')).toggleClass('selected-for-generation');
			
			$this.toggleClass('selected-for-generation');
		});
	
		self.$palette_control_wrapper.find('.cancel-generated-palettes-button').on('click', function (e) {
			e.stopPropagation();
			var $this = $(this);
			
			//Strip out the auto generated data element
			self.$palette_control_wrapper
				.find('.boldgrid-active-palette[data-auto-generated]')
				.removeAttr('data-auto-generated');
			
			self.generated_palettes_container.empty();
			
			//Remove All Selected Colors
			$this.closest('.palette-generate-mode')
				.removeClass('palette-generate-mode')
				.find('.selected-for-generation')
				.removeClass('selected-for-generation');
		});
	};
	
	/**
	 * Clone one of the sets of the icons that are used for deletion and duplication
	 * This is done to make it easier to add them when the palette becomes active
	 */
	color_palette.duplicate_modification_icons = function () {
		color_palette.$icon_set = self.$palette_control_wrapper.find('.boldgrid-duplicate-dashicons').first();
		if ( color_palette.$icon_set ) {
			color_palette.$icon_set = color_palette.$icon_set.clone();
		}
	};
	
	/**
	 * Allow the user to expand an advanced options accordion
	 */
	color_palette.setup_advanced_options = function () {
		self.$palette_control_wrapper.find('.boldgrid-advanced-options-content').hide();
		
		self.$palette_control_wrapper.find('.boldgrid-advanced-options').on('click', function ( e ) {
			e.stopPropagation();
		});
		
		self.$palette_control_wrapper.find('.boldgrid-advanced-options-label').on('click', function (e) {
			e.stopPropagation();
			$(this).closest('.boldgrid-advanced-options')
				.find('.boldgrid-advanced-options-content')
				.stop()
				.slideToggle();
		});
	};
	
	/**
	 * Bind the event of the user clicking on the generate palette button
	 */
	color_palette.bind_generate_palette_action = function () {
		self.$palette_control_wrapper.find('.palette-generator-button').on('click', function (e) {
	
			var partial_palette = color_palette.create_partial_palette();
			
			//Generate Palettes
			var palettes = BOLDGRID.COLOR_PALETTE.Generate.generate_palette_collection( 
				partial_palette, color_palette.generated_color_palettes );
			
			color_palette.display_generated_palettes(palettes);
		});
	};
	
	/**
	 * Given an array of palettes, display them in the generated palettes section.
	 */
	color_palette.display_generated_palettes = function ( palettes ) {
		var $palette_container = self.generated_palettes_container.empty();

		//Currently activate neutral
		var neutral_color = jQuery('.boldgrid-active-palette').attr('data-neutral-color');
		
		$.each( palettes, function () {
			var $wrapper = $('<div data-palette-wrapper="true"><ul><li class="boldgrid-palette-colors"></li></ul></div>');
			var $new_ul = $wrapper.find('ul')
				.addClass('boldgrid-inactive-palette')
				.attr('data-auto-generated', "true" )
				.attr('data-neutral-color', neutral_color )
				.attr('data-color-palette-format', color_palette.get_random_format() );
			
			var $new_li = $new_ul.find('li');
			
			$.each(this, function (){
				var $span = $( '<span></span>' )
					.css ( 'background-color', this )
					.attr( 'data-color', true );
				
				$new_li.append( $span );
			});
			
			$palette_container.append( $wrapper );
		});
	};
	
	/**
	 * Grav the selected palettes from the active palette class and add them to an array
	 */
	color_palette.create_partial_palette = function () { 
		var partial_palette = [];
		self.$palette_control_wrapper.find('.boldgrid-active-palette .boldgrid-palette-colors').each( function () {
			var $this = $(this);
			if ( $this.hasClass('selected-for-generation') ) {
				partial_palette.push($this.css('background-color'));
			} else {
				partial_palette.push(null);
			}
		});
		
		return partial_palette;
	};
	
	/**
	 * Get the palette formats that can be used for a palette
	 * palette formats are essentially body classes
	 */
	color_palette.fetch_acceptable_palette_formats = function () {
		//Store the color palette accepted formats
		color_palette.body_classes = self.$palette_control_wrapper
			.find('.boldgrid-color-palette-wrapper').data('color-formats');
		
		if ( typeof color_palette.body_classes == 'object') {
			color_palette.body_classes = $.map(color_palette.body_classes, function(value, index) {
			    return [value];
			});
		}
	};
	
	/**
	 * Take the an active section and change the markup to what the the inactive 
	 * palettes should be
	 */
	color_palette.revert_current_selection = function ( $palette_wrapper ) {
		$palette_wrapper.removeClass( 'current-palette-wrapper' );
		var $ul = $palette_wrapper.find('> ul');
		$ul.removeClass( 'boldgrid-active-palette' ).addClass( 'boldgrid-inactive-palette' );
		$ul.find('.boldgrid-duplicate-dashicons').remove();
		var $new_li = $( '<li class="boldgrid-palette-colors"></li>' );
		$ul.find('.boldgrid-palette-colors:not(.ui-sortable-helper)').not('.ui-sortable-placeholder').each( function() {
			var $this = $(this);
			if ($this.css('position') != 'absolute') {
				var $span = $( '<span data-color="true"></span>' )
					.css( 'background-color', $this.css('background-color') );
				$new_li.append( $span );
			}
		} );
		$palette_wrapper.find('.bg-lock-controls').remove();
		
		$new_li.append ( color_palette.$icon_set.clone() );
		$ul.find( '.boldgrid-palette-colors' ).remove();
		$ul.append( $new_li );
		
		if ( $ul.data( 'ui-sortable' ) ) {
			$ul.sortable('disable');
		}
	};
	
	/**
	 * Allow user to click on a link inside the help screen to select the first color.
	 */
	color_palette.bind_help_link = function () {
		self.$palette_control_wrapper.on('click', '[data-action="open-color-picker"]', function (e) {
			e.stopPropagation();
			var $element = self.$palette_control_wrapper.find('.boldgrid-active-palette .boldgrid-palette-colors:first');
			color_palette.activate_color ( null, $element );
		});
	};
	
	color_palette.sync_locks = function () {
		var $lock_controls = self.$palette_control_wrapper.find('.bg-lock-controls .color-lock');
		$lock_controls.removeClass('selected-for-generation');
		self.$palette_control_wrapper.find('.boldgrid-active-palette .boldgrid-palette-colors').each( function ( index ) {
			var $this = $(this);
			if ( $this.hasClass('selected-for-generation') ) {
				$lock_controls.eq(index).addClass('selected-for-generation');
			}
		});
	};
	
	/**
	 * Active a palette
	 * Also apply jquery sortable
	 */
	color_palette.activate_palette = function( $ul ) {

		self.$palette_control_wrapper.find('.wp-color-result' ).addClass('expanded-wp-colorpicker');
		
		if ( $ul.attr('data-auto-generated') ) {
			$ul = $ul.closest('div').clone().find('[data-auto-generated]');
		}
		
		var saved_colors = [];
		self.$palette_control_wrapper
			.find( '.boldgrid-active-palette .boldgrid-palette-colors' ).each( function (key) {
				
			var $this = $( this );
			if ( $this.hasClass('selected-for-generation') ) {
				saved_colors.push(key);
			}
		});

		//Creating Lists
		$ul.find( 'span[data-color]' ).each( function( key ) {
			var $this = $( this );
			var background_color = $this.css( 'background-color' );
			
			//Carry over selected 
			var selected_class = '';
			if ( saved_colors.indexOf(key) != '-1' ) {
				selected_class = 'selected-for-generation';
			}
			
		    $ul.append ( 
		    	'<li class="boldgrid-palette-colors ' + selected_class + ' boldgrid-dashicon" style="background-color: ' + background_color + '"></li>' 
    		);
	    } );
		
		
		$ul.append ( color_palette.$icon_set.clone() );

		//This is the old palette strip that had the colors contained
		$ul.find( 'li:first' ).remove();
		var $div = $ul.closest( 'div' ).addClass( 'current-palette-wrapper' ).detach();
		self.$palette_control_wrapper.find( '.boldgrid-color-palette-wrapper' ).prepend( $div );

		var $action_butttons = self.$palette_control_wrapper.find( '.palette-action-buttons' );
		$action_butttons.removeClass( 'hidden' ).insertAfter( $ul );
		color_palette.$color_picker.show();
		
		self.$palette_control_wrapper.find( '.boldgrid-active-palette' ).each( function() {
			var $this = $( this );
			if ( !$this.attr('data-auto-generated') ) {
				color_palette.revert_current_selection( $this.closest('div') );
			} else {
				$this.closest('div').remove();
			}
		});
		
		//Apply Sortable
		color_palette.add_jquery_sortable( $ul );
		self.$neutral_color_selection.css( 'background-color', $ul.attr( 'data-neutral-color' ) );
		color_palette.set_neutral_color_text();
		
		$ul.sortable( 'enable' );
		$ul.disableSelection();
		$ul.removeClass( 'boldgrid-inactive-palette' );
		$ul.addClass( 'boldgrid-active-palette' );
		$ul.find( 'li' ).disableSelection();
		
		var $lock_controls = $( '<div class="bg-lock-controls"></div>' );
		$ul.find( 'li' ).each( function ( index ) {
			$lock_controls.append( '<div class="color-lock" data-count="' + index + '"><div class="lock unlock"><div class="top"></div><div class="mid"></div><div class="bottom"><div class="keyhole-top"></div><div class="keyhole-bottom"></div></div></div>' );
		});
		$ul.after($lock_controls);
		color_palette.sync_locks();
		
		if ( !color_palette.first_update ) {
			color_palette.update_theme_option();
		} else {
			color_palette.first_update = false;
		}
	};
	
	/**
	 * Apply jQuery sortable
	 */
	color_palette.add_jquery_sortable = function ( $ul ) {
		$ul.sortable( {
			
			start: function ( event,ui ) {
				if ( ui.item ) {
					var $to_element = ui.item;
					
					if (!$to_element.find('span').length) {
						color_palette.modify_palette_action( $to_element.closest('[data-palette-wrapper="true"]') );
					}
					
				}
			},
			helper: 'clone',
			stop: function ( event, ui ) {
				
				color_palette.update_theme_option();
				color_palette.open_picker();
				if ( ui.item ) {
					var $to_element = ui.item;
					if (!$to_element.find('span').length) {
						self.$palette_control_wrapper
							.find('.active-palette-section')
							.removeClass('active-palette-section');
						
						$to_element.addClass('active-palette-section');
						
						//Toggle this class to make sure that dashicon placement is updated
						$to_element.siblings().each( function () {
							var $this = $( this );
							$this.toggleClass( 'boldgrid-dashicon' );
							setTimeout( function () {
								$this.toggleClass( 'boldgrid-dashicon' );
							}, 15 );
						});
						
						var $scope = $to_element.closest( '.boldgrid-color-palette-wrapper' );
						//Change the color of the color picker to the active palette
						color_palette.preselect_active_color( $scope );
						color_palette.sync_locks();
					}
				}
			}
		});
	};

	/**
	 * Grab all the palettes from the DOM and create a variable to be passed to the iframe
	 * via wp_customizer
	 */
	color_palette.format_current_palette_state = function () {
		var $active_palette = self.$palette_control_wrapper.find('.boldgrid-active-palette').first();
		
		var palettes_object = {};
		
		//Initialize palette settings
		palettes_object['active-palette'] = $active_palette.attr('data-color-palette-format');
		palettes_object['active-palette-id'] = $active_palette.attr('data-palette-id');
		palettes_object.palettes = {};	//A list of palettes to be compiled
		palettes_object.saved_palettes = []; //A complete list of palettes

		//Store the active body class in the color palette class
		self.active_body_class = palettes_object['active-palette'];

		var palette_routine = function () {
			var $this = $(this);
			
			var $color_backgrounds = {};
			if ( $this.hasClass('boldgrid-active-palette') ) {
				$color_backgrounds = $this.find('li.boldgrid-palette-colors');
			} else {
				$color_backgrounds = $this.find('.boldgrid-palette-colors span');
			}
			
			var colors = [];
			$color_backgrounds.not('.dashicons').each( function () {
				colors.push( $(this).css('background-color') );
			});
			
			var palette = { 
				'format' : $this.attr('data-color-palette-format'),
				'colors' : colors,
				'neutral-color' : $this.attr('data-neutral-color'),
			};
			palettes_object.palettes[ $this.attr('data-color-palette-format') ] = palette;
			
			if ( !$this.attr('data-copy-on-mod') ) {
				palettes_object.saved_palettes.push(palette);
			}
		};
		
		self.$palette_control_wrapper
			.find('[data-color-palette-format]')
			.not($active_palette)
			.not('[data-auto-generated="true"]')
			.each( palette_routine );
		
		$active_palette.each( palette_routine );
		
		return palettes_object;
	};
	
	/**
	 * Take the colors in a palette and format them into an SCSS format
	 */
	color_palette.create_color_scss_file = function( palette_config ) {
		var scss_file = '';
		//null out variables before use
		scss_file += '$palette-primary_1: null;$palette-primary_2: null;$palette-primary_3: null;$palette-primary_4: null;$palette-primary_5: null;$palette-primary-neutral-color: null;$text-contrast-palette-primary-1: null;$text-contrast-palette-primary-2: null;$text-contrast-palette-primary-3: null;$text-contrast-palette-primary-4: null;$text-contrast-palette-primary-5: null;$text-contrast-palette-primary-neutral-color: null;';

		var colors_prefix = '$colors: ';
		$.each ( palette_config.palettes, function ( format ) {
			if ( this.colors ) {
				
				var class_colors = colors_prefix;
				$.each ( this.colors, function ( color_order ) {
					var actual_order = color_order + 1;
					scss_file += '$' + format + "_" + actual_order.toString() + ":" + this + ";";
					class_colors += '$' + format + "_" + actual_order.toString() + ' ';
				});
				
				if ( class_colors != colors_prefix ) {
					scss_file += class_colors + ";";
				}
			}
			if ( this['neutral-color'] ) {
				scss_file += '$' + format + "-neutral-color:" + this['neutral-color'] + ";";
			}
		});

		// text contrast variables
		var text_light = wp.customize( 'boldgrid_light_text' ).get();
		var text_dark = wp.customize( 'boldgrid_dark_text' ).get();

		scss_file += '$light-text:' + text_light + ';';
		scss_file += '$dark-text:' + text_dark + ';';

		return scss_file;

	};
	
	/**
	 * Update the theme option 
	 */
	color_palette.update_theme_option = function() {
		color_palette.state = color_palette.format_current_palette_state();
		var scss_file = color_palette.create_color_scss_file( color_palette.state );
		BOLDGRID.Sass.compile( scss_file + BOLDGRIDSass.ScssFormatFileContents );
	};
	
	/**
	 * When the user clicks on a plattte active it
	 */
	color_palette.bind_palette_activation = function () {
		self.$palette_control_wrapper.on( 'click', '.boldgrid-inactive-palette', function() {
			color_palette.activate_palette( $( this ) );
		} );
	};
	
	color_palette.activate_color = function ( e, $element ) {
		var $this;

		if ( ! e ) {
			$this = $element;
		} else {
			//This event should not occur during palette generation mode
			$this = $( this );
			e.stopPropagation();
		}

        if ( false === $this.hasClass( 'active-palette-section' ) ) {
        	
        	//If this is a neutral color set a different set of defaults
        	if ( $this.hasClass('boldgrid-neutral-color') ) {
        		self.$color_picker_input.iris({ palettes: default_neutrals });
        	} else {
        		self.$color_picker_input.iris({ palettes: true });
        	}
        	
        	color_palette.modify_palette_action( $this.closest('[data-palette-wrapper="true"]') );
        	
			self.$palette_control_wrapper.find('.active-palette-section').removeClass('active-palette-section');
	        $this.addClass( 'active-palette-section' );
	        color_palette.open_picker();
			
			color_palette.set_iris_color( $this.css( 'background-color' ) ); 
        }
	};
	
	/**
	 * When the user clicks on a color in an active palette open the color picker.
	 */
	color_palette.bind_color_activation = function () {
		self.$palette_control_wrapper.on ( 
			'click',
			'.boldgrid-active-palette li, .boldgrid-neutral-color',
			color_palette.activate_color
		);
	};
	
	color_palette.open_picker = function () {
		if ( !self.$palette_control_wrapper.find('.wp-picker-open' ).length ) {
			
			self.$palette_control_wrapper.find('.wp-color-result' ).click();
		}
	};
	
	color_palette.set_iris_color = function ( css_color ) {
		//Set the color value
		var background_color = net.brehaut.Color( css_color );
		self.$color_picker_input.iris('color', background_color.toString());
	};
	
	color_palette.set_neutral_color_text = function () {
		var background_color = net.brehaut.Color( self.$neutral_color_selection.css('background-color') );
		if ( background_color.getLuminance() > 0.5 ) {
			self.$neutral_color_selection.css( 'color', '#333' );
		} else {
			self.$neutral_color_selection.css( 'color', 'white' );
		}
	};
	
	/**
	 * Set the options for the color picker.
	 */
	color_palette.setup_color_picker = function () {

		var myOptions = {
			    // you can declare a default color here,
			    // or in the data-default-color attribute on the input
			    defaultColor : false,
			    change : function( event, ui ) {
				    var color = ui.color.toString();
				    var $active_list = self.$palette_control_wrapper
				    	.find( '.active-palette-section' );
				    
				    $active_list.css( 'background-color', color );
				    
				    if ($active_list.hasClass('boldgrid-neutral-color')) {
				    	var active_palette = self.$palette_control_wrapper
				    		.find('.boldgrid-active-palette');
				    		
				    	if ( active_palette.attr('data-neutral-color') ) {
				    		active_palette.attr('data-neutral-color', color);
				    		
				    		self.$palette_control_wrapper
				    			.find('.generated-palettes-container .boldgrid-inactive-palette')
				    			.each( function () {
				    				$(this).attr('data-neutral-color', color);
				    			});
				    	}
	
				    	color_palette.set_neutral_color_text();
				    }
				    
				    //Make sure that we only trigger this event after a 500 ms delay
				    color_palette.last_refresh_time = new Date().getTime();
				    var current_refreshtime = color_palette.last_refresh_time;
			    	setTimeout ( function () {
			    		//If this is the last event after 50 ms 
			    		//TODO: Change this based on device
			    		if ( ! BOLDGRID.Sass.processing &&
			    			( current_refreshtime == color_palette.last_refresh_time ||
			    				self.most_recent_update + 100 < new Date().getTime() ) ) {

			    			color_palette.update_theme_option();
				    		self.most_recent_update = new Date().getTime();
			    		}
			    	}, 200, current_refreshtime );

			    },
			    // hide the color picker controls on load
			    hide : true,
			    // show a group of common colors beneath the square
			    // or, supply an array of colors to customize further
			    palettes : true,
			};

			var $wp_color_picker = self.$color_picker_input.wpColorPicker( myOptions );
			color_palette.$color_picker = self.$palette_control_wrapper.find('.wp-picker-container' ).hide();
	};	
	
	/**
	 * Set the color on the color palette to the color that has the class ".active-palette-seciton"
	 */
	color_palette.preselect_active_color = function ( $scope ) {
		color_palette.set_iris_color( $scope.find('.active-palette-section').css( 'background-color' ) ); 
	};
	
	/**
	 * Setup the duplicate nad remove palette icon clicks.
	 */
	color_palette.bind_palette_duplicate_remove = function ( ) {
		self.$palette_control_wrapper.on('click', '.boldgrid-copy-palette, .boldgrid-remove-palette', function (e) {
			e.stopPropagation();
			var $this = $(this);
			
			//Get the closest wrapper of the pallette, this container holds all of the colors
			var $palette_wrapper = $this.closest('[data-palette-wrapper="true"]');
			
			var removal = true;
			if ($this.hasClass('boldgrid-copy-palette')) {
				removal = false;
			}
			
			if ( removal ) {
				color_palette.remove_palette( $palette_wrapper );
			} else {
				color_palette.copy_palette( $palette_wrapper );
			}
		});
	};
	
	/**
	 * Remove a palette form the list of inactive palettes.
	 */
	color_palette.remove_palette = function ( $palette ) {
		if ( $palette.find('.boldgrid-inactive-palette').length ) {
			$palette.remove();
			color_palette.state = color_palette.format_current_palette_state();
			color_palette.update_palette_settings( true );
		}
	};
	
	/**
	 * Clone a palette
	 */
	color_palette.modify_palette_action = function ( $palette ) {
		if ( $palette.find('[data-copy-on-mod="1"]').length ) {
			color_palette.copy_palette( $palette );
		}
	};
	
	/**
	 * Triggered when the user presses the copy palette icon
	 * Split into two actions, when the user clicks on a palette that is inactive or active
	 */
	color_palette.copy_palette = function ( $palette ) {
		var $active_palette = $palette.find('.boldgrid-active-palette');
		if ( $active_palette.length ) {
			//Translate the active palette into an inactive palette, clone to make sure that 
			//the original stays
			var $cloned_active_palette = $palette.clone( false );
			$cloned_active_palette.find('.palette-action-buttons').remove();
			$cloned_active_palette.find('.boldgrid-active-palette').removeAttr('data-auto-generated');
			//Since this item has already been copied, don't preserve it on modification
			color_palette.clean_palette_clone($palette.find('.boldgrid-active-palette'));

			color_palette.revert_current_selection ( $cloned_active_palette );
			
			//Find the first inactive palette
			var $first_inactive = color_palette.get_first_inactive();
			
			//After the palette was changed into an inactive style, wrap it in the standard container
			//For easy identification purposes
			$cloned_active_palette.insertBefore($first_inactive);
		} else if ( $palette.find('.boldgrid-inactive-palette').length ) {
			//Simply copy in place.
			var $cloned_palette = $palette.clone(false);

			//Since this item has already been copied, don't preserve it on modification
			color_palette.clean_palette_clone($cloned_palette.find('.boldgrid-inactive-palette'));
			
			$palette.after( $cloned_palette );
		} 
		
		color_palette.state = color_palette.format_current_palette_state();
		color_palette.update_palette_settings( true );
		
	};
	
	/**
	 * Remove attributes that identify this palette as a hardcoded palette
	 */
	color_palette.clean_palette_clone = function ( $palette_clone ) {
		$palette_clone.removeAttr('data-copy-on-mod')
					  .removeAttr('data-palette-id');
	};
	
	/**
	 * Out of the list of palettes return the first palette that is inactive
	 */
	color_palette.get_first_inactive = function () {
		return self.$palette_control_wrapper
			.find('.boldgrid-color-palette-wrapper > [data-palette-wrapper="true"]')
			.not('.current-palette-wrapper')
			.first();
	};
	
	/**
	 * When the Sass script return that a compile was complete, send the data to the 
	 * preview script
	 */
	color_palette.bind_compile_done = function () {
		$window.on('boldgrid_sass_compile_done', function (event, data) {
			//console.log( event, data );
			color_palette.compiled_css = data.text;
			color_palette.update_palette_settings( true );
		});
	};
	
	/**
	 * Change the palette settings
	 */
	color_palette.update_palette_settings = function ( force_update ) {
		color_palette.text_area_val = JSON.stringify({ 'state' : color_palette.state });
		self.$palette_option_field.val( color_palette.text_area_val );
		if ( force_update ) {
			self.$palette_option_field.change();
		}
	};
	
	/**
	 * Get a random acceptble format.
	 * TEMP: hardcoded to first palette.
	 */
	color_palette.get_random_format = function () {
		//return color_palette.body_classes[ Math.floor( Math.random() * color_palette.body_classes.length)];
		return color_palette.body_classes[0];
	};
	
	/**
	 * Initialization processes to be run after the picker has been initialized
	 */
	color_palette.wp_picker_post_init = function () {
		//Post Color Picker Load
		var $active_palette = self.$palette_control_wrapper.find('.boldgrid-inactive-palette[data-is-active="1"]');
		var $default_palette = self.$palette_control_wrapper.find('.boldgrid-inactive-palette[data-is-default="1"]');
		
		var $palette_to_activate = $default_palette;
		if ($active_palette.length) {
			$palette_to_activate = $active_palette;
		} 
		
		//Active the palette in the UI
		$palette_to_activate.click();
		
		self.$palette_control_wrapper.find('.wp-color-result' ).addClass('expanded-wp-colorpicker');

		$('body').on('click', function () {
			self.$palette_control_wrapper.find('.wp-color-result' ).addClass('expanded-wp-colorpicker');
			 
				//Remove advanced Options DropDown
				self.$palette_control_wrapper.find('.boldgrid-color-palette-wrapper')
					.find('.boldgrid-advanced-options')
					.addClass('hidden');
				
				//Deselect color
				self.$palette_control_wrapper
					.find('.active-palette-section')
					.removeClass('active-palette-section');
		});
		
		//TODO this doesnt work on auto close
		self.$palette_control_wrapper.find('.wp-color-result' ).on( 'click', function() {
			
				var $this = $( this );
				var picker_visible = $this.parent().find('.iris-picker').is(':visible');
				
				if ( picker_visible ) {
					//$this.hide();
					var $palette_wrapper = $this.closest('.boldgrid-color-palette-wrapper');
					$this.removeClass('expanded-wp-colorpicker');
					
					//Auto Select first color
					if ( !self.$palette_control_wrapper.find('.active-palette-section').length ) {
						self.$palette_control_wrapper.find('.boldgrid-active-palette li:first').click();
					}
					
					//Show advanced Options DropDown
					$palette_wrapper.find('.boldgrid-advanced-options')
						.removeClass('hidden');
					
					//Change the color of the color picker to the active palette
					color_palette.preselect_active_color( $palette_wrapper );
					
				} else {
					//Remove advanced Options DropDown
					$this.closest('.boldgrid-color-palette-wrapper')
						.find('.boldgrid-advanced-options')
						.addClass('hidden');
					
					$this.addClass('expanded-wp-colorpicker');
				}
				
			} );
		
		self.active_body_class = self.$palette_control_wrapper
		.find('.boldgrid-active-palette')
		.first()
		.attr('data-color-palette-format');
	};
	
})( jQuery );

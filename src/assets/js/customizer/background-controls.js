var BOLDGRID = BOLDGRID || {};
BOLDGRID.CUSTOMIZER = BOLDGRID.CUSTOMIZER || {};

( function( $ ) {

	'use strict';

	 BOLDGRID.CUSTOMIZER.Background = {};
	var self = BOLDGRID.CUSTOMIZER.Background;

	var $window = $( window );

	$( function() {
		$window.on( 'boldgrid_customizer_refresh',  onload_procedure  );
	} );

	var onload_procedure = function () {
		bind_all();
		validate_selection_set();

		var $flat_color_background = $('#customize-control-boldgrid_background_color');
		var $pattern_background = $('#customize-control-boldgrid_background_pattern');
		var $background_type = $('#customize-control-boldgrid-background-type');

		var $image_mods = $('#accordion-section-background_image .customize-control')
			.not($pattern_background)
			.not($background_type)
			.not($flat_color_background);

		var $verticle_pos = $('#customize-control-boldgrid_background_vertical_position');
		var $horizontal_pos = $('#customize-control-boldgrid_background_horizontal_position');
		var $image_background = $('#customize-control-background_image');
		var $repeat_background = $('#customize-control-background_repeat');
		var $pos_x_background = $('#customize-control-background_position_x');
		var $attachment_background = $('#customize-control-background_attachment');

		self.$background_color_picker_color = $flat_color_background.find('.wp-color-result');
		var $background_color_picker = $flat_color_background.find('.wp-color-picker');
		var $pattern_input = $pattern_background.find( "#boldgrid_background_pattern input" );
		var $boldgrid_pattern_wrapper = $pattern_background.find( ".boldgrid-pattern-wrapper" );
		var $remove_selected_pattern = $pattern_background.find( ".remove-selected-pattern" );

		$flat_color_background.find('.wp-picker-clear').on('click', function () {
			wp.customize.control( 'boldgrid_background_color' ).setting( '' );
			validate_background_color_setting();
		});
		//Bind Events
		validate_background_color_setting();
		append_head_styles();
		bind_background_color_change();

		$remove_selected_pattern.on( 'click', function() {
			var $this =  $( this );
			$boldgrid_pattern_wrapper.removeAttr('data-pattern-selected');
			$remove_selected_pattern.attr('disabled', 'disabled');
			$('#boldgrid_background_pattern .active-pattern').removeClass('active-pattern');
			$pattern_input.val('').change();
		} );

		$( '.patternpreview' ).on( 'click', function() {
			var $this =  $( this );
			$( '.pattern-wrapper .active-pattern' ).removeClass( 'active-pattern' );
			$this.toggleClass( 'active-pattern' );
			var background_image = $this.css( 'background-image' );
			$pattern_input.val( background_image ).change();
			$boldgrid_pattern_wrapper.attr('data-pattern-selected', 1);
			$remove_selected_pattern.removeAttr('disabled', 'disabled');
		} );

		//Init Button Set
	    $( "#boldgrid-background-type" ).buttonset();

		$( '.accordion-section-content' ).on( 'scroll', function( e ) {
			var $this = $(this);
			var top = $this.scrollTop();
			if ( top > 75 && wp.customize( 'boldgrid_background_type')() == 'pattern' ) {
				$this.addClass( 'boldgrid-sticky-customizer' );
			} else {
				$this.removeClass( 'boldgrid-sticky-customizer' );
			}
		} );

		$background_type.on( 'change', 'input', function () {
			var $this = $( this );
			if ( $this.val() == 'image' ) {
				$( '#customize-control-boldgrid_background_color' ).hide();
				$( '#customize-control-boldgrid_background_pattern' ).hide();
			} else {
				$( '#customize-control-boldgrid_background_color' ).show();
				$( '#customize-control-boldgrid_background_pattern' ).show();
			}

		});

		$background_type.find( 'input:checked' ).change();

	};

	var validate_background_color_setting = function () {
		var $container = jQuery( wp.customize.previewer.container );
		var to = wp.customize( 'boldgrid_background_color' )();
		if ( to === '' ) {
			setTimeout( function () {
				var $iframe = $container.find( 'iframe' ).contents();
				var color = $iframe.find( 'body' ).css( 'background-color' );
				self.$background_color_picker_color.css( 'background-color', color );
				append_head_styles( color );
			}, 100);
		}
	};

	var append_head_styles = function ( to ) {
		if ( ! to ) {
			to = wp.customize( 'boldgrid_background_color' )();
		}

		$( '#customizer_boldgrid_background_color' ).remove();
		if ( to ) {
			var style = '';
			style += '<style id="customizer_boldgrid_background_color">';
			style += '#customize-control-boldgrid_background_pattern .patternpreview{background-color:' + to + ';}';
			style += '</style>';

			$( 'head' ).append( $( style ) );
		}
	};

	/**
	 * When the user changes the background color using the color picker,
	 * update the preview patterns
	 */
	var bind_background_color_change = function () {
		wp.customize( 'boldgrid_background_color', function( value ) {
			value.bind( function( to ) {
				append_head_styles();
			} );
		} );
	};

	var validate_selection_set = function () {
		var bg_image = wp.customize( 'background_image' )();
		var bg_attach = wp.customize( 'background_attachment' )();
		var bg_img_size = wp.customize( 'boldgrid_background_image_size' )();
		var bg_vt_pos = wp.customize( 'boldgrid_background_vertical_position' )();
		var bg_hor_pos = wp.customize( 'boldgrid_background_horizontal_position' )();
		var bg_type = wp.customize( 'boldgrid_background_type' )();

		if ( bg_type == 'pattern') {

			//Activate Pattern
			wp.customize.control( 'boldgrid_background_pattern' ).activate( { duration: 0 } );
			wp.customize.control( 'boldgrid_background_color' ).activate( { duration: 0 } );

			//Deactivate Image
			wp.customize.control( 'boldgrid_background_horizontal_position' ).deactivate( { duration: 0 } );
			wp.customize.control( 'boldgrid_background_vertical_position' ).deactivate( { duration: 0 } );
			wp.customize.control( 'boldgrid_background_image_size' ).deactivate( { duration: 0 } );
			wp.customize.control( 'background_image' ).deactivate( { duration: 0 } );
			wp.customize.control( 'background_repeat' ).deactivate( { duration: 0 } );
			wp.customize.control( 'background_attachment' ).deactivate( { duration: 0 } );


		} else {
			//Activate Image
			wp.customize.control( 'boldgrid_background_image_size' ).activate( { duration: 0 } );
			wp.customize.control( 'boldgrid_background_horizontal_position' ).activate( { duration: 0 } );
			wp.customize.control( 'boldgrid_background_vertical_position' ).activate( { duration: 0 } );
			wp.customize.control( 'background_image' ).activate( { duration: 0 } );
			wp.customize.control( 'background_attachment' ).activate( { duration: 0 } );
			wp.customize.control( 'background_repeat' ).activate( { duration: 0 } );

			if ( !bg_image ) {
				wp.customize.control( 'boldgrid_background_horizontal_position' ).deactivate( { duration: 0 } );
				wp.customize.control( 'boldgrid_background_vertical_position' ).deactivate( { duration: 0 } );
				wp.customize.control( 'boldgrid_background_image_size' ).deactivate( { duration: 0 } );
				wp.customize.control( 'background_attachment' ).deactivate( { duration: 0 } );
				wp.customize.control( 'background_repeat' ).deactivate( { duration: 0 } );
			} else {
				wp.customize.control( 'boldgrid_background_horizontal_position' ).activate( { duration: 0 } );
				wp.customize.control( 'boldgrid_background_vertical_position' ).activate( { duration: 0 } );
				wp.customize.control( 'boldgrid_background_image_size' ).activate( { duration: 0 } );
				wp.customize.control( 'background_attachment' ).activate( { duration: 0 } );
				wp.customize.control( 'background_repeat' ).activate( { duration: 0 } );
			}

			if ( bg_attach == 'parallax' ) {
				wp.customize.control( 'boldgrid_background_horizontal_position' ).deactivate( { duration: 0 } );
				wp.customize.control( 'boldgrid_background_vertical_position' ).deactivate( { duration: 0 } );
				wp.customize.control( 'background_repeat' ).deactivate( { duration: 0 } );
			}
		}

	};

	var bind_all = function () {
		var background_control_refresh = [
		     'background_image',
		     'background_attachment',
		     'background_repeat',
		     'boldgrid_background_image_size',
		     'boldgrid_background_horizontal_position',
		     'boldgrid_background_type',
		     'boldgrid_background_vertical_position'];

		$.each(background_control_refresh, function () {

			wp.customize( this, function( value ) {
				value.bind( validate_selection_set );
			});
		});

	};


})( jQuery );

( function( $ ) {

	wp.customize( 'logo_shadow_switch', function( value ) {
		value.bind( function( to ) {
			_shadow_toggle();
		});
	});

	var _shadow_toggle = function (  ) {

		var shadow      =  wp.customize( 'logo_shadow_switch' ).get(  );
		var vertical    =  wp.customize.control( 'logo_shadow_vertical' );
		var horizontal  =  wp.customize.control( 'logo_shadow_horizontal' );
		var color       =  wp.customize.control( 'logo_shadow_color' );
		var blur        =  wp.customize.control( 'logo_shadow_blur' );

		if ( 1 != shadow ) {

			vertical.deactivate( { duration: 0 } );
			horizontal.deactivate( { duration: 0 } );
			color.deactivate( { duration: 0 } );
			blur.deactivate( { duration: 0 } );

		} else {
			vertical.activate( { duration: 0 } );
			horizontal.activate( { duration: 0 } );
			color.activate( { duration: 0 } );
			blur.activate( { duration: 0 } );
		}

	};

	/**
	 *  Don't display site logo text modifications if a user has a logo uploaded to replace it.
	 */
	var _logo_toggle = function (  ) {

		var logo      =  wp.customize( 'boldgrid_logo_setting' ).get(  );

		if ( logo !== '' ) {
			_.each( wp.customize.section( 'title_tagline' ).controls(), function ( control ) {
				control.deactivate( { duration:  0 } );
			});
			wp.customize.control( 'boldgrid_logo_setting' ).activate( { duration: 0 } );
			wp.customize.control( 'site_icon' ).activate( { duration: 0 } );
			wp.customize.control( 'blogname' ).activate( { duration: 0 } );
			wp.customize.control( 'logo_margin_bottom' ).activate( { duration: 0 } );
			wp.customize.control( 'logo_margin_left' ).activate( { duration: 0 } );
			wp.customize.control( 'logo_margin_top' ).activate( { duration: 0 } );
			wp.customize.control( 'boldgrid_logo_size' ).activate( { duration: 0 } );

			//Enable Position Controls & reorganize
			wp.customize.control( 'boldgrid_position_toggle' ).activate( { duration: 0 } );
			wp.customize.control( 'boldgrid_position_toggle' ).priority( 55 );
			wp.customize.control( 'logo_margin_left' ).priority( 55 );
			wp.customize.control( 'logo_margin_bottom' ).priority( 55 );
			wp.customize.control( 'logo_margin_top' ).priority( 55 );
			wp.customize.control( 'boldgrid_logo_size' ).priority( 50 );
		} else {
			//Reset Position Controls Position
			wp.customize.control( 'boldgrid_logo_size' ).deactivate( { duration: 0 } );

			wp.customize.control( 'boldgrid_position_toggle' ).priority( 10 );
			wp.customize.control( 'logo_margin_left' ).priority( 10 );
			wp.customize.control( 'logo_margin_bottom' ).priority( 10 );
			wp.customize.control( 'logo_margin_top' ).priority( 10 );
		}

	};

	var _font_toggle = function (  ) {

		var font     				 	=  wp.customize( 'boldgrid_font_toggle' ).get(  );
		var font_family					=  wp.customize.control( 'logo_font_family' );
		var font_size					=  wp.customize.control( 'logo_font_size' );
		var text_decoration 			=  wp.customize.control( 'logo_text_decoration' );
		var text_decoration_hover		=  wp.customize.control( 'logo_text_decoration_hover' );
		var text_transform				=  wp.customize.control( 'logo_text_transform' );

		if ( 1 != font )  {

			font_family.deactivate( { duration: 0 } );
			font_size.deactivate( { duration: 0 } );
			text_decoration.deactivate( { duration: 0 } );
			text_decoration_hover.deactivate( { duration: 0 } );
			text_transform.deactivate( { duration: 0 } );

		}

	};

	var _position_toggle = function (  ) {

		var position     			=  wp.customize( 'boldgrid_position_toggle' ).get(  );
		var letter_spacing			=  wp.customize.control( 'logo_letter_spacing' );
		var line_height				=  wp.customize.control( 'logo_line_height' );
		var margin_bottom			=  wp.customize.control( 'logo_margin_bottom' );
		var margin_top				=  wp.customize.control( 'logo_margin_top' );
		var margin_left				=  wp.customize.control( 'logo_margin_left' );

		if ( 1 != position ) {

			letter_spacing.deactivate( { duration: 0 } );
			line_height.deactivate( { duration: 0 } );
			margin_bottom.deactivate( { duration: 0 } );
			margin_top.deactivate( { duration: 0 } );
			margin_left.deactivate( { duration: 0 } );

		}

	};

	/**
	 * refresh msg
	 */

	$( window ).on( 'message', function ( e ) {
		var event = e.originalEvent;

		// Ensure we have a string that's JSON.parse-able
		if ( typeof event.data !== 'string' || event.data[0] !== '{' ) {
			return;
		}

		var message = JSON.parse( event.data );
		if ( message.id == 'synced' ) {

			_shadow_toggle(  );
			_logo_toggle(  );
			_font_toggle(  );
			_position_toggle(   );

		}

	});

})( jQuery );

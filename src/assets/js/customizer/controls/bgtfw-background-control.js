import PaletteSelector from '../color/palette-selector';

const colorLib = window.net.brehaut;

export default function() {

	'use strict';

	var BOLDGRID = BOLDGRID || {};
	BOLDGRID.CUSTOMIZER = BOLDGRID.CUSTOMIZER || {};
	BOLDGRID.CUSTOMIZER.Background = {};

	var self = BOLDGRID.CUSTOMIZER.Background,
		api = wp.customize;

	var $window = $( window );

	$( function() {

		/**
		 * @todo This needs to be refactored. All these events should not be each time
		 * preview iframe is refreshed.
		 */
		$window.on( 'boldgrid_customizer_refresh', onload_procedure );
		loadPatterns();
	} );

	var onload_procedure = function() {
		bind_all();
		validate_selection_set();

		var $flat_color_background = $( '#customize-control-boldgrid_background_color' );
		var $pattern_background = $( '#customize-control-boldgrid_background_pattern' );
		var $background_type = $( '#customize-control-boldgrid-background-type' );
		self.$background_color_picker_color = $flat_color_background.find( '.wp-color-result' );
		var $pattern_input = $pattern_background.find( '#boldgrid_background_pattern input' );
		var $boldgrid_pattern_wrapper = $pattern_background.find( '.boldgrid-pattern-wrapper' );
		var $remove_selected_pattern = $pattern_background.find( '.remove-selected-pattern' );

		// Bind Events.
		validate_background_color_setting();
		append_head_styles();
		bind_background_color_change();
		bind_palette_change();

		$remove_selected_pattern.on( 'click', function() {
			$boldgrid_pattern_wrapper.removeAttr( 'data-pattern-selected' );
			$remove_selected_pattern.attr( 'disabled', 'disabled' );
			$( '#boldgrid_background_pattern .active-pattern' ).removeClass( 'active-pattern' );
			wp.customize( 'boldgrid_background_pattern' ).set( '' );
		} );

		$( '#boldgrid_background_pattern .patternpreview' ).on( 'click', function() {
			var $this =  $( this );
			$( '.pattern-wrapper .active-pattern' ).removeClass( 'active-pattern' );
			$this.toggleClass( 'active-pattern' );
			var background_image = $this.css( 'background-image' );
			$pattern_input.val( background_image ).change();
			wp.customize( 'boldgrid_background_pattern' ).set( background_image );
			$boldgrid_pattern_wrapper.attr( 'data-pattern-selected', 1 );
			$remove_selected_pattern.removeAttr( 'disabled', 'disabled' );
		} );

		// Init Button Set.
		$( '#boldgrid-background-type' ).buttonset();
		$( '.accordion-section-content, .wp-full-overlay-sidebar-content' ).on( 'scroll', function() {
			var $this = $( this );
			var top = $this.scrollTop();
			if ( top > 75 && wp.customize( 'boldgrid_background_type' )() === 'pattern' ) {
				$this.addClass( 'boldgrid-sticky-customizer' );
			} else {
				$this.removeClass( 'boldgrid-sticky-customizer' );
			}
		} );

		$background_type.on( 'change', 'input', function() {
			var $this = $( this );
			if ( $this.val() === 'image' ) {
				$( '#customize-control-boldgrid_background_color' ).hide();
				$( '#customize-control-boldgrid_background_pattern' ).hide();
			} else {
				$( '#customize-control-boldgrid_background_color' ).show();
				$( '#customize-control-boldgrid_background_pattern' ).show();
			}

		});

		$background_type.find( 'input:checked' ).change();
	};

	/**
	 * When the background panel is opened, load available background patterns.
	 *
	 * @since 2.0.0
	 */
	var loadPatterns = function() {
		var loaded = false;

		wp.customize.section( 'background_image' ).expanded.bind( function( isExpanded ) {
			if ( isExpanded && ! loaded ) {
				loaded = true;
				setBackgroundPatterns().then( () => {
					setActivePattern();
				} );
			}
		} );
	};

	var setBackgroundPattern = function( el, patterns ) {
		var color = new PaletteSelector(),
			bg = color.getColor( wp.customize( 'boldgrid_background_color' )(), true ),
			win = wp.customize.previewer.targetWindow(),
			contrast;

		contrast = win.BOLDGRID.Customizer.Util.getTextContrast( bg );

		bg = colorLib.Color( bg );
		bg = contrast.includes( 'light' ) ? bg.lightenByAmount( 0.075 ) : bg.darkenByAmount( 0.075 );
		bg = bg.toCSS();

		el.css( 'background-image', patterns.default[ el.data( 'background' ) ]( bg ) );
	};

	async function addPatterns() {
		const hero = await import( /* webpackChunkName: "hero-patterns" */ 'hero-patterns' );

		$( '#boldgrid_background_pattern .patternpreview' ).each( function() {
			var color = new PaletteSelector(),
				el = $( this ),
				bg = color.getColor( wp.customize( 'boldgrid_background_color' )(), true ),
				win = wp.customize.previewer.targetWindow(),
				contrast;

			contrast = win.BOLDGRID.Customizer.Util.getTextContrast( bg );

			bg = colorLib.Color( bg );
			bg = contrast.includes( 'light' ) ? bg.lightenByAmount( 0.075 ) : bg.darkenByAmount( 0.075 );
			bg = bg.toCSS();

			el.css( 'background-image', hero[ el.data( 'background' ) ]( bg ) );
		} );

		return true;
	};

	var setBackgroundPatterns = function() {
		return addPatterns();
	};

	var setActivePattern = function() {
		$( `#boldgrid_background_pattern .patternpreview[style*='background-image: ${ wp.customize( 'boldgrid_background_pattern' )() }']` ).addClass( 'active-pattern' );
	};

	var validate_background_color_setting = function() {
		var $container = $( wp.customize.previewer.container );
		var to = wp.customize( 'boldgrid_background_color' )();
		if ( to === '' ) {
			setTimeout( function() {
				var $iframe = $container.find( 'iframe' ).contents();
				var color = $iframe.find( 'body' ).css( 'background-color' );
				self.$background_color_picker_color.css( 'background-color', color );
				append_head_styles( color );
				setBackgroundPatterns();
			}, 100 );
		}
	};

	var append_head_styles = function( to ) {
		if ( ! to ) {
			to = wp.customize( 'boldgrid_background_color' )();
		}

		$( '#customizer_boldgrid_background_color' ).remove();
		if ( to ) {
			var style = '';
			style += '<style id="customizer_boldgrid_background_color">';
			style += '#customize-control-boldgrid_background_pattern .patternpreview{background-color:' + to.split( ':' ).pop() + ';}';
			style += '</style>';

			$( 'head' ).append( $( style ) );
		}
	};

	/**
	 * When the user changes the background color using the color picker,
	 * update the preview patterns
	 */
	var bind_background_color_change = function() {
		wp.customize( 'boldgrid_background_color', function( value ) {
			value.bind( function() {
				var pattern;
				append_head_styles();
				setBackgroundPatterns().then( () => {
					pattern = $( '#customize-control-boldgrid_background_pattern' ).find( '.active-pattern' ).css( 'background-image' );
					$( '#boldgrid_background_pattern input' ).val( pattern ).change();
					wp.customize( 'boldgrid_background_pattern' ).set( pattern );
				} );
			} );
		} );
	};

	var bind_palette_change = function() {
		wp.customize( 'boldgrid_color_palette', function( value ) {
			value.bind( function() {
				append_head_styles();
				setBackgroundPatterns().then( () => {
					pattern = $( '#customize-control-boldgrid_background_pattern' ).find( '.active-pattern' ).css( 'background-image' );
					$( '#boldgrid_background_pattern input' ).val( pattern ).change();
					wp.customize( 'boldgrid_background_pattern' ).set( pattern );
				} );
			} );
		} );
	};

	var validate_selection_set = function() {
		var bg_image = wp.customize( 'background_image' )();
		var bg_attach = wp.customize( 'background_attachment' )();
		var bg_type = wp.customize( 'boldgrid_background_type' )();

		if ( bg_type === 'pattern' ) {

			// Activate Pattern.
			wp.customize.control( 'boldgrid_background_pattern' ).activate( { duration: 0 } );
			wp.customize.control( 'boldgrid_background_color' ).activate( { duration: 0 } );

			// Deactivate Image.
			wp.customize.control( 'boldgrid_background_horizontal_position' ).deactivate( { duration: 0 } );
			wp.customize.control( 'boldgrid_background_vertical_position' ).deactivate( { duration: 0 } );
			wp.customize.control( 'boldgrid_background_image_size' ).deactivate( { duration: 0 } );
			wp.customize.control( 'background_image' ).deactivate( { duration: 0 } );
			wp.customize.control( 'background_repeat' ).deactivate( { duration: 0 } );
			getAttachmentControl().deactivate( { duration: 0 } );

		} else {

			// Activate Image.
			wp.customize.control( 'boldgrid_background_image_size' ).activate( { duration: 0 } );
			wp.customize.control( 'boldgrid_background_horizontal_position' ).activate( { duration: 0 } );
			wp.customize.control( 'boldgrid_background_vertical_position' ).activate( { duration: 0 } );
			wp.customize.control( 'background_image' ).activate( { duration: 0 } );
			getAttachmentControl().activate( { duration: 0 } );
			wp.customize.control( 'background_repeat' ).activate( { duration: 0 } );

			if ( ! bg_image ) {
				wp.customize.control( 'boldgrid_background_horizontal_position' ).deactivate( { duration: 0 } );
				wp.customize.control( 'boldgrid_background_vertical_position' ).deactivate( { duration: 0 } );
				wp.customize.control( 'boldgrid_background_image_size' ).deactivate( { duration: 0 } );
				getAttachmentControl().deactivate( { duration: 0 } );
				wp.customize.control( 'background_repeat' ).deactivate( { duration: 0 } );
			} else {
				wp.customize.control( 'boldgrid_background_horizontal_position' ).activate( { duration: 0 } );
				wp.customize.control( 'boldgrid_background_vertical_position' ).activate( { duration: 0 } );
				wp.customize.control( 'boldgrid_background_image_size' ).activate( { duration: 0 } );
				getAttachmentControl().activate( { duration: 0 } );
				wp.customize.control( 'background_repeat' ).activate( { duration: 0 } );
			}

			if ( bg_attach === 'parallax' ) {
				wp.customize.control( 'boldgrid_background_horizontal_position' ).deactivate( { duration: 0 } );
				wp.customize.control( 'boldgrid_background_vertical_position' ).deactivate( { duration: 0 } );
				wp.customize.control( 'background_repeat' ).deactivate( { duration: 0 } );
			}
		}

		toggleOverlay( bg_type );
	};

	/**
	 * Hide or show the background overlay controls.
	 *
	 * @since 2.0.0
	 */
	var toggleOverlay = function ( bgType ) {
		var state,
			opts = { duration: 0 },
			overlayControl = api.control( 'bgtfw_background_overlay' ),
			backgroundImage = api( 'background_image' ),
			overlayColorControl = api.control( 'bgtfw_background_overlay_color' ),
			overlayAlphaControl = api.control( 'bgtfw_background_overlay_alpha' );

		state = bgType === 'pattern' || ! backgroundImage() ? 'deactivate' : 'activate';
		overlayControl[ state ]( opts );

		state = overlayControl.setting() && overlayControl.active() ? 'activate' : 'deactivate';
		overlayColorControl[ state ]( opts );
		overlayAlphaControl[ state ]( opts );
	};

	var getAttachmentControl = function() {
		if ( wp.customize.control( 'background_attachment' ) ) {
			return wp.customize.control( 'background_attachment' );
		} else {
			return wp.customize.control( 'boldgrid_background_attachment' );
		}
	};

	var bind_all = function() {
		var background_control_refresh = [
			'background_image',
			'background_attachment',
			'background_repeat',
			'bgtfw_background_overlay',
			'boldgrid_background_image_size',
			'boldgrid_background_horizontal_position',
			'boldgrid_background_type',
			'boldgrid_background_vertical_position'
		];

		$.each( background_control_refresh, function() {
			wp.customize( this, function( value ) {
				value.bind( validate_selection_set );
			} );
		} );
	};
}

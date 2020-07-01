import PaletteSelector from '../color/palette-selector';

const colorLib = window.net.brehaut;
const api = wp.customize;

export default function() {

	'use strict';

	var init = function() {
		bindColors();

		if ( api.section( 'background_image' ).expanded() ) {
			updateBgType( api( 'boldgrid_background_type' )() );
			validateSelectionSet();
		}

	};

	/**
	 * Update Background Type Actions.
	 *
	 * This is where we hide the color and pattern controls.
	 *
	 * @since 2.1.1
	 *
	 * @param {String} to thememod string to check against.
	 */
	var updateBgType = ( to ) => {
		let init = 'activate',
			opts = { duration: 0 },
			state = 'image' === to ? `de${init}` : init;

		[ 'boldgrid_background_color', 'boldgrid_background_pattern' ].map( id => api.control( id )[ state ]( opts ) );
	};

	/**
	 * When the background panel is opened, load available background patterns.
	 *
	 * @since 2.1.1
	 */
	var loadSection = function() {
		let loaded = false;
		api.section( 'background_image' ).expanded.bind( expanded => {
			if ( expanded && ! loaded ) {
				loaded = true;
				bindValidation();
				validateSelectionSet();
				backgroundSectionInit();
				updateBgType( api( 'boldgrid_background_type' )() );
				api( 'boldgrid_background_type', value => value.bind( ( to ) => updateBgType( to ) ) );
				setBackgroundPatterns().then( () => setActivePattern() );
				appendHeadStyles();
			} else if ( expanded ) {
				updateBgType( api( 'boldgrid_background_type' )() );
				validateSelectionSet();
			}
		} );
	};

	/**
	 * Add patterns to section.
	 *
	 * This is a chunked resource and offloaded to avoid high resource
	 * usage until it's needed.
	 *
	 * @since 2.1.1
	 */
	var backgroundSectionInit = () => {
		let wrapper = document.getElementById( 'boldgrid_background_pattern-control-wrapper' ),
			button = document.getElementById( 'boldgrid_background_pattern-remove-pattern' );

		$( button ).on( 'click', () => {
			$( '#boldgrid_background_pattern .active-pattern' ).removeClass( 'active-pattern' );
			api( 'boldgrid_background_pattern' ).set( '' );
			wrapper.removeAttribute( 'data-pattern-selected' );
			button.disabled = true;
		} );

		$( '#boldgrid_background_pattern .patternpreview' ).on( 'click', ( e ) => {
			let el = $( e.currentTarget );
			if ( ! el.hasClass( 'active-pattern' ) ) {
				$( '#boldgrid_background_pattern .active-pattern' ).removeClass( 'active-pattern' );
				el.addClass( 'active-pattern' );
				api( 'boldgrid_background_pattern' ).set( el.css( 'background-image' ) );
				wrapper.dataset.patternSelected = 1;
				button.disabled = false;
			}
		} );

		// Init Button Set.
		$( '.accordion-section-content, .wp-full-overlay-sidebar-content' ).on( 'scroll', function() {
			let el = $( this );
			if ( 75 <= el.scrollTop() && 'pattern' === api( 'boldgrid_background_type' )() ) {
				el.addClass( 'boldgrid-sticky-customizer' );
			} else {
				el.removeClass( 'boldgrid-sticky-customizer' );
			}
		} );
	};

	/**
	 * Add patterns to section.
	 *
	 * This is a chunked resource and offloaded to avoid high resource
	 * usage until it's needed.
	 *
	 * @since 2.1.1
	 */
	var setBackgroundPatterns = async function() {
		const hero = await import( /* webpackChunkName: "hero-patterns" */ 'hero-patterns' );

		let color = new PaletteSelector(),
			bg = color.getColor( api( 'boldgrid_background_color' )(), true ),
			win = api.previewer.targetWindow(),
			contrast;

		contrast = win.BOLDGRID.Customizer.Util.getTextContrast( bg );

		bg = colorLib.Color( bg );
		bg = contrast.includes( 'light' ) ? bg.lightenByAmount( 0.075 ) : bg.darkenByAmount( 0.075 );
		bg = bg.toCSS();

		$( '#boldgrid_background_pattern .patternpreview' ).each( function() {
			this.style.backgroundImage = hero[ this.dataset.background ]( bg );
		} );

		return true;
	};

	var setActivePattern = function() {
		$( `#boldgrid_background_pattern .patternpreview[style*='background-image: ${ wp.customize( 'boldgrid_background_pattern' )() }']` ).addClass( 'active-pattern' );
	};

	var appendHeadStyles = function( to ) {
		var style = '';
		if ( ! to ) {
			to = api( 'boldgrid_background_color' )();
		}

		$( '#customizer_boldgrid_background_color' ).remove();

		if ( to ) {
			style += '<style id="customizer_boldgrid_background_color">';
			style += '#customize-control-boldgrid_background_pattern .patternpreview{background-color:' + to.split( ':' ).pop() + ';}';
			style += '</style>';

			$( 'head' ).append( $( style ) );
		}
	};

	/**
	 * Bind color related controls for backgrounds.
	 *
	 * @since 2.1.1
	 */
	var bindColors = () => {
		api( 'boldgrid_background_color', 'boldgrid_color_palette', ( ...controls ) => controls.map( control => control.bind( setColors ) ) );
	};

	/**
	 * Set color for backgrounds based on selection.
	 *
	 * @since 2.1.1
	 */
	var setColors = () => {
		if ( 'pattern' === api( 'boldgrid_background_type' )() ) {
			setBackgroundPatterns().then( () => {
				let pattern = $( '#customize-control-boldgrid_background_pattern' ).find( '.active-pattern' ).css( 'background-image' );
				$( '#boldgrid_background_pattern input' ).val( pattern ).change();
				api( 'boldgrid_background_pattern' ).set( pattern );
				appendHeadStyles();
			} );
		}
	};

	var validateSelectionSet = function() {
		var opts = { duration: 0 },
			type = api( 'boldgrid_background_type' )();

		if ( 'pattern' === type ) {

			// Activate Pattern.
			api.control( 'boldgrid_background_pattern' ).activate( opts );
			api.control( 'boldgrid_background_color' ).activate( opts );

			// Deactivate Image.
			api.control( 'boldgrid_background_image_size' ).deactivate( opts );
			api.control( 'background_image' ).deactivate( opts );
			api.control( 'background_repeat' ).deactivate( opts );
			getAttachmentControl().deactivate( opts );

		} else {

			// Activate Image.
			api.control( 'boldgrid_background_image_size' ).activate( opts );
			api.control( 'background_image' ).activate( opts );
			getAttachmentControl().activate( opts );
			api.control( 'background_repeat' ).activate( opts );

			if ( ! api( 'background_image' )() ) {
				api.control( 'boldgrid_background_image_size' ).deactivate( opts );
				getAttachmentControl().deactivate( opts );
				api.control( 'background_repeat' ).deactivate( opts );
			} else {
				api.control( 'boldgrid_background_image_size' ).activate( opts );
				getAttachmentControl().activate( opts );
				api.control( 'background_repeat' ).activate( opts );
			}

			if ( 'parallax' === api( 'background_attachment' )() ) {
				api.control( 'boldgrid_background_image_size' ).deactivate( opts );
				api.control( 'background_repeat' ).deactivate( opts );
			}
		}

		toggleOverlay( type );
	};

	/**
	 * Hide or show the background overlay controls.
	 *
	 * @since 2.0.0
	 */
	var toggleOverlay = function( bgType ) {
		var state,
			opts = { duration: 0 },
			overlayControl = api.control( 'bgtfw_background_overlay' ),
			backgroundImage = api( 'background_image' ),
			overlayColorControl = api.control( 'bgtfw_background_overlay_color' ),
			overlayTypeControl = api.control( 'bgtfw_background_overlay_type' ),
			overlayAlphaControl = api.control( 'bgtfw_background_overlay_alpha' );

		state = 'pattern' === bgType || ! backgroundImage() ? 'deactivate' : 'activate';
		overlayControl[ state ]( opts );

		state = overlayControl.setting() && overlayControl.active() ? 'activate' : 'deactivate';
		overlayColorControl[ state ]( opts );
		overlayAlphaControl[ state ]( opts );
		overlayTypeControl[ state ]( opts );
	};

	/**
	 * Get Background Attachment Control.
	 *
	 * Determines if new WP bg attachment control is used,
	 * or a BoldGrid deprecated attachment control.
	 *
	 * @since 2.0.0
	 */
	var getAttachmentControl = function() {
		let id = 'background_attachment';
		id = api.control( id ) ? id : `boldgrid_${id}`;
		return api.control( id );
	};

	var bindValidation = function() {
		let ids = [
			'background_image',
			'background_attachment',
			'background_repeat',
			'bgtfw_background_overlay',
			'boldgrid_background_image_size',
			'boldgrid_background_type'
		];

		api( ...ids, ( ...controls ) => controls.map( control => control.bind( validateSelectionSet ) ) );
	};

	$( function() {

		/**
		 * @todo This needs to be refactored. All these events should not be each time
		 * preview iframe is refreshed.
		 */
		$( window ).on( 'boldgrid_customizer_refresh', init );
		loadSection();
	} );
}

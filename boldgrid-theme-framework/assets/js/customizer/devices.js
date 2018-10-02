const $ = jQuery;
const api = wp.customize;

/**
 * Customizer Devices
 *
 * This class handle the various device previews in the WordPress customizer.
 *
 * @since 2.0.0
 *
 * @type {Devices}
 */
export class Devices {

	/**
	 * Initialize class and set class properties.
	 *
	 * @since 2.0.0
	 */
	constructor() {
		this.devices = {
			large: {
				name: 'large',
				breakpoint: 1200
			},
			desktop: {
				name: 'desktop',
				breakpoint: 992
			},
			tablet: {
				name: 'tablet',
				breakpoint: 768
			},
			mobile: {
				name: 'mobile',
				breakpoint: 0
			}
		};
		this.shouldChange = true;
		this.currentDevice = '';
		this.previewedDevice = '';
		this.container = null;
		this.window = $( window );
		this.regex = /(^|\s)previewed-from-(?=desktop|mobile|large|tablet)\S+/g;
	}

	/**
	 * Init
	 *
	 * @since 2.0.0
	 */
	init() {
		this._attachEvents();
	}

	/**
	 * Attach events.
	 *
	 * @since 2.0.0
	 */
	_attachEvents() {
		this._customizerReady();
	}

	/**
	 * Bind to customizer on ready event.
	 *
	 * This makes sure that the customizer is loaded and ready
	 * before we interact with it.
	 *
	 * @since 2.0.0
	 */
	_customizerReady() {
		api.bind( 'ready', () => this._previewerReady() );
	}

	/**
	 * Bind to previewer on ready event.
	 *
	 * This makes sure that the preview iframe is loaded before
	 * we interact with it.
	 *
	 * @since 2.0.0
	 */
	_previewerReady() {
		api.previewer.bind( 'ready', () => {
			this.onLoad();
			this._resize();
			this._click();
		} );
	}

	/**
	 * Handles resize event.
	 *
	 * @since 2.0.0
	 */
	_resize() {
		$( window ).on( 'resize', _.debounce( () => {
			this.detectDevice();

			if ( this.previewedDevice === this.currentDevice ) {
				this.shouldChange = true;
			}

			if ( this.shouldChange ) {
				this.toggleClass( this.currentDevice );
				this.setDevice( this.currentDevice, false );
			}
		}, 300 ) );
	}

	/**
	 * Handles click events for device preview buttons.
	 *
	 * @since 2.0.0
	 */
	_click() {
		$( '.devices > button' ).on( 'click', ( event, data ) => {
			if ( _.isUndefined( data ) || ! _.isUndefined( data.internal ) && ! data.internal ) {
				this.shouldChange = false;
			}

			this.previewedDevice = $( event.currentTarget ).data( 'device' );

			// Wait for frame resize transition since it uses transforms.
			wp.customize.previewer.preview.container.one( 'webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', () => this.setBodyHeight() );

			this.detectDevice();
		} );
	}

	/**
	 * Things to do on load of customizer previewer.
	 *
	 * @since 2.0.0
	 */
	onLoad() {
		this.container = api.previewer.preview.container;
		this.detectDevice();

		if ( ! this.hasClass() ) {
			this.toggleClass( this.currentDevice );
			this.setDevice( this.currentDevice, false );
			$( '.devices > button' ).blur();
			this.shouldChange = true;
		}

		this.setupScroll();
	}

	/**
	 * Setup scrolling bugs.
	 *
	 * @since 2.0.0
	 */
	setupScroll() {
		let preview = api.previewer.preview.iframe[0];

		// Set seamless for html5.
		preview.seamless = true;

		// Set overflow since scrolling attribute is not valid for HTML5.
		frames[ preview.name ].document.body.style.overflow = 'hidden';

		// Set the body height.
		this.setBodyHeight();

		// On resize recalc the height offsets.

		$( window ).on( 'resize', _.debounce( () => {
			this.setBodyHeight();
		}, 300 ) );

		// Scroll syncing ** Do Not Debounce **.
		$( window ).on( 'scroll', () => {
			let name = api.previewer.preview.iframe[0].name;
			let doc = frames[ name ].document;
			doc.documentElement.scrollTop = window.pageYOffset;
			doc.body.scrollTop = window.pageYOffset; // Google Chrome, Safari, documents without valid doctype.
			$( '.wp-customizer .wp-full-overlay' ).scrollLeft( window.pageXOffset );
		} );

	}

	setBodyHeight() {
		let name = api.previewer.preview.iframe[0].name;
		document.body.style.height = frames[ name ].document.body.offsetHeight + 'px';

		let previewWidth = frames[ name ].document.body.offsetWidth;
		let controlWidth = document.getElementById( 'customize-controls' ).offsetWidth;
		document.body.style.width = Math.abs( previewWidth + controlWidth ) + 'px';
	}

	/**
	 * Create or update stylesheet in the head of the preview iframe.
	 *
	 * @since 2.0.0
	 *
	 * @return {String} device The device size entering customizer.
	 */
	detectDevice() {
		let width = window.innerWidth - $( '#customize-controls' ).outerWidth();

		if ( this.devices.tablet.breakpoint > width ) {
			this.currentDevice = this.devices.mobile.name;
		} else if ( this.devices.tablet.breakpoint <= width && this.devices.desktop.breakpoint >= width ) {
			this.currentDevice = this.devices.tablet.name;
		} else if ( this.devices.desktop.breakpoint < width && this.devices.large.breakpoint >= width ) {
			this.currentDevice = this.devices.desktop.name;
		} else {
			this.currentDevice = this.devices.large.name;
		}

		return this.currentDevice;
	}

	/**
	 * Set the device size of previewer window in customizer.
	 *
	 * @since 2.0.0
	 *
	 * @param {String} device The device size to set for previewer.
	 * @param {Bool} focus Should focus be applied?
	 */
	setDevice( device ) {
		let button = $( `.devices > [data-device="${device}"]` );

		// Remove focus from currently focused buttons.
		$( '.devices > button' ).blur();

		button.trigger( 'click', [ { internal: true } ] ).blur();
	}

	/**
	 * Toggle the device class on iframe for CSS media queries.
	 *
	 * @since 2.0.0
	 *
	 * @param {String} device The device size to set for previewer.
	 * @param {Function} callback Optional callback method.
	 */
	toggleClass( device, callback ) {
		this.removeClass();

		this.container.addClass( `previewed-from-${device}` );
		document.body.className += ` bgtfw-is-${device}`;
		'function' === typeof( callback ) && callback();
	}

	/**
	 * Removes previewer-from- classes from iframe for CSS media queries.
	 *
	 * @since 2.0.0
	 */
	removeClass() {
		document.body.className = document.body.className.replace( /(^|\s)bgtfw-is-(?=desktop|mobile|large|tablet)\S+/g, '' );
		this.container.removeClass( ( index, className ) => {
			return ( className.match( this.regex ) || [] ).join( ' ' );
		} );
	}

	/**
	 * Checks if previewer-from- classes exist on the container before modifying.
	 *
	 * @since 2.0.0
	 */
	hasClass() {
		return this.regex.test( this.container.attr( 'class' ) );
	}
}

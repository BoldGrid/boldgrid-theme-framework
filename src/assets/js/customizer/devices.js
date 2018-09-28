var $ = jQuery;

export class Devices {

	/**
	 * Initialize class and set class properties.
	 *
	 * @since 2.0.0
	 */
	constructor() {
		this.container = wp.customize.previewer.preview.container;
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
		this.window.on( 'resize', () => {
			let device = this.detectDevice();
			this.toggleClass( device );
		} );
	}

	/**
	 * Create or update stylesheet in the head of the preview iframe.
	 *
	 * @since 2.0.0
	 *
	 * @return {String} device The device size entering customizer.
	 */
	detectDevice() {
		let width = this.window.outerWidth() - $( '#customize-controls' ).outerWidth();

		let	device = 'large';

		if ( 768 > width ) {
			device = 'mobile';
		} else if ( 768 <= width && 992 >= width ) {
			device = 'tablet';
		} else if ( 992 < width && 1200 >= width ) {
			device = 'desktop';
		}

		return device;
	}

	/**
	 * Set the device size of previewer window in customizer.
	 *
	 * @since 2.0.0
	 *
	 * @param {String} device The device size to set for previewer.
	 */
	setDevice( device ) {
		$( `.devices > [data-device="${device}"]` ).click();
	}

	/**
	 * Toggle the device class on iframe for CSS media queries.
	 *
	 * @since 2.0.0
	 *
	 * @param {String} device The device size to set for previewer.
	 */
	toggleClass( device ) {
		this.removeClass();
		this.container.addClass( `previewed-from-${device}` );
	}

	/**
	 * Removes previewer-from- classes from iframe for CSS media queries.
	 *
	 * @since 2.0.0
	 */
	removeClass() {
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

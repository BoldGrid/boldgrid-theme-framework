const api = wp.customize;

/**
 * HeaderBackground Class
 *
 * Helps with Header Background controls in customizer.
 *
 * @since 2.2.1
 */
export class HeaderBackground {

	/**
	 * Constructor.
	 *
	 * @since 2.2.1
	 */
	constructor() {
		var self = this;

		self.imageVideoControls = [
			'header_image',
			'header_video',
			'external_header_video'
		];
		self.headerOverlayControls = [
			'bgtfw_header_overlay',
			'bgtfw_header_overlay_color',
			'bgtfw_header_overlay_alpha'
		];

		api.bind( 'ready', () => this.ready( self ) );
	}

	/**
	 * Ready.
	 *
	 * This method fires when the wp.customize is ready.
	 *
	 * @since 2.2.1
	 *
	 * @param {HeaderBackground} self This Object.
	 */
	ready( self ) {
		var sectionId = api.control( 'bgtfw_header_overlay' ).section();

		api.previewer.bind( 'ready', function() {
			if ( self.hasHeaderImage() || self.hasHeaderVideo() ) {
				self.activateOverlayControls();
			} else {
				self.deactivateOverlayControls();
			}
		} );
		api.section( sectionId, function( section ) {
			section.expanded.bind( function() {
				if ( self.hasHeaderImage() || self.hasHeaderVideo() ) {
					self.activateOverlayControls();
				} else {
					self.deactivateOverlayControls();
				}
			} );
		} );

		$.each( self.imageVideoControls, function() {
			api( this, function( value ) {
				value.bind( function() {
					self.toggleVideoDisplay( self );
					if ( self.hasHeaderImage() || self.hasHeaderVideo() ) {
						self.activateOverlayControls();
					} else {
						self.deactivateOverlayControls();
					}
				} );
			} );
		} );
	}

	/**
	 * Toggle Video Display
	 *
	 * Toggles the header video display control.
	 *
	 * @since 2.3.1
	 */
	toggleVideoDisplay( self ) {
		if ( self.hasHeaderVideo() ) {
			api.control( 'bgtfw_video_background_all' ).activate();
		} else {
			api.control( 'bgtfw_video_background_all' ).deactivate();
		}
	}

	/**
	 * Has Header Image
	 *
	 * @since 2.2.1
	 *
	 * @return {bool} Returns true if there is a header image set.
	 */
	hasHeaderImage() {
		var headerImage = api( 'header_image' )();

		if ( headerImage && 'remove-header' !== headerImage ) {
			return true;
		}

		return false;
	}

	/**
	 * Has Header Video.
	 *
	 * @since 2.2.1
	 *
	 * @return {bool} Returns true if there is a header video set.
	 */
	hasHeaderVideo() {
		var headerVideo = api( 'header_video' )(),
			externalVideo = api( 'external_header_video' )();

		return headerVideo || externalVideo ? true : false;
	}

	/**
	 * Activate Overlay Controls.
	 *
	 * Activates Header Overlay and related controls.
	 *
	 * @since 2.2.1
	 *
	 */
	activateOverlayControls() {
		api.control( 'bgtfw_header_overlay' ).activate();
		if ( api( 'bgtfw_header_overlay' )() ) {
			api.control( 'bgtfw_header_overlay_color' ).activate();
			api.control( 'bgtfw_header_overlay_alpha' ).activate();
		} else {
			api.control( 'bgtfw_header_overlay_color' ).deactivate();
			api.control( 'bgtfw_header_overlay_alpha' ).deactivate();
		}
	}

	/**
	 * Deactivate Overlay Controls.
	 *
	 * Deactivates Header Overlay and related controls.
	 *
	 * @since 2.2.1
	 */
	deactivateOverlayControls() {
		api.control( 'bgtfw_header_overlay' ).deactivate();
		api.control( 'bgtfw_header_overlay_color' ).deactivate();
		api.control( 'bgtfw_header_overlay_alpha' ).deactivate();
	}
}

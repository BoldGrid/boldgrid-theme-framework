(function( wp, $ ){
	'use strict';

	if ( ! wp || ! wp.customize ) { return; }

	// Set up our namespace.
	var api = wp.customize;

	api.croppingBackgroundImageControl = api.CroppedImageControl.extend({
		/**
		 * Updates the setting and re-renders the control UI.
		 *
		 * Save the image to the setting instead of the attachment id.
		 *
		 * @param {object} attachment
		 */
		setImageFromAttachment: function( attachment ) {
			this.params.attachment = attachment;

			// Set the Customizer setting; the callback takes care of rendering.
			this.setting( attachment.sizes.full.url );
		}

	});
	
	/**
	 * Extends wp.customizer.controlConstructor with control constructor for
	 * background_image.
	 */
	$.extend( api.controlConstructor, {
		background: api.croppingBackgroundImageControl 
	});
})( window.wp, jQuery );
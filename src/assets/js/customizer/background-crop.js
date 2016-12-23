( function( wp, $ ) {

	'use strict';

	if ( ! wp || ! wp.customize ) {
		return;
	}

	// Set up our namespace.
	var api = wp.customize;

	api.croppingBackgroundImageControl = api.CroppedImageControl.extend({

		ready: function() {

			var control = this,
				loadedAttachment;

			var setAttachmentDataAndRenderContent = function() {

				// Reattach data to object.
				control.params.attachment = loadedAttachment;
				control.renderContent();
			};

			// Store the attachment data.
			loadedAttachment = this.params.attachment;
			this.setting.bind( function() {
				loadedAttachment = control.params.attachment;
			} );

			// Bind Listeners.
			api.CroppedImageControl.prototype.ready.apply( this, arguments );
			setAttachmentDataAndRenderContent( control.setting() );
			this.setting.bind( setAttachmentDataAndRenderContent );
		},

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

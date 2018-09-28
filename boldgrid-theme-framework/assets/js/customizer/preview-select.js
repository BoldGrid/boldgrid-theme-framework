var $ = jQuery;

export class PreviewSelect {

	/**
	 * Create or update stylesheet in the head of the preview iframe.
	 *
	 * @since 2.0.0
	 *
	 * @param  {string} id  ID of the style tag.
	 * @param  {string} css CSS for the style tag.
	 * @return {jQuery}     DOM element.
	 */
	detectDevice() {
		let width = wp.customize.previewer.preview.iframe.outerWidth(),
			device = 'mobile';

		if ( 1200 >= width ) {
			device = 'large';
		}

		if ( 992 >= width ) {
			device = 'desktop';
		}

		if ( 768 >= width ) {
			device = 'tablet';
		}

		return device;
	}

	setDevice( device ) {
		$( `.devices > [data-device="${device}"]` ).click();
	}

}

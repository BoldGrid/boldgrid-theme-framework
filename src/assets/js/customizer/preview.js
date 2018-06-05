var $ = jQuery;

export class Preview {

	/**
	 * Create or update stylesheet in the head of the preview iframe.
	 *
	 * @since 2.0.0
	 *
	 * @param  {string} id  ID of the style tag.
	 * @param  {string} css CSS for the style tag.
	 * @return {jQuery}     DOM element.
	 */
	updateDynamicStyles( id, css ) {
		const $head = $( 'head' );
		let $selector = $( '#' + id );

		if ( $selector.length ) {
			$selector.html( css );
		} else {
			$selector = $( '<style type="text/css" id="' + id + '">' );
			$selector.html( css );
			$head.append( $selector );
		}

		return $selector;
	}

}

<?php 
/**
 * Functionality From: https://wordpress.org/plugins/background-image-cropper/
 */
class Boldgrid_Framework_Background_Crop extends WP_Customize_Cropped_Image_Control {
	public $type = 'background';

	public function enqueue() {
		wp_enqueue_script( 'boldgrid-background-image-cropper' );
	}

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @since 3.4.0
	 *
	 * @uses WP_Customize_Media_Control::to_json()
	 */
	public function to_json() {
		parent::to_json();

		$value = $this->value();
		if ( $value ) {
			// Get the attachment model for the existing file.
			$attachment_id = attachment_url_to_postid( $value );
			if ( $attachment_id ) {
				$this->json['attachment'] = wp_prepare_attachment_for_js( $attachment_id );
			}
		}
	}
}
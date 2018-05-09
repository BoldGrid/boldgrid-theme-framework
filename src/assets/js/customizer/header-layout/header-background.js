( function( $ ) {

	// Toggle a body class if a custom header exists.
	$.each( [ 'external_header_video', 'header_image', 'header_video' ], function( index, settingId ) {
		wp.customize( settingId, function( setting ) {

			// Whether a header image is available.
			var hasHeaderImage = function() {
				var image = wp.customize( 'header_image' )();
				return '' !== image && 'remove-header' !== image;
			};

			setting.bind( function() {

				// Check if a header image has been provided and update body classes.
				if ( hasHeaderImage() ) {
					$( document.body ).addClass( 'has-header-image' );
				} else {
					$( document.body ).removeClass( 'has-header-image' );
				}

				$( document.body ).removeClass( 'has-video-header has-youtube-header' );

				// Check if a YouTube Video is loaded.
				if ( wp.customize( 'external_header_video' )() !== '' ) {
					$( document.body ).addClass( 'has-youtube-video' );
					$( document.body ).removeClass( 'has-video-header' );
				}

				// Check if a header video is provided as preferred state.
				if ( 0 !== wp.customize( 'header_video' )() && '' !== wp.customize( 'header_video' )() ) {
					$( document.body ).addClass( 'has-video-header' );
					$( document.body ).removeClass( 'has-youtube-header' );
				}
			} );
		} );
	} );
} )( jQuery );

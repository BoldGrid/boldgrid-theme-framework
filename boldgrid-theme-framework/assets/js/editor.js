( function( $ ) {
	'use strict';

	$( function() {
		tinymce.PluginManager.add( 'boldgrid_theme_framework', function( editor ) {
			editor.on( 'init', function() {
				var $style,
					$iframeHead;

				if ( BOLDGRID_THEME_FRAMEWORK.Editor && BOLDGRID_THEME_FRAMEWORK.Editor.mce_inline_styles ) {
					$style = $( '<style>' );
					$style.html( BOLDGRID_THEME_FRAMEWORK.Editor.mce_inline_styles );
					$iframeHead = $( tinyMCE.activeEditor.iframeElement ).contents().find( 'head' );

					$iframeHead.append( $style );

					// Copy all google fonts into the editor.
					$( 'head link[rel="stylesheet"][href*="fonts.googleapis.com/css"]' ).each( function() {
						$iframeHead.append( $( this ).clone() );
					} );
				}
			} );

		} );

	} );

})( jQuery );

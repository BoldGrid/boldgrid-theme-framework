( function( $ ) {
	'use strict';

	$( function() {
		tinymce.PluginManager.add( 'boldgrid_theme_framework', function( editor ) {
			editor.on( 'init', function() {
				var $style;

				if ( BOLDGRID_THEME_FRAMEWORK.Editor && BOLDGRID_THEME_FRAMEWORK.Editor.mce_inline_styles ) {
					$style = $( '<style>' );
					$style.html( BOLDGRID_THEME_FRAMEWORK.Editor.mce_inline_styles );

					$( tinyMCE.activeEditor.iframeElement ).contents().find( 'head' ).append( $style );
				}
			} );

		} );

	} );

})( jQuery );

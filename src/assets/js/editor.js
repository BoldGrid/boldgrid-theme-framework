( function( $ ) {
	'use strict';

	$( function() {
		tinymce.PluginManager.add( 'boldgrid_theme_framework', function( editor ) {
			editor.on( 'init', function() {
				if ( BOLDGRID_THEME_FRAMEWORK.Editor && BOLDGRID_THEME_FRAMEWORK.Editor.mce_inline_styles ) {
					tinyMCE.activeEditor.dom.addStyle( BOLDGRID_THEME_FRAMEWORK.Editor.mce_inline_styles );
				}
			} );

		} );

	} );

})( jQuery );

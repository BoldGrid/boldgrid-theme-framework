/* global tinymce, BOLDGRID_THEME_FRAMEWORK, tinyMCE */
( function( $ ) {
	'use strict';

	/*
	 * Removing this from the document ready event, to be sure this is added before
	 * early enough in the page load
	 */
	tinymce.PluginManager.add( 'boldgrid_theme_framework', function( editor ) {
		editor.on( 'init', function() {
			var $style,
				$iframeHead;

			if ( BOLDGRID_THEME_FRAMEWORK.Editor && BOLDGRID_THEME_FRAMEWORK.Editor.mce_inline_styles ) {
				$style = $( '<style class="mce-inline-styles">' );
				$style.html( BOLDGRID_THEME_FRAMEWORK.Editor.mce_inline_styles );
				$iframeHead = $( tinyMCE.activeEditor.iframeElement ).contents().find( 'head' );

				$iframeHead.append( $style );

				// Copy all google fonts into the editor.
				$( 'head link[rel="stylesheet"][href*="fonts.googleapis.com/css"], [id^="kirki-local-webfonts"]' ).each( function() {
					$iframeHead.append( $( this ).addClass( 'webfontjs-loader-styles' ).clone() );
				} );
			}
		} );
	} );

	$( function() {
		if ( hasPPBEnabled() ) {
			setupPPB();
		}
	} );

	/**
	 * Hook into any PPB events.
	 *
	 * @since 2.0.0
	 */
	function setupPPB() {

		// Add widget classes. copied form bootstrap-shim/customizer.js
		window.BOLDGRID.EDITOR.Service.event.on( 'shortcodeUpdated', function( node ) {
			var $node = $( node );

			$node.find( '.widget_rss ul' ).addClass( 'media-list' );

			$node.find( '.widget_meta ul, .widget_recent_entries ul, .widget_archive ul, .widget_categories ul, .widget_nav_menu ul, .widget_pages ul' )
				.addClass( 'nav' );

			$node.find( '.widget_recent_comments ul#recentcomments' )
				.css( { 'list-style': 'none', 'padding-left': '0' } );

			$node.find( '.widget_recent_comments ul#recentcomments li' ).css( 'padding', '5px 15px' );
		} );

		// Append variables to the document head.
		if ( BOLDGRID_THEME_FRAMEWORK.Editor && BOLDGRID_THEME_FRAMEWORK.Editor.mce_inline_styles ) {
			let $style = $( '<style id="bgtfw-inline"></style>' );
			let styles = BOLDGRID_THEME_FRAMEWORK.Editor.mce_inline_styles.replace( /h1, .h1.*}/, '' );
			$style.html( styles );
			$( 'head' ).append( $style );
		}
	}

	/**
	 * Check if the PPB is available.
	 *
	 * @since 2.0.0
	 *
	 * @return {Boolean} Is PPB available?
	 */
	function hasPPBEnabled() {
		var BOLDGRID = window.BOLDGRID;
		return BOLDGRID && BOLDGRID.EDITOR && BOLDGRID.EDITOR.Service && BOLDGRID.EDITOR.$window;
	}

} )( jQuery );

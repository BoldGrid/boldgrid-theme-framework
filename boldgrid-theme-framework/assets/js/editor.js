/* global tinymce,BOLDGRID_THEME_FRAMEWORK,tinyMCE,jQuery */

var BOLDGRID = BOLDGRID || {};
BOLDGRID.BGTFW = BOLDGRID.BGTFW || {};

( function( $ ) {
	'use strict';

	BOLDGRID.BGTFW.Editor = {

		/**
		 * @summary Initialize the "value displayed" element.
		 *
		 * @since 2.0.0
		 */
		initValueDisplayed: function() {
			$( '.bgtfw-misc-pub-section' ).each( function() {
				var $section = $( this ),
					$defaultOption = $section.find( 'input[data-default-option="1"]' );

				$section.find( '.value-displayed' ).html( $defaultOption.attr( 'data-value-displayed' ) );
			} );
		},

		/**
		 * @summary Handle the click of the Edit link.
		 *
		 * @since 2.0.0
		 *
		 * @memberOf BOLDGRID.BGTFW.Editor
		 */
		onClickEdit: function() {
			var $edit = $( this ),
				$section = $edit.closest( '.bgtfw-misc-pub-section' );

			$section.find( '.options' ).slideToggle( 'fast' );
			$edit.toggle();

			return false;
		},

		/**
		 * @summary Handle the click of the Cancel link.
		 *
		 * @since 2.0.0
		 */
		onClickCancel: function() {
			var $cancel = $( this ),
				$section = $cancel.closest( '.bgtfw-misc-pub-section' ),
				$defaultOption = $section.find( 'input[data-default-option="1"]' );

			$section
				.find( '.options' ).slideToggle( 'fast' ).end()
				.find( '.edit' ).toggle().end()
				.find( '.value-displayed' ).html( $defaultOption.attr( 'data-value-displayed' ) );

			$defaultOption.prop( 'checked', true );

			return false;
		},

		/**
		 * @summary Handle the click of the OK button.
		 *
		 * @since 2.0.0
		 */
		onClickOk: function() {
			var $ok = $( this ),
				$section = $ok.closest( '.bgtfw-misc-pub-section' ),
				$selected = $section.find( 'input:checked' );

			$section
				.find( '.options' ).slideToggle( 'fast' ).end()
				.find( '.edit' ).toggle().end()
				.find( '.value-displayed' ).html( $selected.attr( 'data-value-displayed' ) );

			return false;
		}
	};

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
						$iframeHead.append( $( this ).addClass( 'webfontjs-loader-styles' ).clone() );
					} );
				}
			} );

		} );

		$( 'body' ).on( 'click', '.bgtfw-misc-pub-section a.edit', BOLDGRID.BGTFW.Editor.onClickEdit );
		$( 'body' ).on( 'click', '.bgtfw-misc-pub-section a.button-cancel', BOLDGRID.BGTFW.Editor.onClickCancel );
		$( 'body' ).on( 'click', '.bgtfw-misc-pub-section a.button', BOLDGRID.BGTFW.Editor.onClickOk );
		BOLDGRID.BGTFW.Editor.initValueDisplayed();
	} );
})( jQuery );

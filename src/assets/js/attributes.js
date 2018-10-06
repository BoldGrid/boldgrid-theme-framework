/**
 * Handle page attributes within the editor.
 *
 * Specifically, handle the edit, ok, and cancel options. This replicates the edit, ok, and cancel
 * options native to meta boxes (such as the status and visibility settings in the publish meta box).
 *
 * @since 2.0.0
 */

/* global jQuery */

var BOLDGRID = BOLDGRID || {};
BOLDGRID.BGTFW = BOLDGRID.BGTFW || {};

( function( $ ) {
	'use strict';

	BOLDGRID.BGTFW.Attributes = {

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
		 * @memberOf BOLDGRID.BGTFW.Attributes
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
		$( 'body' ).on( 'click', '.bgtfw-misc-pub-section a.edit', BOLDGRID.BGTFW.Attributes.onClickEdit );
		$( 'body' ).on( 'click', '.bgtfw-misc-pub-section a.button-cancel', BOLDGRID.BGTFW.Attributes.onClickCancel );
		$( 'body' ).on( 'click', '.bgtfw-misc-pub-section a.button', BOLDGRID.BGTFW.Attributes.onClickOk );
		BOLDGRID.BGTFW.Attributes.initValueDisplayed();

		// Handle click of "Advanced Options".
		$( '#bgtfw-attributes-meta-box .advanced-toggle' ).on( 'click', function() {
			$( '.post-attributes-advanced-wrap' ).slideToggle();
			$( this ).toggleClass( 'open' );
		});
	} );
})( jQuery );

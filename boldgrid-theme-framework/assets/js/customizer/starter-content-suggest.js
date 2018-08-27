/**
 * This file handles Customizer actions for suggesting the user install starter content.
 *
 * @summary Suggest starter content.
 *
 * @since 2.0.0
 * @requires jquery-ui-dialog
 */

/* global jQuery */

var BOLDGRID = BOLDGRID || {};
BOLDGRID.StarterContentSuggest = BOLDGRID.StarterContentSuggest || {};

( function ( $ ) {

	'use strict';

	var self, bg, api;

	bg = BOLDGRID;
	api = parent.wp.customize;

	/**
	 * Suggest starter content.
	 *
	 * @since 2.0.0
	 */
	BOLDGRID.StarterContentSuggest = {
			
		i18n: window.boldgridFrameworkCustomizerSuggest || {},
		
		/**
		 * Handle the user clicking "No" in the prompt.
		 * 
		 * @since 2.0.0
		 */
		onClickNo: function() {
			$( this ).dialog( 'close' );
			self.setSuggested();
		},
		
		/**
		 * Handle the user clicking "Yes" in the prompt.
		 * 
		 * @since 2.0.0
		 */
		onClickYes: function() {
			parent.window.location = self.i18n.starterContentUrl;
			self.setSuggested();
		},
		
		/**
		 * Flag that we have suggested to the user to install starter content.
		 * 
		 * @since 2.0.0
		 */
		setSuggested: function() {
			var data = {
					action: 'bgtfw_starter_content_suggested',
					security: $( '#suggest_nonce' ).val()
				};
			
			$.post( self.i18n.ajaxurl, data );
		},

		/**
		 * Init.
		 * 
		 * @since 2.0.0
		 */
		init: function() {
			self._onReady();
		},

		/**
		 * Action to take on document ready.
		 * 
		 * @since 2.0.0
		 */
		_onReady: function() {
			$( function() {
				var dialogSettings = {
					width: 400,
					resizable: false,
					modal: true,
					buttons: [
					    {
					      text: self.i18n.yes,
					      click: self.onClickYes
					    },
					    {
					    	text: self.i18n.no,
					    	click: self.onClickNo
						}
					]
				};
					
				$( '#dialog-starter-content-suggest' ).dialog( dialogSettings );
			});
		}
	};

	self = BOLDGRID.StarterContentSuggest;
} )( jQuery );

BOLDGRID.StarterContentSuggest.init();
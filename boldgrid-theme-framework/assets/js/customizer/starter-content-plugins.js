/**
 * This file handles installing starter content plugins.
 *
 * @summary Starter content plugins.
 *
 * @since 2.0.0
 */

/* global jQuery, ajaxurl */

var BOLDGRID = BOLDGRID || {};
BOLDGRID.StarterContentPlugins = BOLDGRID.StarterContentPlugins || {};

( function( $ ) {

	'use strict';

	var self;
	
	BOLDGRID.StarterContentPlugins = {
			
		installPluginsSuccess : false,
		
		activatePluginsSuccess : false,
		
		done : false,
		
		$form : null,
		
		$spinner : null,
		
		$installButton : null,

		/**
		 * Install plugins.
		 * 
		 * Make an ajax call to install plugins. When done "installing" them, make a call to
		 * self.activatePlugins to actually "activate" them.
		 * 
		 * @since 2.0.0
		 * 
		 * @param array plugins
		 */
		installPlugins : function( plugins ) {
			var request,
				data = {
					_wpnonce : $('[name="bgtfw-bulk-install"]').val(),
					_wp_http_referer: $('[name="_wp_http_referer"]').val(),
					action : 'tgmpa-bulk-install'
				};
	
			self.$installButton.addClass( 'disabled' ).css( 'pointer-events', 'none' );
			self.$spinner.css( 'visibility', 'visible' ).css( 'float', 'none' ).css( 'margin-left', '0px' );
	
			request = $.post( ajaxurl + '?page=bgtfw-install-plugins', data );
	
			request.done( function() {
				self.installPluginsSuccess = true;
				self.activatePlugins();
			});
	
			request.fail( function() {
				self.installPluginsSuccess = false;
				self.done = true;
				self.$form.submit();
			});
		},
	
		/**
		 * Activate plugins.
		 * 
		 * After the plugins have been "installed", this method is called to "activate" them.
		 * 
		 * @since 2.0.0
		 * 
		 * @param array plugins
		 */
		activatePlugins : function( plugins ) {
			var request,
				data = {
					_wpnonce : $('[name="bgtfw-bulk-activate"]').val(),
					_wp_http_referer: $('[name="_wp_http_referer"]').val(),
					action : 'tgmpa-bulk-activate'
				};
	
			request = $.post( ajaxurl + '?page=bgtfw-install-plugins', data );
	
			request.done( function() {
				self.activatePluginsSuccess = true;
			});
	
			request.fail( function(){
				self.activatePluginsSuccess = false;
			});

			// No matter what, we're done at this point.
			request.always( function() {
				self.done = true;
				self.$form.submit();
			});
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
		 * Action to take when a "starter content set" form is submitted.
		 * 
		 * Install required plugins, and then go to the customizer to install the starter content.
		 * 
		 * @since 2.0.0
		 * 
		 * @param Event object e
		 */
		onFormSubmit : function( e ) {
			var plugins;
			
			self.$form = $( this );
			plugins = self.$form.find( '[name="plugins"]' ).val();
	
			// If we don't have any plugins to install, go straight to the Customizer.
			if( ! plugins ) {
				return true;
			}
	
			plugins = JSON.parse( plugins );
			self.$spinner = self.$form.find( '.spinner' );
			self.$installButton = self.$form.find( '.button' );

			/*
			 * The first time we submit the form, ! self.done, we make an attempt to install the
			 * plugins. After that attempt, self.done, we submit the form again.
			 */
			if( self.done ) {
				if( ! self.activatePluginsSuccess || ! self.activatePluginsSuccess ) {
					
					// Show the error, hide the spinner, and stop.
					self.$form.find( '.notice-error' ).attr( 'style', 'display:block !important' );
					self.$spinner.hide();
					return false;
				} else {
					
					// Success, off to the Customizer.
					return true;
				}
			} else {
				
				// Install and activate the plugins.
				e.preventDefault();
				self.installPlugins( plugins );
			}
		},
		
		/**
		 * Action to take on document ready.
		 * 
		 * @since 2.0.0
		 */
		_onReady: function() {
			$( function() {
				$( 'form.starter-content-install' ).on( 'submit', self.onFormSubmit );
			});
		},
	};
	
	self = BOLDGRID.StarterContentPlugins;
	
} ( jQuery ) );

BOLDGRID.StarterContentPlugins.init();
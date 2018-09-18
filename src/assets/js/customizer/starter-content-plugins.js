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
			
		i18n: window.bgtfwCustomizerStarterContentPlugins || {},
			
		installPluginsSuccess : false,
		
		activatePluginsSuccess : false,
		
		done : false,
		
		$form : null,
		
		$spinner : null,
		
		$installButton : null,

		/**
		 * Our messages container.
		 *
		 * If there are any errors when installing starter content, this container will have error
		 * messages added to it.
		 *
		 * @since 2.0.0
		 */
		$messages : null,

		/**
		 * Install plugins.
		 * 
		 * Make an ajax call to install plugins. When done "installing" them, make a call to
		 * self.activatePlugins to actually "activate" them.
		 * 
		 * @since 2.0.0
		 */
		installPlugins : function() {
			var request,
				data = {
					_wpnonce : $('[name="bgtfw-bulk-install"]').val(),
					_wp_http_referer: $('[name="_wp_http_referer"]').val(),
					action : 'tgmpa-bulk-install',
					plugin : self.i18n.pluginData.to_install
				},
				onFail,
				onSuccess;
			
			onFail = function() {
				self.installPluginsSuccess = false;
				self.done = true;
				self.$form.submit();
			};
			
			onSuccess = function( response ) {
				response = response === undefined ? self.i18n.noResponseInstall : response;

				var $response = $( '<div>' + response + '</div>' ),
					$errors = $response.find( '.error' );

				if( $errors.length > 0 ) {
					self.$messages
						.show()
						.find( '.starter-content-error' )
							.empty()
							.append( $errors );
					onFail();
				} else {
					self.installPluginsSuccess = true;
					self.activatePlugins();
				}
			};
	
			self.$installButton.addClass( 'disabled' ).css( 'pointer-events', 'none' );
			self.$spinner.css( 'visibility', 'visible' ).css( 'float', 'none' ).css( 'margin-left', '0px' );
			
			if( 0 === data.plugin.length ) {
				// At this point, there were no plugins to install. Go check if any need to be activated.
				onSuccess( 'No plugins to install.' );	
			} else {
				// Ajax call to install necessary plugins (data.plugin).
				request = $.post( ajaxurl + '?page=bgtfw-install-plugins', data );
				request.done( onSuccess );
				request.fail( onFail );	
			}
		},
	
		/**
		 * Activate plugins.
		 * 
		 * After the plugins have been "installed", this method is called to "activate" them.
		 * 
		 * @since 2.0.0
		 */
		activatePlugins : function() {
			var request,
				data = {
					_wpnonce : $('[name="bgtfw-bulk-activate"]').val(),
					_wp_http_referer: $('[name="_wp_http_referer"]').val(),
					action : 'tgmpa-bulk-activate',
					plugin : self.i18n.pluginData.to_activate
				},
				onFail,
				onSuccess,
				onAlways;

			onFail = function() {
				self.activatePluginsSuccess = false;
			};

			onSuccess = function( response ) {
				response = response === undefined ? self.i18n.noResponseActivate : response;
				
				var $response = $( '<div>' + response + '</div>' ),
					$errors = $response.find( '.error' );
				
				if( $errors.length > 0 ) {
					self.$messages
						.show()
						.find( '.starter-content-error' )
							.empty()
							.append( $errors );
					onFail();
				} else {
					self.activatePluginsSuccess = true;
				}
			};
			
			/*
			 * This is the last step of the (1/2) install / (2/2) activate process. No matter what at
			 * this point, we're "always" done - it's time to submit the form again.
			 */
			onAlways = function() {
				self.done = true;
				self.$form.submit();
			};

			if( 0 === data.plugin.length ) {
				// At this point, there were no plugins to activate. We're done.
				self.activatePluginsSuccess = true;
				onAlways();
			} else {
				// Ajax call to activate necessary plugins (data.plugin).
				request = $.post( ajaxurl + '?page=bgtfw-install-plugins', data );
				request.done( onSuccess );
				request.fail( onFail );
				request.always( onAlways );	
			}
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
			self.$form = $( this );
	
			self.$spinner = self.$form.find( '.spinner' );
			self.$installButton = self.$form.find( '.button' );
			self.$messages = self.$form.find( '.starter-content-messages' );

			/*
			 * The first time we submit the form, ! self.done, we make an attempt to install and activate
			 * the plugins. After that attempt, self.done, we submit the form again.
			 */
			if( self.done ) {

				// If we failed, show some errors. Otherwise, return true and off to the Customizer!
				if( ! self.installPluginsSuccess || ! self.activatePluginsSuccess ) {
					
					// Show the error, hide the spinner, and stop.
					self.$form.find( '.notice-error' ).attr( 'style', 'display:block !important' );
					self.$spinner.hide();
					
					return false;
				} else {
					return true;
				}
			} else {
				
				// Install and activate the plugins.
				e.preventDefault();
				self.installPlugins();
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
		}
	};
	
	self = BOLDGRID.StarterContentPlugins;
	
} ( jQuery ) );

BOLDGRID.StarterContentPlugins.init();
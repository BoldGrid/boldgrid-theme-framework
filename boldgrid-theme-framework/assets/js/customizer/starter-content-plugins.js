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
		
		/**
		 * Success status of our ajax call post plugin installs and activation.
		 *
		 * @since    2.0.0
		 * @type     bool
		 * @memberof BOLDGRID.StarterContentPlugins
		 */
		postPluginsSuccess : false,

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
					_wpnonce : self.i18n.bulkPluginsNonce,
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
					self.showErrors( $errors );
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
				request = $.post( ajaxurl + '?page=bgtfw-install-plugins', data )
					.done( onSuccess )
					.fail( onFail );
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
					_wpnonce : self.i18n.bulkPluginsNonce,
					action : 'tgmpa-bulk-activate',
					plugin : self.i18n.pluginData.to_activate
				},
				onFail,
				onSuccess;

			onFail = function() {
				self.activatePluginsSuccess = false;
				self.done = true;
				self.$form.submit();
			};

			onSuccess = function( response ) {
				response = ( response === undefined ) ? self.i18n.noResponseActivate : response;
				
				var $response = $( '<div>' + response + '</div>' ),
					$errors = $response.find( '.error' );
				
				if( $errors.length > 0 ) {
					self.showErrors( $errors );
					onFail();
				} else {
					self.activatePluginsSuccess = true;
					self.postPluginSetup();
				}
			};

			if( 0 === data.plugin.length ) {
				// At this point, there were no plugins to activate. Go ahead and run post plugin setup.
				onSuccess( 'No plugins to install.' );
			} else {
				// Ajax call to activate necessary plugins (data.plugin).
				request = $.post( ajaxurl + '?page=bgtfw-install-plugins', data )
					.done( onSuccess )
					.fail( onFail );
			}
		},
		
		/**
		 * Post plugin setup.
		 *
		 * Actions to take after all plugins have been installed and activated.
		 *
		 * @since 2.0.0
		 */
		postPluginSetup: function() {
			var request,
				data = {
					action : 'bgtfw-post-plugin-setup',
					_wpnonce : self.i18n.bulkPluginsNonce
				},
				onAlways,
				onSuccess;

			onSuccess = function( response ) {
				response = ( response === undefined || '0' === response ) ? self.i18n.unknownPostError : response;
						
				var $response = $( '<div>' + response + '</div>' ),
					$errors = $response.find( '.error' );

				if( $errors.length > 0 ) {
					self.showErrors( $errors );
				} else {
					self.postPluginsSuccess = true;
				}
			};

			onAlways = function() {
				self.done = true;
				self.$form.submit();
			};

			request = $.post( ajaxurl, data )
				.done( onSuccess )
				.always( onAlways );
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
				if( ! self.installPluginsSuccess || ! self.activatePluginsSuccess || ! self.postPluginsSuccess ) {
					
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
		 * Show errors.
		 *
		 * Due to the nature of the beast, our ajax calls don't get pretty wp_send_json_success / error
		 * responses. Instead, we get raw output. If we've found any elements within the response having
		 * .error as the class, we'll send those elements here to get them displayed.
		 *
		 * @since 2.0.0
		 *
		 * @param jQuery object $errors
		 */
		showErrors: function( $errors ) {
			self.$messages
				.show()
				.find( '.starter-content-error' )
					.empty()
					.append( $errors );
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
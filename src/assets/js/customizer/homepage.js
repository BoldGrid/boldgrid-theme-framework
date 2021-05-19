/**
 * This file handles Misc items for Customizer > Design > Homepage.
 *
 * @summary Homepage settings.
 *
 * @since 2.0.0
 */

/* global jQuery, boldgridFrameworkCustomizerHomepage */

var BOLDGRID = BOLDGRID || {};
BOLDGRID.CustomizerHomepage = BOLDGRID.CustomizerHomepage || {};

( function ( $ ) {

	'use strict';

	var self, bg, api;

	bg = BOLDGRID;
	api = parent.wp.customize;

	/**
	 * Customize the homepage.
	 *
	 * @since 2.0.0
	 */
	BOLDGRID.CustomizerHomepage = {

		i18n: boldgridFrameworkCustomizerHomepage || {},

		$latestPostsContainer: null,

		/**
		 * @summary Add our "Configure posts page" links.
		 *
		 * @since 2.0.0
		 */
		addLinks: function() {
			var $addNewToggle = $( '#sub-accordion-section-static_front_page .add-new-toggle' ),
				$linkConfigurePage = $( '<button type="button" class="button-link bgtfw-homepage-config" style="display:block">' + self.i18n.ConfigurePostsPage + '</a>' ),
				$linkConfigure = $( '<button type="button" class="button-link bgtfw-homepage-config" style="display:block">' + self.i18n.Configure + '</a>' );

			// Remove the + icon from "+ Add New Page" and replace it with a + dashicon.
			$addNewToggle.each( function() {
				var text = $( this ).text();
				text = text.replace( '+', '' );
				$( this )
					.text( text )
					.css( 'font-weight', 'normal' );
			});

			// Give our "Configure Posts Page" a few of the same styles as the existing "Add New Page".
			$linkConfigurePage
				.css( 'text-decoration', $addNewToggle.css( 'text-decoration' ) )
				.css( 'font-weight', $addNewToggle.css( 'font-weight' ) );

			$( '#customize-control-page_for_posts .add-new-toggle' ).after( $linkConfigurePage );
			self.$latestPostsContainer.append( $linkConfigure );

			self.toggleLinks();
		},

		/**
		 * @summary Handle the click of our "Configure posts page" links.
		 *
		 * @since 2.0.0
		 */
		onClickConfigure: function() {
			api.panel( 'bgtfw_blog_blog_page_panel' ).expand();
		},

		/**
		 * @summary Toggle our "Configure posts page" links.
		 *
		 * @since 2.0.0
		 */
		toggleLinks: function() {
			var value = $( '[name="_customize-radio-show_on_front"]:checked' ).val();

			switch( value ) {
				case 'posts':
					self.$latestPostsContainer.find( '.bgtfw-homepage-config' ).show();
					break;
				case 'page':
					self.$latestPostsContainer.find( '.bgtfw-homepage-config' ).hide();
					break;
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
		 * Action to take on document ready.
		 *
		 * @since 2.0.0
		 */
		_onReady: function() {
			$( function() {
				self.$latestPostsContainer = $( '#_customize-input-show_on_front-radio-posts').closest( '.customize-inside-control-row' );

				self.addLinks();

				$( '[name="_customize-radio-show_on_front"]' ).on( 'change', self.toggleLinks );

				$( '.bgtfw-homepage-config' ).on( 'click', self.onClickConfigure );
			});
		}
	};

	self = BOLDGRID.CustomizerHomepage;
} )( jQuery );

BOLDGRID.CustomizerHomepage.init();
/* ========================================================================
 * DOM-based Routing
 * Based on http://www.paulirish.com/2009/markup-based-unobtrusive-comprehensive-dom-ready-execution/
 * by Paul Irish.
 *
 * Only fires on body classes that match. If a body class contains a dash,
 * replace the dash with an underscore when adding it to the object below.
 * ======================================================================== */
( function( $ ) {

	"use strict";

	// Use this variable to set up the common and DOM based specific functionality.
	var BoldGrid = {

		// Scripts to fire on all pages.
		'common': {
			init: function() {
				// JavaScript to be fired on all pages
				this.skipLink();
			},
			finalize: function() {
				// JavaScript to be fired on all pages, after page specific JS is fired
			},
			skipLink: function() {
				var isWebkit  =  navigator.userAgent.toLowerCase(  ).indexOf( 'webkit' ) > -1,
				    isOpera   =  navigator.userAgent.toLowerCase(  ).indexOf( 'opera' )  > -1,
				    isIE      =  navigator.userAgent.toLowerCase(  ).indexOf( 'msie' )   > -1;

				if ( ( isWebkit || isOpera || isIE ) && document.getElementById && window.addEventListener ) {
					window.addEventListener( 'hashchange', function(  ) {
						var id = location.hash.substring( 1 ),
							element;
						if ( ! ( /^[A-z0-9_-]+$/.test( id ) ) ) {
							return;
						}
						element = document.getElementById( id );
						if ( element ) {
							if ( ! ( /^(?:a|select|input|button|textarea)$/i.test( element.tagName ) ) ) {
								element.tabIndex = -1;
							}
							element.focus();
						}
					}, false );
				}
			},
		},

		/**
		 *  Add a sticky footer to the theme, so the footer
		 *  always remains at the bottom of the screen and
		 *  looks nice.
		 *
		 *  If the top of our footer doesn't meet the end of our site's
		 *  content, then we will make the sticky footer do it's thing by
		 *  setting the margin of our wrapper, and give our filler a height.
		 *
		 *  If the footer does meet our content, then we need to remove the
		 *  height from the filler, so it doesn't overflow.
		 */
		'sticky_footer_enabled' : {
			init: function() {
				// JavaScript to be fired on all pages.
				this.stickyFooter();
			},
			finalize: function() {
				// JavaScript to be fired on all pages, after page specific JS is fired.
				$( window ).on( 'resize', this.stickyFooter );
			},
			stickyFooter: function() {
				var footer = $( 'footer#colophon' );
				if ( ! footer.length ) {
					return;
				}

				var admin_bar      =  $( '#wpadminbar' ),
					sticky_wrapper =  $( '#boldgrid-sticky-wrap' ),
					footer_height  =  footer.outerHeight(  ),
					sticky_push    =  $( '#boldgrid-sticky-push' ).height( footer_height ),
					footer_top     =  footer[0].getBoundingClientRect().top,
					content_end    =  $( '.site-content' )[0].getBoundingClientRect().bottom,
					sticky_filler  =  footer_top - content_end;

				// Make sure sticky footer is enabled from configs (configs add the wrapper).
				if ( sticky_wrapper.length ) {
					// Check if the top of footer meets our site content's end.
					if ( !! ( sticky_filler ) ) {
						// Set negative margin to the wrapper's bottom
						sticky_wrapper.css({ 'marginBottom': ~footer_height + 1 + 'px'});
						// Give the filler div a height for the remaining distance inbetween.
						$( '#boldgrid-sticky-filler' ).css({ 'height': sticky_filler - footer_height });
						// If in admin keep WYSIWYG and caluculate adminbar height
						if ( $( '#wpadminbar' ).length ) {
							var admin_bar_height = admin_bar.height(  );
							var admin_translate  = 'translate( 0, -' + admin_bar_height + 'px )';
							// Add 2d transformation to footer to bring bottom links into view
							footer.css({
								'bottom': admin_bar_height + 'px',
							});
						}
					} else {
						// Remove the filler's height
						$( '#boldgrid-sticky-filler' ).removeAttr( 'style' );
					}
				}
			},
		},

		// Parallax enabled pages.
		'boldgrid_customizer_parallax' : {
			init: function() {
				var $body = $( 'body.boldgrid-customizer-parallax' );
				if ( $body.stellar ) {
					$body.attr( 'data-stellar-background-ratio', '0.2' );
					$body.stellar();
				}
			},
		},

		// Default bootstrap menu handling.
		'standard_menu_enabled': {
			init: function() {
				this.dropdowns();
			},
			dropdowns: function() {
				var dropdown    = $( 'ul.nav li.dropdown' ),
					breakpoint  = 768;
				dropdown
					.on( 'mouseover', function( e ) {
						// Set ARIA expanded to true for screen readers
						this.firstChild.setAttribute( 'aria-expanded', 'true' );
						// Add open class
						$( e.currentTarget ).addClass( 'open' );
							// Prevent clicking on the dropdown's parent link
							$( e.currentTarget ).on( 'click', function( e ) {
								// only do this if window is mobile size
								if ( window.innerWidth <= breakpoint ) {
									if ( e.target === this || e.target.parentNode === this ) {
										e.preventDefault(  );
									}
								} else {
									return true;
								}
							} );
						} )
					.on( 'mouseleave', function( e ) {
						// Set ARIA expanded to falsefor screen readers
						this.firstChild.setAttribute( 'aria-expanded', 'false' );
						// Remove all open classes on dropdowns
						dropdown.removeClass( 'open' );
						// If the window is smaller than the 768 bootstrap breakpoint
						if ( window.innerWidth <= breakpoint ) {
							if ( e.target === this || e.target.parentNode === this ) {
								return true;
							}
						}
					} );
				// Check if device support touch events.
				if ( 'ontouchstart' in document.documentElement ) {
					dropdown.each( function(  ) {
						var $this = $( this );
						// Listen for the touch event
						this.addEventListener( 'touchstart', function( e ) {
							if ( e.touches.length === 1 ) {
								// Prevent touch events within dropdown bubbling tp dpcument
								e.stopPropagation(  );
								// Toggle hover
								if ( ! $this.hasClass( 'open' ) ) {
									// Prevent link on first touch
									if ( e.target === this || e.target.parentNode === this ) {
										e.preventDefault(  );
									}
									// Hide other open dropdowns
									dropdown.removeClass( 'open' );
									$this.addClass( 'open' );
									// Hide dropdown on touch outside of dropdown menu
									document.addEventListener( 'touchstart', close_dropdown = function( e ) {
										e.stopPropagation(  );
										$this.removeClass( 'open' );
										document.removeEventListener( 'touchstart', close_dropdown );
									});
								}
							}
						}, false );
					});
				}
			}
		},

		// Offcanvas menu handling.
		'offcanvas_menu_enabled' : {
			init: function() {

			},
		},
		// WOW.js enabled.
		'wow_js_enabled' : {
			init: function(){
				new WOW().init();
			}
		},
	};

	// The routing fires all common scripts, followed by the DOM specific scripts.
	// Additional events can be added for more control over timing.
	var UTIL = {
		fire: function(func, funcname, args) {
			var fire, namespace = BoldGrid;
			funcname = ( funcname === undefined ) ? 'init' : funcname;
			fire = func !== '';
			fire = fire && namespace[func];
			fire = fire && typeof namespace[func][funcname] === 'function';

			if ( fire ) {
				namespace[func][funcname]( args );
			}
		},
		loadEvents: function() {
			// Fire common init JS.
			UTIL.fire( 'common' );

			// Fire page-specific init JS, and then finalize JS.
			$.each( document.body.className.replace( /-/g, '_' ).split( /\s+/ ), function( i, classnm ) {
				UTIL.fire( classnm );
				UTIL.fire( classnm, 'finalize' );
			});

			// Fire common finalize JS.
			UTIL.fire( 'common', 'finalize' );
		}
	};

	// Load Events.
	$( document ).ready( UTIL.loadEvents );

})( jQuery );

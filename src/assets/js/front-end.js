/* ========================================================================
 * DOM-based Routing
 * Based on http://www.paulirish.com/2009/markup-based-unobtrusive-comprehensive-dom-ready-execution/
 * by Paul Irish.
 *
 * Only fires on body classes that match. If a body class contains a dash,
 * replace the dash with an underscore when adding it to the object below.
 * ======================================================================== */
( function( $ ) {

	'use strict';

	// Use this variable to set up the common and DOM based specific functionality.
	var BoldGrid = {

		// Scripts to fire on all pages.
		'common': {

			// JavaScript to be fired on all pages.
			init: function() {
				this.skipLink();
			},

			// JavaScript to be fired on all pages, after page specific JS is fired.
			finalize: function() {},

			// JavaScript for the skip link functionality.
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
			}
		},

		// Default bootstrap menu handling.
		'standard_menu_enabled': {
			init: function() {
				this.dropdowns();
			},
			dropdowns: function() {
				var dropdown    = $( '.no-collapse li.dropdown' ),
					breakpoint  = 768;
				dropdown
					.on( 'mouseover', function( e ) {

						// Set ARIA expanded to true for screen readers.
						this.firstChild.setAttribute( 'aria-expanded', 'true' );

						// Add open class.
						$( e.currentTarget ).addClass( 'open' );

							// Prevent clicking on the dropdown's parent link.
							$( e.currentTarget ).on( 'click', function( e ) {

								// Only do this if window is mobile size.
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

						// Set ARIA expanded to falsefor screen readers.
						this.firstChild.setAttribute( 'aria-expanded', 'false' );

						// Remove all open classes on dropdowns.
						dropdown.removeClass( 'open' );

						// If the window is smaller than the 768 bootstrap breakpoint.
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

						// Listen for the touch event.
						this.addEventListener( 'touchstart', function( e ) {
							if ( 1 === e.touches.length ) {

								// Prevent touch events within dropdown bubbling tp dpcument.
								e.stopPropagation(  );

								// Toggle hover.
								if ( ! $this.hasClass( 'open' ) ) {

									// Prevent link on first touch.
									if ( e.target === this || e.target.parentNode === this ) {
										e.preventDefault(  );
									}

									// Hide other open dropdowns.
									dropdown.removeClass( 'open' );
									$this.addClass( 'open' );

									// Hide dropdown on touch outside of dropdown menu.
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
		'sticky_footer_enabled': {
			init: function() {
				this.flexSupport();
			},
			finalize: function() {
				if ( ! Modernizr.flexbox ) {
					$( window ).on( 'resize', this.stickyFooter );
				}
			},
			flexSupport: function() {
				if ( ! Modernizr.flexbox ) {
					this.stickyFooter();
				}
			},
			stickyFooter: function() {
				var footer = $( '.site-footer' ),
					admin_bar        = $( '#wpadminbar' ),
					sticky_wrapper   = $( '#boldgrid-sticky-wrap' ),
					footer_height    = footer.outerHeight(  ),
					footer_top       = footer[0].getBoundingClientRect().top,
					content_end      = $( '.site-content' )[0].getBoundingClientRect().bottom,
					sticky_filler    = footer_top - content_end,
					admin_bar_height = admin_bar.height();

				if ( ! footer.length ) {
					return;
				}

				// Make sure sticky footer is enabled from configs (configs add the wrapper).
				if ( sticky_wrapper.length ) {

					// Check if the top of footer meets our site content's end.
					if ( !! ( sticky_filler ) ) {

						// Set negative margin to the wrapper's bottom.
						sticky_wrapper.css({ 'marginBottom': ~footer_height + 1 + 'px' });

						// Give the filler div a height for the remaining distance inbetween.
						$( '#boldgrid-sticky-filler' ).css({ 'height': sticky_filler - footer_height });

						// If in admin keep WYSIWYG and caluculate adminbar height.
						if ( $( '#wpadminbar' ).length ) {
							footer.css({
								'bottom': admin_bar_height + 'px'
							});
						}
					} else {

						// Remove the filler's height.
						$( '#boldgrid-sticky-filler' ).removeAttr( 'style' );
					}
				}
			}
		},

		// Parallax enabled pages.
		'boldgrid_customizer_parallax': {
			init: function() {
				var $body = $( 'body.boldgrid-customizer-parallax' );
				if ( $body.stellar ) {
					$body.attr( 'data-stellar-background-ratio', '0.2' );
					$body.stellar();
				}
			}
		},

		// WOW.js enabled.
		'wow_js_enabled': {
			init: function() {

				// Trigger event when WOW is enabled.
				$( document ).trigger( 'wowEnabled' );
				this.loadWow();
			},
			loadWow: function() {
				var wow = new WOW({
					boxClass: _wowJsOptions.boxClass,
					animateClass: _wowJsOptions.animateClass,
					offset: _wowJsOptions.offset,
					mobile: _wowJsOptions.mobile,
					live: _wowJsOptions.live
				});
				wow.init();
			}
		},
		'nicescroll_enabled': {
			init: function() {
				$( _niceScrollOptions.selector ).niceScroll({
					cursorcolor: _niceScrollOptions.cursorcolor,
					cursoropacitymin: _niceScrollOptions.cursoropacitymin,
					cursoropacitymax: _niceScrollOptions.cursoropacitymax,
					cursorwidth: _niceScrollOptions.cursorwidth,
					cursorborder: _niceScrollOptions.cursorborder,
					cursorborderradius: _niceScrollOptions.cursorborderradius,
					zindex: _niceScrollOptions.zindex,
					scrollspeed: _niceScrollOptions.scrollspeed,
					mousescrollstep: _niceScrollOptions.mousescrollstep,
					touchbehavior: _niceScrollOptions.touchbehavior,
					hwacceleration: _niceScrollOptions.hwacceleration,
					boxzoom: _niceScrollOptions.boxzoom,
					dblclickzoom: _niceScrollOptions.dblclickzoom,
					gesturezoom: _niceScrollOptions.gesturezoom,
					grabcursorenabled: _niceScrollOptions.grabcursorenabled,
					autohidemode: _niceScrollOptions.autohidemode,
					background: _niceScrollOptions.background,
					iframeautoresize: _niceScrollOptions.iframeautoresize,
					cursorminheight: _niceScrollOptions.cursorminheight,
					preservenativescrolling: _niceScrollOptions.preservenativescrolling,
					railoffset: _niceScrollOptions.railoffset,
					bouncescroll: _niceScrollOptions.bouncescroll,
					spacebarenabled: _niceScrollOptions.spacebarenabled,
					railpadding: {
						top: _niceScrollOptions.railpadding.top,
						right: _niceScrollOptions.railpadding.right,
						left: _niceScrollOptions.railpadding.left,
						bottom: _niceScrollOptions.railpadding.bottom
					},
					disableoutline: _niceScrollOptions.disableoutline,
					horizrailenabled: _niceScrollOptions.horizrailenabled,
					railalign: _niceScrollOptions.railalign,
					railvalign: _niceScrollOptions.railvalign,
					enabletranslate3d: _niceScrollOptions.enabletranslate3d,
					enablemousewheel: _niceScrollOptions.enablemousewheel,
					enablekeyboard: _niceScrollOptions.enablekeyboard,
					smoothscroll: _niceScrollOptions.smoothscroll,
					sensitiverail: _niceScrollOptions.sensitiverail,
					enablemouselockapi: _niceScrollOptions.enablemouselockapi,
					cursorfixedheight: _niceScrollOptions.cursorfixedheight,
					hidecursordelay: _niceScrollOptions.hidecursordelay,
					directionlockdeadzone: _niceScrollOptions.directionlockdeadzone,
					nativeparentscrolling: _niceScrollOptions.nativeparentscrolling,
					enablescrollonselection: _niceScrollOptions.enablescrollonselection,
					cursordragspeed: _niceScrollOptions.cursordragspeed,
					rtlmode: _niceScrollOptions.rtlmode,
					cursordragontouch: _niceScrollOptions.cursordragontouch,
					oneaxismousemode: _niceScrollOptions.oneaxismousemode,
					scriptpath: _niceScrollOptions.scriptpath,
					preventmultitouchscrolling: _niceScrollOptions.preventmultitouchscrolling,
					disablemutationobserver: _niceScrollOptions.disablemutationobserver
				});
			}
		},
		'goup_enabled': {
			init: function() {
				$.goup({
					location: _goupOptions.location,
					locationOffset: _goupOptions.locationOffset,
					bottomOffset: _goupOptions.bottomOffset,
					containerSize: _goupOptions.containerSize,
					containerRadius: _goupOptions.containerRadius,
					containerClass: _goupOptions.containerClass,
					arrowClass: _goupOptions.arrowClass,
					containerColor: _goupOptions.containerColor,
					arrowColor: _goupOptions.arrowColor,
					trigger: _goupOptions.trigger,
					entryAnimation: _goupOptions.entryAnimation,
					alwaysVisible: _goupOptions.alwaysVisible,
					goupSpeed: _goupOptions.goupSpeed,
					hideUnderWidth: _goupOptions.hideUnderWidth,
					title: _goupOptions.title,
					titleAsText: _goupOptions.titleAsText,
					titleAsTextClass: _goupOptions.titleAsTextClass,
					zIndex: _goupOptions.zIndex
				});
			}
		}
	};

	/*
	 * The routing fires all common scripts, followed by the DOM specific
	 * scripts.  Additional events can be added for more control over timing.
	 */
	var UTIL = {
		fire: function( func, funcname, args ) {
			var fire, namespace = BoldGrid;
			funcname = ( undefined === funcname ) ? 'init' : funcname;
			fire = '' !== func;
			fire = fire && namespace[func];
			fire = fire && 'function' === typeof namespace[func][funcname];

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

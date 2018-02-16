/* ========================================================================
 * DOM-based Routing
 * Based on http://www.paulirish.com/2009/markup-based-unobtrusive-comprehensive-dom-ready-execution/
 * by Paul Irish.
 *
 * Only fires on body classes that match. If a body class contains a dash,
 * replace the dash with an underscore when adding it to the object below.
 * ======================================================================== */

// Setup our object.
var BoldGrid = BoldGrid || {};

( function( $ ) {

	'use strict';

	var UTIL;

	// Use this variable to set up the common and DOM based specific functionality.
	BoldGrid = {

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

		// Header Top.
		'custom_header': {
			init: function() {

				// Check for video background embed type.
				$( document ).on( 'wp-custom-header-video-loaded', this.checkType );

				// Initial calculations.
				this.calc();

				// Listen for resize events to retrigger calculations.
				$( window ).resize( this.calc );
			},

			/**
			 * Performs check of video background type as native video or youtube embed.
			 *
			 * @since 2.0.0
			 *
			 * @return null
			 */
			checkType: function() {
				var timer, body, youtube, nativeVideo;

				timer = setTimeout( function loadVideo() {

					body = $( 'body' );
					youtube = ( ( ( wp || {} ).customHeader || {} ).handlers || {} ).youtube;
					nativeVideo = ( ( ( wp || {} ).customHeader || {} ).handlers || {} ).nativeVideo;

						// jscs:disable requireYodaConditions
						if ( youtube.player == null && nativeVideo.video == null ) {
								timer = setTimeout( loadVideo, 50 );
						} else {
							if ( nativeVideo.video == null && typeof youtube.player.stopVideo === 'function' ) {
								body.addClass( 'has-youtube-header' );
							} else if ( youtube.player == null && $( nativeVideo.video ).length ) {
								body.addClass( 'has-video-header' );
							} else {
								timer = setTimeout( loadVideo, 50 );
							}

						// jscs:enable requireYodaConditions
						}
				}, 50 );
			},

			calc: function() {
				var header_height = Math.abs( $( '#navi' ).outerHeight() - $( '#main-menu' ).height() ),
					screen_width = $( window ).width() + 16;

				$( '.wp-custom-header' ).css( 'height', '' );

				// Desktop.
				if ( screen_width > 768 ) {
					if ( !! $( '.custom-header' ).not( '.header-fixed' ).length ) {

						// Adjusts .header-top position, offsets content based on header content.
						$( '#content' ).css( 'margin-top', '0px' );
						$( '.wp-custom-header' ).css( 'height', header_height + 2 );
					}

				// Mobile.
				} else {
					$( '#content' ).css( 'margin-top', '0px' );
					if ( $( '#main-menu' ).is( ':visible' ) ) {
						header_height = Math.abs( $( '#navi' ).outerHeight() - $( '#main-menu' ).height() );
					}
					$( '.wp-custom-header' ).css( 'height', $( '#navi' ).outerHeight() + 2 );
				}
			}
		},

		// Sticky/Fixed Header.
		'header_fixed': {
			init: function() {
				var video;

				video = document.getElementById( 'wp-custom-header-video' );

				if ( !! video ) {
					if ( 4 === video.readyState ) {
						$( window ).trigger( 'resize' );
					} else {

						// Setup event listener for loadeddata to indicate video has loaded and can be played.
						video.addEventListener( 'loadeddata', function() {
							$( window ).trigger( 'resize' );
						}, false );
					}

				} else {
					$( window ).trigger( 'resize' );
				}

				// Initial calculations.
				this.calc();

				// Listen for resize events to retrigger calculations.
				$( window ).resize( this.calc );
			},

			calc: function() {
				var header_height = Math.abs( $( '#navi' ).outerHeight() - $( '#main-menu' ).height() ),
					screen_width = $( window ).width() + 16;

				if ( !! $( '.custom-header.header-fixed' ).length ) {

					$( '.wp-custom-header' ).css( 'height', '' );

					// Desktop.
					if ( screen_width > 768 ) {

						// Adjusts .header-top position, offsets content based on header content.
						if ( $( '.header-fixed:not(.header-left):not(.header-right)' ).length ) {
							$( '#content' ).css( 'margin-top', header_height + 'px' );
							$( '.wp-custom-header' ).css( 'height', header_height + 2 );

							// Adjusts .header-left and .header-right, remove styling from the .header-top defaults.
						} else {
							$( '#content' ).css( 'margin-top', '0' );
							$( '.wp-custom-header' ).css( 'height', '100vh' );
						}

					// Mobile.
					} else {
						$( '#content' ).css( 'margin-top', '0px' );
						if ( $( '#main-menu' ).is( ':visible' ) ) {
							header_height = Math.abs( $( '#navi' ).outerHeight() - $( '#main-menu' ).height() );
						}
						$( '.wp-custom-header' ).css( 'height', $( '#navi' ).outerHeight() + 2 );
					}
				}

				window.addEventListener( 'scroll', function() {
					var distanceY = window.pageYOffset || document.documentElement.scrollTop,
						shrinkOn = 100,
						header = document.querySelector( 'header' );
					if ( distanceY > shrinkOn ) {
						$( header ).addClass( 'smaller' );
					} else {
						if ( true === $( header ).hasClass( 'smaller' ) ) {
							$( header ).removeClass( 'smaller' );
						}
					}
				} );
			}
		},

		// Default bootstrap menu handling.
		'standard_menu_enabled': {

			// Setup main navigation.
			init: function( sm ) {
				if ( null == sm ) {
					sm = $( '#main-menu' );
				}

				sm.smartmenus({
					mainMenuSubOffsetX: -1,
					mainMenuSubOffsetY: 4,
					subMenusSubOffsetX: 6,
					subMenusSubOffsetY: -6
				});

				// Adds event handling for CSS animated sub menus - toggle animation classes on sub menus show/hide.
				sm.bind({
					'show.smapi': function( e, menu ) {
						$( menu ).removeClass( 'hide-animation' ).addClass( 'show-animation' );
					},
					'hide.smapi': function( e, menu ) {
						$( menu ).removeClass( 'show-animation' ).addClass( 'hide-animation' );
					}
					}).on( 'animationend webkitAnimationEnd oanimationend MSAnimationEnd', 'ul', function( e ) {
						$( this ).removeClass( 'show-animation hide-animation' );
						e.stopPropagation();
				});

				$( window ).on( 'resize', function() {
					var $mainMenuState = $( '#main-menu-state' ),
						screen_width = $( window ).width() + 16;
					if ( screen_width > 768 && $mainMenuState.length ) {
						if ( $mainMenuState[0].checked ) {
							$mainMenuState.attr( 'checked', false );
						}
					}
				});

				$( function() {
					var $mainMenuState = $( '#main-menu-state' );
					if ( $mainMenuState.length ) {

						// Animate mobile menu.
						$mainMenuState.change( function() {
							var $menu = $( '#main-menu' );
							this.checked ? BoldGrid.standard_menu_enabled.collapse( $menu ) : BoldGrid.standard_menu_enabled.expand( $menu );
						});

						// Hide mobile menu beforeunload.
						$( window ).bind( 'beforeunload unload', function() {
							if ( $mainMenuState[0].checked ) {
								$mainMenuState[0].click();
							}
						});
					}
				});
			},

			// Collpase the main navigation.
			collapse: function( $menu ) {
				if ( $menu.length < 1 ) {
					$menu = $( '#main-menu' );
				}
				$menu.hide().slideDown( 250, function() {
					$menu.css( 'display', '' );
				});
			},

			// Expand the main navigation.
			expand: function( $menu ) {
				if ( $menu.length < 1 ) {
					$menu = $( '#main-menu' );
				}
				$menu.show().slideUp( 250, function() {
					$menu.css( 'display', '' );
				});
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
				var wrap, filler, push;

				if ( ! Modernizr.flexbox ) {
					wrap = document.getElementById( 'boldgrid-sticky-wrap' );
					filler = document.createElement( 'DIV' );
					filler.id = 'boldgrid-sticky-filler';
					push = document.createElement( 'DIV' );
					push.id = 'boldgrid-sticky-push';
					wrap.appendChild( filler );
					wrap.appendChild( push );
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

		// Scroll to top button is enabled.
		'goup_enabled': {

			// Initialize.
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
			},

			// Destroy scroll to top buttons.
			destroy: function() {
				$( '.goup-container, .goup-text' ).remove();
			}
		}
	};

	/*
	 * The routing fires all common scripts, followed by the DOM specific
	 * scripts.  Additional events can be added for more control over timing.
	 */
	UTIL = {
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

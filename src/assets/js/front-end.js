/* global Modernizr:false, WOW:false, _wowJsOptions:true, _niceScrollOptions:true, _goupOptions:true, FloatLabels:false, highlightRequiredFields, bgtfwButtonClasses */

/* ========================================================================
 * DOM-based Routing
 * Based on http://www.paulirish.com/2009/markup-based-unobtrusive-comprehensive-dom-ready-execution/
 * by Paul Irish.
 *
 * Only fires on body classes that match. If a body class contains a dash,
 * replace the dash with an underscore when adding it to the object below.
 * ======================================================================== */
import { createHooks } from '@wordpress/hooks';
import cssVars from 'css-vars-ponyfill';
import domReady from '@wordpress/dom-ready';

// Setup our object.
//jscs:disable requireVarDeclFirst
var BoldGrid = BoldGrid || {};
//jscs:enable requireVarDeclFirst

( function( $ ) {

	'use strict';

	var UTIL;

	// Use this variable to set up the common and DOM based specific functionality.
	BoldGrid = {

		// Setup hooks.
		hooks: createHooks(),

		// Scripts to fire on all pages.
		'common': {

			// JavaScript to be fired on all pages.
			init: function() {
				$( ':root' ).removeClass( 'no-bgtfw' ).addClass( 'bgtfw-loading' );
				this.observeBody();
				this.skipLink();
				this.cssVarsPonyfill();
				this.responsiveVideos();
				this.addButtonClasses();
				this.addColLg();

				/*
				 * Disabling this.forms() for now. Leaving code here until 2.16.0. If no issues by then,
				 * we can remove floatLabels all together.
				 *
				 * this.forms();
				 */
			},

			/**
			 * Add col-lg to columns that do not have it.
			 *
			 * @since 2.14.0
			 */
			addColLg() {
				$( 'div[class^="col-"]' ).each( function() {
					var $this = $( this ),
						classes = $this.attr( 'class' ),
						mdSize = classes.match( /col-md-([\d]+)/i ),
						lgSize = classes.match( /col-lg-([\d]+)/i );

					if ( mdSize && ! lgSize ) {
						$this.addClass( `col-lg-${mdSize[1]}` );
					}

				} );
			},

			/**
			 * Add Button Classes.
			 *
			 * This function is triggered when this class Inits, and adds
			 * the custom button classes to all button-primary and button-secondary
			 * button classes.
			 *
			 * @since 2.12.0
			 */
			addButtonClasses: function() {
				var buttonType;

				this.adjustMenuButtons();

				for ( buttonType in bgtfwButtonClasses ) {
					let buttonTypeClass = buttonType.replace( 'bgtfw_', '' );
					let buttonClasses   = bgtfwButtonClasses[ buttonType ];
					this.removeButtonClasses( buttonTypeClass.replace( '_', '-' ) );
					this.addButtonClass( buttonTypeClass.replace( '_', '-' ), buttonClasses );
				}
			},

			/**
			 * Adjust Menu Buttons.
			 *
			 * This moves li.btn classes to the li > a.btn elements
			 */
			adjustMenuButtons: function() {
				$( '.menu-item.btn' ).each( function() {
					var $this = $( this );

					if ( $this.hasClass( 'button-primary' ) ) {
						$this.removeClass( 'button-primary' );
						$this.children( 'a' ).addClass( 'button-primary' );
					}

					if ( $this.hasClass( 'button-secondary' ) ) {
						$this.removeClass( 'button-secondary' );
						$this.children( 'a' ).addClass( 'button-secondary' );
					}

					$this.removeClass( 'btn' );

					$this.addClass( 'item-has-btn' );

					$this.removeClass( function( index, className ) {
						return ( className.match( /(^|\s)btn\S+/g ) || [] ).join( ' ' );
					} );
					$this.children( 'a' ).addClass( 'btn' );
				} );
			},

			/** Remove Button Classes
			 *
			 * Removes button classes that are already present to prevent duplicates.
			 *
			 * @since 2.14.0
			 *
			 * @param {string} buttonTypeClass Primary or Secondary button class.
			 * @param {array}  buttonClasses   Custom classes to add.
			 *
			 */
			removeButtonClasses: function( buttonTypeClass ) {
				$( '.' + buttonTypeClass ).each( function() {
					if ( 'submit' === $( this ).prop( 'type' ) ) {
						return;
					}

					$( this ).removeClass( 'btn' );

					$( this ).removeClass( function( index, className ) {
						return ( className.match( /(^|\s)btn-\S+/g ) || [] ).join( ' ' );
					} );
				} );
			},

			/**
			 * Add Button Class
			 *
			 * Adds the custom button classes to each button.
			 *
			 * @since 2.12.0
			 *
			 * @param {string} buttonTypeClass Primary or Secondary button class.
			 * @param {array}  buttonClasses   Custom classes to add.
			 */
			addButtonClass: function( buttonTypeClass, buttonClasses ) {
				$( '.' + buttonTypeClass ).each( function() {
					if ( 'submit' === $( this ).prop( 'type' ) && ! $( this ).hasClass( 'weforms_submit_btn' ) ) {
						return;
					}

					$( this ).addClass( 'btn' );
					buttonClasses.split( ' ' ).forEach( ( buttonClass ) => {
						if ( ! $( this ).hasClass( buttonClass ) ) {
							$( this ).addClass( buttonClass );
						}
					} );
				} );
			},

			// Handle responsive video iframe embeds.
			responsiveVideos: function() {
				$( window ).on( 'resize', function() {
					var proportion,
						parentWidth;

					// Loop iframe elements.
					document.querySelectorAll( 'iframe' ).forEach( function( iframe ) {

						// Only continue if the iframe has a width & height defined.
						if ( iframe.width && iframe.height ) {

							// Calculate the proportion/ratio based on the width & height.
							proportion = parseFloat( iframe.width ) / parseFloat( iframe.height );

							// Get the parent element's width.
							parentWidth = parseFloat( window.getComputedStyle( iframe.parentElement, null ).width.replace( 'px', '' ) );

							// Set the max-width & height.
							iframe.style.maxWidth = '100%';
							iframe.style.maxHeight = Math.round( parentWidth / proportion ).toString() + 'px';
						}
					} );

				} );
			},

			// Observe classList changes on body element.
			observeBody: function() {
				var observer = new MutationObserver( mutations => {
					let changes = 0;
					mutations.forEach( mutation => 'class' === mutation.attributeName ? changes++ : changes );
					if ( 0 !== changes ) {
						BoldGrid.common.triggerResize();
					}
				} );

				observer.observe( document.body, { attributes: true } );
			},

			// Vanilla trigger resize events.
			triggerResize: function() {
				if ( 'function' === typeof( Event ) ) {
					window.dispatchEvent( new Event( 'resize' ) );
				} else {

					// For IE and other older browser ( causes deprecation warning in modern browsers ).
					let event = window.document.createEvent( 'UIEvents' );
					event.initUIEvent( 'resize', true, false, window, 0 );
					window.dispatchEvent( event );
				}
			},

			// Apply CSS vars ponyfill for legacy browser support.
			cssVarsPonyfill: function() {
				if ( ! Modernizr.customproperties ) {
					cssVars();
				}
			},

			// Add debouncing for frontend.
			debounce: function( func, wait, immediate ) {
				var timeout;
				return function() {
					var context = this,
						args = arguments;

					var later = function() {
						timeout = null;
						if ( ! immediate ) {
							func.apply( context, args );
						}
					};

					var callNow = immediate && ! timeout;
					clearTimeout( timeout );
					timeout = setTimeout( later, wait );
					if ( callNow ) {
						func.apply( context, args );
					}
				};
			},

			// JavaScript to be fired on all pages, after page specific JS is fired.
			finalize: function() {
				$( ':root' ).removeClass( 'bgtfw-loading' ).addClass( 'bgtfw-loaded' );
				$( '#boldgrid-sticky-wrap' ).one( BoldGrid.common.detectAnimationEvent(), function() {
					BoldGrid.custom_header.calc();
				} );
			},

			detectTransitionEvent: function() {
				var i,
					el = document.createElement( 'fakeelement' );

				var transitions = {
					'transition': 'transitionend',
					'OTransition': 'oTransitionEnd',
					'MozTransition': 'transitionend',
					'WebkitTransition': 'webkitTransitionEnd'
				};

				for ( i in transitions ) {
					if ( undefined !== el.style[ i ] ) {
						return transitions[ i ];
					}
				}
			},

			detectAnimationEvent: function() {
				var i, el, animations;

				el = document.createElement( 'fakeelement' );

				animations = {
					'animation': 'animationend',
					'OAnimation': 'oAnimationEnd',
					'MozAnimation': 'animationend',
					'WebkitAnimation': 'webkitAnimationEnd'
				};

				for ( i in animations ) {
					if ( undefined !== el.style[i] ) {
						return animations[i];
					}
				}
			},

			// JavaScript for the skip link functionality.
			skipLink: function() {
				var isWebkit = -1 < navigator.userAgent.toLowerCase().indexOf( 'webkit' ),
					isOpera = -1 < navigator.userAgent.toLowerCase().indexOf( 'opera' ),
					isIE = -1 < navigator.userAgent.toLowerCase().indexOf( 'msie' );

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

			// Handle forms.
			forms: function( hasFloat = false ) {
				var wcCheckoutLabels,
					wcRequiredLabels = [];
				let selectors = '.comment-form-rating #rating, .widget_categories .postform, .quantity .qty, [data-style=wpuf-style]',
					floatLabelsOn = true;

				if ( Array.isArray( window.floatLabelsOn ) && '' === window.floatLabelsOn[0] ) {
					floatLabelsOn = false;
				}

				if ( ! hasFloat && floatLabelsOn ) {
					new FloatLabels(
						'form', {
							prefix: 'bgtfw-',
							style: 2,
							exclude: selectors
						}
					);
				}

				wcCheckoutLabels = $( 'form[name=checkout] .woocommerce-input-wrapper label' );
				if ( 'yes' === highlightRequiredFields ) {
					wcCheckoutLabels.each( function() {
						if ( ! $( this ).html().includes( '(optional)' ) ) {
							wcRequiredLabels.push( this );
						}
					} );

					wcRequiredLabels.forEach( function( requiredLabel ) {
						var placeholder = $( requiredLabel ).parent().find( 'input' ).attr( 'placeholder' );
						if ( ! $.contains( requiredLabel, $( 'abbr' ) ) ) {
							$( requiredLabel ).append( '<abbr class="required" title="required"> *</abbr>' );
							$( requiredLabel ).parent().find( 'input' ).attr( 'placeholder', placeholder + ' *' );
						}
					} );
				}

				/**
				 * Determine the neutral color and return className
				 * that should be applied to native select elements.
				 */
				const getColor = () => {
					var styles = getComputedStyle( document.documentElement );
					var neutralTextContrast = styles.getPropertyValue( '--color-neutral-text-contrast' );
					var darkTextColor = styles.getPropertyValue( '--dark-text' );

					return neutralTextContrast === darkTextColor ? 'light' : 'dark';
				};

				// Adds select element to the excluded form element selectors.
				selectors = selectors.split( ',' ).map( selector => {
					let selects = selector.split( ' ' );
					let select = selects.pop().trim();
					select = ' select' + select;
					selects.push( select );
					return selects.join( '' );
				} ).join( ',' );

				let selects = document.querySelectorAll( selectors );

				// Apply color class to found select elements.
				for ( let i = 0; i < selects.length; i++ ) {
					selects[ i ].classList.remove( 'dark', 'light' );
					selects[ i ].classList.add( getColor() );
				}
			},

			// Side header handling.
			sideHeaderHandler: function() {
				var header;

				header = $( '.site-header' );
				header.on( 'scroll', function() {
					if ( 0 !== header.scrollLeft() ) {
						header.scrollLeft( 0 );
					}
				} );
			}
		},

		// Header Top.
		'custom_header': {
			init: function() {

				// Check for custom header image.
				this.checkImg();

				// Initial calc.
				BoldGrid.custom_header.calc();

				// Listen for resize events to retrigger calculations.
				$( window ).on( 'resize', BoldGrid.common.debounce( this.calc, 250 ) );
			},

			/**
			 * Performs check of video background type as native video or youtube embed.
			 *
			 * @since 2.0.0
			 *
			 * @return null
			 */
			checkType: function() {
				var timer, body, youtube, nativeVideo, video, i;

				i = 0;

				timer = setTimeout( function loadVideo() {
					i++;
					body = document.body.classList;
					youtube = ( ( ( wp || {} ).customHeader || {} ).handlers || {} ).youtube;
					nativeVideo = ( ( ( wp || {} ).customHeader || {} ).handlers || {} ).nativeVideo;

					// ~ 3sec with 50ms delays between load attempts.
					if ( 60 < i ) {
						clearTimeout( timer );
					}

					// jscs:disable requireYodaConditions
					if ( null == youtube.player && null == nativeVideo.video ) {
						timer = setTimeout( loadVideo, 50 );
					} else {

						// YouTube player found and YT handler is loaded.
						if ( null == nativeVideo.video && 'function' === typeof youtube.player.stopVideo ) {
							body.add( 'has-youtube-header' );
							body.remove( 'has-header-image' );
							body.remove( 'has-video-header' );
							BoldGrid.custom_header.calc();

						// HTML5 video player found and native handler is loaded.
						} else if ( null == youtube.player && $( nativeVideo.video ).length ) {
							body.add( 'has-video-header' );
							body.remove( 'has-header-image' );
							body.remove( 'has-youtube-header' );

							video = document.getElementById( 'wp-custom-header-video' );

							if ( video ) {

								// Check  ready state of video player before attempting to reflow layout.
								if ( 4 === video.readyState ) {
									BoldGrid.custom_header.calc();
								} else {

									// Setup event listener for loadeddata to indicate video has loaded and can be played.
									video.addEventListener( 'loadeddata', function() {
										BoldGrid.custom_header.calc();
									}, false );
								}
							}
						} else {
							timer = setTimeout( loadVideo, 50 );
						}

					// jscs:enable requireYodaConditions
					}
				}, 50 );
			},

			checkImg: function() {
				var customHeaders, body;

				body = document.body.classList;
				body.remove( 'has-header-image' );
				body.remove( 'has-video-header' );
				customHeaders = document.querySelectorAll( '.wp-custom-header' );

				customHeaders.forEach( customHeader => {
					if ( customHeader && customHeader.firstChild && 'IMG' === customHeader.firstChild.nodeName ) {
						body.add( 'has-header-image' );
					}
				} );

			},

			calc: function() {
				var classes;

				classes = document.body.classList;

				// Desktop view.
				if ( 768 <= window.innerWidth ) {
					$( '#wp-custom-header-video' ).show();

					// Fixed Headers
					if ( classes.contains( 'header-slide-in' ) ) {

					// Non-fixed headers.
					} else {

						// Do something else.
					}

				// Mobile.
				} else {
					$( '#wp-custom-header-video' ).hide();
					$( '.wp-custom-header-video-button' ).hide();
					if ( classes.contains( 'header-slide-in' ) ) {

						// Destroy instance.
					}
				}

			}
		},

		'header_slide_in': {

			/**
			 * Initialize header-slide-in.
			 */
			init: function( direct = false ) {
				let mq = window.matchMedia( '(min-width: 768px)' ),
					sticky = () => {
						this._scroll();
						window.addEventListener( 'scroll', this._scroll );
					};

				// On DOMLoaded check media query and initialize sticky listener if matched.
				domReady( () => mq.matches && sticky() );

				if ( direct ) {
					mq.matches && sticky();
				}

				mq.addListener( e => {
					if ( e.matches ) {
						sticky();
					} else {
						window.removeEventListener( 'scroll', this._scroll );
						$( '#masthead-sticky' ).parent().removeClass( 'bgtfw-stick' );
					}
				} );
			},

			_scroll: function() {
				let header = document.querySelector( '.bgtfw-header' ),
					distanceY = window.pageYOffset || document.documentElement.scrollTop,
					shrinkOn = header.offsetHeight,
					sticky = $( '.bgtfw-sticky-header' );

				if ( distanceY > shrinkOn ) {
					$( sticky ).addClass( 'bgtfw-stick' );
					$( sticky ).attr( 'aria-hidden', 'false' );
				} else {
					$( sticky ).removeClass( 'bgtfw-stick' );
					$( sticky ).attr( 'aria-hidden', 'true' );
				}
			}
		},

		// Sticky/Fixed Header.
		'header_fixed': {

			init: function() {

				// Setup anchors.
				$( 'a[name]' ).css( {
					'padding-top': $( '.site-header' ).outerHeight( true ) + 'px',
					'margin-top': '-' + $( '.site-header' ).outerHeight( true ) + 'px',
					'display': 'inline-block'
				} );
			},

			calc: function() {
				BoldGrid.custom_header.calc();
			}
		},

		'header_left': {
			init: function() {
				BoldGrid.common.sideHeaderHandler();
			}
		},

		'header_right': {
			init: function() {
				BoldGrid.common.sideHeaderHandler();
			}
		},

		// Default bootstrap menu handling.
		'standard_menu_enabled': {

			init: function( sm ) {
				if ( null != sm ) {
					BoldGrid.standard_menu_enabled.setupMenu( sm );
				} else {
					$.each( $( '.sm' ), function( index, menu ) {
						BoldGrid.standard_menu_enabled.setupMenu( $( menu ) );
					} );
				}
			},

			// Setup main navigation.
			setupMenu: function( sm ) {

				// This has to be done here to ensure that the mega menus are configured before they are initialized.
				$( sm ).find( 'ul.custom-sub-menu' ).each( function() {
					$( this ).find( 'ul' ).addClass( 'in-mega' );
					$( this ).find( 'ul' ).dataSM( 'in-mega', true );
				} );

				sm.smartmenus( {
					mainMenuSubOffsetX: -1,
					mainMenuSubOffsetY: 4,
					subMenusSubOffsetX: 6,
					subMenusSubOffsetY: -6
				} );

				// Adds event handling for CSS animated sub menus - toggle animation classes on sub menus show/hide.
				sm.on( {
					'show.smapi': function( e, menu ) {
						$( menu ).removeClass( 'hide-animation' ).addClass( 'show-animation' );
					},
					'hide.smapi': function( e, menu ) {
						$( menu ).removeClass( 'show-animation' ).addClass( 'hide-animation' );
					}
					} ).on( 'animationend webkitAnimationEnd oanimationend MSAnimationEnd', 'ul', function( e ) {
						BoldGrid.custom_header.calc();
						$( this ).removeClass( 'show-animation hide-animation' );
						e.stopPropagation();
				} );

				$( window ).on( 'resize', BoldGrid.common.debounce( function() {
					var $mainMenuState = sm.siblings( 'input' ),
						screenWidth = $( window ).width() + 16;
					if ( 768 <= screenWidth && $mainMenuState.length ) {
						if ( $mainMenuState[0].checked ) {
							$mainMenuState.prop( 'checked', false ).trigger( 'change' );
						}
					}
				}, 250 ) );

				$( function() {
					var $mainMenuState = sm.siblings( 'input' );
					if ( $mainMenuState.length ) {

						// Animate mobile menu.
						$mainMenuState.on( 'change', function( e ) {
							var $menu = $( e.currentTarget ).siblings( '.sm' );
							this.checked ? BoldGrid.standard_menu_enabled.collapse( $menu ) : BoldGrid.standard_menu_enabled.expand( $menu );
						} );

						// Hide mobile menu beforeunload.
						$( window ).on( 'beforeunload unload', function() {
							if ( $mainMenuState[0].checked ) {
								$mainMenuState[0].click();
							}
						} );
					}
				} );
			},

			// Collpase the main navigation.
			collapse: function( $menu ) {
				if ( 1 > $menu.length ) {
					return;
				}
				$menu.siblings( 'label' ).find( '.hamburger' ).addClass( 'is-active' );
				$menu.hide().slideDown( 220, function() {
					$menu.css( 'display', '' );
				} );
			},

			// Expand the main navigation.
			expand: function( $menu ) {
				if ( 1 > $menu.length ) {
					return;
				}
				$menu.siblings( 'label' ).find( '.hamburger' ).removeClass( 'is-active' );
				$menu.show().slideUp( 220, function() {
					$menu.css( 'display', '' );
				} );
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
					adminBar        = $( document.getElementById( 'wpadminbar' ) ),
					stickyWrapper   = $( '#boldgrid-sticky-wrap' ),
					footerHeight    = footer.outerHeight(  ),
					footerTop       = footer[0].getBoundingClientRect().top,
					contentEnd      = $( '.site-content' )[0].getBoundingClientRect().bottom,
					stickyFiller    = footerTop - contentEnd,
					adminBarHeight = adminBar.height();

				if ( ! footer.length ) {
					return;
				}

				// Make sure sticky footer is enabled from configs (configs add the wrapper).
				if ( stickyWrapper.length ) {

					// Check if the top of footer meets our site content's end.
					if ( stickyFiller ) {

						// Set negative margin to the wrapper's bottom.
						stickyWrapper.css( { 'marginBottom': ~footerHeight + 1 + 'px' } );

						// Give the filler div a height for the remaining distance inbetween.
						$( '#boldgrid-sticky-filler' ).css( { 'height': stickyFiller - footerHeight } );

						// If in admin keep WYSIWYG and caluculate adminbar height.
						if ( $( document.getElementById( 'wpadminbar' ) ).length ) {
							footer.css( {
								'bottom': adminBarHeight + 'px'
							} );
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
			jarallax: null,
			/* jshint ignore:start */
			init: function() {
				( async function() {
					if ( ! BoldGrid.boldgrid_customizer_parallax.jarallax ) {
						BoldGrid.boldgrid_customizer_parallax.jarallax = await import( /* webpackChunkName: "jarallax" */ 'jarallax' );
					}
					BoldGrid.boldgrid_customizer_parallax.jarallax.jarallax( document.body,
						{
							speed: 0.2,
							onInit: BoldGrid.hooks.doAction( 'bgtfwParallaxReady' )
						}
					);
				} )();
			}
			/* jshint ignore:end */
		},

		// WOW.js enabled.
		'wow_js_enabled': {
			init: function() {

				// Trigger event when WOW is enabled.
				$( document ).trigger( 'wowEnabled' );
				this.loadWow();
			},
			loadWow: function() {
				var wow = new WOW( {
					boxClass: _wowJsOptions.boxClass,
					animateClass: _wowJsOptions.animateClass,
					offset: _wowJsOptions.offset,
					mobile: _wowJsOptions.mobile,
					live: _wowJsOptions.live
				} );

				$( function() {
					wow.init();
				} );
			}
		},
		'nicescroll_enabled': {
			init: function() {
				$( _niceScrollOptions.selector ).niceScroll( {
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
				} );
			}
		},

		// Scroll to top button is enabled.
		'goup_enabled': {

			// Initialize.
			init: function() {

				// PHP handles body class set, so this flag is only to handle intial appearance from customize views.
				var scrollEnabled = true;

				if ( 'undefined' !== typeof wp && 'undefined' !== typeof wp.customize && 'undefined' !== wp.customize( 'bgtfw_scroll_to_top_display' ) ) {
					scrollEnabled = 'hide' !== wp.customize( 'bgtfw_scroll_to_top_display' )() ? true : false;
				}

				scrollEnabled && this.setup();
			},

			// Setup scroll to top arrows.
			setup: function() {
				var arrowColor = _goupOptions.arrowColor ? _goupOptions.arrowColor : BoldGrid.goup_enabled.getArrowColor();

				$.goup( {
					location: _goupOptions.location,
					locationOffset: _goupOptions.locationOffset,
					bottomOffset: _goupOptions.bottomOffset,
					containerSize: _goupOptions.containerSize,
					containerRadius: _goupOptions.containerRadius,
					containerClass: _goupOptions.containerClass,
					arrowClass: _goupOptions.arrowClass,
					containerColor: _goupOptions.containerColor,
					arrowColor: arrowColor,
					trigger: _goupOptions.trigger,
					entryAnimation: _goupOptions.entryAnimation,
					alwaysVisible: _goupOptions.alwaysVisible,
					goupSpeed: _goupOptions.goupSpeed,
					hideUnderWidth: _goupOptions.hideUnderWidth,
					title: _goupOptions.title,
					titleAsText: _goupOptions.titleAsText,
					titleAsTextClass: _goupOptions.titleAsTextClass,
					zIndex: _goupOptions.zIndex
				} );
			},

			// Convert color RGB to hex format.
			rgb2hex: function( rgb ) {
				rgb = rgb.match( /^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/ );
				function hex( x ) {
					return ( '0' + parseInt( x ).toString( 16 ) ).slice( -2 );
				}

				return '#' + hex( rgb[1] ) + hex( rgb[2] ) + hex( rgb[3] );
			},

			// Get color of arrow if not specified in configs.
			getArrowColor: function() {
				var color,
					test = document.createElement( 'div' );

				test.className = 'color-1-text-contrast';
				test.id = 'goup-color-test';
				document.body.appendChild( test );
				color = $( test ).css( 'color' );
				$( '#goup-color-test' ).remove();

				return BoldGrid.goup_enabled.rgb2hex( color );
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
			var fire,
				namespace = BoldGrid;

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
			} );

			// Fire common finalize JS.
			UTIL.fire( 'common', 'finalize' );
		}
	};

	// Load Events.
	$( document ).ready( UTIL.loadEvents );

	/*
	 * Check for video background embed type.
	 * This has to be added here, to be sure the event
	 * listener is added at the appropriate
	 * time.
	 */
	$( document ).on( 'wp-custom-header-video-loaded', function() {
		BoldGrid.custom_header.checkType();
	} );

} )( jQuery );
window.BoldGrid = BoldGrid;

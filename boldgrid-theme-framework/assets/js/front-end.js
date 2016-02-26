/**
 *  The file contains the functions that should always be running
 *  inside of the themes
 *  @param $
 */
jQuery( function( $ ) {

	var self = {};

	$( function (  ) {

		parallax(  );
		sticky_footer(  );
		dropdown_handler(  );
		wow_js();

		$( window ).on( 'resize', sticky_footer );

	});
	
	/**
	 *  Init the WOW JS Javascript
	 */
	var wow_js = function () {
		
		if ( BOLDGRID_THEME_FRAMEWORK.wow_js_enabled ) {
			new WOW().init();
		}
		
	};

	/**
	 *  Apply parallax to the body background if the user
	 *  selected this option from the wordpress customizer.
	 */
	var parallax = function(  ) {

		var $body = $( 'body.boldgrid-customizer-parallax' );

		if ( $body.length && $body.stellar ) {

			 $body.attr( 'data-stellar-background-ratio', '0.2' );
			 $body.stellar(  );

		}

	};

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
	var sticky_footer = function(  ) {
		
		var footer = $( 'footer#colophon' );
		if ( !footer.length ) {
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
				
				$( '.site' ).css( 'height', 'auto' );

			} else {

				// Remove the filler's height
				$( '#boldgrid-sticky-filler' ).removeAttr( 'style' );

			}
		}
	};

	/** 
	 *  Events for desktop users who think they
	 *  are using mobile by resizing their window.
	 *  And touch events for the true mobile users.
	 */
	var dropdown_handler = function (  ) {

		var dropdown    = jQuery( 'ul.nav li.dropdown' ),
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

						} else { return true; }

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


		/**
		 * Click to open for touch events
		 */
		// Check if device support touch events, there's probably a better way out there.
		if ( 'ontouchstart' in document.documentElement ) {

			dropdown.each( function(  ) {

				var $this = jQuery( this );

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

	};

});

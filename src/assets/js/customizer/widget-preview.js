var BOLDGRID = BOLDGRID || {};
BOLDGRID.CUSTOMIZER = BOLDGRID.CUSTOMIZER || {};

/**
 * Show the user a preview of the widget area
 * @param $
 */
( function( $ ) {
	'use strict';
	BOLDGRID.CUSTOMIZER.WidgetPreview = {};
	var self = BOLDGRID.CUSTOMIZER.WidgetPreview;
	self.hover_bound = false;
	self.section_click_bound = false;
	$( function() {
		$( window ).on( 'boldgrid_customizer_refresh', onload );
	} );

	onload = function() {
		self.section_selector = create_sections_selector();
		self.$previewer = $( wp.customize.previewer.container ).find( 'iframe' ).last().contents();
		bind_section_hover();
		bind_force_mouse_leave();

		self.$widget_overlay = $( '<div id="boldgrid-widget-area-overlay" class="widget-area-overlay hidden"><h2>Widget Area</h2></div>' );
		if ( ! self.$previewer.find( 'body' ).find( '#boldgrid-widget-area-overlay' ).length ) {
			self.$previewer.find( 'body' ).append( self.$widget_overlay );
		}
		self.$widget_overlay = self.$previewer.find( 'body' ).find( '#boldgrid-widget-area-overlay' );
	};

	var bind_force_mouse_leave = function() {
		if ( true === self.section_click_bound ) {
			return;
		}
		$( self.section_selector ).on( 'click', function() {
			reset_overlay();
			self.$previewer.find( '[data-widget-area][data-empty-area]' ).css({
				'width': '',
				'height': ''
			});
		});

		self.section_click_bound = true;
	};

	var bind_section_hover = function() {
		var mouseenter = function() {

			// Ensure 1 mouse enter for 1 mouseleave.
			self.complete_event = true;
			if ( $( this ).hasClass( 'open' ) ) {
				return;
			}

			var $matching_area = self.$previewer.find( '[data-widget-area="' + this.id + '"]' );
			if ( $matching_area.is( ':visible' ) && $matching_area.length ) {
				if ( $matching_area.attr( 'data-empty-area' ) ) {
					$matching_area.css({
						'width': '100%',
						'height': '50px'
					});

					self.$widget_overlay.find( 'h2' )
						.prepend( '<span class="empty-phrase-heading">Empty <span>' );
					self.$widget_overlay.addClass( 'empty-widget-area' );
				}

				highlight_widget_area( $matching_area );
			}
		};

		var mouseleave = function() {
			if ( false === self.complete_event ) {
				return;
			}

			self.complete_event = false;
			var $matching_area = self.$previewer.find( '[data-widget-area="' + this.id + '"]' );
			if ( $matching_area.is( ':visible' ) && $matching_area.length ) {
				if ( $matching_area.attr( 'data-empty-area' ) ) {
					$matching_area.css({
						'width': '',
						'height': ''
					});
				}
				reset_overlay();
			}
		};

		if ( self.section_selector && false === self.hover_bound ) {
			self.hover_bound = true;
			$( self.section_selector ).hoverIntent( mouseenter, mouseleave );
		}
	};

	var reset_overlay = function() {
		self.$widget_overlay.find( '.empty-phrase-heading' ).remove();
		self.$widget_overlay
			.addClass( 'hidden' )
			.removeClass( 'empty-widget-area' );
	};

	var highlight_widget_area = function( $matching_area ) {
		var position = $matching_area[0].getBoundingClientRect(),
			largest_height = $matching_area.outerHeight( true ),
			largest_width = $matching_area.outerWidth( true ),
			areaOffset;

		$matching_area.find( '*' ).each( function() {
			var $this = $( this );
			var outer_height = $this.outerHeight( true );
			var outer_width = $this.outerWidth( true );
			if ( outer_height > largest_height ) {
				largest_height = outer_height;
			}
			if ( outer_width > largest_width ) {
				largest_width = outer_width;
			}
		});

		self.$widget_overlay.css({
			'width': position.width,
			'height': largest_height,
			'left': $matching_area.offset().left,
			'top': $matching_area.offset().top
		}).removeClass( 'hidden' );

		areaOffset = $matching_area.offset().top - 65;

		if ( wp.customize( 'bgtfw_fixed_header' )() && 'header-top' === wp.customize( 'bgtfw_header_layout_position' )() ) {
			areaOffset -= self.$previewer.find( '.bgtfw-header-stick' ).outerHeight();
		}

		self.$previewer.find( 'html, body' ).stop().animate({
			scrollTop: areaOffset
		}, 750 );
	};

	var create_sections_selector = function() {
		var widgets = wp.customize.panel( 'widgets' );

		if ( 'undefined' === typeof widgets ) {
			return;
		}

		var sections = wp.customize.panel( 'widgets' ).sections();

		var section_selector = '';
		var first = true;
		$.each( sections, function() {
			if ( false === first ) {
				section_selector += ',';
			}

			section_selector += '#accordion-section-' + this.id + ':not(.open)';
			first = false;
		});

		return section_selector;
	};
} )( jQuery );

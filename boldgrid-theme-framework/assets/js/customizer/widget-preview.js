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
		$window.on( 'boldgrid_customizer_refresh',  onload  );
		add_widget_description();
	} );
	
	onload = function () {
		self.section_selector = create_sections_selector();
		self.$previewer = jQuery(wp.customize.previewer.container).find('iframe').last().contents();
		bind_section_hover();
		bind_force_mouse_leave();
		
		self.$widget_overlay = $('<div id="boldgrid-widget-area-overlay" class="widget-area-overlay hidden"><h2>Widget Area</h2></div>');
		self.$previewer
			.find( 'body' )
			.append( self.$widget_overlay );
	};
	
	var bind_force_mouse_leave = function () {
		
		if ( true === self.section_click_bound ) {
			return;
		}
		
		$(self.section_selector).on('click', function () {
			reset_overlay();
			self.$previewer.find('[data-widget-area][data-empty-area]').css({
				'width' : '',
				'height' : '',
			});
		});
		self.section_click_bound = true;
	};
	
	var bind_section_hover = function () {
		
		var mouseenter = function () {
			//Ensure 1 mouse enter for 1 mouseleave
			self.complete_event = true;
			if ($(this).hasClass('open')){
				return;
			}
			
			var $matching_area = self.$previewer.find('[data-widget-area="' + this.id + '"]');
			if ( $matching_area.is(':visible') && $matching_area.length ) {
				if ( $matching_area.attr('data-empty-area') ) {
					$matching_area.css({
						'width' : '100%',
						'height' : '50px',
					});
					
					self.$widget_overlay.find('h2')
						.prepend('<span class="empty-phrase-heading">Empty <span>');
					self.$widget_overlay.addClass('empty-widget-area');
				}
			
				highlight_widget_area( $matching_area );
			}
		};
		
		var mouseleave = function () {
			if ( false === self.complete_event ){
				return;
			}
			
			self.complete_event = false;
			var $matching_area = self.$previewer.find( '[data-widget-area="' + this.id + '"]' );
			if ( $matching_area.is( ':visible' ) && $matching_area.length ) {
				if ( $matching_area.attr( 'data-empty-area' ) ) {
					$matching_area.css({
						'width' : '',
						'height' : '',
					});
				}
				reset_overlay();
			}
		};
		
		if ( self.section_selector && false === self.hover_bound ) {
			self.hover_bound = true;
			$( self.section_selector ).hoverIntent(mouseenter, mouseleave);
		}
	};
	
	var reset_overlay = function () {
		self.$widget_overlay.find('.empty-phrase-heading').remove();
		self.$widget_overlay
			.addClass('hidden')
			.removeClass('empty-widget-area');
	};
	
	var highlight_widget_area = function ( $matching_area ) {
		
		var position = $matching_area[0].getBoundingClientRect();

		var largest_height = $matching_area.outerHeight( true );
		var largest_width = $matching_area.outerWidth( true );
		$matching_area.find('*').each( function () {
			var $this = $(this);
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
			'top': $matching_area.offset().top, 
		}).removeClass('hidden');
		
		self.$previewer.find('html, body').stop().animate({
            scrollTop: $matching_area.offset().top - 65
        }, 750);
	};
	
	var create_sections_selector = function () {
		var sections = wp.customize.panel( 'widgets' ).sections();

		var section_selector = '';
		var first = true;
		$.each( sections, function ( key ) {
			
			if ( false === first ) {
				section_selector += ',';
			} 
				
			section_selector += "#accordion-section-" + this.id + ":not(.open)";
			first = false;
		});

		return section_selector;
	};
	
	var add_widget_description = function () {
		//TODO: Move this markup into php and localize
		$("#accordion-panel-widgets .customize-info.accordion-section")
			.after( '<p class="boldgrid-subdescription">A Widget is a small block that performs'+
				' a specific function. We have provided some prefilled widget areas for you. '+
				'You can hover over the Widget Areas below to see where they are located on the page.</p>');

		// if no header or footer widgets, change wording to add more widgets.
		if ( wp.customize( 'boldgrid_footer_widgets' ).get(  ) &&
			 wp.customize( 'boldgrid_header_widgets' ).get(  ) !== '0' ) {
			$("#accordion-panel-widgets .accordion-sub-container")
				.append( '<p class="boldgrid-subdescription bottom-description">To change the number of columns in your header or footer, use the following buttons. </p>')
				.append( '<div class="boldgrid-subdescription"><button  type="button" data-focus-control="boldgrid_header_widgets" class="button">Header Columns</button><button class="button" type="button" data-focus-control="boldgrid_footer_widgets">Footer Columns</button><div>')
				.append( '<div class="boldgrid-subdescription edit-in-admin"><a href="' + Boldgrid_Thememod_Markup.siteurl + '/wp-admin/widgets.php" type="button" class="button">Edit in Admin</a><div>');

		} else {

			$("#accordion-panel-widgets .accordion-sub-container")
				.append( '<p class="boldgrid-subdescription bottom-description">You can add more widget areas in your header or footer, just use the following buttons: </p>')
				.append( '<div class="boldgrid-subdescription"><button  type="button" data-focus-control="boldgrid_header_widgets" class="button">Header Widgets</button><button class="button" type="button" data-focus-control="boldgrid_footer_widgets">Footer Widgets</button><div>')
				.append( '<div class="boldgrid-subdescription edit-in-admin"><a href="' + Boldgrid_Thememod_Markup.siteurl + '/wp-admin/widgets.php" type="button" class="button">Edit in Admin</a><div>');

		}
	
		$("#accordion-panel-nav_menus .accordion-sub-container")
			.append( '<div class="boldgrid-subdescription edit-in-admin"><a href="' + Boldgrid_Thememod_Markup.siteurl + '/wp-admin/nav-menus.php" type="button" class="button">Edit in Admin</a><div>');

	};
	
})(jQuery);

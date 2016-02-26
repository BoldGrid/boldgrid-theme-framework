/**
 * We know that this event is triggered by wordpress but we can't get it to work
 * This is a temp solution.
 * 
 * @param $
 */
(function( $ ) {
	var refresh_event = $.Event( 'boldgrid_customizer_refresh' );
	var help_overlay_bound = null;
	$window = $( window );
	
	$window.on( 'message', function( e ) {

		var event = e.originalEvent;

		// Ensure we have a string that's JSON.parse-able
		if ( typeof event.data !== 'string' || event.data[ 0 ] !== '{' ) {
			return;
		}

		var message = JSON.parse( event.data );
		if ( message.id == 'synced' ) {
			$window.trigger( refresh_event, message );
			setup_customizer_diagram();
			
		} 
	} );

	$(function () {
		$(document).on('click', '.open-widgets-section', function () {
			wp.customize.panel( 'widgets' ).focus();
		});
		$(document).on('click', '[data-focus-control]', function () {
			var control = $(this).data('focus-control');
			var customizer_control = wp.customize.control(control);
			if ( customizer_control ) {
				customizer_control.focus();
			}
		}); 
		$(document).on('click', '[data-focus-section]', function () {
			var control = $(this).data('focus-section');
			var customizer_control = wp.customize.section( control );
			if ( customizer_control ) {
				customizer_control.focus();
			}
		}); 
		
		//Add the markup and display the warning if needed
		setup_transferred_theme_mod_warning();
		
		$('#accept-theme-mod-changes').on('click', function () {
			var success = function () {
				
			};
			
			ajax_reset_theme_mods( true, success );
		});
		$('#undo-theme-mod-changes').on('click', function () {
			var success = function () {
				location.reload();
			};
			
			ajax_reset_theme_mods( false, success );
		});

	});
	
	var setup_customizer_diagram = function () {
		if ( !help_overlay_bound ) {
			help_overlay_bound = true;
		} else {
			$('#customize-theme-controls').append($('#accordion-section-boldgrid_customizer_help_panel'));
			return;
		}
		setTimeout( function () {
			   $('#customize-theme-controls')
			   		.append('<li id="accordion-section-boldgrid_customizer_help_panel"'+
					' class="accordion-section control-section" style="">'+
					'<h3 class="accordion-section-title" tabindex="0">Help</h3></li>');
			
			var $help_panel = $('#accordion-section-boldgrid_customizer_help_panel');
			
			var set_highlighting = function ( toggle ) {
				var $overlay_help = wp.customize.previewer.container.find('iframe')
				.contents().find('.overlay-help');

				if ( toggle ) {
					if ( $overlay_help.is(':visible') ) {
						$help_panel.removeClass('active');
					} else {
						$help_panel.addClass('active');
					}
					
					//Fade out modal
					$overlay_help.fadeToggle();
				} else {
					if ( $overlay_help.is(':visible') ) {
						$help_panel.addClass('active');
					} else {
						$help_panel.removeClass('active');
					}
				}
				
			};
			
			set_highlighting();
			$help_panel.on('click', function () {
				set_highlighting(true);
			});
		
		}, 100);
	};
	
	var ajax_reset_theme_mods = function ( accept, success_cb ) {
		var always = function () {
			$('.overlay-prompt').remove();
		};
		
		//Disable All button during ajax
		$('.overlay-prompt .button').attr('disabled', 'disabled');
		$('.overlay-prompt .spinner').addClass('is-active');
		
		$.post(
		    ajaxurl, 
		    {
		        'action': 'boldgrid_reset_theme_mods',
		        'data':   {'accept' : accept}
		    }
		).done( success_cb ).always( always );
	};
	
	
	var setup_transferred_theme_mod_warning = function () {
		if ( Boldgrid_Thememod_Markup.transferred_theme_mods.length ) {
			$("#customize-theme-controls").append( Boldgrid_Thememod_Markup.html );		
		}
	};
	
})( jQuery );
/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {

		var $body = $( 'body' );
		var $custom_styles = $( '#boldgrid-override-styles' );

		$( function() {
			init_values();
			attribution_links();
			setup_post_edit_link();
			$( '.site-description' ).addClass( _typographyClasses );
		});

		// Site title and description.
		wp.customize( 'blogname', function( value ) {

			// If logo isn't set then bind site-title for live update, otherwise let .site-title update with logo live.
			if ( parent.wp.customize( 'boldgrid_logo_setting' ) && ! parent.wp.customize( 'boldgrid_logo_setting' ).get() ) {
				value.bind( function( to ) {
					$( '.site-title a' ).text( to );
				} );
			}
		} );

		wp.customize( 'blogdescription', function( value ) {
			value.bind( function( to ) {
				if ( to ) {
					$( '.site-description' ).text( to ).removeClass( 'invisible' );
				} else {
					$( '.site-description' ).text( '' ).addClass( 'invisible' );
				}
			} );
		} );

		wp.customize( 'custom_theme_css', function( value ) {
			value.bind( function( to ) {
				$( '#boldgrid-custom-css' ).html( to );
			} );
		} );

		/**
		 * Set vertical position of image
		 */
		wp.customize( 'boldgrid_background_vertical_position', function( value ) {
			value.bind( set_background_vertical_position );
		} );

		/**
		 * Set horizontal position of image
		 */
		wp.customize( 'boldgrid_background_horizontal_position', function( value ) {
			value.bind( set_background_horizontal_position );
		} );

		/**
		 * Add a background pattern
		 */
		wp.customize( 'boldgrid_background_pattern', function( value ) {
			value.bind( update_color_and_patterns );
		} );

		/**
		 * Set body background and remove image
		 */
		wp.customize( 'boldgrid_background_color', function( value ) {
			value.bind( update_color_and_patterns );
		} );

		/**
		 * When updating background type reset all saved values
		 */
		wp.customize( 'boldgrid_background_type', function( value ) {
			value.bind( background_type_update );
		} );

		wp.customize( 'background_attachment', function( value ) {
			value.bind( background_attachment_update );
		} );

		wp.customize( 'background_image', function( value ) {
			value.bind( background_image_update );
		} );

		wp.customize( 'background_repeat', function( value ) {
			value.bind( background_repeat_update );
		} );

		/**
		 * When updating background type reset all saved values
		 */
		wp.customize( 'boldgrid_background_image_size', function( value ) {
			value.bind( background_size_update );
		} );

		/**
		 * Allow the user to click the post edit link in the customizer and go to the editor
		 */
		var setup_post_edit_link = function() {
			$( '.post-edit-link' ).on( 'click', function() {
				parent.location = $( this ).attr( 'href' );
			});
		};

		var background_type_update = function( to ) {

				if ( to === 'pattern' ) {
					update_color_and_patterns();
				} else {
					$body.css( {
						'background-image': '',
						'background-size': '',
						'background-repeat': '',
						'background-attachment': ''
					} );

					init_values();
				}

				// Remove these styles that should only overwrite on the front end.
				$custom_styles.remove();
		};

		var background_size_update = function( to ) {
			$body.css( 'background-size', to );
		};

		var background_attachment_update = function( to ) {
			if ( to === 'parallax' ) {
				$body.addClass( 'boldgrid-customizer-parallax-effect' );
				$body.css( 'background-attachment', 'fixed' );

				$body.css( {
					'background-position': '0px 0px',
					'background-attachment': 'fixed'
				} );

				$body.data( 'stellar-background-ratio', '0.2' );
				$body.stellar();

				if ( $body.data( 'plugin_stellar' ) ) {
					$body.data( 'plugin_stellar' ).init();
				}
			} else {
				var plugin_stellar_data = $( 'body' ).data( 'plugin_stellar' );
				if ( plugin_stellar_data ) {
					plugin_stellar_data.destroy();
				}

				background_size_update( wp.customize( 'boldgrid_background_image_size' )() );

				$body.css( {
					'background-attachment': to
				});
				set_background_vertical_position();
				set_background_horizontal_position();
				background_repeat_update();

				$body.removeClass( 'boldgrid-customizer-parallax-effect' );
			}
		};

		/**
		 * Set the theme background_vertical_position on the preview frame
		 */
		var set_background_vertical_position = function() {
			var to = wp.customize( 'boldgrid_background_vertical_position' )();
			var cur_background_pos = $body.css( 'background-position' );
			var background_pos = cur_background_pos.split( ' ' )[0] + ' ' + ( to ) * 5 + 'px';
			$body.css( 'background-position', background_pos );
		};

		/**
		 * Set the theme background_horizontal_position on the preview frame
		 */
		var set_background_horizontal_position = function() {
			var to = wp.customize( 'boldgrid_background_horizontal_position' )();
			var cur_background_pos = $body.css( 'background-position' );
			var background_pos =  ( to ) * 5 + 'px' + ' ' +  cur_background_pos.split( ' ' )[1];
			$body.css( 'background-position', background_pos );
		};

		/**
		 * Set the theme update_color_and_patterns on the preview frame
		 */
		var update_color_and_patterns = function() {
			var background_color = wp.customize( 'boldgrid_background_color' )();
			var background_pattern = wp.customize( 'boldgrid_background_pattern' )();

			if ( ! background_color || background_color === 'none' ) {
				background_color = '';
			}

			if ( ! background_pattern ) {
				background_pattern = 'none';
			}

			$custom_styles.remove();
			$body.css( {
				'background-image': background_pattern,
				'background-size': 'auto',
				'background-repeat': 'repeat',
				'background-attachment': 'scroll',
				'background-color': background_color
			});
		};

		var background_image_update = function( to ) {
			if ( ! to ) {
				to = '';
			} else {
				to = 'url(' + to + ')';
			}

			$body.css( {
				'background-image': to
			});
		};

		var background_repeat_update = function( ) {
			$body.css( {
				'background-repeat': wp.customize( 'background_repeat' )()
			});
		};

		var init_values = function() {
			$( '#custom-background-css' ).remove();
			var bg_attach = wp.customize( 'background_attachment' )(),
				bg_img_size = wp.customize( 'boldgrid_background_image_size' )(),
				bg_type = wp.customize( 'boldgrid_background_type' )();

			update_color_and_patterns();
			if ( bg_type !== 'pattern' ) {
				background_attachment_update( bg_attach );
				background_size_update( bg_img_size );
				var bg_image = wp.customize( 'background_image' )();
				background_image_update( bg_image );
				if ( bg_attach !== 'parallax' ) {
					set_background_vertical_position();
					set_background_horizontal_position();
					background_repeat_update();
				}
			}

		};

		var attribution_links = function() {
			var controls;
			wp.customize.bind( 'ready', _.defer( function() {
				if ( _.isFunction( parent.wp.customize.section ) ) {
					controls = parent.wp.customize.section( 'boldgrid_footer_panel' ).controls();
					_( controls ).each( function( control ) {
						var selector, regex = new RegExp( /^(hide_)+\w*(_attribution)+$/, 'm' );
						if ( regex.test( control.id ) ) {
							if ( !! parseInt( wp.customize( control.id )() ) ) {
								selector = '.' + control.id.replace( 'hide_', '' ).replace( /_/g, '-' ) + '-link';
								$( selector ).addClass( 'hidden' );
							}
						}
					} );
				}
				attributionSeparators();
			} ) );
		};

		/**
		 * Responsible for adjusting the separators in live preview for attribution footer links.
		 */
		var attributionSeparators = function() {
			$( '.attribution-theme-mods > .link' )
				.removeClass( 'no-separator' )
				.filter( ':visible' )
				.last()
				.addClass( 'no-separator' );
		};

		if ( _.isFunction( parent.wp.customize.section ) ) {
			var attributionControls = parent.wp.customize.section( 'boldgrid_footer_panel' ).controls();
			_( attributionControls ).each( function( control ) {
				var selector, regex = new RegExp( /^(hide_)+\w*(_attribution)+$/, 'm' );
				if ( regex.test( control.id ) ) {
					wp.customize( control.id, function( value ) {
						selector = '.' + control.id.replace( /hide_/, '' ).replace( /_/g, '-' ) + '-link';
						value.bind( function( to ) {
							if ( ! to ) {
								$( selector ).removeClass( 'hidden' );
							} else {
								$( selector ).addClass( 'hidden' );
							}
							attributionSeparators();
						});
					});
				}
			});
		}
	} )( jQuery );

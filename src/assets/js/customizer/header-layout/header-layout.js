const controlApi = parent.wp.customize;

parent.window.BOLDGRID.colWidthSliders = parent.window.BOLDGRID.colWidthSliders ?
			parent.window.BOLDGRID.colWidthSliders :
			{};

const colWidthSliders = parent.window.BOLDGRID.colWidthSliders;

export class HeaderLayout  {

	/**
	 * Class initialized.
	 *
	 * @since 2.0.0
	 *
	 * @return {Preview} Class instance.
	*/
	init() {


		this.changeColumnDevice();

		this.bindExpanding();

		this.bindControlChanges();

		this.bindResizeEvents();

		this.bindFullWidth();

		wp.customize.bind( 'preview-ready', () => {
			this.correctMenuLocations();
			wp.customize.preview.bind( 'sendHeaderLayout', ( args ) => {
				this.renderHeaderPreview( ...args );
			} );
		} );

		parent.window.BOLDGRID.colWidths = parent.window.BOLDGRID.colWidths ?
			parent.window.BOLDGRID.colWidths :
			this;

		// window.BOLDGRID.colWidths = window.BOLDGRID.colWidths ?
		// 	window.BOLDGRID.colWidths :
		// 	this;
	}

	correctMenuLocations() {
		var footerSocialMenu = controlApi( 'nav_menu_locations[footer-social]' )(),
			mainMenu         = controlApi( 'nav_menu_locations[main]' )(),
			socialMenu       = controlApi( 'nav_menu_locations[social]' )(),
			footerLayout     = controlApi( 'bgtfw_footer_layout' )(),
			stickySocialMenu,
			stickyMainMenu;

			if ( window._.isFunction( controlApi( 'nav_menu_locations[sticky-social]' ) ) ) {
				stickySocialMenu = controlApi( 'nav_menu_locations[sticky-social]' )();
			}

			if ( window._.isFunction( controlApi( 'nav_menu_locations[sticky-main]' ) ) ) {
				stickyMainMenu = controlApi( 'nav_menu_locations[sticky-main]' )();
			}

		if ( 0 === footerSocialMenu ) {
			controlApi.control( 'nav_menu_locations[footer-social]' ).setting.set( socialMenu );
		}

		if ( 0 === stickyMainMenu || 0 === stickySocialMenu ) {
			controlApi.control( 'nav_menu_locations[sticky-main]' ).setting.set( mainMenu );
			controlApi.control( 'nav_menu_locations[sticky-social]' ).setting.set( socialMenu );
		}

		footerLayout.forEach( ( layoutCol ) => {
			layoutCol.items.forEach( ( item ) => {
				if ( 'boldgrid_menu_social' === item.type ) {
					item.type = 'boldgrid_menu_footer-social';
				}
			} );
		} );
	}

	bindFullWidth() {
		controlApi.control( 'bgtfw_header_layout_custom_col_width' ).container
			.find( '.col-width-full-width' ).on( 'click', ( event ) => {
				var deviceClass = this.getDeviceClass( $( event.currentTarget ).data( 'device' ) );
				if ( $( event.currentTarget ).prop( 'checked' ) ) {
					$( event.currentTarget ).parent().addClass( 'disabled' );
				} else {
					$( event.currentTarget ).parent().removeClass( 'disabled' );
				}

				this.updateControlValue();

				$( event.currentTarget ).parent().siblings( '.col-width-slider' ).data( 'items' ).forEach( ( item ) => {
					$( '.' + item.uid ).toggleClass( deviceClass + 'full-width' );
				} );
			} );
	}

	bindResizeEvents() {
		controlApi.bind( 'preview-ready', () => {
			$( window ).on( 'resize', _.debounce( () => {
				var $container = controlApi.control( 'bgtfw_header_layout_custom_col_width' ).container;
				$container.find( '.col-width-slider' ).children().remove();
				this.initialColumnSliders();
			}, 300 ) );
		} );
	}

	changeColumnDevice() {
		var $container   = controlApi.control( 'bgtfw_header_layout_custom_col_width' ).container,
			$deviceLabel = $container.find( '.devices-wrapper label' );

		$deviceLabel.on( 'click', ( e ) => {
			var $thisLabel      = $( e.currentTarget ),
				$thisInputValue = $thisLabel.siblings( 'input' ).val();
			$container.find( '.col-width-slider-device-group' ).height( 0 );
			$container.find( '#bgtfw_header_layout_custom_col_width-slider-' + $thisInputValue ).height( 81 );

			if ( 'phone' === $thisInputValue ) {
				$container.closest( 'body' ).find( 'button.preview-mobile' ).not( '.active' ).trigger( 'click' );
			} else {
				$container.closest( 'body' ).find( 'button.preview-' + $thisInputValue ).not( '.active' ).trigger( 'click' );
			}
		} );
	}

	/**
	 * Update Control.
	 *
	 * @since SINCEVERSION
	 */
	updateSliderControl( value ) {
		$.ajax(
			wp.ajax.settings.url,
			{
				type: 'POST',
				context: this,
				data: {
					action: 'bgtfw_header_columns',
					headerColumnsNonce: controlApi.settings.nonce['bgtfw-header-columns'],
					wpCustomize: 'on',
					customizeTheme: controlApi.settings.theme.stylesheet,
					customHeaderLayout: value
				}
			}
		).done(
			( response ) => {
				var container = controlApi.control( 'bgtfw_header_layout_custom_col_width' ).container;
				container.find( '.col-width-slider-device-group' ).remove();
				container.find( '.sliders-wrapper' ).html( response.data.markup );
				this.initialColumnSliders( true );
				this.updateControlValue();

				container.css( 'opacity', 1 );
			}
		);
	}

	/**
	 * Initial Column Sliders.
	 *
	 * @since SINCEVERSION
	 */
	initialColumnSliders( forceDefaults = false ) {
		var $container   = controlApi.control( 'bgtfw_header_layout_custom_col_width' ).container,
				$sliders = $container.find( '.col-width-slider' ),
				value   = controlApi( 'bgtfw_header_layout_custom_col_width' )();

		$sliders.each( ( _, sliderElement ) => {
			var items = $( sliderElement ).data( 'items' ),
				sliderValues = [];

			items.forEach( ( item, index ) => {
				var width = parseInt( item.width );

				if ( ! forceDefaults && value && value[ item.uid ] && value[ item.uid ][ item.device ] ) {
					width = parseInt( value[ item.uid ] && value[ item.uid ][ item.device ] );
				}

				if ( 0 === index ) {
					sliderValues.push( 0, width );
				} else {
					let startValue = sliderValues[ sliderValues.length - 1 ];
					let endValue   = startValue + width;
					sliderValues.push( startValue, endValue );
				}

				if ( parent ) {
					parent.window.BOLDGRID.colWidthSliders[ item.uid ] = {
						row: sliderElement.dataset.row,
						col: index,
						key: item.key
					};
				} else {
					colWidthSliders[ item.uid ] = {
						row: sliderElement.dataset.row,
						col: index,
						key: item.key
					};
				}
			} );

			let slider = $container.find( sliderElement ).multiSlider( {
				min: 0,
				max: 12,
				step: 1,
				total: items.length,
				values: sliderValues,
				stop: this.bindSliderChanges
			} );


			let $slider = $container.find( slider.element );
			$slider.find( '.ui-slider-range' ).each( ( sliderIndex, sliderRange ) => {
				var uid = items[ sliderIndex ].uid,
					device = items[ sliderIndex ].device;

				let disabled = $slider.siblings( '.full-width-wrapper' ).children( 'input' ).attr( 'checked' ) ?
					true : false;

				if ( disabled ) {
					$slider.siblings( '.full-width-wrapper' ).children( 'input' ).parent().addClass( 'disabled' );
				} else {
					$slider.siblings( '.full-width-wrapper' ).children( 'input' ).parent().removeClass( 'disabled' );
				}

				$container.find( sliderRange ).parent().attr( {
					'data-uid': uid,
					'data-device': device
				} );

				$container.find( sliderRange ).html( '<span class="col-width-range-label">' + items[sliderIndex].key + '</span>' );
				if ( parent ) {
					parent.window.BOLDGRID.colWidthSliders[ uid ][ device ] = slider;
				} else {
					colWidthSliders[ uid ][ device ] = slider;
				}
			} );

			let device = controlApi.control( 'bgtfw_header_layout_custom_col_width' ).container.find( '.devices-wrapper input:checked' ).val();
				controlApi.control( 'bgtfw_header_layout_custom_col_width' ).container
					.find( '.col-width-slider-device-group' ).not( '#bgtfw_header_layout_custom_col_width-slider-' + device )
					.height( 0 );
		} );
	}

	getDeviceClass( deviceSize ) {
		var deviceClass = 'col-lg-';
			switch ( deviceSize ) {
				case 'large':
					deviceClass = 'col-lg-';
					break;
				case 'desktop':
					deviceClass = 'col-md-';
					break;
				case 'tablet':
					deviceClass = 'col-sm-';
					break;
				case 'phone':
					deviceClass = 'col-xs-';
					break;
			}

		return deviceClass;
	}

	/**
	 * Update Control Value
	 *
	 * @since SINCEVERSION
	 */
	updateControlValue() {
		var sliderObjects = colWidthSliders,
			valueObject   = {},
			$fullWidthControls = controlApi.control( 'bgtfw_header_layout_custom_col_width' ).container.find( '.col-width-full-width' );

		for ( const uid in sliderObjects ) {
			valueObject[ uid ] = {};
			for ( const device in sliderObjects[ uid ] ) {
				if ( 'col' === device || 'row' === device || 'key' === device ) {
					continue;
				}

				let values = sliderObjects[ uid ][ device ].option( 'values' );
				let col    = sliderObjects[ uid ].col;
				let end    = ( ( col + 1 ) * 2 ) - 1;
				let start  = end - 1;

				valueObject[ uid ][device] = values[ end ] - values[ start ];
			}
		}

		valueObject.fullWidth = [];

		$fullWidthControls.each( ( _, fullWidthControl ) => {
			var $control = controlApi.control( 'bgtfw_header_layout_custom_col_width' ).container.find( fullWidthControl ),
				device = $control.data( 'device' ),
				checked = $control.attr( 'checked' ) ? true : false,
				row = parseInt( $control.data( 'row' ) );

				if ( ! valueObject.fullWidth[ row ] ) {
					valueObject.fullWidth[ row ] = {};
				}

				valueObject.fullWidth[ row ][ device ] = checked;
		} );

		controlApi.control( 'bgtfw_header_layout_custom_col_width' ).setting( valueObject );
	}

	/**
	 * Bind Slider Changes.
	 *
	 * @since SINCEVERSION
	 */
	bindSliderChanges( event, ui ) {
		var newValue    = parseInt( ui.values[1] ) - parseInt( ui.values[0] ),
			uid         = event.target.dataset.uid,
			device      = event.target.dataset.device,
			deviceClass = parent.window.BOLDGRID.colWidths.getDeviceClass( device );

		$( '.' + uid )[0].classList.forEach( ( className ) => {
			if ( className.includes( deviceClass ) ) {
				$( '.' + uid ).removeClass( className );
				return;
			}
		} );

		$( '.' + uid ).addClass( deviceClass + newValue );

		parent.window.BOLDGRID.colWidths.updateControlValue();
	}

	/**
	 * Bind Expanding.
	 *
	 * Bind Events to expanding panels and sections.
	 *
	 * @since SINCEVERSION
	 */
	bindExpanding() {
		var customLayoutSection = controlApi.section(
			controlApi.control( 'bgtfw_header_layout_custom' ).section()
		);

		controlApi.panel( 'bgtfw_header_layouts' ).expanded.bind(  () => {
			if ( 'custom' === controlApi( 'bgtfw_header_preset' )() ) {
				controlApi.section( 'bgtfw_header_layout_advanced' ).activate();
			} else if ( 'custom' !== controlApi( 'bgtfw_header_preset' )() ) {
				controlApi.section( 'bgtfw_header_layout_advanced' ).deactivate();
			}
		} );

		controlApi.section( 'bgtfw_header_presets' ).expanded.bind( () => {
			this.brandingNotices( controlApi( 'bgtfw_header_preset_branding' )(), controlApi.control( 'bgtfw_header_preset_branding' ) );
			controlApi.control( 'bgtfw_header_preset' ).container.find( '.bgtfw_header_presetcustom' ).on( 'click', () => {
				controlApi.section( 'bgtfw_header_layout_advanced' ).activate();
				controlApi.control( 'bgtfw_header_layout_custom' ).activate();
				controlApi.control( 'bgtfw_header_layout_position' ).focus();
			} );
		} );

		controlApi.section( 'bgtfw_header_layout' ).expanded.bind( ( isExpanded ) => {
			if ( isExpanded ) {
				let colWidths,
				colUids,
				themeMod = controlApi( 'bgtfw_header_layout_col_width' )().media;
				colWidths = 'string' === typeof themeMod ? JSON.parse( themeMod ) : themeMod ;
				colUids = Object.keys( colWidths.large.values );

				// If this page uses Header Templates, do not mess with them.
				if ( $( '.template-header' ).length ) {
					return;
				}

				// Added #masthead to the selector to ensure that Page Header Template rows are not selected.
				$( '.bgtfw-header #masthead .boldgrid-section .row > div' ).each( ( itemIndex, itemElement ) => {
					let uid = colUids[ itemIndex ],
						classList;

					// If the different values are not set, then use the baseWidths value ( which is from the 'large' device ).
					classList = [
						'col-lg-' + ( colWidths.large.values[uid] ? colWidths.large.values[ uid ] : 6 ),
						'col-md-' + ( colWidths.desktop.values[uid] ? colWidths.desktop.values[ uid ] : 6 ),
						'col-sm-' + ( colWidths.tablet.values[uid] ? colWidths.tablet.values[ uid ] : 12 ),
						'col-xs-' + ( colWidths.phone.values[uid] ? colWidths.phone.values[ uid ] : 12 )
					];

					uid = $( itemElement ).hasClass( 'h00' ) ? 'h00' : uid;
					uid = $( itemElement ).hasClass( 'h01' ) ? 'h01' : uid;

					// This removes the empty column widths.
					$( itemElement ).removeClass();

					$( itemElement ).addClass( classList.join( ' ' ) );

					$( itemElement ).addClass( uid );
				} );
			}
		} );

		customLayoutSection.expanded.bind( () => {
			this.initialColumnSliders();
			if ( 'custom' === controlApi( 'bgtfw_header_preset' )() ) {
				controlApi.control( 'bgtfw_header_layout_custom' ).activate();
			} else {
				controlApi.control( 'bgtfw_header_layout_custom' ).deactivate();
			}
			let $container = controlApi.control( 'bgtfw_header_layout_custom_col_width' ).container;
			$container.closest( 'body' ).find( '#customize-footer-actions button' ).on( 'click', ( event ) => {
					var device = event.currentTarget.dataset.device;

					controlApi.control( 'bgtfw_header_layout_custom_col_width' ).container.find( '.devices' ).each( ( _, deviceLabel ) => {
						if ( device === controlApi.control( 'bgtfw_header_layout_custom_col_width' ).container.find( deviceLabel ).data( 'device' ) ) {
							controlApi.control( 'bgtfw_header_layout_custom_col_width' ).container.find( deviceLabel ).trigger( 'click' );
						}
					} );
				} );

		} );
	}

	maybeUpdateSlider( oldValue, newValue ) {
		if ( oldValue === newValue ) {
			return false;
		}

		if ( oldValue.length !== newValue.length ) {
			return true;
		}

		for ( let rowIndex = 0; rowIndex < oldValue.length; rowIndex++ ) {
			if ( oldValue[ rowIndex ].items.length !== newValue[ rowIndex ].items.length ) {
				return true;
			}

			for ( let itemIndex = 0; itemIndex < oldValue[ rowIndex ].items.length; itemIndex++ ) {
				if ( oldValue[ rowIndex ].items[ itemIndex ].uid !== newValue[ rowIndex ].items[ itemIndex ].uid ) {
					return true;
				}
			}
		}

		return false;
	}

	renderHeaderPreview( headerId, markup, value ) {
		var hiddenItems = {};
		$( headerId ).find( '.boldgrid-section' ).remove();
		$( headerId ).append( markup );
		hiddenItems = this.getHiddenItems( value );
		$( '#sticky-header-display-inline-css' ).remove();
		this.hideHiddenItems( hiddenItems );
	}

	customPageHeader() {
		var $customPageHeader = $( '#masthead.template-header' );
		if ( 0 !== $customPageHeader.length ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Bind Control Changes
	 *
	 * Bind events to changing layout control values.
	 *
	 * @since SINCEVERSION
	 */
	bindControlChanges() {
		controlApi( 'bgtfw_header_layout_custom', ( control ) => {
			control.bind( ( value, oldValue ) => {
				var updateSlider = this.maybeUpdateSlider( oldValue, value );
				if ( updateSlider ) {
					controlApi.control( 'bgtfw_header_layout_custom_col_width' ).container.css( 'opacity', 0.10 );
				}
				$.ajax(
					wp.ajax.settings.url,
					{
						type: 'POST',
						context: this,
						data: {
							action: 'bgtfw_header_preset',
							headerPresetNonce: controlApi.settings.nonce['bgtfw-header-preset'],
							wpCustomize: 'on',
							customizeTheme: controlApi.settings.theme.stylesheet,
							headerPreset: 'custom',
							customHeaderLayout: value,
							columnWidths: controlApi( 'bgtfw_header_layout_custom_col_width' )()
						}
					}
				).done(
					( response ) => {
						var hiddenItems = {};
						if ( response.data.markup ) {
							$( '#masthead' ).find( '.boldgrid-section' ).remove();
							$( '#masthead' ).append( response.data.markup );
							hiddenItems = this.getHiddenItems( value );
							$( '#sticky-header-display-inline-css' ).remove();
							this.hideHiddenItems( hiddenItems );
							if ( updateSlider ) {
								this.updateSliderControl( value );
							}
						}
					}
				);
			} );
		} );

		controlApi( 'bgtfw_sticky_header_layout_custom', ( control ) => {
			control.bind( ( value ) => {
				$.ajax(
					wp.ajax.settings.url,
					{
						type: 'POST',
						context: this,
						data: {
							action: 'bgtfw_header_preset',
							headerPresetNonce: controlApi.settings.nonce['bgtfw-header-preset'],
							wpCustomize: 'on',
							customizeTheme: controlApi.settings.theme.stylesheet,
							stickyHeaderPreset: 'custom',
							customHeaderLayout: value
						}
					}
				).done(
					( response ) => {
						var hiddenItems = {};
						if ( response.data.markup ) {
							$( '#masthead-sticky' ).find( '.boldgrid-section' ).remove();
							$( '#masthead-sticky' ).append( response.data.markup );
							hiddenItems = this.getHiddenItems( value );
							$( '#sticky-header-display-inline-css' ).remove();
							this.hideHiddenItems( hiddenItems );
						}
					}
				);
			} );
		} );

		controlApi( 'bgtfw_header_layout_position', ( control ) => {
			if ( 'header-top' === controlApi( 'bgtfw_header_layout_position' )() ) {
				$( '.bgtfw-sticky-header' ).show();
			} else {
				$( '.bgtfw-sticky-header' ).hide();
			}
			control.bind( ( value ) => {
				$( 'body' ).removeClass( 'header-left header-right header-top' );
				$( 'body' ).addClass( value );
				if ( 'header-top' === value ) {
					$( '.bgtfw-sticky-header' ).show();
				} else {
					$( '.bgtfw-sticky-header' ).hide();
				}
			} );
		} );

		controlApi( 'bgtfw_header_preset', ( control ) => {
			control.bind( ( headerPreset ) => {
				var requestData = {
					action: 'bgtfw_header_preset',
					headerPresetNonce: controlApi.settings.nonce['bgtfw-header-preset'],
					wpCustomize: 'on',
					customizeTheme: controlApi.settings.theme.stylesheet,
					headerPreset: headerPreset
				};

				if ( 'custom' === headerPreset ) {
					requestData.customHeaderLayout = controlApi( 'bgtfw_header_layout_custom' )();
				} else {
					controlApi.section( 'bgtfw_header_layout_advanced' ).deactivate();
				}

				if ( this.customPageHeader() ) {
					control.notifications.add( 'customPageHeader', new controlApi.Notification(
						'customPageHeader',
						{
							type: 'warning',
							message: 'The page that you are previewing is using a Custom Page Header. Changes made will still be saved, but will not be previewed on this page.'
						}
					) );
					return;
				} else {
					control.notifications.remove( 'customPageHeader' );
				}

				$( '#masthead' ).css( 'opacity', 0.1 );

				$.ajax(
					wp.ajax.settings.url,
					{
						type: 'POST',
						data: requestData,
						context: this
					}
				).done( ( response ) => {
					var hiddenItems = {};
					if ( response.data.markup ) {
						if ( 'lshsbm' === headerPreset ) {
							controlApi.control( 'bgtfw_header_layout_position' ).setting( 'header-left' );
						} else if ( 'header-left' === controlApi( 'bgtfw_header_layout_position' )() ) {
							controlApi.control( 'bgtfw_header_layout_position' ).setting( 'header-top' );
						}
						$( '#masthead' ).find( '.boldgrid-section' ).remove();
						$( '#masthead' ).append( response.data.markup );
						$( '#sticky-header-display-inline-css' ).remove();

						hiddenItems = this.getHiddenItems( response.data.layout );
						this.hideHiddenItems( hiddenItems );
						$( '#masthead' ).css( 'opacity', 1 );
					}
				} );
			} );
		} );

		controlApi( 'bgtfw_sticky_header_preset', ( control ) => {
			control.bind( ( stickyHeaderPreset ) => {
				var requestData = {
					action: 'bgtfw_header_preset',
					headerPresetNonce: controlApi.settings.nonce['bgtfw-header-preset'],
					wpCustomize: 'on',
					customizeTheme: controlApi.settings.theme.stylesheet,
					stickyHeaderPreset: stickyHeaderPreset
				};

				if ( 'custom' === stickyHeaderPreset ) {
					controlApi.section( 'bgtfw_sticky_header_layout_advanced' ).activate();
					controlApi.control( 'bgtfw_sticky_header_layout_custom' ).focus();
					requestData.customHeaderLayout = controlApi( 'bgtfw_sticky_header_layout_custom' )();
				} else {
					controlApi.section( 'bgtfw_sticky_header_layout_advanced' ).deactivate();
				}

				$( '#masthead-sticky' ).css( 'opacity', 0.1 );

				$.ajax(
					wp.ajax.settings.url,
					{
						type: 'POST',
						data: requestData,
						context: this
					}
				).done( ( response ) => {
					var hiddenItems = {};
					if ( response.data.markup ) {
						$( '#masthead-sticky' ).find( '.boldgrid-section' ).remove();
						$( '#masthead-sticky' ).append( response.data.markup );
						$( '#sticky-header-display-inline-css' ).remove();

						hiddenItems = this.getHiddenItems( response.data.layout );
						this.hideHiddenItems( hiddenItems );
						$( '#masthead-sticky' ).css( 'opacity', 1 );
					}
				} );
			} );
		} );

		controlApi( 'bgtfw_header_preset_branding', ( control ) => {
			control.bind( ( value ) => {
				var hiddenItems = [ 'logo', 'title', 'description' ],
					showItems   = [];
				if ( 'default' !== controlApi( 'bgtfw_header_preset' ) && 'custom' !== controlApi( 'bgtfw_header_preset' ) ) {
					$( '#sticky-header-display-inline-css' ).remove();
					value.forEach( ( item ) => {
						var itemIndex = hiddenItems.indexOf( item );
						if ( -1 < itemIndex ) {
							hiddenItems.splice( itemIndex, 1 );
							showItems.push( item );
						}
						this.brandingNotices( value, controlApi.control( 'bgtfw_header_preset_branding' ) );
					} );

					showItems.forEach( ( showItem ) => {
						$( '#masthead .site-branding' ).removeClass( 'hide-' + showItem );
						$( '#masthead .site-branding' ).children().removeClass( 'invisible' );
						$( '#masthead .site-branding .site-description' ).show();
					} );

					hiddenItems.forEach( ( hiddenItem ) => {
						$( '#masthead .site-branding' ).addClass( 'hide-' + hiddenItem );
					} );
				}
			} );
		} );

		controlApi( 'bgtfw_sticky_header_preset_branding', ( control ) => {
			control.bind( ( value ) => {
				var hiddenItems = [ 'logo', 'title', 'description' ],
					showItems   = [];
				if ( 'default' !== controlApi( 'bgtfw_sticky_header_preset' ) && 'custom' !== controlApi( 'bgtfw_sticky_header_preset' ) ) {
					$( '#sticky-header-display-inline-css' ).remove();
					value.forEach( ( item ) => {
						var itemIndex = hiddenItems.indexOf( item );
						if ( -1 < itemIndex ) {
							hiddenItems.splice( itemIndex, 1 );
							showItems.push( item );
						}
						this.brandingNotices( value, controlApi.control( 'bgtfw_sticky_header_preset_branding' ) );
					} );

					showItems.forEach( ( showItem ) => {
						$( '#masthead-sticky .site-branding' ).removeClass( 'hide-' + showItem );
					} );

					hiddenItems.forEach( ( hiddenItem ) => {
						$( '#masthead-sticky .site-branding' ).addClass( 'hide-' + hiddenItem );
					} );
				}
			} );
		} );
	}

	brandingNotices( value, control ) {
		var container = control.container;
		container.find( '.branding_notice' ).hide();

		if ( value.includes( 'logo' ) && ! controlApi( 'custom_logo' )() ) {
			container.find( '.branding_notice.logo' ).show();
			container.find( '.branding_notice.logo a' ).on( 'click', ( e ) => {
				e.preventDefault();
				controlApi.control( 'custom_logo' ).focus();
			} );
		}

		if ( value.includes( 'title' ) && ! controlApi( 'blogname' )() ) {
			container.find( '.branding_notice.title' ).show();
			container.find( '.branding_notice.title a' ).on( 'click', ( e ) => {
				e.preventDefault();
				controlApi.control( 'title' ).focus();
			} );
		}

		if ( value.includes( 'description' ) && ! controlApi( 'blogdescription' )() ) {
			container.find( '.branding_notice.description' ).show();
			container.find( '.branding_notice.description a' ).on( 'click', ( e ) => {
				e.preventDefault();
				controlApi.control( 'blogdescription' ).focus();
			} );
		}
	}

	getHiddenItems( value ) {
		var hiddenItems = {},
		layout = Array.isArray( value ) ? value : [];
		layout.forEach( ( container ) => {
			container.items.forEach( ( item ) => {
				var uid,
					hidden = [];
				if ( 'branding' === item.key ) {
					uid = item.uid;
					item.display.forEach( ( displayItem ) => {
						if ( 'hide' === displayItem.display ) {
							hidden.push( displayItem.selector );
						}
					} );
				}
				if ( uid && hidden ) {
					hiddenItems[uid] = hidden;
				}
			} );
		} );

		return hiddenItems;
	}

	/**
	 * Hide Hidden Items.
	 *
	 * @param {object} hiddenItems Set of items to be hidden.
	 *
	 * @since SINCEVERSION
	 */
	hideHiddenItems( hiddenItems ) {
		let hideThisUid = ( uid ) => {
			hiddenItems[uid].forEach( ( hiddenItem ) => {
				$( '.' + uid ).find( '.site-description' ).addClass( 'invisible' );
				$( '.' + uid ).find( '.site-branding' ).find( hiddenItem ).hide();
			} );
		};

		for ( const uid in hiddenItems ) {
			$( '.' + uid ).find( '.site-branding' ).children().show();
			$( '.' + uid ).find( '.site-description' ).removeClass( 'invisible' );
			hideThisUid( uid );
		}
	}
}

export default HeaderLayout;

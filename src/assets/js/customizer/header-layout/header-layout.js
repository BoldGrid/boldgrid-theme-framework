const api = wp.customize;
const controlApi = parent.wp.customize;

export class HeaderLayout  {

	/**
	 * Class initialized.
	 *
	 * @since 2.0.0
	 *
	 * @return {Preview} Class instance.
	*/
	init() {
		this.bindExpanding();

		this.bindControlChanges();
	}

	changeColumnDevice() {
		var $container   = controlApi.control( 'bgtfw_header_layout_custom_col_width' ).container,
			$deviceLabel = $container.find( 'label' );

		$deviceLabel.on( 'click', ( e ) => {
			var $thisLabel      = $( e.currentTarget ),
				$thisInputValue = $thisLabel.siblings( 'input' ).val();

			console.log( {
				'$thisLabel': $thisLabel,
				'$thisInputValue': $thisInputValue
			} );
			$container.find( '.col-width-slider-device-group' ).hide();
			$container.find( '#bgtfw_header_layout_custom_col_width-slider-' + $thisInputValue ).show();
		} );
	}

	/**
	 * Initial Column Sliders.
	 *
	 * @since SINCEVERSION
	 */
	initialColumnSliders() {
		var $container   = controlApi.control( 'bgtfw_header_layout_custom_col_width' ).container,
				$sliders      = $container.find( '.col-width-slider' );

		parent.window.BOLDGRID.colWidthSliders = parent.window.BOLDGRID.colWidthSliders ?
			parent.window.BOLDGRID.colWidthSliders :
			{};

		$sliders.each( ( rowIndex, sliderElement ) => {
			var items = $( sliderElement ).data( 'items' ),
				sliderValues = [];

			items.forEach( ( item, index ) => {
				var width = parseInt( item.width );
				if ( 0 === index ) {
					sliderValues.push( 0, width );
				} else {
					let startValue = sliderValues[ sliderValues.length - 1 ];
					let endValue   = startValue + width;
					sliderValues.push( startValue, endValue );
				}

				parent.window.BOLDGRID.colWidthSliders[ item.uid ] = {
					row: rowIndex,
					col: index,
					key: item.key
				};
			} );

			console.log( {
				'values': sliderValues,
				'sliderElement': $container.find( sliderElement )
			} );

			let slider = $container.find( sliderElement ).multiSlider( {
				min: 0,
				max: 12,
				step: 1,
				total: items.length,
				values: sliderValues
			} );

			window._.delay( () => {
				var $slider = $container.find( slider.element );
				$container.find( '.col-width-slider-device-group' ).not( '#bgtfw_header_layout_custom_col_width-slider-large' ).hide();
				$slider.find( '.ui-slider-range' ).each( ( sliderIndex, sliderRange ) => {
					var uid = items[ sliderIndex ].uid,
						device = items[ sliderIndex ].device;
					console.log( {
						'sliderIndex': sliderIndex,
						'sliderRange': sliderRange,
						'device': device
					} );

					$container.find( sliderRange ).html( '<span class="col-width-range-label">' + items[sliderIndex].key + '</span>' );
					parent.window.BOLDGRID.colWidthSliders[ uid ][ device ] = sliderRange;
				} );
			}, 100 );
		} );
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
			if ( 'custom' === controlApi( 'bgtfw_header_preset' )() ) {
				this.initialColumnSliders();
				this.changeColumnDevice();
				controlApi.control( 'bgtfw_header_layout_custom' ).activate();
			} else {
				controlApi.control( 'bgtfw_header_layout_custom' ).deactivate();
			}
		} );
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
							headerPreset: 'custom',
							customHeaderLayout: value
						}
					}
				).done(
					( response ) => {
						var hiddenItems = {};
						console.log( { 'bgtfw_header_preset': this, 'response': response.data } );
						if ( response.data.markup ) {
							$( '#masthead' ).find( '.boldgrid-section' ).remove();
							$( '#masthead' ).append( response.data.markup );
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
					controlApi.section( 'bgtfw_header_layout_advanced' ).activate();
					controlApi.section( 'bgtfw_header_layout_advanced' ).focus();
					requestData.customHeaderLayout = controlApi( 'bgtfw_header_layout_custom' )();
				} else {
					controlApi.section( 'bgtfw_header_layout_advanced' ).deactivate();
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
					console.log( { 'bgtfw_header_preset': this, 'response': response.data } );
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
				// request = wp.ajax;
				// request.settings.context = this;
				// result = request.post(
				// 	'bgtfw_header_preset',
				// 	requestData
				// );

				// result.done( ( response ) => {
				// 	var hiddenItems = {};
				// 	if ( response.markup ) {
				// 		if ( 'lshsbm' === headerPreset ) {
				// 			controlApi.control( 'bgtfw_header_layout_position' ).setting( 'header-left' );
				// 		} else if ( 'header-left' === controlApi( 'bgtfw_header_layout_position' )() ) {
				// 			controlApi.control( 'bgtfw_header_layout_position' ).setting( 'header-top' );
				// 		}
				// 		$( '#masthead' ).find( '.boldgrid-section' ).remove();
				// 		$( '#masthead' ).append( response.markup );
				// 		$( '#sticky-header-display-inline-css' ).remove();

				// 		hiddenItems = this.getHiddenItems( response.layout );
				// 		this.hideHiddenItems( hiddenItems );
				// 		$( '#masthead' ).css( 'opacity', 1 );
				// 	}
				// } );
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
					} );

					hiddenItems.forEach( ( hiddenItem ) => {
						$( '#masthead .site-branding' ).addClass( 'hide-' + hiddenItem );
					} );
				}
			} );
		} );
	}

	brandingNotices( value, control ) {
		var container = control.container;
		container.find( '.branding_notice' ).hide();

		console.log( 'brandingNotices' );

		if ( value.includes( 'logo' ) && ! controlApi( 'custom_logo' )() ) {
			container.find( '.branding_notice.logo' ).show();
			container.find( '.branding_notice.logo a' ).on( 'click', ( e ) => {
				console.log( 'clicked' );
				e.preventDefault();
				controlApi.control( 'custom_logo' ).focus();
			} );
		}

		if ( value.includes( 'title' ) && ! controlApi( 'title' )() ) {
			container.find( '.branding_notice.title' ).show();
			container.find( '.branding_notice.title a' ).on( 'click', ( e ) => {
				console.log( 'clicked' );
				e.preventDefault();
				controlApi.control( 'title' ).focus();
			} );
		}

		if ( value.includes( 'description' ) && ! controlApi( 'blogdescription' )() ) {
			container.find( '.branding_notice.description' ).show();
			container.find( '.branding_notice.description a' ).on( 'click', ( e ) => {
				console.log( 'clicked' );
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

	/**
	 * Events to run when the Dom loads.
	 *
	 * @since 2.0.0
	 */
	onCustomizerReady() {
		console.log( {
			'customizer_ready': this,
			'api': api,
			'controlApi': controlApi
		} );
	}
}

export default HeaderLayout;

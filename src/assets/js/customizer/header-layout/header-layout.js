const controlApi = parent.wp.customize;

// Sometimes underscore is needed, when the parent / parent.window are not defined.
const underscore = parent.window._;

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

		this.socialMenuId = controlApi( 'nav_menu_locations[social]' )();

		// Handles changes in the previewed device.
		this.changeColumnDevice();

		// Bind events to expanding panels and sections.
		this.bindExpanding();

		// Bind changes to control values.
		this.bindControlChanges();

		// Bind resizing to adjust slider widths.
		this.bindResizeEvents();

		// Bind 'Full Width' column slider checkboxes.

		//this.bindFullWidth();

		// Bind to the 'Preview Ready' event to correct menu location controls.
		wp.customize.bind( 'preview-ready', () => {
			this.correctMenuLocations();
			wp.customize.preview.bind( 'sendHeaderLayout', ( args ) => {
				this.renderHeaderPreview( ...args );
			} );
		} );

		// Assign this object to the BOLDGRID object, to make it accessible to other scripts.
		parent.window.BOLDGRID.colWidths = parent.window.BOLDGRID.colWidths ?
			parent.window.BOLDGRID.colWidths :
			this;
	}

	/**
	 * Correct Menu Locations.
	 *
	 * @since 2.7.0
	 */
	correctMenuLocations() {
		var footerSocialMenu = controlApi( 'nav_menu_locations[footer-social]' )(),
			mainMenu         = controlApi( 'nav_menu_locations[main]' )(),
			socialMenu       = controlApi( 'nav_menu_locations[social]' )(),
			footerLayout     = controlApi( 'bgtfw_footer_layout' )(),
			stickySocialMenu,
			stickyMainMenu;

			/*
			 * These are wrapped in isFunction checks to prevent errors when Crio Premium
			 * is not installed as well
			 */
			if ( window._.isFunction( controlApi( 'nav_menu_locations[sticky-social]' ) ) ) {
				stickySocialMenu = controlApi( 'nav_menu_locations[sticky-social]' )();
			}

			if ( window._.isFunction( controlApi( 'nav_menu_locations[sticky-main]' ) ) ) {
				stickyMainMenu = controlApi( 'nav_menu_locations[sticky-main]' )();
			}

		/*
		 * This provides backwards compatibility to inspirations that were made before Crio 2.7.0
		 * when the footer's social menu location was just 'social'. This ensures that the new location
		 * 'footer-social' will be assigned the same menu as before.
		*/
		if ( 0 === footerSocialMenu ) {
			controlApi.control( 'nav_menu_locations[footer-social]' ).setting.set( socialMenu );
		}

		// We need to make sure that the Sticky Menus are assigned as well.
		if ( 0 === stickyMainMenu || 0 === stickySocialMenu ) {
			controlApi.control( 'nav_menu_locations[sticky-main]' ).setting.set( mainMenu );
			controlApi.control( 'nav_menu_locations[sticky-social]' ).setting.set( socialMenu );
		}

		// This changes the 'social' location in the footer to 'footer-social'.
		footerLayout.forEach( ( layoutCol ) => {
			layoutCol.items.forEach( ( item ) => {
				if ( 'boldgrid_menu_social' === item.type ) {
					item.type = 'boldgrid_menu_footer-social';
				}
			} );
		} );
	}

	/**
	 * Bind Full Width.
	 *
	 * This handles the adaption of the column widths
	 * elements when the 'full width' checkbox is selected.
	 *
	 * @since 2.7.0
	 */
	bindFullWidth() {
		var colWidthValue = controlApi( 'bgtfw_header_layout_custom_col_width' )(),
			container     = controlApi.control( 'bgtfw_header_layout_custom_col_width' ).container;

		container.find( '.col-width-full-width' ).each( ( _, controlInput ) => {
			var device    = controlInput.dataset.device,
				row       = controlInput.dataset.row,
				isChecked;

			/*
			 * When adding a new row and row item, the values for that row
			 * are not going to be defined yet, so check to be sure they are
			 * first. This resolves issue #368 as of v2.7.1
			 */
			if ( colWidthValue && 'fullWidth' in colWidthValue && undefined !== colWidthValue.fullWidth[ row ] ) {
				isChecked = colWidthValue.fullWidth[ row ][ device ];
			} else if ( 'tablet' === device || 'phone' === device ) {
				isChecked = true;
			} else {
				isChecked = false;
			}

			controlApi.control( 'bgtfw_header_layout_custom_col_width' )
				.container.find( controlInput ).prop( 'checked', isChecked );

		} );

		container.find( '.col-width-full-width' ).off( 'click' ).on( 'click', ( event ) => {
			var deviceClass = parent.window.BOLDGRID.colWidths.getDeviceClass( $( event.currentTarget ).data( 'device' ) );

			/*
				* If we actually disable the sliders here, it effects the way they
				* initialize. Therefore, the checkbox's container itself will expand to
				* cover the slider, and prevent interraction. This is handled via CSS, based on the
				* 'disabled' class.
				*/
			if ( $( event.currentTarget ).prop( 'checked' ) ) {
				$( event.currentTarget ).parent().addClass( 'disabled' );
			} else {
				$( event.currentTarget ).parent().removeClass( 'disabled' );
			}

			// We need to make sure that the values of the column sliders adjust to match.
			parent.window.BOLDGRID.colWidths.updateControlValue();

			// This is where the preview itself is updated.
			$( event.currentTarget ).parent().siblings( '.col-width-slider' ).data( 'items' ).forEach( ( item ) => {
				$( '.' + item.uid ).toggleClass( deviceClass + 'full-width' );
			} );
		} );
	}

	/**
	 * Bind Resize Events.
	 *
	 * We have to re-initialize the column width sliders when the
	 * size of the viewport changes to make sure the sliders are not
	 * cutoff on the edges.
	 *
	 * @since 2.7.0
	 */
	bindResizeEvents() {
		controlApi.bind( 'preview-ready', () => {
			$( window ).on( 'resize', _.debounce( () => {
				var $container = controlApi.control( 'bgtfw_header_layout_custom_col_width' ).container;
				$container.find( '.col-width-slider' ).children().remove();
				this.initialColumnSliders();
			}, 300 ) );
		} );
	}

	/**
	 * Change Column Device.
	 *
	 * Each device size has it's own set of column width sliders.
	 * These are all technically not 'hidden', because hiding the elements
	 * screws up the slider's calculation of the container's width. Instead, when
	 * changing devices, the height of the 'hidden' devices' set of sliders is set to 0,
	 * the 'visible' device is set to a height of 100 pixels per ROW.
	 *
	 * @since 2.7.0
	 */
	changeColumnDevice() {
		var $container   = controlApi.control( 'bgtfw_header_layout_custom_col_width' ).container,
			$deviceLabel = $container.find( '.devices-wrapper label' );

		$deviceLabel.on( 'click', ( e ) => {
			var $thisLabel      = $( e.currentTarget ),
				$thisInputValue = $thisLabel.siblings( 'input' ).val();
			$container.find( '.col-width-slider-device-group' ).height( 0 );
			let numOfRows = $container.find( '#bgtfw_header_layout_custom_col_width-slider-' + $thisInputValue ).find( '.col-width-slider' ).length;
			$container.find( '#bgtfw_header_layout_custom_col_width-slider-' + $thisInputValue ).height( 100 * numOfRows );

			// This triggers the change in the device shown in the preview, to match the one in the control.
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
	 * When changes are made to the layout, it requires the
	 * column width sliders to be updated. This is done by
	 * re-initializing the sliders using the values passed to
	 * this method.
	 *
	 * @since 2.7.0
	 *
	 * @param {Object} value The layout value used to update controls.
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

				// We must remove the existing sliders present in the control container.
				container.find( '.col-width-slider-device-group' ).remove();

				// Place the new placeholder markup for the updated sliders.
				container.find( '.sliders-wrapper' ).html( response.data.markup );

				// Initialize the updated sliders using the new placeholder markup.
				this.initialColumnSliders( true );
				this.updateControlValue();

				container.css( 'opacity', 1 );
			}
		);
	}

	/**
	 * Initial Column Sliders.
	 *
	 * The column sliders are created in two parts. The first part
	 * is done server side via PHP, which generates HTML markup containing
	 * the necessary information in the dataset of the containers. The second
	 * part is done here, where the sliders are initialized using the data
	 * contained in the markup's dataset.
	 *
	 * The values used will be pulled from this control's saved values, unless the
	 * 'forceDefaults' option is set. If defaults are forced, the column widths are
	 * set equal to 12 / the number of columns in the row.
	 *
	 * @since 2.7.0
	 *
	 * @param {bool} forceDefaults Whether or not to force defaults.
	 */
	initialColumnSliders( forceDefaults = false ) {
		var $container   = controlApi.control( 'bgtfw_header_layout_custom_col_width' ).container,
				$sliders = $container.find( '.col-width-slider' ),
				value   = controlApi( 'bgtfw_header_layout_custom_col_width' )();

		$sliders.each( ( _, sliderElement ) => {
			var items = $( sliderElement ).data( 'items' ),
				sliderValues = [];

			// Populate the BOLDGRID.colWidthSliders objects
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

				if ( colWidthSliders[ item.uid ] ) {
					colWidthSliders[ item.uid ].row = sliderElement.dataset.row;
					colWidthSliders[ item.uid ].col = index;
					colWidthSliders[ item.uid ].key = item.key;
				} else {
					colWidthSliders[ item.uid ] = {
						row: sliderElement.dataset.row,
						col: index,
						key: item.key
					};
				}
			} );

			// Initializes the jQueryUI Sliders.
			let slider = $container.find( sliderElement ).multiSlider( {
				min: 0,
				max: 12,
				step: 1,
				total: items.length,
				values: sliderValues,
				create: this.bindFullWidth,
				stop: this.bindSliderChanges
			} );

			// Adds extra labels and data to slider's datasets.
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


				if ( ! colWidthSliders[uid] ) {
					colWidthSliders[uid] = {};
				}

				colWidthSliders[ uid ][ device ] = slider;
			} );

			// Ensure only one device size is enabled at the time of initialization.
			let selectedDevice = controlApi.control( 'bgtfw_header_layout_custom_col_width' ).container.find( '.devices-wrapper input:checked' ).val();
				controlApi.control( 'bgtfw_header_layout_custom_col_width' ).container
					.find( '.col-width-slider-device-group' ).not( '#bgtfw_header_layout_custom_col_width-slider-' + selectedDevice )
					.height( 0 );
		} );
	}

	/**
	 * Get Device Class.
	 *
	 * This is used to essentially translate between the device names:
	 * large, desktop, mobile, and phone, into the bootstrap breakpoint
	 * class names, col-lg, col-md, col-sm, and col-xs.
	 *
	 * @since 2.7.0
	 * @param {string} deviceSize The size of the device to retrieve class for.
	 * @returns {string} The class selector of this device.
	 */
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
	 * The WordPress Customizer_API is designed to read
	 * the value of a single input and coorelate that value to the
	 * setting when saving. Since the column width sliders utilize multiple
	 * slider inputs, when one of them is changed, the values of all sliders need
	 * to be stored in a json string and the control's setting property must be set
	 * manually.
	 *
	 * @since 2.7.0
	 */
	updateControlValue() {
		var sliderObjects = colWidthSliders,
			valueObject   = {},
			$fullWidthControls = controlApi.control( 'bgtfw_header_layout_custom_col_width' ).container.find( '.col-width-full-width' );

		// Add each UID's width value to the valueObject.
		for ( const uid in sliderObjects ) {
			valueObject[ uid ] = {};
			for ( const device in sliderObjects[ uid ] ) {

				/*
				 * each UID also has data specifying it's col, row, and key,
				 * but we don't want those in this object.
				 */
				if ( 'col' === device || 'row' === device || 'key' === device ) {
					continue;
				}

				/*
				 * The sliders store the values of all handles on a slider
				 * in one array, as start and end indexes. For example, a slider with 2
				 * UIDs with a width of 6 columns each, would look like this:
				 * [0,6,6,12]. To get the value of each UID, we simply determine its position
				 * on the slider from it's 'col' property, and subract the start index, from the
				 * end index.
				 */
				let values = sliderObjects[ uid ][ device ].option( 'values' );
				let col    = sliderObjects[ uid ].col;
				let end    = ( ( col + 1 ) * 2 ) - 1;
				let start  = end - 1;

				valueObject[ uid ][device] = values[ end ] - values[ start ];
			}
		}

		/*
		 * Next we have to add whether or not the row's items are supposed
		 * to be full width or not. these are stored as simply booleans, keyed
		 * by row and device. ie [0][large] = false or [1][tablet] = true.
		 */
		valueObject.fullWidth = [];

		$fullWidthControls.each( ( _, fullWidthControl ) => {
			var $control = controlApi.control( 'bgtfw_header_layout_custom_col_width' ).container.find( fullWidthControl ),
				device = $control.data( 'device' ),
				checked = $control.prop( 'checked' ) ? true : false,
				row = parseInt( $control.data( 'row' ) );

				if ( ! valueObject.fullWidth[ row ] ) {
					valueObject.fullWidth[ row ] = {};
				}
				valueObject.fullWidth[ row ][ device ] = checked;
		} );

		// This is where the wp.customize setting is actually changed.
		controlApi.control( 'bgtfw_header_layout_custom_col_width' ).setting.set( valueObject );
	}

	/**
	 * Bind Slider Changes.
	 *
	 * The slider value changes are bound to the sliders' onStop
	 * event, so the changes are not triggered till after the slider
	 * handle is moved AND released.
	 *
	 * On slider value changes, we update the preview by removing the
	 * existing bootstrap column class, and replace it with the updated one.
	 *
	 * @since 2.7.0
	 *
	 * @param {Event}  event Triggering event.
	 * @param {Object} ui    The UI object.
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
	 * @since 2.7.0
	 */
	bindExpanding() {
		var customLayoutSection = controlApi.section(
			controlApi.control( 'bgtfw_header_layout_custom' ).section()
		);

		/*
		 * bgtfw_header_layout_custom
		 *
		 * When the bgtfw_header_layout_custom section expands,
		 * we have to do the following:
		 * 1. Initialize Column Sliders.
		 * 2. Activate / deactivate the custom_layout control based on which preset is active.
		 * 3. Sync device size buttons.
		 * 4. Bind the controls for the header_width slider.
		*/
		customLayoutSection.expanded.bind( () => {

			// Initialize column sliders on expand.
			this.initialColumnSliders();

			// Activate / Deactivate the custom layout controls.
			if ( 'custom' === controlApi( 'bgtfw_header_preset' )() ) {
				controlApi.control( 'bgtfw_header_layout_custom' ).activate();
			} else {
				controlApi.control( 'bgtfw_header_layout_custom' ).deactivate();
			}

			// Sync device size buttons.
			let $container = controlApi.control( 'bgtfw_header_layout_custom_col_width' ).container;
			$container.closest( 'body' ).find( '#customize-footer-actions button' ).on( 'click', ( event ) => {
				var device = event.currentTarget.dataset.device;

				controlApi.control( 'bgtfw_header_layout_custom_col_width' ).container.find( '.devices' ).each( ( _, deviceLabel ) => {
					if ( device === controlApi.control( 'bgtfw_header_layout_custom_col_width' ).container.find( deviceLabel ).data( 'device' ) ) {
						controlApi.control( 'bgtfw_header_layout_custom_col_width' ).container.find( deviceLabel ).trigger( 'click' );
					}
				} );
			} );

			// Bind the controls for the header_width slider.
			controlApi.control( 'bgtfw_header_width' ).container.find( 'input[type=range]' ).on( 'change', ( event ) => {
				var newValue = event.currentTarget.value;
				controlApi.control( 'bgtfw_header_width' ).container.find( 'span.value input' ).val( newValue );
				controlApi.control( 'bgtfw_header_width' ).container.find( 'span.value input' ).html( newValue );
			} );
		} );

		/*
		 * bgtfw_header_layouts
		 *
		 * When we expand this panel, we have to check for whether
		 * or not we should display the advanced layout section.
		 */
		controlApi.panel( 'bgtfw_header_layouts' ).expanded.bind(  ( isExpanded ) => {
			if ( isExpanded && 'custom' === controlApi( 'bgtfw_header_preset' )() ) {
				controlApi.section( 'bgtfw_header_layout_advanced' ).activate();
			} else if ( isExpanded && 'custom' !== controlApi( 'bgtfw_header_preset' )() ) {
				controlApi.section( 'bgtfw_header_layout_advanced' ).deactivate();
			}
		} );

		/*
		 * bgtfw_header_presets
		 *
		 * When the presets panel expands, we need to bind to the 'click' event
		 * of the custom preset, that way it will change focus even if it is already
		 * selected.
		 */
		controlApi.section( 'bgtfw_header_presets' ).expanded.bind( ( isExpanded ) => {
			if ( isExpanded ) {
				this.brandingNotices( controlApi( 'bgtfw_header_preset_branding' )(), controlApi.control( 'bgtfw_header_preset_branding' ) );
				controlApi.control( 'bgtfw_header_preset' ).container.find( '.bgtfw_header_presetcustom' ).on( 'click', () => {
					controlApi.section( 'bgtfw_header_layout_advanced' ).activate();
					underscore.defer( () => {
						controlApi.control( 'bgtfw_header_layout_position' ).focus();
					} );
				} );
			}

			if ( ! isExpanded && 'custom' === controlApi( 'bgtfw_header_preset' )() ) {
				controlApi.section( 'bgtfw_header_layout_advanced' ).activate();
			} else if ( ! isExpanded && 'custom' !== controlApi( 'bgtfw_header_preset' )() ) {
				controlApi.section( 'bgtfw_header_layout_advanced' ).deactivate();
			}

		} );
	}

	/**
	 * Maybe Update Slider.
	 *
	 * Compares the old and new values to decide whether the
	 * slider needs to be updated.
	 *
	 * @since 2.7.0
	 *
	 * @param {Object} oldValue Old Value.
	 * @param {Object} newValue New Value.
	 * @returns {Boolean} True if new value is different than old.
	 */
	maybeUpdateSlider( oldValue, newValue ) {
		if ( oldValue.length > newValue.length ) {
			return true;
		}

		for ( let rowIndex = 0; rowIndex < oldValue.length; rowIndex++ ) {
			for ( let itemIndex = 0; itemIndex < newValue[ rowIndex ].items.length; itemIndex++ ) {

				if ( oldValue[ rowIndex ].items.length !== newValue[ rowIndex ].items.length && 'sidebar' === newValue[ rowIndex ].items[ itemIndex ].key ) {
					controlApi.previewer.refresh();
					break;
				}
			}
		}
		if ( oldValue === newValue ) {
			return false;
		}

		if ( oldValue.length < newValue.length ) {
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

	/**
	 * Render Header Preview
	 *
	 * @since 2.7.0
	 *
	 * @param {string} headerId The ID selector of the header.
	 * @param {string} markup   The markup of the header.
	 * @param {Object} value    The layout value of the header.
	 */
	renderHeaderPreview( headerId, markup, value ) {
		var hiddenItems = {};
		$( headerId ).find( '.boldgrid-section' ).remove();
		$( headerId ).append( markup );
		hiddenItems = this.getHiddenItems( value );
		$( '#sticky-header-display-inline-css' ).remove();
		this.hideHiddenItems( hiddenItems );
	}

	/**
	 * Custom Page Headers
	 *
	 * Determines whether the current page in the preview
	 * is using a custom page header.
	 *
	 * @since 2.7.0
	 *
	 * @returns {Boolean} True if the current page uses Custom Page Headers.
	 */
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
	 * @since 2.7.0
	 */
	bindControlChanges() {
		var socialMenuSectionId = 'nav_menu[' + this.socialMenuId + ']';

		controlApi.bind( 'add', ( control ) => {
			if ( window._.isFunction( control.section ) && socialMenuSectionId === control.section() ) {
				control.setting.transport = 'refresh';
			}
		} );

		/*
		 * bgtfw_header_layout_custom
		 *
		 * When the header layout controls change,
		 * we use admin ajax to retrieve the new header
		 * layout markup. This is similar to a 'partial-refresh', however
		 * by doing it manually, we can preserve the 'header-video' content if used.
		 */
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

							// Remove the old markup.
							$( '#masthead' ).find( '.boldgrid-section' ).remove();

							// Add the new markup.
							$( '#masthead' ).append( response.data.markup );

							// get unused branding items.
							hiddenItems = this.getHiddenItems( value );

							// This inline CSS screws up the branding visiblity.
							$( '#sticky-header-display-inline-css' ).remove();

							// Hide the unused branding items.
							this.hideHiddenItems( hiddenItems );

							// If the layout changes require re-initializing the slider, do so.
							if ( updateSlider ) {
								this.updateSliderControl( value );
							}

							window.BOLDGRID.CustomizerEdit._onLoad();
						}
					}
				);
			} );
		} );

		/*
		 * bgtfw_sticky_header_layout_custom
		 *
		 * see bgtfw_header_layout_custom above.
		 */
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

		/*
		 * bgtfw_header_layout_position
		 *
		 * When the position is changed from 'top' to 'left' or 'right'
		 * the 'sticky header' controls and the header width controls
		 * need to be adjusted accordingly.
		 *
		 * This also updates the preview by adding or removing the header-top,
		 * header-left, or header-right classes from 'body' as necessary.
		 */
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
					controlApi( 'bgtfw_header_width', controlApi( 'bgtfw_header_width' )() );
					$( '.bgtfw-sticky-header' ).hide();
				}
			} );
		} );

		/*
		 * bgtfw_header_preset
		 *
		 * This is the big one. When changing the header preset,
		 * bgtfw_header_postion and bgtfw_header_layout_custom need
		 * to be updated as well.
		 *
		 * This control / preview is updated via an AJAX request
		 * to obtain the new markup for the header section.
		 */
		controlApi( 'bgtfw_header_preset', ( control ) => {
			control.bind( ( headerPreset ) => {
				var requestData = {
					action: 'bgtfw_header_preset',
					headerPresetNonce: controlApi.settings.nonce['bgtfw-header-preset'],
					wpCustomize: 'on',
					customizeTheme: controlApi.settings.theme.stylesheet,
					headerPreset: headerPreset,
					hasLogoSet: controlApi( 'custom_logo' ) ? true : false
				};

				/*
				 * If the preset is being changed to 'custom', we need to set the
				 * customHeaderLayout value so that the AJAX handler knows what layout
				 * to use for generating the markup. The header_layout_custom control's
				 * activation and focus is taken care of by it's 'click' event handler, however
				 * we still need to deactivate it here, if the preset is not 'custom'
				 */
				if ( 'custom' === headerPreset ) {
					requestData.customHeaderLayout = controlApi( 'bgtfw_header_layout_custom' )();
					controlApi.section( 'bgtfw_header_layout_advanced' ).activate();
				}

				// Display / hide notice if page is using a Custom Page Header.
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

						/*
						 * 'lshsbm' is the side header preset.
						 * when that is selected, we must update the layout position control
						 */
						if ( 'lshsbm' === headerPreset ) {
							controlApi.control( 'bgtfw_header_layout_position' ).setting( 'header-left' );
						} else if ( 'header-left' === controlApi( 'bgtfw_header_layout_position' )() ) {
							controlApi.control( 'bgtfw_header_layout_position' ).setting( 'header-top' );
						}

						controlApi.control( 'bgtfw_header_layout' ).setting( response.data.layout );

						// Remove old markup from previewer.
						$( '#masthead' ).find( '.boldgrid-section' ).remove();

						// Add new markup to previewer.
						$( '#masthead' ).append( response.data.markup );

						// get unused branding items.
						hiddenItems = this.getHiddenItems( response.data.layout );

						// This inline CSS screws up the branding visiblity.
						$( '#sticky-header-display-inline-css' ).remove();

						// Hide the unused branding items.
						this.hideHiddenItems( hiddenItems );

						this.setupCurrentMenuItem( 'main-menu' );

						$( '#masthead' ).css( 'opacity', 1 );

						window.BOLDGRID.CustomizerEdit._onLoad();
					}
				} );
			} );
		} );

		/*
		 * bgtfw_sticky_header_preset
		 *
		 * see bgtfw_header_preset for more information.
		 */
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
					underscore.defer( () => {
						controlApi.control( 'bgtfw_sticky_header_layout_custom' ).focus();
					} );
					requestData.customHeaderLayout = controlApi( 'bgtfw_sticky_header_layout_custom' )();
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
						controlApi.control( 'bgtfw_sticky_header_layout' ).setting( response.data.layout );
						hiddenItems = this.getHiddenItems( response.data.layout );
						this.hideHiddenItems( hiddenItems );
						this.setupCurrentMenuItem( 'sticky-main-menu' );
						$( '#masthead-sticky' ).css( 'opacity', 1 );
					}
				} );
			} );
		} );

		/*
		 * bgtfw_header_preset_branding
		 *
		 * When the branding visibility controls are changed
		 * we need to make sure to hide / show the correct items.
		 */
		controlApi( 'bgtfw_header_preset_branding', ( control ) => {
			control.bind( ( value ) => {
				var hiddenItems = [ 'logo', 'title', 'description' ],
					showItems   = [];

				// We do not want to use these controls for 'default' or 'custom' layouts.
				if ( 'default' === controlApi( 'bgtfw_header_preset' ) || 'custom' === controlApi( 'bgtfw_header_preset' ) ) {
					return;
				}

				// Remove this inline css. It screws up our visibility previews.
				$( '#sticky-header-display-inline-css' ).remove();

				/*
				 * Any items that should be shown, add to 'showItems'
				 * and remove from 'hiddenItems.
				 */
				value.forEach( ( item ) => {
					var itemIndex = hiddenItems.indexOf( item );
					if ( -1 < itemIndex ) {
						hiddenItems.splice( itemIndex, 1 );
						showItems.push( item );
					}

					// Display branding notices if applicable.
					this.brandingNotices( value, controlApi.control( 'bgtfw_header_preset_branding' ) );
				} );

				// Show all items in the 'showItems' array.
				showItems.forEach( ( showItem ) => {
					$( '#masthead .site-branding' ).removeClass( 'hide-' + showItem );
					$( '#masthead .site-branding' ).children().removeClass( 'invisible' );
					$( '#masthead .site-branding .site-description' ).show();
				} );

				// Hide all items in the 'hiddenItems' array.
				hiddenItems.forEach( ( hiddenItem ) => {
					$( '#masthead .site-branding' ).addClass( 'hide-' + hiddenItem );
				} );

				window.BOLDGRID.CustomizerEdit._onLoad();
			} );
		} );

		controlApi( 'blogdescription', ( control ) => {
			control.bind( () => {
				_.defer( window.BOLDGRID.CustomizerEdit._onLoad );
			} );
		} );

		/*
		 * bgtfw_sticky_header_preset_branding
		 *
		 * see bgtfw_header_preset_branding for more information.
		 */
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

	/**
	 * Branding Notices.
	 *
	 * @since 2.7.0
	 *
	 * @param {Object} value HiddenItems object.
	 * @param {wp.customize.control} control Customizer Control object.
	 */
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

	/**
	 * Customizer always marks home as the current-menu-item.  This will check the query
	 * params of the link in the menu, and compare it to the currently previewed URL and
	 * remove the .current-menu-item classes from links that don't match.  I assume this
	 * should be fixed in core at some point, or we have done something incorrect somewhere,
	 * but for the time being this works.
	 *
	 * @since 2.0.0
	 */
	setupCurrentMenuItem( menuId ) {
		var currentUrl = $( location ).attr( 'href' ).split( '?' )[0];
		$( '#' + menuId + ' > li' ).children( 'a' ).each( ( _, link ) => {
			var href = $( link ).attr( 'href' );
			if ( href === currentUrl ) {
				$( link ).parent().addClass( 'current-menu-item current_page_item' );
			}
		} );
	}

	/**
	 * Get Hidden Items.
	 *
	 * Retrieves a list of branding items that need
	 * to be hidden.
	 *
	 * @since 2.7.0
	 *
	 * @param {Object} value Layout Object
	 * @returns {Object}     Items that need to be hidden.
	 */
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
						if ( 'hide' === displayItem.display && '.custom-logo' === displayItem.selector ) {
							hidden.push( '.custom-logo-link' );
						} else if ( 'hide' === displayItem.display ) {
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
	 * @since 2.7.0
	 */
	hideHiddenItems( hiddenItems ) {
		if ( 'default' !== controlApi( 'bgtfw_header_preset' )() && 'custom' !== controlApi( 'bgtfw_header_preset' )() ) {
			return;
		}
		let hideThisUid = ( uid ) => {
			hiddenItems[uid].forEach( ( hiddenItem ) => {
				if ( '.site-description' === hiddenItem ) {
					$( '.' + uid ).find( '.site-description' ).addClass( 'invisible' );
				}
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

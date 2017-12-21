/* global kirkiControlLoader, kirkiAllFonts, kirki */
wp.customize.controlConstructor['kirki-typography'] = wp.customize.kirkiDynamicControl.extend({

	initKirkiControl: function() {

		'use strict';

		var control = this,
		    value   = control.setting._value,
		    picker;

		control.renderFontSelector();
		control.renderBackupFontSelector();
		control.renderVariantSelector();
		control.renderSubsetSelector();

		// Font-size.
		if ( control.params['default']['font-size'] ) {
			this.container.on( 'change keyup paste', '.font-size input', function() {
				control.saveValue( 'font-size', jQuery( this ).val() );
			});
		}

		// Line-height.
		if ( control.params['default']['line-height'] ) {
			this.container.on( 'change keyup paste', '.line-height input', function() {
				control.saveValue( 'line-height', jQuery( this ).val() );
			});
		}

		// Margin-top.
		if ( control.params['default']['margin-top'] ) {
			this.container.on( 'change keyup paste', '.margin-top input', function() {
				control.saveValue( 'margin-top', jQuery( this ).val() );
			});
		}

		// Margin-bottom.
		if ( control.params['default']['margin-bottom'] ) {
			this.container.on( 'change keyup paste', '.margin-bottom input', function() {
				control.saveValue( 'margin-bottom', jQuery( this ).val() );
			});
		}

		// Letter-spacing.
		if ( control.params['default']['letter-spacing'] ) {
			value['letter-spacing'] = ( jQuery.isNumeric( value['letter-spacing'] ) ) ? value['letter-spacing'] + 'px' : value['letter-spacing'];
			this.container.on( 'change keyup paste', '.letter-spacing input', function() {
				value['letter-spacing'] = ( jQuery.isNumeric( jQuery( this ).val() ) ) ? jQuery( this ).val() + 'px' : jQuery( this ).val();
				control.saveValue( 'letter-spacing', value['letter-spacing'] );
			});
		}

		// Word-spacing.
		if ( control.params['default']['word-spacing'] ) {
			this.container.on( 'change keyup paste', '.word-spacing input', function() {
				control.saveValue( 'word-spacing', jQuery( this ).val() );
			});
		}

		// Text-align.
		if ( control.params['default']['text-align'] ) {
			this.container.on( 'change', '.text-align input', function() {
				control.saveValue( 'text-align', jQuery( this ).val() );
			});
		}

		// Text-transform.
		if ( control.params['default']['text-transform'] ) {
			jQuery( control.selector + ' .text-transform select' ).selectWoo().on( 'change', function() {
				control.saveValue( 'text-transform', jQuery( this ).val() );
			});
		}

		// Color.
		if ( control.params['default'].color ) {
			picker = this.container.find( '.kirki-color-control' );
			picker.wpColorPicker({
				change: function() {
					setTimeout( function() {
						control.saveValue( 'color', picker.val() );
					}, 100 );
				}
			});
		}
	},

	/**
	 * Adds the font-families to the font-family dropdown
	 * and instantiates selectWoo.
	 */
	renderFontSelector: function() {

		var control         = this,
		    selector        = control.selector + ' .font-family select',
		    data            = [],
		    standardFonts   = [],
		    googleFonts     = [],
		    value           = control.setting._value,
		    fonts           = control.getFonts(),
		    fontSelect;

		// Format standard fonts as an array.
		if ( ! _.isUndefined( fonts.standard ) ) {
			_.each( fonts.standard, function( font ) {
				standardFonts.push({
					id: font.family.replace( /&quot;/g, '&#39' ),
					text: font.label
				});
			});
		}

		// Format google fonts as an array.
		if ( ! _.isUndefined( fonts.google ) ) {
			_.each( fonts.google, function( font ) {
				googleFonts.push({
					id: font.family,
					text: font.family
				});
			});
		}

		// Combine forces and build the final data.
		data = [
			{ text: 'Standard Fonts', children: standardFonts },
			{ text: 'Google Fonts',   children: googleFonts }
		];

		// Instantiate selectWoo with the data.
		fontSelect = jQuery( selector ).selectWoo({
			data: data
		});

		// Set the initial value.
		if ( value['font-family'] ) {
			fontSelect.val( value['font-family'].replace( /'/g, '"' ) ).trigger( 'change' );
		}

		// When the value changes
		fontSelect.on( 'change', function() {

			// Set the value.
			control.saveValue( 'font-family', jQuery( this ).val() );

			// Re-init the font-backup selector.
			control.renderBackupFontSelector();

			// Re-init variants selector.
			control.renderVariantSelector();

			// Re-init subsets selector.
			control.renderSubsetSelector();
		});
	},

	/**
	 * Adds the font-families to the font-family dropdown
	 * and instantiates selectWoo.
	 */
	renderBackupFontSelector: function() {

		var control       = this,
		    selector      = control.selector + ' .font-backup select',
		    standardFonts = [],
		    value         = control.setting._value,
		    fontFamily    = value['font-family'],
		    variants      = kirki.util.webfonts.google.getVariants( fontFamily ),
		    fonts         = control.getFonts(),
		    fontSelect;

		if ( _.isUndefined( value['font-backup'] ) || null === value['font-backup'] ) {
			value['font-backup'] = '';
		}

		// Hide if we're not on a google-font.
		if ( false !== variants ) {
			jQuery( control.selector + ' .font-backup' ).show();
		} else {
			jQuery( control.selector + ' .font-backup' ).hide();
		}

		// Format standard fonts as an array.
		if ( ! _.isUndefined( fonts.standard ) ) {
			_.each( fonts.standard, function( font ) {
				standardFonts.push({
					id: font.family.replace( /&quot;/g, '&#39' ),
					text: font.label
				});
			});
		}

		// Instantiate selectWoo with the data.
		fontSelect = jQuery( selector ).selectWoo({
			data: standardFonts
		});

		// Set the initial value.
		if ( 'undefined' !== typeof value['font-backup'] ) {
			fontSelect.val( value['font-backup'].replace( /'/g, '"' ) ).trigger( 'change' );
		}

		// When the value changes
		fontSelect.on( 'change', function() {

			// Set the value.
			control.saveValue( 'font-backup', jQuery( this ).val() );
		});
	},

	/**
	 * Renders the variants selector using selectWoo
	 * Displays font-variants for the currently selected font-family.
	 */
	renderVariantSelector: function() {

		var control    = this,
		    value      = control.setting._value,
		    fontFamily = value['font-family'],
		    variants   = kirki.util.webfonts.google.getVariants( fontFamily ),
		    selector   = control.selector + ' .variant select',
		    data       = [],
		    isValid    = false,
		    fontWeight,
		    variantSelector,
		    fontStyle;

		if ( variants ) {
			jQuery( control.selector + ' .variant' ).show();
			_.each( variants, function( variant ) {
				if ( value.variant === variant ) {
					isValid = true;
				}
				data.push({
					id: variant,
					text: variant
				});
			});
			if ( ! isValid ) {
				value.variant = 'regular';
			}

			if ( jQuery( selector ).hasClass( 'select2-hidden-accessible' ) ) {
				jQuery( selector ).selectWoo( 'destroy' );
				jQuery( selector ).empty();
			}

			// Instantiate selectWoo with the data.
			variantSelector = jQuery( selector ).selectWoo({
				data: data
			});
			variantSelector.val( value.variant ).trigger( 'change' );
			variantSelector.on( 'change', function() {
				control.saveValue( 'variant', jQuery( this ).val() );

				fontWeight = ( ! _.isString( value.variant ) ) ? '400' : value.variant.match( /\d/g );
				fontWeight = ( ! _.isObject( fontWeight ) ) ? '400' : fontWeight.join( '' );
				fontStyle  = ( -1 !== value.variant.indexOf( 'italic' ) ) ? 'italic' : 'normal';

				control.saveValue( 'font-weight', fontWeight );
				control.saveValue( 'font-style', fontStyle );
			});
		} else {
			jQuery( control.selector + ' .variant' ).hide();
		}
	},

	/**
	 * Renders the subsets selector using selectWoo
	 * Displays font-subsets for the currently selected font-family.
	 */
	renderSubsetSelector: function() {

		var control    = this,
		    value      = control.setting._value,
		    fontFamily = value['font-family'],
		    subsets    = kirki.util.webfonts.google.getSubsets( fontFamily ),
		    selector   = control.selector + ' .subsets select',
		    data       = [],
		    validValue = value.subsets,
		    subsetSelector;

		if ( false !== subsets ) {
			jQuery( control.selector + ' .subsets' ).show();
			_.each( subsets, function( subset ) {

				if ( _.isObject( validValue ) ) {
					if ( -1 === validValue.indexOf( subset ) ) {
						validValue = _.reject( validValue, function( subValue ) {
							return subValue === subset;
						});
					}
				}

				data.push({
					id: subset,
					text: subset
				});
			});

		} else {
			jQuery( control.selector + ' .subsets' ).hide();
		}

		if ( jQuery( selector ).hasClass( 'select2-hidden-accessible' ) ) {
			jQuery( selector ).selectWoo( 'destroy' );
			jQuery( selector ).empty();
		}

		// Instantiate selectWoo with the data.
		subsetSelector = jQuery( selector ).selectWoo({
			data: data
		});
		subsetSelector.val( validValue ).trigger( 'change' );
		subsetSelector.on( 'change', function() {
			control.saveValue( 'subsets', jQuery( this ).val() );
		});
	},

	/**
	 * Get fonts.
	 */
	getFonts: function() {
		var control = this;

		if ( 'undefined' !== typeof window[ 'kirkiFonts' + control.id ] ) {
			return window[ 'kirkiFonts' + control.id ];
		}
		if ( ! _.isEmpty( kirki.util.webfonts.google.fonts ) ) {
			return {
				google: kirki.util.webfonts.google.fonts,
				standard: kirkiAllFonts.standard
			};
		}
		return {
			google: [],
			standard: []
		};
	},

	/**
	 * Saves the value.
	 */
	saveValue: function( property, value ) {

		var control = this,
		    input   = control.container.find( '.typography-hidden-value' ),
		    val     = control.setting._value;

		val[ property ] = value;

		jQuery( input ).attr( 'value', JSON.stringify( val ) ).trigger( 'change' );
		control.setting.set( val );
	}
});

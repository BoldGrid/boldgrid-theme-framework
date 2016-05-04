wp.customize.controlConstructor['kirki-preset'] = wp.customize.Control.extend({

	ready: function() {

		'use strict';

		var control = this,
		    element = this.container.find( 'select' ),
		    selectValue;

		// Init selectize
		jQuery( element ).selectize();

		// Trigger a change
		this.container.on( 'change', 'select', function() {

			// Get the control's value
			selectValue = jQuery( this ).val();

			// Update the value using the customizer API and trigger the "save" button
			control.setting.set( selectValue );

			// We have to get the choices of this control
			// and then start parsing them to see what we have to do for each of the choices.
			jQuery.each( control.params.choices, function( key, value ) {

				// If the current value of the control is the key of the choice,
				// then we can continue processing, Otherwise there's no reason to do anything.
				if ( selectValue === key ) {

					// Each choice has an array of settings defined in it.
					// We'll have to loop through them all and apply the changes needed to them.
					jQuery.each( value.settings, function( presetSetting, presetSettingValue ) {
						kirkiSetSettingValue( presetSetting, presetSettingValue );
					});

				}

			});

			wp.customize.previewer.refresh();

		});

	}
});

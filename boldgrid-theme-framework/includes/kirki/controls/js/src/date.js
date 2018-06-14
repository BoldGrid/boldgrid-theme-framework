wp.customize.controlConstructor['kirki-date'] = wp.customize.kirkiDynamicControl.extend( {

	initKirkiControl: function() {
		var control  = this,
			selector = control.selector + ' input.datepicker';

		// Init the datepicker
		jQuery( selector ).datepicker( {
			dateFormat: 'yy-mm-dd'
		} );

		control.container.find( '.kirki-controls-loading-spinner' ).hide();

		// Save the changes
		this.container.on( 'change keyup paste', 'input.datepicker', function() {
			control.setting.set( jQuery( this ).val() );
		} );
	}
} );

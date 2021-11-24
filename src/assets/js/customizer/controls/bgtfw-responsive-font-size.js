const api = wp.customize;

export default () => {

	api.bind( 'ready', () => {
		var $container   = api.control( 'bgtfw_body_font_size' ).container,
			$deviceLabel = $container.find( '.devices-wrapper label' );

		$deviceLabel.on( 'click', ( e ) => {
			var $thisLabel      = $( e.currentTarget ),
				$thisInputValue = $thisLabel.siblings( 'input' ).val();

			$container.find( 'p.font-size-input' ).hide();
			$container.find( '.devices-wrapper input' ).prop( 'ckecked', false );
			$container.find( '#bgtfw_body_font_size-font-size-' + $thisInputValue ).parent().show();
			$thisLabel.siblings( 'input' ).prop( 'checked', true );

			// This triggers the change in the device shown in the preview, to match the one in the control.
			if ( 'phone' === $thisInputValue ) {
				$container.closest( 'body' ).find( 'button.preview-mobile' ).not( '.active' ).trigger( 'click' );
			} else {
				$container.closest( 'body' ).find( 'button.preview-' + $thisInputValue ).not( '.active' ).trigger( 'click' );
			}
		} );

		api.section( 'boldgrid_typography' ).expanded.bind( () => {
			var $container         = api.section( 'boldgrid_typography' ).container,
				currentDevice      = $container.find( 'devices-wrapper input:checked' ),
				$responsiveWrapper = $container.find( 'devices-wrapper input:checked' ).parents( '.boldgrid-responsive-font-size-wrapper' );

			$responsiveWrapper.find( 'p.font-size-input' ).hide();
			$responsiveWrapper.find( 'p.font-size-input ' + currentDevice ).show();
		} );
	} );
};

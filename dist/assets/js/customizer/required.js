var BOLDGRID = BOLDGRID || {};
BOLDGRID.CUSTOMIZER = BOLDGRID.CUSTOMIZER || {};

(function( $ ) {
	'use strict';

	var $window = $( window );
	var self = BOLDGRID.CUSTOMIZER.Required;
	$( function() {
		$window.on( 'boldgrid_customizer_refresh', onload_procedure );
	} );

	var onload_procedure = function() {
		$.each( BOLDGRID_Customizer_Required, function( key ) {
			if ( false === wp.customize( key )() ) {
				for ( var i = 0; i < this.length; i++ ) {
					wp.customize.control( this[ i ] ).deactivate();
				}
			}
		} );
	};
})( jQuery );


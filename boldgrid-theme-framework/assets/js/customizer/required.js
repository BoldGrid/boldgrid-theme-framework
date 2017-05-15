var BOLDGRID = BOLDGRID || {};
BOLDGRID.CUSTOMIZER = BOLDGRID.CUSTOMIZER || {};

( function( $ ) {

	'use strict';

	var $window = $( window );
	$( function() {
		$window.on( 'boldgrid_customizer_refresh', onload_procedure );
	} );

	var onload_procedure = function() {
		$.each( BOLDGRID_Customizer_Required, function( key ) {
			/*jshint eqeqeq:false */
			/*jshint -W041 */
			if ( false == wp.customize( key )() ) {
				/*jshint eqeqeq:true */
				/*jshint +W041 */
				for ( var i = 0; i < this.length; i++ ) {
					! _.isUndefined( wp.customize.control( this[ i ] ) ) && wp.customize.control( this[ i ] ).deactivate();
				}
			}
		} );
	};

})( jQuery );

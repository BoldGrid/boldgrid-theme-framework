var BOLDGRID = BOLDGRID || {};
BOLDGRID.Sass = BOLDGRID.Sass || {};

( function( $ ) {
	var self = BOLDGRID.Sass;

	self.$window = $( window );
	self.compile_done = $.Event( 'boldgrid_sass_compile_done' );
	self.processing = false;

	Sass.setWorkerUrl( BOLDGRIDSass.WorkerUrl );

	var instance_compiler = new Sass( BOLDGRIDSass.WorkerUrl );

	instance_compiler.writeFile( 'bgtfw/config-files.scss', BOLDGRIDSass.ScssFormatFileContents );

	/**
	 * Setup a compile function
	 */
	self.compile = function( scss, options ) {
		options = options || {};

		self.processing = true;

		/*
		 * var d = new Date();
		 * var start_time  = d.getTime();
		 */

		instance_compiler.compile( scss, function( result ) {
			self.processing = false;
			var data = {
				result: result,
				source: options.source
			};

			if ( result.status !== 0 ) {
				console.error( result.formatted );
			}

			self.$window.trigger( self.compile_done, data );

			/*
			 * var d = new Date();
			 * var difference  = d.getTime() - start_time;
			 * console.log( difference, " milliseconds" );
			 * console.log( result );
			 * console.log( scss );
			 */
		} );
	};

})( jQuery );

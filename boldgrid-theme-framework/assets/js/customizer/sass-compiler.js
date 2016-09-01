var BOLDGRID = BOLDGRID || {};
BOLDGRID.Sass = BOLDGRID.Sass || {};

( function( $ ) {
	var self = BOLDGRID.Sass;

	self.$window = $( window );
	self.compile_done = $.Event( 'boldgrid_sass_compile_done' );
	self.processing = false;

	Sass.setWorkerUrl( BOLDGRIDSass.WorkerUrl );

	var instance_compiler = new Sass( BOLDGRIDSass.WorkerUrl );
	var count = 0;

	/**
	 * Setup a compile function
	 */
	self.compile = function  ( scss, options ) {
		options = options || {};

		self.processing = true;

		count++;
		//After about 100 compiles error thrown on compiles
		//Hackfix to create new instance at 75
		if ( count > 75 ) {
			count = 0;
			Sass.setWorkerUrl( BOLDGRIDSass.WorkerUrl );
			if ( instance_compiler ) {
				instance_compiler.destroy();
				instance_compiler = null;
			}

			instance_compiler = new Sass( BOLDGRIDSass.WorkerUrl );
		}

		//var d = new Date();
		//var start_time  = d.getTime();

		instance_compiler.compile( scss, function( result ) {
			self.processing = false;
			var data = {
				result : result,
				source : options.source
			};

			if ( result.status !== 0 ) {
				console.error( result.formatted );
			}

			self.$window.trigger ( self.compile_done, data );

			//var d = new Date();
			//var difference  = d.getTime() - start_time;
			//console.log( difference, " milliseconds" );
			//console.log( result );
			//console.log( scss );
		} );
	};

})( jQuery );

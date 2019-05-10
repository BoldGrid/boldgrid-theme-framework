var https = require( 'https' );

var options = {
	host: 'api.github.com',
	path: '/repos/TGMPA/TGM-Plugin-Activation/releases/latest',
	port: 443,
	method: 'GET',
	headers: {
		'Content-Type': 'application/json',
		'user-agent': 'node.js'
	}
};

function setOptions( arg ) {
	Object.keys( options ).forEach( function( key ) {
		options[ key ] = arg[ key ] || options[ key ];
	} );
}

function getData( onResult ) {
	var req = https.request( options, function( res ) {
		var output = '';
		res.setEncoding( 'utf8' );

		res.on('data', function( chunk ) {
			output += chunk;
		} );

		res.on( 'end', function() {
			if ( options.headers['Content-Type'].includes( 'json' ) ) {
				output = JSON.parse( output );
			}
			onResult( res.statusCode, output );
		} );
	} );

	req.on( 'error', function( err ) {
		onResult( status, err );
	} );

	req.end();
}

module.exports = {
	setOptions: setOptions,
	getData: getData
};

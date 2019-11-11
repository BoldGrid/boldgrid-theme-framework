var https = require( 'https' );

var options = {
	host: 'api.github.com',
	path: '/repos/TGMPA/TGM-Plugin-Activation/releases/latest',
	port: 443,
	method: 'GET',
	headers: {
		'Content-Type': 'application/json',
		'user-agent': 'node.js',
	}
};

function setOptions( arg ) {
	Object.keys( options ).forEach( function( key ) {
		options[ key ] = arg[ key ] || options[ key ];
	} );

	// Headers being passed to options, and an Authorization header hasn't already been set by overriding, then check for env var passed in.
	if ( options.headers && ! options.headers['Authorization'] && process.env.GITHUB_TOKEN ) {
		options.headers['Authorization'] = `token ${ process.env.GITHUB_TOKEN }`;
	}
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

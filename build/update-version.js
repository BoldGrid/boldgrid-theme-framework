const path = require( 'path' ),
	fs = require( 'fs' );

let args = process.argv.slice( 2 );

if ( ! args ) {
	console.log( 'You must enter path to file to update version number in.' );
	process.exit( 1 );
}

let file = path.resolve( args[0] ),
	version = args[1];

fs.readFile( file, 'utf8', function( err, content ) {
	if ( err ) {
		return console.log( err );
	}
	let locate = content.replace( /\r/g, '\n' ),
		regex = /^[ \t\/*#@]*Version*?:(.*)$/gmi,
		match = regex.exec( locate );

	match = match[1].replace( /\s*(?:\*\/|\?>).*/, '' ).trim();

	content = content.replace( match, version );

	fs.writeFile( file, content, 'utf8', function( err ) {
		if ( err ) return console.log( err );
		console.log( 'Updated version in ' + args[0] + '!' );
	} );
} );

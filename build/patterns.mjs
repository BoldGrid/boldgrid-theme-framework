import fs from 'fs';
import path from 'path';
import * as hero from 'hero-patterns';

var bgtfwPatterns = {};
bgtfwPatterns.patterns = [];

for ( let pattern in hero.default ) {
	var patternData = {
		id: pattern,
		formattedName: pattern.charAt( 0 ).toUpperCase() + pattern.replace( /([A-Z])/g, ' $1' ).slice( 1 )
	}
	bgtfwPatterns.patterns.push( patternData );
}


fs.writeFile ( path.join(path.resolve( './boldgrid-theme-framework/assets'), 'json') + '/patterns.json', JSON.stringify( bgtfwPatterns ), function( err ) {
	if ( err ) throw err;
	console.log('complete');
} );

const fs = require( 'fs' ),
	path = require( 'path' ),
	engine = require( 'php-parser' ),
	glob = require( 'glob' ),
	chalk = require( 'chalk' ),
	table = require('text-table'),
	moment = require( 'moment' );

module.exports = ( pattern = '**/*.php', config = {} ) => {
	const options = Object.assign( {
		engine: {
			parser: {
				extractDoc: true,
				php7: true
			},
			ast: {
				withPositions: true
			}
		},
		domain: [ 'bgtfw', 'kirki' ],
		missingDomain: true,
		variableDomain: true,
		keywords: [
			'__:1,2d',
			'_e:1,2d',
			'_x:1,2c,3d',
			'esc_html__:1,2d',
			'esc_html_e:1,2d',
			'esc_html_x:1,2c,3d',
			'esc_attr__:1,2d',
			'esc_attr_e:1,2d',
			'esc_attr_x:1,2c,3d',
			'_ex:1,2c,3d',
			'_n:1,2,4d',
			'_nx:1,2,4c,5d',
			'_n_noop:1,2,3d',
			'_nx_noop:1,2,3c,4d',
		],
		fix: true,
		fileOpts: {
			encoding: 'utf-8',
			flag: 'r'
		},
		glob: {
			absolute: true
		},
		force: false,
		logfile: {
			create: false,
			path: __dirname,
			format: {
				prefix: 'wp-textdomain-',
				timestamp: 'YYYYMMDD-HHmmss',
				suffix: false
			}
		}
	}, config );

	console.log( chalk.magenta( 'Linting textdomains...' ) );

	const files = glob.sync( pattern, options.glob );

	if ( ! options.domain ) {
		throw new Error( options.domain );
	}

	options.domain = options.domain instanceof Array ? options.domain : [ options.domain ];

	options.fix = Boolean( options.fix );

	const
		parsedConfigs = parseConfig( options.keywords ),
		functions = parsedConfigs.functions,
		funcDomain = parsedConfigs.funcDomain,
		parser = new engine( options.engine );

	let errorCount = 0,
		errors = [];
		allErrors = {};

	files.forEach( _file => {
		let modifiedContent = '';
		if ( ! fs.existsSync( _file ) ) {
			return;
		}

		const tokens = parser.tokenGetAll( fs.readFileSync( _file, options.fileOpts ) );
		let defaultParams = {
				name: false,
				line: false,
				domain: false,
				argument: 0,
			},
			gettext = defaultParams,
			parensBalance = 0;

		for ( let i = 0; i < tokens.length; i++ ) {

			let token = tokens[ i ][0], text = tokens[ i ][1], line = tokens[ i ][2],
				content = ( 'undefined' !== typeof tokens[ i ][1] ? tokens[ i ][1] : tokens[ i ][0] );

			//Look for T_STRING (function call )
			if ( token.includes( 'T_STRING' ) && functions.indexOf( text ) > -1 ){

				gettext = {
					name: text,
					line: line,
					domain: false,
					argument: 0,
				};

				parensBalance = 0;

			//Check for T_CONSTANT_ENCAPSED_STRING - and that we are in the text-domain argument
			} else if ( token.includes( 'T_CONSTANT_ENCAPSED_STRING' ) && gettext.line && funcDomain[ gettext.name ] === gettext.argument ) {

				if ( gettext.argument > 0 ){
					gettext.domain = text.substr( 1, text.length -2 );//get rid of quotes from beginning & end

					// Fix content.
					if ( options.fix && gettext.domain !== options.domain[0] ) {
						content = `'${ options.domain[0] }'`;
					}
				}

			//Check for variable - and that we are in the text-domain argument
			} else if ( token.includes( 'T_VARIABLE' ) && gettext.line && funcDomain[ gettext.name ] === gettext.argument ) {

				if ( gettext.argument > 0 ) {
					gettext.domain = -1; //We don't know what the domain is )its a variable).

					// Fix content.
					if ( options.variableDomain && options.fix ) {
						content = `'${ options.domain[0] }'`;
					}
				}

			//Check for comma seperating arguments. Only interested in 'top level' where parensBalance == 1
			} else if ( token === ',' && parensBalance === 1 && gettext.line ) {
				gettext.argument++;

			//If we are an opening bracket, increment parensBalance
			} else if ( '(' === token && gettext.line ) {

				//If in gettext function and found opening parenthesis, we are at first argument
				if ( gettext.argument === 0 ) {
					gettext.argument = 1;
				}

				parensBalance++;

			//If in gettext function and found closing parenthesis,
			} else if ( ')' === token && gettext.line ) {
				parensBalance--;

				//If parenthesis match we have parsed all the function's arguments. Time to tally.
				if ( gettext.line && 0 === parensBalance ) {

					var errorType = false;

					if ( options.variableDomain && gettext.domain === -1 ) {
						errorType = 'variable-domain';
					} else if ( options.missingDomain && ! gettext.domain ) {
						errorType = 'missing-domain';
					} else if ( gettext.domain && gettext.domain !== -1 && options.domain.indexOf( gettext.domain ) === -1 ) {
						errorType = 'incorrect-domain';
					}

					if ( errorType ) {
						errors.push( gettext );
					}

					// Reset gettext
					gettext = defaultParams;
				}
			}

			modifiedContent += content;
		}

		//Output errors
		if ( errors.length > 0 ) {

			console.log( "\n" + chalk.underline( _file ) );

			var rows = [], error_line, func, message;

			for ( i = 0; i < errors.length; i++ ) {

				error_line = chalk`{yellow L${errors[ i ].line}}`;
				func = chalk.cyan( errors[ i ].name );

				if ( ! errors[ i ].domain ) {
					message = chalk.red( 'Missing text domain' );

				} else if ( errors[ i ].domain === -1 ) {
					message = chalk.red( 'Variable used in domain argument' );

				} else{
					message = chalk`{red Incorrect text domain used:} {reset.bold '${ errors[ i ].domain }'}`;
				}

				rows.push( [ error_line, func, message ] );
				errorCount++;
			}

			console.log( table( rows ) );

			if ( options.fix ){
				fs.writeFileSync( _file, modifiedContent );
				console.log( chalk.bold( `${_file} corrected.` ) );
			}
		}

		allErrors[ _file ] = errors;

		//Reset errors
		errors = [];
	} );


	if ( options.logFile ) {
		fs.writeFileSync( getLogFile(), JSON.stringify( allErrors ) );
	}

	if ( errorCount > 0 && ! options.force ) {
		console.log( "\n" + chalk.yellow.bold( errorCount + ' issue' + ( errorCount === 1 ? '' : 's' ) ) );
		process.exitCode = 0;
	} else if ( errorCount > 0 ) {
		console.log( "\n" + chalk.red.bold( '✖  ' + errorCount + ' issue' + ( errorCount === 1 ? '' : 's' ) ) );
		process.exitCode = 1;
	} else {
		console.log( "\n" + chalk.green.bold( '✔  No issues found!') + "\n" );
		process.exitCode = 0;
	}

	function getLogFile() {
		let userPath = '',
			stamp = {
				prefix: '',
				date: '',
				suffix: '',

			},
			extension = '.json';

		userPath = options.logfile.path ? options.logfile.path : path.resolve( glob.options.cwd, path.sep );

		stamp.prefix = options.logfile.format.prefix ? options.logfile.format.prefix : '';

		let date = moment().format( options.logfile.format.timestamp );
		stamp.date = date ? date : '';

		stamp.suffix = options.logfile.format.suffix ? options.logfile.format.suffix : '';

		let file = Object.values( stamp ).join( '' );

		if ( ! file ) {
			console.log( "\n" + chalk.red.bold( '✖  You must enter a valid filename!' ) );
			process.exit( 0 );
		}
		let str = path.join( userPath, file ) + extension;
		console.log( str );
		return str.toString();
	}

	function parseConfig( keywords ) {
		const functions = [],
			funcDomain = {},
			regex = new RegExp( '([0-9]+)d', 'i' );

		keywords.forEach( keyword => {
			const parts = keyword.split( ':' ),
				[ name ] = parts;

			let arg = 0;

			if ( parts.length > 1 ) {
				const args = parts[1],
					argParts = args.split( ',' );

				for ( let i = 0; i < argParts.length; i++ ) {
					if ( regex.test( argParts[ i ] ) ) {
						arg = parseInt( regex.exec( argParts[ i ] ), 10 );
						break;
					}
				}

				arg = arg ? arg : argParts.length + 1;
			}

			arg = arg ? arg : 2;
			funcDomain[ name ] = arg;
			functions.push( name );
		} );

		return { functions: functions, funcDomain: funcDomain };
	}
};

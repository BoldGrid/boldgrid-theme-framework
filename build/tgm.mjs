const
	chalk = require( 'chalk' ),
	repo = require( './modules/github-request' ),
	fs = require( 'fs' ),
	path = require( 'path' ),

	options = {
		slug: 'bgtfw',
		prefix: 'bgtfw',
		addonName: 'Bgtfw',
		addonType: 'parent-theme',
		publishType: 'wporg',
		installPath: path.join( path.resolve( './boldgrid-theme-framework/includes' ), 'tgm' ),
		files: [ 'class-tgm-plugin-activation.php', 'LICENSE.md' ],
		calls: {
			latestVersion: {
				host: 'api.github.com',
				path: '/repos/TGMPA/TGM-Plugin-Activation/releases/latest',
				post: 443,
				method: 'GET',
				headers: {
					'Content-Type': 'application/json',
					'user-agent': 'node.js'
				}
			},
			getFile: {
				host: 'raw.githubusercontent.com',
				method: 'GET',
				headers: {
					'Content-Type': 'text/html',
					'user-agent': 'node.js'
				}
			}
		}
	};

let errorCount = 0,
	finished = 0;

console.log( chalk.cyanBright( '\nInstalling TGM Plugin Activation Library...\n' ) );
repo.setOptions( options.calls.latestVersion );
repo.getData( ( status, response ) => {
	if ( 200 === status ) {
		console.log( chalk`{blue   🛈}  {blue Latest Version:} {bold ${response.tag_name}}\n` );
		options.files.map( file => {
			getFile( response.tag_name, file );
		} );
	} else {
		errorMsg( 'Unable to retrieve latest version information from github!', status, response.message );
	}
} );



function getFile( tag, file ) {
	let url = { path: '/TGMPA/TGM-Plugin-Activation/' + tag + '/' + file };
	repo.setOptions( Object.assign( options.calls.getFile, url ) );
	repo.getData( ( status, response ) => {
		if ( 200 === status ) {
			if ( url.path.includes( '.php' ) ) {
				response = updateContent( response );
			}
			writeFile( response, file );
		} else {
			errorMsg( 'Unable to retrieve file: ' + file, status, response.message );
		}
	} );
}

function writeFile( content, file ) {
	let fileName = file;
	file = options.installPath + path.sep + file;
	fs.writeFile( file, content, function( err ) {
		if ( err ) throw err;
		console.log( chalk`{greenBright   ✓  Installed file:} ${fileName}` );
		updateCounter();
	} );
}

function updateCounter() {
	finished++;
	if ( options.files.length == finished ) {
		if ( ! errorCount ) {
			console.log( chalk.greenBright( '\nSuccessfully Installed TGM Plugin Activation Library!\n' ) );
		} else {
			let s = errorCount > 1 ? 's' : '';
			let is = '' === s ? 'is' : 'are';
			console.log( chalk`
{red Installation of TGM Plugin Activation Library Failed! }
 - There ${is} {bold ${errorCount} error${s}} marked above.\n` );
		}
	}
}

function errorMsg( reason, status, error ) {
	if ( error ) console.log( error );
	errorCount++;
	console.log( chalk`  {bgRed.white.bold.dim   error  } ${status}: ${reason}` );
	updateCounter();
}

function updateContent( content ) {
	if ( 'other' !== options.publishType && ( 'parent-theme' === options.addonType || 'child-theme' === options.addonType ) ) {
		content = replaceAddAdminMenuFunction( content );
		if ( 'wporg' === options.publishType ) {
			content = removeLoadTextDomainFunctions( content );
			content = replaceTextDomain( 'tgmpa', options.slug, content );
		}
		content = addGeneratorUseIndicator( content, options.addonName, options.addonType, options.publishType );
	}
	return content;
}

function reEscape( str ) {
	return str.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
}

function addGeneratorUseIndicator( content, addonName, addonType, publishType ) {
	var replacement = ' for ' + addonType.replace( /-/g, ' ' ) + ' ' + addonName;
	if ( 'wporg' === publishType ) {
		replacement += ' for publication on WordPress.org';
	} else if ( 'themeforest' === publishType ) {
		replacement += ' for publication on ThemeForest';
	}

	return content.replace( /(\* @version\s+[0-9\.]+)([\r\n]+)/, '$1' + replacement + '$2' );
}

function replaceTextDomain( searchString, replacement, content ) {
	var reBracketsA, reBracketsB, reNoBrackets;
	searchString = reEscape( searchString );
	searchString = searchString.replace( /\\-/g, '-' );
	replacement = '$1' + replacement + '$2';
	reBracketsA = new RegExp( "((?:_[_enx]|_[en]x|_n[x]?_noop|__ngettext_noop|translate_nooped_plural)\\((?:[^\\)]+%s\\)){2}[^\\)]+,\\s+')" + searchString + "('\\s+\\))", 'g' );
	reBracketsB = new RegExp( "((?:_[_enx]|_[en]x|_n[x]?_noop|__ngettext_noop|translate_nooped_plural)\\([^\\)]+?%1\\$s \\(%2\\$d/%3\\$d\\)',\\s+')" + searchString + "('\\s+\\))", 'g' );
	reNoBrackets = new RegExp( "((?:_[_enx]|_[en]x|_n[x]?_noop|__ngettext_noop|translate_nooped_plural)\\((?:\\s+\\/\\* translators: [^*]+)?[^\\)]+,\\s+')" + searchString + "('\\s+\\))", 'g' );
	content = content.replace( reBracketsA, replacement );
	content = content.replace( reBracketsB, replacement );
	content = content.replace( reNoBrackets, replacement );

	return content;
}

function replaceAddAdminMenuFunction( content ) {
	var re, replacement;
	re = /(protected function add_admin_menu\([^\)]*\) \{\s+)(?:[^\}]+\}){3}(\s+})/;
	replacement = "$1$this->page_hook = add_theme_page( $args['page_title'], $args['menu_title'], $args['capability'], $args['menu_slug'], $args['function'] );$2";

	return content.replace( re, replacement );
}

function removeLoadTextDomainFunctions(content) {
	var reHookIns, reLoadFunction, reOverloadFunctionA, reOverloadFunctionB, replacement = "";
	reHookIns = /[\t]+\/\*(?:[^\*]+\*)+\/\s+add_action\( 'init', array\( \$this, 'load_textdomain' \)[^)]*\);\s+add_filter\( 'load_textdomain_mofile'[^\r\n]+/;
	reLoadFunction = /[\t]+\/[\*]{2}(?:[^*]+\*)+\/\s+public function load_textdomain\(\) \{(?:[^\}]+\}){4}/;
	reOverloadFunctionA = /[\t]+\/[\*]{2}(?:[^*]+\*)+\/\s+public function correct_plugin_mofile\([^\)]*\) \{(?:[^\}]+\}){4}/;
	reOverloadFunctionB = /[\t]+\/[\*]{2}(?:[^*]+\*)+\/\s+public function overload_textdomain_mofile\([^\)]*\) \{(?:[^\}]+\}){5}/;
	content = content.replace( reHookIns, replacement );
	content = content.replace( reLoadFunction, replacement );
	content = content.replace( reOverloadFunctionA, replacement );
	content = content.replace( reOverloadFunctionB, replacement );

	return content
}

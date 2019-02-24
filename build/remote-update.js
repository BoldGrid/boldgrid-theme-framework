#!/usr/bin/env node
"use strict";

const shell = require( 'shelljs' ),
	path = require( 'path' ),
	got = require( 'got' );

console.log( 'Fetching Git commit hash...' );

const currentDir = {
	cwd: path.join( __dirname, '..' )
};

const gitCommitRet = shell.exec( 'git rev-parse HEAD', currentDir );

if ( 0 !== gitCommitRet.code ) {
	console.log( 'Error getting git commit hash' );
	process.exit();
}

const gitCommitHash = gitCommitRet.stdout.trim();

let gitDetails = shell.exec( `git log ${ gitCommitHash } --format='%an,%ae'^!`, currentDir );

let gitAuthor = 'BoldGrid';
let gitEmail = 'pdt@boldgrid.com';

if ( 0 === gitDetails.code ) {
	gitDetails = gitDetails.stdout.trim().split( ',' );
	gitAuthor = gitDetails[0];
	gitEmail = gitDetails[1];
}

console.log( `BoldGrid/Prime build triggering from git commit: ${gitCommitHash}` );
console.log( 'Calling Travis...' );

got.post( 'https://api.travis-ci.org/repo/BoldGrid%2Fprime/requests', {
	headers: {
		"Content-Type": "application/json",
		"Accept": "application/json",
		"Travis-API-Version": "3",
		"Authorization": `token ${ process.env.TRAVIS_API_TOKEN }`,
	},
	body: JSON.stringify( {
		request: {
			message: `BGTFW auto-built from commit: ${gitCommitHash}`,
			branch: master,
			config: {
				merge_mode: 'deep_merge',
				env: {
					BGTFW_AUTO_UPDATE_TAG: process.env.TRAVIS_TAG,
					BGTFW_AUTO_UPDATE_AUTHOR: gitAuthor,
					BGTFW_AUTO_UPDATE_EMAIL: gitEmail
				},
				script: 'node bin/tag.js style.css ${BGTFW_AUTO_UPDATE_TAG}'
			}
		},
	} ),
} )

.then( () => {
	console.log( 'Triggered BoldGrid/prime build and deployment.' );
	process.exit();
} )

.catch( err => {
	console.log( err );
	console.log( 'BoldGrid/prime auto deployment failed!  You should manually build and release this repo.' );
	process.exit();
} );

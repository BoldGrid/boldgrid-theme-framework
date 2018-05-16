const path = require( 'path' );
const webpack = require( 'webpack' );

module.exports = {
	mode: 'production',

	context: path.resolve( __dirname, 'src' ),

	entry: {
		customizer: './assets/js/customizer/customizer.js'
	},

	output: {
		filename: './assets/js/customizer/[name].min.js',
		path: path.resolve( __dirname, 'boldgrid-theme-framework' )
	},

	module: {
		rules: [
			{
				test: /\.ejs$/,
				loader: 'ejs-loader'
			},
			{
				test: /\.html$/,
				use: [
					{
						loader: 'html-loader',
						options: {
							minimize: true
						}
					}
				]
			},
			{
				test: /\.js$/,
				use: [ 'babel-loader' ]
			}

			// Disabled eslint because everything was failing.
			/*{
				test: /\.js$/,
				enforce: 'pre',
				exclude: /node_modules/,
				loader: 'eslint-loader',
				options: {
					emitWarning: true
				}
			},*/
		]
	}
};

const webpack = require( 'webpack' );
const path = require( 'path' );
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );

module.exports = {
	mode: process.env.NODE_ENV || 'production',

	context: path.resolve( __dirname, 'src' ),

	entry: {
		customizer: './assets/js/customizer/customizer.js',
		'base-controls': './assets/js/customizer/base-controls.js'
	},

	output: {
		filename: './assets/js/customizer/[name].min.js',
		path: path.resolve( __dirname, 'boldgrid-theme-framework' )
	},

	externals: {
		jquery: 'jQuery'
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
				test: /\.svg$/,
				loader: 'svg-inline-loader'
			},
			{
				test: /\.s?[ac]ss$/,
				use: [
					MiniCssExtractPlugin.loader,
					'css-loader',

					// 'postcss-loader',
					{
						loader: 'sass-loader',
						options: {
							includePaths: [
								path.resolve( __dirname, 'node_modules' )
							]
						}
					}
				]
			},
			{
				test: /\.js$/,
				use: {
					loader: 'babel-loader',
					options: {
						presets: [ '@babel/preset-env' ]
					}
				}
			},
			{
				test: /\.js$/,
				enforce: 'pre',
				exclude: /node_modules/,
				loader: 'eslint-loader',
				options: {
					emitWarning: true
				}
			}
		]
	},
	plugins: [
		new MiniCssExtractPlugin( {
			filename: './assets/css/[name]-bundle.min.css'
		} ),

		new webpack.ProvidePlugin( {
			$: 'jquery',
			jQuery: 'jquery'
		} )
	]
};

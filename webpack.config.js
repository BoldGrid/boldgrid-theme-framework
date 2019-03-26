const webpack = require( 'webpack' );
const FriendlyErrors = require( 'friendly-errors-webpack-plugin' );
const path = require( 'path' );
const src = path.resolve( __dirname, 'src' );
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );
const requireDesired = require( './build/modules/require-desired.js' );
const merge = require( 'webpack-merge' );
const localConfig = merge( requireDesired( `${__dirname}/build/config` ), requireDesired( `${__dirname}/build/config.local` ) );
const RuntimePath = require( 'webpack-runtime-public-path-plugin' );

if ( 'development' === localConfig.mode ) {
	process.env.NODE_ENV = 'development';
}

const webpackConfig = merge( {
	mode: process.env.NODE_ENV || 'production',
	context: src,
	output: {
		path: path.resolve( __dirname, 'boldgrid-theme-framework' ),
		publicPath: '/wp-content/themes/prime/inc/boldgrid-theme-framework/'
	},
	devServer: {
		publicPath: '/wp-content/themes/prime/inc/boldgrid-theme-framework/',
		contentBase: '/wp-content/themes/prime/inc/boldgrid-theme-framework/',
		historyApiFallback: true,
		quiet: true,
		port: 4009,
		overlay: {
			errors: true,
			warnings: true
		}
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
		new RuntimePath( {
			runtimePublicPath: 'window.BGTFW.assets'
		} ),
		new MiniCssExtractPlugin( {
			filename: './assets/css/[name]-bundle.min.css'
		} ),
		new webpack.ProvidePlugin( {
			$: 'jquery',
			jQuery: 'jquery',
			'window.jQuery': 'jquery'
		} ),
		new FriendlyErrors()
	]
}, localConfig );

module.exports = webpackConfig;

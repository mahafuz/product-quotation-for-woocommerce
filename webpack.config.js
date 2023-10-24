const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = {
	entry: {
		backend: path.resolve( __dirname, 'quote_packer/backend.js' ),
		frontend: path.resolve( __dirname, 'quote_packer/frontend.js')
	}, // Your entry point for React
	output: {
		path: path.resolve(__dirname, 'build'),
		filename: `[name].js`, // Output JavaScript to 'dist/js' folder
	},
	module: {
		rules: [
			{
				test: /\.js|jsx$/,
				exclude: /node_modules/,
				use: {
					loader: 'babel-loader',
				},
			},
			{
				test: /\.(png|jpg|jpeg|gif|svg)$/,
        		use: {
					loader: 'url-loader',
					options: {
						limit: 8192, // Convert images < 8kb to base64 strings in the bundle
						name: 'images/[name].[ext]',
					},
				},
			},
			{
				test: /\.scss|.css$/,
				use: [MiniCssExtractPlugin.loader, 'css-loader', 'postcss-loader', 'sass-loader'],
			},
		],
	},
	plugins: [
		new MiniCssExtractPlugin({
			filename: 'css/style.css', // Output CSS to 'dist/css' folder
		}),
	],
	resolve: {
		extensions: ['.js', '.jsx'],
	},
};

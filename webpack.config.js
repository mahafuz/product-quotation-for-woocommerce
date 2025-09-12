const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const path = require('path');

const PRODUCT_QUOTATION_VERSION = '2.5.0';

const config = {
	...defaultConfig,
	entry: {
		backend: path.resolve(__dirname, 'src/backend.js'),
		button: path.resolve(__dirname, 'src/button.js'),
		cart: path.resolve(__dirname, 'src/cart.js'),
	},
	output: {
		filename: `[name].${PRODUCT_QUOTATION_VERSION}.js`,
		path: path.resolve(__dirname, 'assets/build'),
	},
	plugins: [...defaultConfig.plugins, new CleanWebpackPlugin()],
	resolve: {
		alias: {
			...defaultConfig.resolve.alias,
			'@src': path.resolve(__dirname, 'src/'),
			'@Components': path.resolve(__dirname, 'src/components/'),
			'@Containers': path.resolve(__dirname, 'src/containers/'),
			'@Global': path.resolve(__dirname, 'src/global/'),
			'@Utils': path.resolve(__dirname, 'src/utils/'),
			'@Assets': path.resolve(__dirname, 'src/assets/'),
			'@Redux': path.resolve(__dirname, 'src/redux/'),
		},
	},
	watchOptions: {
		ignored: /assets\/build/,
	},
};

module.exports = config;

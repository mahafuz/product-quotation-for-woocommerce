const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const path = require('path');

const PRODUCT_QUOTATION_VERSION = '2.5.0';

const config = {
	...defaultConfig,
	entry: {
		// SPA / Admin Dashboard
		backend: path.resolve(__dirname, 'src/backend/dashboard.js'),

		// Frontend (storefront)
		button: path.resolve(__dirname, 'src/frontend/button.js'),
		cart: path.resolve(__dirname, 'src/frontend/cart.js'),
		form: path.resolve(__dirname, 'src/frontend/form.js'),

		// Admin common utilities
		//admin: path.resolve(__dirname, 'src/common/quotify-admin-common.js'),
	},
	output: {
		filename: `[name].${PRODUCT_QUOTATION_VERSION}.js`,
		path: path.resolve(__dirname, 'assets/build'),
	},
	optimization: {
		...defaultConfig.optimization,
		splitChunks: false,
		runtimeChunk: false,
	},
	plugins: [...defaultConfig.plugins, new CleanWebpackPlugin()],
	resolve: {
		alias: {
			...defaultConfig.resolve.alias,
			'@src': path.resolve(__dirname, 'src/'),
			'@Components': path.resolve(__dirname, 'src/components/'),
			'@Containers': path.resolve(__dirname, 'src/containers/'),
			'@Global': path.resolve(__dirname, 'src/global/'),
			'@Utils': path.resolve(__dirname, 'src/Utils/'),
			'@Assets': path.resolve(__dirname, 'src/assets/'),
			'@Redux': path.resolve(__dirname, 'src/redux/'),
			'@Images': path.resolve(__dirname, 'src/images/'),
			'@Scss': path.resolve(__dirname, 'src/scss/'),
		},
	},
	watchOptions: {
		ignored: /assets\/build/,
	},
};

module.exports = config;

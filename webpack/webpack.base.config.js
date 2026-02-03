const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const path = require('path');

const PRODUCT_QUOTATION_VERSION = '2.5.0';

// Go up one level since this file is in webpack/ directory
const rootPath = path.resolve(__dirname, '..');

const baseConfig = {
	...defaultConfig,
	output: {
		filename: `[name].${PRODUCT_QUOTATION_VERSION}.js`,
		path: path.resolve(rootPath, 'assets/build'),
	},
	optimization: {
		...defaultConfig.optimization,
		splitChunks: false,
		runtimeChunk: false,
	},
	resolve: {
		alias: {
			...defaultConfig.resolve.alias,
			'@src': path.resolve(rootPath, 'src/'),
			'@Components': path.resolve(rootPath, 'src/components/'),
			'@Containers': path.resolve(rootPath, 'src/containers/'),
			'@Global': path.resolve(rootPath, 'src/global/'),
			'@Utils': path.resolve(rootPath, 'src/Utils/'),
			'@Assets': path.resolve(rootPath, 'src/assets/'),
			'@Redux': path.resolve(rootPath, 'src/redux/'),
			'@Images': path.resolve(rootPath, 'src/images/'),
			'@Scss': path.resolve(rootPath, 'src/scss/'),
		},
	},
	watchOptions: {
		ignored: /assets\/build/,
	},
};

module.exports = baseConfig;

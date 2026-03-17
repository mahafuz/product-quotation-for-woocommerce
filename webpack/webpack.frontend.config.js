const baseConfig = require('./webpack.base.config');
const path = require('path');

// Go up one level since this file is in webpack/ directory
const rootPath = path.resolve(__dirname, '..');

module.exports = {
	...baseConfig,
	name: 'frontend',
	entry: {
		button: path.resolve(rootPath, 'src/frontend/button.js'),
		cart: path.resolve(rootPath, 'src/frontend/cart.js'),
		form: path.resolve(rootPath, 'src/frontend/form.js'),
	},
	externals: {
		'@wordpress/i18n': 'wp.i18n',
	},
};

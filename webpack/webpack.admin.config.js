const baseConfig = require('./webpack.base.config');
const path = require('path');

// Go up one level since this file is in webpack/ directory
const rootPath = path.resolve(__dirname, '..');

module.exports = {
	...baseConfig,
	name: 'admin',
	entry: {
		admin: path.resolve(rootPath, 'src/common/quotify-admin-common.js'),
	},
};
